<table class="table remitos-datatable">
    <thead>
        <tr>
            <th class="text-center">Elementos</th>
            <th class="text-center">Dolares</th>
            <th class="text-center">Pesos</th>
            <th class="text-center">T.C. Teórico</th>
            <th class="text-center">SubTotal Teórico</th>
            <th class="text-center columna-final">T.C. Final</th>
            <th class="text-center columna-final">SubTotal Final</th>
            <th class="text-center">Acciones</th>
        </tr>
    </thead>
    <tbody>
        @php
            $totalTeorico = 0;
            $totalFinal = 0;
        @endphp
        @foreach ($detalles as $detalle)
                @php
                    $subtotalTeorico = $detalle->subTotalRtoTeorico;
                    $totalTeorico += $subtotalTeorico;

                    // Obtenemos el valor final si existe
                    $tcFinal = $detalle->TC_RtoReal ?? 0;
                    $subtotalFinal = $detalle->subTotalRtoReal;
                    $totalFinal += $subtotalFinal;
                @endphp
                <tr class="text-center">
                    <td>{{ $detalle->elemento->descripcionElementoRto ?? 'Sin descripción' }}</td>
                    <td class="text-end editable-cell" data-type="dolar" data-id="{{ $detalle->id }}"
                        data-field="valorDolaresRtoTeorico">
                        {{ number_format($detalle->valorDolaresRtoTeorico, 2, ',', '.') }}
                    </td>
                    <td class="text-end editable-cell" data-type="peso" data-id="{{ $detalle->id }}"
                        data-field="valorPesosRtoTeorico">
                        {{ number_format($detalle->valorPesosRtoTeorico, 2, ',', '.') }}
                    </td>
                    <td class="text-end editable-cell" data-type="tc" data-id="{{ $detalle->id }}" data-field="TC_RtoTeorico">
                        {{ number_format($detalle->TC_RtoTeorico, 2, ',', '.') }}
                    </td>
                    <td class="text-end" data-type="peso" data-id="{{ $detalle->id }}" data-field="subTotalRtoTeorico">
                        {{ number_format($subtotalTeorico, 2, ',', '.') }}
                    </td>
                    <td class="text-end columna-final editable-cell" data-type="tc" data-id="{{ $detalle->id }}"
                        data-field="TC_RtoReal">
                        {{ number_format($tcFinal, 2, ',', '.') }}
                    </td>
                    <td class="text-end columna-final" data-type="peso" data-id="{{ $detalle->id }}"
                        data-field="subTotalRtoReal">
                        {{ number_format($subtotalFinal, 2, ',', '.') }}
                    <td>
                        <a href="#" class="badge bg-danger eliminar-elemento"
                            data-id="{{ $detalle->id }}"><span>Eliminar</span></a>
                    </td>
                </tr>
        @endforeach
        <!-- Fila de totales -->
        <tr class="table-primary">
            <td class="fw-bold">TOTALES:</td>
            <td></td>
            <td></td>
            <td></td>
            <td class="fw-bold text-end">$ {{ number_format($totalTeorico, 2, ',', '.') }}</td>
            <td class="columna-final"></td>
            <td class="fw-bold text-end columna-final" data-type="total" data-id="{{ $items->id }}"
                data-field="totalFinalRto">$ {{ number_format($totalFinal, 2, ',', '.') }}</td>
        </tr>
        <!-- Fila de diferencia -->
        <tr class="table-info">
            <td class="fw-bold">DIFERENCIA:</td>
            <td colspan="2" class="fw-bold text-end">
                $ {{ number_format($totalFinal - $totalTeorico, 2, ',', '.') }}
            </td>
            <!-- resto de la fila -->
        </tr>
    </tbody>
</table>

<script>
    // Inicializar DataTable
    if (typeof $ !== 'undefined' && $.fn.dataTable) {
        $('.remitos-datatable').DataTable({
            searching: false,
            lengthChange: false,
            info: false,
            paging: true
        });
    }

    // Variables para la edición en línea
    let activeEditCell = null;

    // Función para iniciar la edición de una celda
    function startEditing(cell) {
        // Si ya hay una celda en edición, terminar primero
        if (activeEditCell && activeEditCell !== cell) {
            finishEditing(false); // false = no guardar
        }

        // Marcar esta celda como activa
        activeEditCell = cell;

        // Obtener el valor actual (sin formato)
        const currentText = cell.textContent.trim();
        const currentValue = currentText.replace(/\./g, '').replace(',', '.');

        // Guardar el valor original como atributo para posible cancelación
        cell.setAttribute('data-original-text', currentText);

        // Crear input de manera segura
        const input = document.createElement('input');
        input.type = 'text';
        input.value = currentValue;

        // Limpiar la celda manualmente
        while (cell.firstChild) {
            cell.removeChild(cell.firstChild);
        }

        // Añadir clase de edición y el input
        cell.classList.add('editing');
        cell.appendChild(input);

        // Dar foco al input
        input.focus();
        input.select();

        // Manejar teclas para guardar/cancelar
        input.addEventListener('keydown', function (e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                finishEditing(true); // true = guardar
            } else if (e.key === 'Escape') {
                e.preventDefault();
                finishEditing(false); // false = cancelar
            }
        });

        // También guardar al perder el foco
        input.addEventListener('blur', function () {
            if (activeEditCell === cell) {
                finishEditing(true);
            }
        });
    }

    // Función para finalizar la edición
    function finishEditing(save) {
        if (!activeEditCell) return;

        const input = activeEditCell.querySelector('input');
        if (!input) {
            restoreCell(activeEditCell);
            return;
        }

        if (save) {
            saveCell(activeEditCell, input.value);
        } else {
            // Cancelar - restaurar valor original
            const originalText = activeEditCell.getAttribute('data-original-text') || '';
            restoreCell(activeEditCell, originalText);
        }
    }

    // Función para restaurar una celda
    function restoreCell(cell, text = '') {
        if (!cell) return;

        try {
            // Limpiar la celda de manera segura
            while (cell.firstChild) {
                cell.removeChild(cell.firstChild);
            }

            cell.classList.remove('editing');
            cell.textContent = text;

            if (cell === activeEditCell) {
                activeEditCell = null;
            }
        } catch (error) {
            console.error('Error al restaurar celda:', error);
            // Si hay error, intentar simplemente asignar el texto
            if (cell) {
                try {
                    cell.textContent = text;
                    cell.classList.remove('editing');
                    if (cell === activeEditCell) {
                        activeEditCell = null;
                    }
                } catch (e) {
                    console.error('Error secundario al restaurar celda:', e);
                }
            }
        }
    }

    // Función para guardar el valor de una celda
// Función para guardar el valor de una celda
function saveCell(cell, value) {
    if (!cell) return;

    let newValue = value.trim();

    // Validar que sea un número
    if (!/^[0-9]*[.,]?[0-9]*$/.test(newValue)) {
        alert('Por favor, ingrese un valor numérico válido');
        const input = cell.querySelector('input');
        if (input) {
            input.focus();
        }
        return;
    }

    // Convertir coma a punto para cálculos
    newValue = newValue.replace(',', '.');

    // Obtener datos para la actualización
    const id = cell.dataset.id;
    const field = cell.dataset.field;

    if (!id || !field) {
        console.error('Falta id o field en la celda editable');
        const originalText = cell.getAttribute('data-original-text') || '';
        restoreCell(cell, originalText);
        return;
    }

    // Mostrar valor formateado mientras se guarda
    const formattedValue = parseFloat(newValue).toLocaleString('es-AR', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });

    // Guardar una referencia al dataset para acceder después de la llamada fetch
    const cellInfo = {
        id: id,
        field: field,
        isRealField: cell.classList.contains('columna-final')
    };

    // Restaurar la celda con el nuevo valor formateado
    try {
        restoreCell(cell, formattedValue);
    } catch (e) {
        console.error('Error al restaurar celda antes del fetch:', e);
    }

    // Crear FormData para envío
    const formData = new FormData();
    formData.append('id', id);
    formData.append('field', field);
    formData.append('value', newValue);
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

    // Enviar actualización al servidor
    fetch('/remitos/actualizarCampo', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Usar una referencia más estable para la actualización de la UI
                const updatedCell = document.querySelector(`[data-id="${cellInfo.id}"][data-field="${cellInfo.field}"]`);
                if (updatedCell) {
                    updateUIWithServerData(updatedCell, data);
                } else {
                    // Si no podemos encontrar la celda original, actualizar por selección directa
                    updateUIWithoutCell(cellInfo, data);
                }
            } else {
                handleServerError(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            handleServerError(error.message);
        });
}

// Nueva función para actualizar la UI sin referencia a la celda original
function updateUIWithoutCell(cellInfo, data) {
    try {
        // Encontrar la fila por id
        const row = document.querySelector(`tr td[data-id="${cellInfo.id}"]`).closest('tr');
        if (!row) return;
        
        // Determinar si estamos en un campo final
        const isRealField = cellInfo.isRealField || data.isRealField;
        
        // Actualizar subtotales según corresponda
        if (isRealField && data.subtotalFinal !== undefined) {
            const finalSubtotalCell = row.querySelector('td:nth-child(7)');
            if (finalSubtotalCell) {
                finalSubtotalCell.textContent = parseFloat(data.subtotalFinal).toLocaleString('es-AR', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
            }
        } else if (data.subtotal !== undefined) {
            const theoreticSubtotalCell = row.querySelector('td:nth-child(5)');
            if (theoreticSubtotalCell) {
                theoreticSubtotalCell.textContent = parseFloat(data.subtotal).toLocaleString('es-AR', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
            }
        }
        
        // Actualizar totales generales
        updateTotals(data);
    } catch (error) {
        console.error('Error al actualizar UI sin celda:', error);
    }
}

// Nueva función para actualizar solo los totales
function updateTotals(data) {
    try {
        // Actualizar totales generales
        if (data.totalTeorico !== undefined) {
            const totalTeoricoElement = document.querySelector('tr.table-primary td:nth-child(5)');
            if (totalTeoricoElement) {
                totalTeoricoElement.textContent = '$ ' + parseFloat(data.totalTeorico).toLocaleString('es-AR', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
            }
        }
        
        if (data.totalFinal !== undefined) {
            const totalFinalElement = document.querySelector('tr.table-primary td:nth-child(7)');
            if (totalFinalElement) {
                totalFinalElement.textContent = '$ ' + parseFloat(data.totalFinal).toLocaleString('es-AR', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
            }
        }
        
        // Actualizar diferencia
        if (data.diferencia !== undefined) {
            const diferenciaElement = document.querySelector('tr.table-info td:nth-child(2)');
            if (diferenciaElement) {
                diferenciaElement.textContent = '$ ' + parseFloat(data.diferencia).toLocaleString('es-AR', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
            }
        }
    } catch (error) {
        console.error('Error al actualizar totales:', error);
    }
}
    // Función para actualizar la UI con datos del servidor
    function updateUIWithServerData(cell, data) {
        try {
            if (!cell) return;

            // Determinar si estamos en una columna final
            const isRealField = cell.classList.contains('columna-final') || data.isRealField;
            const row = cell.closest('tr');

            if (!row) return;

            // Cuando se actualiza dólares o pesos, necesitamos actualizar ambos subtotales
            const updateBothSubtotals = ['valorDolaresRtoTeorico', 'valorPesosRtoTeorico'].includes(cell.dataset.field);

            // Actualizar subtotal teórico si corresponde
            if (data.subtotal !== undefined) {
                const theoreticSubtotalCell = row.querySelector('td:nth-child(5)');
                if (theoreticSubtotalCell) {
                    theoreticSubtotalCell.textContent = parseFloat(data.subtotal).toLocaleString('es-AR', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    });
                }
            }

            // Actualizar subtotal final si corresponde o si estamos actualizando dólares/pesos
            if ((isRealField || updateBothSubtotals) && data.subtotalFinal !== undefined) {
                const finalSubtotalCell = row.querySelector('td:nth-child(7)');
                if (finalSubtotalCell) {
                    finalSubtotalCell.textContent = parseFloat(data.subtotalFinal).toLocaleString('es-AR', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    });
                }
            }

            // Actualizar totales generales
            if (data.totalTeorico !== undefined) {
                const totalTeoricoElement = document.querySelector('tr.table-primary td:nth-child(5)');
                if (totalTeoricoElement) {
                    totalTeoricoElement.textContent = '$ ' + parseFloat(data.totalTeorico).toLocaleString('es-AR', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    });
                }
            }

            if (data.totalFinal !== undefined) {
                const totalFinalElement = document.querySelector('tr.table-primary td:nth-child(7)');
                if (totalFinalElement) {
                    totalFinalElement.textContent = '$ ' + parseFloat(data.totalFinal).toLocaleString('es-AR', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    });
                }
            }

            // Actualizar diferencia
            if (data.diferencia !== undefined) {
                const diferenciaElement = document.querySelector('tr.table-info td:nth-child(2)');
                if (diferenciaElement) {
                    diferenciaElement.textContent = '$ ' + parseFloat(data.diferencia).toLocaleString('es-AR', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    });
                }
            }
        } catch (error) {
            console.error('Error al actualizar UI:', error);
        }
    }
    // Función auxiliar para actualizar elementos de totales
    function updateTotalElement(selector, formattedValue) {
        const element = document.querySelector(selector);
        if (element) {
            element.textContent = formattedValue;
        }
    }

    // Manejar errores del servidor
    function handleServerError(message) {
        alert('Error: ' + (message || 'Error desconocido'));
        // Opcional: recargar la página para asegurar datos correctos
        // location.reload();
    }

    // Inicializar las celdas editables
    const editableCells = document.querySelectorAll('.editable-cell');
    if (editableCells && editableCells.length > 0) {
        editableCells.forEach(cell => {
            if (cell) {
                cell.addEventListener('click', function () {
                    startEditing(this);
                });
            }
        });
    }

    // Manejar clics fuera para finalizar edición
    document.addEventListener('click', function (e) {
        if (activeEditCell && !activeEditCell.contains(e.target)) {
            finishEditing(true);
        }
    });
</script>