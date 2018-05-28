<?php

namespace Peteleco\Buzzlead\Api;

use Peteleco\Buzzlead\Api\Response\ApiResponse;
use Peteleco\Buzzlead\Api\Response\WithdrawalResponse;
use Psr\Http\Message\ResponseInterface;

class WithdrawalRequest extends ApiRequest
{

    /**
     * Api endpoint
     *
     * @var string
     */
    protected $path = '/api/service/{emailUser}/bonus/rescue/{numberRequest}';

    /**
     * @var string
     */
    protected $numberRequest;

    /**
     * Realize the request
     *
     * @return mixed
     */
    public function send()
    {
        try {
            $response = $this->client->post($this->getUrl(), [
                'debug'                             => false,
                \GuzzleHttp\RequestOptions::HEADERS => $this->requestHeaders(),
            ]);
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            return $this->handleException($e);
        }

        return $this->handleResponse($response);
    }

    /**
     * @param null|ResponseInterface $response
     *
     * @return ApiResponse
     */
    public function handleResponse(?ResponseInterface $response): ApiResponse
    {
        return new WithdrawalResponse($response->getStatusCode(), $response->getBody()->getContents());
    }

    /**
     * @return string
     */
    public function getNumberRequest(): string
    {
        return $this->numberRequest;
    }

    /**
     * @param string $numberRequest
     *
     * @return WithdrawalRequest
     */
    public function setNumberRequest(string $numberRequest): WithdrawalRequest
    {
        $this->numberRequest = $numberRequest;

        return $this;
    }

    public function handleException(\GuzzleHttp\Exception\ClientException $exception): ApiResponse
    {
        if ($exception->getCode() != 404) {
            throw $exception;
        }

        return new WithdrawalResponse($exception->getCode(), $exception->getResponse()->getBody()->getContents());
    }
}