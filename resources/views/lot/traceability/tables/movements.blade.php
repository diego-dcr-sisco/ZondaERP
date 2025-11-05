<div class="m-3">
               <table class="table table-bordered table-hover table-striped table-sm align-middle caption-top">
    <thead>
        <tr>
            <th scope="col" class="fw-bold">Mov.Id</th>
            <th scope="col" class="fw-bold">Almacen origen</th>
            <th scope="col" class="fw-bold">Almacen destino</th>
            <th scope="col" class="fw-bold">Productos y movimientos</th>
            <th scope="col" class="fw-bold">Observaciones</th>
            <th scope="col" class="fw-bold">Fecha</th>
            <th scope="col" class="fw-bold">Usuario</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($movements as $movement)
            <tr>
                <th scope="row" class="text-center">{{ $movement->id }}</th>
                <td class="{{ $movement->warehouse_id ? 'text-primary fw-bold' : '' }}">
                    {{ $movement->warehouse->name ?? '-' }}</td>
                <td class="{{ $movement->destination_warehouse_id ? 'text-primary fw-bold' : '' }}">
                    {{ $movement->destinationWarehouse->name ?? '-' }}</td>
                <td class="p-0">
                    <table class="table m-0  table-sm table-inner-transparent">

                        <thead>
                            <tr>
                                <th class="fw-bold" scope="col">Producto</th>
                                <th class="fw-bold" scope="col">Tipo Movimiento</th>
                                <th class="fw-bold" scope="col">Cantidad</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($movement->movementProducts as $mp)
                                @if($mp->lot && $mp->lot->id == $lot->id)
                                    <tr>
                                        <td>{{ $mp->product->name ?? '-' }}</td>
                                        <td class="{{ $movement->movementType->type == 'in' ? 'text-success' : 'text-danger' }} fw-bold">
                                            {{ $movement->movementType->name ?? '-' }}</td>
                                        <td class="{{ $movement->movementType->type == 'in' ? 'text-success' : 'text-danger' }} fw-bold">
                                            {{ $mp->amount }}</td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </td>
                <td>{{ $movement->observations ?? '-' }}</td>
                <td>
                    {{ \Carbon\Carbon::parse($movement->date)->format('d/m/Y') }}<br>
                    <small class="text-muted">{{ $movement->time }}</small>
                </td>
                <td>{{ $movement->user->name ?? '-' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
</div>

 <style>
        
        .table-inner-transparent {
            background-color: transparent !important;
        }
        .table-inner-transparent thead tr,
        .table-inner-transparent tbody tr,
        .table-inner-transparent th,
        .table-inner-transparent td {
            background-color: transparent !important;
            border-color: rgba(0, 0, 0, 0.1) !important;
        }
        .table-inner-transparent thead th {
            border-bottom-width: 2px;
            font-weight: bold;
        }
    </style>