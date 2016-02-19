<?php

namespace Xtreamwayz\HTMLFormValidator\FormElement;

use Zend\Validator\Identical;

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
        $this->attachValidatorByName(Identical::class, [
            'token' => $this->element->getAttribute('value'),
        ]);
    }
}
