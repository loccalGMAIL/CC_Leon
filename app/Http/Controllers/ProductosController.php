<?php

namespace App\Http\Controllers;
use App\Models\Proveedor;
use App\Models\Rto;

use Illuminate\Http\Request;

class ProductosController extends Controller
{
    public function index()
    {
        $proveedores = Proveedor::where('estadoProveedor', '1')->get();
        $titulo = 'Productos';
        return view('modules.productos.index', compact('titulo', 'proveedores'));
    }

    public function create()
    {
        return view('productos.create');
    }

    public function store(Request $request)
    {
        // Aquí puedes manejar la lógica para almacenar el producto
        // Por ejemplo, guardar en la base de datos

        return redirect()->route('productos.index')->with('success', 'Producto creado exitosamente.');
    }

    public function show($id)
    {
        return view('productos.show', compact('id'));
    }

    public function edit($id)
    {
        return view('productos.edit', compact('id'));
    }

    public function update(Request $request, $id)
    {
        // Aquí puedes manejar la lógica para actualizar el producto
        // Por ejemplo, actualizar en la base de datos

        return redirect()->route('productos.index')->with('success', 'Producto actualizado exitosamente.');
    }

    public function destroy($id)
    {
        // Aquí puedes manejar la lógica para eliminar el producto
        // Por ejemplo, eliminar de la base de datos

        return redirect()->route('productos.index')->with('success', 'Producto eliminado exitosamente.');
    }
}
