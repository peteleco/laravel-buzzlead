<?php

namespace Peteleco\Buzzlead\Api;

use Peteleco\Buzzlead\Api\Response\ApiResponse;
use Peteleco\Buzzlead\Api\Response\CreateAmbassadorResponse;
use Peteleco\Buzzlead\Exceptions\InvalidEmailException;
use Peteleco\Buzzlead\Model\CampaignForm;
use Peteleco\Buzzlead\Model\SourceForm;
use Psr\Http\Message\ResponseInterface;


/**
 * Class CreateAmbassadorRequest
 *
 * @package Peteleco\Buzzlead\Api
 */
class CreateAmbassadorRequest extends ApiRequest
{

    /**
     * Api endpoint
     *
     * @var string
     */
    protected $path = '/api/service/{emailUser}/notifications/ambassador';


    /**
     * @var SourceForm
     */
    protected $sourceForm;

    /**
     * @var CampaignForm
     */
    protected $campaignForm;

    public function __construct(array $config = [])
    {
        parent::__construct($config);
        $this->setCampaignForm(new CampaignForm([
            '_id' => $config['campaign']
        ]));
    }

    public function send(): ApiResponse
    {
        $response = $this->client->post($this->getUrl(), [
            'debug'                             => false,
            \GuzzleHttp\RequestOptions::HEADERS => $this->requestHeaders(),
            \GuzzleHttp\RequestOptions::JSON    => [
                'origem'    => $this->sourceForm->toArray(),
                'campaign'  => $this->campaignForm->toArray(),
                'sendMail' => false,
                'short'     => false,
            ]
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
        return new CreateAmbassadorResponse($response->getStatusCode(), $response->getBody()->getContents());
    }

    /**
     * @return SourceForm
     */
    public function getSourceForm(): SourceForm
    {
        return $this->sourceForm;
    }

    /**
     * @param SourceForm $sourceForm
     *
     * @return CreateAmbassadorRequest
     * @throws InvalidEmailException
     */
    public function setSourceForm(SourceForm $sourceForm): CreateAmbassadorRequest
    {

        // if (!filter_var($sourceForm->email, FILTER_VALIDATE_EMAIL)) {
        if(empty($sourceForm->email)) {
            throw new InvalidEmailException();
        }

        $this->sourceForm = $sourceForm;

        return $this;
    }

    /**
     * @return CampaignForm
     */
    public function getCampaignForm(): CampaignForm
    {
        return $this->campaignForm;
    }

    /**
     * @param CampaignForm $campaignForm
     *
     * @return CreateAmbassadorRequest
     */
    public function setCampaignForm(CampaignForm $campaignForm): CreateAmbassadorRequest
    {
        $this->campaignForm = $campaignForm;

        return $this;
    }
}