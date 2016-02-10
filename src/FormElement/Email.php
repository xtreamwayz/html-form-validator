<?php

namespace Xtreamwayz\HTMLFormValidator\FormElement;

use DOMElement;
use Zend\InputFilter\InputInterface;
use Zend\Validator;

class Email extends AbstractFormElement
{
    /**
     * @inheritdoc
     */
    protected function attachDefaultValidators(InputInterface $input, DOMElement $element)
    {
        if ($element->hasAttribute('maxlength')) {
            $this->attachValidatorByName($input, 'stringlength', [
                'max' => $element->getAttribute('maxlength'),
            ]);
        }

        if ($element->hasAttribute('pattern')) {
            $this->attachValidatorByName($input, 'regex', [
                'pattern' => sprintf('/%s/', $element->getAttribute('pattern')),
            ]);
        }

        $input->getValidatorChain()
              ->attach(
                  new Validator\EmailAddress([
                      'useMxCheck' => filter_var(
                          $element->getAttribute('data-validator-use-mx-check'),
                          FILTER_VALIDATE_BOOLEAN
                      ),
                  ])
              );
    }
}
