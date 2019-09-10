<?php 

namespace App;

use Sofa\Revisionable\Laravel\RevisionableTrait; // trait
use Sofa\Revisionable\Revisionable; // interface

class revisions extends \Eloquent implements Revisionable {

    use RevisionableTrait;
protected $table = 'revisions';


    /*
     * Set revisionable whitelist - only changes to any
     * of these fields will be tracked during updates.
     */

    public function cuenca()
    {
        return $this->belongsTo('App\cuenca');
    }

    public function identifiableBasin() {
        return $this->action;
    }

    }

 ?>


