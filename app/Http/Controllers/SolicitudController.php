<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Solicitud;

class SolicitudController extends Controller
{

    public function index()
    {
        $solicitudes = Solicitud::all();
        return response()->json($solicitudes);
    }

}
