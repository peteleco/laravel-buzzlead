<?php

namespace Peteleco\Buzzlead\Api;

use Peteleco\Buzzlead\Api\Response\ApiResponse;
use Peteleco\Buzzlead\Api\Response\ConversionResponse;
use Peteleco\Buzzlead\Model\OrderForm;
use Psr\Http\Message\ResponseInterface;

/**
 * Class ConversionRequest
 * Cria a conversão de um pedido,
 * o pedido entrará como pendente de pagamento.
 * O embaixador só pontua transações autorizadas.
 *
 * @package Peteleco\Buzzlead\Api
 */
class ConversionRequest extends ApiRequest
{

    /**
     * Api endpoint
     *
     * @var string
     */
    protected $path = '/api/service/{emailUser}/notification/convert';

    /**
     * @var string
     */
    protected $voucher;

    /**
     * @var OrderForm
     */
    protected $orderForm;

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
                \GuzzleHttp\RequestOptions::JSON    => $this->orderForm->toArray()
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
        return new ConversionResponse($response->getStatusCode(), $response->getBody()->getContents());
    }

    /**
     * @param \GuzzleHttp\Exception\ClientException $exception
     *
     * @return ApiResponse
     */
    public function handleException(\GuzzleHttp\Exception\ClientException $exception): ApiResponse
    {
        if ($exception->getCode() != 404) {
            throw $exception;
        }

        return new ConversionResponse($exception->getCode(), $exception->getResponse()->getBody()->getContents());
    }

    /**
     * @return string
     */
    public function getVoucher(): string
    {
        return $this->voucher;
    }

    /**
     * @param string $voucher
     *
     * @return ConversionRequest
     */
    public function setVoucher(string $voucher): ConversionRequest
    {
        $this->voucher = $voucher;

        return $this;
    }

    /**
     * @return OrderForm
     */
    public function getOrderForm(): OrderForm
    {
        return $this->orderForm;
    }

    /**
     * @param OrderForm $orderForm
     *
     * @return ConversionRequest
     */
    public function setOrderForm(OrderForm $orderForm): ConversionRequest
    {
        $this->orderForm = $orderForm;

        return $this;
    }

}