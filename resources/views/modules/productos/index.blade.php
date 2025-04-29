@extends('layouts.main')

@section('titulo', $titulo)

@section('contenido')

    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Remitos</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item active">Productos</li>
                </ol>
            </nav>
        </div>

        <section class="section">
            <div class="row">
                <div class="col-lg-12">

                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Listado de Productos por Proveedor</h5>
                            <p class="card-text">Para comenzar a operar, seleccione un proveedor.</p>
                            {{-- Menu de botones --}}
                            <!-- Div de los botones y controles principales -->
                            <div class="d-flex justify-content-between mt-3 mb-1">
                                <!-- Bloque izquierdo con select y botones -->
                                <div class="d-flex gap-2">
                                    <select class="form-select" id="idProveedor" name="idProveedor" style="width: auto">
                                        <option value="">Seleccionar proveedor</option>
                                        @foreach ($proveedores as $proveedor)
                                            <option value="{{ $proveedor->id }}">
                                                {{ $proveedor->razonSocialProveedor }} ({{ $proveedor->nombreProveedor }})
                                            </option>
                                        @endforeach
                                    </select>

                                    <button type="button" id="toggleFinalColumns" class="btn btn-sm btn-primary" disabled>
                                        <i class="fa-solid fa-eye"></i> Mostrar Columna Costos
                                    </button>
                                    <a href="#" class="btn btn-sm btn-primary disabled" data-bs-toggle="modal"
                                        data-bs-target="#agregarRemitoModal">
                                        <i class="fa-solid fa-circle-plus"></i> Agregar nuevo producto
                                    </a>
                                </div>

                                <!-- Bloque derecho solo con el formulario de cotización -->
                                <div>
                                    <form action="{{ route('cotizacion.guardar') }}" method="POST">
                                        @csrf
                                        <div class="input-group" style="width: auto;">
                                            <span class="input-group-text bg-primary text-white fw-bold">USD</span>
                                            <input 
                                                type="number" 
                                                name="cotizacion" 
                                                value="{{ $cotizacion->precioDolar }}" 
                                                step="0.01" 
                                                class="form-control text-center fw-bold bg-primary text-white"
                                                placeholder="0.00"
                                                style="width: 6rem;"
                                            >
                                            <button type="submit" name="action" value="guardar" class="btn btn-success" title="Guardar cotización">
                                                <i class="fa-solid fa-save"></i>
                                            </button>
                                            <button type="submit" formaction="{{ route('cotizacion.actualizar-externa') }}" class="btn btn-primary" title="Actualizar cotización desde API">
                                                <i class="fa-solid fa-sync-alt"></i>
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <!-- Segundo div con texto de actualización alineado con el componente de cotización -->
                            <div class="d-flex justify-content-between mb-3">
                                <div></div> <!-- Div vacío en el lado izquierdo para mantener la estructura -->
                                <small class="text-muted" style="font-size: 0.75rem; margin-right: 10px;">
                                    Última actualización:
                                    @if (isset($cotizacion))
                                        {{ $cotizacion->updated_at->format('d/m/Y H:i') }}
                                    @else
                                        No disponible
                                    @endif
                                </small>
                            </div>

                            <!-- Table with stripped rows -->

                            <!-- End Table with stripped rows -->

                        </div>
                    </div>

                </div>
                <!-- End Table with stripped rows -->
            </div>



    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Para mensajes de éxito
            @if (session('swal_success'))
                Swal.fire({
                    title: '',
                    text: "{{ session('swal_success') }}",
                    icon: 'success',
                    confirmButtonText: 'OK'
                });
            @endif

            // Para mensajes de error
            @if (session('swal_error'))
                Swal.fire({
                    title: 'Error',
                    text: "{{ session('swal_error') }}",
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            @endif
        });
    </script>


@endsection
