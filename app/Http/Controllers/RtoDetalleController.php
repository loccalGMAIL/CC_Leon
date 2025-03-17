<?php

namespace App\Http\Controllers;

use App\Models\RtoDetalle;
use App\Models\ElementoRto;
use App\Models\Rto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;

class RtoDetalleController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validar la entrada
        $validator = Validator::make($request->all(), [
            'rto_id' => 'required|exists:rto,id',
            'elementoRto_id' => 'required|exists:elementos_rto,id',
            'valorDolaresRtoTeorico' => 'nullable|numeric',
            'valorPesosRtoTeorico' => 'nullable|numeric',
            'TC_RtoTeorico' => 'nullable|numeric',
            'descripcionNuevoElemento' => 'nullable|string|max:255',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }
    
        try {
            // Calcular el subtotal
            $subtotal = 0;
            if ($request->filled('valorDolaresRtoTeorico') && $request->filled('TC_RtoTeorico')) {
                $subtotal = $request->valorDolaresRtoTeorico * $request->TC_RtoTeorico;
            } elseif ($request->filled('valorPesosRtoTeorico')) {
                $subtotal = $request->valorPesosRtoTeorico;
            }
    
            // Crear el nuevo detalle de RTO
            $rtoDetalle = RtoDetalle::create([
                'rto_id' => $request->rto_id,
                'elementoRto_id' => $request->elementoRto_id,
                'valorDolaresRtoTeorico' => $request->valorDolaresRtoTeorico,
                'valorPesosRtoTeorico' => $request->valorPesosRtoTeorico,
                'TC_RtoTeorico' => $request->TC_RtoTeorico,
                //'subTotalRtoTeorico' => $subtotal,
            ]);
    
            // Actualizar el total en la tabla de RTO
            $this->actualizarTotalRto($request->rto_id);
    
            // Agregar detalles adicionales a la respuesta que puedan ser útiles
            return response()->json([
                'success' => true,
                'message' => 'Elemento agregado correctamente al remito',
                'rtoDetalle' => $rtoDetalle
            ]);
        } catch (\Exception $e) {
            // \Log::error('Error al crear detalle de RTO: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al agregar elemento: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Actualiza el total del RTO sumando todos los subtotales de los detalles.
     *
     * @param int $rtoId
     * @return void
     */
    private function actualizarTotalRto($rtoId)
    {
        $total = RtoDetalle::where('rto_id', $rtoId)
            ->sum('subTotalRtoTeorico');

        \App\Models\Rto::where('id', $rtoId)
            ->update(['totalTempRto' => $total]);
    }

    public function actualizarCampo(Request $request)
    {
        try {
            $id = $request->input('id');
            $field = $request->input('field');
            $value = $request->input('value');

            // Validar datos
            if (!in_array($field, ['valorDolaresRtoTeorico', 'valorPesosRtoTeorico', 'TC_RtoTeorico', 'totalFinalRto'])) {
                return response()->json(['success' => false, 'message' => 'Campo no válido']);
            }

            if ($field === 'totalFinalRto') {
                // Si es el total final, actualizar directamente en la tabla Rto
                $rto = Rto::findOrFail($id);
                $rto->totalFinalRto = $value;
                $rto->save();

                $totalTeorico = $rto->totalTempRto;
                $totalFinal = $value;
                $diferencia = $totalFinal - $totalTeorico;

                return response()->json([
                    'success' => true,
                    'totalTeorico' => $totalTeorico,
                    'totalFinal' => $totalFinal,
                    'diferencia' => $diferencia
                ]);
            }

            // Si no es el total final, continuar con la lógica existente para otros campos
            $detalle = RtoDetalle::findOrFail($id);

            // Actualizar el campo
            $detalle->$field = $value;

            // Si se actualiza el tipo de cambio o los dólares, recalcular el subtotal
            if ($field == 'valorDolaresRtoTeorico' || $field == 'TC_RtoTeorico') {
                $subtotal = $detalle->valorDolaresRtoTeorico * $detalle->TC_RtoTeorico;
                $detalle->subTotalRtoTeorico = $subtotal;
            }

            $detalle->save();

            // Recalcular totales
            $rtoId = $detalle->rto_id;
            $totalTeorico = RtoDetalle::where('rto_id', $rtoId)->sum('subTotalRtoTeorico');
            $totalFinal = RtoDetalle::where('rto_id', $rtoId)->sum('subTotalRtoReal');

            $diferencia = $totalFinal - $totalTeorico;

            // Actualizar el remito principal
            $rto = Rto::find($rtoId);
            $rto->totalTempRto = $totalTeorico;
            $rto->totalFinalRto = $totalFinal;
            $rto->save();

            return response()->json([
                'success' => true,
                'subtotal' => $detalle->subTotalRtoTeorico,
                'totalTeorico' => $totalTeorico,
                'totalFinal' => $totalFinal,
                'diferencia' => $diferencia
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function obtenerValor($id, $field)
    {
        try {
            $detalle = RtoDetalle::findOrFail($id);

            if (!in_array($field, ['valorDolaresRtoTeorico', 'valorPesosRtoTeorico', 'TC_RtoTeorico'])) {
                return response()->json(['success' => false, 'message' => 'Campo no válido']);
            }

            return response()->json([
                'success' => true,
                'value' => $detalle->$field
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function delete($id)
{
    try {
        // Encuentra el detalle a eliminar
        $detalle = RtoDetalle::findOrFail($id);
        
        // Elimina el detalle
        $detalle->delete();
        
        // Responde con JSON
        return response()->json([
            'success' => true,
            'message' => 'Elemento eliminado correctamente'
        ]);
    } catch (\Exception $e) {
        // Registra el error para diagnóstico
        // \Log::error('Error al eliminar detalle: ' . $e->getMessage());
        
        // Retorna respuesta JSON con error
        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }
}
}