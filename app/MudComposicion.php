<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MudComposicion extends Model
{
    protected $table = 'mud_composicion';
    public $timestamps = false;

     protected $fillable = ['d_filtration_function_id','component','concentraction'];

     static function store($request, $filtration_function){
     	if($filtration_function->composicion){
            MudComposicion::where('d_filtration_function_id', $filtration_function->id)->delete();
          }

     	$data = json_decode(str_replace(",[null,null]","",$request));
		foreach($data as $datos)
		{
			$store = new MudComposicion;
			$store->d_filtration_function_id = $filtration_function->id;
			$store->component = (float)str_replace(",", ".", $datos[0]);
			$store->concentraction = (float)str_replace(",", ".", $datos[1]);
			$store->save();

		}
     }

    static function getData($id)
    {
    	
    	$datos = collect([]);

    	foreach(MudComposicion::where('d_filtration_function_id', $id)->get() as $mudComposicion) 
    	{
    		$datos->push([(float)$mudComposicion->component, (float)$mudComposicion->concentraction]);
    	}

    	return $datos;
    }
}
