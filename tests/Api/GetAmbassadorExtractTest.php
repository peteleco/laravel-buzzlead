<?php

namespace Peteleco\Buzzlead\Test\Api;

use Peteleco\Buzzlead\Api\GetAmbassadorExtractRequest;
use Peteleco\Buzzlead\Exceptions\AmbassadorWithoutConversionsException;
use Peteleco\Buzzlead\Test\TestCase;

class GetAmbassadorExtractTest extends TestCase
{

    /**
     * @var array
     */
    protected $config;

    /**
     * @var string Ambassador voucher id
     */
    protected $voucher;

    /**
     * @var array
     */
    protected $ambassador;

    public function setUp()
    {
        parent::setUp();
        $this->config = app('config');
    }

    /**
     * @test verifica se a url para está correta
     */
    public function it_url_ok()
    {
        $ambassador = $this->createAmbassador();
        $api        = new GetAmbassadorExtractRequest($this->config['buzzlead']);

        $api->setEmailIndicator($ambassador['email']);
        $api->setIdCampaign($this->config['buzzlead']['campaign']);

        $this->assertEquals(
            '/api/service/' . $this->config['buzzlead']['api']['email'] . '/bonus/' . $ambassador['email'] . '/' . $this->config['buzzlead']['campaign'],
            $api->getUrl()
        );
    }

    /**
     * @test Extrato de um embaixador sem conversoes
     */
    public function itGetExtractFromAmbassadorWithoutConversion()
    {
        $ambassador = $this->createAmbassador();
        $api        = new GetAmbassadorExtractRequest($this->config['buzzlead']);
        $api->setEmailIndicator($ambassador['email']);
        $api->setIdCampaign($this->config['buzzlead']['campaign']);

        $response = $api->send();
        $this->assertEquals(0, $response->getConversions()->count(), 'A quantidade de conversões diferem.');
        $this->assertEquals($response->getBalance(), 0);
    }

    /**
     * @test Retorna o extrato do pedido
     */
    public function itGetExtractFromAmbassador()
    {
        $ambassador = $this->createAmbassador();
        $this->createConversion($ambassador['voucher']);
        $this->createConversion($ambassador['voucher']);

        $api = new GetAmbassadorExtractRequest($this->config['buzzlead']);
        $api->setEmailIndicator($ambassador['email']);
        $api->setIdCampaign($this->config['buzzlead']['campaign']);
        $response = $api->send();
        $this->assertEquals(2, $response->getConversions()->count(), 'A quantidade de conversões diferem.');
        $this->assertEquals($response->getBalance(), 20);
    }

}