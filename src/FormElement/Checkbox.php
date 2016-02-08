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
    }
}
