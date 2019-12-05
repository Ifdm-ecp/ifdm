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
        $rules = [
            'components' => 'required',
            'plus_fraction_molecular_weight' => 'numeric|between:100,2000',
            'plus_fraction_specific_gravity' => 'numeric|between:0.7,1.5',
            'plus_fraction_boiling_temperature' => 'numeric|between:650,2000',
            'sample_molecular_weight' => 'numeric|min:0|not_in:0',
            'critical_temperature' => 'required|numeric|min:0|not_in:0',
            'critical_pressure' => 'required|numeric|min:0|not_in:0',
            'density_at_reservoir_pressure' => 'required|numeric|between:0.5,1.5',
            'density_at_bubble_pressure' => 'required|numeric|between:0.5,1.5',
            'density_at_atmospheric_pressure' => 'required|numeric|between:0.5,1.5',
            'reservoir_temperature' => 'required|numeric|min:0|not_in:0',
            'current_reservoir_pressure' => 'required|numeric|min:0|not_in:0',
            'fluid_api_gravity' => 'required|numeric|between:5,70',
            'number_of_temperatures' => 'required|numeric|between:1,20',
            'temperature_delta' => 'required|numeric|between:20,100',
            'asphaltene_particle_diameter' => 'required|numeric|between:1,20',
            'asphaltene_molecular_weight' => 'required|numeric|between:0,2000|not_in:0,2000',
            'asphaltene_apparent_density' => 'required|numeric|between:0.7,1.5',
            'saturate' => 'required|numeric|between:0,100',
            'aromatic' => 'required|numeric|between:0,100|not_in:0',
            'resine' => 'required|numeric|between:0,100|not_in:0',
            'asphaltene' => 'required|numeric|between:0,100',
            'hydrogen_carbon_ratio' => 'required_if:elemental_data_selector,1,on|numeric',
            'oxygen_carbon_ratio' => 'required_if:elemental_data_selector,1,on|numeric',
            'nitrogen_carbon_ratio' => 'required_if:elemental_data_selector,1,on|numeric',
            'sulphure_carbon_ratio' => 'required_if:elemental_data_selector,1,on|numeric',
            'fa_aromaticity' => 'required_if:elemental_data_selector,1,on|numeric',
            'vc_molar_volume' => 'required_if:elemental_data_selector,1,on|numeric',
            'sum_zi_components_table' => 'numeric|between:0.99,1.1',
            'zi_range_flag_components_table' => 'in:1',
            'binary_coefficients_range_flag' => 'in:1',
            'bubble_point_data_range_flag' => 'in:1',
            'only_s' => 'required|in:run,save',
        ];

        /* Agregar validacion para SARA */
        if (is_numeric($this->input('saturate')) and is_numeric($this->input('aromatic')) and is_numeric($this->input('resine')) and is_numeric($this->input('asphaltene'))) {
            $sara = $this->input('saturate') + $this->input('aromatic') + $this->input('resine') + $this->input('asphaltene');

            if ($sara > 101 || $sara < 99) {
                $rules['saturate'] = 'required|numeric|between:99,101';
            }
        }

        $value_bubble_point_table = json_decode(str_replace(",[null,null]", "", $this->input("value_bubble_point_table")));
        $temperature = [];
        foreach ($value_bubble_point_table as $value) {
            array_push($temperature, str_replace(",", ".", $value[0]));
        }
        $min_temperature = min($temperature);

        $rules['initial_temperature'] = 'required|numeric|not_in:0|between:' . $min_temperature . ',' . $this->input('critical_temperature');

        if ($this->only_s == "save") {
            $rules["components"] = str_replace("required|", "", $rules["components"]);
            $rules["plus_fraction_molecular_weight"] = str_replace("required|", "", $rules["plus_fraction_molecular_weight"]);
            $rules["plus_fraction_specific_gravity"] = str_replace("required|", "", $rules["plus_fraction_specific_gravity"]);
            $rules["plus_fraction_boiling_temperature"] = str_replace("required|", "", $rules["plus_fraction_boiling_temperature"]);
            $rules["sample_molecular_weight"] = str_replace("required|", "", $rules["sample_molecular_weight"]);
            $rules["critical_temperature"] = str_replace("required|", "", $rules["critical_temperature"]);
            $rules["critical_pressure"] = str_replace("required|", "", $rules["critical_pressure"]);
            $rules["density_at_reservoir_pressure"] = str_replace("required|", "", $rules["density_at_reservoir_pressure"]);
            $rules["density_at_bubble_pressure"] = str_replace("required|", "", $rules["density_at_bubble_pressure"]);
            $rules["density_at_atmospheric_pressure"] = str_replace("required|", "", $rules["density_at_atmospheric_pressure"]);
            $rules["reservoir_temperature"] = str_replace("required|", "", $rules["reservoir_temperature"]);
            $rules["current_reservoir_pressure"] = str_replace("required|", "", $rules["current_reservoir_pressure"]);
            $rules["fluid_api_gravity"] = str_replace("required|", "", $rules["fluid_api_gravity"]);
            $rules["number_of_temperatures"] = str_replace("required|", "", $rules["number_of_temperatures"]);
            $rules["temperature_delta"] = str_replace("required|", "", $rules["temperature_delta"]);
            $rules["asphaltene_particle_diameter"] = str_replace("required|", "", $rules["asphaltene_particle_diameter"]);
            $rules["asphaltene_molecular_weight"] = str_replace("required|", "", $rules["asphaltene_molecular_weight"]);
            $rules["asphaltene_apparent_density"] = str_replace("required|", "", $rules["asphaltene_apparent_density"]);
            $rules["saturate"] = str_replace("required|", "", $rules["saturate"]);
            $rules["aromatic"] = str_replace("required|", "", $rules["aromatic"]);
            $rules["resine"] = str_replace("required|", "", $rules["resine"]);
            $rules["asphaltene"] = str_replace("required|", "", $rules["asphaltene"]);
            $rules["hydrogen_carbon_ratio"] = str_replace("required|", "", $rules["hydrogen_carbon_ratio"]);
            $rules["oxygen_carbon_ratio"] = str_replace("required|", "", $rules["oxygen_carbon_ratio"]);
            $rules["nitrogen_carbon_ratio"] = str_replace("required|", "", $rules["nitrogen_carbon_ratio"]);
            $rules["sulphure_carbon_ratio"] = str_replace("required|", "", $rules["sulphure_carbon_ratio"]);
            $rules["fa_aromaticity"] = str_replace("required|", "", $rules["fa_aromaticity"]);
            $rules["vc_molar_volume"] = str_replace("required|", "", $rules["vc_molar_volume"]);
            $rules["sum_zi_components_table"] = str_replace("required|", "", $rules["sum_zi_components_table"]);
            $rules["zi_range_flag_components_table"] = str_replace("required|", "", $rules["zi_range_flag_components_table"]);
            $rules["binary_coefficients_range_flag"] = str_replace("required|", "", $rules["binary_coefficients_range_flag"]);
            $rules["bubble_point_data_range_flag"] = str_replace("required|", "", $rules["bubble_point_data_range_flag"]);
        }

        return $rules;
    }



    public function messages()
    {
        return [
            'components.required' => 'The components is required.',
            'correlation.required' => 'The correlation is required.',
            'critical_temperature.required' => 'The critical temperature is required.',
            'critical_pressure.required' => 'The critical pressure is required.',
            'density_at_reservoir_pressure.required' => 'The density at reservoir pressure is required.',
            'density_at_bubble_pressure.required' => 'The density at bubble pressure is required.',
            'density_at_atmospheric_pressure.required' => 'The density at atmospheric pressure is required.',
            'reservoir_temperature.required' => 'The reservoir temperature is required.',
            'current_reservoir_pressure.required' => 'The current reservoir pressure is required.',
            'fluid_api_gravity.required' => 'The fluid API gravity is required.',
            'initial_temperature.required' => 'The initial temperature is required.',
            'number_of_temperatures.required' => 'The number of temperatures is required.',
            'temperature_delta.required' => 'The temperature delta is required.',
            'asphaltene_particle_diameter.required' => 'The asphaltene particle diameter is required.',
            'asphaltene_molecular_weight.required' => 'The asphaltene molecular weight is required.',
            'asphaltene_apparent_density.required' => 'The asphaltene apparent density is required.',
            'saturate.required' => 'The saturate is required.',
            'aromatic.required' => 'The aromatic is required.',
            'resine.required' => 'The resine is required.',
            'asphaltene.required' => 'The asphaltene is required.',

            'hydrogen_carbon_ratio.required_if' => 'The hydrogen carbon ratio is required.',
            'oxygen_carbon_ratio.required_if' => 'The oxygen carbon ratio is required.',
            'nitrogen_carbon_ratio.required_if' => 'The nitrogen carbon ratio is required.',
            'sulphure_carbon_ratio.required_if' => 'The sulphure carbon ratio is required.',
            'fa_aromaticity.required_if' => 'The FA aromaticity is required.',
            'vc_molar_volume.required_if' => 'The VC molar volume is required.',

            'plus_fraction_molecular_weight.required' => 'The plus fraction molecular weight is required.',
            'plus_fraction_specific_gravity.required' => 'The plus fraction specific gravity is required.',
            'plus_fraction_boiling_temperature.required' => 'The plus fraction boiling temperature is required.',
            'sample_molecular_weight.required' => 'The sample molecular weight is required.',

            'plus_fraction_molecular_weight.numeric' => 'The plus fraction molecular weight must be a number.',
            'plus_fraction_specific_gravity.numeric' => 'The plus fraction specific gravity must be a number.',
            'plus_fraction_boiling_temperature.numeric' => 'The plus fraction boiling temperature must be a number.',
            'sample_molecular_weight.numeric' => 'The sample molecular weight must be a number.',
            'critical_temperature.numeric' => 'The critical temperature must be a number.',
            'critical_pressure.numeric' => 'The critical pressure must be a number.',
            'density_at_reservoir_pressure.numeric' => 'The density at reservoir pressure must be a number.',
            'density_at_bubble_pressure.numeric' => 'The censity at bubble pressure must be a number.',
            'density_at_atmospheric_pressure.numeric' => 'The density at atmospheric pressure must be a number.',
            'reservoir_temperature.numeric' => 'The reservoir temperature must be a number.',
            'current_reservoir_pressure.numeric' => 'The current reservoir pressure must be a number.',
            'fluid_api_gravity.numeric' => 'The fluid API gravity must be a number.',
            'initial_temperature.numeric' => 'The initial temperature must be a number.',
            'number_of_temperatures.numeric' => 'The number of temperatures must be a number.',
            'temperature_delta.numeric' => 'The temperature delta must be a number.',
            'asphaltene_particle_diameter.numeric' => 'The asphaltene particle diameter must be a number.',
            'asphaltene_molecular_weight.numeric' => 'The asphaltene molecular weight must be a number.',
            'asphaltene_apparent_density.numeric' => 'The asphaltene apparent density must be a number.',
            'saturate.numeric' => 'The saturate must be a number.',
            'aromatic.numeric' => 'The aromatic must be a number.',
            'resine.numeric' => 'The resine must be a number.',
            'asphaltene.numeric' => 'The asphaltene must be a number.',
            'hydrogen_carbon_ratio.numeric' => 'The hydrogen carbon ratio must be a number.',
            'oxygen_carbon_ratio.numeric' => 'The oxygen carbon ratio must be a number.',
            'nitrogen_carbon_ratio.numeric' => 'The nitrogen carbon ratio must be a number.',
            'sulphure_carbon_ratio.numeric' => 'The sulphure carbon ratio must be a number.',
            'fa_aromaticity.numeric' => 'The FA aromaticity must be a number.',
            'vc_molar_volume.numeric' => 'The VC molar volume must be a number.',

            'initial_temperature.not_in' => 'The initial temperature can not be 0.',
            'number_of_temperatures.between' => 'The number of temperatures is not between :min - :max.',
            'temperature_delta.between' => 'The temperature delta is not between :min - :max.',

            'saturate.between' => 'The SARA is not between :min - :max.',

            'plus_fraction_molecular_weight.between' => 'The plus fraction molecular weight is not between :min - :max.',
            'plus_fraction_specific_gravity.between' => 'The plus fraction specific gravity is not between :min - :max.',
            'plus_fraction_boiling_temperature.between' => 'The plus fraction boiling temperature is not between :min - :max.',
            'sample_molecular_weight.min' => 'The sample molecular weight must be greater than 0.',
            'sample_molecular_weight.not_in' => 'The sample molecular weight must be greater than 0.',

            'critical_temperature.min' => 'The critical temperature must be greater than 0.',
            'critical_temperature.not_in' => 'The critical temperature must be greater than 0.',
            'critical_pressure.min' => 'The critical pressure must be greater than 0.',
            'critical_pressure.not_in' => 'The critical pressure must be greater than 0.',
            'density_at_reservoir_pressure.between' => 'The density at reservoir pressure must be between 0.5 and 1.5.',
            'density_at_bubble_pressure.between' => 'The density at bubble pressure must be between 0.5 and 1.5.',
            'density_at_atmospheric_pressure.between' => 'The density at atmospheric pressure must be between 0.5 and 1.5.',
            'reservoir_temperature.min' => 'The reservoir temperature must be greater than 0.',
            'reservoir_temperature.not_in' => 'The reservoir temperature must be greater than 0.',
            'current_reservoir_pressure.min' => 'The current reservoir pressure must be greater than 0.',
            'current_reservoir_pressure.not_in' => 'The current reservoir pressure must be greater than 0.',
            'fluid_api_gravity.between' => 'The fluid API gravity must be between 5 and 70.',

            'asphaltene_particle_diameter.between' => 'The asphaltene Particle Diameter is not between :min - :max.',
            'asphaltene_molecular_weight.between' => 'The asphaltene Molecular Weight must be greater than 0 and less than 2000.',
            'asphaltene_molecular_weight.not_in' => 'The asphaltene Molecular Weight must be greater than 0 and less than 2000.',
            'asphaltene_apparent_density.between' => 'The asphaltene Apparent Density is not between :min - :max.',

            'sum_zi_components_table.between' => 'The sum of Zi in components table must be 1+-0.1.',
            'zi_range_flag_components_table.in' => 'All the Zi data in components table must be between 0 and 1.',
            'binary_coefficients_range_flag.in' => 'All the Binary Interaction Coefficients Data must be between -10 and 10.',
            'bubble_point_data_range_flag.in' => 'All Temperature and Bubble Point data must be positive numbers, please check your Bubble Point Table.',

            'saturate.between' => 'Saturated is not between :min - :max.',
            'aromatic.between' => 'Aromatics is not between :min - :max.',
            'resine.between' => 'Resines is not between :min - :max.',
            'asphaltene.between' => 'Asphaltenes is not between :min - :max.',

            'aromatic.not_in' => 'Aromatics must be greater than 0.',
            'resine.not_in' => 'Resines must be greater than 0.',
        ];
    }
}
