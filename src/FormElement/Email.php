<?php

namespace Xtreamwayz\HTMLFormValidator\FormElement;

use DOMElement;
use Zend\InputFilter\InputInterface;
use Zend\Validator;

class Email extends AbstractFormElement
{
    public function attachValidators(InputInterface $input, DOMElement $element)
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
