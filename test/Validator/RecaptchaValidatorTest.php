<?php
/**
 * html-form-validator (https://github.com/xtreamwayz/html-form-validator)
 *
 * @see       https://github.com/xtreamwayz/html-form-validator for the canonical source repository
 * @copyright Copyright (c) 2016 Geert Eltink (https://xtreamwayz.com/)
 * @license   https://github.com/xtreamwayz/html-form-validator/blob/master/LICENSE.md MIT
 */

namespace XtreamwayzTest\HTMLFormValidator\Validator;

//use AspectMock\Test as test;
use ArrayIterator;
use InvalidArgumentException;
use Xtreamwayz\HTMLFormValidator\Validator;

class RecaptchaValidatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Validator\RecaptchaValidator
     */
    protected $validator;

    protected $privKey = 'secret_private_key';

    protected $pubKey  = 'public_key';

    /**
     * Creates a new RecaptchaValidator object for each test method
     *
     * @return void
     */
    public function setUp()
    {
        $this->validator = new Validator\RecaptchaValidator(['key' => $this->privKey]);
    }

    /*
        protected function tearDown()
        {
            test::clean();
        }
    */
    public function testOptionsIteratorToArray()
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

    public function testMissingKeyOptionThrowsInvalidArgumentException()
    {
        $this->expectException(InvalidArgumentException::class);

        new Validator\RecaptchaValidator();
    }

    public function testGetMessages()
    {
        self::assertEquals([], $this->validator->getMessages());
    }
}
