<?php

namespace Xtreamwayz\HTMLFormValidator\FormElement;

use DOMElement;
use Zend\InputFilter\Input;
use Zend\Validator;

abstract class AbstractFormElement
{
    public function __invoke(DOMElement $element)
    {
        // Build input validator chain for element
        $input = new Input($element->getAttribute('id'));
        $this->attachValidators($input, $element);
        $this->attachFilters($input, $element->getAttribute('data-filters'));

        // TODO: Add custom validator(s) -> $dataValidator = $input->getAttribute('data-validator');

        // Can't be empty if it has a required attribute
        if ($element->hasAttribute('required')) {
            $input->getValidatorChain()->attach(new Validator\NotEmpty());
        }

        // Validate regex patter
        if ($pattern = $element->getAttribute('pattern')) {
            $input->getValidatorChain()->attach(new Validator\Regex(sprintf('/%s/', $pattern)));
        }

        return $input;
    }

    abstract function attachValidators(Input $input, DOMElement $element);

    public function attachFilters(Input $input, $filters)
    {
        $filters = explode(',', $filters);
        foreach ($filters as $filter) {
            if ($filter) {
                $input->getFilterChain()->attachByName($filter);
            }
        }
    }
}
