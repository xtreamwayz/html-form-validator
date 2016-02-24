<?php

namespace XtreamwayzTest\HTMLFormValidator;

use Xtreamwayz\HTMLFormValidator\ValidationResult;

class ValidationResultTest extends \PHPUnit_Framework_TestCase
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
            'regexNotMatch' => '',
            'notInArray' => '',
        ]
    ];

    public function testValuesAreAvailable()
    {
        $result = new ValidationResult($this->rawValues, $this->values, $this->messages, null);

        $this->assertEquals($this->rawValues, $result->getRawValues());
        $this->assertEquals($this->values, $result->getValues());
        $this->assertEquals($this->messages, $result->getMessages());
    }

    public function testResultIsValid()
    {
        $result = new ValidationResult($this->rawValues, $this->values, [], null);

        $this->assertTrue($result->isValid());
    }

    public function testPostResultIsValid()
    {
        $result = new ValidationResult($this->rawValues, $this->values, [], 'POST');

        $this->assertTrue($result->isValid());
    }

    public function testResultWithMessagesIsNotValid()
    {
        $result = new ValidationResult($this->rawValues, $this->values, $this->messages, null);

        $this->assertFalse($result->isValid());
    }

    public function testPostResultWithMessagesIsNotValid()
    {
        $result = new ValidationResult($this->rawValues, $this->values, $this->messages, 'POST');

        $this->assertFalse($result->isValid());
    }

    public function testGetResultIsNotValid()
    {
        $result = new ValidationResult($this->rawValues, $this->values, $this->messages, 'GET');

        $this->assertFalse($result->isValid());
    }

    public function testSubmitButtonIsClicked()
    {
        $result = new ValidationResult($this->rawValues, $this->values, $this->messages, 'POST', 'confirm');

        $this->assertTrue($result->isClicked('confirm'));
        $this->assertFalse($result->isClicked('cancel'));
        $this->assertEquals('confirm', $result->isClicked());
    }

    public function testSubmitButtonIsNotClicked()
    {
        $result = new ValidationResult($this->rawValues, $this->values, $this->messages, 'POST', null);

        $this->assertFalse($result->isClicked('confirm'));
        $this->assertFalse($result->isClicked('cancel'));
        $this->assertNull($result->isClicked());
    }
}
