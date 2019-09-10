<?php 

namespace App;
use App\campo;
use App\cuenca;
use Sofa\Revisionable\Laravel\RevisionableTrait; // trait
use Sofa\Revisionable\Revisionable; // interface

class proyecto extends \Eloquent implements Revisionable {

    use RevisionableTrait;
    protected $table = 'proyectos';
    public $timestamps = false;


    /*
     * Set revisionable whitelist - only changes to any
     * of these fields will be tracked during updates.
     */

    public function Usuario(){
        return $this->belongsTo('App\Usuario');
    }

    public function Escenarios(){
        return $this->hasMany('App\escenario');
    }

    public function identifiableNameProject($key)
    {
        return formacion::where('id', $key)->first();
    }

    protected $revisionable = [
        'nombre',
        'fecha',
        'descripcion',
        'usuario_id',
        'compania'
    ];

}

?>


