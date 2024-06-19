<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    protected $table = 'usuarios';

    protected $primaryKey = 'idUsuario';

    protected $hidden = ['clave'];

    
    public $timestamps = false;

    // Definir los campos que se pueden asignar en masa
    protected $fillable = ['googleId', 'Nombre', 'Apellidos', 'DNI', 'Telefono', 'Direccion', 'ciudad', 'codigoPostal', 'nombreUsuario', 'clave','correo','imagen'];

}
