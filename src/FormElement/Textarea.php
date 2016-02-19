<?php

namespace Xtreamwayz\HTMLFormValidator\FormElement;

use Zend\Validator\StringLength;

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
        if ($this->element->hasAttribute('minlength') || $this->element->hasAttribute('maxlength')) {
            $this->attachValidatorByName(StringLength::class, [
                'min'      => $this->element->getAttribute('minlength') ?: 0,
                'max'      => $this->element->getAttribute('maxlength') ?: null,
            ]);
        }
    }
}
