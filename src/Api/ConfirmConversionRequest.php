<?php

namespace Peteleco\Buzzlead\Api;

use Peteleco\Buzzlead\Api\Response\ApiResponse;
use Peteleco\Buzzlead\Api\Response\ConfirmConversionResponse;
use Psr\Http\Message\ResponseInterface;

class ConfirmConversionRequest extends ApiRequest
{

    /**
     * Api endpoint
     *
     * @var string
     */
    protected $path = '/api/service/{emailUser}/bonus/status/{numberRequest}/{statusRequest}';


    /**
     * @var string
     */
    protected $numberRequest;

    protected $statusRequest = 'confirmado';

    /**
     * Realize the request
     *
     * @return mixed
     */
    public function send()
    {
        $response = $this->client->post($this->getUrl(), [
            'debug'                             => false,
            \GuzzleHttp\RequestOptions::HEADERS => $this->requestHeaders()
        ]);

        return $this->handleResponse($response);
    }

    /**
     * @param null|ResponseInterface $response
     *
     * @return ApiResponse
     */
    public function handleResponse(?ResponseInterface $response): ApiResponse
    {
        return new ConfirmConversionResponse($response->getStatusCode(), $response->getBody()->getContents());
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
     * @return ConfirmConversionRequest
     */
    public function setNumberRequest(string $numberRequest): ConfirmConversionRequest
    {
        $this->numberRequest = $numberRequest;

        return $this;
    }
}