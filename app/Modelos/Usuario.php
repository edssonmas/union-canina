<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
     protected $table = 'usuarios';
     protected $primaryKey='id';
     public $timestamps = false;

     public function mascotas(){
         return $this->hasMany(Mascota::class,'id_usuario','id');
     }

     public function mensajesEnviados(){
         return $this->HasMany(Mensaje::class,'id_usuario','id');
     }

     public function conversaciones(){
         return $this->BelongsToMany(Conversacion::class,'usuarios_has_conversaciones','id_usuario','id_conversacion')->withPivot('participante');
     }
 

}
