<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Informes extends Controller
{
    public function index()
    {
        $titulo = 'Informes';
        return view('modules.informes.index', compact('titulo'));

    }
}
