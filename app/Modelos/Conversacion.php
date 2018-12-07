<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Conversacion extends Model
{
   protected $table = 'conversaciones';
     protected $primaryKey='id';
     public $timestamps = false;

     public function mensajes(){
         return $this->hasMany(Mensaje::class, 'id_conversacion','id');
     }
     public function usuarios(){
         return $this->BelongsToMany(Usuario::class,'usuarios_has_conversaciones','id_conversacion','id_usuario')->withPivot('participante');
     }
}
