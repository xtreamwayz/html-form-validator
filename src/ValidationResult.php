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

final class ValidationResult implements ValidationResultInterface
{
    /**
     * @var array
     */
    private $rawValues;

    /**
     * @var array
     */
    private $values;

    /**
     * @var array
     */
    private $messages;

    /**
     * @var null|string
     */
    private $method;

    /**
     * @var null|string
     */
    private $submitName;

    /**
     * @inheritdoc
     */
    public function __construct(
        array $rawValues,
        array $values,
        array $messages,
        string $method = null,
        string $submitName = null
    ) {
        $this->rawValues = $rawValues;
        $this->values = $values;
        $this->messages = $messages;
        $this->method = $method;
        $this->submitName = $submitName;
    }

    /**
     * @inheritdoc
     */
    public function isValid() : bool
    {
        return (count($this->messages) === 0 && ($this->method === null || $this->method === 'POST'));
    }

    public function isClicked(string $name = null)
    {
        if (null !== $name) {
            return ($this->submitName === $name);
        }

        return $this->submitName;
    }

    /**
     * @inheritdoc
     */
    public function getMessages() : array
    {
        return $this->messages;
    }

    /**
     * @inheritdoc
     */
    public function getRawValues() : array
    {
        return $this->rawValues;
    }

    /**
     * @inheritdoc
     */
    public function getValues() : array
    {
        return $this->values;
    }
}
