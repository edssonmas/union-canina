<?php
namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Extravio extends Model
{
     protected $table = 'extravios';
     protected $primaryKey='id';
     public $timestamps = false;
     
     public function mascota(){
         return $this->belongsTo(Mascota::class,'id_mascota','id');
     }
     public function ciudad(){
     	return $this->belongsTo(Ciudad::class,'id_ciudad','id');
     }
}
