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
        $stringlengthOptions = [];
        if ($this->element->hasAttribute('maxlength')) {
            $stringlengthOptions['max'] = $this->element->getAttribute('maxlength');
        }
        if ($this->element->hasAttribute('minlength')) {
            $stringlengthOptions['min'] = $this->element->getAttribute('minlength');
        }
        if (!empty($stringlengthOptions)) {
            $this->attachValidatorByName('stringlength', $stringlengthOptions);
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
