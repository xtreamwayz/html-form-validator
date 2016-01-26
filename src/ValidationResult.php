<?php

namespace Xtreamwayz\HTMLFormValidator;

class ValidationResult
{
    /**
     * @var array
     */
    private $rawInputData;

    /**
     * @var array
     */
    private $validatedData;

    /**
     * @var array
     */
    private $errors;

    /**
     * ValidationResult constructor.
     *
     * @param array $rawInputData
     * @param array $validatedData
     * @param array $errors
     */
    public function __construct(array $rawInputData, array $validatedData, array $errors)
    {
        $this->errors = $errors;
        $this->rawInputData = $rawInputData;
        $this->validatedData = $validatedData;
    }

    /**
     * Check if the validation was successful
     *
     * @return bool
     */
    public function isValid()
    {
        return empty($this->errors);
    }

    /**
     * Get error messages
     *
     * @return array
     */
    public function getErrorMessages()
    {
        return $this->errors;
    }

    /**
     * Get the raw input values
     *
     * @return array
     */
    public function getRawInputValues()
    {
        return $this->rawInputData;
    }

    /**
     * Get the filtered input values
     *
     * @return array
     */
    public function getValidValues()
    {
        return $this->validatedData;
    }
}
