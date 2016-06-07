<?php
/**
 * html-form-validator (https://github.com/xtreamwayz/html-form-validator)
 *
 * @see       https://github.com/xtreamwayz/html-form-validator for the canonical source repository
 * @copyright Copyright (c) 2016 Geert Eltink (https://xtreamwayz.com/)
 * @license   https://github.com/xtreamwayz/html-form-validator/blob/master/LICENSE.md MIT
 */

declare(strict_types = 1);

namespace Xtreamwayz\HTMLFormValidator;

use Psr\Http\Message\ServerRequestInterface;
use Zend\InputFilter\Factory;

interface FormFactoryInterface
{
    /**
     * FormFactory constructor: Load html form and optionally set an InputFilter
     *
     * @param string       $htmlForm
     * @param null|Factory $factory
     * @param array        $defaultValues
     */
    public function __construct(string $htmlForm, Factory $factory = null, array $defaultValues = []);

    /**
     * Load html form and optionally set default data
     *
     * @param string $htmlForm
     * @param array  $defaultValues
     *
     * @return FormFactoryInterface
     */
    public static function fromHtml(string $htmlForm, array $defaultValues = []) : FormFactoryInterface;

    /**
     * Return form as a string. Optionally inject the error messages for the result.
     *
     * @param ValidationResultInterface|null $result
     *
     * @return string
     */
    public function asString(ValidationResultInterface $result = null) : string;

    /**
     * @param ServerRequestInterface $request
     *
     * @return ValidationResultInterface
     */
    public function validateRequest(ServerRequestInterface $request) : ValidationResultInterface;

    /**
     * Validate the loaded form with the data
     *
     * @param array       $data   the submitted data to be validated
     * @param null|string $method the request method (POST, GET, etc.)
     *
     * @return ValidationResultInterface
     */
    public function validate(array $data, $method = null) : ValidationResultInterface;
}
