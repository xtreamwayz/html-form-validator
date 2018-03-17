<?php

declare(strict_types=1);

namespace XtreamwayzTest\HTMLFormValidator;

use PHPUnit\Framework\TestCase;
use Xtreamwayz\HTMLFormValidator\InputFilterFactory;
use Xtreamwayz\HTMLFormValidator\Validator\RecaptchaValidator;
use Zend\InputFilter\Factory;
use Zend\ServiceManager\ServiceManager;

class InputFilterFactoryTest extends TestCase
{
    public function createFactory(?bool $useConfig = null) : Factory
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
                        'invokables' => [],
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

    public function testInputFilterFactoryCreation() : void
    {
        $factory = $this->createFactory(false);

        self::assertInstanceOf(Factory::class, $factory);
        self::assertFalse($factory->getDefaultValidatorChain()->getPluginManager()->has('recaptcha'));
    }

    public function testCustomValidatorIsRegistered() : void
    {
        $factory = $this->createFactory(true);

        self::assertTrue($factory->getDefaultValidatorChain()->getPluginManager()->has('recaptcha'));
        self::assertInstanceOf(
            RecaptchaValidator::class,
            $factory->getDefaultValidatorChain()->getPluginManager()->get('recaptcha', ['key' => 'secret_key'])
        );
    }

    public function testInputHasAccessToCustomValidator() : void
    {
        $factory = $this->createFactory(true);

        $input = $factory->createInput(['name' => 'foo']);

        self::assertTrue($input->getValidatorChain()->getPluginManager()->has('recaptcha'));
        self::assertInstanceOf(
            RecaptchaValidator::class,
            $input->getValidatorChain()->getPluginManager()->get('recaptcha', ['key' => 'secret_key'])
        );
    }
}
