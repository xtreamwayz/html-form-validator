<?php

namespace Xtreamwayz\HTMLFormValidator\FormElement;

use Zend\Validator\Regex;

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
        $this->attachValidatorByName(Regex::class, [
            'pattern' => '/(\d{4})-W(\d{2})/',
        ]);
    }
}
