<?php

namespace App\Http\Controllers;

use App\Models\rto;
use App\Models\Camion;
use App\Models\Proveedor;
use App\Models\Reclamo;
use App\Models\Observacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Dashboard extends Controller
{
    public function index()
    {
        $titulo = 'Dashboard';

        $proveedores = Proveedor::where('estadoProveedor', '1')->get();

        // Métricas generales
        $totalRemitos = rto::count();
        $remitosEnEspera = rto::where('estado', 'Espera')->count();
        $remitosConDeuda = rto::where('estado', 'Deuda')->count();
        $remitosPagados = rto::where('estado', 'Pagado')->count();

        // Remitos recientes
        $remitosRecientes = rto::with('proveedor')
            ->orderBy('fechaIngresoRto', 'desc')
            ->limit(5)
            ->get();

        // Conteos adicionales
        $totalProveedores = Proveedor::where('estadoProveedor', '1')->count();
        $totalCamiones = Camion::count();
        $totalReclamos = Reclamo::where('estadoReclamoRto', 'pendiente')->count();
        $totalObservaciones = Observacion::count();


        // Datos para el gráfico de remitos por mes (últimos 6 meses)
        $remitosPorMes = rto::select(
            DB::raw('MONTH(fechaIngresoRto) as mes'),
            DB::raw('YEAR(fechaIngresoRto) as año'),
            DB::raw('COUNT(*) as total')
        )
            ->whereRaw('fechaIngresoRto >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)')
            ->groupBy('año', 'mes')
            ->orderBy('año', 'asc')
            ->orderBy('mes', 'asc')
            ->get();

        // Formateamos los datos para el gráfico de remitos por mes
        $mesesLabels = [];
        $mesesData = [];

        foreach ($remitosPorMes as $registro) {
            $nombreMes = date('M', mktime(0, 0, 0, $registro->mes, 10));
            $mesesLabels[] = $nombreMes . ' ' . $registro->año;
            $mesesData[] = $registro->total;
        }

        // Facturación total por proveedor
        $facturacionPorProveedor = rto::select(
            'proveedores.nombreProveedor',
            DB::raw('SUM(rto.totalFinalRto) as facturacion')
        )
            ->join('proveedores', 'rto.proveedores_id', '=', 'proveedores.id')
            ->whereNotNull('rto.totalFinalRto')
            ->groupBy('proveedores.nombreProveedor')
            ->orderBy('facturacion', 'desc')
            ->limit(5)
            ->get();

        $proveedoresLabels = $facturacionPorProveedor->pluck('nombreProveedor')->toArray();
        $proveedoresData = $facturacionPorProveedor->pluck('facturacion')->toArray();
        return view('modules.dashboard.home', compact(
            'titulo',
            'totalRemitos',
            'remitosEnEspera',
            'remitosConDeuda',
            'remitosPagados',
            'totalProveedores',
            'totalCamiones',
            'totalReclamos',
            'totalObservaciones',
            'remitosRecientes',
            'mesesLabels',
            'mesesData',
            'proveedoresLabels',
            'proveedoresData',
            'proveedores'
        ));
    }
}