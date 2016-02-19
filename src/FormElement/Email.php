<?php

namespace Xtreamwayz\HTMLFormValidator\FormElement;

use Zend\Filter\StringTrim;
use Zend\Filter\StripNewlines;
use Zend\Validator\EmailAddress;
use Zend\Validator\Regex;
use Zend\Validator\StringLength;

class Email extends AbstractFormElement
{
    /**
     * @inheritdoc
     */
    protected function attachDefaultFilters()
    {
        $this->attachFilterByName(StripNewlines::class);
        $this->attachFilterByName(StringTrim::class);
    }

    /**
     * @inheritdoc
     */
    protected function attachDefaultValidators()
    {
        $this->attachValidatorByName(EmailAddress::class, [
            'useMxCheck' => filter_var(
                $this->element->getAttribute('data-validator-use-mx-check'),
                FILTER_VALIDATE_BOOLEAN
            ),
        ]);

        if ($this->element->hasAttribute('minlength') || $this->element->hasAttribute('maxlength')) {
            $this->attachValidatorByName(StringLength::class, [
                'min' => $this->element->getAttribute('minlength') ?: 0,
                'max' => $this->element->getAttribute('maxlength') ?: null,
            ]);
        }

        if ($this->element->hasAttribute('pattern')) {
            $this->attachValidatorByName(Regex::class, [
                'pattern' => sprintf('/%s/', $this->element->getAttribute('pattern')),
            ]);
        }
    }
}
