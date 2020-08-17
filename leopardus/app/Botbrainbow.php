<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Botbrainbow extends Model
{
    protected $table = 'botbrainbow';

    protected $fillable = [
        'abierto', 'alto', 'bajo', 'cerrado',
        'fecha_numerica', 'post_nega'
    ];
}
