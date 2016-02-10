<?php

namespace Xtreamwayz\HTMLFormValidator\FormElement;

use DOMElement;
use Zend\InputFilter\InputInterface;
use Zend\Validator;

class Date extends AbstractFormElement
{
    /**
     * @inheritdoc
     */
    protected function attachDefaultValidators(InputInterface $input, DOMElement $element)
    {

        $defaultDateFormat = 'Y-m-d';
        $dateFormat = !empty($element->getAttribute('date-format')) ?
            $element->getAttribute('date-format') :
            $defaultDateFormat;

        $input->getValidatorChain()
            ->attach(new Validator\Date(['format' => $dateFormat]));
    }
}
