<?php namespace Peteleco\Buzzlead\Api\Response;

class WithdrawalResponse extends ApiResponse
{

    public function handle(?string $contents): WithdrawalResponse
    {
        $json = \GuzzleHttp\json_decode($contents);

        $this->setAttributes($json);

        return $this;
    }
}