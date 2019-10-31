<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class filtration_function_request extends Request
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
            'basin'=>'required|unique:d_filtration_function,name',
            'field'=>'required',
            'formation'=>'required',
            'mud_density'=>'required|numeric',
            //'kdki_cement_slurry'=>'required|numeric',
            'kdki_mud'=>'required|numeric',
            'core_diameter'=>'required|numeric',
            'plastic_viscosity' => 'required|numeric',
            'yield_point' => 'required|numeric',
            'cement_density' => 'numeric',
            'cement_plastic_viscosity' => 'numeric',
            'cement_yield_point' => 'numeric',
        ];
        
        if($this->filtration_function_factors_option == 1)
        {
            for ($i=1; $i < $this->lab_test_counter; $i++) 
            { 
                $rules["k_lab_test_".$i] = 'required|numeric';
                $rules["p_lab_test_".$i] = 'required|numeric';
            }
        }else{
            $rules['a_factor'] = 'required|numeric';
            $rules['b_factor'] = 'required|numeric';
        }

        return $rules;

    }

    public function messages()
    {
        $messages = [
            'basin.required'=>'You must choose a basin.',
            'basin.unique'=>'That Basin name is already taken.',
            'field.required'=>'You must choose a field.',
            'formation.required'=>'You must choose a formation.',
            'a_factor.required'=>'The a factor is required.',
            'a_factor.numeric'=>'The a factor must be a number.',
            'b_factor.required'=>'The b factor is required.',
            'b_factor.numeric'=>'The b factor must be a number.',
            'mud_density.required'=>'The Mud density is required.',
            'mud_density.numeric'=>'The Mud density must be a number.',
            //'kdki_cement_slurry.required'=>'The kdki cement slurry is required.',
            //'kdki_cement_slurry.numeric'=>'The kdki cement slurry must be a number.',
            'kdki_mud.required'=>'The kdki mud is required.',
            'kdki_mud.numeric'=>'The kdki mud is must be a number.',
            'core_diameter.required'=>'The Core diameter is required.',
            'core_diameter.numeric'=>'Core diameter is must be a number.',

            'plastic_viscosity.required'=>'The Plastic Viscosity mud is required.',
            'plastic_viscosity.numeric'=>'The Plastic Viscosity mud is must be a number.',
            'yield_point.required'=>'The Yield Point is required.',
            'yield_point.numeric'=>'Yield Point is must be a number.',
        ];

        for ($i=1; $i < $this->lab_test_counter; $i++) 
        { 
            $messages["k_lab_test_".$i.".required"] = 'The k value for lab test #'.$i.' is required.';
            $messages["k_lab_test_".$i.".numeric"] = 'The k value for lab test #'.$i.' must be a number.';
            $messages["p_lab_test_".$i.".required"] = 'The p value for lab test #'.$i.' is required.';
            $messages["p_lab_test_".$i.".numeric"] = 'The p value for lab test #'.$i.' must be a number.';
        }
        return $messages;
    
    }
}
