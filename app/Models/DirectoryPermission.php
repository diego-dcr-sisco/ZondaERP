<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Tenancy\TenantScoped;

class DirectoryPermission extends Model
{
    use HasFactory, TenantScoped;

    protected $table = 'directory_permissions';

    protected $fillable = [
        'id',
        'tenant_id',
        'user_id',
        'path',
        'created_at',
        'updated_at'
    ];
}
