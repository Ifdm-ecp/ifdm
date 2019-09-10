<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class ProductionTestRequest extends Request
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
            'pltqo'=> 'boolean',
            'pltqg'=> 'boolean',
            'pltqw'=> 'boolean',
        ];
    }

    public function messages()
    {
        return [
            'pltqo.boolean'=> 'Sume of the oil percentage of all producing intervals for a given well must be between 0% and 100%.',
            'pltqg.boolean'=> 'Sume of the gas percentage of all producing intervals for a given well must be between 0% and 100%.',
            'pltqw.boolean'=> 'Sume of the water percentage of all producing intervals for a given well must be between 0% and 100%.',
        ];
    
    }
}
