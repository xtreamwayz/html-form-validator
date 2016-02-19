<?php

namespace Xtreamwayz\HTMLFormValidator\FormElement;

use Zend\Filter\StringToLower;
use Zend\Validator\Regex;

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
        $this->attachValidatorByName(Regex::class, [
            'pattern' => '/^\#[a-fA-Z0-9]{6}$/',
        ]);
    }
}
