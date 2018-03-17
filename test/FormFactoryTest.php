<?php
/**
 * html-form-validator (https://github.com/xtreamwayz/html-form-validator)
 *
 * @see       https://github.com/xtreamwayz/html-form-validator for the canonical source repository
 * @copyright Copyright (c) 2016 Geert Eltink (https://xtreamwayz.com/)
 * @license   https://github.com/xtreamwayz/html-form-validator/blob/master/LICENSE.md MIT
 */

namespace XtreamwayzTest\HTMLFormValidator;

use PHPUnit\Framework\TestCase;
use ReflectionProperty;
use Xtreamwayz\HTMLFormValidator\Form;
use Xtreamwayz\HTMLFormValidator\FormFactory;
use Xtreamwayz\HTMLFormValidator\FormFactoryInterface;
use Xtreamwayz\HTMLFormValidator\FormInterface;
use Zend\InputFilter\Factory;
use Zend\InputFilter\InputFilterInterface;

class FormFactoryTest extends TestCase
{
    public function testConstructorWithNoArguments()
    {
        $formFactory = new FormFactory();

        self::assertInstanceOf(FormFactoryInterface::class, $formFactory);
        self::assertInstanceOf(FormFactory::class, $formFactory);
    }

    public function testConstructorWithArguments()
    {
        $factory = $this->prophesize(Factory::class);
        $options = [
            'foo' => 'bar',
        ];

        $formFactory = new FormFactory($factory->reveal(), $options);

        self::assertInstanceOf(FormFactoryInterface::class, $formFactory);
        self::assertInstanceOf(FormFactory::class, $formFactory);
    }

    public function testCreatingFormFromHtml()
    {
        $html = '
            <form action="/" method="post">
                <input type="text" name="foo" />
                <input type="text" name="baz" data-filters="stringtrim" />
            </form>';

        $formFactory = new FormFactory();
        $form        = $formFactory->fromHtml($html);

        self::assertInstanceOf(FormInterface::class, $form);
        self::assertInstanceOf(Form::class, $form);
    }

    public function testCreatingFormUsesInjectedFactoryAndOptions()
    {
        $html = '
            <form action="/" method="post">
                <input type="text" name="foo" />
                <input type="text" name="baz" data-filters="stringtrim" />
            </form>';

        $factory     = $this->prophesize(Factory::class);
        $inputFilter = $this->prophesize(InputFilterInterface::class);
        $options     = [
            'cssHasErrorClass' => 'has-error',
            'cssErrorClass'    => 'error',
        ];
        $defaults    = [
            'foo' => 'bar',
        ];

        $propFactory = new ReflectionProperty(Form::class, 'factory');
        $propFactory->setAccessible(true);

        $propInputFilter = new ReflectionProperty(Form::class, 'inputFilter');
        $propInputFilter->setAccessible(true);

        $propCssHasErrorClass = new ReflectionProperty(Form::class, 'cssHasErrorClass');
        $propCssHasErrorClass->setAccessible(true);

        $propCssErrorClass = new ReflectionProperty(Form::class, 'cssErrorClass');
        $propCssErrorClass->setAccessible(true);

        $formFactory = new FormFactory($factory->reveal(), $options);
        $form        = $formFactory->fromHtml($html, $defaults, $inputFilter->reveal());

        self::assertInstanceOf(FormInterface::class, $form);
        self::assertInstanceOf(Form::class, $form);
        self::assertSame($factory->reveal(), $propFactory->getValue($form));
        self::assertSame($inputFilter->reveal(), $propInputFilter->getValue($form));
        self::assertSame($options['cssHasErrorClass'], $propCssHasErrorClass->getValue($form));
        self::assertSame($options['cssErrorClass'], $propCssErrorClass->getValue($form));
    }
}
