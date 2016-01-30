<?php

namespace Xtreamwayz\HTMLFormValidator;

use DOMDocument;
use DOMElement;
use DOMXPath;
use Xtreamwayz\HTMLFormValidator\FormElement;
use Zend\InputFilter\BaseInputFilter;
use Zend\InputFilter\Input;
use Zend\InputFilter\InputFilter;
use Zend\Validator;

class FormFactory
{
    /**
     * @var BaseInputFilter
     */
    private $inputFilter;

    /**
     * @var DOMDocument
     */
    private $document;

    /**
     * @var array
     */
    private $defaultValues;

    private $errorClass = 'has-danger';

    /**
     * @var FormElement\AbstractFormElement[]
     */
    private $formElements = [
        'email'    => FormElement\Email::class,
        'number'   => FormElement\Number::class,
        'text'     => FormElement\Text::class,
        'textarea' => FormElement\Textarea::class,
    ];

    /**
     * FormFactory constructor: Load html form and optionally set an InputFilter
     *
     * @param                      $htmlForm
     * @param array                $defaultValues
     * @param BaseInputFilter|null $inputFilter
     */
    public function __construct($htmlForm, array $defaultValues = [], BaseInputFilter $inputFilter = null)
    {
        $this->inputFilter = $inputFilter ?: new InputFilter();
        $this->defaultValues = $defaultValues;

        // Create new doc
        $this->document = new DOMDocument('1.0', 'utf-8');
        // Don't add missing doctype, html and body
        $this->document->loadHTML($htmlForm, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

        $this->preProcessForm();
        $this->injectDefaultValues($defaultValues);
    }

    /**
     * Load html form and optionally set default data
     *
     * @param       $htmlForm
     * @param array $defaultValues
     *
     * @return FormFactory
     */
    public static function fromHtml($htmlForm, array $defaultValues = [])
    {
        return new self($htmlForm, $defaultValues);
    }

    /**
     * Validate the loaded form with the data
     *
     * @param array $data
     *
     * @return ValidationResult
     */
    public function validate(array $data)
    {
        if (!$this->inputFilter) {
            $this->inputFilter = new InputFilter();
        }

        $this->prepareValidatorsAndFilters();
        $this->inputFilter->setData($data);
        $validationErrors = [];

        // Do some validation
        if (!$this->inputFilter->isValid()) {
            foreach ($this->inputFilter->getInvalidInput() as $error) {
                $validationErrors[$error->getName()] = $error->getMessages();
            }
        }

        // Return validation result
        return new ValidationResult(
            $this->inputFilter->getRawValues(),
            $this->inputFilter->getValues(),
            $validationErrors
        );
    }

    /**
     * Pre-process form: set id if needed and  and set
     */
    private function preProcessForm()
    {
        $xpath = new DOMXPath($this->document);
        $elements = $xpath->query('//input | //textarea');

        /** @var DOMElement $element */
        foreach ($elements as $element) {
            // Set some basic vars
            $name = $element->getAttribute('name');
            $id = $element->getAttribute('id');
            if (! $name && ! $id) {
                // At least a name or id is needed. Silently continue, might be a submit button.
                continue;
            }

            // Create an id if needed, this speeds up finding the element again
            if (! $id) {
                $id = $name;
                $element->setAttribute('id', $id);
            }
        }
    }

    private function injectDefaultValues(array $data)
    {
        foreach ($data as $id => $value) {
            $element = $this->document->getElementById($id);

            if ($element->nodeName == 'input') {
                // Set value for input elements
                $element->setAttribute('value', $value);
            } else {
                // For other elements
                $element->nodeValue = $value;
            }
        }
    }

    private function prepareValidatorsAndFilters()
    {
        $xpath = new DOMXPath($this->document);
        $elements = $xpath->query('//input | //textarea');

        /** @var DOMElement $element */
        foreach ($elements as $element) {
            $id = $element->getAttribute('id');

            // Detect element type
            $type = $element->getAttribute('type');
            if ($element->tagName == 'textarea') {
                $type = 'textarea';
            }

            // Add validation
            if (isset($this->formElements[$type])) {
                $validator = new $this->formElements[$type];
                if ($this->inputFilter->has($id)) {
                    $input = $this->inputFilter->get($id);
                } else {
                    // No input find for element, create a new one
                    $input = new Input($id);
                    // Enforce properties so the NotEmpty validator is automatically added,
                    // we'll take care of this later.
                    $input->setRequired(false);
                    $input->setAllowEmpty(true);
                    $this->inputFilter->add($input);
                }

                // Process element and attach filters and validators
                $validator($element, $input);
            }
        }
    }

    /**
     * Return form as a string. Optionally inject the error messages for the result.
     *
     * @param ValidationResult|null $result
     *
     * @return string
     */
    public function asString(ValidationResult $result = null)
    {
        if ($result) {
            // Inject data if a result is set
            $this->injectSubmittedValues($result);
            $this->injectErrorMessages($result);
        }

        $this->document->formatOutput = true;

        return $this->document->saveHTML();
    }

    /**
     * Inject submitted filtered values into the form, bootstrap style
     *
     * @param ValidationResult $result
     */
    private function injectSubmittedValues(ValidationResult $result)
    {
        foreach ($result->getValidValues() as $id => $value) {
            $element = $this->document->getElementById($id);

            $reuseSubmittedValue = filter_var(
                $element->getAttribute('data-reuse-submitted-value'),
                FILTER_VALIDATE_BOOLEAN
            );

            if (!$reuseSubmittedValue) {
                continue;
            }

            // Clean up html
            $element->removeAttribute('data-reuse-submitted-value');

            if ($element->nodeName == 'input') {
                // Set value for input elements
                $element->setAttribute('value', $value);
            } else {
                // For other elements
                $element->nodeValue = $value;
            }
        }
    }

    /**
     * Inject error messages into the form, bootstrap style
     *
     * @param ValidationResult $result
     */
    private function injectErrorMessages(ValidationResult $result)
    {
        foreach ($result->getErrorMessages() as $id => $errors) {
            $element = $this->document->getElementById($id);

            // Set error class to parent
            $parent = $element->parentNode;
            $class = trim($parent->getAttribute('class') . ' ' . $this->errorClass);
            $parent->setAttribute('class', $class);

            // Inject error messages
            foreach ($errors as $code => $message) {
                $div = $this->document->createElement('div');
                $div->setAttribute('class', 'text-danger');
                $div->nodeValue = $message;
                $element->parentNode->insertBefore($div, $element->nextSibling);
            }
        }
    }
}
