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
        Schema::create('conciliacion_pagos_info_primarias', function (Blueprint $table) {
            $table->id();
            $table->string('numero_documento')->nullable();
            $table->string('forma_pago')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conciliacion_pagos_info_primarias');
    }
};
