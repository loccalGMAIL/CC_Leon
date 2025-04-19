@extends('layouts.main')

@section('titulo', $titulo)

@section('contenido')

  <main id="main" class="main">

    <div class="pagetitle">
    <h1>Proveedores</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item active">Proveedores</li>
      </ol>
    </nav>
    </div><!-- End Page Title -->

    <section class="section">
    <div class="row">
      <div class="col-lg-12">

      <div class="card">
        <div class="card-body">
        <h5 class="card-title">Administrar los proveedores</h5>
        <p class="card-text">En esta sección podrá administrar las cuentas de los proveedores.</p>

        <a href="{{route('proveedores.create')}}" class="btn btn-primary mt-3 mb-3">
          <i class="fa-solid fa-circle-plus"> </i> Agregar nuevo proveedor
        </a>
        <!-- Table with stripped rows -->
        <table class="table datatable">
          <thead>
          <tr>
            <th></th>
            <th class="text-center">Nombre</th>
            <th class="text-center">DNI</th>
            <th class="text-center">Razon Social</th>
            <th class="text-center">CUIT</th>
            <th class="text-center">Direccion</th>
            <th class="text-center">Telefono</th>
            <th class="text-center">Email</th>
            <th class="text-center">Activo</th>
            <th class="text-center">Acciones</th>
          </tr>
          </thead>
          <tbody>
          @foreach ($items as $item)
          <tr class="text-center">
            <td>{{$item->id}}</td>
          <td>{{$item->nombreProveedor}}</td>
          <td>{{$item->dniProveedor}}</td>
          <td>{{$item->razonSocialProveedor}}</td>
          <td>{{$item->cuitProveedor}}</td>
          <td>{{$item->direccionProveedor}}</td>
          <td>{{$item->telefonoProveedor}}</td>
          <td>{{$item->mailProveedor}}</td>
          <td>
            <div class="form-check form-switch text-center">
                <input class="form-check-input cambiar-estado" type="checkbox" 
                    id="estadoProveedor{{ $item->id }}" 
                    data-id="{{ $item->id }}" 
                    {{ $item->estadoProveedor == 1 ? 'checked' : '' }}>
            </div>
          </td>
          <td>
          <a href="{{route('proveedores.edit', $item->id)}}" class="btn btn-success btn-sm"><i class="fa-solid fa-pen-to-square"></i></a>
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

@push('scripts')
    <script>
        $(document).ready(function() {
            $('.cambiar-estado').on("change", function() {
                let id = $(this).data("id"); // Obtener el ID del proveedor
                let estado = $(this).is(":checked") ? 1 : 0; // Determinar el nuevo estado

                // Enviar solicitud AJAX al servidor
                $.ajax({
                    url: `/proveedores/estado/${id}`, // Ruta para cambiar el estado
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}', // Token CSRF
                        estadoProveedor: estado // Nuevo estado
                    },
                    success: function(response) {
                        // Mostrar notificación de éxito con SweetAlert
                        Swal.fire(
                            '¡Estado actualizado!',
                            response.message,
                            'success'
                        );
                    },
                    error: function(xhr) {
                        // Mostrar notificación de error con SweetAlert
                        Swal.fire(
                            'Error',
                            'No se pudo actualizar el estado del proveedor.',
                            'error'
                        );
                    }
                });
            });
        });
    </script>
@endpush