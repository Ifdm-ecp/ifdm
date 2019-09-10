<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class FieldCreateRequest extends Request
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
            'field_name' => 'required|unique:campos,nombre,'.$this->get('id'),
            'basin_for_field' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'field_name.required' => 'Field name required.',
            'basin_for_field.required' => 'Basin required.',
            'field_name.unique'  => 'Field name has already been taken.',
        ];
    
    }
}
