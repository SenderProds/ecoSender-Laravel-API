<?php

use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\InstalacionController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\SolicitudController;
use App\Http\Controllers\UsuarioController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductoController;
use App\Http\Middleware\Cors;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('api/v1')->middleware([\App\Http\Middleware\Cors::class])->group(function () {
    Route::get('/productos', [ProductoController::class, 'index']);
    Route::get('/categorias', [CategoriaController::class, 'index']);
    Route::get('/solicitudes', [SolicitudController::class, 'index']);
    Route::get('/productosCategoria', [ProductoController::class, 'obtenerProductosPorCategoria']);
    Route::get('/usuarios', [UsuarioController::class, 'index']);
    Route::get('/obtenerDatosUsuario', [UsuarioController::class, 'obtenerDatosUsuario']);
    Route::get('/empleados', [EmpleadoController::class, 'index']);
    Route::get('/pedidos', [PedidoController::class, 'index']);
    Route::get('/instalaciones', [InstalacionController::class, 'index']);
    

    Route::post('/obtenerIdUsuario', [UsuarioController::class, 'obtenerIdUsuario']);
    Route::post('/agregarEmpleado', [EmpleadoController::class, 'agregarEmpleado']);
    Route::post('/agregarUsuario', [UsuarioController::class, 'agregarUsuario']);
    Route::post('/comprobarUsuario', [UsuarioController::class, 'comprobarUsuario']);
    Route::post('/comprobarJWT', [UsuarioController::class, 'comprobarJWT']);
    Route::post('/insertarDatosUsuario', [UsuarioController::class, 'insertarDatosUsuario']);
    Route::post('/comprobarGoogleId', [UsuarioController::class, 'comprobarGoogleId']);
    Route::post('/obtenerPedidosUsuario', [PedidoController::class, 'obtenerPedidosUsuario']);
    Route::post('/obtenerNumeroPedidosUsuario', [PedidoController::class, 'obtenerNumeroPedidosUsuario']);
    Route::post('/comprobarDatos', [UsuarioController::class, 'comprobarDatos']);
});
