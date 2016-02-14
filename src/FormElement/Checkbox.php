<?php

namespace Xtreamwayz\HTMLFormValidator\FormElement;

class Checkbox extends AbstractFormElement
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
        $this->attachValidatorByName('identical', [
            'token' => $this->element->getAttribute('value'),
        ]);
    }
}
