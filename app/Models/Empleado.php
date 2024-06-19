<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    protected $table = 'empleados';

    // Desactivar la gestión automática de timestamps
    public $timestamps = false;

    // Definir los campos que se pueden asignar en masa
    protected $fillable = ['correo', 'nombreEmpleado', 'clave', 'nif', 'telefono', 'rol'];
}
