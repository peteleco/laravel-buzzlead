<?php namespace Peteleco\Buzzlead\Api\Response;

use Peteleco\Buzzlead\Exceptions\InvalidAffiliateCodeException;
use Peteleco\Buzzlead\Exceptions\OrderAlreadyConvertedException;
use Peteleco\Buzzlead\Exceptions\RequestFailedException;
use Peteleco\Buzzlead\Exceptions\SelfIndicationException;

class ConversionResponse extends ApiResponse
{

    public function handle(?string $contents): ConversionResponse
    {
        $json = \GuzzleHttp\json_decode($contents);

        $this->setAttributes($json);

        if (! isset($json->success)) {
            throw new RequestFailedException($this->getMessage());
        }

        if (! $this->hasSuccess()) {
            if($this->getMessage() == 'Bônus já confirmado para esse e-mail ou pedido. Não foi contabilizado bônus para essa conversão') {
                throw new OrderAlreadyConvertedException();
            }

            if($this->getStatusCode() == 200 && ($this->getMessage() == 'Não é permitido gerar bônus para o mesmo e-mail da indicação.')) {
                throw new SelfIndicationException();
            }

            if($this->getStatusCode() == 404 && ($this->getMessage() == 'Indicação não encontrada.')) {
                throw new InvalidAffiliateCodeException();
            }

            throw new RequestFailedException($this->getMessage());
        }

        return $this;
    }
}