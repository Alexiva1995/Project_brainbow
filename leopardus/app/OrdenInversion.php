<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrdenInversion extends Model
{
    protected $table = 'orden_inversiones';

    protected $fillable = [
        'invertido', 'concepto', 'iduser', 'idtrasancion', 'status',
        'saldo_capital', 'rendimiento'
    ];
}
