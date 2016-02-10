<?php

namespace Xtreamwayz\HTMLFormValidator\FormElement;

use DOMElement;
use Zend\InputFilter\InputInterface;
use Zend\Validator;

class Password extends AbstractFormElement
{
    /**
     * @inheritdoc
     */
    protected function attachDefaultValidators(InputInterface $input, DOMElement $element)
    {
        if ($element->hasAttribute('maxlength')) {
            $this->attachValidatorByName($input, 'stringlength', [
                'max' => $element->getAttribute('maxlength'),
            ]);
        }
    }
}
