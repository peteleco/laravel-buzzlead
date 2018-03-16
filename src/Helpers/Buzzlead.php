<?php

namespace Peteleco\Buzzlead\Helpers;

class Buzzlead
{

    /**
     *
     */
    static public function tracker()
    {
        if (self::isSandboxMode()) {
            return '<script src="' . self::config()['scripts']['sandbox'] . '"></script>';
        }

        return '<script src="' . self::config()['scripts']['production'] . '"></script>';
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
}