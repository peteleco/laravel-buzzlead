<?php

namespace Peteleco\Buzzlead\Test\Api;

use Peteleco\Buzzlead\Api\GetAmbassadorExtractRequest;
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
        $ambassador    = $this->createAmbassador();
        $firstOrderId  = $this->createConversion($ambassador['voucher']);
        $secondOrderId = $this->createConversion($ambassador['voucher']);

        $api = new GetAmbassadorExtractRequest($this->config['buzzlead']);
        $api->setEmailIndicator($ambassador['email']);
        $api->setIdCampaign($this->config['buzzlead']['campaign']);
        $response = $api->send();

        $this->assertEquals(2, $response->getConversions()->count(), 'A quantidade de conversões diferem.');
        $this->assertEquals($response->getBalance(), 20);
        // Expected Json
        $this->assertJsonStringEqualsJsonString(\GuzzleHttp\json_encode([
            'unit'         => 'point',
            'numeroPedido' => $firstOrderId,
            'totalPercent' => 0,
            'total'        => 10
        ]), \GuzzleHttp\json_encode($response->getConversions()->first()->bonus));

        $this->assertJsonStringEqualsJsonString(\GuzzleHttp\json_encode([
            'unit'         => 'point',
            'numeroPedido' => $secondOrderId,
            'totalPercent' => 0,
            'total'        => 10
        ]), \GuzzleHttp\json_encode($response->getConversions()->last()->bonus));
    }

}