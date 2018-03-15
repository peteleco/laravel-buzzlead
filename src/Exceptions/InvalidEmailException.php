<?php

namespace Peteleco\Buzzlead\Exceptions;

/**
 * Class InvalidEmailException
 *
 * @package Peteleco\Buzzlead\Exceptions
 */
class InvalidEmailException extends \Exception
{
    protected $message = 'The submitted email is invalid.';
}