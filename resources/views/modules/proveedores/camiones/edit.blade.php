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
        <h5 class="card-title">Agregar nuevo camión</h5>

        <form action="{{ route('proveedores.camiones.update', $item->id) }}" method="POST">
          @csrf
          @method('PUT')

          <div class="row g-3 mb-4">
          <!-- Primera columna -->
          <div class="col-md-6">
            <div class="form-group mb-3">
            <label for="patente" class="form-label">Patente del Camión</label>
            <input type="text" class="form-control" name="patente" id="patente" value="{{ $item->patente }}">
            </div>


            <!-- Segunda columna -->
            <div class="col-md-6">
            <label for="proveedor_id">Proveedor</label>
            <select name="proveedor_id" id="proveedor_id" class="form-control">
                <option value=""></option>
                @foreach ($proveedores as $proveedor)
                  <option value="{{ $proveedor->id }}" {{ $item->proveedores_id == $proveedor->id ? 'selected' : '' }}>
                    {{ $proveedor->nombreProveedor }}
                  </option>
                @endforeach
              </select>

            </div>
          </div>

          <div class="row mt-4">
            <div class="col-12">
            <div class="d-flex gap-2">
              <button type="submit" class="btn btn-primary">Guardar</button>
              <a href="{{ route('proveedores') }}" class="btn btn-info">Cancelar</a>
            </div>
            </div>
          </div>
        </form>

        </div>
      </div>
      </div>
    </div>
    </section>
  </main>
@endsection