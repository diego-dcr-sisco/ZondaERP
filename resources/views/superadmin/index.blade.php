@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="row justify-content-center align-items-center min-vh-100">
        <div class="col-md-6 col-lg-4">
            <div class="card shadow-lg border-0">
                <div class="card-header  text-white text-center py-3" style="background-color:#212529">
                    <h4 class="mb-0">
                        <i class="bi bi-person-badge me-2"></i>Dashboard Superadmin
                    </h4>
                </div>
                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <i class="bi bi-building display-4 " style ="color:#212529"></i>
                        <p class="text-muted mt-2">Selecciona una suscripción para gestionar</p>
                    </div>
                    
                    <form action="{{ route('superadmin.switch-tenant') }}" method="POST" id="tenantSwitchForm">
                        @csrf
                        <div class="mb-3">
                            <label for="tenant_select" class="form-label fw-bold">Seleccionar Suscripción</label>
                            <select class="form-select @error('tenant_id') is-invalid @enderror" 
                                    id="tenant_select" 
                                    name="tenant_id">
                                <option value="" selected disabled>-- Selecciona una suscripción --</option>
                                @foreach($tenants as $tenant)
                                    <option value="{{ $tenant->id }}" 
                                            {{ old('tenant_id') == $tenant->id ? 'selected' : '' }}>
                                        {{ $tenant->company_name }} 
                                        @if($tenant->slug)
                                            ({{ $tenant->slug }})
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('tenant_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn w-100 py-2 text-white" style="background-color:#212529">
                            <i class="bi bi-box-arrow-in-right me-2"></i>Acceder a la Suscripción
                        </button>
                    </form>
                </div>
                <div class="card-footer bg-light text-center py-3">
                    <small class="text-muted">
                        <i class="bi bi-info-circle me-1"></i>
                        Superadmin - Acceso a todas las suscripciones
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.min-vh-100 {
    min-height: 80vh;
}
.card {
    border-radius: 15px;
}
.card-header {
    border-radius: 15px 15px 0 0 !important;
}
.form-select {
    border-radius: 8px;
    padding: 12px;
    border: 2px solid #e9ecef;
    transition: all 0.3s ease;
}
.form-select:focus {
    border-color: #212529;
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
}
.btn-primary {
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.3s ease;
}
.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(13, 110, 253, 0.3);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('tenantSwitchForm');
    const select = document.getElementById('tenant_select');
    
    // Validación del formulario
    form.addEventListener('submit', function(e) {
        if (!select.value) {
            e.preventDefault();
            alert('Por favor, selecciona un tenant');
            select.focus();
        }
    });
});
</script>
@endsection