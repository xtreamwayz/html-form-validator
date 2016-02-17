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
     * @deprecated 0.3.0 Use getMessages() instead
     */
    public function getErrorMessages()
    {
        return $this->getMessages();
    }

    /**
     * @inheritdoc
     */
    public function getMessages()
    {
        return $this->messages;
    }

    /**
     * @deprecated 0.3.0 Use getRawValues() instead
     */
    public function getRawInputValues()
    {
        return $this->getRawValues();
    }

    /**
     * @inheritdoc
     */
    public function getRawValues()
    {
        return $this->rawValues;
    }

    /**
     * @deprecated 0.3.0 Use getValues() instead
     */
    public function getValidValues()
    {
        return $this->getValues();
    }

    /**
     * @inheritdoc
     */
    public function getValues()
    {
        return $this->values;
    }
}
