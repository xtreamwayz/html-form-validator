<?php

namespace Xtreamwayz\HTMLFormValidator;

interface ValidationResultInterface
{
    /**
     * ValidationResult constructor.
     *
     * @param array $rawInputData
     * @param array $validatedData
     * @param array $errors
     */
    public function __construct(array $rawInputData, array $validatedData, array $errors);

    /**
     * Check if the validation was successful
     *
     * @return bool
     */
    public function isValid();

    /**
     * Get error messages
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
