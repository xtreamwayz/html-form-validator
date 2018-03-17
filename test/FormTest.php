<?php

declare(strict_types=1);

namespace XtreamwayzTest\HTMLFormValidator;

use PHPUnit\Framework\TestCase;
use Xtreamwayz\HTMLFormValidator\Form;
use Psr\Http\Message\ServerRequestInterface;
use Xtreamwayz\HTMLFormValidator\FormFactory;
use Xtreamwayz\HTMLFormValidator\FormFactoryFactory;
use Xtreamwayz\HTMLFormValidator\FormInterface;
use Xtreamwayz\HTMLFormValidator\ValidationResult;
use Zend\InputFilter\Factory;

class FormTest extends TestCase
{
    private $rawValues = [
        'foo' => 'bar',
        'baz' => ' qux ',
    ];

    private $values    = [
        'foo' => 'bar',
        'baz' => 'qux',
    ];

    private $messages  = [
        'foo' => [
            'regexNotMatch' => 'The input does not match against pattern \'/^\d+$/\'',
        ],
    ];

    public function testPsrPostRequestIsValid()
    {
        $html = '
            <form action="/" method="post">
                <input type="text" name="foo" />
                <input type="text" name="baz" data-filters="stringtrim" />
            </form>';

        $form = (new FormFactory())->fromHtml($html);
        $request = $this->prophesize(ServerRequestInterface::class);
        $request->getMethod()->willReturn('POST');
        $request->getParsedBody()->willReturn($this->rawValues);

        $result = $form->validateRequest($request->reveal());

        self::assertInstanceOf(ValidationResult::class, $result);
        self::assertEquals($this->rawValues, $result->getRawValues());
        self::assertEquals($this->values, $result->getValues());
        self::assertEquals([], $result->getMessages());
        self::assertTrue($result->isValid());
    }

    public function testPsrGetRequestIsNotValid()
    {
        $html = '
            <form action="/" method="post">
                <input type="text" name="foo" />
                <input type="text" name="baz" data-filters="stringtrim" />
            </form>';

        $form = (new FormFactory())->fromHtml($html);
        $request = $this->prophesize(ServerRequestInterface::class);
        $request->getMethod()->willReturn('GET');
        $request->getParsedBody()->willReturn($this->rawValues);

        $result = $form->validateRequest($request->reveal());

        self::assertInstanceOf(ValidationResult::class, $result);
        self::assertEquals([], $result->getRawValues());
        self::assertEquals([], $result->getValues());
        self::assertEquals([], $result->getMessages());
        self::assertFalse($result->isValid());
    }

    public function testPsrPostRequestHasMessages()
    {
        $html = '
            <form action="/" method="post">
                <input type="text" name="foo" pattern="^\d+$" />
                <input type="text" name="baz" data-filters="stringtrim" />
            </form>';

        $form = (new FormFactory())->fromHtml($html);
        $request = $this->prophesize(ServerRequestInterface::class);
        $request->getMethod()->willReturn('POST');
        $request->getParsedBody()->willReturn($this->rawValues);

        $result = $form->validateRequest($request->reveal());

        self::assertInstanceOf(ValidationResult::class, $result);
        self::assertEquals($this->rawValues, $result->getRawValues());
        self::assertEquals($this->values, $result->getValues());
        self::assertEquals($this->messages, $result->getMessages());
        self::assertFalse($result->isValid());
    }

    public function testSetValuesStatically()
    {
        $html = '
            <form action="/" method="post">
                <input type="text" name="foo" data-reuse-submitted-value="true" />
                <input type="text" name="baz" data-filters="stringtrim" />
            </form>';

        $form = (new FormFactory())->fromHtml($html, [
            'foo' => 'bar',
            'baz' => 'qux',
        ]);

        self::assertContains('<input type="text" name="foo" value="bar">', $form->asString());
        self::assertContains('<input type="text" name="baz" value="qux">', $form->asString());
    }

    public function testSetValuesWithConstructor()
    {
        $html = '
            <form action="/" method="post">
                <input type="text" name="foo" data-reuse-submitted-value="true" />
                <input type="text" name="baz" data-filters="stringtrim" />
            </form>';

        $form = (new FormFactory())->fromHtml($html, [
            'foo' => 'bar',
            'baz' => 'qux',
        ]);

        self::assertContains('<input type="text" name="foo" value="bar">', $form->asString());
        self::assertContains('<input type="text" name="baz" value="qux">', $form->asString());
    }
}
