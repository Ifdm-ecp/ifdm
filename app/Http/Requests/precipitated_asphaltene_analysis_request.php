<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class precipitated_asphaltene_analysis_request extends Request
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

        $rules = [];

        if (!$button_wr) {
            $rules['components'] = 'required';
            $rules['plus_fraction_molecular_weight'] = 'numeric|between:100,2000';
            $rules['plus_fraction_specific_gravity'] = 'numeric|between:0.7,1.5';
            $rules['plus_fraction_boiling_temperature'] = 'numeric|between:650,2000';
            $rules['sample_molecular_weight'] = 'numeric|min:0|not_in:0';
            $rules['critical_temperature'] = 'numeric|required|min:0|not_in:0';
            $rules['critical_pressure'] = 'numeric|required|min:0|not_in:0';
            $rules['density_at_reservoir_pressure'] = 'numeric|required|between:0.5,1.5';
            $rules['density_at_bubble_pressure'] = 'numeric|required|between:0.5,1.5';
            $rules['density_at_atmospheric_pressure'] = 'numeric|required|between:0.5,1.5';
            $rules['reservoir_temperature'] = 'numeric|required|min:0|not_in:0';
            $rules['current_reservoir_pressure'] = 'numeric|required|min:0|not_in:0';
            $rules['fluid_api_gravity'] = 'numeric|required|between:5,70';
            $rules['number_of_temperatures'] = 'numeric|required|between:1,20';
            $rules['temperature_delta'] = 'numeric|required|between:20,100';
            $rules['asphaltene_particle_diameter'] = 'numeric|required|between:1,20';
            $rules['asphaltene_molecular_weight'] = 'numeric|required|between:0,2000|not_in:0,2000';
            $rules['asphaltene_apparent_density'] = 'numeric|required|between:0.7,1.5';
            $rules['saturate'] = 'numeric|required|between:0,100';
            $rules['aromatic'] = 'numeric|required|between:0,100|not_in:0';
            $rules['resine'] = 'numeric|required|between:0,100|not_in:0';
            $rules['asphaltene'] = 'numeric|required|between:0,100';
            $rules['hydrogen_carbon_ratio'] = 'numeric|required_if:elemental_data_selector,1,on';
            $rules['oxygen_carbon_ratio'] = 'numeric|required_if:elemental_data_selector,1,on';
            $rules['nitrogen_carbon_ratio'] = 'numeric|required_if:elemental_data_selector,1,on';
            $rules['sulphure_carbon_ratio'] = 'numeric|required_if:elemental_data_selector,1,on';
            $rules['fa_aromaticity'] = 'numeric|required_if:elemental_data_selector,1,on';
            $rules['vc_molar_volume'] = 'numeric|required_if:elemental_data_selector,1,on';
            $rules['sum_zi_components_table'] = 'numeric|between:0.99,1.1';
            $rules['zi_range_flag_components_table'] = 'in:1';
            $rules['binary_coefficients_range_flag'] = 'in:1';
            $rules['bubble_point_data_range_flag'] = 'in:1';
            /* dd($this->binary_coefficients_range_flag); */
            /* Agregar validacion para SARA */
            if(is_numeric($this->input('saturate')) and is_numeric($this->input('aromatic')) and is_numeric($this->input('resine')) and is_numeric($this->input('asphaltene'))){
                $sara = $this->input('saturate') + $this->input('aromatic') + $this->input('resine') + $this->input('asphaltene');

                if($sara > 101 || $sara < 99){
                    $rules['saturate'] = 'numeric|required|between:99,101';
                }
            }
            

            $value_bubble_point_table = json_decode(str_replace(",[null,null]","",$this->input("value_bubble_point_table")));
            $temperature = [];
            foreach ($value_bubble_point_table as $value) {
                array_push($temperature, str_replace(",", ".", $value[0]));
            }
            $min_temperature = min($temperature);

            $rules['initial_temperature'] = 'numeric|required|not_in:0|between:'.$min_temperature.','.$this->input('critical_temperature');
        }

        return $rules;

    }



    public function messages()
    {
        return [
            'components.required' => 'Components required.',
            'correlation.required' => 'Correlation required.',
            'critical_temperature.required' => 'Critical temperature required.',
            'critical_pressure.required' => 'Critical pressure required.',
            'density_at_reservoir_pressure.required' => 'Density at reservoir pressure required.',
            'density_at_bubble_pressure.required' => 'Density at bubble pressure required.',
            'density_at_atmospheric_pressure.required' => 'Density at atmospheric pressure required.',
            'reservoir_temperature.required' => 'Reservoir temperature required.',
            'current_reservoir_pressure.required' => 'Current reservoir pressure required.',
            'fluid_api_gravity.required' => 'Fluid API gravity required.',
            'initial_temperature.required' => 'Initial temperature required.',
            'number_of_temperatures.required' => 'Number of temperatures required.',
            'temperature_delta.required' => 'Temperature delta required.',
            'asphaltene_particle_diameter.required' => 'Asphaltene particle diameter required.',
            'asphaltene_molecular_weight.required' => 'Asphaltene molecular weight required.',
            'asphaltene_apparent_density.required' => 'Asphaltene apparent density required.',
            'saturate.required' => 'Saturate required.',
            'aromatic.required' => 'Aromatic required.',
            'resine.required' => 'Resine required.',
            'asphaltene.required' => 'Asphaltene required.',

            'hydrogen_carbon_ratio.required_if' => 'Hydrogen carbon ratio required.',
            'oxygen_carbon_ratio.required_if' => 'Oxygen carbon ratio required.',
            'nitrogen_carbon_ratio.required_if' => 'Nitrogen carbon ratio required.',
            'sulphure_carbon_ratio.required_if' => 'Sulphure carbon ratio required.',
            'fa_aromaticity.required_if' => 'FA aromaticity required.',
            'vc_molar_volume.required_if' => 'VC molar volume required.',

            'plus_fraction_molecular_weight.required' => 'Plus fraction molecular weight required.',
            'plus_fraction_specific_gravity.required' => 'Plus fraction specific gravity required.',
            'plus_fraction_boiling_temperature.required' => 'Plus fraction boiling temperature required.',
            'sample_molecular_weight.required' => 'Sample molecular weight required.',

            'plus_fraction_molecular_weight.numeric' => 'Plus fraction molecular weight must be numeric.',
            'plus_fraction_specific_gravity.numeric' => 'Plus fraction specific gravity must be numeric.',
            'plus_fraction_boiling_temperature.numeric' => 'Plus fraction boiling temperature must be numeric.',
            'sample_molecular_weight.numeric' => 'Sample molecular weight must be numeric.',
            'critical_temperature.numeric' => 'Critical temperature must be numeric.',
            'critical_pressure.numeric' => 'Critical pressure must be numeric.',
            'density_at_reservoir_pressure.numeric' => 'Density at reservoir pressure must be numeric.',
            'density_at_bubble_pressure.numeric' => 'Density at bubble pressure must be numeric.',
            'density_at_atmospheric_pressure.numeric' => 'Density at atmospheric pressure must be numeric.',
            'reservoir_temperature.numeric' => 'Reservoir temperature must be numeric.',
            'current_reservoir_pressure.numeric' => 'Current reservoir pressure must be numeric.',
            'fluid_api_gravity.numeric' => 'Fluid API gravity must be numeric.',
            'initial_temperature.numeric' => 'Initial temperature must be numeric.',
            'number_of_temperatures.numeric' => 'Number of temperatures must be numeric.',
            'temperature_delta.numeric' => 'Temperature delta must be numeric.',
            'asphaltene_particle_diameter.numeric' => 'Asphaltene particle diameter must be numeric.',
            'asphaltene_molecular_weight.numeric' => 'Asphaltene molecular weight must be numeric.',
            'asphaltene_apparent_density.numeric' => 'Asphaltene apparent density must be numeric.',
            'saturate.numeric' => 'Saturate must be numeric.',
            'aromatic.numeric' => 'Aromatic must be numeric.',
            'resine.numeric' => 'Resine must be numeric.',
            'asphaltene.numeric' => 'Asphaltene must be numeric.',
            'hydrogen_carbon_ratio.numeric' => 'Hydrogen carbon ratio must be numeric.',
            'oxygen_carbon_ratio.numeric' => 'Oxygen carbon ratio must be numeric.',
            'nitrogen_carbon_ratio.numeric' => 'Nitrogen carbon ratio must be numeric.',
            'sulphure_carbon_ratio.numeric' => 'Sulphure carbon ratio must be numeric.',
            'fa_aromaticity.numeric' => 'FA aromaticity must be numeric.',
            'vc_molar_volume.numeric' => 'VC molar volume must be numeric.',

            'initial_temperature.not_in' => 'Initial temperature can not be 0.',
            'number_of_temperatures.between' => 'Number of temperatures must be between 1 and 20.',
            'temperature_delta.between' => 'Temperature delta must be between 20 and 100.',

            'saturate.between' => 'SARA must be between 0 and 100.',

            'plus_fraction_molecular_weight.between' => 'Plus fraction molecular weight must be between 100 and 2000.',
            'plus_fraction_specific_gravity.between' => 'Plus fraction specific gravity must be between 0.7 and 1.5.',
            'plus_fraction_boiling_temperature.between' => 'Plus fraction boiling temperature must be between 650 and 2000.',
            'sample_molecular_weight.min' => 'Sample molecular weight must be greater than 0.',
            'sample_molecular_weight.not_in' => 'Sample molecular weight must be greater than 0.',

            'critical_temperature.min' => 'Critical temperature must be greater than 0.',
            'critical_temperature.not_in' => 'Critical temperature must be greater than 0.',
            'critical_pressure.min' => 'Critical pressure must be greater than 0.',
            'critical_pressure.not_in' => 'Critical pressure must be greater than 0.',
            'density_at_reservoir_pressure.between' => 'Density at reservoir pressure must be between 0.5 and 1.5.',
            'density_at_bubble_pressure.between' => 'Density at bubble pressure must be between 0.5 and 1.5.',
            'density_at_atmospheric_pressure.between' => 'Density at atmospheric pressure must be between 0.5 and 1.5.',
            'reservoir_temperature.min' => 'Reservoir temperature must be greater than 0.',
            'reservoir_temperature.not_in' => 'Reservoir temperature must be greater than 0.',
            'current_reservoir_pressure.min' => 'Current reservoir pressure must be greater than 0.',
            'current_reservoir_pressure.not_in' => 'Current reservoir pressure must be greater than 0.',
            'fluid_api_gravity.between' => 'Fluid API gravity must be between 5 and 70.',

            'asphaltene_particle_diameter.between' => 'Asphaltene Particle Diameter must be between 1 and 20.',
            'asphaltene_molecular_weight.between' => 'Asphaltene Molecular Weight must be greater than 0 and less than 2000.',
            'asphaltene_molecular_weight.not_in' => 'Asphaltene Molecular Weight must be greater than 0 and less than 2000.',
            'asphaltene_apparent_density.between' => 'Asphaltene Apparent Density must be between 0.7 and 1.5.',

            'sum_zi_components_table.between' => 'The sum of Zi in components table must be 1+-0.1.',
            'zi_range_flag_components_table.in' => 'All the Zi data in components table must be between 0 and 1.',
            'binary_coefficients_range_flag.in' => 'All the Binary Interaction Coefficients Data must be between -10 and 10.',
            'bubble_point_data_range_flag.in' => 'All Temperature and Bubble Point data must be positive numbers, please check your Bubble Point Table.',

            'saturate.between' => 'Saturated must be between 0 and 100.',
            'aromatic.between' => 'Aromatics must be between 0 and 100.',
            'resine.between' => 'Resines must be between 0 and 100.',
            'asphaltene.between' => 'Asphaltenes must be between 0 and 100.',

            'aromatic.not_in' => 'Aromatics must be greater than 0.',
            'resine.not_in' => 'Resines must be greater than 0.',
        ];
    }
}
