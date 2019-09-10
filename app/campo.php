<?php 

namespace App;
use App\campo;
use App\cuenca;
use Sofa\Revisionable\Laravel\RevisionableTrait; // trait
use Sofa\Revisionable\Revisionable; // interface

class campo extends \Eloquent implements Revisionable {

    use RevisionableTrait;
    protected $table = 'campos';
    public $timestamps = false;


    /*
     * Set revisionable whitelist - only changes to any
     * of these fields will be tracked during updates.
     */

    public function formations()
    {
        return $this->hasMany('App\formacion');
    }

    public function sectors()
    {
        return $this->hasMany('App\sector');
    }

    public function cuenca()
    {
        return $this->belongsTo('App\cuenca', 'cuenca_id');
    }

    public function identifiableNameField($key)
    {
        return campo::where('id', $key)->first();
    }

    public function identifiableNameBasin($key)
    {
        return cuenca::where('id', $key)->first();
    }

    static function fieldFormation($key)
    {
        return formacion::select('id', 'nombre')->where('campo_id', $key)->get();
    }

    protected $revisionable = [
        'nombre',
        'cuenca_id'
    ];

    protected $labels = [
        'nombre' => 'Name',
        'cuenca_id' => 'Basin'
    ];
}

?>


