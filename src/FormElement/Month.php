<?php

namespace Xtreamwayz\HTMLFormValidator\FormElement;

use DateInterval;
use Xtreamwayz\HTMLFormValidator\FormElement\DateTime as DateTimeElement;
use Zend\Validator\DateStep as DateStepValidator;

class Month extends DateTimeElement
{
    protected $format = 'Y-m';

    protected function getStepValidator()
    {
        $stepValue = $this->node->getAttribute('step') ?: 1; // Months
        $baseValue = $this->node->getAttribute('min') ?: '1970-01';

        return [
            'name'    => DateStepValidator::class,
            'options' => [
                'format'    => $this->format,
                'baseValue' => $baseValue,
                'step'      => new DateInterval("P{$stepValue}M"),
            ],
        ];
    }
}
