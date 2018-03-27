<?php

namespace Peteleco\Buzzlead\Exceptions;

class OrderAlreadyConvertedException extends \Exception
{
    protected $message = 'Bônus já confirmado para esse e-mail ou pedido. Não foi contabilizado bônus para essa conversão.';
}