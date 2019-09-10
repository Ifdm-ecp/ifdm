<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class subparameters_weight extends Model
{
    public $timestamps = false;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'subparameters_weight';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'multiparametric_id', 'ms_scale_index_caco3', 'ms_scale_index_baso4', 'ms_scale_index_iron_scales', 'ms_calcium_concentration', 'ms_barium_concentration', 'fb_aluminum_concentration', 'fb_silicon_concentration', 'fb_critical_radius_factor', 'fb_mineralogic_factor', 'fb_crushed_proppant_factor', 'os_cll_factor', 'os_compositional_factor', 'os_pressure_factor', 'os_high_impact_factor', 'rp_days_below_saturation_pressure', 'rp_delta_pressure_saturation', 'rp_water_intrusion', 'rp_high_impact_factor', 'id_gross_pay', 'id_polymer_damage_factor', 'id_total_volume_water', 'id_mud_damage_factor', 'gd_fraction_netpay', 'gd_drawdown', 'gd_ratio_kh_fracture', 'gd_geomechanical_damage_fraction'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['remember_token'];

    public function statistical()
    {
        return $this->belongsTo('App\Models\MultiparametricAnalysis\Statistical', 'multiparametric_id');
    }
}
