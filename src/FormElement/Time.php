<?php
/**
 * html-form-validator (https://github.com/xtreamwayz/html-form-validator)
 *
 * @see       https://github.com/xtreamwayz/html-form-validator for the canonical source repository
 * @copyright Copyright (c) 2016 Geert Eltink (https://xtreamwayz.com/)
 * @license   https://github.com/xtreamwayz/html-form-validator/blob/master/LICENSE.md MIT
 */

declare(strict_types = 1);

namespace Xtreamwayz\HTMLFormValidator\FormElement;

use DateInterval;
use Xtreamwayz\HTMLFormValidator\FormElement\DateTime as DateTimeElement;
use Zend\Validator\DateStep as DateStepValidator;

class Time extends DateTimeElement
{
    protected $format = 'H:i:s';

    protected function getStepValidator() : array
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
