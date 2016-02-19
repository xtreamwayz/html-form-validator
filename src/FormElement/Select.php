<?php

namespace Xtreamwayz\HTMLFormValidator\FormElement;

use DOMElement;
use Zend\Validator\InArray;

class Select extends AbstractFormElement
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
        $haystack = [];

        /** @var DOMElement $node */
        foreach ($this->element->getElementsByTagName('option') as $node) {
            $haystack[] = $node->getAttribute('value');
        }

        $this->attachValidatorByName(InArray::class, [
            'haystack' => $haystack,
        ]);
    }
}
