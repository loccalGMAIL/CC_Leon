@extends('layouts.main')

@section('titulo', $titulo)

@section('contenido')

  <main id="main" class="main">

    <div class="pagetitle">
    <h1>Proveedores - Camiones</h1>

    </div><!-- End Page Title -->

    <section class="section">
    <div class="row">
      <div class="col-lg-12">

      <div class="card">
        <div class="card-body">
        <h5 class="card-title">Administrar los camiones</h5>
        <p class="card-text">En esta sección podrá administrar los camiones de los diferentes proveedores.</p>

        <a href="{{route('proveedores.camiones.create')}}" class="btn btn-primary mt-3">
          <i class="fa-solid fa-circle-plus"> </i> Agregar nuevo camión
        </a>
        <!-- Table with stripped rows -->
        <table class="table datatable">
          <thead>
          <tr>
            <th class="text-center">#</th>
            <th class="text-center">Patente</th>
            <th class="text-center">Proveedor</th>
            <th class="text-center">Fecha Creación</th>
            <th class="text-center">Acciones</th>
          </tr>
          </thead>
          <tbody>
          @foreach ($items as $item)
          <tr class="text-center">
          <td>{{$item->id}}</td>
          <td>{{$item->patente}}</td>         
          <td>{{ $item->proveedor->nombreProveedor ?? 'Sin proveedor' }}</td>
          <td>{{$item->created_at}}</td>
          <td>
          <a href="{{route('proveedores.camiones.edit', $item->id)}}" class="btn btn-warning bt-sm"><i class="fa-solid fa-user-pen"></i></a>
          {{-- <a href="#" class="btn btn-danger bt-sm"><i class="fa-solid fa-user-gear"></i></a> --}}
          </td>
          </tr>
      @endforeach
        </table>
        <!-- End Table with stripped rows -->

        </div>
      </div>

      </div>
    </div>
    </section>

  </main>

@endsection