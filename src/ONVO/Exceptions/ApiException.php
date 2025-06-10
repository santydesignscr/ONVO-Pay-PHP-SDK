<?php
namespace ONVO\Exceptions;

class ApiException extends \Exception
{
    /**
     * Constructor for the ApiException.
     *
     * @param string $message The Exception message to throw.
     * @param int $code The Exception code.
     * @param \Throwable|null $previous The previous throwable used for the exception chaining.
     */
    public function __construct($message = "", $code = 0, ?\Throwable $previous = null)
    {
        // Call the parent constructor to set the message, code, and previous exception
        parent::__construct($message, $code, $previous);
    }

    /**
     * Custom string representation of the exception.
     *
     * @return string The formatted string with exception details.
     */
    public function __toString()
    {
        return "Error Code: [{$this->code}] Message: {$this->message}\n";
    }

    /**
     * Custom method to format exception details as an array.
     *
     * @return array The array containing exception details.
     */
    public function toArray()
    {
        return [
            'code' => $this->code,
            'message' => $this->message,
            'file' => $this->file,
            'line' => $this->line
        ];
    }
}
