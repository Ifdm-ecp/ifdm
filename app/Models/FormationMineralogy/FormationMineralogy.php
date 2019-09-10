<?php

namespace App\Models\FormationMineralogy;

use Illuminate\Database\Eloquent\Model;

class FormationMineralogy extends Model
{
    protected $fillable = [ 'id', 'formacion_id', 'quarts', 'feldspar', 'clays'];

    public function formacion()
    {
        return $this->belongsTo('App\formacion');
    }

    public function finePercentage()
    {
        return $this->hasMany('App\Models\FormationMineralogy\FinePercentage');
    }

    public function scopeFormacion_id($query, $formacion_id)
    {
    	if($formacion_id != '')
    	{
    		$query->where('formacion_id', $formacion_id);
    	}
    }
}
