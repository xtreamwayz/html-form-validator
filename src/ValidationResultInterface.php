<?php
/**
 * html-form-validator (https://github.com/xtreamwayz/html-form-validator)
 *
 * @see       https://github.com/xtreamwayz/html-form-validator for the canonical source repository
 * @copyright Copyright (c) 2016 Geert Eltink (https://xtreamwayz.com/)
 * @license   https://github.com/xtreamwayz/html-form-validator/blob/master/LICENSE.md MIT
 */

namespace Xtreamwayz\HTMLFormValidator;

interface ValidationResultInterface
{
    /**
     * Check if the validation was successful
     *
     * If there are no validation messages set and the request method is null or POST,
     * the validation result object is considered valid.
     */
    public function isValid() : bool;

    /**
     * Checks if submit button with a specific name is clicked
     */
    public function isClicked(string $name) : bool;

    /**
     * Returns the name of the clicked button
     */
    public function getClicked() : ?string;

    /**
     * Get validation messages
     */
    public function getMessages() : array;

    /**
     * Get the raw input values
     */
    public function getRawValues() : array;

    /**
     * Get the filtered input values
     */
    public function getValues() : array;
}
