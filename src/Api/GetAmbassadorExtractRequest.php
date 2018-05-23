<?php

namespace Peteleco\Buzzlead\Api;

use Peteleco\Buzzlead\Api\Response\GetAmbassadorExtractResponse;
use Peteleco\Buzzlead\Api\Response\ApiResponse;
use Psr\Http\Message\ResponseInterface;

class GetAmbassadorExtractRequest extends ApiRequest
{

    /**
     * Api endpoint
     *
     * @var string
     */
    protected $path = '/api/service/{emailUser}/bonus/{emailIndicator}/{idCampaign}';

    /**
     * @var string
     */
    protected $emailIndicator;

    /**
     * @var string
     */
    protected $idCampaign;

    public function __construct(array $config = [])
    {
        parent::__construct($config);
    }

    /**
     * Realize the request
     *
     * @return mixed
     */
    public function send()
    {
        $response = $this->client->get($this->getUrl(), [
            'debug'                             => false,
            \GuzzleHttp\RequestOptions::HEADERS => $this->requestHeaders(),
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
        return new GetAmbassadorExtractResponse($response->getStatusCode(), $response->getBody()->getContents());
    }

    /**
     * @return string
     */
    public function getEmailIndicator(): string
    {
        return $this->emailIndicator;
    }

    /**
     * @param string $emailIndicator
     *
     * @return GetAmbassadorExtractRequest
     */
    public function setEmailIndicator(string $emailIndicator): GetAmbassadorExtractRequest
    {
        $this->emailIndicator = $emailIndicator;

        return $this;
    }

    /**
     * @return string
     */
    public function getIdCampaign(): string
    {
        return $this->idCampaign;
    }

    /**
     * @param string $idCampaign
     *
     * @return GetAmbassadorExtractRequest
     */
    public function setIdCampaign(string $idCampaign): GetAmbassadorExtractRequest
    {
        $this->idCampaign = $idCampaign;

        return $this;
    }
}