<?php

namespace Peteleco\Buzzlead\Api\Response;

use Peteleco\Buzzlead\Exceptions\RequestFailedException;

/**
 * Class CreateAmbassadorResponse
 *
 * @package Peteleco\Buzzlead\Response
 */
class CreateAmbassadorResponse extends ApiResponse
{

    /**
     * @var
     */
    protected $voucher;

    /**
     * @param null|string $contents
     *
     * @return CreateAmbassadorResponse
     * @throws RequestFailedException
     */
    public function handle(?string $contents): CreateAmbassadorResponse
    {
        $json = \GuzzleHttp\json_decode($contents);

        $this->setAttributes($json);

        if (! isset($json->success)) {
            throw new RequestFailedException($this->getMessage());
        }

        if (! $this->hasSuccess()) {
            throw new RequestFailedException($this->getMessage());
        }

        // Set data response
        $this->setVoucher($json->data->hashid);

        return $this;
    }

    /**
     * @return mixed
     */
    public function getVoucher()
    {
        return $this->voucher;
    }

    /**
     * @param mixed $voucher
     *
     * @return CreateAmbassadorResponse
     */
    public function setVoucher($voucher)
    {
        $this->voucher = $voucher;

        return $this;
    }
}