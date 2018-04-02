<?php

namespace Peteleco\Buzzlead\Test\Api;

use GuzzleHttp\Exception\ClientException;
use Peteleco\Buzzlead\Api\ConversionRequest;
use Peteleco\Buzzlead\Exceptions\InvalidAffiliateCodeException;
use Peteleco\Buzzlead\Exceptions\OrderAlreadyConvertedException;
use Peteleco\Buzzlead\Exceptions\RequestFailedException;
use Peteleco\Buzzlead\Exceptions\SelfIndicationException;
use Peteleco\Buzzlead\Model\OrderForm;
use Peteleco\Buzzlead\Test\TestCase;

/**
 * @property array ambassador
 */
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

    /**
     * @var array
     */
    protected $ambassador;

    public function setUp()
    {
        parent::setUp();
        $this->config     = app('config');
        $this->ambassador = $this->createAmbassador();
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
            'codigo' => $this->ambassador['voucher'],
            'pedido' => $this->faker->uuid,
            'total'  => 151.10,
            'nome'   => $this->faker->name,
            'email'  => $this->fakeEmail(),
        ]));
        $response = $api->send();
        $this->assertTrue($response->hasSuccess());
    }

    /**
     * Tenta converter o mesmo pedido duas vezes.
     * @test
     */
    public function it_not_convert_duplicated_order()
    {
        $orderId = $this->faker->uuid;
        $api     = new ConversionRequest($this->config['buzzlead']);
        $api->setOrderForm(new OrderForm([
            'codigo' => $this->ambassador['voucher'],
            'pedido' => $orderId,
            'total'  => 200,
            'nome'   => $this->faker->name,
            'email'  => $this->fakeEmail(),
        ]));
        $response = $api->send();
        $this->assertTrue($response->hasSuccess());

        $this->expectException(OrderAlreadyConvertedException::class);
        $this->expectExceptionMessage('Bônus já confirmado para esse e-mail ou pedido. Não foi contabilizado bônus para essa conversão.');
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
            'codigo' => $this->ambassador['voucher'],
            'nome'   => $this->faker->name,
            'email'  => $this->fakeEmail(),
        ]));

        $this->expectException(RequestFailedException::class);
        $this->expectExceptionMessage('Total não informado. Não foi possível calcular porcentagem de recompensa!');

        $api->send();
    }

    /**
     * @test
     */
    public function it_convert_for_multiple_orders_for_same_customer_from_same_ambassador()
    {

        $api           = new ConversionRequest($this->config['buzzlead']);
        $firstOrderId  = $this->faker->uuid;
        $secondOrderId = $this->faker->uuid;
//        dump($firstOrderId);
//        dump($secondOrderId);

        $api->setOrderForm(new OrderForm([
            'codigo' => $this->ambassador['voucher'],
            'pedido' => $firstOrderId,
            'total'  => 200,
            'nome'   => $name = $this->faker->name,
            'email'  => $email = $this->fakeEmail(),
        ]));
        $response = $api->send();
        $this->assertTrue($response->hasSuccess());

        $api->setOrderForm(new OrderForm([
            'codigo' => $this->ambassador['voucher'],
            'pedido' => $secondOrderId,
            'total'  => 20,
            'nome'   => $name,
            'email'  => $email,
        ]));
        $response2 = $api->send();
        $this->assertTrue($response2->hasSuccess());
    }

    /**
     * @test
     */
    public function it_ambassador_can_auto_referrer()
    {
        $api = new ConversionRequest($this->config['buzzlead']);
        $api->setOrderForm(new OrderForm([
            'codigo' => $this->ambassador['voucher'],
            'pedido' => $this->faker->uuid,
            'total'  => 200,
            'nome'   => $this->ambassador['name'],
            'email'  => $this->ambassador['email'],
        ]));

//        $this->assertFalse($response->hasSuccess());
        $this->expectException(SelfIndicationException::class);
        $this->expectExceptionMessage('Não é permitido gerar bônus para o mesmo e-mail da indicação.');
        $response = $api->send();
    }

    /**
     * @test
     */
    public function it_convert_with_invalid_code()
    {
        $api = new ConversionRequest($this->config['buzzlead']);
        $api->setOrderForm(new OrderForm([
            'codigo' => 'Qwis',
            'pedido' => $this->faker->uuid,
            'total'  => 200,
            'nome'   => $this->faker->name,
            'email'  => $this->fakeEmail(),
        ]));

        $this->expectException(InvalidAffiliateCodeException::class);
        $this->expectExceptionMessage('Código do embaixador inválido.');

        $response = $api->send();

    }
}