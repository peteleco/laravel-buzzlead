<?php namespace Peteleco\Buzzlead\Api\Response;

use Peteleco\Buzzlead\Exceptions\RequestFailedException;

class ConversionResponse extends ApiResponse
{

    public function handle(?string $contents): ConversionResponse
    {
        $json = \GuzzleHttp\json_decode($contents);
dump($json);
        $this->setAttributes($json);

        if (! isset($json->success)) {
            throw new RequestFailedException($this->getMessage());
        }

        if (! $this->hasSuccess()) {
            throw new RequestFailedException($this->getMessage());
        }

        return $this;
    }
}