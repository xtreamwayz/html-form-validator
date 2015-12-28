<?php

namespace Xtreamwayz\HTMLFormValidator;

use DOMDocument;
use DOMElement;
use Zend\Filter\Word\SeparatorToCamelCase;
use Zend\InputFilter\Input;
use Zend\InputFilter\InputFilter;
use Zend\Validator;

class FormFactory
{
    private $document;

    /**
     * @var SeparatorToCamelCase
     */
    private $toCamelCaseFilter;

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
        $inputFilter = new InputFilter();

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

            // Build input validator chain
            $validator = new Input($name);

            // TODO: Move into it's own class
            if ($input->getAttribute('type') == 'email') {
                $validator->getValidatorChain()
                          ->attach(
                              new Validator\EmailAddress(
                                  [
                                      'useMxCheck' => filter_var(
                                          $input->getAttribute('data-validator-use-mx-check'),
                                          FILTER_VALIDATE_BOOLEAN
                                      ),
                                  ]
                              )
                          )
                ;
            }

            // TODO: Move into it's own class
            if ($input->getAttribute('type') == 'number') {
                $min = $input->getAttribute('min');
                $max = $input->getAttribute('max');

                if ($min && $max) {
                    $validator->getValidatorChain()
                              ->attach(
                                  new Validator\Between(
                                      [
                                          'min' => $min,
                                          'max' => $max,
                                      ]
                                  )
                              )
                    ;
                } elseif ($min) {
                    $validator->getValidatorChain()
                              ->attach(
                                  new Validator\GreaterThan(
                                      [
                                          'min' => $min,
                                      ]
                                  )
                              )
                    ;
                } elseif ($max) {
                    $validator->getValidatorChain()
                              ->attach(
                                  new Validator\LessThan(
                                      [
                                          'max' => $max,
                                      ]
                                  )
                              )
                    ;
                }
            }

            $inputFilter->add($validator);
        }

        $inputFilter->setData($data);
        $validationErrors = [];

        // Do some real validation
        if (! $inputFilter->isValid()) {
            foreach ($inputFilter->getInvalidInput() as $error) {
                $validationErrors[$error->getName()] = $error->getMessages();
            }
        }

        // Return validation result
        return new ValidationResult($inputFilter->getRawValues(), $inputFilter->getValues(), $validationErrors);
    }

    public function asString()
    {
        $this->document->formatOutput = true;

        return $this->document->saveXML();
    }
}
