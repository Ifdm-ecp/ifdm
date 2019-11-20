<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class asphaltene_stability_analysis_request extends Request
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
        $button_wr = (bool) isset($_POST['button_wr']);

        $reservoir_initial_pressure = $this->reservoir_initial_pressure;
        $rules = [];
        if (!$button_wr) {
            $validate = false;

            $rules['saturated'] = 'required_if:'.$button_wr.',=,'.$validate.'|numeric|between:0,100';
            $rules['aromatics'] = 'required_if:'.$button_wr.',=,'.$validate.'|numeric|between:0,100|not_in:0';
            $rules['resines'] = 'required_if:'.$button_wr.',=,'.$validate.'|numeric|between:0,100|not_in:0';
            $rules['asphaltenes'] = 'required_if:'.$button_wr.',=,'.$validate.'|numeric|between:0,100';
            $rules['reservoir_initial_pressure'] = 'required_if:'.$button_wr.',=,false|numeric|min:0|not_in:0';
            $rules['bubble_pressure'] = 'required_if:'.$button_wr.',=,'.$validate.'|numeric|min:0|not_in:0';
            $rules['density_at_reservoir_temperature'] = 'required_if:'.$button_wr.',=,'.$validate.'|numeric|between:0.5,2';
            $rules['api_gravity'] = 'required_if:'.$button_wr.',=,'.$validate.'|numeric|between:5,70';
            $rules['value_components_table'] = 'required_if:'.$button_wr.',=,'.$validate.'';
            $rules['components'] = 'required_if:'.$button_wr.',=,'.$validate.'';
            $rules['sum_zi_components_table'] = 'numeric|between:0.99,1.1';
            $rules['zi_range_flag_components_table'] = 'in:1';
        }
        #Agregar validacion para SARA
        if(is_numeric($this->input('saturated')) and is_numeric($this->input('aromatics')) and is_numeric($this->input('resines')) and is_numeric($this->input('asphaltenes'))){
            $sara = $this->input('saturated') + $this->input('aromatics') + $this->input('resines') + $this->input('asphaltenes');

            if($sara > 100.1 || $sara < 99.9)
            {
                #$rules['saturated'] = 'numeric|required|between:99,101';
            }
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'saturated.required' => 'SARA Analysis - saturated required.',
            'aromatics.required' => 'SARA Analysis - aromatics required.',
            'resines.required' => 'SARA Analysis - resines required.',
            'asphaltenes.required' => 'SARA Analysis - asphaltenes required.',
            'reservoir_initial_pressure.required' => 'Reservoir initial pressure required.',
            'bubble_pressure.required' => 'Bubble pressure required.',
            'density_at_reservoir_temperature.required' => 'Density at reservoir temperature required.',
            'api_gravity.required' => 'API gravity required.',
            'value_components_table.required' => 'Components table required.',
            'components.required' => 'Components required.',

            'saturated.numeric' => 'SARA Analysis - saturated must be numeric.',
            'aromatics.numeric' => 'SARA Analysis - aromatics must be numeric.',
            'resines.numeric' => 'SARA Analysis - resines must be numeric.',
            'asphaltenes.numeric' => 'SARA Analysis - asphaltenes must be numeric.',
            'reservoir_initial_pressure.numeric' => 'Reservoir initial pressure must be numeric.',
            'bubble_pressure.numeric' => 'Bubble pressure must be numeric.',
            'density_at_reservoir_temperature.numeric' => 'Density at reservoir temperature must be numeric.',
            'api_gravity.numeric' => 'API gravity must be numeric.',

            'saturated.between' => 'Saturated must be between 0 and 100.',
            'aromatics.between' => 'Aromatics must be between 0 and 100.',
            'resines.between' => 'Resines must be between 0 and 100.',
            'asphaltenes.between' => 'Asphaltenes must be between 0 and 100.',

            'aromatics.not_in' => 'Aromatics must be greater than 0.',
            'resines.not_in' => 'Resines must be greater than 0.',

            'reservoir_initial_pressure.min' => 'Reservoir Initial Pressure must be greater than 0.',
            'reservoir_initial_pressure.not_in' => 'Reservoir Initial Pressure must be greater than 0.',
            'bubble_pressure.min' => 'Bubble Pressure must be greater than 0.',
            'bubble_pressure.not_in' => 'Bubble Pressure must be greater than 0.',
            'density_at_reservoir_temperature.between' => 'Density at Reservoir Temperature must be between 0.5 and 2.',
            'api_gravity.between' => 'API Gravity must be between 5 and 70.',
            
            'sum_zi_components_table.between' => 'The sum of Zi in components table must be 1+-0.1.',
            'zi_range_flag_components_table.in' => 'All the Zi data in components table must be between 0 and 1.',
        ];
    }
}
