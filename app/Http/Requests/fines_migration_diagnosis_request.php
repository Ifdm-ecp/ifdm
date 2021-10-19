<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class fines_migration_diagnosis_request extends Request
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
        $well_radius = $this->well_radius;
        $drainage_radius = $this->drainage_radius;

        if (!isset($_POST['button_wr'])) {
            return [
                'drainage_radius'=> 'numeric|required|min:'.$well_radius.'|min:0|not_in:0|not_in:'.$well_radius,
                'formation_height'=> 'numeric|required|min:0|not_in:0',
                'well_radius'=> 'numeric|required|min:0|not_in:0|max:'.$drainage_radius.'|not_in:'.$drainage_radius,
                'perforation_radius'=> 'numeric|required_with:perforation_density|min:0|not_in:0',
                'perforation_density'=> 'numeric|required_with:perforation_radius|min:0',
                'current_permeability'=> 'numeric|required|min:0|not_in:0',
                'initial_porosity'=> 'numeric|required|between:0,1|not_in:0,1',
                'current_pressure'=> 'numeric|required|min:0|not_in:0,1',
                'initial_permeability'=> 'numeric|required|min:0|not_in:0',
                'average_pore_diameter'=> 'numeric|min:0|not_in:0', 
                'initial_pressure'=> 'numeric|required|min:0|not_in:0',
                'fine_density' => 'numeric|required|min:0|not_in:0',
                'fine_diameter' => 'numeric|min:0|not_in:0',
                'initial_deposited_fines_concentration' => 'numeric|required|min:0',
                'water_volumetric_factor' => 'numeric|between:0,2',
                'plug_radius' => 'numeric|between:0,1',
                'critical_rate' => 'numeric|required|min:0',
                'length' => 'numeric',
                'diameter' => 'numeric',
                'porosity' => 'numeric',
                'quartz' => 'numeric',
                'illite' => 'numeric',
                'kaolinite' => 'numeric',
                'chlorite' => 'numeric',
                'emectite' => 'numeric',
                'total_amount_of_clays' => 'numeric',
                'feldspar' => 'numeric',
                'initial_fines_concentration_in_fluid' => 'numeric|required|min:0',
            ];
        } else {
            return [];
        }
    }

    public function messages()
    {
        return [
            'drainage_radius.required'=> 'Drainage radius required.',
            'formation_height.required'=> 'Formation height required.',
            'well_radius.required'=> 'Well radius required.',
            'perforation_radius.required_with'=> 'Perforation radius is required if Number of perforations is set.',
            'perforation_density.required_with'=> 'Number of perforations is required if Perforation radius is set.',
            'current_permeability.required'=> 'Current Permeability is required.',
            'initial_porosity.required'=> 'Initial porosity required.',
            'current_pressure.required'=> 'Current Pressure is required.',
            'initial_permeability.required'=> 'Initial permeability required.',
            'average_pore_diameter.required'=> 'Average pore diameter required.',
            'initial_pressure.required'=> 'Initial pressure required.',
            'fine_density.required' => 'Fine density required.',
            'fine_diameter.required' => 'Fine diameter required.',
            'initial_deposited_fines_concentration.required' => 'Initial deposited fines Concentration required.',
            'water_volumetric_factor.required' => 'Water volumetric factor required.',
            'plug_radius.required' => 'Plug radius required.',
            'critical_rate.required' => 'Critical rate required.',
            'length.required' => 'Length required.',
            'diameter.required' => 'Diameter required.',
            'porosity.required' => 'Porosity required.',
            'quartz.required' => 'Quartz required.',
            'illite.required' => 'Illite required.',
            'kaolinite.required' => 'Kaolinite required.',
            'chlorite.required' => 'Chlorite required.',
            'emectite.required' => 'Emectite required.',
            'total_amount_of_clays.required' => 'Total amount of clays required.',
            'feldspar.required' => 'Feldspar required.',
            'clay.required' => 'Clay required.',
            'final_date.required' => 'Final date required.',
            'amount_of_dates.required' => 'Amount of dates required.',
            'initial_fines_concentration_in_fluid.required' => 'Initial Finces Concentration in Fluid is required.',

            'drainage_radius.numeric'=> 'Drainage radius must be numeric.',
            'formation_height.numeric'=> 'Formation height must be numeric.',
            'well_radius.numeric'=> 'Well radius must be numeric.',
            'perforation_radius.numeric'=> 'Perforation radius must be numeric.',
            'perforation_density.numeric'=> 'Numbers of perforations must be numeric.',
            'current_permeability.numeric'=> 'Current Permeability is must be numeric.',
            'initial_porosity.numeric'=> 'Initial porosity must be numeric.',
            'current_pressure.numeric'=> 'The Current Pressure must be numeric.',
            'initial_permeability.numeric'=> 'Initial permeability must be numeric.',
            'average_pore_diameter.numeric'=> 'Average pore diameter must be numeric.',
            'initial_pressure.numeric'=> 'Initial pressure must be numeric.',
            'fine_density.numeric' => 'Fine density must be numeric.',
            'fine_diameter.numeric' => 'Fine diameter must be numeric.',
            'initial_deposited_fines_concentration.numeric' => 'Initial deposited fines concentration must be numeric.',
            'water_volumetric_factor.numeric' => 'Water volumetric factor must be numeric.',
            'plug_radius.numeric' => 'Plug radius must be numeric.',
            'critical_rate.numeric' => 'Critical rate must be numeric.',
            'length.numeric' => 'Length must be numeric.',
            'diameter.numeric' => 'Diameter must be numeric.',
            'porosity.numeric' => 'Porosity must be numeric.',
            'quartz.numeric' => 'Quartz must be numeric.',
            'illite.numeric' => 'Illite must be numeric.',
            'kaolinite.numeric' => 'Kaolinite must be numeric.',
            'chlorite.numeric' => 'Chlorite must be numeric.',
            'emectite.numeric' => 'Emectite must be numeric.',
            'total_amount_of_clays.numeric' => 'Total amount of clays must be numeric.',
            'feldspar.numeric' => 'Feldspar must be numeric.',
            'clay.numeric' => 'Clay must be numeric.',
            'final_date.numeric' => 'Final date must be numeric.',
            'amount_of_dates.numeric' => 'Amount of dates must be numeric.',
            'initial_fines_concentration_in_fluid.numeric' => 'Initial Finces Concentration in Fluid must be numeric.',

            'drainage_radius.min' => 'Drainage radius must be greater than 0 ft and Well Radius.',
            'drainage_radius.not_in' => 'Drainage radius must be greater than 100 ft and Well Radius.',
            'formation_height.min' => 'Net Pay must be greater than 0.',
            'formation_height.not_in' => 'Net Pay must be greater than 0.',
            'well_radius.min' => 'Well Radius must be greater than 0.',
            'well_radius.not_in' => 'Well Radius must be greater than 0 and less than Drainage Radius.',
            'well_radius.max' => 'Well Radius must be less than Drainage Radius.',
            'perforation_radius.not_in' => 'Perforation Radius must be greater than 0.',
            'current_permeability.min' => 'Current Permeability must be greater than 0.',
            'current_permeability.not_in' => 'Current Permeability is must be between 0 and 1.',
            'current_pressure.min' => 'Current Pressure must be greater than 0.',
            'current_pressure.not_in' => 'Current Pressure must be between 0 and 1 (0>value>1).',
            'initial_pressure.min' => 'Initial Pressure must be greater than 0.',
            'initial_pressure.not_in' => 'Initial Pressure must be greater than 0.',
            'initial_porosity.between' => 'Initial Porosity must be between 0 and 1 (0>value>1).',
            'initial_porosity.not_in' => 'Initial Porosity must be between 0 and 1 (0>value>1).',
            'initial_permeability.min' => 'Initial Permeability must be greater than 0.',
            'initial_permeability.not_in' => 'Initial Permeability must be greater than 0.',
            'average_pore_diameter.min' => 'Average Pore Diameter must be greater than 0.',
            'average_pore_diameter.not_in' => 'Average Pore Diameter must be greater than 0.',
            'fine_diameter.min' => 'Fine Diameter must be greater than 0.',
            'fine_diameter.not_in' => 'Fine Diameter must be greater than 0.',
            'water_volumetric_factor.between' => 'Water volumetric must be between 0 and 2.',
            'plug_radius.between' => 'Plug radius must be between 0 and 1.',
            'fine_density.min' => 'Fine Density must be greater than 0.',
            'fine_density.not_in' => 'Fine Density must be greater than 0.',
            'initial_fines_concentration_in_fluid.min' => 'Initial Fines Concentration in Fluid must be at least 0.',
            'initial_deposited_fines_concentration.min' => 'Initial Deposited Fines Concentration must be at least 0.',
            'critical_rate.min' => 'Critical Rate must be at least 0.',
            'perforation_density.min' => 'Number of Perforations must be at least 0.',
            'perforation_radius.min' => 'Perforation Radius must be at least 0.',
        ];
    }
}
