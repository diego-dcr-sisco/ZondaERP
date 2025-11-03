<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('payrolls', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->nullable();
            $table->string('folio');
            $table->string('cfdi_type')->default('N');
            $table->string('payment_method')->default('PUE');
            $table->string('expedition_place');
            $table->string('name_id')->default('16');
            
            // Receiver
            $table->string('receiver_rfc');
            $table->string('receiver_name');
            $table->string('receiver_cfdi_use');
            $table->string('receiver_fiscal_regime');
            $table->string('receiver_tax_zip_code');
            
            // Payroll Data
            $table->string('payroll_type'); // O = Ordinaria, E = Extraordinaria
            $table->decimal('daily_salary', 10, 2);
            $table->decimal('base_salary', 10, 2);
            $table->date('payment_date');
            $table->date('initial_payment_date');
            $table->date('final_payment_date');
            $table->integer('days_paid');
            
            // Issuer
            $table->string('employer_registration');
            
            // Employee
            $table->string('employee_curp');
            $table->string('employee_social_security_number');
            $table->integer('position_risk');
            $table->string('contract_type');
            $table->string('regime_type');
            $table->boolean('unionized')->default(false);
            $table->string('type_of_journey');
            $table->string('employee_number');
            $table->string('department')->nullable();
            $table->string('position')->nullable();
            $table->string('frequency_payment');
            $table->string('federal_entity_key');
            $table->decimal('employee_daily_salary', 10, 2);
            $table->date('start_date_labor_relations');
            
            // Totals
            $table->decimal('total_perceptions', 10, 2)->default(0);
            $table->decimal('total_deductions', 10, 2)->default(0);
            $table->decimal('total_other_payments', 10, 2)->default(0);
            $table->decimal('total', 10, 2)->default(0);
            
            // Status
            $table->string('status')->default('draft');
            $table->text('stamping_response')->nullable();
            
            $table->timestamps();
        });

        Schema::create('payroll_perceptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payroll_id')->constrained()->onDelete('cascade');
            $table->string('perception_type');
            $table->string('code');
            $table->string('description');
            $table->decimal('taxed_amount', 10, 2)->default(0);
            $table->decimal('exempt_amount', 10, 2)->default(0);
            $table->timestamps();
        });

        Schema::create('payroll_deductions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payroll_id')->constrained()->onDelete('cascade');
            $table->string('deduction_type');
            $table->string('code');
            $table->string('description');
            $table->decimal('amount', 10, 2);
            $table->timestamps();
        });

        Schema::create('payroll_other_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payroll_id')->constrained()->onDelete('cascade');
            $table->string('other_payment_type');
            $table->string('code');
            $table->string('description');
            $table->decimal('amount', 10, 2);
            $table->decimal('employment_subsidy_amount', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('payroll_other_payments');
        Schema::dropIfExists('payroll_deductions');
        Schema::dropIfExists('payroll_perceptions');
        Schema::dropIfExists('payrolls');
    }
};