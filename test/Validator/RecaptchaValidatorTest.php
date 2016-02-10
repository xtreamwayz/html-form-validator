<?php

namespace XtreamwayzTest\HTMLFormValidator\Validator;

use AspectMock\Test as test;
use InvalidArgumentException;
use Xtreamwayz\HTMLFormValidator\Validator;
use ArrayIterator;

class RecaptchaValidatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Validator\RecaptchaValidator
     */
    protected $validator;

    protected $privKey = 'secret_private_key';

    protected $pubKey = 'public_key';

    /**
     * Creates a new RecaptchaValidator object for each test method
     *
     * @return void
     */
    public function setUp()
    {
        $this->validator = new Validator\RecaptchaValidator(['key' => $this->privKey]);
    }

    protected function tearDown()
    {
        test::clean();
    }

    public function testOptionsIteratorToArray()
    {
        $options = ['key' => $this->privKey];
        $iterator = new ArrayIterator($options);
        $this->validator = new Validator\RecaptchaValidator($iterator);

        $reflectionClass = new \ReflectionClass(Validator\RecaptchaValidator::class);

        $reflectionProperty = $reflectionClass->getProperty('options');
        $reflectionProperty->setAccessible(true);
        $actualOptions = $reflectionProperty->getValue($this->validator);

        $this->assertEquals($options, $actualOptions);
    }

    public function testMissingKeyOptionThrowsInvalidArgumentException()
    {
        $this->expectException(InvalidArgumentException::class);

        $validator = new Validator\RecaptchaValidator();
    }

    public function testGetMessages()
    {
        $this->assertEquals([], $this->validator->getMessages());
    }

    public function testValidInvalidUserResponse()
    {
        $mock = test::func('Xtreamwayz\HTMLFormValidator\Validator', 'file_get_contents', '{
            "success": true
        }');

        $this->assertTrue($this->validator->isValid($this->pubKey));
        $mock->verifyInvoked();
    }

    public function testInvalidInvalidUserResponse()
    {
        $mock = test::func('Xtreamwayz\HTMLFormValidator\Validator', 'file_get_contents', '{
            "success": false,
            "error-codes": [
                "missing-input-response"
            ]
        }');

        $this->assertFalse($this->validator->isValid($this->pubKey));
        $mock->verifyInvoked();
    }
}
