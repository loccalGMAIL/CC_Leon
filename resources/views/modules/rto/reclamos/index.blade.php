@extends('layouts.main')

@section('titulo', $titulo)

@section('contenido')

  <main id="main" class="main">

    <div class="pagetitle">
    <h1>Reclamos</h1>

    </div><!-- End Page Title -->

    <section class="section">
    <div class="row">
      <div class="col-lg-12">

      <div class="card">
        <div class="card-body">
        <h5 class="card-title">Administrar los reclamos</h5>
        <p class="card-text">En esta sección podrá administrar los reclamos de los envios.</p>

        {{-- <a href="#" class="btn btn-primary mt-3">
          <i class="fa-solid fa-circle-plus"> </i> Agregar nueva observación
        </a> --}}
        <!-- Table with stripped rows -->
        <table class="table datatable">
          <thead>
          <tr>
            <th class="text-center">#</th>
            <th class="text-center">Fecha</th>
            <th class="text-center">Nro Remito</th>
            <th class="text-center">Descripcion</th>
            <th class="text-center">Estado</th>
            <th class="text-center">Resolución</th>
            <th class="text-center">Acciones</th>
          </tr>
          </thead>
          <tbody>
          @foreach ($items as $item)
          <tr class="text-center">
          <td>{{$item->id}}</td>
          <td>{{$item->fecha}}</td>
          <td>{{$item->rto_id}}</td>
          <td>{{$item->descripcionReclamoRto}}</td>
          <td>{{$item->estadoReclamoRto}}</td>
          <td>{{$item->resolucionReclamoRto}}</td>
          <td>
          <a href="#" class="btn btn-warning bt-sm"><i class="fa-solid fa-user-pen"></i></a>
          <a href="#" class="btn btn-danger bt-sm"><i class="fa-solid fa-user-gear"></i></a>
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