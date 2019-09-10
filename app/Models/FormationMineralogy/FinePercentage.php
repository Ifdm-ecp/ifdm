<?php

namespace App\Models\FormationMineralogy;

use Illuminate\Database\Eloquent\Model;

class FinePercentage extends Model
{
    protected $fillable = [ 'id', 'formation_mineralogy_id', 'fines', 'percentage'];

    public function formationMineralogy()
    {
        return $this->belongsTo('App\Models\FormationMineralogy\FormationMineralogy', 'formation_mineralogy_id');
    }

    static function crear($request, $id)
    {
    	$fines = $request['finestraimentselection'];
    	$percentage = $request['percentage'];
    	for ($i=0; $i < count($request['finestraimentselection']) ; $i++) { 
    		$crear = new FinePercentage;
    		$crear->formation_mineralogy_id = $id;
    		$crear->fines = $fines[$i];
    		$crear->percentage = $percentage[$i];
    		$crear->save();
    	}
    }

    static function actualizar($request, $formationMineralogy)
    {
    	self::borrar($formationMineralogy);
    	$fines = $request['finestraimentselection'];
        //dd($request['percentage']);
    	$percentage = $request['percentage'];
    	for ($i=0; $i < count($request['finestraimentselection']) ; $i++) { 
    		$crear = new FinePercentage;
    		$crear->formation_mineralogy_id = $formationMineralogy->id;
    		$crear->fines = $fines[$i];
    		$crear->percentage = $percentage[$i];
    		$crear->save();
    	}
    }


    static function borrar($fineMineralogy)
    {
    	if($fineMineralogy->finePercentage()->count() > 0)
    	{
    		FinePercentage::where('formation_mineralogy_id', $fineMineralogy->id)->delete();
    	}
    }
}
