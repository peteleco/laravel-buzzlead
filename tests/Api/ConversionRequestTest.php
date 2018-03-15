<?php

namespace Peteleco\Buzzlead\Test\Api;

use GuzzleHttp\Exception\ClientException;
use Peteleco\Buzzlead\Api\ConversionRequest;
use Peteleco\Buzzlead\Api\CreateAmbassadorRequest;
use Peteleco\Buzzlead\Exceptions\RequestFailedException;
use Peteleco\Buzzlead\Model\OrderForm;
use Peteleco\Buzzlead\Model\SourceForm;
use Peteleco\Buzzlead\Test\TestCase;

class ConversionRequestTest extends TestCase
{

    /**
     * @var array
     */
    protected $config;

    /**
     * @var string Ambassador voucher id
     */
    protected $voucher;

    public function setUp()
    {
        parent::setUp();
        $this->config  = app('config');
        $this->voucher = $this->createAmbassador();
    }

    private function createAmbassador()
    {
        $api = new CreateAmbassadorRequest($this->config['buzzlead']);

        $api->setSourceForm($sourceForm = new SourceForm([
            'name'  => $this->faker->name(),
            'email' => $this->faker->email()
        ]));

        $response = $api->send();

        return $response->getVoucher();
    }

    /**
     * @test
     */
    public function it_conversion_url_ok()
    {
        $api = new ConversionRequest($this->config['buzzlead']);

        $this->assertEquals(
            '/api/service/' . $this->config['buzzlead']['api']['email'] . '/notification/convert',
            $api->getUrl()
        );
    }

    /**
     * @test
     */
    public function it_convert()
    {
        $api = new ConversionRequest($this->config['buzzlead']);
        $api->setOrderForm(new OrderForm([
            'codigo' => $this->voucher,
            'pedido' => $this->faker->uuid,
            'total'  => 151.10,
            'nome'   => $this->faker->name,
            'email'  => $this->faker->email,
        ]));
        $response = $api->send();
        $this->assertTrue($response->hasSuccess());
    }

    /**
     * @test
     */
    public function it_convert_duplicated_order()
    {
        $orderId = $this->faker->uuid;
        $api     = new ConversionRequest($this->config['buzzlead']);
        $api->setOrderForm(new OrderForm([
            'codigo' => $this->voucher,
            'pedido' => $orderId,
            'total'  => 200,
            'nome'   => $this->faker->name,
            'email'  => $this->faker->email,
        ]));
        $response = $api->send();
        $this->assertTrue($response->hasSuccess());

        $this->expectException(RequestFailedException::class);
        $this->expectExceptionMessage('Bônus já confirmado para esse e-mail ou pedido. Não foi contabilizado bônus para essa conversão');
        $api->send();
    }

    /**
     * @test
     */
    public function it_convert_without_all_fields()
    {
        $api = new ConversionRequest($this->config['buzzlead']);
        $api->setOrderForm(new OrderForm());

        $this->expectException(ClientException::class);
        $this->expectExceptionMessage('{"success":false,"message":"Parâmetro codigo não informado."}');

        $api->send();
    }

    /**
     * @test
     */
    public function it_convert_with_code_only()
    {
        $api = new ConversionRequest($this->config['buzzlead']);
        $api->setOrderForm(new OrderForm([
            'codigo' => $this->voucher
        ]));

        $this->expectException(RequestFailedException::class);
        $this->expectExceptionMessage('Total não informado. Não foi possível calcular porcentagem de recompensa!');

        $api->send();
    }

}