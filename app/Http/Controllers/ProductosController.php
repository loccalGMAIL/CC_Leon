<?php

namespace App\Http\Controllers;
use App\Models\Proveedor;
use App\Models\Cotizacion;
use App\Models\Rto;

use Illuminate\Http\Request;

class ProductosController extends Controller
{
    public function index()
    {
        $titulo = 'Productos';
        $proveedores = Proveedor::where('estadoProveedor', '1')->get();
        $cotizacion = Cotizacion::latest()->first();
        return view('modules.productos.index', compact('titulo', 'proveedores', 'cotizacion'));
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

    public function guardarCotizacion(Request $request)
    {
        // Validar el valor recibido
        $request->validate([
            'cotizacion' => 'required|numeric|min:0',
        ]);

        // Buscar la cotización existente o crear una nueva
        $cotizacion = Cotizacion::first();
        if (!$cotizacion) {
            $cotizacion = new Cotizacion();
        }

        $cotizacion->precioDolar = $request->cotizacion;
        $cotizacion->save();

        // Configurar SweetAlert para la confirmación
        return redirect()->back()->with('swal_success', 'Cotización del dólar actualizada correctamente');
    }

    public function actualizarCotizacionExterna(Request $request)
    {
        try {
            // Obtener la cotización real del Banco Nación Argentina
            $nuevaCotizacion = $this->obtenerCotizacionBancoNacion();

            // Buscar la cotización existente o crear una nueva
            $cotizacion = Cotizacion::first();
            if (!$cotizacion) {
                $cotizacion = new Cotizacion();
            }

            // Actualizar el valor
            $cotizacion->precioDolar = $nuevaCotizacion;
            $cotizacion->save();

            return redirect()->back()->with('swal_success', "Cotización actualizada a $nuevaCotizacion (Banco Nación)");
        } catch (\Exception $e) {
            \Log::error('Error al actualizar cotización externa: ' . $e->getMessage());
            return redirect()->back()->with('swal_error', 'Error al actualizar la cotización: ' . $e->getMessage());
        }
    }

    /**
     * @return float La cotización del dólar oficial (venta)
     * @throws \Exception Si hay algún error en la consulta
     */
    private function obtenerCotizacionBancoNacion()
    {
        // Configuración de la petición
        $opciones = [
            'http' => [
                'method' => 'GET',
                'header' => [
                    'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
                    'Accept: application/json'
                ]
            ]
        ];

        // Crear el contexto para la petición
        $contexto = stream_context_create($opciones);

        // Realizar la petición a la API
        $respuesta = @file_get_contents('https://dolarapi.com/v1/cotizaciones/oficial', false, $contexto);

        // Verificar si la petición fue exitosa
        if ($respuesta === false) {
            // Si falla, intentamos con el método alternativo
            return $this->obtenerCotizacionBancoNacionAlternativa();
        }

        // Decodificar la respuesta JSON
        $data = json_decode($respuesta, true);

        // Verificar que la respuesta contenga los datos esperados
        if (!isset($data['venta'])) {
            throw new \Exception('Formato de respuesta inesperado: no se encontró el valor de venta');
        }

        // Retornar el valor de venta
        return (float) $data['venta'];
    }

    private function obtenerCotizacionBancoNacionAlternativa()
    {
        // Configuración de la petición
        $opciones = [
            'http' => [
                'method' => 'GET',
                'header' => [
                    'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36'
                ]
            ]
        ];

        // Crear el contexto para la petición
        $contexto = stream_context_create($opciones);

        // Realizar la petición a la página del Banco Nación
        $html = @file_get_contents('https://www.bna.com.ar/Personas', false, $contexto);

        if ($html === false) {
            throw new \Exception('No se pudo acceder a la página del Banco Nación');
        }

        // Buscamos la tabla de cotizaciones
        preg_match('/<table class="table cotizacion">(.*?)<\/table>/s', $html, $matches);

        if (empty($matches)) {
            throw new \Exception('No se pudo encontrar la tabla de cotizaciones en la página');
        }

        // Buscamos específicamente la fila del dólar y el valor de venta
        preg_match('/Dolar U\.S\.A.*?<td>(.*?)<\/td>.*?<td>(.*?)<\/td>/s', $matches[1], $cotizacion);

        if (empty($cotizacion) || !isset($cotizacion[2])) {
            throw new \Exception('No se pudo encontrar la cotización del dólar en la tabla');
        }

        // Limpiamos y convertimos el valor a float
        $valorVenta = str_replace(',', '.', trim(strip_tags($cotizacion[2])));

        return (float) $valorVenta;
    }
}
