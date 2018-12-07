<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Ciudad extends Model
{
    protected $table='ciudades';
    protected $primarykey='id';

    public function estado(){
         return $this->belongsTo(Estado::class, 'id_estado','id'); 
    }
    public function mascotas(){
    	return $this->hasmany(Mascota::class,'id_ciudad','id');
    }
    public function extravios(){
    	return $this->hasmany(Extravio::class,'id_extravio','id');
    }
}
