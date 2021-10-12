<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class AddFormationRequest extends Request
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
            'fieldFormation'=> 'required',
            //'nameFormation'=> 'required|unique:formaciones,nombre,'.$this->get('id'),
            'topFormation'=> 'numeric|min:0',
            'netPayFormation'=> 'numeric|min:0',
            'porosityFormation'=> 'numeric|between:0,100',
            'permeabilityFormation'=> 'numeric|min:0',
            'reservoirPressureFormation'=> 'numeric|min:0',
            //'prof'=> 'required_with:CoordF',
        ];
    }

    public function messages()
    {
        return [
            //'prof.required_with' => 'Depth required.',
            'fieldFormation.required' => 'Field name required.',
            // 'nameFormation.unique'  => 'Formation name has already been taken.',
            'nameFormation.required' => 'Formation name required.',
            'topFormation.numeric' => 'Top must be numeric.',
            'netPayFormation.numeric' => 'Netpay must be numeric.',
            'porosityFormation.numeric' => 'Porosity must be numeric.',
            'permeabilityFormation.numeric' => 'Permeability must be numeric.',
            'reservoirPressureFormation.numeric' => 'Reservoir pressure must be numeric.',
            'porosityFormation.between' => 'Porosity must be between 0% and 100%.',
            'topFormation.min' => 'Top must be positive.',
            'netPayFormation.min' => 'Netpay must be positive.',
            'permeabilityFormation.min' => 'Permeability must be positive.',
            'reservoirPressureFormation.min' => 'Reservoir pressure must be positive.',
        ];
    }
}
