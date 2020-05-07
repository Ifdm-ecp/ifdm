<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class disaggregation_request extends Request
{
    /**
     * Create a new request instance.
     *
     * @return void
     */
    public function __construct()
    {
        request()->merge(['array_hydraulic_units_data' => json_decode(request()->hidraulic_units_data_table)]);
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
            'well_radius' => 'required|numeric|between:0,5',
            'reservoir_pressure' => 'required|numeric|between:0,10000',
            'measured_well_depth' => 'required|numeric|between:0,30000',
            'true_vertical_depth' => 'required|numeric|between:0,30000',
            'formation_thickness' => 'required|numeric|between:0,1000',
            'perforated_thickness' => 'required|numeric|between:0,1000',
            'well_completitions' => 'required|numeric|in:1,2,3',
            'perforation_penetration_depth' => 'required_if:well_completitions,3|numeric|between:0,50',
            'perforating_phase_angle' => 'required_if:well_completitions,3|numeric|in:0.0,45.0,60.0,90.0,120.0,180.0,360.0',
            'perforating_radius' => 'required_if:well_completitions,3|numeric|between:0,10',
            'production_formation_thickness' => 'required_if:well_completitions,3|numeric|between:0,1000',
            'horizontal_vertical_permeability_ratio' => 'required_if:well_completitions,3|numeric|between:0,100',
            'drainage_area_shape' => 'required_if:well_completitions,3|integer|in:1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16',
            'fluid_of_interest' => 'required|in:1,2,3,4',
            'oil_rate' => 'required_if:fluid_of_interest,1|numeric|between:0,10000',
            'oil_bottomhole_flowing_pressure' => 'required_if:fluid_of_interest,1|numeric|between:0,10000',
            'oil_viscosity' => 'required_if:fluid_of_interest,1|numeric|between:0,100000',
            'oil_volumetric_factor' => 'required_if:fluid_of_interest,1|numeric|between:0,10',
            'gas_rate' => 'required_if:fluid_of_interest,2|numeric|between:0,10000',
            'gas_bottomhole_flowing_pressure' => 'required_if:fluid_of_interest,2|numeric|between:0,10000',
            'gas_viscosity' => 'required_if:fluid_of_interest,2|numeric|between:0,100000',
            'gas_volumetric_factor' => 'required_if:fluid_of_interest,2|numeric|between:0,10',
            'water_rate' => 'required_if:fluid_of_interest,3|numeric|between:0,10000',
            'water_bottomhole_flowing_pressure' => 'required_if:fluid_of_interest,3|numeric|between:0,10000',
            'water_viscosity' => 'required_if:fluid_of_interest,3|numeric|between:0,100000',
            'water_volumetric_factor' => 'required_if:fluid_of_interest,3|numeric|between:0,10',
            'emulsion' => 'required_if:fluid_of_interest,4|in:1,2',
            'characterized_mixture' => 'required_if:fluid_of_interest,4|in:1,2',
            //if in:1
            'mixture_rate' => 'required_if:characterized_mixture,1|numeric|between:0,10000',
            'mixture_bottomhole_flowing_pressure' => 'required_if:characterized_mixture,1|numeric|between:0,10000',
            'mixture_viscosity' => 'required_if:characterized_mixture,1|numeric|between:0,100000',
            'mixture_volumetric_factor' => 'required_if:characterized_mixture,1|numeric|between:0,10',
            //if in:2
            'mixture_rate_2' => 'required_if:characterized_mixture,2|numeric|between:0,10000',
            'mixture_bottomhole_flowing_pressure_2' => 'required_if:characterized_mixture,2|numeric|between:0,10000',
            'mixture_oil_viscosity' => 'required_if:characterized_mixture,2|numeric|between:0,100000',
            'mixture_oil_fraction' => 'required_if:characterized_mixture,2|numeric|between:0,1',
            'mixture_water_viscosity' => 'required_if:characterized_mixture,2|numeric|between:0,100000',
            'mixture_water_fraction' => 'required_if:characterized_mixture,2|numeric|between:0,1',
            'mixture_oil_volumetric_factor' => 'required_if:characterized_mixture,2|numeric|between:0,10',
            'mixture_water_volumetric_factor' => 'required_if:characterized_mixture,2|numeric|between:0,10',
            //
            'skin' => 'required|numeric|between:0,1000',
            'permeability' => 'required|numeric|between:0,1000000',
            'rock_type' => 'required|in:poco consolidada,consolidada,microfracturada',
            'porosity' => 'required|numeric|between:0,0.467',
            'array_hydraulic_units_data' => 'required|array|min:1',
            'only_s' => 'required|in:run,save',
        ];

        if (is_array($this->array_hydraulic_units_data)) {
            for ($i = 0; $i < count($this->array_hydraulic_units_data); $i++) {
                $rules["array_hydraulic_units_data." . $i . ".0"] = 'required|numeric|between:0,1000';
                $rules["array_hydraulic_units_data." . $i . ".1"] = 'required|numeric|between:0,1';
                $rules["array_hydraulic_units_data." . $i . ".2"] = 'required|numeric|between:0,1000000';
            }
        }

        if ($this->only_s == "save") {
            $rules["well_radius"] = str_replace("required|", "", $rules["well_radius"]);
            $rules["reservoir_pressure"] = str_replace("required|", "", $rules["reservoir_pressure"]);
            $rules["measured_well_depth"] = str_replace("required|", "", $rules["measured_well_depth"]);
            $rules["true_vertical_depth"] = str_replace("required|", "", $rules["true_vertical_depth"]);
            $rules["formation_thickness"] = str_replace("required|", "", $rules["formation_thickness"]);
            $rules["perforated_thickness"] = str_replace("required|", "", $rules["perforated_thickness"]);
            $rules["well_completitions"] = str_replace("required|", "", $rules["well_completitions"]);
            $rules["perforation_penetration_depth"] = str_replace("required_if|", "", $rules["perforation_penetration_depth"]);
            $rules["perforating_phase_angle"] = str_replace("required_if|", "", $rules["perforating_phase_angle"]);
            $rules["perforating_radius"] = str_replace("required_if|", "", $rules["perforating_radius"]);
            $rules["production_formation_thickness"] = str_replace("required_if|", "", $rules["production_formation_thickness"]);
            $rules["horizontal_vertical_permeability_ratio"] = str_replace("required_if|", "", $rules["horizontal_vertical_permeability_ratio"]);
            $rules["drainage_area_shape"] = str_replace("required_if|", "", $rules["drainage_area_shape"]);
            $rules["fluid_of_interest"] = str_replace("required|", "", $rules["fluid_of_interest"]);
            $rules["oil_rate"] = str_replace("required_if|", "", $rules["oil_rate"]);
            $rules["oil_bottomhole_flowing_pressure"] = str_replace("required_if|", "", $rules["oil_bottomhole_flowing_pressure"]);
            $rules["oil_viscosity"] = str_replace("required_if|", "", $rules["oil_viscosity"]);
            $rules["oil_volumetric_factor"] = str_replace("required_if|", "", $rules["oil_volumetric_factor"]);
            $rules["gas_rate"] = str_replace("required_if|", "", $rules["gas_rate"]);
            $rules["gas_bottomhole_flowing_pressure"] = str_replace("required_if|", "", $rules["gas_bottomhole_flowing_pressure"]);
            $rules["gas_viscosity"] = str_replace("required_if|", "", $rules["gas_viscosity"]);
            $rules["gas_volumetric_factor"] = str_replace("required_if|", "", $rules["gas_volumetric_factor"]);
            $rules["water_rate"] = str_replace("required_if|", "", $rules["water_rate"]);
            $rules["water_bottomhole_flowing_pressure"] = str_replace("required_if|", "", $rules["water_bottomhole_flowing_pressure"]);
            $rules["water_viscosity"] = str_replace("required_if|", "", $rules["water_viscosity"]);
            $rules["water_volumetric_factor"] = str_replace("required_if|", "", $rules["water_volumetric_factor"]);
            $rules["emulsion"] = str_replace("required_if|", "", $rules["emulsion"]);
            $rules["characterized_mixture"] = str_replace("required_if|", "", $rules["characterized_mixture"]);
            $rules["mixture_rate"] = str_replace("required_if|", "", $rules["mixture_rate"]);
            $rules["mixture_bottomhole_flowing_pressure"] = str_replace("required_if|", "", $rules["mixture_bottomhole_flowing_pressure"]);
            $rules["mixture_viscosity"] = str_replace("required_if|", "", $rules["mixture_viscosity"]);
            $rules["mixture_volumetric_factor"] = str_replace("required_if|", "", $rules["mixture_volumetric_factor"]);
            $rules["mixture_rate_2"] = str_replace("required_if|", "", $rules["mixture_rate_2"]);
            $rules["mixture_bottomhole_flowing_pressure_2"] = str_replace("required_if", "", $rules["mixture_bottomhole_flowing_pressure_2"]);
            $rules["mixture_oil_viscosity"] = str_replace("required_if", "", $rules["mixture_oil_viscosity"]);
            $rules["mixture_oil_fraction"] = str_replace("required_if", "", $rules["mixture_oil_fraction"]);
            $rules["mixture_water_viscosity"] = str_replace("required_if", "", $rules["mixture_water_viscosity"]);
            $rules["mixture_water_fraction"] = str_replace("required_if", "", $rules["mixture_water_fraction"]);
            $rules["mixture_oil_volumetric_factor"] = str_replace("required_if", "", $rules["mixture_oil_volumetric_factor"]);
            $rules["mixture_water_volumetric_factor"] = str_replace("required_if", "", $rules["mixture_water_volumetric_factor"]);
            $rules["skin"] = str_replace("required|", "", $rules["skin"]);
            $rules["permeability"] = str_replace("required|", "", $rules["permeability"]);
            $rules["rock_type"] = str_replace("required|", "", $rules["rock_type"]);
            $rules["porosity"] = str_replace("required|", "", $rules["porosity"]);
            $rules["array_hydraulic_units_data"] = str_replace("required|", "", $rules["array_hydraulic_units_data"]);
        }

        return $rules;
    }

    public function messages()
    {
        $messages = [
            'well_radius.required' => 'The well radius is required.',
            'reservoir_pressure.required' => 'The reservoir pressure is required.',
            'measured_well_depth.required' => 'The measured well depth is required.',
            'true_vertical_depth.required' => 'The true vertical depth is required.',
            'formation_thickness.required' => 'The formation thickness is required.',
            'perforated_thickness.required' => 'The perforated thickness is required.',
            'well_completitions.required' => 'The well completitions is required.',
            //'perforation_penetration_depth.required' => 'The perforation penetration depth is required.',
            //'perforating_phase_angle.required' => 'The perforating phase angle is required.',
            //'perforating_radius.required' => 'The perforating radius is required.',
            //'production_formation_thickness.required' => 'The production formation thickness is required.',
            //'horizontal_vertical_permeability_ratio.required' => 'The horizontal - vertical permeabibility ratio is required.',
            //'drainage_area_shape.required' => 'Drainage area shape is required.',
            'fluid_of_interest.required' => 'The fluid of interest is required.',
            //'fluid_rate.required' => 'The fluid rate is required.',
            //'bottomhole_flowing_pressure.required' => 'The bottomhole flowing pressure is required.',
            //'fluid_viscosity.required' => 'The fluid viscosity is required.',
            //'fluid_volumetric_factor.required' => 'The fluid volumetric factor is required.',
            'skin.required' => 'The skin is required.',
            'permeability.required' => 'The permeability is required.',
            'rock_type.required' => 'The rock type is required.',
            'porosity.required' => 'The porosity is required.',
            'required_if' => 'The :attribute is required',

            'well_radius.numeric' => 'The well radius must be a number.',
            'reservoir_pressure.numeric' => 'The reservoir pressure must be a number.',
            'measured_well_depth.numeric' => 'The measured well must be a number.',
            'true_vertical_depth.numeric' => 'The true vertical depth must be a number.',
            'formation_thickness.numeric' => 'The formation thickness must be a number.',
            'perforated_thickness.numeric' => 'The perforated thickness must be a number.',
            'well_completitions.numeric' => 'The well completitions must be a number.',
            'perforation_penetration_depth.numeric' => 'The perforation penetration depth must be a number.',
            'perforating_phase_angle.numeric' => 'The perforating phase angle must be a number.',
            'perforating_radius.numeric' => 'The perforating radius must be a number.',
            'production_formation_thickness.numeric' => 'The production formation thickness must be a number.',
            'horizontal_vertical_permeability_ratio.numeric' => 'The horizontal vertical permeabibility ratio must be a number.',
            'drainage_area_shape.numeric' => 'The drainge area shape must be a number.',
            'fluid_of_interest.numeric' => 'The fluid of interest must be a number.',
            'oil_rate.numeric' => 'The oil rate must be a number.',
            'oil_bottomhole_flowing_pressure.numeric' => 'The bottomhole flowing pressure must be a number.',
            'oil_viscosity.numeric' => 'The oil viscosity must be a number.',
            'oil_volumetric_factor.numeric' => 'The oil volumetric factor must be a number.',
            'gas_rate.numeric' => 'The gas rate must be numeric.',
            'gas_bottomhole_flowing_pressure.numeric' => 'The bottomhole flowing pressure must be a number.',
            'gas_viscosity.numeric' => 'The gas viscosity must be a number.',
            'gas_volumetric_factor.numeric' => 'The gas volumetric factor must be a number.',
            'water_rate.numeric' => 'The water rate must be a number.',
            'water_bottomhole_flowing_pressure.numeric' => 'The bottomhole flowing pressure must be a number.',
            'water_viscosity.numeric' => 'The water viscosity must be a number.',
            'water_volumetric_factor.numeric' => 'The water volumetric factor must be a number.',
            'mixture_rate.numeric' => 'The mixture rate must be a number.',
            'mixture_bottomhole_flowing_pressure.numeric' => 'The bottomhole flowing pressure must be a number.',
            'mixture_viscosity.numeric' => 'The mixture viscosity must be a number.',
            'mixture_volumetric_factor.numeric' => 'The mixture volumetric factor must be a number.',
            'mixture_rate_2.numeric' => 'The mixture rate must be a number.',
            'mixture_bottomhole_flowing_pressure_2.numeric' => 'The bottomhole flowing pressure must be a number.',
            'mixture_oil_viscosity.numeric' => 'The oil viscosity must be a number.',
            'mixture_oil_fraction.numeric' => 'The oil fraction must be a number.',
            'mixture_water_viscosity.numeric' => 'The water viscosity must be a number.',
            'mixture_water_fraction.numeric' => 'The water fraction must be a number.',
            'mixture_oil_volumetric_factor.numeric' => 'The mixture oil volumetric factor must be a number.',
            'mixture_water_volumetric_factor.numeric' => 'The mixture water volumetric factor must be a number.',
            'skin.numeric' => 'The skin must be a number.',
            'permeability.numeric' => 'The permeabibility must be a number.',
            'porosity.numeric' => 'The porosity must be a number.',

            'in' => 'The :attribute selected is not part of the allowed selection.',
            'between' => 'The :attribute value is not between :min - :max.',

            'array_hydraulic_units_data.required' => 'The table hydraulic units data is empty. Please check your data.',
            'array_hydraulic_units_data.array' => 'The data structure containing the hydraulic units data table contents is incorrect.',
            'array_hydraulic_units_data.min' => 'The table hydraulic units data is empty. Please check your data.',
            'only_s.required' => 'The info sent that determines if the form is ran or saved is empty.',
            'only_s.in' => 'The info sent that determines if the form is ran or saved is incorrect.',
        ];

        if (is_array($this->array_hydraulic_units_data)) {
            for ($i = 0; $i < count($this->array_hydraulic_units_data); $i++) {
                $messages["array_hydraulic_units_data." . $i . ".0.required"] = 'The table hydraulic units data in row ' . ($i + 1) . ' and column Thickness has an empty value.';
                $messages["array_hydraulic_units_data." . $i . ".0.numeric"] = 'The table hydraulic units data in row ' . ($i + 1) . ' and column Thickness must be a number.';
                $messages["array_hydraulic_units_data." . $i . ".0.between"] = 'The table hydraulic units data in row ' . ($i + 1) . ' and column Thickness is not between 0 - 1000';
                $messages["array_hydraulic_units_data." . $i . ".1.required"] = 'The table hydraulic units data in row ' . ($i + 1) . ' and column Average Porosity has an empty value.';
                $messages["array_hydraulic_units_data." . $i . ".1.numeric"] = 'The table hydraulic units data in row ' . ($i + 1) . ' and column Average Porosity must be a number.';
                $messages["array_hydraulic_units_data." . $i . ".1.between"] = 'The table hydraulic units data in row ' . ($i + 1) . ' and column Average Porosity is not between 0 - 1';
                $messages["array_hydraulic_units_data." . $i . ".2.required"] = 'The table hydraulic units data in row ' . ($i + 1) . ' and column Average Permeability has an empty value.';
                $messages["array_hydraulic_units_data." . $i . ".2.numeric"] = 'The table hydraulic units data in row ' . ($i + 1) . ' and column Average Permeability must be a number.';
                $messages["array_hydraulic_units_data." . $i . ".2.between"] = 'The table hydraulic units data in row ' . ($i + 1) . ' and column Average Permeability is not between 0 - 1000000';
            }
        }

        return $messages;
    }
}
