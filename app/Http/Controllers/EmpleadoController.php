<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use Illuminate\Http\Request;

class EmpleadoController extends Controller
{
    public function index(){
        $empleados = Empleado::all();
        return response()->json($empleados);
    }



    public function agregarEmpleado(Request $request){

        // Validar los datos del formulario
        $datosValidados = $request->validate([
            'correo' => 'required|email|max:255|unique:empleados,correo',
            'nombreEmpleado' => 'required|string|max:255',
            'clave' => 'required|string|min:8', // Ajusta las reglas de validación según tus necesidades
            'nif' => 'required|string|max:20|unique:empleados,nif',
            'telefono' => 'required|string|max:15',
            'rol' => 'required|string|max:50',
        ]);

        $empleado = new Empleado();

        $empleado->correo = $datosValidados['correo'];
        $empleado->nombreEmpleado = $datosValidados['nombreEmpleado'];
        $empleado->clave = $datosValidados['clave'];
        $empleado->nif = $datosValidados['nif'];
        $empleado->telefono = $datosValidados['telefono'];
        $empleado->rol = $datosValidados['rol'];
        $empleado->save();

        return response()->json('Datos Insertados');

    }
}
