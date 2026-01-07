<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Plan;

class Tenant extends Model
{
    protected $table = 'tenant';
    protected $fillable = [
        'company_name',
        'slug',
        'is_active',
        'plan_id',
        'subscription_start',
        'subscription_end',
        'company_name',
        'path',
        // Campos de información fiscal para facturación
        'fiscal_name', // razón social (TaxName)
        'fiscal_regime', // régimen fiscal (sólo código)
        'RFC',
        'issuance_place', // lugar expedición
        'zip_code',
        'validated_at', // validado
        'sat_cert_password', // Contraseña de la llave private SAT
        'phone',
        'license_number',
        'employer_registration',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'subscription_start' => 'date',
        'subscription_end' => 'date',
        'validate_at' => 'datetime'
    ];

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function users()
    {
        return $this->hasMany(User::class, 'tenant_id');
    }
}
