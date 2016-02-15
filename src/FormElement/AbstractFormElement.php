<?php

namespace Xtreamwayz\HTMLFormValidator\FormElement;

use DOMDocument;
use DOMElement;
use Xtreamwayz\HTMLFormValidator\ValidatorManager;
use Zend\InputFilter\InputInterface;

abstract class AbstractFormElement
{
    /**
     * @var DOMElement
     */
    protected $element;

    /**
     * @var InputInterface
     */
    protected $input;

    /**
     * @var DOMDocument
     */
    protected $document;

    /**
     * Process element and attach validators and filters
     *
     * @param DOMElement     $element
     * @param InputInterface $input
     * @param DOMDocument    $document
     */
    public function __invoke(DOMElement $element, InputInterface $input, DOMDocument $document)
    {
        $this->element = $element;
        $this->input = $input;
        $this->document = $document;

        // Build input validator chain for element
        $this->attachDefaultFilters();
        $this->attachFilters();
        $this->attachDefaultValidators();
        $this->attachValidators();

        // Enforce required and allow empty properties
        if ($this->element->hasAttribute('required') || $this->element->getAttribute('aria-required') == 'true') {
            $this->input->setRequired(true);
            // Attach NotEmpty validator manually so it won't use the plugin manager, which fails for servicemanager 3
            $this->attachValidatorByName('notempty');
        } else {
            $this->input->setRequired(false);
            // Enforce properties so it doesn't try to load NotEmpty, which fails for servicemanager 3
            $this->input->setAllowEmpty(true);
        }
    }

    /**
     * Attach default filters for specific form element
     *
     * @return void
     */
    abstract protected function attachDefaultFilters();

    /**
     * Attach filters from data-filters attribute
     */
    protected function attachFilters()
    {
        $dataFilters = $this->element->getAttribute('data-filters');
        if (!$dataFilters) {
            return;
        }

        foreach ($this->parseDataAttribute($dataFilters) as $filter => $options) {
            $this->attachFilterByName($filter, $options);
        }
    }

    /**
     * Attach filter by name
     *
     * @param string $name
     * @param array  $options
     */
    protected function attachFilterByName($name, array $options = [])
    {
        $this->input->getFilterChain()->attachByName($name, $options);
    }

    /**
     * Attach default validators for specific form element
     */
    abstract protected function attachDefaultValidators();

    /**
     * Attach validators from data-validators attribute
     */
    protected function attachValidators()
    {
        $dataValidators = $this->element->getAttribute('data-validators');
        if (!$dataValidators) {
            return;
        }

        foreach ($this->parseDataAttribute($dataValidators) as $validator => $options) {
            $this->attachValidatorByName($validator, $options);
        }
    }

    /**
     * Attach validator by name
     *
     * @param string $name
     * @param array  $options
     */
    protected function attachValidatorByName($name, array $options = [])
    {
        // Needs to be refactored after zend-validator got an update
        $class = ValidatorManager::getValidator($name);
        $this->input->getValidatorChain()->attach(new $class($options));
    }

    /**
     * Parse data attribute value for validators, filters and options
     *
     * @param string $dataAttribute
     *
     * @return \Generator
     */
    protected function parseDataAttribute($dataAttribute)
    {
        preg_match_all("/([a-zA-Z]+)([^|]*)/", $dataAttribute, $matches, PREG_SET_ORDER);

        if (!is_array($matches) || empty($matches)) {
            return;
        }

        foreach ($matches as $match) {
            $validator = $match[1];
            $options = [];

            if (isset($match[2])) {
                $allOptions = explode(',', $match[2]);
                foreach ($allOptions as $option) {
                    $option = explode(':', $option);
                    if (isset($option[0]) && isset($option[1])) {
                        $options[trim($option[0], ' {}\'\"')] = trim($option[1], ' {}\'\"');
                    }
                }
            }

            yield $validator => $options;
        }
    }
}
