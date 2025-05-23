@extends('layouts.main')
@section('titulo', $titulo)
@section('contenido')
  <main id="main" class="main">
    <div class="pagetitle">
    <h1>Observaciones</h1>
    @if(isset($remito))
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('remitos') }}">Remitos</a></li>
        <li class="breadcrumb-item active">Observaciones del Remito {{ str_pad($remito->id, 6, '0', STR_PAD_LEFT) }}
        </li>
      </ol>
    </nav>
    @else
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('remitos') }}">Remitos</a></li>
        <li class="breadcrumb-item active">Observaciones</li>
      </ol>
    </nav>
  @endif
    </div><!-- End Page Title -->

    <section class="section">
    <div class="row">
      <div class="col-lg-12">
      <div class="card">
        <div class="card-body">
        @if(isset($remito))
      <h5 class="card-title">
        Observaciones del Remito
        #{{ str_pad($remito->proveedores_id, 3, '0', STR_PAD_LEFT) }}-{{ str_pad($remito->camion, 3, '0', STR_PAD_LEFT) }}
      </h5>
      <p>Proveedor: {{ $remito->proveedor->razonSocialProveedor }}</p>
      <p>Factura: {{ $remito->nroFacturaRto }}</p>

      <div class="d-flex justify-content-between align-items-center mb-3">
        <h5>Lista de Observaciones</h5>
        <a href="#" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#agregarObservacionModal">
        <i class="fa-solid fa-circle-plus"></i> Agregar Observación
        </a>
      </div>
    @else
    <h5 class="card-title">Administrar las observaciones</h5>
    <p class="card-text">En esta sección podrá administrar las observaciones de los envios.</p>
  @endif

        @if(session('success'))
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif
        <!-- Table with stripped rows -->
        <table class="table datatable">
          <thead>
          <tr>
            <th class="text-center">#</th>
            <th class="text-center">Fecha</th>
            @if(!isset($remito))
        <th class="text-center">Nro Remito</th>
      @endif
            <th class="text-center">Proveedor</th>
            <th class="text-center">Descripcion</th>
            <th class="text-center">Acciones</th>
          </tr>
          </thead>
          <tbody>
          @foreach ($items as $item)
        <tr class="text-center">
        <td>{{$item->id}}</td>
        <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y H:i') }}</td>
        @if(!isset($remito))
      <td>{{ str_pad($item->rto->proveedores_id, 3, '0', STR_PAD_LEFT) }}-{{ str_pad($item->rto->camion, 3, '0', STR_PAD_LEFT) }}</td>
    @endif
        <td>{{ $item->rto->proveedor->razonSocialProveedor }}</td>
        <td>{{$item->descripcionObservacionesRto}}</td>
        <td>
        <a href="#" class="badge bg-success" data-bs-toggle="modal"
          data-bs-target="#editarObservacionModal{{ $item->id }}" title="Editar">
          <i class="fa-solid fa-pen-to-square"></i></a>
        <a href="#" class="badge bg-danger eliminar-observacion" data-id="{{ $item->id }}" title="Eliminar">
          <i class="fa-solid fa-trash"></i></a>
        </td>
        </tr>
      @endforeach
          </tbody>
        </table>
        <!-- End Table with stripped rows -->

        @if($items->isEmpty())
      <div class="alert alert-info mt-3">No hay observaciones
        registradas{{ isset($remito) ? ' para este remito' : '' }}.</div>
    @endif
        </div>
      </div>
      </div>
    </div>
    </section>

    @if(isset($remito))
    <!-- Modal para agregar observación -->
    <div class="modal fade" id="agregarObservacionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
      <div class="modal-header">
      <h5 class="modal-title">Agregar Observación</h5>
      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <form id="nuevaObservacionForm" method="POST" action="{{ route('observaciones.store') }}">
      @csrf
      <input type="hidden" name="Rto_id" value="{{ $remito->id }}">
      <div class="mb-3">
        <label for="descripcionObservacionesRto" class="form-label">Descripción</label>
        <textarea class="form-control" id="descripcionObservacionesRto" name="descripcionObservacionesRto"
        rows="4" required></textarea>
      </div>
      </form>
      </div>
      <div class="modal-footer">
      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
      <button type="submit" form="nuevaObservacionForm" class="btn btn-primary">Guardar</button>
      </div>
      </div>
    </div>
    </div>
  @endif

    <!-- Modales para editar cada observación -->
    @foreach($items as $observacion)
    <div class="modal fade" id="editarObservacionModal{{ $observacion->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
      <div class="modal-header">
      <h5 class="modal-title">Editar Observación</h5>
      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <form id="editarObservacionForm{{ $observacion->id }}" method="POST"
      action="{{ route('observaciones.update', $observacion->id) }}">
      @csrf
      @method('PUT')
      <div class="mb-3">
        <label for="descripcionObservacionesRto{{ $observacion->id }}" class="form-label">Descripción</label>
        <textarea class="form-control" id="descripcionObservacionesRto{{ $observacion->id }}"
        name="descripcionObservacionesRto" rows="4"
        required>{{ $observacion->descripcionObservacionesRto }}</textarea>
      </div>
      </form>
      </div>
      <div class="modal-footer">
      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
      <button type="submit" form="editarObservacionForm{{ $observacion->id }}"
      class="btn btn-primary">Actualizar</button>
      </div>
      </div>
    </div>
    </div>
  @endforeach

    <script>
    function confirmarEliminar(id) {
      if (confirm('¿Está seguro que desea eliminar esta observación?')) {
      // Crear un formulario dinámicamente para enviar la solicitud DELETE
      const form = document.createElement('form');
      form.method = 'POST';
      form.action = '{{ url("observaciones/delete") }}/' + id;
      form.style.display = 'none';

      const csrfToken = document.createElement('input');
      csrfToken.type = 'hidden';
      csrfToken.name = '_token';
      csrfToken.value = '{{ csrf_token() }}';

      const method = document.createElement('input');
      method.type = 'hidden';
      method.name = '_method';
      method.value = 'DELETE';

      form.appendChild(csrfToken);
      form.appendChild(method);
      document.body.appendChild(form);
      form.submit();
      }
    }
    </script>
  </main>
@endsection
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Seleccionar todos los botones de eliminar observación
            const botonesEliminar = document.querySelectorAll('.eliminar-observacion');

            botonesEliminar.forEach(boton => {
                boton.addEventListener('click', function (e) {
                    e.preventDefault(); // Evitar el comportamiento predeterminado del enlace

                    const id = this.dataset.id; // Obtener el ID de la observación

                    // Mostrar confirmación con SweetAlert
                    Swal.fire({
                        title: '¿Estás seguro?',
                        text: "Esta observación será eliminada permanentemente.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Sí, eliminar',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Enviar solicitud AJAX para eliminar la observación
                            fetch(`/observaciones/destroy/${id}`, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}', // Token CSRF
                                    'X-Requested-With': 'XMLHttpRequest'
                                },
                                body: JSON.stringify({
                                    _method: 'DELETE' // Método DELETE
                                })
                            })
                            .then(response => {
                                if (!response.ok) {
                                    throw new Error(`HTTP error! status: ${response.status}`);
                                }
                                return response.json();
                            })
                            .then(data => {
                                if (data.success) {
                                    // Eliminar la fila de la tabla de manera segura
                                    const row = boton.closest('tr');
                                    if (row && row.parentNode) {
                                        row.parentNode.removeChild(row);
                                    }

                                    // Mostrar notificación de éxito
                                    Swal.fire(
                                        '¡Eliminado!',
                                        data.message,
                                        'success'
                                    );
                                } else {
                                    Swal.fire(
                                        'Error',
                                        data.message || 'No se pudo eliminar la observación.',
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
    </script>
@endpush
