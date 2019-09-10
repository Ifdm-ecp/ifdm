<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class filtration_function extends Model
{
    protected $table = 'd_filtration_function';
    public function composicion()
    {
    	return $this->hasMany('App\MudComposicion', 'd_filtration_function_id');
    }
}
