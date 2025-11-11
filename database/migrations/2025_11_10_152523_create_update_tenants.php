<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Actualizar tabla tenant - agregar campo limit_users
        if (Schema::hasTable('tenant') && !Schema::hasColumn('tenant', 'limit_users')) {
            Schema::table('tenant', function (Blueprint $table) {
                $table->integer('limit_users')->default(1)->after('plan_id');
            });
        }

        // Actualizar tabla appearance_settings - múltiples cambios
        if (Schema::hasTable('appearance_settings')) {
            Schema::table('appearance_settings', function (Blueprint $table) {
                // Cambiar valor por defecto de primary_color si existe
                if (Schema::hasColumn('appearance_settings', 'primary_color')) {
                    $table->string('primary_color')->default('#182A41')->change();
                }
                
                // Establecer valor por defecto para watermark_opacity si existe
                if (Schema::hasColumn('appearance_settings', 'watermark_opacity')) {
                    $table->double('watermark_opacity')->default(0.1)->change();
                }
                
                // Agregar nuevo campo custom_css si no existe
                if (!Schema::hasColumn('appearance_settings', 'custom_css')) {
                    $table->text('custom_css')->nullable()->after('watermark_opacity');
                }
                
                // Agregar índice único a tenant_id si no existe
                if (Schema::hasColumn('appearance_settings', 'tenant_id') && !$this->hasUniqueIndex('appearance_settings', 'tenant_id')) {
                    $table->unique('tenant_id');
                }
            });
        }

        // Agregar índices a la tabla tenant para mejorar rendimiento
        if (Schema::hasTable('tenant')) {
            Schema::table('tenant', function (Blueprint $table) {
                if (!Schema::hasIndex('tenant', 'tenant_is_active_index')) {
                    $table->index('is_active', 'tenant_is_active_index');
                }
                
                if (!Schema::hasIndex('tenant', 'tenant_subscription_end_index')) {
                    $table->index('subscription_end', 'tenant_subscription_end_index');
                }
                
                if (!Schema::hasIndex('tenant', 'tenant_active_subscription_index')) {
                    $table->index(['is_active', 'subscription_end'], 'tenant_active_subscription_index');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revertir cambios en tabla tenant
        if (Schema::hasTable('tenant') && Schema::hasColumn('tenant', 'limit_users')) {
            Schema::table('tenant', function (Blueprint $table) {
                $table->dropColumn('limit_users');
            });
        }

        // Revertir cambios en tabla appearance_settings
        if (Schema::hasTable('appearance_settings')) {
            Schema::table('appearance_settings', function (Blueprint $table) {
                // Revertir valor por defecto de primary_color
                if (Schema::hasColumn('appearance_settings', 'primary_color')) {
                    $table->string('primary_color')->default('#64b5f6')->change();
                }
                
                // Revertir valor por defecto de watermark_opacity
                if (Schema::hasColumn('appearance_settings', 'watermark_opacity')) {
                    $table->double('watermark_opacity')->default(0)->change();
                }
                
                // Eliminar campo custom_css
                if (Schema::hasColumn('appearance_settings', 'custom_css')) {
                    $table->dropColumn('custom_css');
                }
                
                // Eliminar índice único de tenant_id
                if ($this->hasUniqueIndex('appearance_settings', 'tenant_id')) {
                    $table->dropUnique(['tenant_id']);
                }
            });
        }

        // Eliminar índices de la tabla tenant
        if (Schema::hasTable('tenant')) {
            Schema::table('tenant', function (Blueprint $table) {
                if (Schema::hasIndex('tenant', 'tenant_is_active_index')) {
                    $table->dropIndex('tenant_is_active_index');
                }
                
                if (Schema::hasIndex('tenant', 'tenant_subscription_end_index')) {
                    $table->dropIndex('tenant_subscription_end_index');
                }
                
                if (Schema::hasIndex('tenant', 'tenant_active_subscription_index')) {
                    $table->dropIndex('tenant_active_subscription_index');
                }
            });
        }
    }

    /**
     * Verificar si una columna tiene índice único
     */
    private function hasUniqueIndex(string $table, string $column): bool
    {
        $indexes = Schema::getConnection()
            ->getDoctrineSchemaManager()
            ->listTableIndexes(Schema::getTablePrefix() . $table);

        foreach ($indexes as $index) {
            if ($index->isUnique() && in_array($column, $index->getColumns())) {
                return true;
            }
        }

        return false;
    }
};