<?php

namespace App\Models\MultiparametricAnalysis;

use Illuminate\Database\Eloquent\Model;

class Analytical extends Model
{
    protected $table = 'multiparametric_analysis_analytical';
    public $timestamps = false;

    protected $fillable = ['id', 'escenario_id', 'netpay', 'absolute_permeability', 'fluid_type', 'viscosity', 'volumetric_factor', 'well_radius', 'drainage_radius', 'reservoir_pressure', 'fluid_rate', 'critical_radius', 'total_volumen', 'saturation_presure', 'mineral_scale_cp', 'organic_scale_cp', 'geomechanical_damage_cp', 'mineral_scale_kd', 'organic_scale_kd', 'geomechanical_damage_kd', 'fines_blockage_kd', 'relative_permeability_kd', 'induced_damage_kd', 'bhp', 'porosity', 'status_wr'];

    public function escenario()
    {
        return $this->belongsTo('App\escenario');
    }
}
