<?php

declare(strict_types = 1);

namespace Xtreamwayz\HTMLFormValidator\FormElement;

use DateInterval;
use DateTimeZone;
use Xtreamwayz\HTMLFormValidator\FormElement\DateTime as DateTimeElement;
use Zend\Validator\DateStep as DateStepValidator;

class Date extends DateTimeElement
{
    protected $format = 'Y-m-d';

    protected function getStepValidator() : array
    {
        $stepValue = $this->node->getAttribute('step') ?: 1; // Days
        $baseValue = $this->node->getAttribute('min') ?: date($this->format, 0);

        return [
            'name'    => DateStepValidator::class,
            'options' => [
                'format'    => $this->format,
                'baseValue' => $baseValue,
                'step'      => new DateInterval("P{$stepValue}D"),
                'timezone'  => new DateTimeZone('UTC'),
            ],
        ];
    }
}
