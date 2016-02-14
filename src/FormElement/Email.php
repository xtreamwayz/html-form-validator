<?php

namespace Xtreamwayz\HTMLFormValidator\FormElement;

use Zend\Filter\StringTrim;
use Zend\Filter\StripNewlines;

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
        if ($this->element->hasAttribute('maxlength')) {
            $this->attachValidatorByName('stringlength', [
                'max' => $this->element->getAttribute('maxlength'),
            ]);
        }

        if ($this->element->hasAttribute('pattern')) {
            $this->attachValidatorByName('regex', [
                'pattern' => sprintf('/%s/', $this->element->getAttribute('pattern')),
            ]);
        }

        $this->attachValidatorByName('emailaddress', [
            'useMxCheck' => filter_var(
                $this->element->getAttribute('data-validator-use-mx-check'),
                FILTER_VALIDATE_BOOLEAN
            ),
        ]);
    }
}
