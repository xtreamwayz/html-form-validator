<?php

namespace Xtreamwayz\HTMLFormValidator\FormElement;

use \Zend\Validator\Date as DateValidator;

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
        $this->attachValidatorByName(DateValidator::class, [
            'format' => 'H:i',
        ]);
    }
}
