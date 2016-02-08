<?php

namespace Xtreamwayz\HTMLFormValidator\FormElement;

use DOMElement;
use Zend\InputFilter\InputInterface;
use Zend\Validator;

class DataList extends AbstractFormElement
{
    /**
     * @inheritdoc
     */
    protected function attachDefaultValidators(InputInterface $input, DOMElement $element)
    {
        $haystack = [];

        $list = $element->getAttribute('list');
        $dataList = $this->document->getElementById($list);
        if (!$dataList) {
            throw new \OutOfRangeException('Cannot find DataList element with id: ' . $list);
        }

        /** @var DOMElement $node */
        foreach($dataList->getElementsByTagName('option') as $node){
            $haystack[] = $node->getAttribute('value');
        }

        $input->getValidatorChain()
              ->attach(new Validator\InArray([
                  'haystack' => $haystack,
              ]));
    }
}
