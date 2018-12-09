<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Raza extends Model
{
     protected $table = 'razas';
     protected $primaryKey='id';
     public $timestamps = false;

     public function mascota(){
         return $this->hasMany(Mascota::class,'id_raza','id');
     }
}
