<?php

namespace Xtreamwayz\HTMLFormValidator\FormElement;

use DOMElement;

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

        $this->attachValidatorByName('inarray', [
            'haystack' => $haystack,
        ]);
    }
}
