<?php


namespace App\Tenancy;

use App\Models\Tenant;

class TenantManager
{
    protected static $currentTenant;

    public static function setCurrentTenant(?Tenant $tenant): void
    {
        
        self::$currentTenant = $tenant;
    }

    public static function getCurrentTenant(): ?Tenant
    {
    
        return self::$currentTenant;
    }

    public static function getCurrentTenantId(): ?int
    {
        return self::$currentTenant ? self::$currentTenant->id : null;
    }

    public static function checkCurrent(): bool
    {
        return !is_null(self::$currentTenant);
    }


     public static function getSatConfiguration(): ?array
    {
        $currentTenant = self::getCurrentTenant();
        
        if (!$currentTenant) {
            return null;
        }

        return [
            'rfc' => $currentTenant->RFC,
            'business_name' => $currentTenant->fiscal_name,
            'tax_regime' => $currentTenant->fiscal_regime,
            'zip_code' => $currentTenant->zip_code,
            'phone' => $currentTenant->phone,
            'license_number' => $currentTenant->license_number,
            'employer_registration' => $currentTenant-> employer_registration,
            'address' => $currentTenant->issuance_place,
            'sat_cert_password' => $currentTenant->sat_cert_password,
        ];
    }
}