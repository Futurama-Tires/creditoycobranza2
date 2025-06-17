<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conciliacion_pagos_factura extends Model
{
    use HasFactory;

    protected $fillable = ['nombre_factura', 'pue_or_ppd'];

}
