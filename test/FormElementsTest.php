<?php

namespace XtreamwayzTest\HTMLFormValidator;

use Xtreamwayz\HTMLFormValidator\FormFactory;
use Xtreamwayz\HTMLFormValidator\ValidationResult;

class FormElementsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider getIntegrationTests
     */
    public function testIntegration(
        $htmlForm,
        $defaultValues,
        $submittedValues,
        $expectedValues,
        $expectedForm,
        $expectedErrors,
        $expectedException
    ) {
        if ($expectedException) {
            $this->expectException($expectedException);
        }

        $form = FormFactory::fromHtml($htmlForm, $defaultValues);
        $result = $form->validate($submittedValues);

        $this->assertInstanceOf(ValidationResult::class, $result);
        $this->assertEquals(
            $submittedValues,
            $result->getRawValues(),
            "Failed asserting submitted values are equal."
        );
        $this->assertEquals($expectedValues, $result->getValues(), "Failed asserting filtered values are equal.");

        if ($expectedForm) {
            $this->assertEqualForms($expectedForm, $form->asString($result));
        }

        if (empty($expectedErrors) && empty($result->getMessages())) {
            $this->assertTrue($result->isValid(), "Failed asserting the validation result is valid.");
        } else {
            $this->assertFalse($result->isValid(), "Failed asserting the validation result is invalid.");
        }

        $this->assertEmpty(
            $this->arrayDiff($expectedErrors, $result->getMessages()),
            "Failed asserting that expected and actual messages are equal."
        );
    }

    private function arrayDiff($array1, $array2)
    {
        $result = [];

        if ($res = array_merge(array_diff_key($array1, $array2), array_diff_key($array2, $array1))) {
            $result = $res;
        }

        foreach ($array1 as $key => $val) {
            if (is_array($val) && isset($array2[$key])) {
                if ($res = array_merge(array_diff_key($val, $array2[$key]), array_diff_key($array2[$key], $val))) {
                    $result[$key] = $res;
                } elseif ($res = $this->arrayDiff($val, $array2[$key])) {
                    $result[$key] = $res;
                }
            } elseif (!isset($array2[$key])) {
                $result[$key] = $val;
            }
        }

        return $result;
    }

    public function getIntegrationTests()
    {
        $fixturesDir = realpath(__DIR__ . '/Fixtures/');

        foreach (new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($fixturesDir),
            \RecursiveIteratorIterator::LEAVES_ONLY
        ) as $file) {
            if (!preg_match('/\.test$/', $file)) {
                continue;
            }

            $testData = $this->readTestFile($file, $fixturesDir);

            $defaultValues = [];
            $submittedValues = [];
            $expectedValues = [];
            $expectedForm = '';
            $expectedErrors = [];
            $expectedException = '';

            try {
                $htmlForm = $testData['HTML-FORM'];

                if (!empty($testData['DEFAULT-VALUES'])) {
                    $defaultValues = json_decode($testData['DEFAULT-VALUES'], true);
                }

                if (!empty($testData['SUBMITTED-VALUES'])) {
                    $submittedValues = json_decode($testData['SUBMITTED-VALUES'], true);
                }

                if (!empty($testData['EXPECTED-VALUES'])) {
                    $expectedValues = json_decode($testData['EXPECTED-VALUES'], true);
                }

                if (!empty($testData['EXPECTED-FORM'])) {
                    $expectedForm = $testData['EXPECTED-FORM'];
                }

                if (!empty($testData['EXPECTED-ERRORS'])) {
                    $expectedErrors = json_decode($testData['EXPECTED-ERRORS'], true);
                }

                if (!empty($testData['EXPECTED-EXCEPTION'])) {
                    $expectedException = trim($testData['EXPECTED-EXCEPTION']);
                }
            } catch (\Exception $e) {
                die(sprintf('Test "%s" is not valid: ' . $e->getMessage(), str_replace($fixturesDir . '/', '', $file)));
            }

            yield basename($file) => [
                $htmlForm,
                $defaultValues,
                $submittedValues,
                $expectedValues,
                $expectedForm,
                $expectedErrors,
                $expectedException,
            ];
        }
    }

    protected function readTestFile(\SplFileInfo $file, $fixturesDir)
    {
        $tokens = preg_split(
            '#(?:^|\n*)--([A-Z-]+)--\n#',
            file_get_contents($file->getRealPath()),
            null,
            PREG_SPLIT_DELIM_CAPTURE
        );

        $sectionInfo = [
            'TEST'               => true,
            'HTML-FORM'          => true,
            'DEFAULT-VALUES'     => false,
            'SUBMITTED-VALUES'   => false,
            'EXPECTED-VALUES'    => false,
            'EXPECTED-FORM'      => false,
            'EXPECTED-ERRORS'    => false,
            'EXPECTED-EXCEPTION' => false,
        ];

        $data = [];
        $section = null;
        foreach ($tokens as $i => $token) {
            if (null === $section && empty($token)) {
                continue; // skip leading blank
            }

            if (null === $section) {
                if (!isset($sectionInfo[$token])) {
                    throw new \RuntimeException(sprintf(
                        'The test file "%s" must not contain a section named "%s".',
                        str_replace($fixturesDir . '/', '', $file),
                        $token
                    ));
                }
                $section = $token;
                continue;
            }

            $sectionData = $token;
            $data[$section] = $sectionData;
            $section = $sectionData = null;
        }

        foreach ($sectionInfo as $section => $required) {
            if ($required && !isset($data[$section])) {
                throw new \RuntimeException(sprintf(
                    'The test file "%s" must have a section named "%s".',
                    str_replace($fixturesDir . '/', '', $file),
                    $section
                ));
            }
        }

        return $data;
    }

    private function assertEqualForms($expected, $actual)
    {
        $this->assertEquals(
            $this->getDomDocument($expected),
            $this->getDomDocument($actual),
            'Failed asserting that the form is rendered correctly.'
        );
    }

    private function getDomDocument($html)
    {
        $doc = new \DOMDocument('1.0', 'utf-8');
        $doc->preserveWhiteSpace = false;

        // Don't add missing doctype, html and body
        //libxml_use_internal_errors(true);
        $doc->loadHTML($html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD | LIBXML_NOBLANKS);
        //libxml_use_internal_errors(false);

        // Remove whitespace for better comparison
        return preg_replace('~\s+~i', ' ', $doc->saveHTML());
    }
}
