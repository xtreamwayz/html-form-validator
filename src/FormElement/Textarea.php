<?php

namespace Xtreamwayz\HTMLFormValidator\FormElement;

class Textarea extends AbstractFormElement
{
    /**
     * @inheritdoc
     */
    protected function attachDefaultFilters()
    {
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
    }
}
