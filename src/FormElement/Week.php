<?php

namespace Xtreamwayz\HTMLFormValidator\FormElement;

use DOMElement;
use Zend\InputFilter\InputInterface;
use Zend\Validator;

class Week extends AbstractFormElement
{
    /**
     * @inheritdoc
     */
    protected function attachDefaultValidators(InputInterface $input, DOMElement $element)
    {
        $input->getValidatorChain()
              ->attach(new Validator\Regex('/(\d{4})-W(\d{2})/'));
    }
}
