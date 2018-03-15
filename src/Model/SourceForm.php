<?php namespace Peteleco\Buzzlead\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string email
 */
class SourceForm extends Model
{

    /**
     * @var bool
     */
    protected $table = false;

    protected $fillable = [
        'name',
        'email'
    ];
}