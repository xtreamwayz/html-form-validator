<?php

namespace Xtreamwayz\HTMLFormValidator\FormElement;

use DOMElement;
use Zend\Filter\StringToLower;
use Zend\InputFilter\InputInterface;

class Color extends AbstractFormElement
{
    /**
     * @inheritdoc
     */
    protected function attachDefaultValidators(InputInterface $input, DOMElement $element)
    {
        $input->getFilterChain()->attachByName(StringToLower::class);

        $this->attachValidatorByName($input, 'regex', [
            'pattern' => '/^\#[a-fA-Z0-9]{6}$/',
        ]);
    }
}
