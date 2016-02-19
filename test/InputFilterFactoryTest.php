<?php

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
        } else {
            $config = [];
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

        $this->assertInstanceOf(Factory::class, $factory);
        $this->assertFalse($factory->getDefaultValidatorChain()->getPluginManager()->has('recaptcha'));
    }

    public function testCustomValidatorIsRegistered()
    {
        $factory = $this->createFactory();

        $this->assertTrue($factory->getDefaultValidatorChain()->getPluginManager()->has('recaptcha'));
        $this->assertInstanceOf(
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

        $this->assertTrue($input->getValidatorChain()->getPluginManager()->has('recaptcha'));
        $this->assertInstanceOf(
            RecaptchaValidator::class,
            $input->getValidatorChain()->getPluginManager()->get('recaptcha', [
                'key' => 'secret_key',
            ])
        );
    }
}
