<?php

declare(strict_types=1);

namespace Xtreamwayz\HTMLFormValidator\FormElement;

use DateInterval;
use Xtreamwayz\HTMLFormValidator\FormElement\DateTime as DateTimeElement;
use Zend\Validator\DateStep as DateStepValidator;
use function sprintf;

class Month extends DateTimeElement
{
    /** @var string */
    protected $format = 'Y-m';

    protected function getStepValidator() : array
    {
        $stepValue = $this->node->getAttribute('step') ?: 1; // Months
        $baseValue = $this->node->getAttribute('min') ?: '1970-01';

        return [
            'name'    => DateStepValidator::class,
            'options' => [
                'format'    => $this->format,
                'baseValue' => $baseValue,
                'step'      => new DateInterval(sprintf('P%dM', $stepValue)),
            ],
        ];
    }
}
