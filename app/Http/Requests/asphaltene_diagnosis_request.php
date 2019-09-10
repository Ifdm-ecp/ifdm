<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class asphaltene_diagnosis_request extends Request
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
        $button_wr = (bool) isset($_POST['button_wr']);
        if (!$button_wr) {
            return [
                'drainage_radius'=> 'numeric|required|between:390,100000',
                'net_pay'=> 'numeric|required|between:1,1000',
                'wellbore_radius'=> 'numeric|required|between:0.2,4',
                'compressibility'=> 'numeric|required|between:0,1|not_in:0',
                'initial_pressure'=> 'numeric|required',
                'initial_porosity'=> 'numeric|required|between:0.15,0.49',
                'initial_permeability'=> 'numeric|required|between:8,5000',
                'average_pore_diameter'=> 'numeric|required',
                'asphaltene_particle_diameter'=> 'numeric|required|between:0.0001,0.6', 
                'asphaltene_apparent_density'=> 'numeric|required|between:0.1,1.4',
                'pvt_data_range_flag'=> 'in:1',
                'asphaltenes_data_range_flag'=> 'in:1',
            ];
        } else {
            return [];
        }
    }

    public function messages()
    {
        return [
            'drainage_radius.required' => 'Drainage radius required.',
            'net_pay.required' => 'Net pay required.',
            'wellbore_radius.required' => 'Wellbore radius required.',
            'compressibility.required' => 'Compressibility required.',
            'initial_pressure.required' => 'Initial pressure required.',
            'initial_porosity.required' => 'Initial porosity required.',
            'initial_permeability.required' => 'Initial permeability required.',
            'average_pore_diameter.required' => 'Average pore diameter required.',
            'asphaltene_particle_diameter.required' => 'Asphaltene particle diameter required.',
            'asphaltene_apparent_density.required' => 'Asphaltene apparent density required.',

            'drainage_radius.numeric' => 'Drainage radius must be numeric.',
            'net_pay.numeric' => 'Net pay must be numeric.',
            'wellbore_radius.numeric' => 'Wellbore radius must be numeric.',
            'compressibility.numeric' => 'Compressibility must be numeric.',
            'initial_pressure.numeric' => 'Initial pressure must be numeric.',
            'initial_porosity.numeric' => 'Initial porosity must be numeric.',
            'initial_permeability.numeric' => 'Initial permeability must be numeric.',
            'average_pore_diameter.numeric' => 'Average pore diameter must be numeric.',
            'asphaltene_particle_diameter.numeric' => 'Asphaltene particle diameter must be numeric.',
            'asphaltene_apparent_density.numeric' => 'Asphaltene apparent density must be numeric.',

            'drainage_radius.between' => 'Drainage radius must be between 390 and 100000.',
            'net_pay.between' => 'Net pay must be between 100 and 1000.',
            'wellbore_radius.between' => 'Wellbore radius must be between 0.2 and 4.',
            'compressibility.between' => 'Compressibility must be between 0 and 1 (not including 0).',
            'compressibility.not_in' => 'Compressibility must be between 0 and 1 (not including 0).',
            'initial_porosity.between' => 'Initial porosity must be between 0.15 and 0.49.',
            'initial_permeability.between' => 'Initial permeability must be between 8 and 5000.',
            'asphaltene_particle_diameter.between' => 'Asphaltene particle diameter must be between 0.0001 and 0.6.',
            'asphaltene_apparent_density.between' => 'Asphaltene apparent density must be between 0.1 and 1.4.',

            'pvt_data_range_flag.in' => 'All the data included in the PVT Table must be positive. ',
            'asphaltenes_data_range_flag.in' => 'All the data included in the Asphaltenes Table must be positive. ',
        ];
    }
}
