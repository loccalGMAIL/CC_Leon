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