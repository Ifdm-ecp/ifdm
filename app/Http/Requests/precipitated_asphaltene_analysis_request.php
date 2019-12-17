<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class precipitated_asphaltene_analysis_request extends Request
{
    /**
     * Create a new request instance.
     *
     * @return void
     */
    public function __construct()
    {
        request()->merge(['array_components_table' => json_decode(request()->value_components_table)]);
        request()->merge(['array_binary_interaction_coefficients_table' => json_decode(request()->value_binary_interaction_coefficients_table)]);
        request()->merge(['array_bubble_point_table' => json_decode(request()->value_bubble_point_table)]);

        /* Agregar validacion para SARA */
        if (is_numeric(request()->saturate) || is_numeric(request()->aromatic) || is_numeric(request()->resine) || is_numeric(request()->asphaltene)) {
            $sara = (is_numeric(request()->saturate) ? request()->saturate : 0) +
            (is_numeric(request()->aromatic) ? request()->aromatic : 0) +
            (is_numeric(request()->resine) ? request()->resine : 0) +
            (is_numeric(request()->asphaltene) ? request()->asphaltene : 0);
            request()->merge(['sara_calc' => $sara]);
        } else {
            request()->merge(['sara_calc' => ""]);
        }
    }

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
            'components' => 'required|array',
            'array_components_table' => 'required|array',
            'plus_fraction_molecular_weight' => 'numeric|between:100,2000',
            'plus_fraction_specific_gravity' => 'numeric|between:0.5,2',
            'plus_fraction_boiling_temperature' => 'numeric|between:100,2000',
            'correlation' => 'in:Twu,Lee-Kesler,Kavett,Pedersen,Riazzi Daubert',
            'array_binary_interaction_coefficients_table' => 'required|array',
            'array_bubble_point_table' => 'required|array',
            'critical_temperature' => 'required|numeric|min:0|not_in:0',
            'critical_pressure' => 'required|numeric|min:0|not_in:0',
            'density_at_reservoir_pressure' => 'required|numeric|between:0.5,2',
            'density_at_bubble_pressure' => 'required|numeric|between:0.5,2',
            'density_at_atmospheric_pressure' => 'required|numeric|between:0.5,2',
            'reservoir_temperature' => 'required|numeric|min:0|not_in:0',
            'current_reservoir_pressure' => 'required|numeric|min:0|not_in:0',
            'fluid_api_gravity' => 'required|numeric|between:5,70',
            'number_of_temperatures' => 'required|numeric|between:5,20',
            'temperature_delta' => 'required|numeric|between:20,100',
            'asphaltene_particle_diameter' => 'required|numeric|between:1,20',
            'asphaltene_molecular_weight' => 'required|numeric|between:0,2000|not_in:0,2000',
            'asphaltene_apparent_density' => 'required|numeric|between:0.5,2',
            'saturate' => 'required|numeric|between:0,100|not_in:0,100',
            'aromatic' => 'required|numeric|between:0,100|not_in:0,100',
            'resine' => 'required|numeric|between:0,100|not_in:0,100',
            'asphaltene' => 'required|numeric|between:0,100|not_in:0,100',
            'sara_calc' => 'required|numeric|between:99,101',
            'hydrogen_carbon_ratio' => 'required_if:elemental_data_selector,1,on|numeric|between:0,1000',
            'oxygen_carbon_ratio' => 'required_if:elemental_data_selector,1,on|numeric|between:0,1000',
            'nitrogen_carbon_ratio' => 'required_if:elemental_data_selector,1,on|numeric|between:0,1000',
            'sulphure_carbon_ratio' => 'required_if:elemental_data_selector,1,on|numeric|between:0,1000',
            'fa_aromaticity' => 'required_if:elemental_data_selector,1,on|numeric|between:0,1000',
            'vc_molar_volume' => 'required_if:elemental_data_selector,1,on|numeric|between:0,1000',
            // 'zi_range_flag_components_table' => 'in:1',
            // 'binary_coefficients_range_flag' => 'in:1',
            // 'bubble_point_data_range_flag' => 'in:1',
            'only_s' => 'required|in:run,save',
        ];

        if (is_array($this->components)) {
            for ($i = 0; $i < count($this->components); $i++) {
                $rules["components." . $i] = 'required|in:N2,CO2,H2S,C1,C2,C3,IC4,NC4,IC5,NC5,NC6,NC7,NC8,NC9,NC10,NC11,NC12,NC13,NC14,NC15,NC16,NC17,NC18,NC19,NC20,NC21,NC22,NC23,NC24,FC6,FC7,FC8,FC9,FC10,FC11,FC12,FC13,FC14,FC15,FC16,FC17,FC18,FC19,FC20,FC21,FC22,FC23,FC24,FC25,FC26,FC27,FC28,FC29,FC30,FC31,FC32,FC33,FC34,FC35,FC36,FC37,FC38,FC39,FC40,FC41,FC42,FC43,FC44,FC45,SO2,H2,Plus +';
            }
        }

        if (is_array($this->array_components_table)) {
            if (count($this->array_components_table) > 0) {
                $rules["sum_zi_components_table"] = 'numeric|between:0.9,1.1';
            }

            for ($i = 0; $i < count($this->array_components_table); $i++) {
                $rules["array_components_table." . $i . ".0"] = 'required|in:N2,CO2,H2S,C1,C2,C3,IC4,NC4,IC5,NC5,NC6,NC7,NC8,NC9,NC10,NC11,NC12,NC13,NC14,NC15,NC16,NC17,NC18,NC19,NC20,NC21,NC22,NC23,NC24,FC6,FC7,FC8,FC9,FC10,FC11,FC12,FC13,FC14,FC15,FC16,FC17,FC18,FC19,FC20,FC21,FC22,FC23,FC24,FC25,FC26,FC27,FC28,FC29,FC30,FC31,FC32,FC33,FC34,FC35,FC36,FC37,FC38,FC39,FC40,FC41,FC42,FC43,FC44,FC45,SO2,H2,Plus +';
                $rules["array_components_table." . $i . ".1"] = 'required|numeric|between:0,1';
                $rules["array_components_table." . $i . ".2"] = 'required|numeric|between:0,2000';
                $rules["array_components_table." . $i . ".3"] = 'required|numeric|between:0,10000';
                $rules["array_components_table." . $i . ".4"] = 'required|numeric|between:-500,10000';
                $rules["array_components_table." . $i . ".5"] = 'required|numeric|between:-2,2';
                $rules["array_components_table." . $i . ".6"] = 'required|numeric|between:-5,1000';
                $rules["array_components_table." . $i . ".7"] = 'required|numeric|between:0,2';
                $rules["array_components_table." . $i . ".8"] = 'required|numeric|between:0,5000';
                $rules["array_components_table." . $i . ".9"] = 'required|numeric|between:0,1000';
            }
        }

        if (is_array($this->array_binary_interaction_coefficients_table)) {
            for ($i = 0; $i < count($this->array_binary_interaction_coefficients_table); $i++) {
                $rules["array_binary_interaction_coefficients_table." . $i . ".0"] = 'required|in:N2,CO2,H2S,C1,C2,C3,IC4,NC4,IC5,NC5,NC6,NC7,NC8,NC9,NC10,NC11,NC12,NC13,NC14,NC15,NC16,NC17,NC18,NC19,NC20,NC21,NC22,NC23,NC24,FC6,FC7,FC8,FC9,FC10,FC11,FC12,FC13,FC14,FC15,FC16,FC17,FC18,FC19,FC20,FC21,FC22,FC23,FC24,FC25,FC26,FC27,FC28,FC29,FC30,FC31,FC32,FC33,FC34,FC35,FC36,FC37,FC38,FC39,FC40,FC41,FC42,FC43,FC44,FC45,SO2,H2,Plus +';
                
                for ($j = 1; $j < count($this->array_binary_interaction_coefficients_table[$i]); $j++) {
                    $rules["array_binary_interaction_coefficients_table." . $i . "." . $j] = 'required|numeric|between:-10,10';
                }
            }
        }

        if (is_array($this->array_bubble_point_table)) {
            for ($i = 0; $i < count($this->array_bubble_point_table); $i++) {
                $rules["array_bubble_point_table." . $i . ".0"] = 'required|numeric|min:0|not_in:0';
                $rules["array_bubble_point_table." . $i . ".1"] = 'required|numeric|min:0|not_in:0';
            }
        }
        
        if ($this->only_s == "save") {
            $rules["components"] = str_replace("required|", "", $rules["components"]);
            $rules["array_components_table"] = str_replace("required|", "", $rules["array_components_table"]);
            $rules["array_binary_interaction_coefficients_table"] = str_replace("required|", "", $rules["array_binary_interaction_coefficients_table"]);
            $rules["array_bubble_point_table"] = str_replace("required|", "", $rules["array_bubble_point_table"]);
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
            $rules["sara_calc"] = str_replace("required|", "", $rules["sara_calc"]);
            $rules["hydrogen_carbon_ratio"] = str_replace("required|", "", $rules["hydrogen_carbon_ratio"]);
            $rules["oxygen_carbon_ratio"] = str_replace("required|", "", $rules["oxygen_carbon_ratio"]);
            $rules["nitrogen_carbon_ratio"] = str_replace("required|", "", $rules["nitrogen_carbon_ratio"]);
            $rules["sulphure_carbon_ratio"] = str_replace("required|", "", $rules["sulphure_carbon_ratio"]);
            $rules["fa_aromaticity"] = str_replace("required|", "", $rules["fa_aromaticity"]);
            $rules["vc_molar_volume"] = str_replace("required|", "", $rules["vc_molar_volume"]);
            if (array_key_exists("sum_zi_components_table", $rules)) {
                $rules["sum_zi_components_table"] = str_replace("required|", "", $rules["sum_zi_components_table"]);
            }
            // $rules["zi_range_flag_components_table"] = str_replace("required|", "", $rules["zi_range_flag_components_table"]);
            // $rules["binary_coefficients_range_flag"] = str_replace("required|", "", $rules["binary_coefficients_range_flag"]);
            // $rules["bubble_point_data_range_flag"] = str_replace("required|", "", $rules["bubble_point_data_range_flag"]);
        }

        return $rules;
    }

    public function messages()
    {
        $messages = [
            'components.required' => 'The components selection is required.',
            'components.array' => 'The data structure containing the components selection is incorrect.',
            'array_components_table.required' => 'The table components data is empty. Please check your data.',
            'array_components_table.array' => 'The data structure containing the components data table contents is incorrect.',
            'plus_fraction_molecular_weight.required' => 'The plus fraction molecular weight is required.',
            'plus_fraction_specific_gravity.required' => 'The plus fraction specific gravity is required.',
            'plus_fraction_boiling_temperature.required' => 'The plus fraction boiling temperature is required.',
            'correlation.in' => 'The correlation selected is not part of the allowed selection.',
            'array_binary_interaction_coefficients_table.required' => 'The table binary interaction coefficients data is empty. Please check your data.',
            'array_binary_interaction_coefficients_table.array' => 'The data structure containing the binary interaction coefficients data table contents is incorrect.',
            'array_bubble_point_table.required' => 'The table bubble point data is empty. Please check your data.',
            'array_bubble_point_table.array' => 'The data structure containing the bubble point data table contents is incorrect.',
            'critical_temperature.required' => 'The critical temperature is required.',
            'critical_pressure.required' => 'The critical pressure is required.',
            'density_at_reservoir_pressure.required' => 'The density at reservoir pressure is required.',
            'density_at_bubble_pressure.required' => 'The density at bubble pressure is required.',
            'density_at_atmospheric_pressure.required' => 'The density at atmospheric pressure is required.',
            'reservoir_temperature.required' => 'The reservoir temperature is required.',
            'current_reservoir_pressure.required' => 'The current reservoir pressure is required.',
            'fluid_api_gravity.required' => 'The fluid API gravity is required.',
            'number_of_temperatures.required' => 'The number of temperatures is required.',
            'temperature_delta.required' => 'The temperature delta is required.',
            'asphaltene_particle_diameter.required' => 'The asphaltene particle diameter is required.',
            'asphaltene_molecular_weight.required' => 'The asphaltene molecular weight is required.',
            'asphaltene_apparent_density.required' => 'The asphaltene apparent density is required.',
            'saturate.required' => 'The saturated is required.',
            'aromatic.required' => 'The aromatics is required.',
            'resine.required' => 'The resines is required.',
            'asphaltene.required' => 'The asphaltenes is required.',
            'sara_calc.required' => 'The SARA calculation is required.',

            'hydrogen_carbon_ratio.required_if' => 'The hydrogen carbon ratio is required.',
            'oxygen_carbon_ratio.required_if' => 'The oxygen carbon ratio is required.',
            'nitrogen_carbon_ratio.required_if' => 'The nitrogen carbon ratio is required.',
            'sulphure_carbon_ratio.required_if' => 'The sulphure carbon ratio is required.',
            'fa_aromaticity.required_if' => 'The FA aromaticity is required.',
            'vc_molar_volume.required_if' => 'The VC molar volume is required.',

            'sum_zi_components_table.numeric' => 'The sum of Zi in components table must be a number.',
            'plus_fraction_molecular_weight.numeric' => 'The plus fraction molecular weight must be a number.',
            'plus_fraction_specific_gravity.numeric' => 'The plus fraction specific gravity must be a number.',
            'plus_fraction_boiling_temperature.numeric' => 'The plus fraction boiling temperature must be a number.',
            'critical_temperature.numeric' => 'The critical temperature must be a number.',
            'critical_pressure.numeric' => 'The critical pressure must be a number.',
            'density_at_reservoir_pressure.numeric' => 'The density at reservoir pressure must be a number.',
            'density_at_bubble_pressure.numeric' => 'The censity at bubble pressure must be a number.',
            'density_at_atmospheric_pressure.numeric' => 'The density at atmospheric pressure must be a number.',
            'reservoir_temperature.numeric' => 'The reservoir temperature must be a number.',
            'current_reservoir_pressure.numeric' => 'The current reservoir pressure must be a number.',
            'fluid_api_gravity.numeric' => 'The fluid API gravity must be a number.',
            'number_of_temperatures.numeric' => 'The number of temperatures must be a number.',
            'temperature_delta.numeric' => 'The temperature delta must be a number.',
            'asphaltene_particle_diameter.numeric' => 'The asphaltene particle diameter must be a number.',
            'asphaltene_molecular_weight.numeric' => 'The asphaltene molecular weight must be a number.',
            'asphaltene_apparent_density.numeric' => 'The asphaltene apparent density must be a number.',
            'saturate.numeric' => 'The saturated must be a number.',
            'aromatic.numeric' => 'The aromatics must be a number.',
            'resine.numeric' => 'The resines must be a number.',
            'asphaltene.numeric' => 'The asphaltenes must be a number.',
            'sara_calc.numeric' => 'The SARA calculation must be a number.',
            'hydrogen_carbon_ratio.numeric' => 'The hydrogen carbon ratio must be a number.',
            'oxygen_carbon_ratio.numeric' => 'The oxygen carbon ratio must be a number.',
            'nitrogen_carbon_ratio.numeric' => 'The nitrogen carbon ratio must be a number.',
            'sulphure_carbon_ratio.numeric' => 'The sulphure carbon ratio must be a number.',
            'fa_aromaticity.numeric' => 'The FA aromaticity must be a number.',
            'vc_molar_volume.numeric' => 'The VC molar volume must be a number.',

            'number_of_temperatures.between' => 'The number of temperatures is not between :min - :max.',
            'temperature_delta.between' => 'The temperature delta is not between :min - :max.',

            'sara_calc.between' => 'The SARA calculation must be 100+-1.',

            'plus_fraction_molecular_weight.between' => 'The plus fraction molecular weight is not between :min - :max.',
            'plus_fraction_specific_gravity.between' => 'The plus fraction specific gravity is not between :min - :max.',
            'plus_fraction_boiling_temperature.between' => 'The plus fraction boiling temperature is not between :min - :max.',

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

            'saturate.between' => 'The saturated is not between :min - :max.',
            'aromatic.between' => 'The aromatics is not between :min - :max.',
            'resine.between' => 'The resines is not between :min - :max.',
            'asphaltene.between' => 'The asphaltenes is not between :min - :max.',
            
            'hydrogen_carbon_ratio.between' => 'The hydrogen carbon ratio is not between :min - :max.',
            'oxygen_carbon_ratio.between' => 'The oxygen carbon ratio is not between :min - :max.',
            'nitrogen_carbon_ratio.between' => 'The nitrogen carbon ratio is not between :min - :max.',
            'sulphure_carbon_ratio.between' => 'The sulphure carbon ratio is not between :min - :max.',
            'fa_aromaticity.between' => 'The FA aromaticity is not between :min - :max.',
            'vc_molar_volume.between' => 'The VC molar volume is not between :min - :max.',

            'saturate.not_in' => 'The saturated must be greater than 0 and less than 100.',
            'aromatic.not_in' => 'The aromatics must be greater than 0 and less than 100.',
            'resine.not_in' => 'The resines must be greater than 0 and less than 100.',
            'asphaltene.not_in' => 'The asphaltenes must be greater than 0 and less than 100.',
        ];

        if (is_array($this->components)) {
            for ($i = 0; $i < count($this->components); $i++) {
                $messages["components." . $i . ".required"] = 'The components selection has an empty value.';
                $messages["components." . $i . ".in"] = 'The components selection has a value that is not part of the allowed selection.';
            }
        }

        if (is_array($this->array_components_table)) {
            for ($i = 0; $i < count($this->array_components_table); $i++) {
                $messages["array_components_table." . $i . ".0.required"] = 'The table components data in row ' . ($i + 1) . ' and column Components has an empty value.';
                $messages["array_components_table." . $i . ".0.in"] = 'The table components data in row ' . ($i + 1) . ' and column Components has a value that is not part of the allowed selection.';
                $messages["array_components_table." . $i . ".1.required"] = 'The table components data in row ' . ($i + 1) . ' and column Zi has an empty value.';
                $messages["array_components_table." . $i . ".1.numeric"] = 'The table components data in row ' . ($i + 1) . ' and column Zi must be a number.';
                $messages["array_components_table." . $i . ".1.between"] = 'The table components data in row ' . ($i + 1) . ' and column Zi is not between :min - :max.';
                $messages["array_components_table." . $i . ".2.required"] = 'The table components data in row ' . ($i + 1) . ' and column MW has an empty value.';
                $messages["array_components_table." . $i . ".2.numeric"] = 'The table components data in row ' . ($i + 1) . ' and column MW must be a number.';
                $messages["array_components_table." . $i . ".2.between"] = 'The table components data in row ' . ($i + 1) . ' and column MW is not between :min - :max.';
                $messages["array_components_table." . $i . ".3.required"] = 'The table components data in row ' . ($i + 1) . ' and column Pc has an empty value.';
                $messages["array_components_table." . $i . ".3.numeric"] = 'The table components data in row ' . ($i + 1) . ' and column Pc must be a number.';
                $messages["array_components_table." . $i . ".3.between"] = 'The table components data in row ' . ($i + 1) . ' and column Pc is not between :min - :max.';
                $messages["array_components_table." . $i . ".4.required"] = 'The table components data in row ' . ($i + 1) . ' and column Tc has an empty value.';
                $messages["array_components_table." . $i . ".4.numeric"] = 'The table components data in row ' . ($i + 1) . ' and column Tc must be a number.';
                $messages["array_components_table." . $i . ".4.between"] = 'The table components data in row ' . ($i + 1) . ' and column Tc is not between :min - :max.';
                $messages["array_components_table." . $i . ".5.required"] = 'The table components data in row ' . ($i + 1) . ' and column W has an empty value.';
                $messages["array_components_table." . $i . ".5.numeric"] = 'The table components data in row ' . ($i + 1) . ' and column W must be a number.';
                $messages["array_components_table." . $i . ".5.between"] = 'The table components data in row ' . ($i + 1) . ' and column W is not between :min - :max.';
                $messages["array_components_table." . $i . ".6.required"] = 'The table components data in row ' . ($i + 1) . ' and column Shift has an empty value.';
                $messages["array_components_table." . $i . ".6.numeric"] = 'The table components data in row ' . ($i + 1) . ' and column Shift must be a number.';
                $messages["array_components_table." . $i . ".6.between"] = 'The table components data in row ' . ($i + 1) . ' and column Shift is not between :min - :max.';
                $messages["array_components_table." . $i . ".7.required"] = 'The table components data in row ' . ($i + 1) . ' and column SG has an empty value.';
                $messages["array_components_table." . $i . ".7.numeric"] = 'The table components data in row ' . ($i + 1) . ' and column SG must be a number.';
                $messages["array_components_table." . $i . ".7.between"] = 'The table components data in row ' . ($i + 1) . ' and column SG is not between :min - :max.';
                $messages["array_components_table." . $i . ".8.required"] = 'The table components data in row ' . ($i + 1) . ' and column Tb has an empty value.';
                $messages["array_components_table." . $i . ".8.numeric"] = 'The table components data in row ' . ($i + 1) . ' and column Tb must be a number.';
                $messages["array_components_table." . $i . ".8.between"] = 'The table components data in row ' . ($i + 1) . ' and column Tb is not between :min - :max.';
                $messages["array_components_table." . $i . ".9.required"] = 'The table components data in row ' . ($i + 1) . ' and column Vc has an empty value.';
                $messages["array_components_table." . $i . ".9.numeric"] = 'The table components data in row ' . ($i + 1) . ' and column Vc must be a number.';
                $messages["array_components_table." . $i . ".9.between"] = 'The table components data in row ' . ($i + 1) . ' and column Vc is not between :min - :max.';
            }
        }

        if (is_array($this->array_binary_interaction_coefficients_table)) {
            for ($i = 0; $i < count($this->array_binary_interaction_coefficients_table); $i++) {
                $messages["array_binary_interaction_coefficients_table." . $i . ".0.required"] = 'The table binary interaction coefficients data in row ' . ($i + 1) . ' and column Components has an empty value.';
                $messages["array_binary_interaction_coefficients_table." . $i . ".0.in"] = 'The table table binary interaction coefficients data in row ' . ($i + 1) . ' and column Components has a value that is not part of the allowed selection.';
                
                for ($j = 1; $j < count($this->array_binary_interaction_coefficients_table[$i]); $j++) {
                    $messages["array_binary_interaction_coefficients_table." . $i . "." . $j . ".required"] = 'The table binary interaction coefficients data in row ' . ($i + 1) . ' and column ' . $this->array_binary_interaction_coefficients_table[$j - 1][0] . ' has an empty value.';
                    $messages["array_binary_interaction_coefficients_table." . $i . "." . $j . ".numeric"] = 'The table binary interaction coefficients data in row ' . ($i + 1) . ' and column ' . $this->array_binary_interaction_coefficients_table[$j - 1][0] . ' must be a number.';
                    $messages["array_binary_interaction_coefficients_table." . $i . "." . $j . ".between"] = 'The table binary interaction coefficients data in row ' . ($i + 1) . ' and column ' . $this->array_binary_interaction_coefficients_table[$j - 1][0] . ' is not between :min - :max.';
                }
            }
        }

        if (is_array($this->array_bubble_point_table)) {
            for ($i = 0; $i < count($this->array_bubble_point_table); $i++) {
                $messages["array_bubble_point_table." . $i . ".0.required"] = 'The table bubble point data in row ' . ($i + 1) . ' and column Temperature (Bubble curve) has an empty value.';
                $messages["array_bubble_point_table." . $i . ".0.numeric"] = 'The table bubble point data in row ' . ($i + 1) . ' and column Temperature (Bubble curve) must be a number.';
                $messages["array_bubble_point_table." . $i . ".0.min"] = 'The table bubble point data in row ' . ($i + 1) . ' and column Temperature (Bubble curve) must be greater than 0.';
                $messages["array_bubble_point_table." . $i . ".0.not_in"] = 'The table bubble point data in row ' . ($i + 1) . ' and column Temperature (Bubble curve) must be greater than 0.';
                $messages["array_bubble_point_table." . $i . ".1.required"] = 'The table bubble point data in row ' . ($i + 1) . ' and column Bubble pressure has an empty value.';
                $messages["array_bubble_point_table." . $i . ".1.numeric"] = 'The table bubble point data in row ' . ($i + 1) . ' and column Bubble pressure must be a number.';
                $messages["array_bubble_point_table." . $i . ".1.min"] = 'The table bubble point data in row ' . ($i + 1) . ' and column Bubble pressure must be greater than 0.';
                $messages["array_bubble_point_table." . $i . ".1.not_in"] = 'The table bubble point data in row ' . ($i + 1) . ' and column Temperature (Bubble curve) must be greater than 0.';
            }
        }

        return $messages;
    }
}
