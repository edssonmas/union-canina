<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Fotografia extends Model
{
    protected $table = 'fotografias';
    protected $primaryKey='id';
    public $timestamps = false;
     
    public function mascota(){
         return $this->belongsTo(Mascota::class,'id_mascota','id');
    }
}
