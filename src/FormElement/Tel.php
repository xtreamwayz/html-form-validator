<?php

namespace Xtreamwayz\HTMLFormValidator\FormElement;

use Zend\Filter\StripNewlines;

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

        $this->attachValidatorByName('phonenumber', [
            'country' => $this->element->getAttribute('data-country'),
        ]);
    }
}
