<?php

declare(strict_types=1);

namespace XtreamwayzTest\HTMLFormValidator;

use PHPUnit\Framework\TestCase;
use Xtreamwayz\HTMLFormValidator\ValidationResult;

class ValidationResultTest extends TestCase
{
    /** @var string[] */
    private $rawValues = [
        'foo' => 'bar',
        'baz' => ' qux ',
    ];

    /** @var string[] */
    private $values = [
        'foo' => 'bar',
        'baz' => 'qux',
    ];

    /** @var string[][] */
    private $messages = [
        'foo' => [
            'regexNotMatch' => '',
            'notInArray'    => '',
        ],
    ];

    public function testValuesAreAvailable(): void
    {
        $result = new ValidationResult($this->rawValues, $this->values, $this->messages, null);

        self::assertEquals($this->rawValues, $result->getRawValues());
        self::assertEquals($this->values, $result->getValues());
        self::assertEquals($this->messages, $result->getMessages());
    }

    public function testResultIsValid(): void
    {
        $result = new ValidationResult($this->rawValues, $this->values, [], null);

        self::assertTrue($result->isValid());
    }

    public function testPostResultIsValid(): void
    {
        $result = new ValidationResult($this->rawValues, $this->values, [], 'POST');

        self::assertTrue($result->isValid());
    }

    public function testResultWithMessagesIsNotValid(): void
    {
        $result = new ValidationResult($this->rawValues, $this->values, $this->messages, null);

        self::assertFalse($result->isValid());
    }

    public function testPostResultWithMessagesIsNotValid(): void
    {
        $result = new ValidationResult($this->rawValues, $this->values, $this->messages, 'POST');

        self::assertFalse($result->isValid());
    }

    public function testGetResultIsNotValid(): void
    {
        $result = new ValidationResult($this->rawValues, $this->values, $this->messages, 'GET');

        self::assertFalse($result->isValid());
    }

    public function testSubmitButtonIsClicked(): void
    {
        $result = new ValidationResult($this->rawValues, $this->values, $this->messages, 'POST', 'confirm');

        self::assertTrue($result->isClicked('confirm'));
        self::assertFalse($result->isClicked('cancel'));
        self::assertEquals('confirm', $result->getClicked());
    }

    public function testSubmitButtonIsNotClicked(): void
    {
        $result = new ValidationResult($this->rawValues, $this->values, $this->messages, 'POST', null);

        self::assertFalse($result->isClicked('confirm'));
        self::assertFalse($result->isClicked('cancel'));
        self::assertNull($result->getClicked());
    }

    public function testMessagesCanBeAdded(): void
    {
        $result = new ValidationResult($this->rawValues, $this->values, $this->messages, 'POST');

        $result->addMessages([
            'foo' => [
                'invalidUuid' => 'This is not a valid uuid',
                'notInArray'  => 'This is not in array',
            ],
            'baz' => [
                'isRequired' => 'This is required',
            ],
        ]);

        $expected = [
            'foo' => [
                'regexNotMatch' => '',
                'invalidUuid'   => 'This is not a valid uuid',
                'notInArray'    => 'This is not in array',
            ],
            'baz' => [
                'isRequired' => 'This is required',
            ],
        ];

        self::assertEquals($expected, $result->getMessages());
    }
}
