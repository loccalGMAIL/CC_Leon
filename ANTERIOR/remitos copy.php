<?php
$pageTitle = "Remitos";

// Simular array de remitos (esto luego vendría de la base de datos)
$remitos = [
    ['id' => 'R001', 'fecha' => '2024-03-15', 'factura' => 'F-12345', 'reclamos' => 'Ninguno', 'observaciones' => 'Entrega completa'],
    ['id' => 'R002', 'fecha' => '2024-03-16', 'factura' => 'F-12346', 'reclamos' => 'Faltante', 'observaciones' => 'Falta 1 caja']
];

// Procesar el formulario si se envió
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_remito') {
    $nuevo_remito = [
        'id' => $_POST['nro_remito'],
        'fecha' => $_POST['fecha'],
        'factura' => $_POST['nro_factura'],
        'reclamos' => $_POST['reclamos'],
        'observaciones' => $_POST['observaciones']
    ];
    
    // Aquí iría la lógica para guardar en la base de datos
    array_push($remitos, $nuevo_remito);
    
    // Si es una petición AJAX, devolver el nuevo remito en formato JSON
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
        echo json_encode(['success' => true, 'remito' => $nuevo_remito]);
        exit;
    }
}

ob_start();
?>

<!-- Modal -->
<div id="nuevoRemitoModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-semibold mb-4">Nuevo Remito</h3>
            <form id="remitoForm" method="POST">
                <input type="hidden" name="action" value="add_remito">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nº Remito</label>
                        <input type="text" name="nro_remito" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Fecha</label>
                        <input type="date" name="fecha" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nº Factura</label>
                        <input type="text" name="nro_factura" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Reclamos</label>
                        <select name="reclamos" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="Ninguno">Ninguno</option>
                            <option value="Faltante">Faltante</option>
                            <option value="Dañado">Dañado</option>
                            <option value="Otro">Otro</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Observaciones</label>
                        <textarea name="observaciones" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" rows="2"></textarea>
                    </div>
                </div>
                <div class="flex justify-end gap-3 mt-4">
                    <button type="button" onclick="closeModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                        Cancelar
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Contenido principal -->
<div class="flex justify-between items-center mb-6">
    <h3 class="text-lg font-semibold">Lista de Remitos</h3>
    <button onclick="openModal()" class="bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center hover:bg-blue-700">
        <span class="mr-2">+</span>
        Nuevo Remito
    </button>
</div>

<div class="bg-white rounded-lg shadow">
    <table class="min-w-full" id="remitosTable">
        <thead>
            <tr class="border-b">
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Nº Remito
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Fecha de Ingreso
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Nº Factura
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Reclamos
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Observaciones
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Acciones
                </th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            <?php foreach ($remitos as $remito): ?>
            <tr>
                <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($remito['id']); ?></td>
                <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($remito['fecha']); ?></td>
                <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($remito['factura']); ?></td>
                <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($remito['reclamos']); ?></td>
                <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($remito['observaciones']); ?></td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex space-x-2">
                        <button class="text-blue-600 hover:text-blue-800">📄</button>
                        <button class="text-green-600 hover:text-green-800">📊</button>
                        <button class="text-yellow-600 hover:text-yellow-800">💰</button>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
function openModal() {
    document.getElementById('nuevoRemitoModal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('nuevoRemitoModal').classList.add('hidden');
}

// Manejar el envío del formulario con AJAX
document.getElementById('remitoForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch('remitos.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Crear nueva fila en la tabla
            const tbody = document.querySelector('#remitosTable tbody');
            const newRow = document.createElement('tr');
            newRow.innerHTML = `
                <td class="px-6 py-4 whitespace-nowrap">${data.remito.id}</td>
                <td class="px-6 py-4 whitespace-nowrap">${data.remito.fecha}</td>
                <td class="px-6 py-4 whitespace-nowrap">${data.remito.factura}</td>
                <td class="px-6 py-4 whitespace-nowrap">${data.remito.reclamos}</td>
                <td class="px-6 py-4 whitespace-nowrap">${data.remito.observaciones}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex space-x-2">
                        <button class="text-blue-600 hover:text-blue-800">📄</button>
                        <button class="text-green-600 hover:text-green-800">📊</button>
                        <button class="text-yellow-600 hover:text-yellow-800">💰</button>
                    </div>
                </td>
            `;
            tbody.appendChild(newRow);
            
            // Cerrar modal y limpiar formulario
            closeModal();
            this.reset();
        }
    })
    .catch(error => console.error('Error:', error));
});

// Cerrar modal cuando se hace clic fuera de él
window.onclick = function(event) {
    const modal = document.getElementById('nuevoRemitoModal');
    if (event.target == modal) {
        closeModal();
    }
}
</script>

<?php
$content = ob_get_clean();
include 'includes/layout.php';
?>