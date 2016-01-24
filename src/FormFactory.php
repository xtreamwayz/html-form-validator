<?php

namespace Xtreamwayz\HTMLFormValidator;

use DOMDocument;
use DOMElement;
use DOMXPath;
use Xtreamwayz\HTMLFormValidator\FormElement;
use Zend\InputFilter\Factory as InputFilterFactory;
use Zend\InputFilter\InputFilter;
use Zend\Validator;

class FormFactory
{
    /**
     * @var InputFilterFactory
     */
    private $inputFilterFactory;

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

    public function __construct($htmlForm, InputFilterFactory $inputFilterFactory = null)
    {
        $this->inputFilterFactory = $inputFilterFactory;

        // Create new doc
        $this->document = new DOMDocument('1.0', 'utf-8');

        // Don't add missing doctype, html and body
        $this->document->loadHTML($htmlForm, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
    }

    public static function fromHtml($htmlForm)
    {
        return new self($htmlForm);
    }

    public function validate(array $data)
    {
        $inputFilter = new InputFilter();
        if ($this->inputFilterFactory) {
            $inputFilter->setFactory($this->inputFilterFactory);
        }

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

            // Detect element type
            $type = $element->getAttribute('type');
            if ($element->tagName == 'textarea') {
                $type = 'textarea';
            }

            // Add validation
            if (isset($this->formElements[$type])) {
                $validator = new $this->formElements[$type];
                $inputFilter->add($validator($element));
            }
        }

        $inputFilter->setData($data);
        $validationErrors = [];

        // Do some real validation
        if (! $inputFilter->isValid()) {
            foreach ($inputFilter->getInvalidInput() as $error) {
                $validationErrors[$error->getName()] = $error->getMessages();
            }
        }

        $validatedValues = $inputFilter->getValues();

        // Set validated values
        foreach ($elements as $element) {
            $reuseSubmittedValue = filter_var(
                $element->getAttribute('data-reuse-submitted-value'),
                FILTER_VALIDATE_BOOLEAN
            );

            if (! $reuseSubmittedValue) {
                continue;
            }

            $id = $element->getAttribute('id');

            // Get element value
            $value = (isset($validatedValues[$id])) ? $validatedValues[$id] : $element->getAttribute('value');

            // Use for textarea
            $element->nodeValue = $value;
            // For other elements
            $element->setAttribute('value', $value);
        }

        // Return validation result
        return new ValidationResult($inputFilter->getRawValues(), $inputFilter->getValues(), $validationErrors);
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
            // Inject error messages and classes into the form
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

        $this->document->formatOutput = true;

        return $this->document->saveHTML();
    }
}
