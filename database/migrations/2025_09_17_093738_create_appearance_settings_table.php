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
        Schema::create('appearance_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->nullable()->constrained('tenant')->onDelete('cascade');
            $table->string('primary_color')->default('#182A41'); // AJUSTE: Color primario por defecto cambiado a #182A41
            $table->string('secondary_color')->default('#b0bec5'); // Color secundario mantiene valor por defecto
            $table->string('logo_path')->nullable();
            $table->string('watermark_path')->nullable();
            $table->double('watermark_opacity')->default(0.1); // AJUSTE: Valor por defecto establecido en 0.1
            $table->text('custom_css')->nullable(); // NUEVO: Campo para CSS personalizado
            $table->timestamps();

            // NUEVO: Índice único para asegurar que cada tenant tenga solo una configuración de apariencia
            $table->unique('tenant_id');

            // NUEVO: Índices para mejorar rendimiento
            $table->index('primary_color');
            $table->index('secondary_color');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appearance_settings');
    }
};