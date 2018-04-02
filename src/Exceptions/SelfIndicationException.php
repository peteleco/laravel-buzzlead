<?php

namespace Peteleco\Buzzlead\Exceptions;

class SelfIndicationException extends \Exception
{
    protected $message = 'Não é permitido gerar bônus para o mesmo e-mail da indicação.';
}