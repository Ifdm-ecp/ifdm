<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class WellCreateRequest extends Request
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
            'basin_well' => 'required',
            'field_well' => 'required',
            'well_well' => 'required',
            'variable_name' => 'required',
            'value_well' => 'required_if:variable_name,BHP,Well radius,Drainage radius,Latitude,Longitude,TVD|numeric',
            'ProdD2' => 'required',
            'date_well' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'basin_well.required' => 'Basin name required.',
            'field_well.required' => 'Field name required.',
            'well_well.required' => 'Well name required.',
            'value_well.required_if' => 'Value required.',
            'ProdD2.required_without' => 'Production data required.',
            'date_well.required' => 'Date required.',
            'variable_name.required' => 'Variable name required.',
            'value_well.numeric'=> 'Value must be numeric',
        ];
    }
}
