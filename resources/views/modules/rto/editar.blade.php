@extends('layouts.main')

@section('titulo', $titulo)

@section('contenido')

    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Editar Remitos</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('remitos') }}">Remitos</a></li>
                    <li class="breadcrumb-item active">Editar Remito {{ str_pad($items->id, 6, '0', STR_PAD_LEFT) }}</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <section class="section">
            <div class="row">
                <div class="col-lg-12">

                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Detalle de Remito Nro:
                                {{ str_pad($items->proveedores_id, 3, '0', STR_PAD_LEFT) }}-{{ str_pad($items->camion, 3, '0', STR_PAD_LEFT) }}
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
                                        <input type="date" class="form-control" id="fechaIngresoRto"
                                            name="fechaIngresoRto" required value="{{ $items->fechaIngresoRto }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="nroFacturaRto" class="form-label">Nro. de Factura</label>
                                        <input type="text" class="form-control" id="nroFacturaRto" name="nroFacturaRto"
                                            value="{{ $items->nroFacturaRto }}" required>
                                    </div>
                                </div>

                                <div class="row g-3 mb-4">
                                    <div class="col-md-6">
                                        <label for="idProveedor" class="form-label">Proveedor</label>
                                        <select class="form-select" id="idProveedor" name="idProveedor" required disabled>
                                            <option value="">Seleccionar proveedor</option>
                                            @foreach ($proveedores as $proveedor)
                                                <option value="{{ $proveedor->id }}"
                                                    {{ $items->proveedores_id == $proveedor->id ? 'selected' : '' }}>
                                                    {{ $proveedor->razonSocialProveedor }}
                                                    ({{ $proveedor->nombreProveedor }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-6 d-flex align-items-end justify-content-end">
                                        <button type="button" id="guardarCambiosRemito" class="btn btn-primary">
                                            <i class="fa-solid fa-save"></i> Guardar cambios
                                        </button>
                                    </div>
                                </div>
                                {{-- Fin Columnas --}}
                            </div>



                            <div class="d-flex gap-2 mt-3 mb-3">
                                <a href="#" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#agregarElementoModal">
                                    <i class="fa-solid fa-circle-plus"></i> Agregar nuevo
                                </a>
                                <button type="button" id="toggleFinalColumns" class="btn btn-sm btn-primary">
                                    <i class="fa-solid fa-eye"></i> Mostrar Columnas Finales
                                </button>
                                <button id="btnPDF" class="btn btn-sm btn-danger">
                                    <i class="fa-solid fa-file-pdf"></i> PDF
                                </button>
                                <button id="btnPrint" class="btn btn-sm btn-success">
                                    <i class="fa-solid fa-print"></i> Imprimir
                                </button>
                                <a href="#" class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                    data-bs-target="#observacionesModal" data-rto-id="{{ $items->id }}">
                                    <i class="fa-solid fa-circle-plus"></i> Observación
                                </a>
                                <a href="#" class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                    data-bs-target="#reclamosModal" data-rto-id="{{ $items->id }}"
                                    data-nro-remito="{{ $items->nroFacturaRto }}">
                                    <i class="fa-solid fa-circle-plus"></i> Reclamo
                                </a>
                            </div>


                            <!-- Table with stripped rows -->
                            @include('modules.rto.table_edit')
                            <!-- End Table with stripped rows -->

                        </div>
                    </div>

                </div>
            </div>

            @include('modules.rto.modales')


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

        .columna-final {
            background-color: #e2f0ff;
        }

        .celda-desactivada {
            background-color: #f0f0f0;
            color: #999;
            cursor: not-allowed;
        }

        .toggle-column.active {
            animation: highlight-column 1s ease;
        }

        @keyframes highlight-column {
            0% {
                background-color: #e2f0ff;
            }

            50% {
                background-color: #c2e0ff;
            }

            100% {
                background-color: #e2f0ff;
            }
        }

        #toggleFinalColumns {
            transition: all 0.3s ease;
        }

        #toggleFinalColumns:hover {
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // // Código para mostrar/ocultar los detalles del remito usando jQuery
            $(document).ready(function() {
                $('#toggleDetalles').on('click', function() {
                    $('#detallesRemito').toggleClass('show');

                    if ($('#detallesRemito').hasClass('show')) {
                        $(this).html(
                            '<i class="fa-solid fa-chevron-up"></i> Ocultar detalles del remito'
                            );
                    } else {
                        $(this).html(
                            '<i class="fa-solid fa-chevron-down"></i> Mostrar detalles del remito'
                            );
                    }
                });
            });

            // Referencias a elementos del DOM
            const proveedorSelect = document.getElementById('idProveedor');
            const camionSelect = document.getElementById('idCamion');
            const camionMessage = document.getElementById('camionMessage');

            // Asegurar que el proveedor esté seleccionado correctamente
            if (proveedorSelect) {
                proveedorSelect.value = {{ $items->proveedores_id }};
            }

            // Manejo del modal de proveedor
            const agregarProveedorModal = document.getElementById('agregarProveedorModal');
            const nuevoProveedorForm = document.getElementById('nuevoProveedorForm');

            if (nuevoProveedorForm) {
                nuevoProveedorForm.addEventListener('submit', function(e) {
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
                                option.textContent =
                                    `${data.proveedor.razonSocialProveedor} (${data.proveedor.nombreProveedor})`;
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
                                alert('Error al agregar proveedor: ' + (data.message ||
                                    'Error desconocido'));
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
                btnNuevoElemento.addEventListener('click', function() {
                    nuevoElementoSeccion.classList.remove('d-none');
                    descripcionNuevoElemento.focus();
                });
            }

            // Configurar botón para cancelar creación de nuevo elemento
            if (btnCancelarNuevoElemento) {
                btnCancelarNuevoElemento.addEventListener('click', function() {
                    nuevoElementoSeccion.classList.add('d-none');
                    descripcionNuevoElemento.value = '';
                });
            }

            // Configurar botón para guardar nuevo elemento
            if (btnGuardarNuevoElemento) {
                btnGuardarNuevoElemento.addEventListener('click', function() {
                    if (!descripcionNuevoElemento.value.trim()) {
                        alert('Por favor, ingrese una descripción para el elemento');
                        return;
                    }

                    // Crear FormData para enviar
                    const formData = new FormData();
                    formData.append('descripcionElementoRto', descripcionNuevoElemento.value);
                    formData.append('_token', document.querySelector('meta[name="csrf-token"]')
                        .getAttribute('content'));

                    // Enviar solicitud para crear nuevo elemento
                    fetch('{{ route('storeElementoRto') }}', {
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
                                    const newOption = new Option(data.elemento.descripcionElementoRto,
                                        data.elemento.id, true, true);
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
                                alert('Error al crear el elemento: ' + (data.message ||
                                    'Error desconocido'));
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
                boton.addEventListener('click', function() {
                    Swal.fire({
                        title: '¿Estás seguro?',
                        text: "Este elemento será eliminado permanentemente.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Sí, eliminar',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const id = this.dataset.id;

                            // Crear FormData para CSRF token
                            const formData = new FormData();
                            formData.append('_token', document.querySelector(
                                'meta[name="csrf-token"]').getAttribute('content'));
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
                                        Swal.fire(
                                            '¡Eliminado!',
                                            'El elemento ha sido eliminado correctamente.',
                                            'success'
                                        );
                                    } else {
                                        Swal.fire(
                                            'Error',
                                            'No se pudo eliminar el elemento: ' + (
                                                data.message || 'Error desconocido'
                                                ),
                                            'error'
                                        );
                                    }
                                })
                                .catch(error => {
                                    console.error('Error:', error);
                                    Swal.fire(
                                        'Error',
                                        'Ocurrió un error al procesar la solicitud.',
                                        'error'
                                    );
                                });
                        }
                    });
                });
            });


        });

        // Detectar cuando se abre el modal
        const observacionesModal = document.getElementById('observacionesModal');
        if (observacionesModal) {
            observacionesModal.addEventListener('show.bs.modal', function(event) {
                // Botón que activó el modal
                const button = event.relatedTarget;

                // Extraer info de los atributos data-*
                const rtoId = button.getAttribute('data-rto-id');
                const nroRemito = button.getAttribute('data-nro-remito');

                // Actualizar contenido del modal
                const modalRtoIdInput = document.getElementById('modal_rto_id');
                // const modalNroRemitoInput = document.getElementById('modal_nroRemito');
                const fechaInput = document.getElementById('fecha');

                if (modalRtoIdInput) modalRtoIdInput.value = rtoId;
                // if (modalNroRemitoInput) modalNroRemitoInput.value = nroRemito;
                if (fechaInput) fechaInput.value = new Date().toISOString().split('T')[0]; // Fecha actual
            });
        }

        // Detectar cuando se abre el modal de reclamos
        const reclamosModal = document.getElementById('reclamosModal');
        if (reclamosModal) {
            reclamosModal.addEventListener('show.bs.modal', function(event) {
                // Botón que activó el modal
                const button = event.relatedTarget;

                // Extraer info de los atributos data-*
                const rtoId = button.getAttribute('data-rto-id');
                const nroRemito = button.getAttribute('data-nro-remito');

                // Actualizar contenido del modal
                const modalRtoIdInput = document.getElementById('reclamo_rto_id');
                const nroRemitoInput = document.getElementById('nroRemitoReclamo');

                if (modalRtoIdInput) modalRtoIdInput.value = rtoId;
                if (nroRemitoInput) nroRemitoInput.value = nroRemito;

                // Resetear el formulario al abrir
                document.getElementById('reclamoForm').reset();
                document.getElementById('estadoReclamoRto').value = 'pendiente';
                toggleResolucionField();
            });
        }
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const guardarCambiosRemito = document.getElementById('guardarCambiosRemito');
            const fechaIngresoRto = document.getElementById('fechaIngresoRto');
            const nroFacturaRto = document.getElementById('nroFacturaRto');

            if (guardarCambiosRemito) {
                guardarCambiosRemito.addEventListener('click', function() {
                    // Mostrar alerta de confirmación
                    Swal.fire({
                        title: '¿Estás seguro?',
                        text: "Se guardarán los cambios realizados.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Sí, guardar',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Crear un objeto con los datos a enviar
                            const data = {
                                fechaIngresoRto: fechaIngresoRto.value,
                                nroFacturaRto: nroFacturaRto.value,
                                _token: document.querySelector('meta[name="csrf-token"]')
                                    .getAttribute('content') // CSRF token
                            };

                            // Enviar los datos al servidor
                            fetch('{{ route('actualizarRemito', $items->id) }}', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-Requested-With': 'XMLHttpRequest'
                                    },
                                    body: JSON.stringify(data)
                                })
                                .then(response => response.json())
                                .then(result => {
                                    if (result.success) {
                                        // Mostrar alerta de éxito
                                        Swal.fire(
                                            '¡Guardado!',
                                            'Los cambios se han guardado correctamente.',
                                            'success'
                                        );
                                    } else {
                                        // Mostrar alerta de error
                                        Swal.fire(
                                            'Error',
                                            'No se pudieron guardar los cambios: ' + (result
                                                .message || 'Error desconocido'),
                                            'error'
                                        );
                                    }
                                })
                                .catch(error => {
                                    console.error('Error:', error);
                                    // Mostrar alerta de error
                                    Swal.fire(
                                        'Error',
                                        'Ocurrió un error al procesar la solicitud.',
                                        'error'
                                    );
                                });
                        }
                    });
                });
            }
        });
    </script>

@endsection
