<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class drilling_request extends Request
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
            'formationSelect' => 'required',
            'intervalSelect' => 'required',
            'inputDataMethodSelect' => 'required',
            'select_filtration_function' => 'required|exists:d_filtration_function,id',
            'a_factor_t' => 'required|numeric|min:0|max:50',
            'b_factor_t' => 'required|numeric|min:0|max:50',
            'd_total_exposure_time_t' => 'required|numeric|min:0|max:50',
            'd_pump_rate_t' => 'required|numeric|min:0|max:500',
            'd_mud_density_t' => 'required|numeric|min:0|max:20',
            'd_plastic_viscosity_t' => 'required|numeric|min:0|max:100',
            'd_yield_point_t' => 'required|numeric|min:0|max:100',
            'd_rop_t' => 'required|numeric|min:0|max:500',
            'd_equivalent_circulating_density_t' => 'required|numeric|min:0|max:30',
            'd_max_p_mud_t'=>'required|numeric',
            'd_min_p_mud_t'=>'required|numeric',
        ];

        if($this->cementingAvailable)
        {
            $rules['c_total_exposure_time_t'] = 'required|numeric|min:0|max:50';
            $rules['c_pump_rate_t'] = 'required|numeric|min:0|max:500';
            $rules['c_cement_slurry_density_t'] = 'required|numeric|min:0|max:50';
            $rules['c_cement_plastic_viscosity_t'] = 'required|numeric|min:0|max:10';
            $rules['c_cement_yield_point_t'] = 'required|numeric|min:0|max:100';
            $rules['c_equivalent_circulating_density_t'] = 'required|numeric|min:0|max:70';
        }
        return $rules;

    }

    public function messages()
    {
        $messages = [
            'formationSelect.required'=>'A formation must be selected.',
            'intervalSelect.required'=>'A producing interval must be selected.',
            'inputDataMethodSelect.required'=>'An input method (Average, By Intervals, Profile) must be selected.',
            'd_total_exposure_time_t.required'=>'The drilling total exposure time is required.',
            'd_total_exposure_time_t.numeric'=>'The drilling total exposure time must be a number.',
            'd_pump_rate_t.required'=>'The drilling pump rate is required.',
            'd_pump_rate_t.numeric'=>'The drilling pump rate must be a number.',
            'd_max_p_mud_t.required'=>'The drilling max mud density is required.',
            'd_max_p_mud_t.numeric'=>'The drilling max mud density must be a number.',
            'd_min_p_mud_t.required'=>'The drilling min mud density is required.',
            'd_min_p_mud_t.numeric'=>'The drilling min mud density must be a number',
            'd_rop_t.required'=>'The drilling ROP is required.',
            'd_rop_t.numeric'=>'The drilling ROP must be a number.',
            'c_total_exposure_time_t.required'=>'The cementing total exposure time is required.',
            'c_total_exposure_time_t.numeric'=>'The cementing total exposure time must be a number',
            'c_pump_rate_t.required'=>'The cementing pump rate is required.',
            'c_pump_rate_t.numeric'=>'The cementing pump rate must be a number',
            'c_cement_slurry_density_t.required'=>'The cement slurry is required.',
            'c_cement_slurry_density_t.numeric'=>'The cement slurry must be a number.',
            'c_equivalent_circulating_density_t.required'=>'The cementing equivalent circulating density is required.',
            'c_equivalent_circulating_density_t.numeric'=>'The cementing equivalent circulating density must be a number.',
        ];

        return $messages;
    }
}
