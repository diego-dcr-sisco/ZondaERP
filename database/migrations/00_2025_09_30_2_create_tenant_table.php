<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tenant', function (Blueprint $table) {
            $table->id();
            $table->string('company_name');
            $table->string('slug')->unique();
            $table->boolean('is_active')->default(true);
            $table->foreignId('plan_id')->nullable()->constrained()->onDelete('set null');
            $table->integer('limit_users')->default(1); // NUEVO: Límite de usuarios permitidos según el plan
            $table->timestamp('subscription_start')->nullable();
            $table->timestamp('subscription_end')->nullable();
            $table->string('path');
            $table->integer('users_amount')->default(0); // Campo existente: cantidad actual de usuarios
            $table->timestamps();

            $table->softDeletes();
            
            // NUEVO: Índices para mejorar rendimiento en búsquedas comunes
            $table->index('is_active');
            $table->index('subscription_end');
            $table->index(['is_active', 'subscription_end']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tenant'); 
    }
};