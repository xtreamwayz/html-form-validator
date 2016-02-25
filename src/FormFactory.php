<?php

namespace Xtreamwayz\HTMLFormValidator;

use DOMDocument;
use DOMElement;
use DOMXPath;
use Psr\Http\Message\ServerRequestInterface;
use Zend\InputFilter\Factory;
use Zend\InputFilter\InputFilterInterface;

final class FormFactory implements FormFactoryInterface
{
    /**
     * @var Factory
     */
    private $factory;

    /**
     * @var DOMDocument
     */
    private $document;

    private $errorClass = 'has-danger';

    /**
     * @var FormElement\BaseFormElement[]
     */
    private $formElements = [
        'hidden'         => FormElement\Hidden::class,
        'text'           => FormElement\Text::class,
        'search'         => FormElement\Text::class,
        'tel'            => FormElement\Tel::class,
        'url'            => FormElement\Url::class,
        'email'          => FormElement\Email::class,
        'password'       => FormElement\Password::class,
        'date'           => FormElement\Date::class,
        'month'          => FormElement\Month::class,
        'week'           => FormElement\Week::class,
        'time'           => FormElement\Time::class,
        'datetime-local' => FormElement\DateTime::class,
        'number'         => FormElement\Number::class,
        'range'          => FormElement\Range::class,
        'color'          => FormElement\Color::class,
        'checkbox'       => FormElement\Checkbox::class,
        'radio'          => FormElement\Radio::class,
        'file'           => FormElement\File::class,
        'select'         => FormElement\Select::class,
        'textarea'       => FormElement\Textarea::class,
    ];

    /**
     * @inheritdoc
     */
    public function __construct($htmlForm, Factory $factory = null, array $defaultValues = [])
    {
        $this->factory = $factory ?: new Factory();

        // Create new doc
        $this->document = new DOMDocument('1.0', 'utf-8');

        // Ignore invalid tag errors during loading (e.g. datalist)
        libxml_use_internal_errors(true);
        // Don't add missing doctype, html and body
        $this->document->loadHTML($htmlForm, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_use_internal_errors(false);

        // Inject default values (from models etc)
        $this->setData($defaultValues, true);
    }

    /**
     * @inheritdoc
     */
    public static function fromHtml($htmlForm, array $defaultValues = [])
    {
        return new self($htmlForm, null, $defaultValues);
    }

    /**
     * @inheritdoc
     */
    public function asString(ValidationResultInterface $result = null)
    {
        if ($result) {
            // Inject data if a result is set
            $this->setData($result->getValues());
            $this->setMessages($result->getMessages());
        }

        // Always remove form validator specific attributes before rendering the form
        // to clean it up and remove possible sensitive data
        foreach ($this->getNodeList() as $name => $node) {
            $node->removeAttribute('data-reuse-submitted-value');
            $node->removeAttribute('data-input-name');
            $node->removeAttribute('data-validators');
            $node->removeAttribute('data-filters');
        }

        $this->document->formatOutput = true;

        return $this->document->saveHTML();
    }

    public function validateRequest(ServerRequestInterface $request)
    {
        if ($request->getMethod() !== 'POST') {
            // Not a post request, skip validation
            return new ValidationResult([], [], [], $request->getMethod());
        }

        return $this->validate((array) $request->getParsedBody(), $request->getMethod());
    }

    /**
     * @inheritdoc
     */
    public function validate(array $data, $method = null)
    {
        $inputFilter = $this->factory->createInputFilter([]);

        // Add all validators and filters to the InputFilter
        $this->buildInputFilterFromForm($inputFilter);

        $inputFilter->setData($data);
        $messages = [];

        // Do some validation
        if (!$inputFilter->isValid()) {
            foreach ($inputFilter->getInvalidInput() as $message) {
                $messages[$message->getName()] = $message->getMessages();
            }
        }

        // Get the submit button
        $submitName = null;
        foreach ($this->getSubmitStateNodeList() as $name) {
            if (isset($data[$name])) {
                $submitName = $name;
            }
        }

        // Return validation result
        return new ValidationResult(
            $inputFilter->getRawValues(),
            $inputFilter->getValues(),
            $messages,
            $method,
            $submitName
        );
    }

    /**
     * Build the InputFilter, validators and filters from form fields
     *
     * @param InputFilterInterface $inputFilter
     */
    private function buildInputFilterFromForm(InputFilterInterface $inputFilter)
    {
        foreach ($this->getNodeList() as $name => $node) {
            if ($inputFilter->has($name)) {
                continue;
            }

            // Detect element type
            $type = $node->getAttribute('type');
            if ($node->tagName == 'textarea') {
                $type = 'textarea';
            } elseif ($node->tagName == 'select') {
                $type = 'select';
            }

            // Add validation
            if (isset($this->formElements[$type])) {
                $elementClass = $this->formElements[$type];
            } else {
                // Create a default validator
                $elementClass = $this->formElements['text'];
            }

            /** @var \Zend\InputFilter\InputProviderInterface $element */
            $element = new $elementClass($node, $this->document);
            $input = $this->factory->createInput($element);
            $inputFilter->add($input, $name);
        }
    }

    /**
     * Get form elements and create an id if needed
     */
    private function getNodeList()
    {
        $xpath = new DOMXPath($this->document);
        $nodeList = $xpath->query('//input | //textarea | //select | //div[@data-input-name]');

        /** @var DOMElement $node */
        foreach ($nodeList as $node) {
            // Set some basic vars
            $name = $node->getAttribute('name');
            if (!$name) {
                $name = $node->getAttribute('data-input-name');
            }

            if (!$name || $node->getAttribute('type') == 'submit') {
                // At least a name is needed to submit a value.
                // Silently continue, might be a submit button.
                continue;
            }

            yield $name => $node;
        }
    }

    /**
     * Get names of available named submit elements
     */
    private function getSubmitStateNodeList()
    {
        $xpath = new DOMXPath($this->document);
        $nodeList = $xpath->query('//input[@type="submit"] | //button[@type="submit"]');

        /** @var DOMElement $node */
        foreach ($nodeList as $node) {
            $name = $node->getAttribute('name');
            if (!$name) {
                // At least a name is needed to submit a value.
                continue;
            }

            yield $name;
        }
    }

    /**
     * Set values and element checked and selected states
     *
     * @param array $data
     * @param bool  $force
     */
    private function setData(array $data, $force = false)
    {
        foreach ($this->getNodeList() as $name => $node) {
            if (!isset($data[$name])) {
                // No value set for this element
                continue;
            }

            $value = $data[$name];

            $reuseSubmittedValue = filter_var(
                $node->getAttribute('data-reuse-submitted-value'),
                FILTER_VALIDATE_BOOLEAN
            );

            if (!$reuseSubmittedValue && $force === false) {
                // Don't need to set the value
                continue;
            }

            if ($node->getAttribute('type') == 'checkbox' || $node->getAttribute('type') == 'radio') {
                if ($value == $node->getAttribute('value')) {
                    $node->setAttribute('checked', 'checked');
                } else {
                    $node->removeAttribute('checked');
                }
            } elseif ($node->nodeName == 'select') {
                /** @var DOMElement $option */
                foreach ($node->getElementsByTagName('option') as $option) {
                    if ($value == $option->getAttribute('value')) {
                        $option->setAttribute('selected', 'selected');
                    } else {
                        $option->removeAttribute('selected');
                    }
                }
            } elseif ($node->nodeName == 'input') {
                // Set value for input elements
                $node->setAttribute('value', $value);
            } elseif ($node->nodeName == 'textarea') {
                $node->nodeValue = $value;
            }
        }
    }

    /**
     * Set validation messages, bootstrap style
     *
     * @param array $data
     */
    private function setMessages(array $data)
    {
        foreach ($data as $name => $errors) {
            // Not sure if this can be optimized and create the DOMXPath only once.
            // At this point the dom is constantly changing.
            $xpath = new DOMXPath($this->document);
            // Get all elements with the name
            $nodeList = $xpath->query(sprintf('//*[@name="%1$s"] | //*[@data-input-name="%1$s"]', $name));

            if ($nodeList->length == 0) {
                // No element found for this element ???
                continue;
            }

            // Get first element only
            $node = $nodeList->item(0);

            /** @var DOMElement $parent */
            $parent = $node->parentNode;
            if (strpos($parent->getAttribute('class'), $this->errorClass) === false) {
                // Set error class to parent
                $class = trim($parent->getAttribute('class') . ' ' . $this->errorClass);
                $parent->setAttribute('class', $class);
            }

            // Inject error messages
            foreach ($errors as $code => $message) {
                $div = $this->document->createElement('div');
                $div->setAttribute('class', 'text-danger');
                $div->nodeValue = $message;
                $node->parentNode->insertBefore($div, $node->nextSibling);
            }

            /** @var DOMElement $node */
            foreach ($nodeList as $node) {
                // Set aria-invalid attribute on all elements
                $node->setAttribute('aria-invalid', 'true');
            }
        }
    }
}
