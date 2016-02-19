<?php

namespace Xtreamwayz\HTMLFormValidator\FormElement;

use Zend\Validator\Date as DateValidator;

class DateTimeLocal extends AbstractFormElement
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
            'format' => 'Y-m-d\TH:i',
        ]);
    }
}
