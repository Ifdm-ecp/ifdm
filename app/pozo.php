<?php 

namespace App;
use App\campo;
use App\cuenca;
use App\pozo;
use Sofa\Revisionable\Laravel\RevisionableTrait; // trait
use Sofa\Revisionable\Revisionable; // interface

class pozo extends \Eloquent implements Revisionable {

    use RevisionableTrait;
    protected $table = 'pozos';
    public $timestamps = false;


    /*
     * Set revisionable whitelist - only changes to any
     * of these fields will be tracked during updates.
     */

    public function formacionesxpozo()
    {
        return $this->hasMany('App\formacionxpozo', 'pozo_id');
    }

    public function campo()
    {
        return $this->belongsTo('App\campo');
    }
    public function mediciones()
    {
        return $this->hasMany('App\medicion');
    }

    public function identifiableNameField($key)
    {
        return campo::where('id', $key)->first();
    }

    public function identifiableNameBasin($key)
    {
        return cuenca::where('id', $key)->first();
    }

    public function identifiableNameWell($key)
    {
        return pozo::where('id', $key)->first();
    }

    protected $revisionable = [
        'nombre',
        'lat',
        'lon',
        'campo_id',
        'type',
        'bhp',
        'radius',
        'drainage_radius',
        'oil_rate',
        'gas_rate',
        'cumulative_oil',
        'cumulative_gas',
        'tdv',
        'cuenca_id'
    ];

    public function fluidoxpozo()
    {
        return $this->hasOne('App\fluidoxpozos');
    }

}

?>


