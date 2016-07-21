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
use Zend\Validator\Date as DateValidator;
use Zend\Validator\DateStep as DateStepValidator;
use Zend\Validator\GreaterThan as GreaterThanValidator;
use Zend\Validator\LessThan as LessThanValidator;

class DateTime extends BaseFormElement
{
    protected $format = 'Y-m-d\TH:i';

    protected function getValidators()
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

        if (!$this->node->hasAttribute('step')
            || 'any' !== $this->node->getAttribute('step')
        ) {
            $validators[] = $this->getStepValidator();
        }

        return $validators;
    }

    protected function getDateValidator()
    {
        return [
            'name'    => DateValidator::class,
            'options' => [
                'format' => $this->format,
            ],
        ];
    }

    protected function getStepValidator()
    {
        $stepValue = $this->node->getAttribute('step') ?: 1; // Minutes
        $baseValue = $this->node->getAttribute('min') ?: date($this->format, 0);

        return [
            'name'    => DateStepValidator::class,
            'options' => [
                'format'    => $this->format,
                'baseValue' => $baseValue,
                'step'      => new DateInterval("PT{$stepValue}M"),
            ],
        ];
    }
}
