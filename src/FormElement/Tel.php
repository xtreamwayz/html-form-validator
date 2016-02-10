<?php

namespace Xtreamwayz\HTMLFormValidator\FormElement;

use DOMElement;
use Zend\I18n\Validator;
use Zend\InputFilter\InputInterface;

class Tel extends AbstractFormElement
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

        $country = $element->getAttribute('data-country');

        $input->getValidatorChain()
              ->attach(new Validator\PhoneNumber(['country' => $country]));
    }
}
