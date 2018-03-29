<?php

namespace Peteleco\Buzzlead\Exceptions;

class InvalidAffiliateCodeException extends \Exception
{
    protected $message = 'Código do embaixador inválido.';
}