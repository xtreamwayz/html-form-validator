<?php

namespace Xtreamwayz\HTMLFormValidator\FormElement;

use Zend\Filter\StripNewlines;
use Zend\I18n\Validator\PhoneNumber;
use Zend\Validator\Regex;
use Zend\Validator\StringLength;

class Tel extends AbstractFormElement
{
    /**
     * @inheritdoc
     */
    protected function attachDefaultFilters()
    {
        $this->attachFilterByName(StripNewlines::class);
    }

    /**
     * @inheritdoc
     */
    protected function attachDefaultValidators()
    {
        $this->attachValidatorByName(PhoneNumber::class, [
            'country' => $this->element->getAttribute('data-country'),
        ]);

        if ($this->element->hasAttribute('minlength') || $this->element->hasAttribute('maxlength')) {
            $this->attachValidatorByName(StringLength::class, [
                'min'      => $this->element->getAttribute('minlength') ?: 0,
                'max'      => $this->element->getAttribute('maxlength') ?: null,
            ]);
        }

        if ($this->element->hasAttribute('pattern')) {
            $this->attachValidatorByName(Regex::class, [
                'pattern' => sprintf('/%s/', $this->element->getAttribute('pattern')),
            ]);
        }
    }
}
