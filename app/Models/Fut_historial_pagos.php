<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fut_historial_pagos extends Model
{
    use HasFactory;
    protected $table = 'fut_historial_pagos';

    protected $fillable = [
        'fecha',
        'creado_desde',
        'numero_documento',
        'nombre',
        'cuenta',
        'nota',
        'importe',
        'estado',
        'folio_SAT_1',
        'rfc_1',
        'forma_pago',
        'metodo_pago',
        'uso_del_cfdi',
        'representante_ventas',
        'metodo_pago_2',
        'rfc_2',
        'uso_de_cfdi_para_pago',
    ];

}
