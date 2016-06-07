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

        self::assertEquals($this->rawValues, $result->getRawValues());
        self::assertEquals($this->values, $result->getValues());
        self::assertEquals($this->messages, $result->getMessages());
    }

    public function testResultIsValid()
    {
        $result = new ValidationResult($this->rawValues, $this->values, [], null);

        self::assertTrue($result->isValid());
    }

    public function testPostResultIsValid()
    {
        $result = new ValidationResult($this->rawValues, $this->values, [], 'POST');

        self::assertTrue($result->isValid());
    }

    public function testResultWithMessagesIsNotValid()
    {
        $result = new ValidationResult($this->rawValues, $this->values, $this->messages, null);

        self::assertFalse($result->isValid());
    }

    public function testPostResultWithMessagesIsNotValid()
    {
        $result = new ValidationResult($this->rawValues, $this->values, $this->messages, 'POST');

        self::assertFalse($result->isValid());
    }

    public function testGetResultIsNotValid()
    {
        $result = new ValidationResult($this->rawValues, $this->values, $this->messages, 'GET');

        self::assertFalse($result->isValid());
    }

    public function testSubmitButtonIsClicked()
    {
        $result = new ValidationResult($this->rawValues, $this->values, $this->messages, 'POST', 'confirm');

        self::assertTrue($result->isClicked('confirm'));
        self::assertFalse($result->isClicked('cancel'));
        self::assertEquals('confirm', $result->isClicked());
    }

    public function testSubmitButtonIsNotClicked()
    {
        $result = new ValidationResult($this->rawValues, $this->values, $this->messages, 'POST', null);

        self::assertFalse($result->isClicked('confirm'));
        self::assertFalse($result->isClicked('cancel'));
        self::assertNull($result->isClicked());
    }
}
