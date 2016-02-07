<?php

namespace Xtreamwayz\HTMLFormValidator\FormElement;

use DOMElement;
use Zend\InputFilter\InputInterface;
use Zend\Validator;

class Week extends AbstractFormElement
{
    /**
     * @inheritdoc
     */
    protected function attachDefaultValidators(InputInterface $input, DOMElement $element)
    {
        //preg_match("/(?<year>\d{4})-W(?<week>\d{2})/", $input_line, $result);

        // TODO: Strip alpha and validate date
        //$input->getValidatorChain()->attach(new Validator\Date(['format' => 'Y-W']));

        $input->getValidatorChain()
              ->attach(new Validator\Regex('/(\d{4})-W(\d{2})/'));
    }
}
