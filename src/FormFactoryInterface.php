<?php

namespace Xtreamwayz\HTMLFormValidator;

use Psr\Http\Message\ServerRequestInterface;
use Zend\InputFilter\Factory;

interface FormFactoryInterface
{
    /**
     * FormFactory constructor: Load html form and optionally set an InputFilter
     *
     * @param string       $htmlForm
     * @param array        $defaultValues
     * @param null|Factory $factory
     */
    public function __construct($htmlForm, array $defaultValues = [], Factory $factory = null);

    /**
     * Load html form and optionally set default data
     *
     * @param string $htmlForm
     * @param array  $defaultValues
     *
     * @return FormFactoryInterface
     */
    public static function fromHtml($htmlForm, array $defaultValues = []);

    /**
     * Return form as a string. Optionally inject the error messages for the result.
     *
     * @param ValidationResultInterface|null $result
     *
     * @return string
     */
    public function asString(ValidationResultInterface $result = null);

    /**
     * @param ServerRequestInterface $request
     *
     * @return ValidationResultInterface
     */
    public function validateRequest(ServerRequestInterface $request);

    /**
     * Validate the loaded form with the data
     *
     * @param array       $data   the submitted data to be validated
     * @param null|string $method the request method (POST, GET, etc.)
     *
     * @return ValidationResultInterface
     */
    public function validate(array $data, $method = null);
}
