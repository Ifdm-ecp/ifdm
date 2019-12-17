<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class asphaltene_stability_analysis_request extends Request
{
    /**
     * Create a new request instance.
     *
     * @return void
     */
    public function __construct()
    {
        request()->merge(['array_components_table' => json_decode(request()->value_components_table)]);

        /* Agregar validacion para SARA */
        if (is_numeric(request()->saturated) || is_numeric(request()->aromatics) || is_numeric(request()->resines) || is_numeric(request()->asphaltenes)) {
            $sara = (is_numeric(request()->saturated) ? request()->saturated : 0) +
                (is_numeric(request()->aromatics) ? request()->aromatics : 0) +
                (is_numeric(request()->resines) ? request()->resines : 0) +
                (is_numeric(request()->asphaltenes) ? request()->asphaltenes : 0);
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
            'saturated' => 'required|numeric|between:0,100|not_in:0,100',
            'aromatics' => 'required|numeric|between:0,100|not_in:0,100',
            'resines' => 'required|numeric|between:0,100|not_in:0,100',
            'asphaltenes' => 'required|numeric|between:0,100|not_in:0,100',
            'sara_calc' => 'required|numeric|between:99,101',
            'reservoir_initial_pressure' => 'required|numeric|min:0|not_in:0',
            'bubble_pressure' => 'required|numeric|min:0|not_in:0',
            'density_at_reservoir_temperature' => 'required|numeric|between:0.5,2',
            'api_gravity' => 'required|numeric|between:5,70',
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
            }
        }

        if ($this->only_s == "save") {
            $rules["components"] = str_replace("required|", "", $rules["components"]);
            $rules["array_components_table"] = str_replace("required|", "", $rules["array_components_table"]);
            $rules["saturated"] = str_replace("required|", "", $rules["saturated"]);
            $rules["aromatics"] = str_replace("required|", "", $rules["aromatics"]);
            $rules["resines"] = str_replace("required|", "", $rules["resines"]);
            $rules["asphaltenes"] = str_replace("required|", "", $rules["asphaltenes"]);
            $rules["sara_calc"] = str_replace("required|", "", $rules["sara_calc"]);
            $rules["reservoir_initial_pressure"] = str_replace("required|", "", $rules["reservoir_initial_pressure"]);
            $rules["bubble_pressure"] = str_replace("required|", "", $rules["bubble_pressure"]);
            $rules["density_at_reservoir_temperature"] = str_replace("required|", "", $rules["density_at_reservoir_temperature"]);
            $rules["api_gravity"] = str_replace("required|", "", $rules["api_gravity"]);
            if (array_key_exists("sum_zi_components_table", $rules)) {
                $rules["sum_zi_components_table"] = str_replace("required|", "", $rules["sum_zi_components_table"]);
            }
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
            'saturated.required' => 'The saturated is required.',
            'aromatics.required' => 'The aromatics is required.',
            'resines.required' => 'The resines is required.',
            'asphaltenes.required' => 'The asphaltenes is required.',
            'sara_calc.required' => 'The SARA calculation is required.',
            'reservoir_initial_pressure.required' => 'The reservoir initial pressure is required.',
            'bubble_pressure.required' => 'The bubble pressure is required.',
            'density_at_reservoir_temperature.required' => 'The density at reservoir temperature is required.',
            'api_gravity.required' => 'The API gravity is required.',

            'sum_zi_components_table.numeric' => 'The sum of Zi in components table must be a number.',
            'saturated.numeric' => 'The saturated must be a number.',
            'aromatics.numeric' => 'The aromatics must be a number.',
            'resines.numeric' => 'The resines must be a number.',
            'asphaltenes.numeric' => 'The asphaltenes must be a number.',
            'sara_calc.numeric' => 'The SARA calculation must be a number.',
            'reservoir_initial_pressure.numeric' => 'The reservoir initial pressure must be a number.',
            'bubble_pressure.numeric' => 'The bubble pressure must be a number.',
            'density_at_reservoir_temperature.numeric' => 'The density at reservoir temperature must be a number.',
            'api_gravity.numeric' => 'The API gravity must be a number.',

            'reservoir_initial_pressure.min' => 'The reservoir initial pressure must be greater than 0.',
            'reservoir_initial_pressure.not_in' => 'The reservoir initial pressure must be greater than 0.',
            'bubble_pressure.min' => 'The bubble pressure must be greater than 0.',
            'bubble_pressure.not_in' => 'The bubble pressure must be greater than 0.',

            'sum_zi_components_table.between' => 'The sum of Zi in components table must be 1+-0.1.',
            'saturated.between' => 'The saturated is not between :min - :max.',
            'aromatics.between' => 'The aromatics is not between :min - :max.',
            'resines.between' => 'The resines is not between :min - :max.',
            'asphaltenes.between' => 'The asphaltenes is not between :min - :max.',
            'sara_calc.between' => 'The SARA calculation must be 100+-1.',
            'density_at_reservoir_temperature.between' => 'The density at reservoir temperature is not between :min - :max.',
            'api_gravity.between' => 'The API gravity is not between :min - :max.',

            'saturated.not_in' => 'The saturated must be greater than 0 and less than 100.',
            'aromatics.not_in' => 'The aromatics must be greater than 0 and less than 100.',
            'resines.not_in' => 'The resines must be greater than 0 and less than 100.',
            'asphaltenes.not_in' => 'The asphaltenes must be greater than 0 and less than 100.',
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
            }
        }

        return $messages;
    }
}
