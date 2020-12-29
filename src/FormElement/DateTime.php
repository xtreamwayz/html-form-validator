<?php

declare(strict_types=1);

namespace Xtreamwayz\HTMLFormValidator\FormElement;

use DateInterval;
use Laminas\Validator\Date as DateValidator;
use Laminas\Validator\DateStep as DateStepValidator;
use Laminas\Validator\GreaterThan as GreaterThanValidator;
use Laminas\Validator\LessThan as LessThanValidator;

use function date;
use function sprintf;

class DateTime extends BaseFormElement
{
    /** @var string */
    protected $format = 'Y-m-d\TH:i';

    protected function getValidators(): array
    {
        $validators   = [];
        $validators[] = $this->getDateValidator();

        if ($this->node->hasAttribute('min')) {
            $validators[] = [
                'name'    => GreaterThanValidator::class,
                'options' => [
                    'min'       => $this->node->getAttribute('min'),
                    'inclusive' => true,
                ],
            ];
        }

        if ($this->node->hasAttribute('max')) {
            $validators[] = [
                'name'    => LessThanValidator::class,
                'options' => [
                    'max'       => $this->node->getAttribute('max'),
                    'inclusive' => true,
                ],
            ];
        }

        if (
            ! $this->node->hasAttribute('step')
            || $this->node->getAttribute('step') !== 'any'
        ) {
            $validators[] = $this->getStepValidator();
        }

        return $validators;
    }

    protected function getDateValidator(): array
    {
        return [
            'name'    => DateValidator::class,
            'options' => [
                'format' => $this->format,
            ],
        ];
    }

    protected function getStepValidator(): array
    {
        $stepValue = $this->node->getAttribute('step') ?: 1; // Minutes
        $baseValue = $this->node->getAttribute('min') ?: date($this->format, 0);

        return [
            'name'    => DateStepValidator::class,
            'options' => [
                'format'    => $this->format,
                'baseValue' => $baseValue,
                'step'      => new DateInterval(sprintf('PT%dM', $stepValue)),
            ],
        ];
    }
}
