<?php
namespace Peteleco\Buzzlead\Exceptions;

/**
 * Class RequestFailedException
 *
 * @package Peteleco\Buzzlead\Exceptions
 */
class RequestFailedException extends \Exception
{

    protected $message = 'There was an error processing the request.';
}