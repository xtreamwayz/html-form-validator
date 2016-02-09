<?php

namespace Xtreamwayz\HTMLFormValidator\FormElement;

use DOMElement;
use Zend\InputFilter\InputInterface;
use Zend\Validator;

class Checkbox extends AbstractFormElement
{
    /**
     * @inheritdoc
     */
    protected function attachDefaultValidators(InputInterface $input, DOMElement $element)
    {
        // Make sure the submitted value is the same as the original
        $input->getValidatorChain()
              ->attach(new Validator\Identical($element->getAttribute('value')));
    }
}
