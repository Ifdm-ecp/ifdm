<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class DamageVariablesRequest extends Request
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
            'basin' => 'required',
            'field' => 'required',
            'well' => 'required',
            'mecan_dano' => 'required',
            'damage_variables' => 'required_without:damage_configuration',
            'damage_configuration' => 'required_without:damage_variables',
            'value' => 'required|numeric',
            'date' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'basin.required' => 'Basin name required.',
            'field.required' => 'Field name required.',
            'well.required' => 'Well name required.',
            'mecan_dano.required' => 'Damage mechanisms required.',
            'damage_variables.required_without' => 'Damage variables required.',
            'damage_configuration.required_without' => 'Damage configuration required.',
            'value.required' => 'Value required.',
            'date.required' => 'Date required.',
            'value.numeric'=> 'Value must be numeric',
        ];
    }
}
