<?php
/**
 * html-form-validator (https://github.com/xtreamwayz/html-form-validator)
 *
 * @see       https://github.com/xtreamwayz/html-form-validator for the canonical source repository
 * @copyright Copyright (c) 2016 Geert Eltink (https://xtreamwayz.com/)
 * @license   https://github.com/xtreamwayz/html-form-validator/blob/master/LICENSE.md MIT
 */

namespace Xtreamwayz\HTMLFormValidator\FormElement;

use DOMDocument;
use DOMElement;
use Zend\InputFilter\InputProviderInterface;

class BaseFormElement implements InputProviderInterface
{
    /**
     * @var DOMElement
     */
    protected $node;

    /**
     * @var DOMDocument
     */
    protected $document;

    public function __construct(DOMElement $node, DOMDocument $document)
    {
        $this->node     = $node;
        $this->document = $document;
    }

    /**
     * Should return an array specification compatible with
     * {@link Zend\InputFilter\Factory::createInput()}.
     *
     * @return array
     */
    public function getInputSpecification()
    {
        $spec = [
            'name'     => $this->getName(),
            'required' => $this->isRequired(),
        ];

        $filters = $this->getFilters();

        if ($this->node->hasAttribute('data-filters')) {
            foreach ($this->parseDataAttribute($this->node->getAttribute('data-filters')) as $name => $options) {
                $filters[] = [
                    'name'    => $name,
                    'options' => $options,
                ];
            }
        }

        if (! empty($filters)) {
            $spec['filters'] = $filters;
        }

        $validators = $this->getValidators();

        if ($this->node->hasAttribute('data-validators')) {
            foreach ($this->parseDataAttribute($this->node->getAttribute('data-validators')) as $name => $options) {
                $validators[] = [
                    'name'    => $name,
                    'options' => $options,
                ];
            }
        }

        if (! empty($validators)) {
            $spec['validators'] = $validators;
        }

        return $spec;
    }

    protected function getName()
    {
        $name = $this->node->getAttribute('name');
        if (! $name) {
            $name = $this->node->getAttribute('data-input-name');
        }

        return $name;
    }

    protected function isRequired()
    {
        return $this->node->hasAttribute('required') || $this->node->getAttribute('aria-required') === 'true';
    }

    protected function getFilters()
    {
        return [];
    }

    protected function getValidators()
    {
        return [];
    }

    /**
     * Parse data attribute value for validators, filters and options
     *
     * @param string $dataAttribute
     *
     * @return \Generator
     */
    protected function parseDataAttribute($dataAttribute)
    {
        preg_match_all('/([a-zA-Z]+)([^|]*)/', $dataAttribute, $matches, PREG_SET_ORDER);

        foreach ($matches as $match) {
            $name    = $match[1];
            $options = [];

            if (isset($match[2])) {
                $allOptions = explode(',', $match[2]);
                foreach ($allOptions as $option) {
                    $option = explode(':', $option);
                    if (isset($option[0], $option[1])) {
                        $options[trim($option[0], ' {}\'\"')] = trim($option[1], ' {}\'\"');
                    }
                }
            }

            yield $name => $options;
        }
    }
}
