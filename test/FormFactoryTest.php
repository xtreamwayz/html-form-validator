<?php

namespace XtreamwayzTest\HTMLFormValidator;

use Psr\Http\Message\ServerRequestInterface;
use Xtreamwayz\HTMLFormValidator\FormFactory;
use Xtreamwayz\HTMLFormValidator\ValidationResult;

class FormFactoryTest extends \PHPUnit_Framework_TestCase
{
    private $rawValues = [
        'foo' => 'bar',
        'baz' => ' qux ',
    ];

    private $values = [
        'foo' => 'bar',
        'baz' => 'qux',
    ];

    private $messages = [
        'foo' => [
            'regexNotMatch' => 'The input does not match against pattern \'/^\d+$/\'',
        ],
    ];

    public function testPsrPostRequestIsValid()
    {
        $htmlForm = '
            <form action="/" method="post">
                <input type="text" name="foo" />
                <input type="text" name="baz" data-filters="stringtrim" />
            </form>';

        $form = FormFactory::fromHtml($htmlForm);
        $request = $this->prophesize(ServerRequestInterface::class);
        $request->getMethod()->willReturn('POST');
        $request->getParsedBody()->willReturn($this->rawValues);

        $result = $form->validateRequest($request->reveal());

        $this->assertInstanceOf(ValidationResult::class, $result);
        $this->assertEquals($this->rawValues, $result->getRawValues());
        $this->assertEquals($this->values, $result->getValues());
        $this->assertEquals([], $result->getMessages());
        $this->assertTrue($result->isValid());
    }

    public function testPsrGetRequestIsNotValid()
    {
        $htmlForm = '
            <form action="/" method="post">
                <input type="text" name="foo" />
                <input type="text" name="baz" data-filters="stringtrim" />
            </form>';

        $form = FormFactory::fromHtml($htmlForm);
        $request = $this->prophesize(ServerRequestInterface::class);
        $request->getMethod()->willReturn('GET');
        $request->getParsedBody()->willReturn($this->rawValues);

        $result = $form->validateRequest($request->reveal());

        $this->assertInstanceOf(ValidationResult::class, $result);
        $this->assertEquals([], $result->getRawValues());
        $this->assertEquals([], $result->getValues());
        $this->assertEquals([], $result->getMessages());
        $this->assertFalse($result->isValid());
    }

    public function testPsrPostRequestHasMessages()
    {
        $htmlForm = '
            <form action="/" method="post">
                <input type="text" name="foo" pattern="^\d+$" />
                <input type="text" name="baz" data-filters="stringtrim" />
            </form>';

        $form = FormFactory::fromHtml($htmlForm);
        $request = $this->prophesize(ServerRequestInterface::class);
        $request->getMethod()->willReturn('POST');
        $request->getParsedBody()->willReturn($this->rawValues);

        $result = $form->validateRequest($request->reveal());

        $this->assertInstanceOf(ValidationResult::class, $result);
        $this->assertEquals($this->rawValues, $result->getRawValues());
        $this->assertEquals($this->values, $result->getValues());
        $this->assertEquals($this->messages, $result->getMessages());
        $this->assertFalse($result->isValid());
    }

    public function testSetValuesStatically()
    {
        $htmlForm = '
            <form action="/" method="post">
                <input type="text" name="foo" data-reuse-submitted-value="true" />
                <input type="text" name="baz" data-filters="stringtrim" />
            </form>';

        $form = FormFactory::fromHtml($htmlForm, [
            'foo' => 'bar',
            'baz' => 'qux',
        ]);

        $this->assertContains('<input type="text" name="foo" value="bar">', $form->asString());
        $this->assertContains('<input type="text" name="baz" value="qux">', $form->asString());
    }

    public function testSetValuesWithConstructor()
    {
        $htmlForm = '
            <form action="/" method="post">
                <input type="text" name="foo" data-reuse-submitted-value="true" />
                <input type="text" name="baz" data-filters="stringtrim" />
            </form>';

        $form = new FormFactory($htmlForm, null, [
            'foo' => 'bar',
            'baz' => 'qux',
        ]);

        $this->assertContains('<input type="text" name="foo" value="bar">', $form->asString());
        $this->assertContains('<input type="text" name="baz" value="qux">', $form->asString());
    }
}
