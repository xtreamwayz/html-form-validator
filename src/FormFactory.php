<?php

namespace Xtreamwayz\HTMLFormValidator;

use DOMDocument;
use DOMElement;
use DOMXPath;
use Xtreamwayz\HTMLFormValidator\FormElement;
use Zend\InputFilter\BaseInputFilter;
use Zend\InputFilter\Input;
use Zend\InputFilter\InputFilter;

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
        'email'          => FormElement\Email::class,
        'number'         => FormElement\Number::class,
        'tel'            => FormElement\Tel::class,
        'text'           => FormElement\Text::class,
        'textarea'       => FormElement\Textarea::class,
        'url'            => FormElement\Url::class,
        'date'           => FormElement\Date::class,
        'month'          => FormElement\Month::class,
        'week'           => FormElement\Week::class,
        'time'           => FormElement\Time::class,
        'datetime-local' => FormElement\DateTimeLocal::class,
        'color'          => FormElement\Color::class,
        'range'          => FormElement\Number::class,
        'password'       => FormElement\Password::class,
        'checkbox'       => FormElement\Checkbox::class,
        'radio'          => FormElement\Radio::class,
        'file'           => FormElement\File::class,
        'select'         => FormElement\Select::class,
        'search'         => FormElement\Search::class,
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

        // Ignore invalid tag errors during loading (e.g. datalist)
        libxml_use_internal_errors(true);
        // Don't add missing doctype, html and body
        $this->document->loadHTML($htmlForm, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_use_internal_errors(false);

        // Add all validators and filters to the InputFilter
        $this->buildInputFilterFromForm();

        // Inject default values (from models etc)
        $this->injectValues($defaultValues, true);
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
            $this->injectValues($result->getValidValues());
            $this->injectErrorMessages($result->getErrorMessages());
        }

        // Always remove form validator specific attributes before rendering the form
        // to clean it up and remove possible sensitive data
        foreach ($this->getFormElements() as $name => $element) {
            $element->removeAttribute('data-reuse-submitted-value');
            $element->removeAttribute('data-input-name');
            $element->removeAttribute('data-validators');
            $element->removeAttribute('data-filters');
        }

        $this->document->formatOutput = true;

        return $this->document->saveHTML();
    }

    /**
     * Build the InputFilter, validators and filters from form fields
     */
    private function buildInputFilterFromForm()
    {
        foreach ($this->getFormElements() as $name => $element) {
            // Detect element type
            $type = $element->getAttribute('type');
            if ($element->tagName == 'textarea') {
                $type = 'textarea';
            } elseif ($element->tagName == 'select') {
                $type = 'select';
            }

            // Add validation
            if (isset($this->formElements[$type])) {
                $validator = new $this->formElements[$type];
            } else {
                // Create a default validator
                $validator = new $this->formElements['text'];
            }

            if ($this->inputFilter->has($name)) {
                $input = $this->inputFilter->get($name);
            } else {
                // No input found for element, create a new one
                $input = new Input($name);
                // Enforce properties so the NotEmpty validator is automatically added,
                // we'll take care of this later.
                $input->setRequired(false);
                $input->setAllowEmpty(true);
                $this->inputFilter->add($input);
            }

            // Process element and attach filters and validators
            $validator($element, $input, $this->document);
        }
    }

    /**
     * Get form elements and create an id if needed
     */
    private function getFormElements()
    {
        $xpath = new DOMXPath($this->document);
        $elements = $xpath->query('//input | //textarea | //select | //div[@data-input-name]');

        /** @var DOMElement $element */
        foreach ($elements as $element) {
            // Set some basic vars
            $name = $element->getAttribute('name');
            if (!$name) {
                $name = $element->getAttribute('data-input-name');
            }

            if (!$name) {
                // At least a name is needed to submit a value.
                // Silently continue, might be a submit button.
                continue;
            }

            yield $name => $element;
        }
    }

    /**
     * Inject submitted filtered values into the form, bootstrap style
     *
     * @param array $data
     * @param bool  $force
     */
    private function injectValues(array $data, $force = false)
    {
        foreach ($this->getFormElements() as $name => $element) {
            if (!isset($data[$name])) {
                // No value set for this element
                continue;
            }

            $value = $data[$name];

            $reuseSubmittedValue = filter_var(
                $element->getAttribute('data-reuse-submitted-value'),
                FILTER_VALIDATE_BOOLEAN
            );

            if (!$reuseSubmittedValue && $force === false) {
                // Don't need to set the value
                continue;
            }

            if ($element->getAttribute('type') == 'checkbox' || $element->getAttribute('type') == 'radio') {
                if ($value == $element->getAttribute('value')) {
                    $element->setAttribute('checked', 'checked');
                } else {
                    $element->removeAttribute('checked');
                }
            } elseif ($element->nodeName == 'select') {
                /** @var DOMElement $node */
                foreach ($element->getElementsByTagName('option') as $node) {
                    if ($value == $node->getAttribute('value')) {
                        $node->setAttribute('selected', 'selected');
                    } else {
                        $node->removeAttribute('selected');
                    }
                }
            } elseif ($element->nodeName == 'input') {
                // Set value for input elements
                $element->setAttribute('value', $value);
            } elseif ($element->nodeName == 'textarea') {
                $element->nodeValue = $value;
            }
        }
    }

    /**
     * Inject error messages into the form, bootstrap style
     *
     * @param array $data
     */
    private function injectErrorMessages(array $data)
    {
        foreach ($this->getFormElements() as $name => $element) {
            if (!isset($data[$name])) {
                // No errors set for this element
                continue;
            }

            $errors = $data[$name];

            /** @var DOMElement $parent */
            $parent = $element->parentNode;
            if (strpos($parent->getAttribute('class'), $this->errorClass) === false) {
                // Set error class to parent
                $class = trim($parent->getAttribute('class') . ' ' . $this->errorClass);
                $parent->setAttribute('class', $class);
            }

            // Set aria-invalid attribute on element
            $element->setAttribute('aria-invalid', 'true');

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
