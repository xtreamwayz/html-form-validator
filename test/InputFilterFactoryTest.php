<?php
/**
 * html-form-validator (https://github.com/xtreamwayz/html-form-validator)
 *
 * @see       https://github.com/xtreamwayz/html-form-validator for the canonical source repository
 * @copyright Copyright (c) 2016 Geert Eltink (https://xtreamwayz.com/)
 * @license   https://github.com/xtreamwayz/html-form-validator/blob/master/LICENSE.md MIT
 */

declare(strict_types=1);

namespace XtreamwayzTest\HTMLFormValidator;

use Xtreamwayz\HTMLFormValidator\InputFilterFactory;
use Xtreamwayz\HTMLFormValidator\Validator\RecaptchaValidator;
use Zend\InputFilter\Factory;
use Zend\ServiceManager\ServiceManager;

class InputFilterFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @param bool $useConfig
     *
     * @return Factory
     */
    public function createFactory($useConfig = true)
    {
        $config = [];

        if ($useConfig === true) {
            $config = [
                'zend-inputfilter' => [
                    'validators' => [
                        // Attach custom validators or override standard validators
                        'invokables' => [
                            'recaptcha' => RecaptchaValidator::class,
                        ],
                    ],
                    'filters'    => [
                        // Attach custom filters or override standard filters
                        'invokables' => [
                        ],
                    ],
                ],
            ];
        }

        // Build container
        $container = new ServiceManager($config);
        $container->setService('config', $config);

        $factory = new InputFilterFactory();

        return $factory($container);
    }

    public function testInputFilterFactoryCreation()
    {
        $factory = $this->createFactory(false);

        self::assertInstanceOf(Factory::class, $factory);
        self::assertFalse($factory->getDefaultValidatorChain()->getPluginManager()->has('recaptcha'));
    }

    public function testCustomValidatorIsRegistered()
    {
        $factory = $this->createFactory();

        self::assertTrue($factory->getDefaultValidatorChain()->getPluginManager()->has('recaptcha'));
        self::assertInstanceOf(
            RecaptchaValidator::class,
            $factory->getDefaultValidatorChain()->getPluginManager()->get('recaptcha', [
                'key' => 'secret_key',
            ])
        );
    }

    public function testInputHasAccessToCustomValidator()
    {
        $factory = $this->createFactory();

        $input = $factory->createInput([
            'name' => 'foo',
        ]);

        self::assertTrue($input->getValidatorChain()->getPluginManager()->has('recaptcha'));
        self::assertInstanceOf(
            RecaptchaValidator::class,
            $input->getValidatorChain()->getPluginManager()->get('recaptcha', [
                'key' => 'secret_key',
            ])
        );
    }
}
