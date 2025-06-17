<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('fut_historial_pagos', function (Blueprint $table) {
            $table->id();
            $table->date('fecha');
            $table->string('creado_desde')->nullable();
            $table->string('numero_documento')->nullable();
            $table->string('nombre')->nullable();
            $table->string('cuenta')->nullable();
            $table->text('nota')->nullable();
            $table->decimal('importe', 15, 2);
            $table->string('estado')->nullable();
            $table->string('folio_SAT_1')->nullable();
            $table->string('rfc_1')->nullable();
            $table->string('forma_pago')->nullable();
            $table->string('metodo_pago')->nullable();
            $table->string('uso_del_cfdi')->nullable();
            $table->string('creado_por')->nullable();
            $table->string('representante_ventas')->nullable();
            $table->string('metodo_pago_2')->nullable();
            $table->string('rfc_2')->nullable();
            $table->string('uso_de_cfdi_para_pago')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fut_histroial_pagos');
    }
};
