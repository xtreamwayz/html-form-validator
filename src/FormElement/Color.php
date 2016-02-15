<?php

namespace Xtreamwayz\HTMLFormValidator\FormElement;

use Zend\Filter\StringToLower;

class Color extends AbstractFormElement
{
    /**
     * @inheritdoc
     */
    protected function attachDefaultFilters()
    {
        $this->attachFilterByName(StringToLower::class);
    }

    /**
     * @inheritdoc
     */
    protected function attachDefaultValidators()
    {
        $this->attachValidatorByName('regex', [
            'pattern' => '/^\#[a-fA-Z0-9]{6}$/',
        ]);
    }
}
