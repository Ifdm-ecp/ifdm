<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\PvtTraits;

class PvtFormacionXPozo extends Model
{
    public $timestamps = false;
    
    static function store($request)
    {
        $store = new PvtFormacionXPozo;
        $pvt = new PvtTraits();        
        $pvt->saveAndUpdate($request, $store);
    } 

    static function edit($data)
    {
        $pvt = new PvtTraits();    
        return $pvt->editPvt($data);
        
    }


    static function actualizarPvtGlobal($request, $formation)
    {
        $update = $formation->pvtFormacionXPozo;
        if (is_null($update)) {
            $update = new PvtFormacionXPozo;
            $request->formacionxpozos_id = $formation->id;
        }

        $pvt = new PvtTraits();
        $pvt->saveAndUpdate($request, $update);
    }
}
