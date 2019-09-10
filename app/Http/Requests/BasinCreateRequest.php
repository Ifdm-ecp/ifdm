<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class BasinCreateRequest extends Request
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
            'basin_name' => 'required|unique:cuencas,nombre,'.$this->get('id'),
        ];
    }

    public function messages()
    {
        return [
            'basin_name.required' => 'Basin name required.',
            'basin_name.unique,'  => 'Basin name has already been taken.',
        ];
    }
}
