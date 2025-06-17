<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pagos extends Model
{
    protected $table = 'pagos';
    protected $fillable = [
        'fecha',
        'concepto_referencia',
        'abonos',
        'cliente',
        'pago',
        'forma_pago',
        'banco',
    ];

    use HasFactory;
}
