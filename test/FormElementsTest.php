<?php
/**
 * html-form-validator (https://github.com/xtreamwayz/html-form-validator)
 *
 * @see       https://github.com/xtreamwayz/html-form-validator for the canonical source repository
 * @copyright Copyright (c) 2016 Geert Eltink (https://xtreamwayz.com/)
 * @license   https://github.com/xtreamwayz/html-form-validator/blob/master/LICENSE.md MIT
 */

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

        $form   = FormFactory::fromHtml($htmlForm, $defaultValues);
        $result = $form->validate($submittedValues);

        self::assertInstanceOf(ValidationResult::class, $result);
        self::assertEquals(
            $submittedValues,
            $result->getRawValues(),
            'Failed asserting submitted values are equal.'
        );
        self::assertEquals($expectedValues, $result->getValues(), 'Failed asserting filtered values are equal.');

        if ($expectedForm) {
            self::assertEqualForms($expectedForm, $form->asString($result));
        }

        if (count($expectedErrors) === 0 && count($result->getMessages()) === 0) {
            self::assertTrue($result->isValid(), 'Failed asserting the validation result is valid.');
        } else {
            self::assertFalse($result->isValid(), 'Failed asserting the validation result is invalid.');
        }

        self::assertEmpty(
            $this->arrayDiff($expectedErrors, $result->getMessages()),
            'Failed asserting that messages are equal.'
        );
    }

    private function arrayDiff(array $array1, array $array2)
    {
        $result = [];

        if ($res = array_merge(array_diff_key($array1, $array2), array_diff_key($array2, $array1))) {
            $result = $res;
        }

        foreach ($array1 as $key => $val) {
            if (! is_array($val)) {
                continue;
            }

            if (! array_key_exists($key, $array2)) {
                $result[$key] = $val;
                continue;
            }

            if ($res = array_merge(array_diff_key($val, $array2[$key]), array_diff_key($array2[$key], $val))) {
                $result[$key] = $res;
            } elseif ($res = $this->arrayDiff($val, $array2[$key])) {
                $result[$key] = $res;
            }
        }

        return $result;
    }

    public function getIntegrationTests()
    {
        $fixturesDir = realpath(__DIR__ . '/Fixtures/');

        $rdi = new \RecursiveDirectoryIterator($fixturesDir);
        $rii = new \RecursiveIteratorIterator($rdi, \RecursiveIteratorIterator::LEAVES_ONLY);
        foreach ($rii as $name => $file) {
            if (! preg_match('/\.test$/', $name)) {
                continue;
            }

            $testData = $this->readTestFile($file, $fixturesDir);

            $defaultValues     = [];
            $submittedValues   = [];
            $expectedValues    = [];
            $expectedForm      = '';
            $expectedErrors    = [];
            $expectedException = '';

            try {
                $htmlForm = $testData['HTML-FORM'];

                if (! empty($testData['DEFAULT-VALUES'])) {
                    $defaultValues = json_decode($testData['DEFAULT-VALUES'], true);
                }

                if (! empty($testData['SUBMITTED-VALUES'])) {
                    $submittedValues = json_decode($testData['SUBMITTED-VALUES'], true);
                }

                if (! empty($testData['EXPECTED-VALUES'])) {
                    $expectedValues = json_decode($testData['EXPECTED-VALUES'], true);
                }

                if (! empty($testData['EXPECTED-FORM'])) {
                    $expectedForm = $testData['EXPECTED-FORM'];
                }

                if (! empty($testData['EXPECTED-ERRORS'])) {
                    $expectedErrors = json_decode($testData['EXPECTED-ERRORS'], true);
                }

                if (! empty($testData['EXPECTED-EXCEPTION'])) {
                    $expectedException = trim($testData['EXPECTED-EXCEPTION']);
                }
            } catch (\Exception $e) {
                die(sprintf('Test "%s" is not valid: ' . $e->getMessage(), str_replace($fixturesDir . '/', '', $file)));
            }

            yield basename($file->getRealPath()) => [
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
            -1,
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

        $data    = [];
        $section = null;
        foreach ($tokens as $i => $token) {
            if (null === $section && ! $token) {
                continue; // skip leading blank
            }

            if (null === $section) {
                if (! array_key_exists($token, $sectionInfo)) {
                    throw new \RuntimeException(sprintf(
                        'The test file "%s" must not contain a section named "%s".',
                        str_replace($fixturesDir . '/', '', $file),
                        $token
                    ));
                }
                $section = $token;
                continue;
            }

            $data[$section] = $token;
            $section        = null;
        }

        foreach ($sectionInfo as $section => $required) {
            if ($required && ! array_key_exists($section, $data)) {
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
        self::assertEquals(
            $this->getDomDocument($expected),
            $this->getDomDocument($actual),
            'Failed asserting that the form is rendered correctly.'
        );
    }

    private function getDomDocument($html)
    {
        $doc                     = new \DOMDocument('1.0', 'utf-8');
        $doc->preserveWhiteSpace = false;

        // Don't add missing doctype, html and body
        //libxml_use_internal_errors(true);
        $doc->loadHTML($html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD | LIBXML_NOBLANKS);
        //libxml_use_internal_errors(false);

        // Remove whitespace for better comparison
        return preg_replace('~\s+~i', ' ', $doc->saveHTML());
    }
}
