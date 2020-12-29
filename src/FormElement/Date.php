<?php

declare(strict_types=1);

namespace Xtreamwayz\HTMLFormValidator\FormElement;

use DateInterval;
use DateTimeZone;
use Laminas\Validator\DateStep as DateStepValidator;
use Xtreamwayz\HTMLFormValidator\FormElement\DateTime as DateTimeElement;

use function date;
use function sprintf;

class Date extends DateTimeElement
{
    protected string $format = 'Y-m-d';

    protected function getStepValidator(): array
    {
        $stepValue = $this->node->getAttribute('step') ?: 1; // Days
        $baseValue = $this->node->getAttribute('min') ?: date($this->format, 0);

        return [
            'name'    => DateStepValidator::class,
            'options' => [
                'format'    => $this->format,
                'baseValue' => $baseValue,
                'step'      => new DateInterval(sprintf('P%dD', $stepValue)),
                'timezone'  => new DateTimeZone('UTC'),
            ],
        ];
    }
}
