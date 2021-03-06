<?php

namespace Peteleco\Buzzlead\Test\Api;


use Peteleco\Buzzlead\Api\CreateAmbassadorRequest;
use Peteleco\Buzzlead\Exceptions\InvalidEmailException;
use Peteleco\Buzzlead\Exceptions\RequestFailedException;
use Peteleco\Buzzlead\Model\SourceForm;
use Peteleco\Buzzlead\Test\TestCase;

class CreateAmbassadorRequestTest extends TestCase
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
     * @test
     */
    public function it_sandbox_mode()
    {
        $api = new CreateAmbassadorRequest($this->config['buzzlead']);

        $this->assertTrue($api->isSandboxMode());
    }

    /**
     * @test
     */
    public function it_create_ambassador_url_with_email()
    {
        $api = new CreateAmbassadorRequest($this->config['buzzlead']);

        $this->assertEquals(
            '/api/service/' . $this->config['buzzlead']['api']['email'] . '/notifications/ambassador',
            $api->getUrl()
        );
    }

    /**
     * @test
     */
    public function it_create_duplicated_ambassador()
    {
        $api = new CreateAmbassadorRequest($this->config['buzzlead']);

        $api->setSourceForm($sourceForm = new SourceForm([
            'name'  => $this->faker->name(),
            'email' => $this->fakeEmail()
        ]));

        $firstResponse = $api->send();
        $this->assertTrue($firstResponse->isSuccess());
        $createdVoucher = $firstResponse->getVoucher();

//        $this->expectException(RequestFailedException::class);
//        $this->expectExceptionMessage('Já existe link pessoal cadastrado para esta pessoa nesta campanha.');
        $api->setSourceForm($sourceForm);
        $secondResponse = $api->send();
        $this->assertTrue($secondResponse->isSuccess());
        $this->assertEquals($createdVoucher, $secondResponse->getVoucher());
    }

    /**
     * @test
     */
    public function it_create_ambassador_with_wrong_email_account()
    {
        $config                 = $this->config['buzzlead'];
        $config['api']['email'] = $this->fakeEmail();
        $api                    = new CreateAmbassadorRequest($config);

        $api->setSourceForm(new SourceForm([
            'name'  => $this->faker->name(),
            'email' => $this->fakeEmail()
        ]));

        $this->expectException(RequestFailedException::class);
        $this->expectExceptionMessage('Invalid User');
        $api->send();

    }

    /**
     * @test
     * O ideal neste teste seria o buzzlead retornar 401
     */
    public function it_create_ambassador_without_campaign()
    {
        $config             = $this->config['buzzlead'];
        $config['campaign'] = '09120391203901293012';
        $api                = new CreateAmbassadorRequest($config);

        $api->setSourceForm(new SourceForm([
            'name'  => $this->faker->name(),
            'email' => $this->fakeEmail()
        ]));

        $this->expectException(RequestFailedException::class);
        $this->expectExceptionMessage('Campanha informada não existe');
        $api->send();
    }

    /**
     * @test
     */
    public function it_create_ambassador_without_email()
    {
        $api = new CreateAmbassadorRequest($this->config['buzzlead']);
        $this->expectException(InvalidEmailException::class);
        $this->expectExceptionMessage('The submitted email is invalid.');
        $api->setSourceForm(new SourceForm([
            'name' => $this->faker->name(),
            // 'email' => 'ldiascarmo@gmail.com'
        ]));

        $api->send();
    }

    /**
     * @test
     */
    public function it_create_ambassador_with_typo_at_email()
    {
        $api = new CreateAmbassadorRequest($this->config['buzzlead']);

        $this->expectException(InvalidEmailException::class);
        $this->expectExceptionMessage('The submitted email is invalid.');

        $api->setSourceForm(new SourceForm([
            'name'  => $this->faker->name(),
            'email' => ''
        ]));

        // $api->send();
    }

    /**
     * @test
     */
    public function it_create_ambassador()
    {
        $api = new CreateAmbassadorRequest($this->config['buzzlead']);

        $api->setSourceForm($sourceForm = new SourceForm([
            'name'  => $this->faker->name(),
            'email' => $this->fakeEmail()
        ]));

        $response = $api->send();
        $this->assertTrue($response->hasSuccess());
        $this->assertNotEmpty($response->getVoucher());
    }

    /**
     * @test Teste com email real para verificar se os email estao sendo enviados
     *       quando não deveria
     */
    public function it_create_ambassador_and_do_not_send_welcome_email()
    {
        $api = new CreateAmbassadorRequest($this->config['buzzlead']);
        $email = $this->config['buzzlead']['test_email_name'] . '+buzzlead_' . microtime() . $this->config['buzzlead']['test_email_domain'];
        $api->setSourceForm($sourceForm = new SourceForm([
            'name'  => $this->faker->name(),
            'email' => $email
        ]));

        $response = $api->send();
        $this->assertTrue($response->hasSuccess());
        $this->assertNotEmpty($response->getVoucher());
    }


}