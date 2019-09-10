<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class catalogoIntervaloProductor extends Model
{
    protected $table = 'catalogo_intervalo_productor';
    public $timestamps = true;

    protected $fillable = ['nombre','formacion_id'];

    static function store($data){
    	return catalogoIntervaloProductor::create($data);
    }

}
