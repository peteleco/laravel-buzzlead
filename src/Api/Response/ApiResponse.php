<?php

namespace Peteleco\Buzzlead\Api\Response;

abstract class ApiResponse
{

    /**
     * @var bool
     */
    protected $success;

    /**
     * @var string
     */
    protected $message;
    /**
     * @var
     */
    protected $data;
    /**
     * @var int
     */
    private $statusCode;
    /**
     * @var null|string
     */
    private $contents;

    public function __construct(int $statusCode, ?string $contents)
    {

        $this->statusCode = $statusCode;
        $this->contents   = $contents;

        $this->handle($this->contents);
    }

    abstract public function handle(?string $contents);

    /**
     * @return bool
     */
    public function isSuccess(): bool
    {
        return $this->success;
    }

    /**
     * @param bool $success
     *
     * @return ApiResponse
     */
    public function setSuccess(bool $success): ApiResponse
    {
        $this->success = $success;

        return $this;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @param string $message
     *
     * @return ApiResponse
     */
    public function setMessage(string $message): ApiResponse
    {
        $this->message = $message;

        return $this;
    }

    /**
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * @return bool
     */
    public function hasSuccess(): bool
    {
        return $this->success;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param mixed $data
     *
     * @return ApiResponse
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Set response attributes
     *
     * @param $json
     */
    protected function setAttributes($json)
    {
        if (isset($json->success)) {
            $this->setSuccess($json->success);
        }

        if (isset($json->message)) {
            $this->setMessage($json->message);
        }
    }
}