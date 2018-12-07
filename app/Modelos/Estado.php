<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Estado extends Model
{
     protected $table = 'estados';
     protected $primaryKey='id';
     public $timestamps = false;

     public function mascota(){
         return $this->hasOne(Mascota::class, 'id_estado','id');
     }

     public function pais(){
     	return $this->belongsTo(Pais::class,'id_pais','id');
     }
     public function ciudades(){
     	return $this->hasMany(Ciudad::class,'id_ciudad','id');
     }
}
