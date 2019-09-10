<?php 

namespace App;
use App\campo;
use App\cuenca;
use Sofa\Revisionable\Laravel\RevisionableTrait; // trait
use Sofa\Revisionable\Revisionable; // interface

class formacionxpozo extends \Eloquent implements Revisionable {


    use RevisionableTrait;
    protected $table = 'formacionxpozos';
    public $timestamps = false;


    /*
     * Set revisionable whitelist - only changes to any
     * of these fields will be tracked during updates.
     */
    public function formacion()
    {
        return $this->belongsTo('App\formacion', 'formacion_id');
    }

    public function pozo()
    {
        return $this->belongsTo('App\pozo', 'pozo_id');
    }

    public function pvtFormacionXPozo()
    {
        return $this->hasOne('App\PvtFormacionXPozo', 'formacionxpozos_id');
    }

    public function identifiableNameWell($key)
    {
        return pozo::where('id', $key)->first();
    }

    public function identifiableNameFormation($key)
    {
        return formacion::where('id', $key)->first();
    }

    public function identifiableNameProducingInterval($key)
    {
        return formacion::where('id', $key)->first();
    }

    protected $revisionable = [
        'intervalo_id',
        'top',
        'netpay',
        'porosidad',
        'permeabilidad',
        'presion_reservorio',
        'lat',
        'lon',
        'z',
        'pozo_id',
        'formacion_id'
    ];

}

?>


