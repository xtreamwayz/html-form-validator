<?php
/**
 * html-form-validator (https://github.com/xtreamwayz/html-form-validator)
 *
 * @see       https://github.com/xtreamwayz/html-form-validator for the canonical source repository
 * @copyright Copyright (c) 2016 Geert Eltink (https://xtreamwayz.com/)
 * @license   https://github.com/xtreamwayz/html-form-validator/blob/master/LICENSE.md MIT
 */

declare(strict_types = 1);

namespace Xtreamwayz\HTMLFormValidator\Validator;

use InvalidArgumentException;
use Traversable;
use Zend\Validator\AbstractValidator;

class RecaptchaValidator extends AbstractValidator
{
    const RECAPTCHA_VERIFICATION_URI = 'https://www.google.com/recaptcha/api/siteverify?secret=%s&response=%s';
    const INVALID = 'recaptcha';

    protected $messageTemplates = [
        self::INVALID => 'ReCaptcha was invalid!',
    ];

    protected $options = [
        'key' => null,
    ];

    /**
     * Sets validator options
     * Accepts the following option keys:
     *   'key' => string, private recaptcha key
     *
     * @param array|Traversable $options
     */
    public function __construct($options = null)
    {
        if ($options instanceof Traversable) {
            $options = iterator_to_array($options);
        }

        if (!is_array($options) || !array_key_exists('key', $options)) {
            throw new InvalidArgumentException('Missing private recaptcha key.');
        }

        parent::__construct($options);
    }

    public function setKey($key) : self
    {
        $this->options['key'] = $key;

        return $this;
    }

    public function isValid($value) : bool
    {
        $uri = sprintf(self::RECAPTCHA_VERIFICATION_URI, $this->options['key'], $value);
        $json = file_get_contents($uri);
        $response = json_decode($json);

        if (!isset($response->success) || $response->success !== true) {
            $this->error(self::INVALID);

            return false;
        }

        return true;
    }
}
