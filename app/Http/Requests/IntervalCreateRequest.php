<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class IntervalCreateRequest extends Request
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
        return [
            'basin_interval' => 'required',
            'field_interval' => 'required',
            'well_interval' => 'required',
            'formation_interval' => 'required',
            'interval_interval' => 'required',
            'variable_name' => 'required',
            'value_interval' => 'required_if:variable_name,Top,Net pay,Porosity,Permeability,Reservoir pressure|numeric',
            'RelP' => 'required_without:value_interval',
            'RelP2' => 'required_without:value_interval',
            'date_interval' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'basin_interval.required' => 'Basin name required.',
            'field_interval.required' => 'Field name required.',
            'well_interval.required' => 'Well name required.',
            'formation_interval.required' => 'Formation name required.',
            'interval_interval.required' => 'Interval name required.',
            'variable_name.required' => 'Variable name required.',
            'value_interval.required_if' => 'Value required.',
            'RelP.required_without' => 'Gas-Liquid table required.',
            'RelP2.required_without' => 'Water-Oil table required.',
            'date_interval.required' => 'Date required.',
            'value_interval.numeric'=> 'Value must be numeric',
        ];
    }
}
