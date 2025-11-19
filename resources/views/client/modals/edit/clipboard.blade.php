<div class="modal fade" id="clipboardModal" tabindex="-1" aria-labelledby="clipboardModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="clipboardModalLabel">Portapapeles</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Ruta seleccionada:</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="currentPathDisplay" value="client_system/"
                            readonly>
                        <button class="btn btn-outline-secondary" id="btnHome" title="Volver a raíz">
                            <i class="bi bi-house-fill"></i>
                        </button>
                    </div>
                </div>
                <div class="directory-tree-container border rounded p-2 mb-3">
                    <ul id="directoryTree" class="directory-tree list-unstyled"></ul>
                </div>
                <input type="hidden" name="path" id="selectedPath" value="client_system/">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="copyButton"
                    onclick="copyDirectories()">Copiar</button>
                <button type="button" class="btn btn-warning" id="moveButton"
                    onclick="moveDirectories()">Mover</button>
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>

<script>
        var selected_dirs = [];
        var selected_files = [];

        function clipboardMode() {
            selected_dirs = getSelectedDirectories();
            selected_files = getSelectedFiles();
            if (selected_dirs.length === 0 && selected_files.length === 0) {
                alert('No hay directorios o archivos seleccionados');
                return;
            }
            $('#clipboardModal').modal('show')
        }

        function getSelectedDirectories() {
            const selected = [];
            $('.dir-checkbox:checked').each(function() {
                selected.push($(this).val());
            });
            return selected;
        }

        function getSelectedFiles() {
            const selected = [];
            $('.file-checkbox:checked').each(function() {
                selected.push($(this).val());
            });
            return selected;
        }

        function getAllSelectedItems() {
            return {
                directories: getSelectedDirectories(),
                files: getSelectedFiles()
            };
        }

        function searchDirectories() {
            var path = $('#pathDataList').val();
            var formData = new FormData();
            var csrfToken = $('meta[name="csrf-token"]').attr("content");

            if (path === '') {
                alert('Por favor, ingrese una ruta de destino');
                return;
            }

            formData.append('path', path);

            $.ajax({
                url: "{{ route('client.directory.search') }}",
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    "X-CSRF-TOKEN": csrfToken,
                },
                success: function(response) {
                    var response_data = response;
                    console.log(response_data);
                    $('#pathlistOptions').empty(); // Clear previous options
                    response_data.forEach(function(response_data) {
                        $('#pathlistOptions').append(
                            `<option value="${response_data.path}"> ${response_data.path} </option>`
                        );
                    });
                },
                error: function(xhr, status, error) {
                    // Handle errors here
                    console.error(error);
                }
            });
        }

        /*function copyDirectories() {
            var path = $('#selectedPath').val();
            var formData = new FormData();
            var csrfToken = $('meta[name="csrf-token"]').attr("content");

            if (path === '') {
                alert('Por favor, ingrese una ruta de destino');
                return;
            }
            if (selected_dirs.length === 0) {
                alert('No hay directorios seleccionados');
                return;
            }

            formData.append('path', path);
            formData.append('directories', JSON.stringify(selected_dirs));



            console.log("Directories a copiar:", selected_dirs);
            console.log("JSON stringificado:", JSON.stringify(selected_dirs));

            $.ajax({
                url: "{{ route('client.directory.copy') }}",
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    "X-CSRF-TOKEN": csrfToken,
                },
                success: function(response) {
                    console.log(response);
                    alert('Directorios copiados correctamente');
                    $('#clipboardModal').modal('hide');
                },
                error: function(xhr, status, error) {
                    // Handle errors here
                    console.error(error);
                }
            });
        }*/

    function copyDirectories() {
        const selectedItems = getAllSelectedItems();
        const path = $('#selectedPath').val();
        const formData = new FormData();
        const csrfToken = $('meta[name="csrf-token"]').attr("content");

        if (path === '') {
            alert('Por favor, ingrese una ruta de destino');
            return;
        }
        if (selectedItems.directories.length === 0 && selectedItems.files.length === 0) {
            alert('No hay directorios o archivos seleccionados');
            return;
        }

        formData.append('path', path);
        formData.append('directories', JSON.stringify(selectedItems.directories));
        formData.append('file_paths', JSON.stringify(selectedItems.files));

        // Mostrar loading
        const originalText = $('#copyButton').html();
        $('#copyButton').html('<i class="bi bi-hourglass-split"></i> Copiando...').prop('disabled', true);

        $.ajax({
            url: "{{ route('client.directory.copy') }}",
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                "X-CSRF-TOKEN": csrfToken,
            },
            success: function(response) {
                
                // Restaurar botón
                $('#copyButton').html(originalText).prop('disabled', false);
                
                if (response.success) {
                    alert('✅ Todos los elementos fueron copiados correctamente');
                    $('#clipboardModal').modal('hide');
                    location.reload();
                } else {
                    // Procesar errores
                    let errores = [];
                    let exitosos = [];
                    let advertencias = [];
                    
                    // Analizar cada resultado individualmente
                    Object.entries(response.results).forEach(([item, result]) => {
                        const nombreCorto = item.split('/').pop(); // Solo el nombre del archivo/carpeta
                        
                        if (result.success) {
                            exitosos.push(`✅ ${nombreCorto}`);
                        } else {
                            const mensajeError = result.message || 'Error desconocido';
                            
                            if (mensajeError.includes('ya existe') || mensajeError.includes('destino ya existe')) {
                                advertencias.push(`⚠️ ${nombreCorto}: ${mensajeError}`);
                            } else if (mensajeError.includes('no existe')) {
                                errores.push(`❌ ${nombreCorto}: ${mensajeError}`);
                            } else if (mensajeError.includes('no es un directorio')) {
                                errores.push(`❌ ${nombreCorto}: ${mensajeError}`);
                            } else {
                                errores.push(`❌ ${nombreCorto}: ${mensajeError}`);
                            }
                        }
                    });
                    
                    let mensajeFinal = '';
                    
                    // Construir mensaje final 
                    if (exitosos.length > 0) {
                        mensajeFinal += '✅ Elementos copiados exitosamente:\n' + exitosos.join('\n') + '\n\n';
                    }
                    
                    if (advertencias.length > 0) {
                        mensajeFinal += '⚠️ Advertencias (no se copiaron):\n' + advertencias.join('\n') + '\n\n';
                    }
                    
                    if (errores.length > 0) {
                        mensajeFinal += '❌ Errores:\n' + errores.join('\n');
                    }
                    
                    if (mensajeFinal) {
                        if (mensajeFinal.length > 500) {
                            if (confirm('Se completó la operación con algunos problemas. ¿Ver detalles?')) {
                                alert(mensajeFinal);
                            }
                        } else {
                            alert(mensajeFinal);
                        }
                    } else {
                        alert('Ocurrió un error desconocido al copiar los elementos');
                    }
                    
                    if (exitosos.length > 0) {
                        if (errores.length === 0 && advertencias.length === 0) {
                            // Recargar automáticamente
                            $('#clipboardModal').modal('hide');
                            location.reload();
                        } else {
                            if (confirm('¿Desea recargar la página para ver los cambios?')) {
                                $('#clipboardModal').modal('hide');
                                location.reload();
                            }
                        }
                    } else {
                        console.log('No se copió ningún elemento');
                    }
                }
            },
            error: function(xhr, status, error) {
                
                // Restaurar botón
                $('#copyButton').html(originalText).prop('disabled', false);
                
                let mensajeError = 'Error al copiar elementos';
                
                if (xhr.responseJSON) {
                    if (xhr.responseJSON.message) {
                        mensajeError = xhr.responseJSON.message;
                    } else if (xhr.responseJSON.error) {
                        mensajeError = xhr.responseJSON.error;
                    } else if (xhr.responseJSON.errors) {
                        // Si hay errores de validación
                        const erroresValidacion = Object.values(xhr.responseJSON.errors).flat();
                        mensajeError = 'Errores de validación:\n' + erroresValidacion.join('\n');
                    }
                } else if (xhr.status === 0) {
                    mensajeError = 'Error de conexión. Verifique su internet.';
                } else if (xhr.status === 500) {
                    mensajeError = 'Error interno del servidor. Intente más tarde.';
                } else if (xhr.status === 404) {
                    mensajeError = 'Servicio no encontrado.';
                }
                
                alert('❌ ' + mensajeError);
            }
        });
    }

        /*function moveDirectories() {
            var path = $('#selectedPath').val();
            var formData = new FormData();
            var csrfToken = $('meta[name="csrf-token"]').attr("content");

            formData.append('path', path);
            formData.append('directories', JSON.stringify(selected_dirs));

            console.log('Path: ', path);
            console.log('Directories: ', selected_dirs);

            $.ajax({
                url: "{{ route('client.directory.move') }}",
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    "X-CSRF-TOKEN": csrfToken,
                },
                success: function(response) {
                    console.log(response);
                    alert('Directorios movidos correctamente');
                    $('#clipboardModal').modal('hide');
                },
                error: function(xhr, status, error) {
                    // Handle errors here
                    console.error(error);
                }
            });
        }*/

    function moveDirectories() {
        const selectedItems = getAllSelectedItems();
        const path = $('#selectedPath').val();
        const formData = new FormData();
        const csrfToken = $('meta[name="csrf-token"]').attr("content");

         if (selectedItems.directories.length === 0 && selectedItems.files.length === 0) {
            alert('No hay directorios o archivos seleccionados');
            return;
        }

        formData.append('path', path);
        formData.append('directories', JSON.stringify(selectedItems.directories));
        formData.append('file_paths', JSON.stringify(selectedItems.files));

         // Mostrar loading
         const originalText = $('#moveButton').html();
         $('#moveButton').html('<i class="bi bi-hourglass-split"></i> Moviendo...').prop('disabled', true);

        $.ajax({
            url: "{{ route('client.directory.move') }}",
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                "X-CSRF-TOKEN": csrfToken,
            },
            success: function(response) {
                    
                // Restaurar botón
                $('#moveButton').html(originalText).prop('disabled', false);
                    
                if (response.success) {
                    alert('✅ Todos los elementos fueron movidos correctamente');
                    $('#clipboardModal').modal('hide');
                    location.reload();
                } else {
                    // Procesar errores
                    let errores = [];
                    let exitosos = [];
                    let advertencias = [];
                        
                    Object.entries(response.results).forEach(([item, result]) => {
                         const nombreCorto = item.split('/').pop(); // Solo el nombre del archivo/carpeta
                            
                        if (result.success) {
                            exitosos.push(`✅ ${nombreCorto}`);
                        } else {
                             
                            const mensajeError = result.message || 'Error desconocido';
                                
                            if (mensajeError.includes('ya existe') || mensajeError.includes('destino ya existe')) {
                                advertencias.push(`⚠️ ${nombreCorto}: ${mensajeError}`);
                            } else if (mensajeError.includes('no existe')) {
                                errores.push(`❌ ${nombreCorto}: ${mensajeError}`);
                            } else {
                                errores.push(`❌ ${nombreCorto}: ${mensajeError}`);
                            }
                        }
                    });
                        
                    let mensajeFinal = '';
                        
                    // Construir mensaje final
                    if (exitosos.length > 0) {
                        mensajeFinal += '✅ Elementos movidos exitosamente:\n' + exitosos.join('\n') + '\n\n';
                    }
                        
                    if (advertencias.length > 0) {
                        mensajeFinal += '⚠️ Advertencias (no se movieron):\n' + advertencias.join('\n') + '\n\n';
                    }
                        
                    if (errores.length > 0) {
                        mensajeFinal += '❌ Errores:\n' + errores.join('\n');
                    }
                        
                    if (mensajeFinal) {       
                        if (mensajeFinal.length > 500) {
                            if (confirm('Se completó la operación con algunos problemas. ¿Ver detalles?')) {
                                alert(mensajeFinal);
                            }
                        } else {
                            alert(mensajeFinal);
                        }
                    } else {
                        alert('Ocurrió un error desconocido al mover los elementos');
                    }
                        
                    if (exitosos.length > 0) {
                        if (errores.length === 0 && advertencias.length === 0) {
                            $('#clipboardModal').modal('hide');
                            location.reload();
                        } else {
                            if (confirm('¿Desea recargar la página para ver los cambios?')) {
                                $('#clipboardModal').modal('hide');
                                location.reload();
                            }
                        }
                    } else {
                        console.log('No se movió ningún elemento');
                    }
                }
            },
            error: function(xhr, status, error) {
                // Restaurar botón
                $('#moveButton').html(originalText).prop('disabled', false);
                    
                let mensajeError = 'Error al mover elementos';
                    
                if (xhr.responseJSON) {
                    if (xhr.responseJSON.message) {
                        mensajeError = xhr.responseJSON.message;
                    } else if (xhr.responseJSON.error) {
                        mensajeError = xhr.responseJSON.error;
                    } else if (xhr.responseJSON.errors) {
                        // Si hay errores de validación
                        const erroresValidacion = Object.values(xhr.responseJSON.errors).flat();
                        mensajeError = 'Errores de validación:\n' + erroresValidacion.join('\n');
                    }
                } else if (xhr.status === 0) {
                     mensajeError = 'Error de conexión. Verifique su internet.';
                } else if (xhr.status === 500) {
                    mensajeError = 'Error interno del servidor. Intente más tarde.';
                } else if (xhr.status === 404) {
                    mensajeError = 'Servicio no encontrado.';
                }
                    
                alert('❌ ' + mensajeError);
            }
        });
    }
</script>

<script>
    let selectedPath = '';

    function loadDirectoryTree(path = '') {
        
        $('#directoryTree').html(
            '<div class="text-center py-2"><div class="spinner-border spinner-border-sm"></div> Cargando directorios...</div>'
        );
        
        $.ajax({
            url: "{{ route('client.directory.tree') }}",
            method: 'POST',
            data: {
                path: path,
                _token: "{{ csrf_token() }}"
            },
            success: function(response) {
                if (response.error) {
                    $('#directoryTree').html('<li class="text-danger">❌ Error: ' + response.error + '</li>');
                    return;
                }
                
                renderTree(response, path);
            },
            error: function(xhr, status, error) {
                
                let errorMessage = 'Error de conexión';
                if (xhr.responseJSON && xhr.responseJSON.error) {
                    errorMessage = xhr.responseJSON.error;
                }
                
                $('#directoryTree').html('<li class="text-danger">❌ ' + errorMessage + '</li>');
            }
        });
    }

    function renderTree(items, basePath) {
        const $tree = $('#directoryTree').empty();

        // Botón "Atrás" si no esta en la raíz
        if (basePath) {
            const pathParts = basePath.split('/').filter(part => part !== '');
            pathParts.pop();
            const parent = pathParts.join('/');
            
            $tree.append(`
                <li class="directory-item back" data-path="${parent}">
                    <i class="bi bi-arrow-left-circle-fill text-primary"></i> 
                    <span class="ms-1">Atrás</span>
                </li>
            `);
        }

        if (!Array.isArray(items) || items.length === 0) {
            $tree.append('<li class="text-muted p-2">📁 No hay subdirectorios en esta carpeta</li>');
        } else {
            items.forEach(item => {
                const itemPath = item.path || '';
                const itemName = item.name || basename(itemPath) || 'Sin nombre';
                
                $tree.append(`
                    <li class="directory-item" data-path="${itemPath}">
                        <i class="bi bi-folder-fill text-warning"></i> 
                        <span class="ms-2">${itemName}</span>
                    </li>
                `);
            });
        }

        $tree.off('click', '.directory-item').on('click', '.directory-item', function(e) {
            e.stopPropagation();
            const path = $(this).data('path');
            
            if ($(this).hasClass('back')) {
                loadDirectoryTree(path);
            } else {
                loadDirectoryTree(path);
            }
        });

        $tree.off('dblclick', '.directory-item').on('dblclick', '.directory-item', function(e) {
            e.stopPropagation();
            if (!$(this).hasClass('back')) {
                selectedPath = $(this).data('path');
                updateSelectedPath();
                $('.directory-item.selected').removeClass('selected');
                $(this).addClass('selected');
            }
        });

        updateSelectedPath(basePath);
    }
    
    

    function basename(path) {
        return path.split('/').pop() || path;
    }

    function updateSelectedPath(overridePath = null) {
        if (overridePath !== null) {
            selectedPath = overridePath;
        }
        
        let displayPath = selectedPath || '';
        
        $('#currentPathDisplay').val(displayPath);
        $('#selectedPath').val(displayPath);
    }

    // Inicialización
    $(document).ready(function() {
        $('#btnHome').on('click', function() {
            selectedPath = '';
            loadDirectoryTree('');
        });

        // Inicializar cuando se muestre el modal clipboard
        $('#clipboardModal').on('show.bs.modal', function() {
            selectedPath = '';
        loadDirectoryTree('');
        });
        
        // Limpiar cuando se cierre el modal
        $('#clipboardModal').on('hidden.bs.modal', function() {
            $('#directoryTree').empty();
            selectedPath = '';
        });
        
    });

</script>

<style>
    .directory-tree-container {
         max-height: 300px;
        overflow-y: auto;
        border: 1px solid #ddd;
        padding: 10px;
        border-radius: 4px;
    }


    .directory-tree {
        list-style: none;
        margin: 0;
        padding: 0;
    }

    .directory-tree .directory-item {
        cursor: pointer;
        padding: 5px 8px;
        display: flex;
        align-items: center;
    }

    .directory-tree .directory-item>i.bi-folder-fill {
        margin-right: 8px;
        color: #ffc107;
        font-size: 1.1em;
    }

    .directory-tree .directory-item:hover {
        background-color: rgba(151, 219, 244, 0.68);
    }

    .directory-tree .directory-item.selected {
        background-color: #e3f2fd;
        font-weight: bold;
    }

    .back-arrow {
        color: rgb(74, 107, 223);
        fill: rgb(74, 107, 223);
    }
</style>
