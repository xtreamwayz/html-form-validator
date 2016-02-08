<?php

namespace Xtreamwayz\HTMLFormValidator\FormElement;

use DOMElement;
use Zend\InputFilter\InputInterface;
use Zend\Validator;

class Select extends AbstractFormElement
{
    /**
     * @inheritdoc
     */
    protected function attachDefaultValidators(InputInterface $input, DOMElement $element)
    {
        $haystack = [];

        /** @var DOMElement $node */
        foreach($element->getElementsByTagName('option') as $node){
            $haystack[] = $node->getAttribute('value');
        }

        var_dump($haystack);

        $input->getValidatorChain()
              ->attach(new Validator\InArray([
                  'haystack' => $haystack,
              ]));
    }
}
