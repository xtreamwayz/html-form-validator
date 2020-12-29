<?php

declare(strict_types=1);

namespace Xtreamwayz\HTMLFormValidator\FormElement;

use DateInterval;
use Laminas\Validator\DateStep as DateStepValidator;
use Laminas\Validator\Regex as RegexValidator;
use Xtreamwayz\HTMLFormValidator\FormElement\DateTime as DateTimeElement;

use function sprintf;

class Week extends DateTimeElement
{
    protected function getDateValidator(): array
    {
        return [
            'name'    => RegexValidator::class,
            'options' => ['pattern' => '/^[0-9]{4}\-W[0-9]{2}$/'],
        ];
    }

    protected function getStepValidator(): array
    {
        $stepValue = $this->node->getAttribute('step') ?: 1; // Weeks
        $baseValue = $this->node->getAttribute('min') ?: '1970-W01';

        return [
            'name'    => DateStepValidator::class,
            'options' => [
                'format'    => 'Y-\WW',
                'baseValue' => $baseValue,
                'step'      => new DateInterval(sprintf('P%dW', $stepValue)),
            ],
        ];
    }
}
