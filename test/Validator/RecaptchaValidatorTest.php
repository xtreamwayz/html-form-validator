<?php

declare(strict_types=1);

namespace XtreamwayzTest\HTMLFormValidator\Validator;

use ArrayIterator;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Xtreamwayz\HTMLFormValidator\Validator;

class RecaptchaValidatorTest extends TestCase
{
    /** @var Validator\RecaptchaValidator */
    protected $validator;

    protected $privKey = 'secret_private_key';

    protected $pubKey = 'public_key';

    /**
     * Creates a new RecaptchaValidator object for each test method
     *
     */
    public function setUp() : void
    {
        $this->validator = new Validator\RecaptchaValidator(['key' => $this->privKey]);
    }

    public function testOptionsIteratorToArray() : void
    {
        $options         = ['key' => $this->privKey];
        $iterator        = new ArrayIterator($options);
        $this->validator = new Validator\RecaptchaValidator($iterator);

        $reflectionClass = new \ReflectionClass(Validator\RecaptchaValidator::class);

        $reflectionProperty = $reflectionClass->getProperty('options');
        $reflectionProperty->setAccessible(true);
        $actualOptions = $reflectionProperty->getValue($this->validator);

        self::assertEquals($options, $actualOptions);
    }

    public function testMissingKeyOptionThrowsInvalidArgumentException() : void
    {
        $this->expectException(InvalidArgumentException::class);

        new Validator\RecaptchaValidator();
    }

    public function testGetMessages() : void
    {
        self::assertEquals([], $this->validator->getMessages());
    }
}
