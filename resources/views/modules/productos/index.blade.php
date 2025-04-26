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
                            <div class="d-flex gap-2 mt-3 mb-3">
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
                                    data-bs-target="#agregarRemitoModal" >
                                    <i class="fa-solid fa-circle-plus"></i> Agregar nuevo producto
                                </a>

                            </div>
                            <!-- Table with stripped rows -->

                            <!-- End Table with stripped rows -->

                        </div>
                    </div>

                </div>
                <!-- End Table with stripped rows -->
            </div>



    </main>




@endsection
