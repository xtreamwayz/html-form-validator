<?php
/**
 * html-form-validator (https://github.com/xtreamwayz/html-form-validator)
 *
 * @see       https://github.com/xtreamwayz/html-form-validator for the canonical source repository
 * @copyright Copyright (c) 2016 Geert Eltink (https://xtreamwayz.com/)
 * @license   https://github.com/xtreamwayz/html-form-validator/blob/master/LICENSE.md MIT
 */

declare(strict_types=1);

namespace Xtreamwayz\HTMLFormValidator;

use Zend\InputFilter\Factory;

class ConfigProvider
{
    public function __invoke() : array
    {
        return [
            'dependencies'        => $this->getDependencies(),
            'html-form-validator' => $this->getOptions(),
        ];
    }

    public function getDependencies() : array
    {
        return [
            'factories' => [
                Factory::class     => InputFilterFactory::class,
                FormFactory::class => FormFactoryFactory::class,
            ],
        ];
    }

    public function getOptions() : array
    {
        return [
            'cssHasErrorClass' => 'has-validation-error',
            'cssErrorClass'    => 'has-validation-error',
        ];
    }
}
