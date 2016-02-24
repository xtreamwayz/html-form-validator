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
     * @var null|string
     */
    private $method;

    /**
     * @inheritdoc
     */
    public function __construct(array $rawValues, array $values, array $messages, $method = null)
    {
        $this->rawValues = $rawValues;
        $this->values = $values;
        $this->messages = $messages;
        $this->method = $method;
    }

    /**
     * @inheritdoc
     */
    public function isValid()
    {
        return (empty($this->messages) && ($this->method === null || $this->method == 'POST'));
    }

    /**
     * @deprecated 0.3.0 Use getMessages() instead
     * @codeCoverageIgnore
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
     * @codeCoverageIgnore
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
     * @codeCoverageIgnore
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
