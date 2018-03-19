<?php

declare(strict_types=1);

namespace Xtreamwayz\HTMLFormValidator;

use function count;

final class ValidationResult implements ValidationResultInterface
{
    /** @var array */
    private $rawValues;

    /** @var array */
    private $values;

    /** @var array */
    private $messages;

    /** @var null|string */
    private $method;

    /** @var null|string */
    private $submitName;

    public function __construct(
        array $rawValues,
        array $values,
        array $messages,
        ?string $method = null,
        ?string $submitName = null
    ) {
        $this->rawValues  = $rawValues;
        $this->values     = $values;
        $this->messages   = $messages;
        $this->method     = $method;
        $this->submitName = $submitName;
    }

    /**
     * @inheritdoc
     */
    public function isValid() : bool
    {
        return count($this->messages) === 0 && ($this->method === null || $this->method === 'POST');
    }

    /**
     * @inheritdoc
     */
    public function isClicked(string $name) : bool
    {
        return $this->submitName === $name;
    }

    /**
     * @inheritdoc
     */
    public function getClicked() : ?string
    {
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
