<?php
$pageTitle = "Remito Teórico";
ob_start();
?>

<div class="bg-white p-6 rounded-lg shadow">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-semibold">Remito Teórico #<?php echo htmlspecialchars($_GET['id'] ?? ''); ?></h2>
        <button id="btnGuardar" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
            Guardar Cambios
        </button>
    </div>

    <div class="mb-6">
        <button id="btnAgregarProveedor" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            Agregar Proveedor
        </button>
    </div>

    <div class="overflow-x-auto">
        <table id="remitoTable" class="min-w-full bg-white">
            <thead>
                <tr class="bg-gray-100">
                    <th colspan="5" class="px-4 py-2 text-center border">Remito Teórico</th>
                </tr>
                <tr class="bg-gray-50">
                    <th class="px-4 py-2 border">Proveedores</th>
                    <th class="px-4 py-2 border">Dólares</th>
                    <th class="px-4 py-2 border">Pesos</th>
                    <th class="px-4 py-2 border">T.C</th>
                    <th class="px-4 py-2 border">Totales</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="border px-4 py-2">Domingo R. Ghelfa</td>
                    <td class="border px-4 py-2" contenteditable="true"></td>
                    <td class="border px-4 py-2" contenteditable="true"></td>
                    <td class="border px-4 py-2">$ 970.50</td>
                    <td class="border px-4 py-2">$ 0.00</td>
                </tr>
                <tr>
                    <td class="border px-4 py-2">Aduana</td>
                    <td class="border px-4 py-2" contenteditable="true"></td>
                    <td class="border px-4 py-2" contenteditable="true"></td>
                    <td class="border px-4 py-2">$ 970.50</td>
                    <td class="border px-4 py-2">$ 0.00</td>
                </tr>
                <!-- Filas predefinidas -->
                <tr>
                    <td colspan="4" class="border px-4 py-2 text-right font-bold">Total:</td>
                    <td class="border px-4 py-2" id="totalGeneral">$ 0.00</td>
                </tr>
                <tr>
                    <td colspan="4" class="border px-4 py-2 text-right font-bold">Total USD:</td>
                    <td class="border px-4 py-2" id="totalUSD">USD 0.00</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal para agregar proveedor -->
<div id="proveedorModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-semibold mb-4">Agregar Proveedor</h3>
            <form id="proveedorForm">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Proveedor</label>
                    <select id="proveedorSelect" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        <option value="Domingo R. Ghelfa">Domingo R. Ghelfa</option>
                        <option value="Aduana">Aduana</option>
                        <option value="Transporte import">Transporte import</option>
                        <option value="ANMAT">ANMAT</option>
                        <option value="INAL (L.C)">INAL (L.C)</option>
                        <option value="Despachante Impo">Despachante Impo</option>
                        <option value="Fiscal (Puerto)">Fiscal (Puerto)</option>
                        <option value="Gestion Compra Impo">Gestion Compra Impo</option>
                        <option value="Comex Casareto">Comex Casareto</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Dólares</label>
                    <input type="number" id="dolares" step="0.01" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Pesos</label>
                    <input type="number" id="pesos" step="0.01" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                        Cancelar
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        Agregar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const TC = 970.50; // Tipo de cambio fijo
    let table = document.getElementById('remitoTable');
    
    // Función para calcular totales
    function calcularTotales() {
        let totalPesos = 0;
        let totalDolares = 0;
        
        // Recorrer todas las filas excepto las últimas dos (totales)
        const filas = table.querySelectorAll('tbody tr:not(:last-child):not(:nth-last-child(2))');
        filas.forEach(fila => {
            const dolares = parseFloat(fila.cells[1].textContent) || 0;
            const pesos = parseFloat(fila.cells[2].textContent) || 0;
            
            // Calcular total de la fila
            const totalFila = (dolares * TC) + pesos;
            fila.cells[4].textContent = `$ ${totalFila.toFixed(2)}`;
            
            totalDolares += dolares;
            totalPesos += totalFila;
        });
        
        // Actualizar totales generales
        document.getElementById('totalGeneral').textContent = `$ ${totalPesos.toFixed(2)}`;
        document.getElementById('totalUSD').textContent = `USD ${totalDolares.toFixed(2)}`;
    }
    
    // Agregar event listeners para celdas editables
    table.addEventListener('input', function(e) {
        if (e.target.matches('[contenteditable]')) {
            calcularTotales();
        }
    });
    
    // Modal de agregar proveedor
    const modal = document.getElementById('proveedorModal');
    const btnAgregar = document.getElementById('btnAgregarProveedor');
    
    btnAgregar.onclick = function() {
        modal.classList.remove('hidden');
    }
    
    function closeModal() {
        modal.classList.add('hidden');
    }
    
    // Manejar el formulario de nuevo proveedor
    document.getElementById('proveedorForm').onsubmit = function(e) {
        e.preventDefault();
        
        const proveedor = document.getElementById('proveedorSelect').value;
        const dolares = document.getElementById('dolares').value;
        const pesos = document.getElementById('pesos').value;
        
        // Crear nueva fila
        const newRow = table.insertRow(table.rows.length - 2);
        newRow.innerHTML = `
            <td class="border px-4 py-2">${proveedor}</td>
            <td class="border px-4 py-2" contenteditable="true">${dolares}</td>
            <td class="border px-4 py-2" contenteditable="true">${pesos}</td>
            <td class="border px-4 py-2">$ ${TC}</td>
            <td class="border px-4 py-2">$ 0.00</td>
        `;
        
        calcularTotales();
        closeModal();
        this.reset();
    }
    
    // Guardar cambios
    document.getElementById('btnGuardar').onclick = function() {
        // Aquí iría la lógica para guardar en la base de datos
        alert('Cambios guardados correctamente');
    }
    
    // Calcular totales iniciales
    calcularTotales();
});
</script>

<?php
$content = ob_get_clean();
include 'includes/layout.php';
?>