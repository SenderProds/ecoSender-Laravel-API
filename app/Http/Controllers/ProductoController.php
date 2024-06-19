<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductoController extends Controller
{
    public function index(){
        $productos = \App\Models\Producto::all(); // Usa el nombre de clase completo
        return response()->json($productos);
    }

    public function obtenerProductosPorCategoria(Request $request){
        $categoriaId = $request->get('categoria');

        $productos = \App\Models\Producto::where('categoria', $categoriaId)->get();

        return response()->json($productos);
    }
}
