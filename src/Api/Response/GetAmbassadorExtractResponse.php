<?php

namespace Peteleco\Buzzlead\Api\Response;

use Illuminate\Support\Collection;
use Peteleco\Buzzlead\Exceptions\AmbassadorWithoutConversionsException;

class GetAmbassadorExtractResponse extends ApiResponse
{

    /**
     * @var Collection
     */
    protected $conversions;

    /**
     * @param null|string $contents
     *
     * @return GetAmbassadorExtractResponse
     * @throws AmbassadorWithoutConversionsException
     */
    public function handle(?string $contents): GetAmbassadorExtractResponse
    {
        $json = \GuzzleHttp\json_decode($contents);

        if (! isset($json->listaDeBonus)) {
            throw new AmbassadorWithoutConversionsException();
        }
//        if (! isset($json->listaDeBonus->bonus)) {
//            return $this->setConversions(Collection::make());
//        }

        // return $this->setConversions(Collection::make($json->listaDeBonus->bonus));
        return $this->setConversions(Collection::make($json->listaDeBonus));
    }

    /**
     * @return Collection
     */
    public function getConversions(): Collection
    {
        return $this->conversions;
    }

    /**
     * @param Collection $conversions
     *
     * @return GetAmbassadorExtractResponse
     */
    public function setConversions(Collection $conversions): GetAmbassadorExtractResponse
    {
        $this->conversions = $conversions;

        return $this;
    }

    /**
     * Retorna o saldo
     *
     * @return float
     */
    public function getBalance(): float
    {
        $balance = 0.0;
        $this->getConversions()->each(function ($conversion) use (&$balance) {
            if(!isset($conversion->bonus->resgate) && $conversion->bonus->unit == 'point') {
                $balance += $conversion->bonus->total;
            }
        });
        return $balance;
    }
}