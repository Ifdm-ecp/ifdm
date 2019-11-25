<?php

namespace App\Http\Requests;

use App\filtration_function;
use App\Http\Requests\Request;

class filtration_function_request extends Request
{
    /**
     * Create a new request instance.
     *
     * @return void
     */
    public function __construct()
    {
        request()->merge(['array_k_data' => json_decode(request()->k_data)]);
        request()->merge(['array_p_data' => json_decode(request()->p_data)]);
        request()->merge(['array_lab_test_table' => json_decode(request()->lab_test_data)]);
        request()->merge(['array_mud_composition' => json_decode(request()->mudComposicion)]);
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
        $rules =  [
            'basin' => 'required|exists:cuencas,id',
            'field' => 'required|exists:campos,id',
            'formation' => 'required|exists:formaciones,id',
            'filtration_function_name' => 'required|unique:d_filtration_function,name',
            'kdki_cement_slurry' => 'numeric|min:0|max:1',
            'kdki_mud' => 'required|numeric|min:0|max:1',
            'core_diameter' => 'required|numeric|min:0|max:50',
            'mud_density' => 'required|numeric|min:0|max:20',
            'plastic_viscosity' => 'required|numeric|min:0|max:100',
            'yield_point' => 'required|numeric|min:0|max:100',
            'lplt_filtrate' => 'numeric|min:0|max:100',
            'hpht_filtrate' => 'numeric|min:0|max:100',
            'ph' => 'numeric|min:0|max:14',
            'gel_strength' => 'numeric|min:0|max:100',
            'cement_density' => 'numeric|min:0|max:50',
            'cement_plastic_viscosity' => 'numeric|min:0|max:100',
            'cement_yield_point' => 'numeric|min:0|max:100',
            'array_mud_composition' => 'array',
        ];

        if (request()->route('filtration_function') !== null) {
            $filtration_function = filtration_function::find(request()->route('filtration_function'));
            if ($filtration_function && $filtration_function->name == $this->filtration_function_name) {
                $rules["filtration_function_name"] = 'required';
            }
        }

        if (is_array($this->array_mud_composition)) {
            for ($i = 0; $i < count($this->array_mud_composition); $i++) {
                $rules["array_mud_composition." . $i . ".0"] = 'required';
                $rules["array_mud_composition." . $i . ".1"] = 'required|numeric';
            }
        }

        if ($this->filtration_function_factors_option == 1) {
            $rules['array_k_data'] = 'required|array';
            $rules['array_p_data'] = 'required|array';
            $rules['array_lab_test_table'] = 'required|array';

            if (is_array($this->array_lab_test_table)) {
                for ($i = 0; $i < count($this->array_lab_test_table); $i++) {
                    $rules["array_k_data." . $i] = 'required|numeric|min:0|max:10000';
                    $rules["array_p_data." . $i] = 'required|numeric|min:0|max:10000';
                    $rules["array_lab_test_table." . $i] = 'required|array';

                    if (is_array($this->array_lab_test_table[$i])) {
                        for ($j = 0; $j < count($this->array_lab_test_table[$i]); $j++) {
                            $rules["array_lab_test_table." . $i . "." . $j] = 'required|array';
                            $rules["array_lab_test_table." . $i . "." . $j . ".0"] = 'required|numeric|min:0|max:1000';
                            $rules["array_lab_test_table." . $i . "." . $j . ".1"] = 'required|numeric|min:0|max:1000';
                        }
                    }
                }
            }
            // for ($i = 1; $i < $this->lab_test_counter; $i++) {
            //     $rules["k_lab_test_" . $i] = 'required|numeric|min:0|max:10000';
            //     $rules["p_lab_test_" . $i] = 'required|numeric|min:0|max:10000';
            // }
        } else {
            $rules['a_factor'] = 'required|numeric|min:0|max:50';
            $rules['b_factor'] = 'required|numeric|min:0|max:50';
        }

        return $rules;
    }

    public function messages()
    {
        $messages = [
            'basin.required' => 'You must choose a basin.',
            'basin.exists' => 'The basin selected must exist in the database.',
            'field.required' => 'You must choose a field.',
            'field.exists' => 'The field selected must exist in the database.',
            'formation.required' => 'You must choose a formation.',
            'formation.exists' => 'The formation selected must exist in the database.',
            'filtration_function_name.required' => 'The filtration function name is required.',
            'filtration_function_name.unique' => 'That filtration function name is already taken.',
            'mud_density.required' => 'The mud density is required.',
            'mud_density.numeric' => 'The mud density must be a number.',
            'mud_density.min' => 'The mud density must be higher or equal than 0.',
            'mud_density.max' => 'The mud density must be lower or equal than 20.',
            'plastic_viscosity.required' => 'The mud plastic viscosity is required.',
            'plastic_viscosity.numeric' => 'The mud plastic viscosity must be a number.',
            'plastic_viscosity.min' => 'The mud plastic viscosity must be higher or equal than 0.',
            'plastic_viscosity.max' => 'The mud plastic viscosity must be lower or equal than 100.',
            'yield_point.required' => 'The mud yield point is required.',
            'yield_point.numeric' => 'The mud yield point must be a number.',
            'yield_point.min' => 'The mud yield point must be higher or equal than 0.',
            'yield_point.max' => 'The mud yield point must be lower or equal than 100.',
            'lplt_filtrate.numeric' => 'The mud lplt filtrate must be a number.',
            'lplt_filtrate.min' => 'The mud lplt filtrate must be higher or equal than 0.',
            'lplt_filtrate.max' => 'The mud lplt filtrate must be lower or equal than 100.',
            'hpht_filtrate.numeric' => 'The mud hpht filtrate must be a number.',
            'hpht_filtrate.min' => 'The mud hpht filtrate must be higher or equal than 0.',
            'hpht_filtrate.max' => 'The mud hpht filtrate must be lower or equal than 100.',
            'ph.numeric' => 'The mud ph must be a number.',
            'ph.min' => 'The mud ph must be higher or equal than 0.',
            'ph.max' => 'The mud ph must be lower or equal than 14.',
            'gel_strength.numeric' => 'The mud gel strength must be a number.',
            'gel_strength.min' => 'The mud gel strength must be higher or equal than 0.',
            'gel_strength.max' => 'The mud gel strength must be lower or equal than 100.',
            'cement_density.numeric' => 'The cement density must be a number.',
            'cement_density.min' => 'The cement density must be higher or equal than 0.',
            'cement_density.max' => 'The cement density must be lower or equal than 50.',
            'cement_plastic_viscosity.numeric' => 'The cement plastic viscosity must be a number.',
            'cement_plastic_viscosity.min' => 'The cement plastic viscosity must be higher or equal than 0.',
            'cement_plastic_viscosity.max' => 'The cement plastic viscosity must be lower or equal than 100.',
            'cement_yield_point.numeric' => 'The cement yield point must be a number.',
            'cement_yield_point.min' => 'The cement yield point must be higher or equal than 0.',
            'cement_yield_point.max' => 'The cement yield point must be lower or equal than 100.',
            'array_mud_composition.array' => 'The data structure containing drilling fluid formulation table contents is incorrect.',
            'kdki_cement_slurry.numeric' => 'The Kd/Ki completition fluids must be a number.',
            'kdki_cement_slurry.min' => 'The Kd/Ki completition fluids must be higher or equal than 0.',
            'kdki_cement_slurry.max' => 'The Kd/Ki completition fluids must be lower or equal than 1.',
            'kdki_mud.required' => 'The Kd/Ki mud is required.',
            'kdki_mud.numeric' => 'The Kd/Ki mud must be a number.',
            'kdki_mud.min' => 'The Kd/Ki mud must be higher or equal than 0.',
            'kdki_mud.max' => 'The Kd/Ki mud must be lower or equal than 1.',
            'core_diameter.required' => 'The core diameter is required.',
            'core_diameter.numeric' => 'The core diameter must be a number.',
            'core_diameter.min' => 'The core diameter must be higher or equal than 0.',
            'core_diameter.max' => 'The core diameter must be lower or equal than 50.',
            'a_factor.required' => 'The a factor is required.',
            'a_factor.numeric' => 'The a factor must be a number.',
            'a_factor.min' => 'The a factor must be higher or equal than 0.',
            'a_factor.max' => 'The a factor must be lower or equal than 50.',
            'b_factor.required' => 'The b factor is required.',
            'b_factor.numeric' => 'The b factor must be a number.',
            'b_factor.min' => 'The b factor must be higher or equal than 0.',
            'b_factor.max' => 'The b factor must be lower or equal than 50.',
        ];

        if (is_array($this->array_mud_composition)) {
            for ($i = 0; $i < count($this->array_mud_composition); $i++) {
                $messages["array_mud_composition." . $i . ".0.required"] = 'The table drilling fluid formulation in row ' . ($i + 1) . ' and column Component has an empty value.';
                $messages["array_mud_composition." . $i . ".1.required"] = 'The table drilling fluid formulation in row ' . ($i + 1) . ' and column Concentration has an empty value.';
                $messages["array_mud_composition." . $i . ".1.numeric"] = 'The table drilling fluid formulation in row ' . ($i + 1) . ' and column Concentration must be a number.';
            }
        }

        if (is_array($this->array_lab_test_table)) {
            for ($i = 0; $i < count($this->array_lab_test_table); $i++) {
                $messages["array_k_data." . $i . ".required"] = 'The permeability value for lab test #' . ($i + 1) . ' is required.';
                $messages["array_k_data." . $i . ".numeric"] = 'The permeability value for lab test #' . ($i + 1) . ' must be a number.';
                $messages["array_k_data." . $i . ".min"] = 'The permeability value for lab test #' . ($i + 1) . ' must be higher or equal than 0.';
                $messages["array_k_data." . $i . ".max"] = 'The permeability value for lab test #' . ($i + 1) . ' must be lower or equal than 10000.';
                $messages["array_p_data." . $i . ".required"] = 'The Pob value for lab test #' . ($i + 1) . ' is required.';
                $messages["array_p_data." . $i . ".numeric"] = 'The Pob value for lab test #' . ($i + 1) . ' must be a number.';
                $messages["array_p_data." . $i . ".min"] = 'The Pob value for lab test #' . ($i + 1) . ' must be higher or equal than 0.';
                $messages["array_p_data." . $i . ".max"] = 'The Pob value for lab test #' . ($i + 1) . ' must be lower or equal than 10000.';
                $messages["array_lab_test_table." . $i . ".required"] = 'The lab tests #' . ($i + 1) . ' table is empty. Please check your data.';
                $messages["array_lab_test_table." . $i . ".array"] = 'The data structure containing the lab tests #' . ($i + 1) . ' table contents is incorrect.';

                if (is_array($this->array_lab_test_table[$i])) {
                    for ($j = 0; $j < count($this->array_lab_test_table[$i]); $j++) {
                        $messages["array_lab_test_table." . $i . "." . $j . ".required"] = 'The lab tests #' . ($i + 1) . ' table in row ' . ($j + 1) . ' is empty. Please check your data.';
                        $messages["array_lab_test_table." . $i . "." . $j . ".array"] = 'The data structure containing the lab tests #' . ($i + 1) . ' table contents in row ' . ($j + 1) . ' is incorrect.';
                        $messages["array_lab_test_table." . $i . "." . $j . ".0.required"] = 'The lab tests #' . ($i + 1) . ' table in row ' . ($j + 1) . ' and column Time has an empty value.';
                        $messages["array_lab_test_table." . $i . "." . $j . ".0.numeric"] = 'The lab tests #' . ($i + 1) . ' table in row ' . ($j + 1) . ' and column Time must be a number.';
                        $messages["array_lab_test_table." . $i . "." . $j . ".0.min"] = 'The lab tests #' . ($i + 1) . ' table in row ' . ($j + 1) . ' and column Time must be higher or equal than 0.';
                        $messages["array_lab_test_table." . $i . "." . $j . ".0.max"] = 'The lab tests #' . ($i + 1) . ' table in row ' . ($j + 1) . ' and column Time must be lower or equal than 1000.';
                        $messages["array_lab_test_table." . $i . "." . $j . ".1.required"] = 'The lab tests #' . ($i + 1) . ' table in row ' . ($j + 1) . ' and column Filtrate Volume has an empty value.';
                        $messages["array_lab_test_table." . $i . "." . $j . ".1.numeric"] = 'The lab tests #' . ($i + 1) . ' table in row ' . ($j + 1) . ' and column Filtrate Volume must be a number.';
                        $messages["array_lab_test_table." . $i . "." . $j . ".1.min"] = 'The lab tests #' . ($i + 1) . ' table in row ' . ($j + 1) . ' and column Filtrate Volume must be higher or equal than 0.';
                        $messages["array_lab_test_table." . $i . "." . $j . ".1.max"] = 'The lab tests #' . ($i + 1) . ' table in row ' . ($j + 1) . ' and column Filtrate Volume must be lower or equal than 1000.';
                    }
                }
            }
        }

        return $messages;
    }
}
