<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Dotenv\Dotenv;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{

    /**
     * Obtienne todos los usuarios
     */
    public function index()
    {
        $usuarios = Usuario::all();

        return response()->json($usuarios);
    }


    /**
     * Obtiene los datos el usuario pasando el id
     */
    public function obtenerDatosUsuario(Request $request)
    {

        $id = $request->get('id');
        $usuario = Usuario::select('Nombre', 'Apellidos', 'DNI', 'Telefono','ciudad', 'Direccion', 'codigoPostal', 'imagen', 'nombreUsuario')->find($id);

        if (!$usuario) {
            return response()->json("false");

        }

        return response()->json($usuario);
    }


    /**
     * Obtiene el id de usuario pasando el jwt o googleId
     */
    public function obtenerIdUsuario(Request $request)
    {
        $dotenv = Dotenv::createImmutable(base_path());
        $dotenv->load();

        $secretKey = $_ENV['SECRET_KEY']; // Obtener la clave secreta de configuración
    
        $jwt = $request->post('jwt');
        $googleId = $request->post('googleId');
    
        
        if ($jwt) {
            
            try {
                // Decodificar el token JWT
                $decoded = JWT::decode($jwt, new \Firebase\JWT\Key($secretKey, 'HS256'));
                
                // Obtener datos del usuario codificados en el token
                $nombreUsuario = $decoded->user_id;
                $clave = $decoded->pass;

                //return response()->json($clave);
    
                // Buscar al usuario por nombre de usuario y verificar la clave
                $usuario = Usuario::where('nombreUsuario', $nombreUsuario)->first();
    
                if ($usuario && Hash::check($clave, $usuario->clave)) {
                    // Si se encuentra el usuario y la clave coincide (la clave está almacenada en hash en la base de datos)
                    return response()->json($usuario->idUsuario);
                } else {
                    // Si no se encuentra el usuario o la clave no coincide
                    return response()->json("false");
                }
    
            } catch (\Exception $e) {
                return response()->json($e->getMessage(), 401);
            }
    
        } elseif ($googleId) {
            // Si se proporciona un googleId, buscar al usuario por ese googleId en la base de datos
            $usuario = Usuario::where('googleId', $googleId)->first();
    
            if ($usuario) {
                return response()->json($usuario->idUsuario);
            } else {
                return response()->json("false");
            }
    
        } else {
            // Si no se proporciona ni token JWT ni googleId
            return response()->json("false");
        }
    }

    /**
     * Agrega un nuevo usuario
     */
    public function agregarUsuario(Request $request)
    {
        $datosValidados = $request->validate([
            'correo' => 'required|email|max:255',
            'nombreUsuario' => 'required|string|max:255|unique:usuarios',
            'clave' => 'required|string|min:8'
        ]);

        $usuario = new Usuario();
        $usuario->correo = $datosValidados['correo'];
        $usuario->nombreUsuario = $datosValidados['nombreUsuario'];
        $usuario->clave = Hash::make($datosValidados['clave']);
        $usuario->save();

        return response()->json("Datos Insertados");
    }


    /**
     * Comprueba si el usuario logeado es correcto
     */
    public function comprobarUsuario(Request $request)
    {
        $secretKey = $_ENV['SECRET_KEY'];
        $datosValidados = $request->validate([
            'nombreUsuario' => 'required|string|max:255',
            'clave' => 'required|string|min:8'
        ]);

        $usuario = Usuario::where('nombreUsuario', $datosValidados['nombreUsuario'])->first();


        if ($usuario && Hash::check($datosValidados['clave'], $usuario->clave)) {
            $payload = array(
                "user_id" => $datosValidados['nombreUsuario'],
                "pass" => $datosValidados['clave']
            );

            $token = JWT::encode($payload, $secretKey, 'HS256');
            return response()->json($token);
        } else {
            return response()->json("false");
        }
        return response()->json("Hasta aqui llega");

    }


    public function comprobarJWT(Request $request)
    {

        $dotenv = Dotenv::createImmutable(base_path());
        $dotenv->load();

        $secretKey = $_ENV['SECRET_KEY']; // Obtener la clave secreta de configuración

        $jwt = $request->input('jwt'); // Obtener el JWT desde la solicitud

        try {
            $decoded = JWT::decode($jwt, new \Firebase\JWT\Key($secretKey, 'HS256')); // Decodificar el JWT con la clave secreta y algoritmo HS256
            $user_id = $decoded->user_id; // Obtener el ID de usuario decodificado del payload
            $pass = $decoded->pass; // Obtener la contraseña decodificada del payload

            // Verificar si existe un usuario con el nombre de usuario proporcionado
            $usuario = Usuario::where('nombreUsuario', $user_id)->first();

            if ($usuario && Hash::check($pass, $usuario->clave)) {
                return response()->json($usuario->id); // Devolver el ID del usuario autenticado
            } else {
                return response()->json("false"); // Si no se encuentra el usuario o la clave no coincide
            }
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 401); // Capturar y devolver cualquier error al decodificar el JWT
        }
    }



    public function insertarDatosUsuario (Request $request){
        $datosValidados = $request->validate([
            'idUsuario' => 'required|integer', // Validar que idUsuario sea requerido y sea un entero
            'nombre' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'dni' => 'required|string|max:20',
            'telefono' => 'nullable|string|max:20',
            'direccion' => 'nullable|string|max:255',
            'ciudad' => 'nullable|string|max:255',
            'codigoPostal' => 'nullable|string|max:20',
        ]);

        $idUsuario = $datosValidados['idUsuario'];

        // Buscar al usuario por su ID
        $usuario = Usuario::findOrFail($idUsuario);

        // Actualizar los campos del usuario
        $usuario->Nombre = $datosValidados['nombre'];
        $usuario->Apellidos = $datosValidados['apellidos'];
        $usuario->DNI = $datosValidados['dni'];
        $usuario->Telefono = $datosValidados['telefono'];
        $usuario->Direccion = $datosValidados['direccion'];
        $usuario->ciudad = $datosValidados['ciudad'];
        $usuario->codigoPostal = $datosValidados['codigoPostal'];

        // Guardar los cambios en la base de datos
        $usuario->save();

        // Devolver una respuesta JSON o cualquier otra respuesta apropiada
        return response()->json(['message' => 'Usuario actualizado correctamente']);
    
    }


    public function comprobarGoogleId(Request $request){
        $googleId = $request->input('googleId');

        if ($googleId) {
            $usuario = Usuario::where('googleId', $googleId)->first();

            if ($usuario) {
                return response()->json($usuario->idUsuario);
            } else {
                return response()->json("false");
            }
        } else {
            return response()->json("false");
        }
    }



    public function comprobarDatos(Request $request)
    {
        $dotenv = Dotenv::createImmutable(base_path());
        $dotenv->load();

        $secretKey = $_ENV['SECRET_KEY'];
        $jwt = $request->post('jwt');
        $googleId = $request->post('googleId');

        if ($jwt) {
            try {
                $decoded = JWT::decode($jwt, new \Firebase\JWT\Key($secretKey, 'HS256'));
                $nombreUsuario = $decoded->user_id;
                $clave = $decoded->pass;

                $usuario = Usuario::where('nombreUsuario', $nombreUsuario)->first();

                if ($usuario && Hash::check($clave, $usuario->clave)) {
                    if (empty($usuario->DNI) || empty($usuario->Telefono) || empty($usuario->Direccion) || empty($usuario->codigoPostal) || empty($usuario->Nombre) || empty($usuario->Apellidos)) {
                        return response()->json($usuario->idUsuario);
                    } else {
                        return response()->json("true");
                    }
                } else {
                    return response()->json("false");
                }
            } catch (\Exception $e) {
                return response()->json($e->getMessage(), 401);
            }
        } elseif ($googleId) {
            $usuario = Usuario::where('googleId', $googleId)->first();

            if ($usuario) {
                if (empty($usuario->DNI) || empty($usuario->Telefono) || empty($usuario->Direccion) || empty($usuario->codigoPostal) || empty($usuario->Nombre) || empty($usuario->Apellidos)) {
                    return response()->json($usuario->idUsuario);
                } else {
                    return response()->json("true");
                }
            } else {
                return response()->json("false");
            }
        } else {
            return response()->json("No se ha recibido ningun post");
        }
    }
}

