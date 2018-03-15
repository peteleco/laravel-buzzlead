<?php

namespace Peteleco\Buzzlead;

use Illuminate\Database\Eloquent\Model;

/**
 * Class BuzzleadTracker
 * Este modelo será utilizado para trackear o pedido
 * com o voucher no buzzlead, pois irá funcionar em duas etapas
 * 1 - Pedido realizado
 * 2 - Confirmação do pedido (Quando o pagamento for aprovado)
 *
 * @package Peteleco\Buzzlead
 */
class BuzzleadTracker extends Model
{

    protected $casts = [
        'dispatched' => 'booleand',
        'confirmed'  => 'booleand'
    ];

    protected $dates = [
        'confirmed_at'
    ];

}