@extends('layouts.main')

@section('titulo', $titulo)

@section('contenido')

            <main id="main" class="main">

        <div class="pagetitle">
            <h1><a href="{{Route('remitos')}}"><i class="fa-solid fa-circle-arrow-left"></i></a> Editar Remitos</h1>

        </div><!-- End Page Title -->

        <section class="section">
            <div class="row">
                <div class="col-lg-12">

                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Detalle de Remito Nro:
                                {{ str_pad($items->proveedores_id, 3, '0', STR_PAD_LEFT) }}-{{ str_pad($items->camiones_id, 3, '0', STR_PAD_LEFT) }}-{{ str_pad($items->id, 6, '0', STR_PAD_LEFT) }}
                            </h5>

                            <!-- Botón para mostrar/ocultar detalles del remito -->
                            <div class="mb-3">
                                <button type="button" id="toggleDetalles" class="btn btn-outline-secondary btn-sm">
                                    <i class="fa-solid fa-chevron-down"></i> Mostrar detalles del remito
                                </button>
                            </div>

                            <!-- Div colapsable con los detalles del remito -->
                            <div id="detallesRemito" class="collapse">
                                {{-- Columnas de datos de Remito --}}
                                <div class="row g-3 mb-4">
                                    <div class="col-md-6">
                                        <label for="fechaIngresoRto" class="form-label">Fecha de Ingreso</label>
                                        <input type="date" class="form-control" id="fechaIngresoRto" name="fechaIngresoRto"
                                            required value="{{ $items->fechaIngresoRto }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="nroFacturaRto" class="form-label">Nro. de Factura</label>
                                        <input type="text" class="form-control" id="nroFacturaRto" name="nroFacturaRto"
                                            value="{{$items->nroFacturaRto}}" required>
                                    </div>
                                </div>

                                <div class="row g-3 mb-4">
                                    <div class="col-md-6">
                                        <div class="d-flex">
                                            <div class="flex-grow-1">
                                                <label for="idProveedor" class="form-label">Proveedor</label>
                                                <select class="form-select" id="idProveedor" name="idProveedor" required>
                                                    <option value="">Seleccionar proveedor</option>
                                                    @foreach($proveedores as $proveedor)
                                                                    <option value="{{ $proveedor->id }}" {{ $items->proveedores_id == $proveedor->id ? 'selected' : '' }}>
                                                            {{ $proveedor->razonSocialProveedor }}
                                                            ({{ $proveedor->nombreProveedor }})
                                                        </option>
                                                    @endforeach
                                                        </select>
                                            </div>
                                            <div class="ms-2 d-flex align-items-end">
                                                <button type="button" class="btn btn-success" data-bs-toggle="modal"
                                                    data-bs-target="#agregarProveedorModal">
                                                    <i class="fa-solid fa-plus"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="idCamion" class="form-label">Camión</label>
                                        <select class="form-select" id="idCamion" name="idCamion" required disabled>
                                            <option value="">Primero seleccione un proveedor</option>
                                        </select>
                                        <div id="camionMessage" class="text-danger mt-2 d-none">
                                            Este proveedor no tiene camiones asignados. Por favor, agregue un camión
                                            primero.
                                        </div>
                                    </div>
                                </div>
                                {{-- Fin Columnas --}}
                            </div>


                            <a href="#" class="btn btn-primary mt-3 mb-3" data-bs-toggle="modal"
                                data-bs-target="#agregarElementoModal">
                                <i class="fa-solid fa-circle-plus"></i> Agregar nuevo
                            </a>

                            <!-- Table with stripped rows -->
                            <table class="table remitos-datatable">
                                <thead>
                                    <tr>
                                        <th class="text-center">Elementos</th>
                                        <th class="text-center">Dolares Teórico</th>
                                        <th class="text-center">Pesos Teórico</th>
                                        <th class="text-center">T.C. Teórico</th>
                                        <th class="text-center">SubTotal Teórico</th>
                                        <th class="text-center columna-final">T.C. Final</th>
                                        <th class="text-center columna-final">SubTotal Final</th>
                                        <th class="text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $totalTeorico = 0;
                                        $totalFinal = 0;
                                    @endphp
                                    @foreach ($detalles as $detalle)
                                                                                @php
                                                                                    $subtotalTeorico = $detalle->valorDolaresRtoTeorico * $detalle->TC_RtoTeorico;
                                                                                    $totalTeorico += $subtotalTeorico;

                                                                                    // Obtenemos el valor final si existe
                                                                                    $tcFinal = $detalle->rtoFinal->TC_RtoFinal ?? $detalle->TC_RtoTeorico;
                                                                                    $subtotalFinal = $detalle->valorDolaresRtoTeorico * $tcFinal;
                                                                                    $totalFinal += $subtotalFinal;
                                                                                @endphp
                                                                    <tr class="text-center">
                                                                        <td>{{ $detalle->elemento->descripcionElementoRto ?? 'Sin descripción' }}</td>
                                                                        <td class="text-end editable-cell" data-type="dolar" data-id="{{ $detalle->id }}"
                                                                            data-field="valorDolaresRtoTeorico">
                                                                            {{ number_format($detalle->valorDolaresRtoTeorico, 2, ',', '.') }}</td>
                                                                        <td class="text-end editable-cell" data-type="peso" data-id="{{ $detalle->id }}"
                                                                            data-field="valorPesosRtoTeorico">
                                                                            {{ number_format($detalle->valorPesosRtoTeorico, 2, ',', '.') }}
                                                                        </td>
                                                                        <td class="text-end editable-cell" data-type="tc" data-id="{{ $detalle->id }}"
                                                                            data-field="TC_RtoTeorico">
                                                                            {{ number_format($detalle->TC_RtoTeorico, 2, ',', '.') }}
                                                                        </td>
                                                                        <td class="text-end">{{ number_format($subtotalTeorico, 2, ',', '.') }}</td>
                                                                        <td class="text-end columna-final">{{ number_format($tcFinal, 2, ',', '.') }}</td>
                                                                        <td class="text-end columna-final">{{ number_format($subtotalFinal, 2, ',', '.') }}
                                                                        </td>
                                                                        <td>
                                                                            <a href="#" class="badge bg-danger eliminar-elemento"
                                                                                data-id="{{ $detalle->id }}"><span>Eliminar</span></a>
                                                                        </td>
                                                                    </tr>
                                    @endforeach
         <!-- Fila de totales -->
                                    <tr class="table-primary">
                                        <td class="fw-bold">TOTALES:</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td class="fw-bold text-end">$ {{ number_format($totalTeorico, 2, ',', '.') }}</td>
                                        <td class="columna-final"></td>
                                        <td class="fw-bold text-end columna-final">$
                                            {{ number_format($totalFinal, 2, ',', '.') }}</td>
                                    </tr>
                                    <!-- Fila de diferencia -->
                                    <tr class="table-info">
                                        <td class="fw-bold">DIFERENCIA:</td>
                                        <td colspan="2" class="fw-bold text-end">
                                            $ {{ number_format($totalFinal - $totalTeorico, 2, ',', '.') }}
                                        </td>
                                        <!-- resto de la fila -->
                                    </tr>
                                </tbody>
                            </table>
                            <!-- End Table with stripped rows -->

                        </div>
                    </div>

                </div>
            </div>


            <!-- Modal para agregar proveedor -->
            <div class="modal fade" id="agregarProveedorModal" tabindex="-1" aria-labelledby="agregarProveedorModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="agregarProveedorModalLabel">Agregar Nuevo Proveedor</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="nuevoProveedorForm" method="POST" action="{{ route('proveedores.store') }}">
                                @csrf
                                <div class="mb-3">
                                    <label for="nombreProveedor" class="form-label">Nombre del proveedor</label>
                                    <input type="text" class="form-control" id="nombreProveedor" name="nombreProveedor"
                                        required>
                                </div>
                                <div class="mb-3">
                                    <label for="dniProveedor" class="form-label">DNI</label>
                                    <input type="text" class="form-control" id="dniProveedor" name="dniProveedor">
                                </div>
                                <div class="mb-3">
                                    <label for="razonSocialProveedor" class="form-label">Razón Social</label>
                                    <input type="text" class="form-control" id="razonSocialProveedor"
                                        name="razonSocialProveedor" required>
                                </div>
                                <div class="mb-3">
                                    <label for="cuitProveedor" class="form-label">CUIT</label>
                                    <input type="text" class="form-control" id="cuitProveedor" name="cuitProveedor"
                                        required>
                                </div>
                                <div class="mb-3">
                                    <label for="telefonoProveedor" class="form-label">Teléfono</label>
                                    <input type="text" class="form-control" id="telefonoProveedor" name="telefonoProveedor">
                                </div>
                                <div class="mb-3">
                                    <label for="mailProveedor" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="mailProveedor" name="mailProveedor">
                                </div>
                                <div class="mb-3">
                                    <label for="direccionProveedor" class="form-label">Dirección</label>
                                    <input type="text" class="form-control" id="direccionProveedor"
                                        name="direccionProveedor">
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Cancelar</button>
                                    <button type="submit" form="nuevoProveedorForm" class="btn btn-primary">Guardar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal para agregar/editar elemento del RTO -->
            <div class="modal fade" id="agregarElementoModal" tabindex="-1" aria-labelledby="agregarElementoModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="agregarElementoModalLabel">Agregar Elemento al Remito</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="elementoRtoForm" method="POST" action="{{route('storeRtoDetalle.store')}}">
                                @csrf
                                <input type="hidden" name="rto_id" value="{{ $items->id }}">
                                <input type="hidden" name="rtoteorico_id" id="rtoteorico_id" value="">

                                <div class="mb-3">
                                    <label for="elementoRto_id" class="form-label">Elemento</label>
                                    <div class="d-flex">
                                        <div class="flex-grow-1">
                                            <select class="form-select elemento-select" id="elementoRto_id"
                                                name="elementoRto_id" required>
                                                <option value="">Seleccionar elemento</option>
                                                @foreach($elementosRto as $elemento)
                                                                <option value="{{ $elemento->id }}">{{ $elemento->descripcionElementoRto }}
                                                    </option>
                                                @endforeach
                                                    </select>
                                        </div>
                                        <div class="ms-2">
                                            <button type="button" class="btn btn-success" id="btnNuevoElemento">
                                                <i class="fa-solid fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Sección para nuevo elemento (inicialmente oculta) -->
                                <div id="nuevoElementoSeccion" class="mb-3 border p-3 rounded d-none">
                                    <div class="mb-2">
                                        <label for="descripcionNuevoElemento" class="form-label">Descripción del nuevo
                                            elemento</label>
                                        <input type="text" class="form-control" id="descripcionNuevoElemento"
                                            name="descripcionNuevoElemento">
                                    </div>
                                    <div class="d-flex justify-content-end">
                                        <button type="button" class="btn btn-secondary me-2"
                                            id="btnCancelarNuevoElemento">Cancelar</button>
                                        <button type="button" class="btn btn-primary" id="btnGuardarNuevoElemento">Guardar
                                            Elemento</button>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col">
                                        <label for="valorDolaresRtoTeorico" class="form-label">Valor USD</label>
                                        <input type="number" step="0.01" class="form-control" id="valorDolaresRtoTeorico"
                                            name="valorDolaresRtoTeorico">
                                    </div>
                                    <div class="col">
                                        <label for="valorPesosRtoTeorico" class="form-label">Valor ARS</label>
                                        <input type="number" step="0.01" class="form-control" id="valorPesosRtoTeorico"
                                            name="valorPesosRtoTeorico">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="TC_RtoTeorico" class="form-label">Tipo de Cambio</label>
                                    <input type="number" step="0.01" class="form-control" id="TC_RtoTeorico"
                                        name="TC_RtoTeorico">
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Cancelar</button>
                                    <button type="submit" class="btn btn-primary">Guardar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </main>

    <style>
        .editable-cell {
            cursor: pointer;
            position: relative;
        }
        
        .editable-cell:hover {
            background-color: #f5f5f5;
        }
        
        .editable-cell:hover::after {
            content: '✎';
            position: absolute;
            right: 10px;
            color: #6c757d;
            font-size: 12px;
        }
        
        .editable-cell.editing {
            padding: 0 !important;
            background-color: #e8f4ff !important;
        }
        
        .editable-cell input {
            width: 100%;
            height: 100%;
            border: 2px solid #0d6efd;
            padding: 0.375rem 0.75rem;
            text-align: right;
            outline: none;
        }
    </style>
    
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Código para mostrar/ocultar los detalles del remito
            const toggleDetallesBtn = document.getElementById('toggleDetalles');
            const detallesRemito = document.getElementById('detallesRemito');
    
            if (toggleDetallesBtn && detallesRemito) {
                // Aseguramos que inicialmente esté oculto con display básico
                detallesRemito.style.display = 'none';
    
                toggleDetallesBtn.addEventListener('click', function () {
                    if (detallesRemito.style.display === 'none') {
                        // Mostrar detalles
                        detallesRemito.style.display = 'block';
                        this.innerHTML = '<i class="fa-solid fa-chevron-up"></i> Ocultar detalles del remito';
                    } else {
                        // Ocultar detalles
                        detallesRemito.style.display = 'none';
                        this.innerHTML = '<i class="fa-solid fa-chevron-down"></i> Mostrar detalles del remito';
                    }
                });
            }
    
            // Referencias a elementos del DOM
            const proveedorSelect = document.getElementById('idProveedor');
            const camionSelect = document.getElementById('idCamion');
            const camionMessage = document.getElementById('camionMessage');
    
            // Variable para guardar el ID del camión que debe estar seleccionado
            const camionSeleccionadoId = {{ $items->camiones_id }};
    
            // Asegurar que el proveedor esté seleccionado correctamente
            if (proveedorSelect) {
                proveedorSelect.value = {{ $items->proveedores_id }};
            }
    
            // Cargar camiones cuando la página carga si hay un proveedor seleccionado
            if (proveedorSelect && proveedorSelect.value) {
                cargarCamiones(proveedorSelect.value, camionSeleccionadoId);
            }
    
            // Función para cargar camiones según el proveedor seleccionado
            function cargarCamiones(proveedorId, camionIdSeleccionado = null) {
                if (proveedorId) {
                    // Habilitar el select de camiones
                    camionSelect.disabled = false;
    
                    // Limpiar opciones actuales
                    camionSelect.innerHTML = '<option value="">Cargando camiones...</option>';
    
                    // La URL correcta con los prefijos de grupo de rutas
                    const url = `/proveedores/${proveedorId}/camiones`;
                    console.log('Consultando URL:', url);
    
                    fetch(url)
                        .then(response => {
                            console.log('Respuesta status:', response.status);
                            if (!response.ok) {
                                throw new Error(`Error HTTP: ${response.status}`);
                            }
                            return response.json();
                        })
                        .then(data => {
                            console.log('Datos recibidos:', data);
                            camionSelect.innerHTML = '';
    
                            if (data.length > 0) {
                                camionSelect.innerHTML = '<option value="">Seleccione un camión</option>';
    
                                data.forEach(camion => {
                                    console.log('Camión:', camion);
                                    const option = document.createElement('option');
                                    option.value = camion.id;
                                    const patenteTexto = camion.patente ? camion.patente : 'Sin patente';
                                    option.textContent = `${camion.id} - ${patenteTexto}`;
    
                                    // Si este es el camión que estaba asociado al remito, seleccionarlo
                                    if (camionIdSeleccionado && camion.id == camionIdSeleccionado) {
                                        option.selected = true;
                                    }
    
                                    camionSelect.appendChild(option);
                                });
    
                                camionMessage.classList.add('d-none');
                            } else {
                                camionSelect.innerHTML = '<option value="">No hay camiones disponibles</option>';
                                camionSelect.disabled = true;
                                camionMessage.classList.remove('d-none');
                            }
                        })
                        .catch(error => {
                            console.error('Error detallado:', error);
                            camionSelect.innerHTML = `<option value="">Error: ${error.message}</option>`;
                        });
                } else {
                    // Si no hay proveedor seleccionado
                    camionSelect.disabled = true;
                    camionSelect.innerHTML = '<option value="">Primero seleccione un proveedor</option>';
                    camionMessage.classList.add('d-none');
                }
            }
    
            // Escuchar cambios en el selector de proveedor
            proveedorSelect.addEventListener('change', function () {
                const proveedorId = this.value;
                console.log('Proveedor seleccionado:', proveedorId);
                cargarCamiones(proveedorId);
            });
    
            // Manejo del modal de proveedor
            const agregarProveedorModal = document.getElementById('agregarProveedorModal');
            const nuevoProveedorForm = document.getElementById('nuevoProveedorForm');
    
            if (nuevoProveedorForm) {
                nuevoProveedorForm.addEventListener('submit', function (e) {
                    e.preventDefault();
                    console.log('Enviando formulario de nuevo proveedor');
    
                    // Crear FormData con los datos del formulario
                    const formData = new FormData(this);
    
                    // Asegurarse de tener el token CSRF
                    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
                    // Enviar datos con fetch
                    fetch(this.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': token
                        }
                    })
                        .then(response => {
                            console.log('Respuesta del servidor:', response.status);
                            return response.json();
                        })
                        .then(data => {
                            console.log('Datos recibidos:', data);
                            if (data.success) {
                                // Cerrar modal
                                const modal = bootstrap.Modal.getInstance(agregarProveedorModal);
                                modal.hide();
    
                                // Agregar el nuevo proveedor al select
                                const option = document.createElement('option');
                                option.value = data.proveedor.id;
                                option.textContent = `${data.proveedor.razonSocialProveedor} (${data.proveedor.nombreProveedor})`;
                                proveedorSelect.appendChild(option);
    
                                // Seleccionar el nuevo proveedor
                                proveedorSelect.value = data.proveedor.id;
    
                                // Disparar el evento change para cargar los camiones
                                proveedorSelect.dispatchEvent(new Event('change'));
    
                                // Limpiar el formulario
                                nuevoProveedorForm.reset();
    
                                // Mostrar notificación de éxito
                                alert('Proveedor agregado correctamente');
                            } else {
                                // Mostrar errores
                                alert('Error al agregar proveedor: ' + (data.message || 'Error desconocido'));
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('Error en el servidor: ' + error.message);
                        });
                });
            }
    
            // Elementos del modal para agregar elementos al RTO
            const elementoModal = document.getElementById('agregarElementoModal');
            const elementoForm = document.getElementById('elementoRtoForm');
    
            // Elementos para crear nuevo elemento
            const btnNuevoElemento = document.getElementById('btnNuevoElemento');
            const nuevoElementoSeccion = document.getElementById('nuevoElementoSeccion');
            const descripcionNuevoElemento = document.getElementById('descripcionNuevoElemento');
            const btnCancelarNuevoElemento = document.getElementById('btnCancelarNuevoElemento');
            const btnGuardarNuevoElemento = document.getElementById('btnGuardarNuevoElemento');
    
            // Campos del formulario de elemento RTO
            const valorDolaresInput = document.getElementById('valorDolaresRtoTeorico');
            const valorPesosInput = document.getElementById('valorPesosRtoTeorico');
            const tcInput = document.getElementById('TC_RtoTeorico');
    
            // Inicializar Select2 para búsqueda en el select si está disponible
            if (typeof $ !== 'undefined' && $.fn.select2) {
                $('.elemento-select').select2({
                    dropdownParent: $('#agregarElementoModal'),
                    placeholder: 'Buscar o seleccionar elemento',
                    allowClear: true
                });
            }
    
            // Configurar botón para mostrar sección de nuevo elemento
            if (btnNuevoElemento) {
                btnNuevoElemento.addEventListener('click', function () {
                    nuevoElementoSeccion.classList.remove('d-none');
                    descripcionNuevoElemento.focus();
                });
            }
    
            // Configurar botón para cancelar creación de nuevo elemento
            if (btnCancelarNuevoElemento) {
                btnCancelarNuevoElemento.addEventListener('click', function () {
                    nuevoElementoSeccion.classList.add('d-none');
                    descripcionNuevoElemento.value = '';
                });
            }
    
            // Configurar botón para guardar nuevo elemento
            if (btnGuardarNuevoElemento) {
                btnGuardarNuevoElemento.addEventListener('click', function () {
                    if (!descripcionNuevoElemento.value.trim()) {
                        alert('Por favor, ingrese una descripción para el elemento');
                        return;
                    }
    
                    // Crear FormData para enviar
                    const formData = new FormData();
                    formData.append('descripcionElementoRto', descripcionNuevoElemento.value);
                    formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
    
                    // Enviar solicitud para crear nuevo elemento
                    fetch('{{ route("storeElementoRto") }}', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Agregar nuevo elemento al select
                                if (typeof $ !== 'undefined' && $.fn.select2) {
                                    const newOption = new Option(data.elemento.descripcionElementoRto, data.elemento.id, true, true);
                                    $('.elemento-select').append(newOption).trigger('change');
                                } else {
                                    const newOption = document.createElement('option');
                                    newOption.value = data.elemento.id;
                                    newOption.textContent = data.elemento.descripcionElementoRto;
                                    newOption.selected = true;
                                    document.getElementById('elementoRto_id').appendChild(newOption);
                                }
    
                                // Ocultar sección de nuevo elemento
                                nuevoElementoSeccion.classList.add('d-none');
                                descripcionNuevoElemento.value = '';
    
                                // Notificar éxito
                                alert('Elemento creado correctamente');
                            } else {
                                alert('Error al crear el elemento: ' + (data.message || 'Error desconocido'));
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('Error al procesar la solicitud');
                        });
                });
            }
    
            // Configurar botones de eliminar elemento
            const botonesEliminarElemento = document.querySelectorAll('.eliminar-elemento');
            botonesEliminarElemento.forEach(boton => {
                boton.addEventListener('click', function () {
                    if (confirm('¿Está seguro de eliminar este elemento?')) {
                        const id = this.dataset.id;
    
                        // Crear FormData para CSRF token
                        const formData = new FormData();
                        formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
                        formData.append('_method', 'POST');
    
                        // Enviar solicitud para eliminar
                        fetch(`/remitos/deleteRtoDetalle/${id}`, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    // Eliminar la fila de la tabla de manera segura
                                    const row = this.closest('tr');
                                    if (row && row.parentNode) {
                                        row.parentNode.removeChild(row);
                                    }
    
                                    // Mostrar notificación de éxito
                                    alert('Elemento eliminado correctamente');
                                } else {
                                    alert('Error al eliminar el elemento: ' + (data.message || 'Error desconocido'));
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                alert('Error al procesar la solicitud');
                            });
                    }
                });
            });
    
            // Envío del formulario de elemento
            if (elementoForm) {
                elementoForm.addEventListener('submit', function (e) {
                    e.preventDefault();
    
                    const formData = new FormData(this);
    
                    fetch(this.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Cerrar modal
                                const modal = bootstrap.Modal.getInstance(elementoModal);
                                if (modal) modal.hide();
    
                                // Recargar la página para mostrar el nuevo elemento
                                location.reload();
                            } else {
                                alert('Error: ' + (data.message || 'Error desconocido'));
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('Error al procesar la solicitud');
                        });
                });
            }
    
            // Inicializar DataTable
            if (typeof $ !== 'undefined' && $.fn.dataTable) {
                $('.remitos-datatable').DataTable({
                    searching: false,
                    lengthChange: false,
                    info: false,
                    paging: true
                });
            }
    
            // Variables para la edición en línea
            let activeEditCell = null;
    
            // Función para iniciar la edición de una celda
            function startEditing(cell) {
                // Si ya hay una celda en edición, terminar primero
                if (activeEditCell && activeEditCell !== cell) {
                    finishEditing(false); // false = no guardar
                }
                
                // Marcar esta celda como activa
                activeEditCell = cell;
                
                // Obtener el valor actual (sin formato)
                const currentText = cell.textContent.trim();
                const currentValue = currentText.replace(/\./g, '').replace(',', '.');
                
                // Guardar el valor original como atributo para posible cancelación
                cell.setAttribute('data-original-text', currentText);
                
                // Crear input de manera segura
                const input = document.createElement('input');
                input.type = 'text';
                input.value = currentValue;
                
                // Limpiar la celda manualmente
                while (cell.firstChild) {
                    cell.removeChild(cell.firstChild);
                }
                
                // Añadir clase de edición y el input
                cell.classList.add('editing');
                cell.appendChild(input);
                
                // Dar foco al input
                input.focus();
                input.select();
                
                // Manejar teclas para guardar/cancelar
                input.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        finishEditing(true); // true = guardar
                    } else if (e.key === 'Escape') {
                        e.preventDefault();
                        finishEditing(false); // false = cancelar
                    }
                });
                
                // También guardar al perder el foco
                input.addEventListener('blur', function() {
                    if (activeEditCell === cell) {
                        finishEditing(true);
                    }
                });
            }
    
            // Función para finalizar la edición
            function finishEditing(save) {
                if (!activeEditCell) return;
                
                const input = activeEditCell.querySelector('input');
                if (!input) {
                    restoreCell(activeEditCell);
                    return;
                }
                
                if (save) {
                    saveCell(activeEditCell, input.value);
                } else {
                    // Cancelar - restaurar valor original
                    const originalText = activeEditCell.getAttribute('data-original-text') || '';
                    restoreCell(activeEditCell, originalText);
                }
            }
    
            // Función para restaurar una celda
            function restoreCell(cell, text = '') {
                // Limpiar la celda de manera segura
                while (cell && cell.firstChild) {
                    cell.removeChild(cell.firstChild);
                }
                
                if (cell) {
                    cell.classList.remove('editing');
                    cell.textContent = text;
                    
                    if (cell === activeEditCell) {
                        activeEditCell = null;
                    }
                }
            }
    
            // Función para guardar el valor de una celda
            function saveCell(cell, value) {
                if (!cell) return;
                
                let newValue = value.trim();
                
                // Validar que sea un número
                if (!/^[0-9]*[.,]?[0-9]*$/.test(newValue)) {
                    alert('Por favor, ingrese un valor numérico válido');
                    const input = cell.querySelector('input');
                    if (input) {
                        input.focus();
                    }
                    return;
                }
                
                // Convertir coma a punto para cálculos
                newValue = newValue.replace(',', '.');
                
                // Obtener datos para la actualización
                const id = cell.dataset.id;
                const field = cell.dataset.field;
                
                if (!id || !field) {
                    console.error('Falta id o field en la celda editable');
                    restoreCell(cell, cell.getAttribute('data-original-text') || '');
                    return;
                }
                
                // Mostrar valor formateado mientras se guarda
                const formattedValue = parseFloat(newValue).toLocaleString('es-AR', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
                
                // Restaurar la celda con el nuevo valor formateado
                restoreCell(cell, formattedValue);
                
                // Crear FormData para envío
                const formData = new FormData();
                formData.append('id', id);
                formData.append('field', field);
                formData.append('value', newValue);
                formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
                
                // Enviar actualización al servidor
                fetch('/remitos/actualizarCampo', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        updateUIWithServerData(cell, data);
                    } else {
                        handleServerError(data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    handleServerError(error.message);
                });
            }
    
            // Función para actualizar la UI con datos del servidor
            function updateUIWithServerData(cell, data) {
                try {
                    // Actualizar subtotal en la misma fila si existe
                    if (data.subtotal !== undefined) {
                        const row = cell.closest('tr');
                        if (row) {
                            const subtotalCell = row.querySelector('td:nth-child(5)');
                            if (subtotalCell) {
                                subtotalCell.textContent = parseFloat(data.subtotal).toLocaleString('es-AR', {
                                    minimumFractionDigits: 2,
                                    maximumFractionDigits: 2
                                });
                            }
                        }
                    }
                    
                    // Actualizar totales
                    updateTotalElement('tr.table-primary td:nth-child(5)', data.totalTeorico);
                    updateTotalElement('tr.table-primary td:nth-child(7)', data.totalFinal);
                    updateTotalElement('tr.table-info td:nth-child(2)', data.diferencia);
                    
                } catch (error) {
                    console.error('Error al actualizar UI:', error);
                }
            }
    
            // Función auxiliar para actualizar elementos de totales
            function updateTotalElement(selector, value) {
                if (value !== undefined) {
                    const element = document.querySelector(selector);
                    if (element) {
                        element.textContent = parseFloat(value).toLocaleString('es-AR', {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        });
                    }
                }
            }
    
            // Manejar errores del servidor
            function handleServerError(message) {
                alert('Error: ' + (message || 'Error desconocido'));
                
                // Opcional: recargar la página para asegurar datos correctos
                // location.reload();
            }
    
            // Inicializar las celdas editables
            const editableCells = document.querySelectorAll('.editable-cell');
            if (editableCells && editableCells.length > 0) {
                editableCells.forEach(cell => {
                    if (cell) {
                        cell.addEventListener('click', function() {
                            startEditing(this);
                        });
                    }
                });
            }
    
            // Manejar clics fuera para finalizar edición
            document.addEventListener('click', function(e) {
                if (activeEditCell && !activeEditCell.contains(e.target)) {
                    finishEditing(true);
                }
            });
        });
    </script>

@endsection