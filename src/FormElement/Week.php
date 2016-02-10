<?php

namespace Xtreamwayz\HTMLFormValidator\FormElement;

use DOMElement;
use Zend\InputFilter\InputInterface;

class Week extends AbstractFormElement
{
    /**
     * @inheritdoc
     */
    protected function attachDefaultValidators(InputInterface $input, DOMElement $element)
    {
        $this->attachValidatorByName($input, 'regex', [
            'pattern' => '/(\d{4})-W(\d{2})/',
        ]);
    }
}
