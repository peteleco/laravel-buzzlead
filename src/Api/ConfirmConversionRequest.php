<?php

namespace Peteleco\Buzzlead\Api;

use Peteleco\Buzzlead\Api\Response\ApiResponse;
use Psr\Http\Message\ResponseInterface;

class ConfirmConversionRequest extends ApiRequest
{

    /**
     * Realize the request
     *
     * @return mixed
     */
    public function send()
    {
        // TODO: Implement send() method.
    }

    /**
     * @param null|ResponseInterface $response
     *
     * @return ApiResponse
     */
    public function handleResponse(?ResponseInterface $response): ApiResponse
    {
        // TODO: Implement handleResponse() method.
    }
}