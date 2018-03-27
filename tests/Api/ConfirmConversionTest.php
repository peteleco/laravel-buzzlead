<?php namespace Peteleco\Buzzlead\Test\Api;

use Peteleco\Buzzlead\Api\ConfirmConversionRequest;
use Peteleco\Buzzlead\Test\TestCase;

class ConfirmConversionTest extends TestCase
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
        $this->config  = app('config');
        $this->ambassador = $this->createAmbassador();
    }

    /**
     * @test
     */
    public function it_conversion_url_ok()
    {
        $api           = new ConfirmConversionRequest($this->config['buzzlead']);
        $numberRequest = $this->faker->uuid;
        $api->setNumberRequest($numberRequest);

        $this->assertEquals(
            '/api/service/' . $this->config['buzzlead']['api']['email'] . '/bonus/status/' . $numberRequest . '/confirmado',
            $api->getUrl()
        );
    }

    /**
     * @test
     */
    public function it_confirm_conversion()
    {
        $numberRequest = $this->createConversion($this->ambassador['voucher']);
        $api           = new ConfirmConversionRequest($this->config['buzzlead']);
        $response      = $api->setNumberRequest($numberRequest)->send();

        $this->assertTrue($response->hasSuccess());
    }

}