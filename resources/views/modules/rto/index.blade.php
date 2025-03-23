@extends('layouts.main')

@section('titulo', $titulo)

@section('contenido')

  <main id="main" class="main">

    <div class="pagetitle">
    <h1>Remitos</h1>

    </div><!-- End Page Title -->

    <section class="section">
    <div class="row">
      <div class="col-lg-12">

      <div class="card">
        <div class="card-body">
        <h5 class="card-title">Listado de Remitos</h5>
        <p class="card-text">En esta sección podrá administrar los remitos de los envios.</p>

        <a href="#" class="btn btn-primary mt-3 mb-3" data-bs-toggle="modal" data-bs-target="#agregarRemitoModal">
          <i class="fa-solid fa-circle-plus"></i> Agregar nuevo remito
        </a>
        <!-- Table with stripped rows -->
        <table class="table datatable">
          <thead>
          <tr>
            <th class="text-center">Nro Remito</th>
            <th class="text-center">Proveedor</th>
            <th class="text-center">Nro Factura</th>
            <th class="text-center">Observ.</th>
            <th class="text-center">Reclamos</th>
            <th class="text-center">Ingreso</th>
            <th class="text-center">Actualiación</th>
            <th class="text-center">Acciones</th>
          </tr>
          </thead>
          <tbody>
          @foreach ($items as $item)
        <tr class="text-center">
        <td>
        {{ str_pad($item->proveedores_id, 3, '0', STR_PAD_LEFT) }}-{{ str_pad($item->camion, 3, '0', STR_PAD_LEFT) }}-{{ str_pad($item->id, 6, '0', STR_PAD_LEFT) }}
        </td>
        <td>{{ $item->proveedor->razonSocialProveedor ?? 'Sin proveedor' }}</td>
        <td>{{$item->nroFacturaRto}}</td>
        <td>{{ $item->observaciones_count ?? 0 }}</td>
        <td>{{ $item->reclamos_count ?? 0 }}</td>
        <td>{{ \Carbon\Carbon::parse($item->fechaIngresoRto)->format('d/m/Y') }}</td>
        <td>{{ \Carbon\Carbon::parse($item->updated_at)->format('d/m/Y H:i') }}</td>
        <td>
        <a href="{{route('remitos.edit', $item->id)}}"
          class="badge bg-success d-block mb-1"><span>Editar</span></a>
        <a href="#" class="badge bg-secondary d-block mb-1"><span>Observaciones</span></a>
        <a href="#" class="badge bg-danger d-block"><span>Reclamos</span></a>
        </td>
        </tr>
      @endforeach
        </table>
        <!-- End Table with stripped rows -->

        </div>
      </div>

      </div>
    </div>

    <!-- Modal para agregar remito -->
    <div class="modal fade" id="agregarRemitoModal" tabindex="-1" aria-labelledby="agregarRemitoModalLabel"
      aria-hidden="true">
      <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title" id="agregarRemitoModalLabel">Agregar Nuevo Remito</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
        <form id="nuevoRemitoForm" method="POST" action="{{ route('remitos.store') }}">
          @csrf
          <div class="row mb-3">
          <div class="col-md-6">
            <label for="fechaIngresoRto" class="form-label">Fecha</label>
            <input type="date" class="form-control" id="fechaIngresoRto" name="fechaIngresoRto" required
            value="{{ date('Y-m-d') }}">
          </div>
          <div class="col-md-6">
            <label for="nroFacturaRto" class="form-label">Nro. de Factura</label>
            <input type="text" class="form-control" id="nroFacturaRto" name="nroFacturaRto" required>
          </div>
          </div>

          <div class="row mb-3">
          <div class="col-md-12">
            <div class="d-flex">
            <div class="flex-grow-1">
              <label for="idProveedor" class="form-label">Proveedor</label>
              <select class="form-select" id="idProveedor" name="idProveedor" required>
              <option value="">Seleccionar proveedor</option>
              @foreach($proveedores as $proveedor)
          <option value="{{ $proveedor->id }}">
          {{ $proveedor->razonSocialProveedor }} ({{ $proveedor->nombreProveedor }})
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
          </div>

        </form>
        </div>
        <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="submit" form="nuevoRemitoForm" class="btn btn-primary">Crear</button>
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
          <label for="nombreProveedor" class="form-label">Nombre del proveedor *</label>
          <input type="text" class="form-control" id="nombreProveedor" name="nombreProveedor" required>
          </div>
          <div class="mb-3">
          <label for="dniProveedor" class="form-label">DNI</label>
          <input type="text" class="form-control" id="dniProveedor" name="dniProveedor">
          </div>
          <div class="mb-3">
          <label for="razonSocialProveedor" class="form-label">Razón Social *</label>
          <input type="text" class="form-control" id="razonSocialProveedor" name="razonSocialProveedor" required>
          </div>
          <div class="mb-3">
          <label for="cuitProveedor" class="form-label">CUIT *</label>
          <input type="text" class="form-control" id="cuitProveedor" name="cuitProveedor" required>
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
          <input type="text" class="form-control" id="direccionProveedor" name="direccionProveedor">
          </div>

        </form>
        </div>
        <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="submit" form="nuevoProveedorForm" class="btn btn-primary">Guardar</button>
        </div>
      </div>
      </div>
    </div>
    </section>

  </main>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
    // Referencias a elementos del DOM
    const proveedorSelect = document.getElementById('idProveedor');

    // Manejar cierre de modal de proveedor y actualizar lista
    const agregarProveedorModal = document.getElementById('agregarProveedorModal');
    agregarProveedorModal.addEventListener('hidden.bs.modal', function () {
      // Aquí podríamos recargar la lista de proveedores si se agregó uno nuevo
    });

    // Envío de formulario de proveedor con AJAX
    const nuevoProveedorForm = document.getElementById('nuevoProveedorForm');
    nuevoProveedorForm.addEventListener('submit', function (e) {
      e.preventDefault();

      // Crear FormData con los datos del formulario
      const formData = new FormData(this);

      // Enviar datos con fetch
      fetch(this.action, {
      method: 'POST',
      body: formData,
      headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      }
      })
      .then(response => {
        if (!response.ok) {
        throw new Error('Error en la respuesta del servidor');
        }
        return response.json();
      })
      .then(data => {
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

    });

  </script>

@endsection