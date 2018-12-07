<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Pais extends Model
{
	protected $table='paises';
	protected $primarykey='id';

	public function estados(){
		return $this->hasMany(Estado::class,'id_pais','id');
	}
    
}
