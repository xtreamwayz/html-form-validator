<?php

namespace Xtreamwayz\HTMLFormValidator\FormElement;

use \Zend\Validator\Date as DateValidator;

class Month extends AbstractFormElement
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
            'format' => 'Y-m',
        ]);
    }
}
