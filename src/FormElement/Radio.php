<?php

namespace Xtreamwayz\HTMLFormValidator\FormElement;

use DOMElement;
use DOMXPath;
use Zend\InputFilter\InputInterface;

class Radio extends AbstractFormElement
{
    /**
     * @inheritdoc
     */
    protected function attachDefaultValidators(InputInterface $input, DOMElement $element)
    {
        $haystack = [];
        $name = $element->getAttribute('name');
        $xpath = new DOMXPath($this->document);

        /** @var DOMElement $node */
        foreach ($xpath->query('//input[@type="radio"][@name="' . $name . '"]') as $node) {
            $haystack[] = $node->getAttribute('value');
        }

        $this->attachValidatorByName($input, 'inarray', [
            'haystack' => $haystack,
        ]);
    }
}
