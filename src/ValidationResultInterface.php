<?php

namespace Xtreamwayz\HTMLFormValidator;

interface ValidationResultInterface
{
    /**
     * ValidationResult constructor.
     *
     * @param array       $rawInputData
     * @param array       $validatedData
     * @param array       $errors
     * @param null|string $method
     */
    public function __construct(array $rawInputData, array $validatedData, array $errors, $method = null);

    /**
     * Check if the validation was successful
     *
     * If there are no validation messages set and the request method is null or POST,
     * the validation result object is considered valid.
     *
     * @return bool
     */
    public function isValid();

    /**
     * Get validation messages
     *
     * @return array
     */
    public function getMessages();

    /**
     * Get the raw input values
     *
     * @return array
     */
    public function getRawValues();

    /**
     * Get the filtered input values
     *
     * @return array
     */
    public function getValues();
}
