<?php

namespace Xtreamwayz\HTMLFormValidator\FormElement;

use DOMDocument;
use DOMElement;
use Xtreamwayz\HTMLFormValidator\ValidatorManager;
use Zend\InputFilter\InputInterface;
use Zend\Validator;

abstract class AbstractFormElement
{
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
        $this->document = $document;

        // Build input validator chain for element
        $this->attachDefaultValidators($input, $element);
        $this->attachValidators($input, $element);
        $this->attachFilters($input, $element);

        // Enforce required and allow empty properties
        if ($element->hasAttribute('required') || $element->getAttribute('aria-required') == 'true') {
            $input->setRequired(true);
            $input->setAllowEmpty(false);
            // Attach NotEmpty validator manually so it won't use the plugin manager, which fails for servicemanager 3
            $input->getValidatorChain()->attach(new Validator\NotEmpty());
        } else {
            // Enforce properties so it doesn't try to load NotEmpty, which fails for servicemanager 3
            $input->setRequired(false);
            $input->setAllowEmpty(true);
        }

        // Validate regex pattern
        if ($pattern = $element->getAttribute('pattern')) {
            $input->getValidatorChain()->attach(new Validator\Regex(sprintf('/^%s$/', $pattern)));
        }
    }

    /**
     * Attach default validators for specific form element
     *
     * @param InputInterface $input
     * @param DOMElement     $element
     *
     * @return void
     */
    abstract protected function attachDefaultValidators(InputInterface $input, DOMElement $element);

    /**
     * Attach validators from data-validators attribute
     *
     * @param InputInterface $input
     * @param DOMElement     $element
     */
    protected function attachValidators(InputInterface $input, DOMElement $element)
    {
        $dataValidators = $element->getAttribute('data-validators');
        if (!$dataValidators) {
            return;
        }

        foreach ($this->parseDataAttribute($dataValidators) as $validator => $options) {
            // TODO: Needs a fix when zend-validator works with servicemanager 3
            if (ValidatorManager::hasValidator($validator)) {
                $class = ValidatorManager::getValidator($validator);
                $input->getValidatorChain()->attach(new $class($options));
            }
        }
    }

    /**
     * Attach filters from data-filters attribute
     *
     * @param InputInterface $input
     * @param DOMElement     $element
     */
    protected function attachFilters(InputInterface $input, DOMElement $element)
    {
        $dataFilters = $element->getAttribute('data-filters');
        if (!$dataFilters) {
            return;
        }

        foreach ($this->parseDataAttribute($dataFilters) as $filter => $options) {
            $input->getFilterChain()->attachByName($filter, $options);
        }
    }

    /**
     * Parse data attribute value for validators, filters and options
     *
     * @param $dataAttribute
     *
     * @return \Generator
     */
    protected function parseDataAttribute($dataAttribute)
    {
        preg_match_all("/([a-zA-Z]+)([^|]*)/", $dataAttribute, $matches, PREG_SET_ORDER);

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
