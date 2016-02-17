<?php

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
     * @inheritdoc
     */
    public function __construct(array $rawValues, array $values, array $messages)
    {
        $this->rawValues = $rawValues;
        $this->values = $values;
        $this->messages = $messages;
    }

    /**
     * @inheritdoc
     */
    public function isValid()
    {
        return empty($this->messages);
    }

    /**
     * @inheritdoc
     */
    public function getMessages()
    {
        return $this->messages;
    }

    /**
     * @inheritdoc
     */
    public function getRawValues()
    {
        return $this->rawValues;
    }

    /**
     * @inheritdoc
     */
    public function getValues()
    {
        return $this->values;
    }
}
