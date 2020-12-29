<?php

declare(strict_types=1);

namespace Xtreamwayz\HTMLFormValidator;

use function array_replace_recursive;
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
     * @inheritDoc
     */
    public function isValid(): bool
    {
        return count($this->messages) === 0 && ($this->method === null || $this->method === 'POST');
    }

    /**
     * @inheritDoc
     */
    public function isClicked(string $name): bool
    {
        return $this->submitName === $name;
    }

    /**
     * @inheritDoc
     */
    public function getClicked(): ?string
    {
        return $this->submitName;
    }

    /**
     * @inheritDoc
     */
    public function addMessages(array $messages): void
    {
        $this->messages = array_replace_recursive($this->messages, $messages);
    }

    /**
     * @inheritDoc
     */
    public function getMessages(): array
    {
        return $this->messages;
    }

    /**
     * @inheritDoc
     */
    public function getRawValues(): array
    {
        return $this->rawValues;
    }

    /**
     * @inheritDoc
     */
    public function getValues(): array
    {
        return $this->values;
    }
}
