<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FinesTreatmentSelection extends Model
{
    protected $table = 'fines_treatment_selection';
    public $timestamps = false;

    protected $fillable = ['escenario_id','quartz','microline', 'orthoclase','albite','plagioClase','biotite','muscovite','chloritem', 'kaolinite','illite','emectite','chloritec','brucite', 'gibbsite', 'calcite','dolomite','ankeritec','sideritec','cast','anhydrite','baryte','celestine', 'halite', 'hematite','magnetite','pyrrhotite','pyrite','chloriteim','sideriteim','ankeriteim', 'glauconite', 'chamosite', 'troilite',  'stilbite', 'heulandite', 'chabazite', 'natrolite', 'analcime','melanterite', 'bentonite','finesna', 'tc','ty','h2s','iic','k','em','wet','tvd','isl','sal','status_wr'
    ];

}
