<?php

declare(strict_types=1);

namespace Xtreamwayz\HTMLFormValidator\Validator;

use InvalidArgumentException;
use Traversable;
use Zend\Validator\AbstractValidator;
use function array_key_exists;
use function file_get_contents;
use function is_array;
use function iterator_to_array;
use function json_decode;
use function sprintf;

class RecaptchaValidator extends AbstractValidator
{
    protected const VERIFICATION_URI = 'https://www.google.com/recaptcha/api/siteverify?secret=%s&response=%s';

    protected const INVALID = 'recaptcha';

    /** @var array */
    protected $messageTemplates = [self::INVALID => 'ReCaptcha was invalid!'];

    /** @var array */
    protected $options = ['key' => null];

    /**
     * Sets validator options
     *
     * Accepts the following option keys:
     *   'key' => string, private recaptcha key
     *
     * @param array|Traversable $options
     *
     * @throws \InvalidArgumentException
     */
    public function __construct($options = null)
    {
        if ($options instanceof Traversable) {
            $options = iterator_to_array($options);
        }

        if (! is_array($options) || ! array_key_exists('key', $options)) {
            throw new InvalidArgumentException('Missing private recaptcha key.');
        }

        parent::__construct($options);
    }

    public function setKey(string $key) : self
    {
        $this->options['key'] = $key;

        return $this;
    }

    public function isValid($value) : bool
    {
        $uri      = sprintf(self::VERIFICATION_URI, $this->options['key'], $value);
        $json     = file_get_contents($uri);
        $response = json_decode($json);

        if (! isset($response->success) || $response->success !== true) {
            $this->error(self::INVALID);

            return false;
        }

        return true;
    }
}
