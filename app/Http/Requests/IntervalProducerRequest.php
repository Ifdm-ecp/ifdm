<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class IntervalProducerRequest extends Request
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

        return[
            'formacionName' => 'required',
            'nameInterval' => 'required',
            //'wellName' => 'required',
            'top' => 'numeric|min:0',
            'netpay' => 'numeric|min:0',
            'porosity' => 'numeric|between:0,100',
            'permeability' => 'numeric|min:0',
            'reservoir' => 'numeric|min:0',
            
        ];

        // if (\Request::isMethod('post'))
        // {
        //    $rules['nameInterval'] = 'required|unique:formacionxpozos,nombre,'.$this->request->get('formacion_id');
        // }elseif(\Request::isMethod('PATCH'))
        // {
        //     $rules['nameInterval'] = 'required|unique:formacionxpozos,nombre,'.$this->request->get('formacion_id').',nombre';
        // }

        return $rules;
    }

    public function messages()
    {
        return [
            'formacionName.required' => 'Formation name required.',
            'nameInterval.required' => 'Interval producer name required.',
            'wellName.required' => 'Well name required.',

            'top.numeric' => 'Top must be numeric.',
            'netpay.numeric' => 'Netpay must be numeric.',
            'porosity.numeric' => 'Porosity must be numeric.',
            'permeability.numeric' => 'Permeability must be numeric.',
            'reservoir.numeric' => 'Top must be numeric.',

            'top.min' => 'Top must be positive.',
            'netpay.min' => 'Netpay must be positive.',
            'permeability.min' => 'Permeability must be positive.',
            'reservoir.min' => 'Reservoir pressure must be positive.',

            'porosity.between' => 'Porosity must be between 0% and 100%.',
        ];
    }
}
