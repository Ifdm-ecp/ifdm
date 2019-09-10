<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class RequestFormationMineralogy extends Request
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
        $rules =  [
                'quarts' => 'numeric|required',
                'feldspar' => 'numeric|required',
                'clays' => 'numeric|required',
            ];

        if($this->request->get('action') == 'create'){
            $rules['formacion_id'] = 'required|unique:formation_mineralogies,formacion_id';
        }elseif($this->request->get('action') == 'update'){
            $rules['formacion_id'] = 'required|unique:formation_mineralogies,formacion_id,'.$this->request->get('formacion_id').',formacion_id';
        }

        return $rules;
    }

     public function messages()
    {
        return [
            'quarts.numeric'  => 'quarts must be numeric.',
            'feldspar.numeric'  => 'feldspar must be numeric.',
            'clays.numeric'  => 'clays must be numeric.',
            'quarts.required'  => 'quarts is required.',
            'feldspar.required'  => 'feldspar is required.',
            'clays.required'  => 'clays is required.',
            'formacion_id.required'  => 'formacion_id is required.',
            'formacion_id.unique'  => 'formacion_id is repeat.',
        ];
    }
}
