<?php

namespace Xtreamwayz\HTMLFormValidator\FormElement;

class Date extends AbstractFormElement
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
            'format' => 'Y-m-d',
        ]);
    }
}
