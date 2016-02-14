<?php

namespace Xtreamwayz\HTMLFormValidator\FormElement;

class Time extends AbstractFormElement
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
        $this->attachValidatorByName('date', [
            'format' => 'H:i',
        ]);
    }
}
