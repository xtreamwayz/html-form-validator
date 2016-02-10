<?php

namespace Xtreamwayz\HTMLFormValidator\FormElement;

use DOMElement;
use Zend\InputFilter\InputInterface;

class Select extends AbstractFormElement
{
    /**
     * @inheritdoc
     */
    protected function attachDefaultValidators(InputInterface $input, DOMElement $element)
    {
        $haystack = [];

        /** @var DOMElement $node */
        foreach ($element->getElementsByTagName('option') as $node) {
            $haystack[] = $node->getAttribute('value');
        }

        $this->attachValidatorByName($input, 'inarray', [
            'haystack' => $haystack,
        ]);
    }
}
