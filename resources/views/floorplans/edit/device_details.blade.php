@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="d-flex align-items-center border-bottom ps-4 p-2 mb-4">
        <a href="{{ route('floorplan.devices', ['id' => $floorplan->id, 'version' => $version]) }}" 
           class="text-decoration-none pe-3">
            <i class="bi bi-arrow-left fs-4"></i>
        </a>
        <span class="text-black fw-bold fs-4">
            Detalles del Dispositivo - <span class=" fs-4 fw-bold bg-warning p-1 rounded">{{ $device->code ?? 'N/A' }}</span>
        </span>
    </div>

    <div class="row">
        <!-- Información del Plano -->
        <div class="col-md-12">
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-3 fw-bold">
                        <i class="bi bi-map me-2"></i>Información del Plano
                    </h5>
                    <div class="row">
                        <div class="col-3 mb-3">
                            <label class="fw-bold text-muted">Plano:</label>
                            <p class="mb-0">{{ $floorplan->filename ?? 'N/A' }}</p>
                        </div>
                        <div class="col-3 mb-3">
                            <label class="fw-bold text-muted">Versión:</label>
                            <p class="mb-0 badge bg-secondary">{{ $version }}</p>
                        </div>
                        <div class="col-3 mb-3">
                            <label class="fw-bold text-muted">Cliente:</label>
                            <p class="mb-0">{{ $floorplan->customer->name ?? 'N/A' }}</p>
                        </div>
                        <div class="col-3 mb-3">
                            <label class="fw-bold text-muted">Servicio:</label>
                            <p class="mb-0">{{ $floorplan->service->name ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>      
        <!-- Información del Dispositivo -->
        <div class="col-md-12">
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-3 fw-bold">
                        <i class="bi bi-info-circle me-2"></i>Información del Dispositivo
                    </h5>
                    
                    <div class="d-flex flex-column flex-lg-row gap-3">
                        <div class="flex-grow-1">
                            <div class="row">
                                <div class="col-lg-3 col-md-4 col-6 mb-3">
                                    <label class="fw-bold text-muted">Código:</label>
                                    <p class="mb-0 fs-5 text-primary">{{ $device->code ?? 'N/A' }}</p>
                                </div>
                                <div class="col-lg-3 col-md-4 col-6 mb-3">
                                    <label class="fw-bold text-muted">Número:</label>
                                    <p class="mb-0 fs-5">{{ $device->nplan ?? 'N/A' }}</p>
                                </div>
                                <div class="col-lg-3 col-md-4 col-6 mb-3">
                                    <label class="fw-bold text-muted">Punto de Control:</label>
                                    <p class="mb-0">{{ $controlPoint->name ?? 'N/A' }}</p>
                                </div>
                                <div class="col-lg-3 col-md-4 col-6 mb-3">
                                    <label class="fw-bold text-muted">Área:</label>
                                    <p class="mb-0">{{ $applicationArea->name ?? 'N/A' }}</p>
                                </div>
                                <div class="col-lg-3 col-md-4 col-6 mb-3">
                                    <label class="fw-bold text-muted">Producto:</label>
                                    <p class="mb-0">{{ $product->name ?? 'Sin producto' }}</p>
                                </div>
                                <div class="col-lg-3 col-md-4 col-6 mb-3">
                                    <label class="fw-bold text-muted">Color:</label>
                                    <div class="d-flex align-items-center">
                                        <div class="color-box me-2" style="width: 20px; height: 20px; background-color: {{ $device->color ?? '#000000' }}; border: 1px solid #ccc;"></div>
                                        <span class="text-uppercase">{{ $device->color ?? 'N/A' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>                       
                        <div class="flex-shrink-0 text-center mt-3 mt-lg-0">
                            <label class="fw-bold text-muted d-block mb-2">Código QR</label>
                            <img src="data:image/png;base64,{{ base64_encode($device->qr) }}" 
                                alt="QR del dispositivo {{ $device->code }}"
                                class="img-fluid border rounded"
                                style="max-width: 120px; height: auto;">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Revisiones -->
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-3 fw-bold">
                        <i class="bi bi-clock-history me-2"></i>Historial de Revisiones
                    </h5>
                    @if($groupedRevisions && $groupedRevisions->count() > 0)
                        <div class="table-responsive">
                            <caption>
                                 <form method="GET" action="{{ route('floorplan.device.details', ['id' => $floorplan->id, 'version' => $version, 'deviceId' => $device->id]) }}"  class="d-inline-flex align-items-center gap-2" id="filterForm">
                                    <span class="text-muted small">Filtrar:</span>
                                    <input type="date" 
                                        name="date" 
                                        class="form-control form-control-sm" 
                                        style="width: 150px;"
                                        value="{{ request('date') }}">
                                    <button type="submit" class="btn btn-success">
                                        <i class="bi bi-search"> Buscar</i>
                                    </button>
                                    @if(request('date'))
                                    <a href="{{ request()->url() }}" class="btn btn-sm btn-outline-secondary">
                                        <i class="bi bi-x-circle"></i> Limpiar
                                    </a>
                                    @endif
                                </form>

                            </caption>
                            <table class="table table-sm table-striped">
                                <thead>
                                    <tr>
                                        <th class="text-center fw-bold">Fecha</th>
                                        <th class="text-center fw-bold">Orden</th>
                                        <th class="text-center fw-bold">Cliente</th>
                                        <th class="text-center fw-bold">Revisión</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($groupedRevisions as $orderId => $incidents)
                                        @php
                                            $firstRevision = $incidents->first();
                                        @endphp
                                        <tr>
                                           
                                            <td style="background-color: #f8f9fa;">
                                                <div class="text-center">
                                                    <div class="fw-bold">{{ $firstRevision->updated_at->format('d/m/Y') }}</div>
                                                    <div class="small text-muted mt-1">{{ $firstRevision->updated_at->format('H:i:s') }}</div>
                                                </div>
                                            </td>
                                            
                                            <td style="background-color: #f8f9fa;">
                                                <div class="text-center">
                                                    <div class="fw-bold text-primary">#{{ $firstRevision->order->folio ?? 'N/A' }}</div>
                                                    <small class="text-muted d-block">ID: {{ $orderId }}</small>
                                                </div>
                                            </td>
                                            
                                            <td style="background-color: #f8f9fa;">
                                                <div class="text-center">
                                                    <div class="fw-bold text-primary">{{ $firstRevision->order->customer->name ?? 'N/A' }}</div>
                                                    @if($firstRevision->order->customer->company_name ?? false)
                                                        <small class="text-muted d-block">{{ $firstRevision->order->customer->company_name }}</small>
                                                    @endif
                                                </div>
                                            </td>
                                            
                                            <td style="background-color: #f8f9fa;">
                                                <div class="text-center">
                                                    <button type="button" 
                                                            class="btn btn-sm btn-outline-primary" 
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#revisionModal{{ $orderId }}"
                                                            title="Ver preguntas">
                                                        <i class="bi bi-eye-fill"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>

                                        <!-- Separador visual -->
                                        <tr>
                                            <td colspan="4" style="height: 8px; background-color: #f5f7fa;"></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                            <caption>
                                 <form method="GET" action="{{ route('floorplan.device.details', ['id' => $floorplan->id, 'version' => $version, 'deviceId' => $device->id]) }}"  class="d-inline-flex align-items-center gap-2" id="filterForm">
                                    <span class="text-muted small">Filtrar:</span>
                                    <input type="date" 
                                        name="date" 
                                        class="form-control form-control-sm" 
                                        style="width: 150px;"
                                        value="{{ request('date') }}">
                                    <button type="submit" class="btn btn-success">
                                        <i class="bi bi-search"> Buscar</i>
                                    </button>
                                    @if(request('date'))
                                    <a href="{{ request()->url() }}" class="btn btn-sm btn-outline-secondary">
                                        <i class="bi bi-x-circle"></i> Limpiar
                                    </a>
                                    @endif
                                </form>

                            </caption>
                        <div class="text-center py-4">
                            <i class="bi bi-inbox display-4 text-muted"></i>
                            <p class="text-muted mt-3">No hay revisiones registradas para este dispositivo</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

   <!-- Gráfica de Incidencia de Plagas Anual -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title mb-4 fw-bold">
                    <i class="bi bi-bug-fill me-2 fw-bold"></i>Incidencia de Plagas {{ $year }}
                </h5>
        <div class="row">
            <div class="col-md-8">
                <div class="chart-container" style="position: relative; height: 400px;">
                    <div id="chartContainer" style="width: 100%; height: 100%;">
                        <canvas id="pestChart" style="width: 100% !important; height: 100% !important;"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-3 bg-light rounded">
                    
                    <!-- Selector de año -->
                    <div class="p-3 bg-light rounded">
                    <h6 class="fw-bold">Resumen <span id="yearTitle">{{ $pestData['year'] ?? date('Y') }}</span></h6>
                    
                    <!-- Selector de año -->
                    <div class="mb-3">
                        <form method="GET" action="{{ route('floorplan.device.details', ['id' => $floorplan->id, 'version' => $version, 'deviceId' => $device->id]) }}" id="yearForm">
                            @csrf
                            <label class="form-label small">Seleccionar año:</label>
                            <select class="form-select form-select-sm" id="yearSelector" name="year" onchange="this.form.submit()">
                                @for($y = date('Y'); $y >= 2024; $y--)
                                    <option value="{{ $y }}" {{ ($pestData['year'] ?? date('Y')) == $y ? 'selected' : '' }}>
                                        {{ $y }}
                                    </option>
                                @endfor
                            </select>
                        </form>
                    </div>

                    <!-- Estadísticas -->
                    <div class="row small">
                        <div class="col-6 mb-2">
                            <div class="text-muted">Total anual</div>
                            <div class="fw-bold text-primary fs-5" id="totalAnnual">
                                @php
                                    $total = array_sum(array_column($pestData['pests'], 'total'));
                                    echo number_format($total);
                                @endphp
                            </div>
                        </div>
                        <div class="col-6 mb-2">
                            <div class="text-muted">Tipos de plagas</div>
                            <div class="fw-bold" id="pestTypes">
                                {{ count($pestData['pests']) }}
                            </div>
                        </div>
                    </div>
                    
                    <!-- Leyenda -->
                    <div class="mt-3">
                        <h6 class="fw-bold small">Plagas</h6>
                        <div id="legendContainer" style="max-height: 150px; overflow-y: auto;">
                            @foreach($pestData['pests'] as $pest)
                            <div class="d-flex align-items-center mb-1">
                                <span class=" me-2" style="background-color: {{ $pest['color'] }}; width: 12px; height: 12px;"></span>
                                <small class="flex-grow-1">{{ $pest['pest_name'] }}</small>
                                <small class="text-muted">{{ number_format($pest['total']) }}</small>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
</div>

@foreach($groupedRevisions as $orderId => $incidents)
<div class="modal fade" id="revisionModal{{ $orderId }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h5 class="modal-title text-white">
                    Detalles de la Revisión
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            
            <div class="modal-body">
                <div class="card border">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-striped table-sm">
                                <thead>
                                    <tr class="border-bottom">
                                        <th class="text-center fw-bold" style="width: 50px;">No</th>
                                        <th class="fw-bold">Pregunta</th>
                                        <th class="fw-bold">Respuesta</th>
                                    </tr>
                                </thead>`
                                <tbody>
                                    @foreach($incidents as $index => $revision)
                                    <tr class="border-bottom">
                                        <td class="text-center align-middle fw-bold">
                                            {{ $index + 1  . '.-' }}
                                        </td>
                                        <td class="align-middle py-3">
                                            {{ $revision->question->question ?? 'N/A' }}
                                        </td>
                                        <td class="align-middle py-3">
                                            <div class="p-2 bg-light rounded">
                                                {{ $revision->answer }}
                                            </div>    
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">
                     Cerrar
                </button>
            </div>
        </div>
    </div>
</div>
@endforeach

<script>
    document.addEventListener('DOMContentLoaded', function() {
        let pestChart = null;
        
        // inicializar la gráfica 
        function initializeChart(pestData) {
            const ctx = document.getElementById('pestChart').getContext('2d');
            
            // Destruir gráfico anterior si existe
            if (pestChart !== null) {
                pestChart.destroy();
            }
            
            // Crear nuevo gráfico tipo línea
            pestChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: pestData.months,
                    datasets: pestData.pests.map(pest => ({
                        label: pest.pest_name,
                        data: pest.data,
                        backgroundColor: pest.color + '20',
                        borderColor: pest.color,
                        borderWidth: 2,
                        fill: false,
                        tension: 0.3,
                        pointBackgroundColor: pest.color,
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6
                    }))
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        /*legend: { display: false }*/
                        legend: false
                    },
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: 'Meses del Año'
                            }
                        },
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Cantidad'
                            },
                            min: 0,
                            ticks: {
                                stepSize: 10
                            }
                        }
                    }
                }
            });
        }
        
        function showLoadingMessage() {
            const chartContainer = document.getElementById('chartContainer');
            if (chartContainer) {
                chartContainer.innerHTML = `
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Cargando...</span>
                        </div>
                        <p class="mt-2 text-muted">Cargando datos...</p>
                    </div>
                `;
            }
        }
        
        // Inicializar con datos del año actual
        const pestData = @json($pestData);
        const currentYear = '{{ $year ?? date("Y") }}';
        
        // Verificar si hay datos
        const hasData = pestData && 
                           pestData.pests;
        
        if (hasData) {
            initializeChart(pestData);
        }
        
        document.getElementById('yearForm')?.addEventListener('submit', showLoadingMessage);
 
        document.getElementById('yearSelector')?.addEventListener('change', function() {
            if (!this.hasAttribute('onchange')) {
                showLoadingMessage();
                this.form.submit();
            }
        });
        
    });
</script>
@endsection