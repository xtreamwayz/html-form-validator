<?php

namespace Xtreamwayz\HTMLFormValidator;

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
     * Validate the loaded form with the data
     *
     * @param array $data
     *
     * @return ValidationResultInterface
     */
    public function validate(array $data);
}
