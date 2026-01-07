@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4 py-3">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h3 text-gray-800">Incidencia de Plagas</h1>
                <div id="chartStatus" class="badge badge-info">Cargando gráfica...</div>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-body">
                    <form method="GET" action="{{ route('quality.pestIncidents') }}" id="filterForm">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="yearFilter">Año</label>
                                    <select class="form-control" id="yearFilter" name="year">
                                        <option value="">Seleccionar año</option>
                                        @foreach($years as $yearOption)
                                            <option value="{{ $yearOption }}" {{ request('year') == $yearOption ? 'selected' : '' }}>
                                                {{ $yearOption }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">                    
                                    <div class="form-check mb-2">
                                        <input class="form-check-input pest-checkbox-all" type="checkbox" 
                                            id="pestAll" value="all"
                                            name="pests_all"
                                            {{ in_array('all', (array)request('pests', [])) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-bold" for="pestAll">
                                            Todas las plagas
                                        </label>
                                    </div>
                                    
                                    <!-- Checkbox para cada plaga -->
                                    <div class="border rounded p-3" style="max-height: 100px; overflow-y: auto;">
                                        @foreach($pests as $id => $name)
                                        <div class="form-check mb-2">
                                            <input class="form-check-input pest-checkbox" type="checkbox" 
                                                name="pests[]"
                                                value="{{ $id }}" 
                                                id="pest{{ $id }}"
                                                {{ in_array($id, (array)request('pests', [])) ? 'checked' : '' }}
                                                {{ in_array('all', (array)request('pests', [])) ? 'disabled' : '' }}>
                                            <label class="form-check-label" for="pest{{ $id }}">
                                                {{ $name }}
                                            </label>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary" id="applyFilters">
                                    <i class="fas fa-filter"></i> Filtrar
                                </button>
                           
                                <a href="{{ route('quality.pestIncidents') }}" class="btn btn-secondary">
                                    <i class="fas fa-redo"></i> Limpiar
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráfica -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h4 class="m-0 fw-bold text-primary">Gráfica Anual de Incidencias</h4>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="plagueChart"></canvas>
                    </div>
                    <div id="chartError" class="alert alert-danger mt-3" style="display: none;">
                        Error al cargar la gráfica. Verifica la consola para más detalles.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Variable global para la gráfica
let plagueChart;

document.addEventListener('DOMContentLoaded', function() {
    // Inicializar la gráfica
    initializeChart(@json($chartData));
    
    // Inicializar el manejo de checkboxes
    initializePestCheckboxes();
    
    // Ocultar mensaje de carga
    document.getElementById('chartStatus').textContent = 'Gráfica cargada';
    document.getElementById('chartStatus').className = 'badge badge-success';
});

function initializeChart(chartData) {
    const ctx = document.getElementById('plagueChart').getContext('2d');
    
    if (!chartData || !chartData.labels || !chartData.datasets) {
        console.error('Datos de gráfica inválidos:', chartData);
        showChartError('Error en los datos de la gráfica');
        return;
    }
    
    try {
        plagueChart = new Chart(ctx, {
            type: 'line',
            data: chartData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                        /*legend: { display: false }*/
                        legend: true
                    },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Cantidad Total'
                        },
                        ticks: {
                            precision: 0 
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Meses'
                        }
                    }
                }
            }
        });
        
        console.log('Gráfica inicializada correctamente');
        hideChartError();
        
    } catch (error) {
        console.error('Error al inicializar la gráfica:', error);
        showChartError('Error al crear la gráfica: ' + error.message);
    }
}

function initializePestCheckboxes() {
    const allCheckbox = document.getElementById('pestAll');
    const pestCheckboxes = document.querySelectorAll('.pest-checkbox');
    
    if (!allCheckbox) {
        console.error('Checkbox "pestAll" no encontrada');
        return;
    }
    
    console.log('Inicializando checkboxes. Total encontrados:', pestCheckboxes.length);
    
    // Estado inicial
    if (allCheckbox.checked) {
        pestCheckboxes.forEach(checkbox => {
            checkbox.disabled = true;
            checkbox.checked = false;
        });
    }
    
    // Evento para "Todas las plagas"
    allCheckbox.addEventListener('change', function() {
        
        if (this.checked) {
            // Deshabilitar y desmarcar todos los checkboxes individuales
            pestCheckboxes.forEach(checkbox => {
                checkbox.disabled = true;
                checkbox.checked = false;
            });
        } else {
            // Habilitar todos los checkboxes
            pestCheckboxes.forEach(checkbox => {
                checkbox.disabled = false;
            });
        }
    });
    
    // Eventos para checkboxes individuales
    pestCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            
            if (this.checked) {
                // Si se selecciona uno, desmarcar "Todas"
                allCheckbox.checked = false;
                
                // Asegurarse de que todos estén habilitados
                pestCheckboxes.forEach(cb => {
                    cb.disabled = false;
                });
            }
        });
    });
}

// Función para preparar el formulario antes de enviar
function prepareFormSubmission() {
    const allCheckbox = document.getElementById('pestAll');
    const pestCheckboxes = document.querySelectorAll('.pest-checkbox');
    
    if (allCheckbox && allCheckbox.checked) {
        // Si "Todas" está seleccionada, deshabilitar los checkboxes individuales
        // para que no se envíen al servidor
        pestCheckboxes.forEach(checkbox => {
            checkbox.disabled = true;
            checkbox.removeAttribute('name'); // No enviar este campo
        });
    } else {
        // Si no está seleccionada "Todas", asegurarse de que los checkboxes
        // tengan el atributo name para que se envíen
        pestCheckboxes.forEach(checkbox => {
            checkbox.disabled = false;
            checkbox.setAttribute('name', 'pests[]');
        });
    }
    
    return true;
}

// Agregar evento al formulario para preparar los datos antes de enviar
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('filterForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            prepareFormSubmission();
            const formData = new FormData(this);
        });
    }
});

function showChartError(message) {
    const errorElement = document.getElementById('chartError');
    if (errorElement) {
        errorElement.textContent = message;
        errorElement.style.display = 'block';
    }
}

function hideChartError() {
    const errorElement = document.getElementById('chartError');
    if (errorElement) {
        errorElement.style.display = 'none';
    }
}
</script>

<style>
    .chart-area {
        position: relative;
        height: 400px;
        width: 100%;
        min-height: 400px;
    }

    #plagueChart {
        width: 100% !important;
        height: 400px !important;
    }

    .card {
        border: none;
        border-radius: 0.5rem;
    }

    .btn {
        border-radius: 0.35rem;
    }

    .badge {
        font-size: 0.85em;
        padding: 0.4em 0.6em;
    }

    .form-check {
        margin-bottom: 0.3rem;
    }

    .form-check-input {
        margin-top: 0.25rem;
    }

    .form-check-label {
        margin-left: 0.5rem;
    }
</style>