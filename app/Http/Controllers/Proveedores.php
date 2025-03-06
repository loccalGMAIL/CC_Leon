<?php

namespace App\Http\Controllers;

use App\Models\Proveedor;
use Illuminate\Http\Request;

class Proveedores extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $titulo = 'Proveedores';
        $items = Proveedor::all();
        return view('modules.proveedores.index', compact('titulo', 'items'));
    }

    public function indexCamiones(){
        $titulo = 'Camiones';
        return view('modules.proveedores.camiones.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $titulo = 'Nuevo Proveedor';
        return view('modules.proveedores.create', compact('titulo'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $proveedor = new Proveedor();
        $proveedor->nombreProveedor = $request->nombreProveedor;
        $proveedor->dniProveedor = $request->dniProveedor;
        $proveedor->razonSocialProveedor = $request->razonSocialProveedor;
        $proveedor->cuitProveedor = $request->cuitProveedor;
        $proveedor->telefonoProveedor = $request->telefonoProveedor;
        $proveedor->mailProveedor = $request->mailProveedor;
        $proveedor->direccionProveedor = $request->direccionProveedor;
        $proveedor->save();
        return redirect()->route('proveedores');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $titulo = 'Editar Proveedor';
        $item = Proveedor::find($id);
        return view('modules.proveedores.edit', compact('titulo', 'item'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $proveedor = Proveedor::find($id);
        $proveedor->nombreProveedor = $request->nombreProveedor;
        $proveedor->dniProveedor = $request->dniProveedor;
        $proveedor->razonSocialProveedor = $request->razonSocialProveedor;
        $proveedor->cuitProveedor = $request->cuitProveedor;
        $proveedor->telefonoProveedor = $request->telefonoProveedor;
        $proveedor->mailProveedor = $request->mailProveedor;
        $proveedor->direccionProveedor = $request->direccionProveedor;
        $proveedor->save();
        return redirect()->route('proveedores');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
