<?php

namespace Xtreamwayz\HTMLFormValidator;

use DOMDocument;
use DOMElement;
use DOMXPath;
use Xtreamwayz\HTMLFormValidator\FormElement;
use Zend\InputFilter\BaseInputFilter;
use Zend\InputFilter\InputFilter;
use Zend\Validator;

class FormFactory
{
    /**
     * @var InputFilter
     */
    private $inputFilter;

    /**
     * @var DOMDocument
     */
    private $document;

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

    public function __construct($htmlForm, BaseInputFilter $inputFilter = null)
    {
        $this->inputFilter = $inputFilter ?: new InputFilter();

        // Create new doc
        $this->document = new DOMDocument('1.0', 'utf-8');
        // Don't add missing doctype, html and body
        $this->document->loadHTML($htmlForm, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
    }

    public static function fromHtml($htmlForm, BaseInputFilter $inputFilter = null)
    {
        return new self($htmlForm, $inputFilter);
    }

    public function validate(array $data)
    {
        $this->preProcessForm();

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

    private function preProcessForm()
    {
        $xpath = new DOMXPath($this->document);
        $elements = $xpath->query('//input | //textarea');

        /** @var DOMElement $element */
        foreach ($elements as $element) {
            // Set some basic vars
            $name = $element->getAttribute('name');
            $id = $element->getAttribute('id');
            if (!$name && !$id) {
                // At least a name or id is needed. Silently continue, might be a submit button.
                continue;
            }

            // Create an id if needed, this speeds up finding the element again
            if (!$id) {
                $id = $name;
                $element->setAttribute('id', $id);
            }

            /*
            // Detect element type
            $type = $element->getAttribute('type');
            if ($element->tagName == 'textarea') {
                $type = 'textarea';
            }

            // Add validation
            if (isset($this->formElements[$type])) {
                $validator = new $this->formElements[$type];
                $this->inputFilter->add($validator($element));
            }
            */
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
            $this->injectSubmittedValues($result);
            $this->injectErrorMessages($result);
        }

        $this->document->formatOutput = true;

        return $this->document->saveHTML();
    }

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

            if ($element->nodeName == 'input') {
                // Set value for input elements
                $element->setAttribute('value', $value);
            } else {
                // For other elements
                $element->nodeValue = $value;
            }
        }
    }

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
