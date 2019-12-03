<?php 

namespace App;
use App\campo;
use App\cuenca;
use App\pozo;
use App\formacion;

use Sofa\Revisionable\Laravel\RevisionableTrait; // trait
use Sofa\Revisionable\Revisionable; // interface

class escenario extends \Eloquent implements Revisionable {

    use RevisionableTrait;
    protected $table = 'escenarios';
    public $timestamps = false;


    /*
     * Set revisionable whitelist - only changes to any
     * of these fields will be tracked during updates.
     */

    public function Proyecto(){
        return $this->belongsTo('App\proyecto');
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

    public function identifiableNameFormation($key)
    {
        return formacion::where('id', $key)->first();
    }

    public function identifiableNameProject($key)
    {
        return formacion::where('id', $key)->first();
    }

    public function identifiableNameScenario($key)
    {
        return escenario::where('id', $key)->first();
    }


    protected $revisionable = [
        'nombre',
        'descripcion',
        'tipo',
        'cuenca_id',
        'campo_id',
        'pozo_id',
        'formacion_id',
        'fecha',
        'proyecto_id'
    ];

    static function escenarioCompleto($id)
    {
        $update = escenario::find($id);
        $update->completo = 1;
        $update->save();
    }

    public function pozo()
    {
        return $this->belongsTo('App\pozo');
    }

    public function campo()
    {
        return $this->belongsTo('App\campo');
    }

    public function cuenca()
    {
        return $this->belongsTo('App\cuenca');
    }

    public function formacionxpozo()
    {
        return $this->belongsTo('App\formacionxpozo', 'formacion_id');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function fts()
    {
        return $this->hasOne('App\FinesTreatmentSelection');
    }

    public function analytical()
    {
        return $this->hasOne('App\Models\MultiparametricAnalysis\Analytical');
    }

    public function statistical()
    {
        return $this->hasOne('App\Models\MultiparametricAnalysis\statistical');
    }

    public function drilling()
    {
        return $this->hasOne('App\drilling', 'scenario_id');
    }

    public function asphalteneRemediations()
    {
        return $this->hasOne('App\asphaltene_remediations', 'id_scenary');
    }

    public function shared()
    {
        return $this->hasMany('App\shared_scenario', 'scenario_id');
    }

}



