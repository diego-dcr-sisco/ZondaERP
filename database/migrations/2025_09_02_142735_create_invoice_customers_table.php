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
        Schema::create('invoice_customers', function (Blueprint $table) {
            $table->id();
            $table->enum('taxpayer_type', ['physical', 'moral']);
            $table->string('comercial_name');
            $table->string('social_reason');
            $table->string('rfc');
            $table->string('phone');
            $table->string('email');

            $table->string('tax_system');
            $table->string('cfdi_usage');

            $table->string('zip_code');
            $table->string('state');
            $table->string('city');
            $table->string('address');

            $table->float('credit_limit')->nullable();
            $table->integer('credit_days')->nullable();

            $table->string('payment_method')->default(0);
            $table->string('payment_form')->default(0);

            $table->enum('status', ['facturable', 'moroso', 'no_facturable'])->default('no_facturable');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_tax_data');
    }
};
