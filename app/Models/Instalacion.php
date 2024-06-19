<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Instalacion extends Model
{
    protected $table= "instalaciones";

    public $timestamps = false;

    protected $fillable = ['voltaje', 'fecha', 'hora'];


}
