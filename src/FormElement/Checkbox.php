<?php

namespace Xtreamwayz\HTMLFormValidator\FormElement;

use DOMElement;
use Zend\InputFilter\InputInterface;

class Checkbox extends AbstractFormElement
{
    /**
     * @inheritdoc
     */
    protected function attachDefaultValidators(InputInterface $input, DOMElement $element)
    {
        $this->attachValidatorByName($input, 'identical', [
            'token' => $element->getAttribute('value'),
        ]);
    }
}
