<?php

namespace Xtreamwayz\HTMLFormValidator\InputType;

use DOMElement;
use Zend\InputFilter\Input;
use Zend\Validator;

class Email extends AbstractInputType
{
    public function attachValidators(Input $input, DOMElement $element)
    {
        $input->getValidatorChain()
              ->attach(
                  new Validator\EmailAddress(
                      [
                          'useMxCheck' => filter_var(
                              $element->getAttribute('data-validator-use-mx-check'),
                              FILTER_VALIDATE_BOOLEAN
                          ),
                      ]
                  )
              )
        ;
    }
}
