<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class IPRRequest extends Request
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
            //'GDFormation' => 'required|numeric|between:0,100',
        ];
    }

    public function messages()
    {
        return [
            //'GDFormation.between'=> 'GD must be between 0% and 100%.',
        ];
    
    }
}
