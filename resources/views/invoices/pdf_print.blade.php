<style>
    @page {
        margin: 0.7cm !important;
        size: letter portrait;
    }

    body, html {
        margin: 0 !important;
        padding: 0 !important;
        font-family: 'Helvetica', Arial, sans-serif;
        font-size: 10px !important;
        line-height: 1.2 !important;
        width: 100% !important;
        height: 100% !important;
    }

    .invoice-container {
        width: calc(21.59cm - 1.4cm) !important; 
        min-height: calc(27.94cm - 1.4cm) !important; 
        padding: 0.5cm !important;
        background: white;
        box-sizing: border-box;
        position: relative;
        margin: 0 auto !important; 
        overflow: visible !important;
    }

    /* HEADER */
    .header {
        width: 100% !important;
        margin-bottom: 15px !important;
        padding-bottom: 10px !important;
        border-bottom: 1px solid #000 !important;
        display: table !important;
        table-layout: fixed !important;
    }

    .header-row {
        display: table-row !important;
    }

    .logo-container {
        display: table-cell !important;
        width: 48% !important; 
        vertical-align: top !important;
        text-align: left !important;
        padding-right: 2% !important; 
    }

    .document-info {
        display: table-cell !important;
        width: 50% !important;
        vertical-align: top !important;
        text-align: right !important;
    }

    .company-name {
        font-size: 14px !important;
        font-weight: bold !important;
        margin-bottom: 4px !important;
        line-height: 1.2 !important;
    }

    .document-details {
        font-size: 9px !important;
        line-height: 1.3 !important;
        margin: 0;
        padding: 0;
    }

    .two-columns-container {
        width: 100% !important;
        margin-bottom: 15px !important;
        display: table !important;
        table-layout: fixed !important;
        border-spacing: 10px 0 !important; 
    }

    .two-columns-container > div {
        display: table-cell !important;
        width: 50% !important;
        vertical-align: top !important;
    }

    .section-title {
        font-size: 12px !important;
        font-weight: bold !important;
        margin: 0 0 8px 0 !important;
        padding-bottom: 3px !important;
        border-bottom: 1px solid #ccc !important;
    }

    .info-item {
        margin-bottom: 6px !important;
        font-size: 10px !important;
        line-height: 1.3 !important;
        width: 100% !important;
    }

    .info-label {
        font-weight: bold !important;
        display: inline-block !important;
        width: 85px !important; 
        vertical-align: top !important;
    }

    .info-value {
        display: inline-block !important;
        width: calc(100% - 90px) !important; 
        word-wrap: break-word !important;
        overflow-wrap: break-word !important;
    }

    /* TABLA DE PRODUCTOS */
    .products-table {
        width: 100% !important;
        border-collapse: collapse !important;
        margin: 15px 0 10px 0 !important;
        page-break-inside: avoid !important;
        font-size: 9px !important;
        table-layout: fixed !important;
    }

    .products-table th {
        background-color: #f2f2f2 !important;
        padding: 5px 3px !important; 
        text-align: left !important;
        border: 1px solid #000 !important;
        font-weight: bold !important;
        font-size: 9px !important;
    }

    .products-table td {
        font-size: 9px !important;
        padding: 5px 3px !important; 
        border: 1px solid #000 !important;
        word-wrap: break-word !important;
        overflow-wrap: break-word !important;
        line-height: 1.2 !important;
    }

    /* ANCHOS DE COLUMNAS AJUSTADOS */
    .products-table th:nth-child(1),
    .products-table td:nth-child(1) { 
        width: 6% !important; 
    }
    
    .products-table th:nth-child(2),
    .products-table td:nth-child(2) { 
        width: 9% !important; 
    }
    
    .products-table th:nth-child(3),
    .products-table td:nth-child(3) { 
        width: 45% !important;
    }
    
    .products-table th:nth-child(4),
    .products-table td:nth-child(4) { 
        width: 7% !important; 
    }
    
    .products-table th:nth-child(5),
    .products-table td:nth-child(5) { 
        width: 9% !important; 
    }
    
    .products-table th:nth-child(6),
    .products-table td:nth-child(6) { 
        width: 9% !important; 
    }
    
    .products-table th:nth-child(7),
    .products-table td:nth-child(7) { 
        width: 9% !important; 
    }

    .totals-table {
        width: 30% !important; 
        margin-left: auto !important;
        margin-right: 0 !important; 
        border-collapse: collapse !important;
        margin-top: 10px !important;
        font-size: 10px !important;
    }

    .totals-table td {
        padding: 5px 7px !important;
        border: 1px solid #000 !important;
        font-size: 10px !important;
    }

    .totals-label {
        font-weight: bold !important;
        background-color: #f2f2f2 !important;
    }

    /* FOOTER */
    .footer {
        position: absolute !important;
        bottom: 0.5cm !important;
        left: 0 !important;
        right: 0 !important;
        width: 100% !important;
        text-align: center !important;
        font-size: 8px !important;
        color: #666 !important;
        border-top: 1px solid #ccc !important;
        padding-top: 8px !important;
        page-break-inside: avoid !important;
        line-height: 1.3 !important;
        box-sizing: border-box !important;
    }

    /* LOGO  */
    .logo-container img {
        max-width: 200px !important; 
        max-height: 60px !important;
        height: auto !important;
        margin: 0 0 5px 0 !important;
    }

    .invoice-title {
        color: #8bc34a !important;
        font-weight: bold !important;
        font-size: 14px !important; 
        margin-bottom: 6px !important;
        line-height: 1.2 !important;
    }

    .uuid-text {
        font-size: 7px !important;
        word-break: break-all !important;
        line-height: 1.1 !important;
    }

    .clearfix::after {
        content: "" !important;
        display: table !important;
        clear: both !important;
    }

    .no-break {
        page-break-inside: avoid !important;
    }

    .text-right {
        text-align: right !important;
    }

    .text-center {
        text-align: center !important;
    }

    .text-left {
        text-align: left !important;
    }

    .cfdi-tag {
        display: inline-block;
        background-color: #f2f2f2;
        padding: 2px 5px;
        border-radius: 3px;
        font-size: 9px;
        margin-right: 5px;
        border: 1px solid #ccc;
    }
    
    .footer {
        margin-top: 20px;
        text-align: center;
        font-size: 8px;
        color: #666;
        border-top: 1px solid #000;
        padding-top: 8px;
    }

    .cfdi-tag {
        display: inline-block;
        background-color: #f2f2f2;
        padding: 2px 5px;
        border-radius: 3px;
        font-size: 9px;
        margin-right: 5px;
        border: 1px solid #ccc;
    }

    /* AJUSTES PARA IMPRESIÓN */
    @media print {
        body, html {
            margin: 0 !important;
            padding: 0 !important;
        }
        
        .invoice-container {
            padding: 0.5cm !important;
            margin: 0 auto !important;
            width: calc(100% - 1.4cm) !important; /* 100% menos márgenes */
            max-width: 21.59cm !important;
            height: auto !important;
            min-height: calc(100% - 1.4cm) !important;
            box-shadow: none !important;
        }

        .footer {
            position: fixed !important;
            bottom: 0.7cm !important;
            left: 0.7cm !important;
            right: 0.7cm !important;
            width: calc(100% - 1.4cm) !important;
        }
        
    }
</style>

<div class="invoice-container">
    <!-- HEADER -->
    <div class="header">
        <div class="header-row">
            <div class="logo-container">
                @if($logoPath != 'images/zonda/landscape_logo.png')
                    <img src="data:image/png;base64,{{ base64_encode(Storage::disk('public')->get($logoPath)) }}" style="width: 300px; margin: 0;">
                @else($logoPath == 'images/zonda/landscape_logo.png')
                    <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/zonda/landscape_logo.png'))) }}" style="width: 300px; margin: 0;">
                @endif
                <div class="company-name">{{ $sat_config['business_name'] }}</div>
                <div class="document-details">
                    <div>RFC: {{ $sat_config['rfc'] }}</div>
                    @php
                        $regimeCode = $sat_config['tax_regime'];
                        $regime = collect($taxRegimes)->firstWhere('Value', $regimeCode);
                    @endphp
                    <div>Régimen Fiscal: {{ $sat_config['tax_regime'] }} - {{ $regime['Name'] ?? 'Desconocido' }}</div>
                    @if($sat_config['phone'] !== null)
                        <div>Teléfono: {{ $sat_config['phone'] }}</div>
                    @endif
                    @if($sat_config['license_number'] !== null)
                        <div>Licencia Sanitaria: {{ $sat_config['license_number'] }}</div>
                    @endif
                </div>
            </div>
            <div class="document-info">
                <div class="document-details">
                    <div class="invoice-title">
                        FACTURA - {{ $invoice->folio ?? 'A' . str_pad($order->id, 5, '0', STR_PAD_LEFT) . '-' . now()->format('Y') }}
                    </div>
                    <div style="margin-bottom: 4px;">
                        <strong style="font-size:8px;">FOLIO FISCAL (UUID)</strong><br>
                        <span class="uuid-text">{{ $invoice->UUID ?? '' }}</span>
                    </div>
                    <div style="margin-bottom: 4px;">
                        <strong style="font-size:8px;">NO. DE SERIE DEL CERTIFICADO DEL EMISOR</strong><br>
                        <span style="font-size:8px;">{{ $invoice->csd_serial_number ?? '' }}</span>
                    </div>
                    <div>
                        <strong style="font-size:8px;">LUGAR DE EXPEDICIÓN</strong><br>
                        <span style="font-size:8px;">{{ $sat_config['address'] }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- (EMISOR/RECEPTOR) -->
    <div class="two-columns-container">
        <div>
            <div class="section-title">DATOS DEL EMISOR</div>
            <div class="info-item">
                <span class="info-label">Nombre:</span> 
                <span class="info-value">{{ $sat_config['business_name'] }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">RFC:</span> 
                <span class="info-value">{{ $sat_config['rfc'] }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Régimen Fiscal:</span> 
                <span class="info-value">{{ $regime['Name'] ?? 'Desconocido' }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Domicilio:</span> 
                <span class="info-value">{{ $sat_config['address'] }}</span>
            </div>
        </div>
        
        <div>
            <div class="section-title">DATOS DEL RECEPTOR</div>
            <div class="info-item">
                <span class="info-label">Nombre:</span> 
                <span class="info-value">{{ $invoice->customer->social_reason }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">RFC:</span> 
                <span class="info-value">{{ $invoice->customer->rfc ?? 'XAXX010101000' }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Uso CFDI:</span> 
                <span class="info-value">{{ $invoice->customer->cfdiUsage->code ?? 'G03' }} - {{ $invoice->customer->taxData->cfdiUsage->description ?? 'Gastos en general' }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Domicilio:</span> 
                <span class="info-value">{{ $invoice->customer->address }}</span>
            </div>
        </div>
    </div>

    <!-- CONCEPTOS -->
    @include('invoices.tables.pdf_order_concepts')

    <!-- FOOTER -->
    <div class="footer">
        <p>{{ $sat_config['business_name'] }} • RFC: {{ $sat_config['rfc'] }} • {{ $sat_config['address'] }}, CP {{ $sat_config['zip_code'] }}</p>
        <p>Teléfono: {{ $sat_config['phone'] }} • www.zonda.mx • contacto@zonda</p>
        <p>Este documento es una representación impresa de un Comprobante Fiscal Digital por Internet</p>   
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        function updateTotal(index) {
            var quantity = parseFloat(document.querySelector('input.quantity-input[data-index="' + index + '"]').value) || 1;
            var cost = parseFloat(document.querySelector('input.cost-input[data-index="' + index + '"]').value) || 0;
            var discountInput = document.querySelector('input[name="services[' + index + '][discount]"]');
            var discount = discountInput ? parseFloat(discountInput.value) || 0 : 0;
            var total = ((cost - discount) * quantity).toFixed(2);
            document.getElementById('total-' + index).textContent = total;
        }

        document.querySelectorAll('.quantity-input, .cost-input').forEach(function(input) {
            input.addEventListener('input', function() {
                var index = this.getAttribute('data-index');
                updateTotal(index);
            });
        });
    });
</script>