
        <table class="table table-bordered table-striped table-sm">
            <thead>
                <tr>
                    <th scope="col" class="fw-bold">#</th>
                    <th scope="col" class="fw-bold">Orden</th>
                    <th scope="col" class="fw-bold">Cliente</th>
                    <th scope="col" class="fw-bold">Almacén</th>
                    <th scope="col" class="fw-bold">Usuario</th>
                    <th scope="col" class="fw-bold">Cantidad</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orders as $index => $order)
                    <tr>
                        <th scope="row">{{ $index + 1 }}</th>
                        <td scope="row">{{ $order->order->folio ?? 'N/A' }}</td>
                        <td scope="row">{{ $order->order->customer->name ?? 'N/A' }}</td>
                        <td scope="row">{{ $order->warehouse->name ?? '-' }}</td>
                        <td scope="row">{{ $order->user->name ?? '-' }}</td>
                        <td scope="row">{{ $order->amount ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
