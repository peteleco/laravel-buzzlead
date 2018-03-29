<?php

namespace Peteleco\Buzzlead\Helpers;

class Buzzlead
{

    /**
     *
     */
    static public function tracker()
    {
        if(!self::isEnabled()) {
            return '';
        }
        if (self::isSandboxMode()) {
            return '<script src="' . self::config()['script']['sandbox'] . '" async></script>';
        }

        return '<script src="' . self::config()['script']['production'] . '" async></script>';
    }

    /**
     * @return bool
     */
    static public function isSandboxMode()
    {
        if (self::config()['env'] == 'production') {
            return false;
        }

        return true;
    }

    /**
     * @return mixed
     */
    static public function config()
    {
        return config('buzzlead');
    }

    /**
     * Verifica se o pacote está ativo
     *
     * @return bool
     */
    static public function isEnabled(): bool
    {
        return self::config()['enabled'];
    }
}