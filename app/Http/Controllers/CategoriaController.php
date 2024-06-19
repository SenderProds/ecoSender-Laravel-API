<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


class CategoriaController extends Controller
{
    public function index()
    {
        $categorias = \App\Models\Categoria::all();
        return response()->json($categorias);
    }
}
