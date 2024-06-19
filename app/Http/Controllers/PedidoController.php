<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use Illuminate\Http\Request;

class PedidoController extends Controller
{
    public function index()
    {
        $pedidos = Pedido::all();
        return response()->json($pedidos);
    }



    /**
     * Obtiene todos los pedidos de un usuario
     */
    public function obtenerPedidosUsuario(Request $request)
    {
        // Validar la entrada para asegurarse de que 'usuario_id' está presente
        $request->validate([
            'id' => 'required|integer',
        ]);

        $usuario_id = $request->input('id');

        // Buscar los pedidos del usuario con el id proporcionado
        $pedidos = Pedido::where('id_cliente', $usuario_id)->get();

        if ($pedidos->isEmpty()) {
            return response()->json("false");
        } else {
            return response()->json($pedidos);
        }
    }


    public function obtenerNumeroPedidosUsuario(Request $request)
    {
        // Validar la entrada para asegurarse de que 'usuario_id' está presente
        $request->validate([
            'id' => 'required|integer',
        ]);

        $usuario_id = $request->input('id');

        // Contar los pedidos del usuario con el id proporcionado
        $numeroDePedidos = Pedido::where('id_cliente', $usuario_id)->count();

        return response()->json($numeroDePedidos);
    }
}
