<?php
/**
 * html-form-validator (https://github.com/xtreamwayz/html-form-validator)
 *
 * @see       https://github.com/xtreamwayz/html-form-validator for the canonical source repository
 * @copyright Copyright (c) 2016 Geert Eltink (https://xtreamwayz.com/)
 * @license   https://github.com/xtreamwayz/html-form-validator/blob/master/LICENSE.md MIT
 */

namespace Xtreamwayz\HTMLFormValidator\FormElement;

use DateInterval;
use Xtreamwayz\HTMLFormValidator\FormElement\DateTime as DateTimeElement;
use Zend\Validator\DateStep as DateStepValidator;
use Zend\Validator\Regex as RegexValidator;

class Week extends DateTimeElement
{
    protected function getDateValidator()
    {
        return [
            'name'    => RegexValidator::class,
            'options' => [
                'pattern' => '/^[0-9]{4}\-W[0-9]{2}$/',
            ],
        ];
    }

    protected function getStepValidator()
    {
        $stepValue = $this->node->getAttribute('step') ?: 1; // Weeks
        $baseValue = $this->node->getAttribute('min') ?: '1970-W01';

        return [
            'name'    => DateStepValidator::class,
            'options' => [
                'format'    => 'Y-\WW',
                'baseValue' => $baseValue,
                'step'      => new DateInterval("P{$stepValue}W"),
            ],
        ];
    }
}
