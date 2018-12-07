<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Mascota extends Model
{
     protected $table = 'mascotas';
     protected $primaryKey='id';
     public $timestamps = false;
     
     public function raza(){
         return $this->belongsTo(Raza::class, 'id_raza','id');
     }
     public function usuario(){
         return $this->belongsTo(Usuario::class, 'id_usuario','id');
     }
     public function extravios(){
         return $this->hasMany(Extravio::class,'id_mascota','id');
     }
     public function ciudad(){
        return $this->belongsto(Ciudad::class,'id_ciudad','id');
     }
     public function fotografias(){
         return $this->hasMany(Fotografia::class, 'id_mascota','id');
     }
}
