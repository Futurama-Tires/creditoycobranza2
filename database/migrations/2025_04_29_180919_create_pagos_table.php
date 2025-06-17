<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// CONCILIACION BANCARIA

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pagos', function (Blueprint $table) {
            $table->id();
            $table->date('fecha');
            $table->string('concepto_referencia');
            $table->decimal('abonos', 15, 2);
            $table->string('cliente');
            $table->string('pago')->nullable();
            $table->string('forma_pago');
            $table->string('banco');
            $table->date('fidp')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pagos');
    }
};
