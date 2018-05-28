<?php

namespace Peteleco\Buzzlead\Test\Api;

use Peteleco\Buzzlead\Api\GetAmbassadorExtractRequest;
use Peteleco\Buzzlead\Api\WithdrawalRequest;
use Peteleco\Buzzlead\Test\TestCase;

class WithdrawalRequestTest extends TestCase
{

    /**
     * @var array
     */
    protected $config;

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
        $api  = new WithdrawalRequest($this->config['buzzlead']);
        $api->setNumberRequest($uuid = $this->faker->uuid);
        $this->assertEquals(
            '/api/service/' . $this->config['buzzlead']['api']['email'] . '/bonus/rescue/' . $uuid,
            $api->getUrl()
        );
    }

    /**
     * @test Verifica se realiza o resgate de uma conversao
     */
    public function it_withdrawal_a_conversion()
    {
        $ambassador = $this->createAmbassador();
        $orderId1 = $this->createConversion($ambassador['voucher']);
        $orderId2 = $this->createConversion($ambassador['voucher']);
        $orderId3 = $this->createConversion($ambassador['voucher']);

        $api = new WithdrawalRequest($this->config['buzzlead']);
        $api->setNumberRequest($orderId2);
        $response = $api->send();
        $this->assertTrue($response->isSuccess());

        // Confere o saldo
        $api2 = new GetAmbassadorExtractRequest($this->config['buzzlead']);
        $api2->setEmailIndicator($ambassador['email']);
        $api2->setIdCampaign($this->config['buzzlead']['campaign']);
        $response2 = $api2->send();
        $this->assertEquals(3, $response2->getConversions()->count(), 'A quantidade de conversões diferem.');
        $this->assertEquals($response2->getBalance(), 20);

    }

    /**
     * @test Tenta resgatar uma conversao que nao existe
     */
    public function it_withdrawal_a_nonexistent_conversion()
    {
        $api = new WithdrawalRequest($this->config['buzzlead']);
        $api->setNumberRequest($this->faker->uuid);
        $response = $api->send();

        $this->assertFalse($response->isSuccess());
    }

    /**
     * @test Tenta resgatar um conversao que já foi resgatada
     */
    public function it_withdrawal_the_same_twice()
    {
        $ambassador = $this->createAmbassador();
        $orderId1 = $this->createConversion($ambassador['voucher']);

        $api = new WithdrawalRequest($this->config['buzzlead']);
        $api->setNumberRequest($orderId1);
        $response = $api->send();
        $this->assertTrue($response->isSuccess());

        $this->assertFalse($api->send()->isSuccess());

    }
}