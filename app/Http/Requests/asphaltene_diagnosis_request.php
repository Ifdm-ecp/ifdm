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
                'drainage_radius'=> 'numeric|required|between:1,100000',
                'net_pay'=> 'numeric|required|between:1,100000',
                'wellbore_radius'=> 'numeric|required|between:0.2,4',
                'current_pressure'=> 'numeric|required|between:0,100000|not_in:0',
                'initial_pressure'=> 'numeric|required|between:0,100000|not_in:0',
                'initial_porosity'=> 'numeric|required|between:0,1|not_in:0',
                'initial_permeability'=> 'numeric|required|between:1,10000',
                'average_pore_diameter'=> 'numeric|required|between:0.6,100',
                'asphaltene_particle_diameter'=> 'numeric|required|between:0.0001,100', 
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
            'current_pressure.required' => 'Current reservoir pressure required.',
            'initial_pressure.required' => 'Initial reservoir pressure required.',
            'initial_porosity.required' => 'Initial porosity required.',
            'initial_permeability.required' => 'Initial permeability required.',
            'average_pore_diameter.required' => 'Average pore diameter required.',
            'asphaltene_particle_diameter.required' => 'Asphaltene particle diameter required.',
            'asphaltene_apparent_density.required' => 'Asphaltene apparent density required.',

            'drainage_radius.numeric' => 'Drainage radius must be numeric.',
            'net_pay.numeric' => 'Net pay must be numeric.',
            'wellbore_radius.numeric' => 'Wellbore radius must be numeric.',
            'current_pressure.numeric' => 'Current reservoir pressure must be numeric.',
            'initial_pressure.numeric' => 'Initial reservoir pressure must be numeric.',
            'initial_porosity.numeric' => 'Initial porosity must be numeric.',
            'initial_permeability.numeric' => 'Initial permeability must be numeric.',
            'average_pore_diameter.numeric' => 'Average pore diameter must be numeric.',
            'asphaltene_particle_diameter.numeric' => 'Asphaltene particle diameter must be numeric.',
            'asphaltene_apparent_density.numeric' => 'Asphaltene apparent density must be numeric.',

            'drainage_radius.between' => 'Drainage radius must be between 1 and 100000.',
            'net_pay.between' => 'Net pay must be between 1 and 100000.',
            'wellbore_radius.between' => 'Wellbore radius must be between 0.2 and 4.',
            'current_pressure.between' => 'Current reservoir pressure must be between 0 and 100000 (not including 0).',
            'current_pressure.not_in' => 'Current reservoir pressure must be between 0 and 100000 (not including 0).',
            'initial_pressure.between' => 'Initial reservoir pressure must be between 0 and 100000 (not including 0).',
            'initial_pressure.not_in' => 'Initial reservoir pressure must be between 0 and 100000 (not including 0).',
            'initial_porosity.between' => 'Initial porosity must be between 0 and 1 (not including 0).',
            'initial_porosity.not_in' => 'Initial porosity must be between 0 and 1 (not including 0).',
            'initial_permeability.between' => 'Initial permeability must be between 1 and 10000.',
            'average_pore_diameter.between' => 'Average pore diameter must be between 0.6 and 100.',
            'asphaltene_particle_diameter.between' => 'Asphaltene particle diameter must be between 0.0001 and 100.',
            'asphaltene_apparent_density.between' => 'Asphaltene apparent density must be between 0.1 and 1.4.',

            'pvt_data_range_flag.in' => 'All the data included in the PVT Table must be positive. ',
            'asphaltenes_data_range_flag.in' => 'All the data included in the Asphaltenes Table must be positive. ',
        ];
    }
}
