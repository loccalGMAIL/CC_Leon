<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Proveedor;
use App\Models\Rto;
use App\Models\Camion;


class RtoController extends Controller
{
    public function index()
    {
        $titulo = 'Remitos';
        $items = Rto::with(['proveedor','camion'])->get();
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

}
