<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Mensaje extends Model
{
     protected $table = 'mensajes';
     protected $primaryKey='id';
     public $timestamps = false;    

     public function usuario(){
         return $this->belongsTo(Usuario::class,'id_usuario','id');
     }
     public function conversacion(){
         return $this->belongsTo(Conversacion::class, 'id_conversacion','id');
     }
}
