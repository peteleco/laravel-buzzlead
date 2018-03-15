<?php namespace Peteleco\Buzzlead\Api\Response;

use Peteleco\Buzzlead\Exceptions\RequestFailedException;

/**
 * Class ConfirmConversionResponse
 *
 * @package Peteleco\Buzzlead\Api\Response
 */
class ConfirmConversionResponse extends ApiResponse
{

    public function handle(?string $contents)
    {
        $json = \GuzzleHttp\json_decode($contents);

        $this->setAttributes($json);

        if (! isset($json->success)) {
            throw new RequestFailedException($this->getMessage());
        }

        if (! $this->hasSuccess()) {
            throw new RequestFailedException($this->getMessage());
        }

        $this->setData($json->data);

        return $this;
    }
}