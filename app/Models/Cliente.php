<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cliente extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'nombre',
        'numero_de_cuenta',
    ];

}
