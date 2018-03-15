<?php

namespace Peteleco\Buzzlead\Model;

use Illuminate\Database\Eloquent\Model;

class OrderForm extends Model
{

    /**
     * @var bool
     */
    protected $table = false;

    /**
     * @var array
     */
    protected $fillable = [
        'codigo',
        'total',
        'pedido',
        'nome',
        'email'
    ];

}