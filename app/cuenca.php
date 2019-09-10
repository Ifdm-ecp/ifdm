<?php 

namespace App;

use Sofa\Revisionable\Laravel\RevisionableTrait; // trait
use Sofa\Revisionable\Revisionable; // interface

class cuenca extends \Eloquent implements Revisionable {

    use RevisionableTrait;
    protected $table = 'cuencas';
    public $timestamps = false;


    /*
     * Set revisionable whitelist - only changes to any
     * of these fields will be tracked during updates.
     */

    public function campos()
    {
        return $this->hasMany('App\campo');
    }

    static function basinField($key)
    {
        return campo::select('id', 'nombre')->where('cuenca_id', $key)->get();
    }


    public function identifiableNameBasin($key)
    {
        return cuenca::where('id', $key)->first();
    }

    protected $revisionable = [
        'nombre'
    ];

}

?>


