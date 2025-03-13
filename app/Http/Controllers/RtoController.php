<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Proveedor;
use App\Models\Rto;
use App\Models\Camion;
use App\Models\ElementoRto;
use App\Models\RtoDetalle;

class RtoController extends Controller
{
    public function index()
    {
        $titulo = 'Remitos';
        $items = Rto::with(['proveedor', 'camion'])->get();
        $proveedores = Proveedor::where('estadoProveedor', '1')->get();
        return view('modules.rto.index', compact('titulo', 'items', 'proveedores'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $request->validate([
            'fechaIngresoRto' => 'required|date',
            'nroFacturaRto' => 'required|string|max:50',
            'idProveedor' => 'required|exists:proveedores,id',
            'idCamion' => 'required|exists:camiones,id',
        ]);

        // Crear el remito
        $remito = new Rto();
        $remito->fechaIngresoRto = $request->fechaIngresoRto;
        $remito->nroFacturaRto = $request->nroFacturaRto;
        $remito->proveedores_id = $request->idProveedor;
        $remito->camiones_id = $request->idCamion;
        $remito->save();

        return redirect()->route('remitos')
            ->with('success', 'Remito creado correctamente');
    }

    public function edit($id)
    {
        // Obtener el remito con sus relaciones
        $items = Rto::with(['proveedor', 'camion'])->findOrFail($id);
        
        // Cargar los detalles del remito
        $detalles = RtoDetalle::with('elemento')
                    ->where('rto_id', $id)
                    ->get();
        
        $elementosRto = ElementoRto::all();
        
        // Obtener todos los proveedores para el selector
        $proveedores = Proveedor::where('estadoProveedor', '1')
                      ->orderBy('razonSocialProveedor')
                      ->get();
        
        return view('modules.rto.editar', [
            'titulo' => 'Editar Remito',
            'items' => $items,
            'detalles' => $detalles,
            'proveedores' => $proveedores,
            'elementosRto' => $elementosRto
        ]);
    }

}
