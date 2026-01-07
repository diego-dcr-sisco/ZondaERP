<?php

namespace App\Services;

use App\Models\Tenant;
use App\Tenancy\TenantManager;
use Illuminate\Support\Facades\Cache;

class ConfigurationService
{
    protected $tenant;
    
    public function __construct()
    {
        $this->tenant = TenantManager::getCurrentTenant();
    }
    
    /**
     * Obtener configuración del Tenant para Facturama
     */
    public function getFacturamaConfig(): array
    {
            return 
                $tenantData->$this->getTenantFacturamaData();   
    }
    
    /**
     * Obtener datos del tenant para Facturama
     */
    private function getTenantFacturamaData(): array
    {
        if (!$this->tenant) {
            return [];
        }
            
        return [
            'rfc' => $this->tenant->RFC,
            'business_name' => $this->tenant->fiscal_name,
            'fiscal_regime' => $this->tenant->fiscal_regime,
            'zip_code' => $this->tenant->zip_code,
            'issuance_place' => $this->tenant->issuance_place,
            'validated_at' => $this->tenant->validated_at,
            'sat_cert_password' => $this->tenant->sat_cert_password,
            
        ];
    }
    
    
}