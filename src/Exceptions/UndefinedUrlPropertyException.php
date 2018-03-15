<?php

namespace Peteleco\Buzzlead\Exceptions;

use Throwable;

/**
 * Class UndefinedUrlPropertyException
 *
 * @package Peteleco\Buzzlead
 */
class UndefinedUrlPropertyException extends \Exception
{

    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        if (! $message) {
            $message = "The url property is undefined";
        } else {
            $message = "The url property $message is undefined";
        }

        parent::__construct($message, $code, $previous);
    }
}

