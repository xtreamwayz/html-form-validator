<?php

namespace Xtreamwayz\HTMLFormValidator\FormElement;

class Week extends AbstractFormElement
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
        $this->attachValidatorByName('regex', [
            'pattern' => '/(\d{4})-W(\d{2})/',
        ]);
    }
}
