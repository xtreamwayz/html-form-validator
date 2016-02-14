<?php

namespace Xtreamwayz\HTMLFormValidator\FormElement;

use DOMElement;
use Zend\Filter\StringTrim;
use Zend\Filter\StripNewlines;
use Zend\InputFilter\InputInterface;

class Email extends AbstractFormElement
{
    /**
     * @inheritdoc
     */
    protected function attachDefaultValidators(InputInterface $input, DOMElement $element)
    {
        $input->getFilterChain()->attachByName(StripNewlines::class);
        $input->getFilterChain()->attachByName(StringTrim::class);

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

        $this->attachValidatorByName($input, 'emailaddress', [
            'useMxCheck' => filter_var(
                $element->getAttribute('data-validator-use-mx-check'),
                FILTER_VALIDATE_BOOLEAN
            ),
        ]);
    }
}
