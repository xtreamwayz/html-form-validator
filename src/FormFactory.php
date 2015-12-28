<?php

namespace Xtreamwayz\HTMLFormValidator;

use DOMDocument;
use DOMElement;
use Zend\Filter\Word\SeparatorToCamelCase;
use Zend\Validator\ValidatorInterface;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Input;

class FormFactory
{
    private $document;

    /**
     * @var SeparatorToCamelCase
     */
    private $toCamelCaseFilter;

    private $errors = [];

    public function __construct($htmlForm)
    {
        $this->document = new DOMDocument('1.0', 'utf-8');
        $this->document->loadHTML($htmlForm);

        $this->toCamelCaseFilter = new SeparatorToCamelCase('-');
    }

    public static function fromHtml($htmlForm)
    {
        return new self($htmlForm);
    }

    public function validate(array $data)
    {
        $rawInputData = [];
        $validatedData = [];

        $inputs = $this->document->getElementsByTagName('input');
        /** @var DOMElement $input */
        foreach ($inputs as $input) {
            // Set some basic vars
            $name = ($input->getAttribute('name')) ? $input->getAttribute('name') : $input->getAttribute('id');
            $value = (isset($data[$name])) ? $data[$name] : $input->getAttribute('value');
            $dataValidator = $input->getAttribute('data-validator');
            $reuseSubmittedValue = filter_var(
                $input->getAttribute('data-reuse-submitted-value'),
                FILTER_VALIDATE_BOOLEAN
            );

            // Set raw data
            $rawInputData[$name] = $value;

            // Set input value
            if ($reuseSubmittedValue) {
                $input->setAttribute('value', $value);
            }

            // Get validate options
            $validateOptions = [];
            $validateOptions['min'] = $input->getAttribute('min');
            $validateOptions['max'] = $input->getAttribute('max');
            $validateOptions['useMxCheck'] = filter_var(
                $input->getAttribute('data-validator-use-mx-check'),
                FILTER_VALIDATE_BOOLEAN
            );

            // Do some real validation
            if ($dataValidator && $name) {
                $validatorClass = sprintf(
                    'Zend\\Validator\\%s',
                    $this->toCamelCaseFilter->filter($dataValidator)
                );

                /** @var ValidatorInterface $validator */
                $validator = new $validatorClass($validateOptions);

                if (! $validator->isValid($value)) {
                    foreach ($validator->getMessages() as $key => $message) {
                        $this->errors[$name][$key] = $message;
                    }
                } else {
                    $validatedData[$name] = $value;
                }
            }
        }

        // Return validation result
        return new ValidationResult($rawInputData, $validatedData, $this->errors);
    }

    public function asString()
    {
        $this->document->formatOutput = true;

        return $this->document->saveXML();
    }
}
