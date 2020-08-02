<?php

declare(strict_types=1);

namespace Xtreamwayz\HTMLFormValidator\FormElement;

use Laminas\Filter\StripNewlines as StripNewlinesFilter;
use Laminas\Validator\Regex as RegexValidator;
use Laminas\Validator\StringLength as StringLengthValidator;
use function sprintf;

class Text extends BaseFormElement
{
    protected function getFilters() : array
    {
        return [
            ['name' => StripNewlinesFilter::class],
        ];
    }

    protected function getValidators() : array
    {
        $validators = [];

        if ($this->node->hasAttribute('minlength') || $this->node->hasAttribute('maxlength')) {
            $validators[] = [
                'name'    => StringLengthValidator::class,
                'options' => [
                    'min' => $this->node->getAttribute('minlength') ?: 0,
                    'max' => $this->node->getAttribute('maxlength') ?: null,
                ],
            ];
        }

        if ($this->node->hasAttribute('pattern')) {
            $validators[] = [
                'name'    => RegexValidator::class,
                'options' => [
                    'pattern' => sprintf('/%s/', $this->node->getAttribute('pattern')),
                ],
            ];
        }

        return $validators;
    }
}
