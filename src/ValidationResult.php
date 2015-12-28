<?php

namespace Xtreamwayz\HTMLFormValidator;

class ValidationResult
{
    private $rawInputData;

    private $validatedData;

    private $errors;

    public function __construct($rawInputData, $validatedData, $errors)
    {
        $this->errors = $errors;
        $this->rawInputData = $rawInputData;
        $this->validatedData = $validatedData;
    }

    public function isValid()
    {
        return empty($this->errors);
    }

    public function getErrorMessages()
    {
        return $this->errors;
    }

    public function getRawInputValues()
    {
        return $this->rawInputData;
    }

    public function getValidValues()
    {
        return $this->validatedData;
    }
}
