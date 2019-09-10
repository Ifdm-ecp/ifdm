<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class ProductionTestRequestInterval extends Request
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
            'pltqo.boolean'=> 'Oil percentage must be between 0% and 100%.',
            'pltqg.boolean'=> 'Gas percentage must be between 0% and 100%.',
            'pltqw.boolean'=> 'Water percentage must be between 0% and 100%.',
        ];
    
    }
}
