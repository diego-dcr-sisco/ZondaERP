<style>
    .navbar-item {
        color: white;
        text-decoration: none;
        background-color: transparent;
        /* "none" no es válido, usa "transparent" */
        transition: all 0.3s ease;
        /* Suaviza la transición */
        padding: 8px 16px;
        /* Añade espacio interno */
        display: block;
        /* Mejor comportamiento en elementos <a> */

    }

    .navbar-item:hover {
        color: white;
        background-color: #5d6d7e;
        transform: translateX(4px);
        border-radius: 0 5px 5px 0;
    }
</style>

<ul class="nav flex-column">
    @isset($navigation)
        @foreach ($navigation as $key_nav => $route_nav)
            @if(tenant_can_any(['write_branch', 'write_calendary', 'write_customer', 'write_lot', 'write_order', 'write_pest',
                'write_product', 'write_point', 'write_service', 'write_user', 'write_warehouse', 'write_system_client',
                'read_system_client', 'show_matrix', 'show_sedes', 'handle_crm', 'handle_tracking', 'handle_quotes',
                'handle_planning', 'handle_contracts', 'handle_control_points', 'handle_floorplans', 'handle_quality',
                'handle_report_appearance', 'show_quality_analytics', 'handle_invoice', 'handle_client_system', 'handle_rh',
                'handle_files_employees', 'handle_stock', 'handle_product_technical_details', 'assing_technician',
                'generate_voucher_stock', 'show_stock_alerts', 'handle_customer_zones']))
                <li class="nav-item">
                    <a class="nav-link navbar-item" href="{{ $route_nav }}">{{ $key_nav }}</a>
                </li>
            @endif
        @endforeach
    @endisset
</ul>
