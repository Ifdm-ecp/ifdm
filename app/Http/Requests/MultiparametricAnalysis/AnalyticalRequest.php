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
        $rules = [
            'netpay' => 'required|numeric',
            'absolute_permeability' => 'required|numeric',
            'porosity' => 'required|numeric',
            'fluid_type' => 'required|in:Oil,Gas',
            'viscosity_oil' => 'required_if:fluid_type,Oil|numeric',
            'volumetric_factor_oil' => 'required_if:fluid_type,Oil|numeric',
            'viscosity_gas' => 'required_if:fluid_type,Gas|numeric',
            'volumetric_factor_gas' => 'required_if:fluid_type,Gas|numeric',
            'well_radius' => 'required|numeric',
            'drainage_radius' => 'required|numeric',
            'reservoir_pressure' => 'required|numeric',
            'fluid_rate_oil' => 'required_if:fluid_type,Oil|numeric',
            'fluid_rate_gas' => 'required_if:fluid_type,Gas|numeric',
            'critical_radius' => 'required|numeric',
            'total_volumen' => 'required|numeric',
            'saturation_presure' => 'required|numeric',
            'mineral_scale_cp' => 'required|numeric',
            'organic_scale_cp' => 'required|numeric',
            'geomechanical_damage_cp' => 'required|numeric',
            'mineral_scale_kd' => 'required|numeric',
            'organic_scale_kd' => 'required|numeric',
            'geomechanical_damage_kd' => 'required|numeric',
            'fines_blockage_kd' => 'required|numeric',
            'relative_permeability_kd' => 'required|numeric',
            'induced_damage_kd' => 'required|numeric',
        ];

        if ($this->only_s == "save") {
            $rules["netpay"] = str_replace("required|", "", $rules["netpay"]);
            $rules["absolute_permeability"] = str_replace("required|", "", $rules["absolute_permeability"]);
            $rules["porosity"] = str_replace("required|", "", $rules["porosity"]);
            $rules["fluid_type"] = str_replace("required|", "", $rules["fluid_type"]);
            $rules["viscosity_oil"] = str_replace("required_if:fluid_type,Oil|", "", $rules["viscosity_oil"]);
            $rules["volumetric_factor_oil"] = str_replace("required_if:fluid_type,Oil|", "", $rules["volumetric_factor_oil"]);
            $rules["viscosity_gas"] = str_replace("required_if:fluid_type,Gas|", "", $rules["viscosity_gas"]);
            $rules["volumetric_factor_gas"] = str_replace("required_if:fluid_type,Gas|", "", $rules["volumetric_factor_gas"]);
            $rules["well_radius"] = str_replace("required|", "", $rules["well_radius"]);
            $rules["drainage_radius"] = str_replace("required|", "", $rules["drainage_radius"]);
            $rules["reservoir_pressure"] = str_replace("required|", "", $rules["reservoir_pressure"]);
            $rules["fluid_rate_oil"] = str_replace("required_if:fluid_type,Oil|", "", $rules["fluid_rate_oil"]);
            $rules["fluid_rate_gas"] = str_replace("required_if:fluid_type,Gas|", "", $rules["fluid_rate_gas"]);
            $rules["critical_radius"] = str_replace("required|", "", $rules["critical_radius"]);
            $rules["total_volumen"] = str_replace("required|", "", $rules["total_volumen"]);
            $rules["saturation_presure"] = str_replace("required|", "", $rules["saturation_presure"]);
            $rules["mineral_scale_cp"] = str_replace("required|", "", $rules["mineral_scale_cp"]);
            $rules["organic_scale_cp"] = str_replace("required|", "", $rules["organic_scale_cp"]);
            $rules["geomechanical_damage_cp"] = str_replace("required|", "", $rules["geomechanical_damage_cp"]);
            $rules["mineral_scale_kd"] = str_replace("required|", "", $rules["mineral_scale_kd"]);
            $rules["organic_scale_kd"] = str_replace("required|", "", $rules["organic_scale_kd"]);
            $rules["geomechanical_damage_kd"] = str_replace("required|", "", $rules["geomechanical_damage_kd"]);
            $rules["fines_blockage_kd"] = str_replace("required|", "", $rules["fines_blockage_kd"]);
            $rules["relative_permeability_kd"] = str_replace("required|", "", $rules["relative_permeability_kd"]);
            $rules["induced_damage_kd"] = str_replace("required|", "", $rules["induced_damage_kd"]);
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'netpay.required' => 'NetPay is required.',
            'absolute_permeability.required' => 'Absolute Permeability is required.',
            'porosity.required' => 'Pososity is required.',
            'fluid_type.required' => 'Fluid Type is required.',
            'viscosity_oil.required_if' => 'Oil Viscosity is required.',
            'volumetric_factor_oil.required_if' => 'Oil Volumetric Factor is required.',
            'viscosity_gas.required_if' => 'Gas Viscosity is required.',
            'volumetric_factor_gas.required_if' => 'Gas Volumetric Factor is required.',
            'well_radius.required' => 'Well Radius is required.',
            'drainage_radius.required' => 'Drainage Radius is required.',
            'reservoir_pressure.required' => 'Reservoir Pressure is required.',
            'fluid_rate_oil.required' => 'Oil Fluid Rate is required.',
            'fluid_rate_gas.required' => 'Gas Fluid Rate is required.',
            'critical_radius.required' => 'Critical Radius is required.',
            'total_volumen.required' => 'Total Volume is required.',
            'saturation_presure.required' => 'Saturation Pressure is required.',
            'mineral_scale_cp.required' => 'Critical Pressure Mineral Scales is required.',
            'organic_scale_cp.required' => 'Critical Pressure Organic Scales is required.',
            'geomechanical_damage_cp.required' => 'Critical Pressure Geomechanical Damage is required.',
            'mineral_scale_kd.required' => 'K Damaged And K Base Ratio Mineral Scales is required.',
            'organic_scale_kd.required' => 'K Damaged And K Base Ratio Organic Scales is required.',
            'geomechanical_damage_kd.required' => 'K Damaged And K Base Ratio Geomechanical Damage is required.',
            'fines_blockage.required' => 'K Damaged And K Base Ratio Fines Blockage is required.',
            'relative_permeability.required' => 'K Damaged And K Base Ratio Relative Permeability is required.',
            'induced_damage.required' => 'K Damaged And K Base Ratio Induced Damage is required.',

            'netpay.numeric' => 'NetPay must be numeric.',
            'absolute_permeability.numeric' => 'Absolute Permeability must be numeric.',
            'porosity.numeric' => 'Pososity must be numeric.',
            'viscosity_oil.numeric' => 'Oil Viscosity must be numeric.',
            'volumetric_factor_oil.numeric' => 'Oil Volumetric Factor must be numeric.',
            'viscosity_gas.numeric' => 'Gas Viscosity must be numeric.',
            'volumetric_factor_gas.numeric' => 'Gas Volumetric Factor must be numeric.',
            'well_radius.numeric' => 'Well Radius must be numeric.',
            'drainage_radius.numeric' => 'Drainage Radius must be numeric.',
            'reservoir_pressure.numeric' => 'Reservoir Pressure must be numeric.',
            'fluid_rate.numeric' => 'Fluid Rate must be numeric.',
            'critical_radius.numeric' => 'Critical Radius must be numeric.',
            'total_volumen.numeric' => 'Total Volume must be numeric.',
            'saturation_presure.numeric' => 'Saturation Pressure must be numeric.',
            'mineral_scale_cp.numeric' => 'Critical Pressure Mineral Scales must be numeric.',
            'organic_scale_cp.numeric' => 'Critical Pressure Organic Scales must be numeric.',
            'geomechanical_damage_cp.numeric' => 'Critical Pressure Geomechanical Damage must be numeric.',
            'mineral_scale_kd.numeric' => 'K Damaged And K Base Ratio Mineral Scales must be numeric.',
            'organic_scale_kd.numeric' => 'K Damaged And K Base Ratio Organic Scales must be numeric.',
            'geomechanical_damage_kd.numeric' => 'K Damaged And K Base Ratio Geomechanical Damage must be numeric.',
            'fines_blockage.numeric' => 'K Damaged And K Base Ratio Fines Blockage must be numeric.',
            'relative_permeability.numeric' => 'K Damaged And K Base Ratio Relative Permeability must be numeric.',
            'induced_damage.numeric' => 'K Damaged And K Base Ratio Induced Damage must be numeric.',

            'fluid_type.in' => 'Fluid Type selection must be Oil or Gas.',
        ];
    }
}
