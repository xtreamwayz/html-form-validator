<?php

namespace Xtreamwayz\HTMLFormValidator\InputType;

use DOMElement;
use Zend\InputFilter\Input;
use Zend\Validator;

abstract class AbstractInputType
{
    public function __invoke(DOMElement $element)
    {
        // Build input validator chain for element
        $input = new Input($element->getAttribute('name'));
        $this->attachValidators($input, $element);
        $this->attachFilters($input, $element->getAttribute('data-filters'));

        // TODO: Add custom validator(s) -> $dataValidator = $input->getAttribute('data-validator');

        if ($element->hasAttribute('required')) {
            $input->getValidatorChain()->attach(new Validator\NotEmpty());
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
