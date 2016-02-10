<?php

namespace Xtreamwayz\HTMLFormValidator\FormElement;

use DOMElement;
use Zend\InputFilter\InputInterface;

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

        if ($element->hasAttribute('pattern')) {
            $this->attachValidatorByName($input, 'regex', [
                'pattern' => sprintf('/%s/', $element->getAttribute('pattern')),
            ]);
        }
    }
}
