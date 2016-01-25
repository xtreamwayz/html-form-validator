<?php

namespace Xtreamwayz\HTMLFormValidator\FormElement;

use DOMElement;
use Zend\InputFilter\InputInterface;
use Zend\Validator;
use Zend\Filter;

abstract class AbstractFormElement
{
    public function __invoke(DOMElement $element, InputInterface $input)
    {
        // Build input validator chain for element
        $this->attachValidators($input, $element);
        //$this->attachFilters($input, $element->getAttribute('data-filters'));

        // TODO: Add custom validator(s) -> $dataValidator = $input->getAttribute('data-validator');

        // Can't be empty if it has a required attribute
        if ($element->hasAttribute('required')) {
            $input->getValidatorChain()->attach(new Validator\NotEmpty());
        } else {
            $input->setRequired(false);
        }

        // Validate regex patter
        if ($pattern = $element->getAttribute('pattern')) {
            $input->getValidatorChain()->attach(new Validator\Regex(sprintf('/%s/', $pattern)));
        }
    }

    abstract function attachValidators(InputInterface $input, DOMElement $element);

    public function attachFilters(InputInterface $input, $filters)
    {
        $filters = explode(',', $filters);
        foreach ($filters as $filter) {
            if ($filter) {
                $class = 'Zend\\Filter\\' . $filter;
                $input->getFilterChain()->attach(new $class);
            }
        }
    }
}
