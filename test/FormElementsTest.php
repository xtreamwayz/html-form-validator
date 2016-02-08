<?php

namespace Xtreamwayz\HTMLFormValidatorTest;

use Xtreamwayz\HTMLFormValidator\FormFactory;
use Xtreamwayz\HTMLFormValidator\ValidationResult;

class FormElementsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider getIntegrationTests
     */
    public function testIntegration(
        $message,
        $htmlForm,
        $defaultValues,
        $submittedValues,
        $expectedValues,
        $expectedForm,
        $expectedErrors
    ) {
        $form = FormFactory::fromHtml($htmlForm, $defaultValues);
        $result = $form->validate($submittedValues);

        $this->assertInstanceOf(ValidationResult::class, $result);
        $this->assertEquals(
            $submittedValues,
            $result->getRawInputValues(),
            "Failed asserting submitted values are equal."
        );
        $this->assertEquals($expectedValues, $result->getValidValues(), "Failed asserting filtered values are equal.");

        if ($expectedForm) {
            $this->assertEqualForms($expectedForm, $form->asString($result));
        }

        if (empty($expectedErrors)) {
            $this->assertTrue($result->isValid(), "Failed asserting the validation result is valid.");
        } else {
            $this->assertFalse($result->isValid(), "Failed asserting the validation result is invalid.");
            $this->assertEqualErrors($expectedErrors, $result->getErrorMessages());
        }
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

            $message = '';
            $htmlForm = '';
            $defaultValues = [];
            $submittedValues = [];
            $expectedValues = [];
            $expectedForm = '';
            $expectedErrors = [];

            try {
                $message = $testData['TEST'];
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
            } catch (\Exception $e) {
                die(sprintf('Test "%s" is not valid: ' . $e->getMessage(), str_replace($fixturesDir . '/', '', $file)));
            }

            yield basename($file) => [
                $message,
                $htmlForm,
                $defaultValues,
                $submittedValues,
                $expectedValues,
                $expectedForm,
                $expectedErrors,
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
            'TEST'             => true,
            'HTML-FORM'        => true,
            'DEFAULT-VALUES'   => false,
            'SUBMITTED-VALUES' => false,
            'EXPECTED-VALUES'  => false,
            'EXPECTED-FORM'    => false,
            'EXPECTED-ERRORS'  => false,
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

    private function assertEqualErrors(array $expected, array $actual)
    {
        $this->assertEquals(count($expected), count($actual), "Failed asserting that errors are equal.");

        foreach ($expected as $name => $errorCodes) {
            $this->assertArrayHasKey(
                $name,
                $actual,
                sprintf("Failed asserting that input name '%s' has an error.", $name)
            );

            foreach ($errorCodes as $errorCode) {
                $this->assertArrayHasKey(
                    $errorCode,
                    $actual[$name],
                    sprintf("Failed asserting that input name '%s' has error code '%s'.", $name, $errorCode)
                );
            }
        }
    }

    private function assertEqualForms($expected, $actual)
    {
        $this->assertEquals(
            $this->getDomDocument($expected),
            $this->getDomDocument($actual),
            'The form is not rendered correctly.'
        );
    }

    private function getDomDocument($html)
    {
        $doc = new \DOMDocument('1.0', 'utf-8');
        // Don't add missing doctype, html and body
        libxml_use_internal_errors(true);
        $doc->loadHTML($html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_use_internal_errors(false);

        return $doc;
    }
}
