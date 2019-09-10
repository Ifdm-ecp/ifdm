<?php 

namespace App;
use App\campo;
use App\formacion;
use App\catalogoIntervaloProductor;
use Sofa\Revisionable\Laravel\RevisionableTrait; // trait
use Sofa\Revisionable\Revisionable; // interface

class formacion extends \Eloquent implements Revisionable {

    use RevisionableTrait;
    protected $table = 'formaciones';
    public $timestamps = false;


    /*
     * Set revisionable whitelist - only changes to any
     * of these fields will be tracked during updates.
     */
    public function mineralogy()
    {
        return $this->hasOne('App\Models\FormationMineralogy\FormationMineralogy', 'formacion_id');
    }

    public function formacionesxpozo()
    {
        return $this->hasMany('App\formacionxpozo', 'formacion_id');
    }

    public function pvt()
    {
        return $this->hasOne('App\Pvt');
    }

    public function pvtGlobals()
    {
        return $this->hasMany('App\PvtGlobal');
    }
    
    public function campos()
    {
        return $this->belongsTo('App\campo', 'campo_id');
    }

    public function intervalosProductores()
    {
        return $this->hasMany('App\catalogoIntervaloProductor');
    }

    public function identifiableNameField($key)
    {
        return campo::where('id', $key)->first();
    }

    public function identifiableNameFormation($key)
    {
        return formacion::where('id', $key)->first();
    }

    protected $revisionable = [
        'nombre',
        'campo_id',
        'top',
        'porosidad',
        'permeabilidad',
        'presion_reservorio'
    ];

}

?>


