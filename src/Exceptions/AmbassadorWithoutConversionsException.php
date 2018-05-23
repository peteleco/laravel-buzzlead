<?php
namespace Peteleco\Buzzlead\Exceptions;

class AmbassadorWithoutConversionsException extends \Exception
{
    protected $message = 'O embaixador não possuí conversões.';
}