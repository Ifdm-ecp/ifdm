<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\PvtTraits;

class PvtGlobal extends Model
{
    

	protected $fillable = ['id','formacion_id','descripcion','pressure','uo','ug','uw','bo','bg','bw','rs','rv'];

    public $timestamps = false;

    public function formacion()
    {
    	return $this->belongsTo('App\formacion', 'formacion_id');
    }


	static function store($request)
	{
        $store = new PvtGlobal;
        $pvt = new PvtTraits();        
        $pvt->saveAndUpdate($request, $store);


	} 

	static function edit($data)
	{
        $pvt = new PvtTraits();    
        return $pvt->editPvt($data);
        
	}

	static function actualizarPvtGlobal($request, $id)
	{ 
		$update = PvtGlobal::find($id);
        $pvt = new PvtTraits();  
        dd($pvt);
		$pvt->saveAndUpdate($request, $update);
	}
	


////////////////////////////////////////////////////////////////////
    public function scopeFormacion_id($query, $formacion_id)
    {
    	if($formacion_id != '')
    	{
    		$query->where('formacion_id', $formacion_id);
    	}
    }


    static function tree()
    { 
        //$pozo = formacion::find(1);
        //dd($pozo);
        //$pozo->formacionxpozo;

        $cuencas = cuenca::has('campos.formations.pvtGlobals')->get();
        $campos = campo::has('formations.pvtGlobals')->get();
        $formaciones = formacion::has('pvtGlobals')->get();

        $data = collect();
        foreach ($cuencas as $cuenca) {
            $cuencaData = collect();
            $cuencaData->put('id', 'cuenca'.$cuenca->id);
            $cuencaData->put('parent', '#');
            $cuencaData->put('text', $cuenca->nombre);
            $cuencaData->put('icon', 'glyphicon glyphicon-folder-open text-primary');
            $data->push($cuencaData);
        }

        foreach ($campos as $campo){
            $campoData = collect();
            $campoData->put('id', 'campo'.$campo->id);
            $campoData->put('parent', 'cuenca'.$campo->cuenca->id);
            $campoData->put('text', $campo->nombre);
            $campoData->put('icon', 'glyphicon glyphicon-folder-open text-success');
            $data->push($campoData);
        }

        foreach ($formaciones as $formacion){
            $formacionData = collect();
            $formacionData->put('id', 'formacion'.$formacion->id);
            $formacionData->put('parent', 'campo'.$formacion->campos->id);
            $formacionData->put('text', $formacion->nombre);
            $formacionData->put('icon', 'glyphicon glyphicon-folder-open text-warning');
            $data->push($formacionData);
            foreach ($formacion->pvtGlobals as $pvt) {
                $pvtData = collect();
                $pvtData->put('id', $pvt->id);
                $pvtData->put('parent', 'formacion'.$pvt->formacion->id);
                $pvtData->put('text', $pvt->descripcion);
                $pvtData->put('icon', 'glyphicon glyphicon-log-in text-info');
                $data->push($pvtData);
            }
        }

        return json_encode($data);
    }
}
