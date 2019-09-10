<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class FormationWellRequest extends Request
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
            'XW' => 'required',
            'YW' => 'required',
            'TDVW' => 'numeric',
            'apiGravity' => 'numeric|between:0,100',
            'wor' => 'numeric|min:0',
            'lgr' => 'numeric|min:0',
            'gwr' => 'numeric|min:0',
            'saturationP' => 'numeric|min:0',
            'gor' => 'numeric|min:0',
            'cgr' => 'numeric|min:0',
            'specificG' => 'numeric|min:0',
            'oilViscosity' => 'numeric|min:0',
            'gasViscosity' => 'numeric|min:0',
            'waterViscosity' => 'numeric|min:0',
            'FVFo' => 'numeric|min:0',
            'FVFg' => 'numeric|min:0',
            'FVFw' => 'numeric|min:0',
            'basin' => 'required',
            'field' => 'required',
            'nameWell' => 'required|unique:pozos,Nombre,'.$this->get('id'),
            'bhp' => 'numeric',
            'wellRadius' => 'numeric|min:0',
            'drainageRadius' => 'numeric|min:0',
        ];
    }

    public function messages()
    {
        return [

            'basin.required'=> 'Basin name required',
            'field.required'=> 'Field name required',
            'nameWell.required'=> 'Well name required',
            'nameInterval.unique'  => 'Well name has already been taken.',

            'XW.required'=> 'Latitude is required',
            'YW.required'=> 'Longitude is required',
            'TDVW.numeric'=> 'TVD must be numeric',
            'apiGravity.numeric'=> 'Api gravity must be numeric',
            'wor.numeric'=> 'Wor must be numeric',
            'lgr.numeric'=> 'Lgr must be numeric',
            'gwr.numeric'=> 'Gwr must be numeric',
            'saturationP.numeric'=> 'Saturation pressure must be numeric',
            'gor.numeric'=> 'Gor must be numeric',
            'cgr.numeric'=> 'Cgr must be numeric',
            'specificG.numeric'=> 'Specific gas must be numeric',
            'oilViscosity.numeric'=> 'Oil viscosity must be numeric',
            'gasViscosity.numeric'=> 'Gas viscosity must be numeric',
            'waterViscosity.numeric'=> 'Water viscosity must be numeric',
            'FVFo.numeric'=> 'FVF oil must be numeric',
            'FVFg.numeric'=> 'FVF gas must be numeric',
            'FVFw.numeric'=> 'FVF water must be numeric',
            'bhp.numeric'=> 'Bhp must be numeric',
            'wellRadius.numeric'=> 'Well radius must be numeric',
            'drainageRadius.numeric'=> 'Drainage radius must be numeric',
            'wellRadius.numeric'=> 'Well radius must be positive',
            'drainageRadius.numeric'=> 'Drainage radius must be positive',
            
            'wor.min' => 'Wor must be positive',
            'saturationP.min' => 'Saturation pressure must be positive',
            'gor.min' => 'Gor must be positive',
            'lgr.min' => 'Lgr must be positive',
            'gwr.min' => 'Gwr must be positive',
            'cgr.min' => 'Cgr must be positive',
            'specificG.min' => 'Specific gas must be positive',
            'oilViscosity.min' => 'Oil viscosity must be positive',
            'gasViscosity.min' => 'Gas viscosity must be positive',
            'waterViscosity.min' => 'Water viscosity must be positive',
            'FVFo.min' => 'FVF oil must be positive',
            'FVFg.min' => 'FVF gas must be positive',
            'FVFw.min' => 'FVF water must be positive',

            'apiGravity.between' => 'Api gravity must be between 0 and 100',
        ];
    
    }
}
