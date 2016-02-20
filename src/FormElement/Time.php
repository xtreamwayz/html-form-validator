<?php

namespace Xtreamwayz\HTMLFormValidator\FormElement;

use DateInterval;
use Xtreamwayz\HTMLFormValidator\FormElement\DateTime as DateTimeElement;
use Zend\Validator\DateStep as DateStepValidator;

class Time extends DateTimeElement
{
    protected $format = 'H:i:s';

    protected function getStepValidator()
    {
        $stepValue = $this->node->getAttribute('step') ?: 60; // Seconds
        $baseValue = $this->node->getAttribute('min') ?: date($this->format, 0);

        return [
            'name'    => DateStepValidator::class,
            'options' => [
                'format'    => $this->format,
                'baseValue' => $baseValue,
                'step'      => new DateInterval("PT{$stepValue}S"),
            ],
        ];
    }
}
