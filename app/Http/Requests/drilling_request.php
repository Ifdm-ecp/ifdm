<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class drilling_request extends Request
{
    /**
     * Create a new request instance.
     *
     * @return void
     */
    public function __construct() {
        request()->merge(['array_select_interval_general_data' => json_decode(request()->select_interval_general_data)]);
        request()->merge(['array_generaldata_table' => json_decode(request()->generaldata_table)]);
        request()->merge(['array_inputdata_profile_table' => json_decode(request()->inputdata_profile_table)]);
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
            'array_select_interval_general_data' => 'required|array|min:1',
            'array_generaldata_table' => 'required|array',
            'inputDataMethodSelect' => 'required|in:1,2',
            'inputdata_intervals_table' => 'array',
            'array_inputdata_profile_table' => 'required|array|min:1',
            'select_filtration_function' => 'required|exists:d_filtration_function,id',
            'a_factor_t' => 'required|numeric|min:0|max:50',
            'b_factor_t' => 'required|numeric|min:0|max:50',
            'd_total_exposure_time_t' => 'required|numeric|min:0|max:50',
            'd_pump_rate_t' => 'required|numeric|min:0|max:1000',
            'd_mud_density_t' => 'required|numeric|min:0|max:20',
            'd_plastic_viscosity_t' => 'required|numeric|min:0|max:100',
            'd_yield_point_t' => 'required|numeric|min:0|max:100',
            'd_rop_t' => 'required|numeric|min:0|max:500',
            'd_equivalent_circulating_density_t' => 'required|numeric|min:0|max:30',
            'only_s' => 'required|in:run,save',
        ];

        if (is_array($this->array_generaldata_table)) {
            for ($i = 0; $i < count($this->array_generaldata_table); $i++) {
                $rules["array_generaldata_table." . $i . ".0"] = 'required';
                $rules["array_generaldata_table." . $i . ".1"] = 'required|numeric|min:0|max:50000';
                $rules["array_generaldata_table." . $i . ".2"] = 'required|numeric|min:0|max:50000';
                $rules["array_generaldata_table." . $i . ".3"] = 'required|numeric|min:0|max:10000';
                $rules["array_generaldata_table." . $i . ".4"] = 'required|numeric|min:0|max:10';
                $rules["array_generaldata_table." . $i . ".5"] = 'required|numeric|min:0|max:10';
            }
        }

        if (is_array($this->array_inputdata_profile_table)) {
            for ($i = 0; $i < count($this->array_inputdata_profile_table); $i++) {
                $rules["array_inputdata_profile_table." . $i . ".0"] = 'required|numeric|min:0|max:50000';
                $rules["array_inputdata_profile_table." . $i . ".1"] = 'required|numeric|min:0|max:50000';
                $rules["array_inputdata_profile_table." . $i . ".2"] = 'required|numeric|min:0|max:1';
                $rules["array_inputdata_profile_table." . $i . ".3"] = 'required|numeric|min:0|max:10000';
                $rules["array_inputdata_profile_table." . $i . ".4"] = 'required|numeric|min:0|max:100';
                $rules["array_inputdata_profile_table." . $i . ".5"] = 'required|numeric|min:0|max:1';
            }
        }

        if ($this->cementingAvailable) {
            $rules['c_total_exposure_time_t'] = 'required|numeric|min:0|max:50';
            $rules['c_pump_rate_t'] = 'required|numeric|min:0|max:500';
            $rules['c_cement_slurry_density_t'] = 'required|numeric|min:0|max:50';
            $rules['c_plastic_viscosity_t'] = 'required|numeric|min:0|max:100';
            $rules['c_yield_point_t'] = 'required|numeric|min:0|max:100';
            $rules['c_equivalent_circulating_density_t'] = 'required|numeric|min:0|max:70';
        }

        if ($this->only_s == "save") {
            $rules["select_filtration_function"] = str_replace("required|", "", $rules["select_filtration_function"]);
            $rules["a_factor_t"] = str_replace("required|", "", $rules["a_factor_t"]);
            $rules["b_factor_t"] = str_replace("required|", "", $rules["b_factor_t"]);
            $rules["d_total_exposure_time_t"] = str_replace("required|", "", $rules["d_total_exposure_time_t"]);
            $rules["d_pump_rate_t"] = str_replace("required|", "", $rules["d_pump_rate_t"]);
            $rules["d_mud_density_t"] = str_replace("required|", "", $rules["d_mud_density_t"]);
            $rules["d_plastic_viscosity_t"] = str_replace("required|", "", $rules["d_plastic_viscosity_t"]);
            $rules["d_yield_point_t"] = str_replace("required|", "", $rules["d_yield_point_t"]);
            $rules["d_rop_t"] = str_replace("required|", "", $rules["d_rop_t"]);
            $rules["d_equivalent_circulating_density_t"] = str_replace("required|", "", $rules["d_equivalent_circulating_density_t"]);
            
            if ($this->cementingAvailable) {
                $rules["c_total_exposure_time_t"] = str_replace("required|", "", $rules["c_total_exposure_time_t"]);
                $rules["c_pump_rate_t"] = str_replace("required|", "", $rules["c_pump_rate_t"]);
                $rules["c_cement_slurry_density_t"] = str_replace("required|", "", $rules["c_cement_slurry_density_t"]);
                $rules["c_plastic_viscosity_t"] = str_replace("required|", "", $rules["c_plastic_viscosity_t"]);
                $rules["c_yield_point_t"] = str_replace("required|", "", $rules["c_yield_point_t"]);
                $rules["c_equivalent_circulating_density_t"] = str_replace("required|", "", $rules["c_equivalent_circulating_density_t"]);
            }
        }

        return $rules;
    }

    public function messages()
    {
        $messages = [
            'array_select_interval_general_data.required' => 'A producing interval must be selected.',
            'array_select_interval_general_data.array' => 'The data structure containing the producing intervals is incorrect.',
            'array_select_interval_general_data.min' => 'A producing interval must be selected.',
            'array_generaldata_table.required' => 'The table general data is empty. Please check your data.',
            'array_generaldata_table.array' => 'The data structure containing the general data table contents is incorrect.',
            'inputDataMethodSelect.required' => 'An input method (Average, By Intervals, Profile) must be selected.',
            'inputDataMethodSelect.in' => 'The input method selected is not part of the allowed selection.',
            'array_inputdata_profile_table.required' => 'The table input data is empty. Please check your data.',
            'array_inputdata_profile_table.array' => 'The data structure containing the input data table contents is incorrect.',
            'select_filtration_function.required' => 'A filtration function must be selected.',
            'select_filtration_function.exists' => 'The filtration function selected must exist in the database.',
            'a_factor_t.required' => 'The a factor is required.',
            'a_factor_t.numeric' => 'The a factor must be a number.',
            'a_factor_t.min' => 'The a factor must be higher or equal than 0.',
            'a_factor_t.max' => 'The a factor must be lower or equal than 50.',
            'b_factor_t.required' => 'The b factor is required.',
            'b_factor_t.numeric' => 'The b factor must be a number.',
            'b_factor_t.min' => 'The b factor must be higher or equal than 0.',
            'b_factor_t.max' => 'The b factor must be lower or equal than 50.',
            'd_total_exposure_time_t.required' => 'The drilling total exposure time is required.',
            'd_total_exposure_time_t.numeric' => 'The drilling total exposure time must be a number.',
            'd_total_exposure_time_t.min' => 'The drilling total exposure time must be higher or equal than 0.',
            'd_total_exposure_time_t.max' => 'The drilling total exposure time must be lower or equal than 50.',
            'd_pump_rate_t.required' => 'The drilling pump rate is required.',
            'd_pump_rate_t.numeric' => 'The drilling pump rate time must be a number.',
            'd_pump_rate_t.min' => 'The drilling pump rate time must be higher or equal than 0.',
            'd_pump_rate_t.max' => 'The drilling pump rate time must be lower or equal than 1000.',
            'd_mud_density_t.required' => 'The drilling mud density is required.',
            'd_mud_density_t.numeric' => 'The drilling mud density must be a number.',
            'd_mud_density_t.min' => 'The drilling mud density must be higher or equal than 0.',
            'd_mud_density_t.max' => 'The drilling mud density must be lower or equal than 20.',
            'd_plastic_viscosity_t.required' => 'The drilling plastic viscosity is required.',
            'd_plastic_viscosity_t.numeric' => 'The drilling plastic viscosity must be a number.',
            'd_plastic_viscosity_t.min' => 'The drilling plastic viscosity must be higher or equal than 0.',
            'd_plastic_viscosity_t.max' => 'The drilling plastic viscosity must be lower or equal than 100.',
            'd_yield_point_t.required' => 'The drilling yield point is required.',
            'd_yield_point_t.numeric' => 'The drilling yield point must be a number.',
            'd_yield_point_t.min' => 'The drilling yield point must be higher or equal than 0.',
            'd_yield_point_t.max' => 'The drilling yield point must be lower or equal than 100.',
            'd_rop_t.required' => 'The drilling ROP is required.',
            'd_rop_t.numeric' => 'The drilling ROP must be a number.',
            'd_rop_t.min' => 'The drilling ROP must be higher or equal than 0.',
            'd_rop_t.max' => 'The drilling ROP must be lower or equal than 500.',
            'd_equivalent_circulating_density_t.required' => 'The drilling equivalent circulating density is required.',
            'd_equivalent_circulating_density_t.numeric' => 'The drilling equivalent circulating density must be a number.',
            'd_equivalent_circulating_density_t.min' => 'The drilling equivalent circulating density must be higher or equal than 0.',
            'd_equivalent_circulating_density_t.max' => 'The drilling equivalent circulating density must be lower or equal than 30.',
            'c_total_exposure_time_t.required' => 'The completion total exposure time is required.',
            'c_total_exposure_time_t.numeric' => 'The completion total exposure time must be a number.',
            'c_total_exposure_time_t.min' => 'The completion total exposure time must be higher or equal than 0.',
            'c_total_exposure_time_t.max' => 'The completion total exposure time must be lower or equal than 50.',
            'c_pump_rate_t.required' => 'The completion pump rate is required.',
            'c_pump_rate_t.numeric' => 'The completion pump rate time must be a number.',
            'c_pump_rate_t.min' => 'The completion pump rate time must be higher or equal than 0.',
            'c_pump_rate_t.max' => 'The completion pump rate time must be lower or equal than 500.',
            'c_cement_slurry_density_t.required' => 'The completion cement slurry density is required.',
            'c_cement_slurry_density_t.numeric' => 'The completion cement slurry density must be a number.',
            'c_cement_slurry_density_t.min' => 'The completion cement slurry density must be higher or equal than 0.',
            'c_cement_slurry_density_t.max' => 'The completion cement slurry density must be lower or equal than 50.',
            'c_plastic_viscosity_t.required' => 'The completion plastic viscosity is required.',
            'c_plastic_viscosity_t.numeric' => 'The completion plastic viscosity must be a number.',
            'c_plastic_viscosity_t.min' => 'The completion plastic viscosity must be higher or equal than 0.',
            'c_plastic_viscosity_t.max' => 'The completion plastic viscosity must be lower or equal than 10.',
            'c_yield_point_t.required' => 'The completion yield point is required.',
            'c_yield_point_t.numeric' => 'The completion yield point must be a number.',
            'c_yield_point_t.min' => 'The completion yield point must be higher or equal than 0.',
            'c_yield_point_t.max' => 'The completion yield point must be lower or equal than 100.',
            'c_equivalent_circulating_density_t.required' => 'The completion equivalent circulating density is required.',
            'c_equivalent_circulating_density_t.numeric' => 'The completion equivalent circulating density must be a number.',
            'c_equivalent_circulating_density_t.min' => 'The completion equivalent circulating density must be higher or equal than 0.',
            'c_equivalent_circulating_density_t.max' => 'The completion equivalent circulating density must be lower or equal than 70.',
            'only_s.required' => 'The info sent that determines if the form is ran or saved is empty.',
            'only_s.in' => 'The info sent that determines if the form is ran or saved is incorrect.',
        ];

        if (is_array($this->array_generaldata_table)) {
            for ($i = 0; $i < count($this->array_generaldata_table); $i++) {
                $messages["array_generaldata_table." . $i . ".0.required"] = 'The table general data in row ' . ($i + 1) . ' and column Interval has en empty value.';
                $messages["array_generaldata_table." . $i . ".1.required"] = 'The table general data in row ' . ($i + 1) . ' and column Top has en empty value.';
                $messages["array_generaldata_table." . $i . ".1.numeric"] = 'The table general data in row ' . ($i + 1) . ' and column Top must be a number.';
                $messages["array_generaldata_table." . $i . ".1.min"] = 'The table general data in row ' . ($i + 1) . ' and column Top must be higher or equal than 0.';
                $messages["array_generaldata_table." . $i . ".1.max"] = 'The table general data in row ' . ($i + 1) . ' and column Top must be lower or equal than 50000.';
                $messages["array_generaldata_table." . $i . ".2.required"] = 'The table general data in row ' . ($i + 1) . ' and column Bottom has en empty value.';
                $messages["array_generaldata_table." . $i . ".2.numeric"] = 'The table general data in row ' . ($i + 1) . ' and column Bottom must be a number.';
                $messages["array_generaldata_table." . $i . ".2.min"] = 'The table general data in row ' . ($i + 1) . ' and column Bottom must be higher or equal than 0.';
                $messages["array_generaldata_table." . $i . ".2.max"] = 'The table general data in row ' . ($i + 1) . ' and column Bottom must be lower or equal than 50000.';
                $messages["array_generaldata_table." . $i . ".3.required"] = 'The table general data in row ' . ($i + 1) . ' and column Reservoir Pressure has en empty value.';
                $messages["array_generaldata_table." . $i . ".3.numeric"] = 'The table general data in row ' . ($i + 1) . ' and column Reservoir Pressure must be a number.';
                $messages["array_generaldata_table." . $i . ".3.min"] = 'The table general data in row ' . ($i + 1) . ' and column Reservoir Pressure must be higher or equal than 0.';
                $messages["array_generaldata_table." . $i . ".3.max"] = 'The table general data in row ' . ($i + 1) . ' and column Reservoir Pressure must be lower or equal than 10000.';
                $messages["array_generaldata_table." . $i . ".4.required"] = 'The table general data in row ' . ($i + 1) . ' and column Hole Diameter has en empty value.';
                $messages["array_generaldata_table." . $i . ".4.numeric"] = 'The table general data in row ' . ($i + 1) . ' and column Hole Diameter must be a number.';
                $messages["array_generaldata_table." . $i . ".4.min"] = 'The table general data in row ' . ($i + 1) . ' and column Hole Diameter must be higher or equal than 0.';
                $messages["array_generaldata_table." . $i . ".4.max"] = 'The table general data in row ' . ($i + 1) . ' and column Hole Diameter must be lower or equal than 10.';
                $messages["array_generaldata_table." . $i . ".5.required"] = 'The table general data in row ' . ($i + 1) . ' and column Drill Pipe Diameter has en empty value.';
                $messages["array_generaldata_table." . $i . ".5.numeric"] = 'The table general data in row ' . ($i + 1) . ' and column Drill Pipe Diameter must be a number.';
                $messages["array_generaldata_table." . $i . ".5.min"] = 'The table general data in row ' . ($i + 1) . ' and column Drill Pipe Diameter must be higher or equal than 0.';
                $messages["array_generaldata_table." . $i . ".5.max"] = 'The table general data in row ' . ($i + 1) . ' and column Drill Pipe Diameter must be lower or equal than 10.';
            }
        }

        if (is_array($this->array_inputdata_profile_table)) {
            for ($i = 0; $i < count($this->array_inputdata_profile_table); $i++) {
                $messages["array_inputdata_profile_table." . $i . ".0.required"] = 'The table input data in row ' . ($i + 1) . ' and column Top has en empty value.';
                $messages["array_inputdata_profile_table." . $i . ".0.numeric"] = 'The table input data in row ' . ($i + 1) . ' and column Top must be a number.';
                $messages["array_inputdata_profile_table." . $i . ".0.min"] = 'The table input data in row ' . ($i + 1) . ' and column Top must be higher or equal than 0.';
                $messages["array_inputdata_profile_table." . $i . ".0.max"] = 'The table input data in row ' . ($i + 1) . ' and column Top must be lower or equal than 50000.';
                $messages["array_inputdata_profile_table." . $i . ".1.required"] = 'The table input data in row ' . ($i + 1) . ' and column Bottom has en empty value.';
                $messages["array_inputdata_profile_table." . $i . ".1.numeric"] = 'The table input data in row ' . ($i + 1) . ' and column Bottom must be a number.';
                $messages["array_inputdata_profile_table." . $i . ".1.min"] = 'The table input data in row ' . ($i + 1) . ' and column Bottom must be higher or equal than 0.';
                $messages["array_inputdata_profile_table." . $i . ".1.max"] = 'The table input data in row ' . ($i + 1) . ' and column Bottom must be lower or equal than 50000.';
                $messages["array_inputdata_profile_table." . $i . ".2.required"] = 'The table input data in row ' . ($i + 1) . ' and column Porosity has en empty value.';
                $messages["array_inputdata_profile_table." . $i . ".2.numeric"] = 'The table input data in row ' . ($i + 1) . ' and column Porosity must be a number.';
                $messages["array_inputdata_profile_table." . $i . ".2.min"] = 'The table input data in row ' . ($i + 1) . ' and column Porosity must be higher or equal than 0.';
                $messages["array_inputdata_profile_table." . $i . ".2.max"] = 'The table input data in row ' . ($i + 1) . ' and column Porosity must be lower or equal than 1.';
                $messages["array_inputdata_profile_table." . $i . ".3.required"] = 'The table input data in row ' . ($i + 1) . ' and column Permeability has en empty value.';
                $messages["array_inputdata_profile_table." . $i . ".3.numeric"] = 'The table input data in row ' . ($i + 1) . ' and column Permeability must be a number.';
                $messages["array_inputdata_profile_table." . $i . ".3.min"] = 'The table input data in row ' . ($i + 1) . ' and column Permeability must be higher or equal than 0.';
                $messages["array_inputdata_profile_table." . $i . ".3.max"] = 'The table input data in row ' . ($i + 1) . ' and column Permeability must be lower or equal than 10000.';
                $messages["array_inputdata_profile_table." . $i . ".4.required"] = 'The table input data in row ' . ($i + 1) . ' and column Fracture intensity has en empty value.';
                $messages["array_inputdata_profile_table." . $i . ".4.numeric"] = 'The table input data in row ' . ($i + 1) . ' and column Fracture intensity must be a number.';
                $messages["array_inputdata_profile_table." . $i . ".4.min"] = 'The table input data in row ' . ($i + 1) . ' and column Fracture intensity must be higher or equal than 0.';
                $messages["array_inputdata_profile_table." . $i . ".4.max"] = 'The table input data in row ' . ($i + 1) . ' and column Fracture intensity must be lower or equal than 100.';
                $messages["array_inputdata_profile_table." . $i . ".5.required"] = 'The table input data in row ' . ($i + 1) . ' and column Irreducible Saturation has en empty value.';
                $messages["array_inputdata_profile_table." . $i . ".5.numeric"] = 'The table input data in row ' . ($i + 1) . ' and column Irreducible Saturation must be a number.';
                $messages["array_inputdata_profile_table." . $i . ".5.min"] = 'The table input data in row ' . ($i + 1) . ' and column Irreducible Saturation must be higher or equal than 0.';
                $messages["array_inputdata_profile_table." . $i . ".5.max"] = 'The table input data in row ' . ($i + 1) . ' and column Irreducible Saturation must be lower or equal than 1.';
            }
        }

        return $messages;
    }
}
