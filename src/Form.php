<?php
/**
 * html-form-validator (https://github.com/xtreamwayz/html-form-validator)
 *
 * @see       https://github.com/xtreamwayz/html-form-validator for the canonical source repository
 * @copyright Copyright (c) 2016 Geert Eltink (https://xtreamwayz.com/)
 * @license   https://github.com/xtreamwayz/html-form-validator/blob/master/LICENSE.md MIT
 */

declare(strict_types=1);

namespace Xtreamwayz\HTMLFormValidator;

use DOMDocument;
use DOMElement;
use DOMXPath;
use Generator;
use Psr\Http\Message\ServerRequestInterface;
use Zend\InputFilter\Factory;
use Zend\InputFilter\InputFilterInterface;
use function in_array;

final class Form implements FormInterface
{
    /**
     * @var Factory
     */
    private $factory;

    /**
     * @var InputFilterInterface
     */
    private $inputFilter;

    /**
     * The form
     *
     * @var DOMDocument
     */
    private $document;

    /**
     * Generic has validation error css class
     *
     * @var string
     */
    private $cssHasErrorClass;

    /**
     * Generic validation error css class
     *
     * @var string
     */
    private $cssErrorClass;

    /**
     * @var FormElement\BaseFormElement[]
     */
    private $formElements = [
        'checkbox'       => FormElement\Checkbox::class,
        'color'          => FormElement\Color::class,
        'date'           => FormElement\Date::class,
        'datetime-local' => FormElement\DateTime::class,
        'email'          => FormElement\Email::class,
        'file'           => FormElement\File::class,
        'hidden'         => FormElement\Hidden::class,
        'month'          => FormElement\Month::class,
        'number'         => FormElement\Number::class,
        'password'       => FormElement\Password::class,
        'radio'          => FormElement\Radio::class,
        'range'          => FormElement\Range::class,
        'search'         => FormElement\Text::class,
        'select'         => FormElement\Select::class,
        'tel'            => FormElement\Tel::class,
        'text'           => FormElement\Text::class,
        'textarea'       => FormElement\Textarea::class,
        'time'           => FormElement\Time::class,
        'url'            => FormElement\Url::class,
        'week'           => FormElement\Week::class,
    ];

    /**
     * @inheritdoc
     */
    public function __construct(
        Factory $factory,
        InputFilterInterface $inputFilter,
        DOMDocument $document,
        array $defaultValues,
        ?array $options = null
    ) {
        $this->factory     = $factory;
        $this->inputFilter = $inputFilter;
        $this->document    = $document;

        $this->setData($defaultValues, true);

        $options                = $options ?? [];
        $this->cssHasErrorClass = $options['cssHasErrorClass'] ?? 'has-validation-error';
        $this->cssErrorClass    = $options['cssErrorClass'] ?? 'has-validation-error';
    }

    /**
     * @inheritdoc
     */
    public function asString(?ValidationResultInterface $result = null) : string
    {
        if ($result) {
            // Inject data if a result is set
            $this->setData($result->getValues());
            $this->setMessages($result->getMessages());
        }

        // Always remove form validator specific attributes before rendering the form
        // to clean it up and remove possible sensitive data
        /** @var DOMElement $node */
        foreach ($this->getNodeList() as $name => $node) {
            $node->removeAttribute('data-reuse-submitted-value');
            $node->removeAttribute('data-input-name');
            $node->removeAttribute('data-validators');
            $node->removeAttribute('data-filters');
        }

        $this->document->formatOutput = true;

        // Return the first form only to prevent returning the XML declaration
        return $this->document->saveHTML($this->document->getElementsByTagName('form')->item(0));
    }

    /**
     * @inheritdoc
     */
    public function validateRequest(ServerRequestInterface $request) : ValidationResultInterface
    {
        return $this->validate((array) $request->getParsedBody(), $request->getMethod());
    }

    /**
     * @inheritdoc
     */
    public function validate(array $data, ?string $method = null) : ValidationResultInterface
    {
        if ($method !== null && $method !== 'POST') {
            // Not a post request, skip validation
            return new ValidationResult([], [], [], $method);
        }

        $inputFilter = $this->inputFilter ?? $this->factory->createInputFilter([]);

        // Add all validators and filters to the InputFilter
        $this->buildInputFilterFromForm($inputFilter);

        $inputFilter->setData($data);
        $messages = [];

        // Do some validation
        if (! $inputFilter->isValid()) {
            foreach ($inputFilter->getInvalidInput() as $message) {
                $messages[$message->getName()] = $message->getMessages();
            }
        }

        // Get the submit button
        $submitName = null;
        foreach ($this->getSubmitStateNodeList() as $name) {
            if (array_key_exists($name, $data)) {
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
     */
    private function buildInputFilterFromForm(InputFilterInterface $inputFilter) : void
    {
        /** @var DOMElement $node */
        foreach ($this->getNodeList() as $name => $node) {
            if ($inputFilter->has($name)) {
                continue;
            }

            // Detect element type
            $type = $node->getAttribute('type');
            if ($node->tagName === 'textarea') {
                $type = 'textarea';
            } elseif ($node->tagName === 'select') {
                $type = 'select';
            }

            // Add validation
            if (array_key_exists($type, $this->formElements)) {
                $elementClass = $this->formElements[$type];
            } else {
                // Create a default validator
                $elementClass = $this->formElements['text'];
            }

            /** @var \Zend\InputFilter\InputProviderInterface $element */
            $element = new $elementClass($node, $this->document);
            $input   = $this->factory->createInput($element);
            $inputFilter->add($input, $name);
        }
    }

    /**
     * Get form elements and create an id if needed
     *
     * return \Generator<string, DOMElement>
     */
    private function getNodeList() : Generator
    {
        $xpath    = new DOMXPath($this->document);
        $nodeList = $xpath->query('//input | //textarea | //select | //div[@data-input-name]');

        /** @var DOMElement $node */
        foreach ($nodeList as $node) {
            $name = $node->getAttribute('name');
            if (! $name) {
                $name = $node->getAttribute('data-input-name');
            }

            if (! $name || $node->getAttribute('type') === 'submit') {
                // At least a name is needed to submit a value.
                // Silently continue, might be a submit button.
                continue;
            }

            if ($node->hasAttribute('disabled')) {
                // Ignore disabled nodes
                continue;
            }

            yield $name => $node;
        }
    }

    /**
     * Get names of available named submit elements
     */
    private function getSubmitStateNodeList() : Generator
    {
        $xpath    = new DOMXPath($this->document);
        $nodeList = $xpath->query('//input[@type="submit"] | //button[@type="submit"]');

        /** @var DOMElement $node */
        foreach ($nodeList as $node) {
            $name = $node->getAttribute('name');
            if (! $name) {
                // At least a name is needed to submit a value.
                continue;
            }

            yield $name;
        }
    }

    /**
     * Set values and element checked and selected states
     */
    private function setData(array $data, ?bool $force = null) : void
    {
        $force = $force ?? false;

        /** @var DOMElement $node */
        foreach ($this->getNodeList() as $name => $node) {
            if (! array_key_exists($name, $data)) {
                // No value set for this element
                continue;
            }

            $value = $data[$name];

            $reuseSubmittedValue = filter_var(
                $node->getAttribute('data-reuse-submitted-value'),
                FILTER_VALIDATE_BOOLEAN
            );

            if (! $reuseSubmittedValue && $force === false) {
                // Don't need to set the value
                continue;
            }

            if (in_array($node->getAttribute('type'), ['checkbox', 'radio'], true)) {
                if ($value === $node->getAttribute('value')) {
                    $node->setAttribute('checked', 'checked');
                } else {
                    $node->removeAttribute('checked');
                }
            } elseif ($node->nodeName === 'select') {
                /** @var DOMElement $option */
                foreach ($node->getElementsByTagName('option') as $option) {
                    if ($value === $option->getAttribute('value')) {
                        $option->setAttribute('selected', 'selected');
                    } else {
                        $option->removeAttribute('selected');
                    }
                }
            } elseif ($node->nodeName === 'input') {
                // Set value for input elements
                $node->setAttribute('value', $value);
            } elseif ($node->nodeName === 'textarea') {
                $node->nodeValue = $value;
            }
        }
    }

    /**
     * Set validation messages, bootstrap style
     */
    private function setMessages(array $data) : void
    {
        /**
         * @var string $name
         * @var array  $errors
         */
        foreach ($data as $name => $errors) {
            // Not sure if this can be optimized and create the DOMXPath only once.
            // At this point the dom is constantly changing.
            $xpath = new DOMXPath($this->document);
            // Get all elements with the name
            $nodeList = $xpath->query(sprintf('//*[@name="%1$s"] | //*[@data-input-name="%1$s"]', $name));

            if ($nodeList->length === 0) {
                // No element found for this element ???
                continue;
            }

            // Get first element only
            $node = $nodeList->item(0);

            /** @var DOMElement $parent */
            $parent = $node->parentNode;
            if (strpos($parent->getAttribute('class'), $this->cssHasErrorClass) === false) {
                // Set error class to parent
                $class = trim($parent->getAttribute('class') . ' ' . $this->cssHasErrorClass);
                $parent->setAttribute('class', $class);
            }

            // Inject error messages
            foreach ($errors as $code => $message) {
                $div = $this->document->createElement('div');
                $div->setAttribute('class', $this->cssErrorClass);
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
