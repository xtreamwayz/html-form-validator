<?php

namespace Xtreamwayz\HTMLFormValidator\FormElement;

use DOMElement;
use DOMXPath;
use Zend\Validator\InArray;

class Radio extends AbstractFormElement
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
        $name = $this->element->getAttribute('name');
        $xpath = new DOMXPath($this->document);

        /** @var DOMElement $node */
        foreach ($xpath->query('//input[@type="radio"][@name="' . $name . '"]') as $node) {
            $haystack[] = $node->getAttribute('value');
        }

        $this->attachValidatorByName(InArray::class, [
            'haystack' => $haystack,
        ]);
    }
}
