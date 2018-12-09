<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Codigo extends Model
{
    protected $table='codigos';
    protected $primarykey='id';


    public function mascota(){
        return $this->belongsTo(Mascota::class,'id_mascota','id');
     }
}
