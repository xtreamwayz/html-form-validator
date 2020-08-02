<?php

declare(strict_types=1);

namespace Xtreamwayz\HTMLFormValidator\FormElement;

use DOMDocument;
use DOMElement;
use Generator;
use Laminas\InputFilter\InputProviderInterface;
use const PREG_SET_ORDER;
use function explode;
use function preg_match_all;
use function trim;

class BaseFormElement implements InputProviderInterface
{
    /** @var DOMElement */
    protected $node;

    /** @var DOMDocument */
    protected $document;

    public function __construct(DOMElement $node, DOMDocument $document)
    {
        $this->node     = $node;
        $this->document = $document;
    }

    /**
     * Should return an array specification compatible with
     * {@link Laminas\InputFilter\Factory::createInput()}.
     *
     * @return array
     */
    public function getInputSpecification() : array
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

    protected function getName() : string
    {
        $name = $this->node->getAttribute('name');
        if (! $name) {
            $name = $this->node->getAttribute('data-input-name');
        }

        return $name;
    }

    protected function isRequired() : bool
    {
        return $this->node->hasAttribute('required') || $this->node->getAttribute('aria-required') === 'true';
    }

    protected function getFilters() : array
    {
        return [];
    }

    protected function getValidators() : array
    {
        return [];
    }

    /**
     * Parse data attribute value for validators, filters and options
     */
    protected function parseDataAttribute(string $dataAttribute) : Generator
    {
        $matches = [];
        preg_match_all('/([a-zA-Z]+)([^|]*)/', $dataAttribute, $matches, PREG_SET_ORDER);

        foreach ($matches as $match) {
            $name    = $match[1];
            $options = [];

            if (isset($match[2])) {
                $allOptions = explode(',', $match[2]);
                foreach ($allOptions as $option) {
                    $option = explode(':', $option);
                    if (! isset($option[0], $option[1])) {
                        continue;
                    }

                    $options[trim($option[0], ' {}\'\"')] = trim($option[1], ' {}\'\"');
                }
            }

            yield $name => $options;
        }
    }
}
