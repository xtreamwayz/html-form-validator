<?php

namespace Xtreamwayz\HTMLFormValidator\FormElement;

use DOMElement;
use Zend\InputFilter\InputInterface;
use Zend\Validator;

class Time extends AbstractFormElement
{
    /**
     * @inheritdoc
     */
    protected function attachDefaultValidators(InputInterface $input, DOMElement $element)
    {
        $input->getValidatorChain()
              ->attach(new Validator\Date(['format' => 'H:i']));
    }
}
