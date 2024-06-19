<?php

namespace App\Http\Controllers;

use App\Models\Instalacion;
use Illuminate\Http\Request;

class InstalacionController extends Controller
{
    public function index()
    {
        $ultimoRegistro = Instalacion::orderBy('id', 'desc')->first();

        return response()->json($ultimoRegistro);
    }
}
