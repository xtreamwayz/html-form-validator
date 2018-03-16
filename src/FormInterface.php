<?php
/**
 * html-form-validator (https://github.com/xtreamwayz/html-form-validator)
 *
 * @see       https://github.com/xtreamwayz/html-form-validator for the canonical source repository
 * @copyright Copyright (c) 2016 Geert Eltink (https://xtreamwayz.com/)
 * @license   https://github.com/xtreamwayz/html-form-validator/blob/master/LICENSE.md MIT
 */

namespace Xtreamwayz\HTMLFormValidator;

use Psr\Http\Message\ServerRequestInterface;

interface FormInterface
{
    /**
     * Return form as a string. Optionally inject the error messages for the result.
     */
    public function asString(?ValidationResultInterface $result = null) : string;

    /**
     * Validate the loaded form with the data from a PSR-7 request
     */
    public function validateRequest(ServerRequestInterface $request) : ValidationResultInterface;

    /**
     * Validate the loaded form with the data
     */
    public function validate(array $data, ?string $requestMethod = null) : ValidationResultInterface;
}
