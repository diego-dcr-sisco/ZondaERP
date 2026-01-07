@extends('layouts.app')
@section('content')
<div class="container-fluid p-0">
    <div class="d-flex align-items-center border-bottom ps-4 p-2">
        <span class="text-black fw-bold fs-4">CONFIGURACIÓN DE CERTIFICADOS (SAT)</span>

    </div>
    <div class="configuration-container">
        <form method="POST" action="{{route('config.sat.upload')}}" enctype="multipart/form-data">
            @csrf
            <!-- Sección de Certificado -->
                <div class="settings-card">
                    <div class="settings-card-header">
                        <i class="bi bi-file-earmark-text me-2"></i>Certificado
                    </div>
                    <div class="settings-card-body">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <p>El archivo .DER debe convertirse a base64 usando el siguiente comando : <span class="fw-bold">openssl x509 -inform der -in nombre_certificado.cer -out nombre_final.cer</span> antes de subirse aquí.</p>
                                <p>Elimine el encabezado y pie de página que aparecen en el archivo generado. </p> 
                                <p>Suba aquí el Certificado. </p>
                                
                                <label for="certificate_file" class="custom-file-upload">
                                    <i class="bi bi-cloud-upload me-2"></i>Seleccionar Archivo
                                </label>
                                <input type="file" 
                                    class="form-control"
                                    id="certificate_file" 
                                    name="certificate_file"
                                    accept=".cer"
                                    onchange="this.form.submit()"/>
                            </div>
                            <div class="col-md-6 text-center">
                            <div class="logo-preview">   
                                    <div class="pem-preview">
                                        @if(Storage::disk('public')->exists('invoices/certificates/certificate.cer'))
                                            <?php
                                            $pemContent = Storage::disk('public')->get('invoices/certificates/certificate.cer');
                                            ?>
                                            <div class="card">
                                                <div class="card-body p-0">
                                                  <div class="pem-content-wrapper" style="background: #f8f9fa; max-height: 250px; overflow: auto;">
                                                        <code class="d-block px-3 pt-2 pb-4" style="font-family: 'SFMono-Regular', Consolas, 'Liberation Mono', Menlo, Courier, monospace; font-size: 11px; white-space: pre-wrap; word-break: break-word; line-height: 1.4; color: #000000;">
                                                            {{ $pemContent }}
                                                        </code>
                                                    </div>
                                                </div>
                                            </div> 
                                         
                                        @endif
                                    </div>    
                            </div>
                            @if(Storage::disk('public')->exists('invoices/certificates/certificate.cer'))
                                <small class="text-muted">
                                    <i class="bi bi-info-circle"></i>
                                    Archivo .cer - {{ Storage::disk('public')->size('invoices/certificates/certificate.cer') }} bytes
                                </small>
                            @else
                                <small class="text-muted">No hay archivo .cer en formato PEM cargado</small>
                            @endif
                        </div>
                        </div>
                    </div>
                </div>
        </form>

        <form method="POST" action="{{route('config.sat.upload')}}" enctype="multipart/form-data">
            @csrf
                <!-- Sección de la Llave Privada -->
                <div class="settings-card">
                    <div class="settings-card-header">
                        <i class="bi bi-file-earmark-text me-2"></i>Llave Privada
                    </div>
                    <div class="settings-card-body">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <p>El archivo .key debe convertirse a base64 usando el siguiente comando : <span class="fw-bold">certutil -encode nombre_certificado.key nombre_certificado.key</span> antes de subirse aquí.</p>
                                <p>Elimine el encabezado y pie de página que aparecen en el archivo generado. </p> 
                                <p>Suba aquí la Llave Privada. </p>

                                <label for="private_key" class="custom-file-upload">
                                    <i class="bi bi-cloud-upload me-2"></i>Seleccionar Archivo
                                </label>
                                <input type="file" 
                                    class="form-control"
                                    id="private_key" 
                                    name="private_key"
                                    accept=".key"
                                    onchange="this.form.submit()"/>
                            </div>
                            <div class="col-md-6 text-center">
                            <div class="logo-preview">
                                <div class="pem-preview">
                                        @if(Storage::disk('public')->exists('invoices/certificates/private_key.key'))
                                            <?php
                                            $pemContent = Storage::disk('public')->get('invoices/certificates/private_key.key');
                                            ?>
                                            <div class="card">
                                                <div class="card-body p-0">
                                                  <div class="pem-content-wrapper" style="background: #f8f9fa; max-height: 250px; overflow: auto;">
                                                        <code class="d-block px-3 pt-2 pb-4" style="font-family: 'SFMono-Regular', Consolas, 'Liberation Mono', Menlo, Courier, monospace; font-size: 11px; white-space: pre-wrap; word-break: break-word; line-height: 1.4; color: #000000;">
                                                            {{ $pemContent }}
                                                        </code>
                                                    </div>
                                                </div>
                                            </div>     
                                        @endif
                                    </div> 
                            </div>
                            @if(Storage::disk('public')->exists('invoices/certificates/private_key.key'))
                                <small class="text-muted">
                                    <i class="bi bi-info-circle"></i>
                                    Archivo .key - {{ Storage::disk('public')->size('invoices/certificates/private_key.key') }} bytes
                                </small>
                            @else
                                <small class="text-muted">No hay archivo .key en formato PEM cargado</small>
                            @endif
                        </div>
                        </div>
                    </div>
                </div> 
        </form> 
        <!-- Sección de Registro de CSD -->
                <div class="settings-card">
                    <div class="settings-card-header">
                        <i class="bi bi-file-earmark-text me-2"></i>Registro de Sello Digital CSD en Facturama
                    </div>
                    <div class="settings-card-body">
                        <div class="row align-items-center">
                            <div class="col-md-12">
                                <p class='fw-bold'>Una vez que ha seleccionado los archivos .cer y .key debe registrarlos en el portal de Facturama para poder emitir CFDI's.</p>
                                
                                <a href="{{ route('config.sat.registerCSD') }}" class="custom-file-upload text-white text-decoration-none">
                                    <i class="bi bi-cloud-upload me-2"></i> Registrar CSD
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Sección de Actualización de CSD -->
                <div class="settings-card">
                    <div class="settings-card-header">
                        <i class="bi bi-file-earmark-text me-2"></i>Actualización de Sello Digital CSD en Facturama
                    </div>
                    <div class="settings-card-body">
                        <div class="row align-items-center">
                            <div class="col-md-12">
                                <p class='fw-bold'>En caso de que el certificado se encuentre vencido es necesario actualizarlo. Vuelva a subir los archivos .cer y .key y de clic en el botón "Actualizar" para poder seguir emitiendo CFDI's.</p>
                                
                                <a href="{{ route('config.sat.updateCSD') }}" class="custom-file-upload text-white text-decoration-none">
                                    <i class="bi bi-cloud-upload me-2"></i> Actualizar CSD
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Sección Eliminar CSD -->
                <div class="settings-card">
                    <div class="settings-card-header">
                        <i class="bi bi-file-earmark-text me-2"></i>Eliminar Sello Digital CSD en Facturama
                    </div>
                    <div class="settings-card-body">
                        <div class="row align-items-center">
                            <div class="col-md-12">
                                <p class='fw-bold'>De clic en Eliminar para dar de baja del portal de Facturama al emisor de los CFDI's.</p>
                                
                                <a href="{{ route('config.sat.deleteCSD') }}" class="custom-file-upload text-white text-decoration-none">
                                    <i class="bi bi-cloud-upload me-2"></i> Eliminar CSD
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
    </div>
</div>

 <style>
        .configuration-container {
            margin: 20px auto;
            padding: 20px;
            background-color: #f8f9fc;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }
        
        .settings-card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            margin-bottom: 20px;
            overflow: hidden;
        }
        
        .settings-card-header {
            background-color: #f8f9fc;
            padding: 15px 20px;
            border-bottom: 1px solid #e3e6f0;
            font-weight: 600;
            color: #4e73df;
        }
        
        .settings-card-body {
            padding: 20px;
        }
        
        .logo-preview, .watermark-preview {
            width: 200px;
            height: 200px;
            border: 2px dashed #ddd;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
            overflow: hidden;
            background-color: #f8f9fc;
            transition: all 0.3s;
        }
        
        .logo-preview:hover, .watermark-preview:hover {
            border-color: #4e73df;
            background-color: #eaecf4;
        }
        
        .logo-preview img, .watermark-preview img {
            max-width: 100%;
            max-height: 100%;
        }
        
        .color-option {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 10px;
            cursor: pointer;
            border: 2px solid transparent;
            transition: all 0.2s;
        }
        
        .color-option:hover, .color-option.selected {
            transform: scale(1.1);
            border-color: #333;
        }
        
        .custom-file-upload {
            display: inline-block;
            padding: 10px 15px;
            background-color: #4e73df;
            color: white;
            border-radius: 5px;
            text-align: center;
            cursor: pointer;
            transition: background-color 0.3s;
            margin-top: 15px;
        }
        
        .custom-file-upload:hover {
            background-color: #3a5ccc;
        }
        
        .btn-save:hover {
            background-color: #3a5ccc;
            transform: translateY(-2px);
        }
        
        .preview-section {
            background-color: white;
            border-radius: 8px;
            padding: 20px;
            margin-top: 30px;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        }
        
        .preview-navbar {
            background-color: #4e73df;
            color: white;
            padding: 10px 15px;
            border-radius: 5px;
            margin-bottom: 15px;
        }
        
        input[type="file"] {
            display: none;
        }
        
        .form-control-color {
            width: 60px;
            height: 40px;
        }
        
        .form-range {
            width: 100%;
        }
        
        output {
            display: inline-block;
            margin-left: 10px;
            font-weight: bold;
        }

         .pem-content pre {
            font-family: 'Consolas', 'Monaco', 'Courier New', monospace;
            font-size: 12px;
            line-height: 1.4;
            background: #f8f9fa;
            white-space: pre-wrap;
            word-wrap: break-word;
        }
        
        .pem-preview {
            height: 100%;
        }
        
        .pem-preview .card {
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        
        .pem-preview .card-body {
            flex: 1;
            overflow: hidden;
        }
</style>
<script>
function submitForm() {
    // Validar que se haya seleccionado un archivo
    const fileInput = document.getElementById('certificate_file');
    if (fileInput.files.length > 0) {
        // Opcional: Mostrar loading
        const submitBtn = document.querySelector('button[type="submit"]');
        if (submitBtn) {
            submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Subiendo...';
            submitBtn.disabled = true;
        }
        
        // Enviar formulario
        document.getElementById('certificateForm').submit();
    }
}
</script>
@endsection