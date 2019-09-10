<?php

namespace App\Http\Requests\MultiparametricAnalysis;

use App\Http\Requests\Request;

class AnalyticalRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if (!isset($_POST['button_wr'])) {
            return [
                'netpay'  => 'numeric|required',
                'absolute_permeability'  => 'numeric|required',
                'fluid_type'  => 'required',
                'viscosity'  => 'numeric|required',
                'volumetric_factor'  => 'numeric|required',
                'well_radius'  => 'numeric|required',
                'drainage_radius'  => 'numeric|required',
                'reservoir_pressure'  => 'numeric|required',
                'fluid_rate'  => 'numeric|required',
                'critical_radius'  => 'numeric|required',
                'total_volumen'  => 'numeric|required',
                'saturation_presure'  => 'numeric|required',
                'mineral_scale_cp'  => 'numeric|required',
                'organic_scale_cp'  => 'numeric|required',
                'geomechanical_damage_cp'  => 'numeric|required',
                'mineral_scale_kd'  => 'numeric|required',
                'organic_scale_kd'  => 'numeric|required',
                'geomechanical_damage_kd'  => 'numeric|required',
                'fines_blockage_kd'  => 'numeric|required',
                'relative_permeability_kd'  => 'numeric|required',
                'induced_damage_kd'  => 'numeric|required',
            ];
        } else {
            return [];
        }
    }

    public function messages()
    {
        return [
            'netpay.numeric'  => 'netpay must be numeric.',
            'absolute_permeability.numeric'  => 'absolute_permeability must be numeric.',
            'viscosity.numeric'  => 'viscosity must be numeric.',
            'volumetric_factor.numeric'  => 'volumetric_factor must be numeric.',
            'well_radius.numeric'  => 'well_radius must be numeric.',
            'drainage_radius.numeric'  => 'drainage_radius must be numeric.',
            'reservoir_pressure.numeric'  => 'reservoir_pressure must be numeric.',
            'fluid_rate.numeric'  => 'fluid_rate must be numeric.',
            'critical_radius.numeric'  => 'critical_radius must be numeric.',
            'total_volumen.numeric'  => 'total_volumen must be numeric.',
            'saturation_presure.numeric'  => 'saturation_presure must be numeric.',
            'mineral_scale_cp.numeric'  => 'mineral_scale_cp must be numeric.',
            'organic_scale_cp.numeric'  => 'organic_scale_cp must be numeric.',
            'geomechanical_damage_cp.numeric'  => 'geomechanical_damage_cp must be numeric.',
            'mineral_scale_kd.numeric'  => 'mineral_scale_kd must be numeric.',
            'organic_scale_kd.numeric'  => 'organic_scale_kd must be numeric.',
            'geomechanical_damage_kd.numeric'  => 'geomechanical_damage_kd must be numeric.',
            'fines_blockage.numeric'  => 'fines_blockage must be numeric.',
            'relative_permeability.numeric'  => 'relative_permeability must be numeric.',
            'induced_damage.numeric'  => 'induced_damage must be numeric.',

            'netpay.required'  => 'netpay is required.',
            'absolute_permeability.required'  => 'absolute_permeability is required.',
            'viscosity.required'  => 'viscosity is required.',
            'volumetric_factor.required'  => 'volumetric_factor is required.',
            'well_radius.required'  => 'well_radius is required.',
            'drainage_radius.required'  => 'drainage_radius is required.',
            'reservoir_pressure.required'  => 'reservoir_pressure is required.',
            'fluid_rate.required'  => 'fluid_rate is required.',
            'critical_radius.required'  => 'critical_radius is required.',
            'total_volumen.required'  => 'total_volumen is required.',
            'saturation_presure.required'  => 'saturation_presure is required.',
            'mineral_scale_cp.required'  => 'mineral_scale_cp is required.',
            'organic_scale_cp.required'  => 'organic_scale_cp is required.',
            'geomechanical_damage_cp.required'  => 'geomechanical_damage_cp is required.',
            'mineral_scale_kd.required'  => 'mineral_scale_kd is required.',
            'organic_scale_kd.required'  => 'organic_scale_kd is required.',
            'geomechanical_damage_kd.required'  => 'geomechanical_damage_kd is required.',
            'fines_blockage.required'  => 'fines_blockage is required.',
            'relative_permeability.required'  => 'relative_permeability is required.',
            'induced_damage.required'  => 'induced_damage is required.',
            'fluid_type.required'  => 'fluid_type is required.',
        ];
    }
}
