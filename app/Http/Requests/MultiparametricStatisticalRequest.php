<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Carbon\Carbon;

class MultiparametricStatisticalRequest extends Request
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
        if (request()->calculate == "true") {
            if (request()->statistical == 'Colombia') {
                return [
                    'statistical' => 'required|in:Colombia',
                ];
            } else {
                return [
                    'basin_statistical' => 'required|exists:cuencas,id',
                    'field_statistical' => 'required|exists:campos,id',
                ];
            }
        } else {
            $rules = [
                'basin_statistical' => 'exists:cuencas,id',
                'field_statistical' => 'exists:campos,id',
            ];

            if (request()->msAvailable) {
                $rulesSpecial = array();
                $availableArray = request()->msAvailable;

                if (in_array('1', $availableArray)) {
                    $rulesSpecial['MS1'] = 'required|numeric|min:0';
                    $rulesSpecial['dateMS1'] = 'required|date_format:d/m/Y|before:' . Carbon::now()->addDays(1)->format('d/m/Y');
                    $rulesSpecial['MS1comment'] = 'string|max:100';
                    $rulesSpecial['p10_MS1'] = 'required|numeric|min:0';
                    $rulesSpecial['p90_MS1'] = 'required|numeric|min:0|not_in:' . request()->p10_MS1;
                    $rulesSpecial['ms_scale_index_caco3'] = 'numeric';
                }

                if (in_array('2', $availableArray)) {
                    $rulesSpecial['MS2'] = 'required|numeric|min:0';
                    $rulesSpecial['dateMS2'] = 'required|date_format:d/m/Y|before:' . Carbon::now()->addDays(1)->format('d/m/Y');
                    $rulesSpecial['MS2comment'] = 'string|max:100';
                    $rulesSpecial['p10_MS2'] = 'required|numeric|min:0';
                    $rulesSpecial['p90_MS2'] = 'required|numeric|min:0|not_in:' . request()->p10_MS2;
                    $rulesSpecial['ms_scale_index_baso4'] = 'numeric';
                }

                if (in_array('3', $availableArray)) {
                    $rulesSpecial['MS3'] = 'required|numeric|min:0';
                    $rulesSpecial['dateMS3'] = 'required|date_format:d/m/Y|before:' . Carbon::now()->addDays(1)->format('d/m/Y');
                    $rulesSpecial['MS3comment'] = 'string|max:100';
                    $rulesSpecial['p10_MS3'] = 'required|numeric|min:0';
                    $rulesSpecial['p90_MS3'] = 'required|numeric|min:0|not_in:' . request()->p10_MS3;
                    $rulesSpecial['ms_scale_index_iron_scales'] = 'numeric';
                }

                if (in_array('4', $availableArray)) {
                    $rulesSpecial['MS4'] = 'required|numeric|between:0,1000000';
                    $rulesSpecial['dateMS4'] = 'required|date_format:d/m/Y|before:' . Carbon::now()->addDays(1)->format('d/m/Y');
                    $rulesSpecial['MS4comment'] = 'string|max:100';
                    $rulesSpecial['p10_MS4'] = 'required|numeric|min:0';
                    $rulesSpecial['p90_MS4'] = 'required|numeric|min:0|not_in:' . request()->p10_MS4;
                    $rulesSpecial['ms_calcium_concentration'] = 'numeric';
                }

                if (in_array('5', $availableArray)) {
                    $rulesSpecial['MS5'] = 'required|numeric|between:0,1000000';
                    $rulesSpecial['dateMS5'] = 'required|date_format:d/m/Y|before:' . Carbon::now()->addDays(1)->format('d/m/Y');
                    $rulesSpecial['MS5comment'] = 'string|max:100';
                    $rulesSpecial['p10_MS5'] = 'required|numeric|min:0';
                    $rulesSpecial['p90_MS5'] = 'required|numeric|min:0|not_in:' . request()->p10_MS5;
                    $rulesSpecial['ms_barium_concentration'] = 'numeric';
                }

                $rules = array_merge($rules, $rulesSpecial);
            }

            if (request()->fbAvailable) {
                $rulesSpecial = array();
                $availableArray = request()->fbAvailable;

                if (in_array('1', $availableArray)) {
                    $rulesSpecial['FB1'] = 'required|numeric|between:0,1000000';
                    $rulesSpecial['dateFB1'] = 'required|date_format:d/m/Y|before:' . Carbon::now()->addDays(1)->format('d/m/Y');
                    $rulesSpecial['FB1comment'] = 'string|max:100';
                    $rulesSpecial['p10_FB1'] = 'required|numeric|min:0';
                    $rulesSpecial['p90_FB1'] = 'required|numeric|min:0|not_in:' . request()->p10_FB1;
                    $rulesSpecial['fb_aluminum_concentration'] = 'numeric';
                }

                if (in_array('2', $availableArray)) {
                    $rulesSpecial['FB2'] = 'required|numeric|between:0,1000000';
                    $rulesSpecial['dateFB2'] = 'required|date_format:d/m/Y|before:' . Carbon::now()->addDays(1)->format('d/m/Y');
                    $rulesSpecial['FB2comment'] = 'string|max:100';
                    $rulesSpecial['p10_FB2'] = 'required|numeric|min:0';
                    $rulesSpecial['p90_FB2'] = 'required|numeric|min:0|not_in:' . request()->p10_FB2;
                    $rulesSpecial['fb_silicon_concentration'] = 'numeric';
                }

                if (in_array('3', $availableArray)) {
                    $rulesSpecial['FB3'] = 'required|numeric|between:0,100';
                    $rulesSpecial['dateFB3'] = 'required|date_format:d/m/Y|before:' . Carbon::now()->addDays(1)->format('d/m/Y');
                    $rulesSpecial['FB3comment'] = 'string|max:100';
                    $rulesSpecial['p10_FB3'] = 'required|numeric|min:0';
                    $rulesSpecial['p90_FB3'] = 'required|numeric|min:0|not_in:' . request()->p10_FB3;
                    $rulesSpecial['fb_critical_radius_factor'] = 'numeric';
                }

                if (in_array('4', $availableArray)) {
                    $rulesSpecial['FB4'] = 'required|numeric|between:0,1';
                    $rulesSpecial['dateFB4'] = 'required|date_format:d/m/Y|before:' . Carbon::now()->addDays(1)->format('d/m/Y');
                    $rulesSpecial['FB4comment'] = 'string|max:100';
                    $rulesSpecial['p10_FB4'] = 'required|numeric|min:0';
                    $rulesSpecial['p90_FB4'] = 'required|numeric|min:0|not_in:' . request()->p10_FB4;
                    $rulesSpecial['fb_mineralogic_factor'] = 'numeric';
                }

                if (in_array('5', $availableArray)) {
                    $rulesSpecial['FB5'] = 'required|numeric|min:0';
                    $rulesSpecial['dateFB5'] = 'required|date_format:d/m/Y|before:' . Carbon::now()->addDays(1)->format('d/m/Y');
                    $rulesSpecial['FB5comment'] = 'string|max:100';
                    $rulesSpecial['p10_FB5'] = 'required|numeric|min:0';
                    $rulesSpecial['p90_FB5'] = 'required|numeric|min:0|not_in:' . request()->p10_FB5;
                    $rulesSpecial['fb_crushed_proppant_factor'] = 'numeric';
                }

                $rules = array_merge($rules, $rulesSpecial);
            }

            if (request()->osAvailable) {
                $rulesSpecial = array();
                $availableArray = request()->osAvailable;

                if (in_array('1', $availableArray)) {
                    $rulesSpecial['OS1'] = 'required|numeric|between:0,14';
                    $rulesSpecial['dateOS1'] = 'required|date_format:d/m/Y|before:' . Carbon::now()->addDays(1)->format('d/m/Y');
                    $rulesSpecial['OS1comment'] = 'string|max:100';
                    $rulesSpecial['p10_OS1'] = 'required|numeric|min:0';
                    $rulesSpecial['p90_OS1'] = 'required|numeric|min:0|not_in:' . request()->p10_OS1;
                    $rulesSpecial['os_cll_factor'] = 'numeric';
                }

                if (in_array('2', $availableArray)) {
                    $rulesSpecial['OS2'] = 'required|numeric|min:0';
                    $rulesSpecial['dateOS2'] = 'required|date_format:d/m/Y|before:' . Carbon::now()->addDays(1)->format('d/m/Y');
                    $rulesSpecial['OS2comment'] = 'string|max:100';
                    $rulesSpecial['p10_OS2'] = 'required|numeric|min:0';
                    $rulesSpecial['p90_OS2'] = 'required|numeric|min:0|not_in:' . request()->p10_OS2;
                    $rulesSpecial['os_volume_of_hcl'] = 'numeric';
                }

                if (in_array('3', $availableArray)) {
                    $rulesSpecial['OS3'] = 'required|numeric|min:0';
                    $rulesSpecial['dateOS3'] = 'required|date_format:d/m/Y|before:' . Carbon::now()->addDays(1)->format('d/m/Y');
                    $rulesSpecial['OS3comment'] = 'string|max:100';
                    $rulesSpecial['p10_OS3'] = 'required|numeric|min:0';
                    $rulesSpecial['p90_OS3'] = 'required|numeric|min:0|not_in:' . request()->p10_OS3;
                    $rulesSpecial['os_compositional_factor'] = 'numeric';
                }

                if (in_array('4', $availableArray)) {
                    $rulesSpecial['OS4'] = 'required|numeric|between:0,20000';
                    $rulesSpecial['dateOS4'] = 'required|date_format:d/m/Y|before:' . Carbon::now()->addDays(1)->format('d/m/Y');
                    $rulesSpecial['OS4comment'] = 'string|max:100';
                    $rulesSpecial['p10_OS4'] = 'required|numeric|min:0';
                    $rulesSpecial['p90_OS4'] = 'required|numeric|min:0|not_in:' . request()->p10_OS4;
                    $rulesSpecial['os_pressure_factor'] = 'numeric';
                }

                if (in_array('5', $availableArray)) {
                    $rulesSpecial['OS5'] = 'required|numeric';
                    $rulesSpecial['dateOS5'] = 'required|date_format:d/m/Y|before:' . Carbon::now()->addDays(1)->format('d/m/Y');
                    $rulesSpecial['OS5comment'] = 'string|max:100';
                    $rulesSpecial['p10_OS5'] = 'required|numeric|min:0';
                    $rulesSpecial['p90_OS5'] = 'required|numeric|min:0|not_in:' . request()->p10_OS5;
                    $rulesSpecial['os_high_impact_factor'] = 'numeric';
                }

                $rules = array_merge($rules, $rulesSpecial);
            }

            if (request()->rpAvailable) {
                $rulesSpecial = array();
                $availableArray = request()->rpAvailable;

                if (in_array('1', $availableArray)) {
                    $rulesSpecial['RP1'] = 'required|numeric|between:0,20000';
                    $rulesSpecial['dateRP1'] = 'required|date_format:d/m/Y|before:' . Carbon::now()->addDays(1)->format('d/m/Y');
                    $rulesSpecial['RP1comment'] = 'string|max:100';
                    $rulesSpecial['p10_RP1'] = 'required|numeric|min:0';
                    $rulesSpecial['p90_RP1'] = 'required|numeric|min:0|not_in:' . request()->p10_RP1;
                    $rulesSpecial['rp_days_below_saturation_pressure'] = 'numeric';
                }

                if (in_array('2', $availableArray)) {
                    $rulesSpecial['RP2'] = 'required|numeric|between:-15000,15000';
                    $rulesSpecial['dateRP2'] = 'required|date_format:d/m/Y|before:' . Carbon::now()->addDays(1)->format('d/m/Y');
                    $rulesSpecial['RP2comment'] = 'string|max:100';
                    $rulesSpecial['p10_RP2'] = 'required|numeric|min:0';
                    $rulesSpecial['p90_RP2'] = 'required|numeric|min:0|not_in:' . request()->p10_RP2;
                    $rulesSpecial['rp_delta_pressure_saturation'] = 'numeric';
                }

                if (in_array('3', $availableArray)) {
                    $rulesSpecial['RP3'] = 'required|numeric|min:0|not_in:0';
                    $rulesSpecial['dateRP3'] = 'required|date_format:d/m/Y|before:' . Carbon::now()->addDays(1)->format('d/m/Y');
                    $rulesSpecial['RP3comment'] = 'string|max:100';
                    $rulesSpecial['p10_RP3'] = 'required|numeric|min:0';
                    $rulesSpecial['p90_RP3'] = 'required|numeric|min:0|not_in:' . request()->p10_RP3;
                    $rulesSpecial['rp_water_intrusion'] = 'numeric';
                }

                if (in_array('4', $availableArray)) {
                    $rulesSpecial['RP4'] = 'required|numeric|min:0|not_in:0';
                    $rulesSpecial['dateRP4'] = 'required|date_format:d/m/Y|before:' . Carbon::now()->addDays(1)->format('d/m/Y');
                    $rulesSpecial['RP4comment'] = 'string|max:100';
                    $rulesSpecial['p10_RP4'] = 'required|numeric|min:0';
                    $rulesSpecial['p90_RP4'] = 'required|numeric|min:0|not_in:' . request()->p10_RP4;
                    $rulesSpecial['rp_high_impact_factor'] = 'numeric';
                }

                $rules = array_merge($rules, $rulesSpecial);
            }

            if (request()->idAvailable) {
                $rulesSpecial = array();
                $availableArray = request()->idAvailable;

                if (in_array('1', $availableArray)) {
                    $rulesSpecial['ID1'] = 'required|numeric|between:0,10000';
                    $rulesSpecial['dateID1'] = 'required|date_format:d/m/Y|before:' . Carbon::now()->addDays(1)->format('d/m/Y');
                    $rulesSpecial['ID1comment'] = 'string|max:100';
                    $rulesSpecial['p10_ID1'] = 'required|numeric|min:0';
                    $rulesSpecial['p90_ID1'] = 'required|numeric|min:0|not_in:' . request()->p10_ID1;
                    $rulesSpecial['id_gross_pay'] = 'numeric';
                }

                if (in_array('2', $availableArray)) {
                    $rulesSpecial['ID2'] = 'required|numeric|min:0';
                    $rulesSpecial['dateID2'] = 'required|date_format:d/m/Y|before:' . Carbon::now()->addDays(1)->format('d/m/Y');
                    $rulesSpecial['ID2comment'] = 'string|max:100';
                    $rulesSpecial['p10_ID2'] = 'required|numeric|min:0';
                    $rulesSpecial['p90_ID2'] = 'required|numeric|min:0|not_in:' . request()->p10_ID2;
                    $rulesSpecial['id_polymer_damage_factor'] = 'numeric';
                }

                if (in_array('3', $availableArray)) {
                    $rulesSpecial['ID3'] = 'required|numeric|between:0,1000000';
                    $rulesSpecial['dateID3'] = 'required|date_format:d/m/Y|before:' . Carbon::now()->addDays(1)->format('d/m/Y');
                    $rulesSpecial['ID3comment'] = 'string|max:100';
                    $rulesSpecial['p10_ID3'] = 'required|numeric|min:0';
                    $rulesSpecial['p90_ID3'] = 'required|numeric|min:0|not_in:' . request()->p10_ID3;
                    $rulesSpecial['id_total_volume_water'] = 'numeric';
                }

                if (in_array('4', $availableArray)) {
                    $rulesSpecial['ID4'] = 'required|numeric|between:0,10000';
                    $rulesSpecial['dateID4'] = 'required|date_format:d/m/Y|before:' . Carbon::now()->addDays(1)->format('d/m/Y');
                    $rulesSpecial['ID4comment'] = 'string|max:100';
                    $rulesSpecial['p10_ID4'] = 'required|numeric|min:0';
                    $rulesSpecial['p90_ID4'] = 'required|numeric|min:0|not_in:' . request()->p10_ID4;
                    $rulesSpecial['id_mud_damage_factor'] = 'numeric';
                }

                $rules = array_merge($rules, $rulesSpecial);
            }

            if (request()->gdAvailable) {
                $rulesSpecial = array();
                $availableArray = request()->gdAvailable;

                if (in_array('1', $availableArray)) {
                    $rulesSpecial['GD1'] = 'required|numeric|between:0,1';
                    $rulesSpecial['dateGD1'] = 'required|date_format:d/m/Y|before:' . Carbon::now()->addDays(1)->format('d/m/Y');
                    $rulesSpecial['GD1comment'] = 'string|max:100';
                    $rulesSpecial['p10_GD1'] = 'required|numeric|min:0';
                    $rulesSpecial['p90_GD1'] = 'required|numeric|min:0|not_in:' . request()->p10_GD1;
                    $rulesSpecial['gd_fraction_netpay'] = 'numeric';
                }

                if (in_array('2', $availableArray)) {
                    $rulesSpecial['GD2'] = 'required|numeric|between:0,10000';
                    $rulesSpecial['dateGD2'] = 'required|date_format:d/m/Y|before:' . Carbon::now()->addDays(1)->format('d/m/Y');
                    $rulesSpecial['GD2comment'] = 'string|max:100';
                    $rulesSpecial['p10_GD2'] = 'required|numeric|min:0';
                    $rulesSpecial['p90_GD2'] = 'required|numeric|min:0|not_in:' . request()->p10_GD2;
                    $rulesSpecial['gd_drawdown'] = 'numeric';
                }

                if (in_array('3', $availableArray)) {
                    $rulesSpecial['GD3'] = 'required|numeric|between:0,1';
                    $rulesSpecial['dateGD3'] = 'required|date_format:d/m/Y|before:' . Carbon::now()->addDays(1)->format('d/m/Y');
                    $rulesSpecial['GD3comment'] = 'string|max:100';
                    $rulesSpecial['p10_GD3'] = 'required|numeric|min:0';
                    $rulesSpecial['p90_GD3'] = 'required|numeric|min:0|not_in:' . request()->p10_GD3;
                    $rulesSpecial['gd_ratio_kh_fracture'] = 'numeric';
                }

                if (in_array('4', $availableArray)) {
                    $rulesSpecial['GD4'] = 'required|numeric|between:0,1';
                    $rulesSpecial['dateGD4'] = 'required|date_format:d/m/Y|before:' . Carbon::now()->addDays(1)->format('d/m/Y');
                    $rulesSpecial['GD4comment'] = 'string|max:100';
                    $rulesSpecial['p10_GD4'] = 'required|numeric|min:0';
                    $rulesSpecial['p90_GD4'] = 'required|numeric|min:0|not_in:' . request()->p10_GD4;
                    $rulesSpecial['gd_geomechanical_damage_fraction'] = 'numeric';
                }

                $rules = array_merge($rules, $rulesSpecial);
            }

            return $rules;
        }
    }

    public function messages()
    {
        return [
            'statistical.required' => 'The checkbox needs to be checked.',
            'statistical.in' => 'The data provided by the checkbox is not correct.',
            'basin_statistical.required' => 'Basin is required.',
            'field_statistical.required' => 'Field is required.',

            'basin_statistical.exists' => "This Basin doesn't exist.",
            'field_statistical.exists' => "This Field doesn't exist.",

            'MS1.required' => 'Scale index of CaCO3 value is required.',
            'MS2.required' => 'Scale index of BaSO4 value is required.',
            'MS3.required' => 'Scale index of iron scales value is required.',
            'MS4.required' => 'Backflow [Ca] value is required.',
            'MS5.required' => 'Backflow [Ba] value is required.',

            'FB1.required' => '[Al] on Produced Water value is required.',
            'FB2.required' => '[Si] on produced water value is required.',
            'FB3.required' => 'Critical Radius derived from maximum critical velocity, Vc value is required.',
            'FB4.required' => 'Mineralogy Factor value is required.',
            'FB5.required' => 'Mass of crushed proppant inside Hydraulic Fractures value is required.',

            'OS1.required' => 'CII Factor: Colloidal Instability Index value is required.',
            'OS2.required' => 'Volume of HCL pumped into the formation value is required.',
            'OS3.required' => 'Cumulative Gas Produced value is required.',
            'OS4.required' => 'Number Of Days Below Saturation Pressure value is required.',
            'OS5.required' => 'De Boer Criteria value is required.',

            'RP1.required' => 'Number of days below Saturation Pressure value is required.',
            'RP2.required' => 'Delta Pressure From Saturation Pressure value is required.',
            'RP3.required' => 'Cumulative Water Produced value is required.',
            'RP4.required' => 'Pore Size Diameter Approximation By Katz And Thompson Correlation value is required.',

            'ID1.required' => 'Gross Pay is required.',
            'ID2.required' => 'Total polymer pumped during Hydraulic Fracturing value is required.',
            'ID3.required' => 'Total volume of water based fluids pumped into the well value is required.',
            'ID4.required' => 'Mud Losses value is required.',

            'GD1.required' => 'Percentage of Net Pay exihibiting Natural value is required.',
            'GD2.required' => 'Drawdown value is required.',
            'GD3.required' => 'Ratio of KH)matrix + fracture / KH)matrix value is required.',
            'GD4.required' => 'Geomechanical damage expressed as fraction of base permeability at BHFP value is required.',

            'dateMS1.required' => 'Scale index of CaCO3 monitoring date is required.',
            'dateMS2.required' => 'Scale index of BaSO4 monitoring date is required.',
            'dateMS3.required' => 'Scale index of iron scales monitoring date is required.',
            'dateMS4.required' => 'Backflow [Ca] monitoring date is required.',
            'dateMS5.required' => 'Backflow [Ba] monitoring date is required.',

            'dateFB1.required' => '[Al] on Produced Water monitoring date is required.',
            'dateFB2.required' => '[Si] on produced water monitoring date is required.',
            'dateFB3.required' => 'Critical Radius derived from maximum critical velocity, Vc monitoring date is required.',
            'dateFB4.required' => 'Mineralogy Factor monitoring date is required.',
            'dateFB5.required' => 'Mass of crushed proppant inside Hydraulic Fractures monitoring date is required.',

            'dateOS1.required' => 'CII Factor: Colloidal Instability Index monitoring date is required.',
            'dateOS2.required' => 'Volume of HCL pumped into the formation monitoring date is required.',
            'dateOS3.required' => 'Cumulative Gas Produced monitoring date is required.',
            'dateOS4.required' => 'Number Of Days Below Saturation Pressure monitoring date is required.',
            'dateOS5.required' => 'De Boer Criteria monitoring date is required.',

            'dateRP1.required' => 'Number of days below Saturation Pressure monitoring date is required.',
            'dateRP2.required' => 'Delta Pressure From Saturation Pressure monitoring date is required.',
            'dateRP3.required' => 'Cumulative Water Produced monitoring date is required.',
            'dateRP4.required' => 'Pore Size Diameter Approximation By Katz And Thompson Correlation monitoring date is required.',

            'dateID1.required' => 'Gross Pay monitoring date is required.',
            'dateID2.required' => 'Total polymer pumped during Hydraulic Fracturing monitoring date is required.',
            'dateID3.required' => 'Total volume of water based fluids pumped into the well monitoring date is required.',
            'dateID4.required' => 'Mud Losses monitoring date is required.',

            'dateGD1.required' => 'Percentage of Net Pay exihibiting Natural monitoring date is required.',
            'dateGD2.required' => 'Drawdown monitoring date is required.',
            'dateGD3.required' => 'Ratio of KH)matrix + fracture / KH)matrix monitoring date is required.',
            'dateGD4.required' => 'Geomechanical damage expressed as fraction of base permeability at BHFP monitoring date is required.',

            'p10_MS1.required' => 'Scale index of CaCO3 p10 is required.',
            'p10_MS2.required' => 'Scale index of BaSO4 p10 is required.',
            'p10_MS3.required' => 'Scale index of iron scales p10 is required.',
            'p10_MS4.required' => 'Backflow [Ca] p10 is required.',
            'p10_MS5.required' => 'Backflow [Ba] p10 is required.',

            'p10_FB1.required' => '[Al] on Produced Water p10 is required.',
            'p10_FB2.required' => '[Si] on produced water p10 is required.',
            'p10_FB3.required' => 'Critical Radius derived from maximum critical velocity, Vc p10 is required.',
            'p10_FB4.required' => 'requirederalogy Factor p10 is required.',
            'p10_FB5.required' => 'Mass of crushed proppant inside Hydraulic Fractures p10 is required.',

            'p10_OS1.required' => 'CII Factor: Colloidal Instability Index p10 is required.',
            'p10_OS2.required' => 'Volume of HCL pumped into the formation p10 is required.',
            'p10_OS3.required' => 'Cumulative Gas Produced p10 is required.',
            'p10_OS4.required' => 'Number Of Days Below Saturation Pressure p10 is required.',
            'p10_OS5.required' => 'De Boer Criteria p10 is required.',

            'p10_RP1.required' => 'Number Of Days Below Saturation Pressure p10 is required.',
            'p10_RP2.required' => 'Delta Pressure From Saturation Pressure p10 is required.',
            'p10_RP3.required' => 'Cumulative Water Produced p10 is required.',
            'p10_RP4.required' => 'Pore Size Diameter Approximation By Katz And Thompson Correlation p10 is required.',

            'p10_ID1.required' => 'Gross Pay p10 is required.',
            'p10_ID2.required' => 'Total polymer pumped during Hydraulic Fracturing p10 is required.',
            'p10_ID3.required' => 'Total volume of water based fluids pumped into the well p10 is required.',
            'p10_ID4.required' => 'Mud Losses p10 is required.',

            'p10_GD1.required' => 'Percentage of Net Pay exihibiting Natural p10 is required.',
            'p10_GD2.required' => 'Drawdown p10 is required.',
            'p10_GD3.required' => 'Ratio of KH)matrix + fracture / KH)matrix p10 is required.',
            'p10_GD4.required' => 'Geomechanical damage expressed as fraction of base permeability at BHFP p10 is required.',

            'p90_MS1.required' => 'Scale index of CaCO3 p90 is required.',
            'p90_MS2.required' => 'Scale index of BaSO4 p90 is required.',
            'p90_MS3.required' => 'Scale index of iron scales p90 is required.',
            'p90_MS4.required' => 'Backflow [Ca] p90 is required.',
            'p90_MS5.required' => 'Backflow [Ba] p90 is required.',

            'p90_FB1.required' => '[Al] on Produced Water p90 is required.',
            'p90_FB2.required' => '[Si] on produced water p90 is required.',
            'p90_FB3.required' => 'Critical Radius derived from maximum critical velocity, Vc p90 is required.',
            'p90_FB4.required' => 'requirederalogy Factor p90 is required.',
            'p90_FB5.required' => 'Mass of crushed proppant inside Hydraulic Fractures p90 is required.',

            'p90_OS1.required' => 'CII Factor: Colloidal Instability Index p90 is required.',
            'p90_OS2.required' => 'Volume of HCL pumped into the formation p90 is required.',
            'p90_OS3.required' => 'Cumulative Gas Produced p90 is required.',
            'p90_OS4.required' => 'Number Of Days Below Saturation Pressure p90 is required.',
            'p90_OS5.required' => 'De Boer Criteria p90 is required.',

            'p90_RP1.required' => 'Number Of Days Below Saturation Pressure p90 is required.',
            'p90_RP2.required' => 'Delta Pressure From Saturation Pressure p90 is required.',
            'p90_RP3.required' => 'Cumulative Water Produced p90 is required.',
            'p90_RP4.required' => 'Pore Size Diameter Approximation By Katz And Thompson Correlation p90 is required.',

            'p90_ID1.required' => 'Gross Pay p90 is required.',
            'p90_ID2.required' => 'Total polymer pumped during Hydraulic Fracturing p90 is required.',
            'p90_ID3.required' => 'Total volume of water based fluids pumped into the well p90 is required.',
            'p90_ID4.required' => 'Mud Losses p90 is required.',

            'p90_GD1.required' => 'Percentage of Net Pay exihibiting Natural p90 is required.',
            'p90_GD2.required' => 'Drawdown p90 is required.',
            'p90_GD3.required' => 'Ratio of KH)matrix + fracture / KH)matrix p90 is required.',
            'p90_GD4.required' => 'Geomechanical damage expressed as fraction of base permeability at BHFP p90 is required.',

            'MS1.numeric' => 'Scale index of CaCO3 value must be numeric.',
            'MS2.numeric' => 'Scale index of BaSO4 value must be numeric.',
            'MS3.numeric' => 'Scale index of iron scales value must be numeric.',
            'MS4.numeric' => 'Backflow [Ca] value must be numeric.',
            'MS5.numeric' => 'Backflow [Ba] value must be numeric.',

            'FB1.numeric' => '[Al] on Produced Water value must be numeric.',
            'FB2.numeric' => '[Si] on produced water value must be numeric.',
            'FB3.numeric' => 'Critical Radius derived from maximum critical velocity, Vc value must be numeric.',
            'FB4.numeric' => 'Mineralogy Factor value must be numeric.',
            'FB5.numeric' => 'Mass of crushed proppant inside Hydraulic Fractures value must be numeric.',

            'OS1.numeric' => 'CII Factor: Colloidal Instability Index value must be numeric.',
            'OS2.numeric' => 'Volume of HCL pumped into the formation value must be numeric.',
            'OS3.numeric' => 'Cumulative Gas Produced value must be numeric.',
            'OS4.numeric' => 'Number Of Days Below Saturation Pressure value must be numeric.',
            'OS5.numeric' => 'De Boer Criteria value must be numeric.',

            'RP1.numeric' => 'Number Of Days Below Saturation Pressure value must be numeric.',
            'RP2.numeric' => 'Delta Pressure From Saturation Pressure value must be numeric.',
            'RP3.numeric' => 'Cumulative Water Produced value must be numeric.',
            'RP4.numeric' => 'Pore Size Diameter Approximation By Katz And Thompson Correlation value must be numeric.',

            'ID1.numeric' => 'Gross Pay value must be numeric.',
            'ID2.numeric' => 'Total polymer pumped during Hydraulic Fracturing value must be numeric.',
            'ID3.numeric' => 'Total volume of water based fluids pumped into the well value must be numeric.',
            'ID4.numeric' => 'Mud Losses value must be numeric.',

            'GD1.numeric' => 'Percentage of Net Pay exihibiting Natural value must be numeric.',
            'GD2.numeric' => 'Drawdown value must be numeric.',
            'GD3.numeric' => 'Ratio of KH)matrix + fracture / KH)matrix value must be numeric.',
            'GD4.numeric' => 'Geomechanical damage expressed as fraction of base permeability at BHFP value must be numeric.',

            'MS1.min' => 'Scale index of CaCO3 value must be higher or equal than :min.',
            'MS2.min' => 'Scale index of BaSO4 value must be higher or equal than :min.',
            'MS3.min' => 'Scale index of iron scales value must be higher or equal than :min.',
            'MS4.between' => 'Backflow [Ca] value is not between :min - :max.',
            'MS5.between' => 'Backflow [Ba] value is not between :min - :max.',

            'FB1.between' => '[Al] on Produced Water value is not between :min - :max.',
            'FB2.between' => '[Si] on produced water value is not between :min - :max.',
            'FB3.between' => 'Critical Radius derived from maximum critical velocity, Vc value is not between :min - :max.',
            'FB4.between' => 'Mineralogy Factor value is not between :min - :max.',
            'FB5.min' => 'Mass of crushed proppant inside Hydraulic Fractures value must be higher or equal than :min.',

            'OS1.between' => 'CII Factor: Colloidal Instability Index value is not between :min - :max.',
            'OS2.min' => 'Volume of HCL pumped into the formation value must be higher or equal than :min.',
            'OS3.min' => 'Cumulative Gas Produced value must be higher or equal than :min.',
            'OS4.between' => 'Number Of Days Below Saturation Pressure value is not between :min - :max.',

            'RP1.between' => 'Number Of Days Below Saturation Pressure value is not between :min - :max.',
            'RP2.between' => 'Delta Pressure From Saturation Pressure value is not between :min - :max.',
            'RP3.min' => 'Cumulative Water Produced value must be higher than :min.',
            'RP4.min' => 'Pore Size Diameter Approximation By Katz And Thompson Correlation value must be higher than :min.',

            'ID1.between' => 'Gross Pay value is not between :min - :max.',
            'ID2.min' => 'Total polymer pumped during Hydraulic Fracturing value must be higher or equal than :min.',
            'ID3.between' => 'Total volume of water based fluids pumped into the well value is not between :min - :max.',
            'ID4.between' => 'Mud Losses value is not between :min - :max.',

            'GD1.between' => 'Percentage of Net Pay exihibiting Natural value is not between :min - :max.',
            'GD2.between' => 'Drawdown value is not between :min - :max.',
            'GD3.between' => 'Ratio of KH)matrix + fracture / KH)matrix value is not between :min - :max.',
            'GD4.between' => 'Geomechanical damage expressed as fraction of base permeability at BHFP value is not between :min - :max.',

            'RP3.not_in' => 'Cumulative Water Produced value must be higher than 0.',
            'RP4.not_in' => 'Pore Size Diameter Approximation By Katz And Thompson Correlation value must be higher than 0.',

            'dateMS1.date_format' => 'Scale index of CaCO3 monitoring date must have the format dd/mm/yyyy.',
            'dateMS2.date_format' => 'Scale index of BaSO4 monitoring date must have the format dd/mm/yyyy.',
            'dateMS3.date_format' => 'Scale index of iron scales monitoring date must have the format dd/mm/yyyy.',
            'dateMS4.date_format' => 'Backflow [Ca] monitoring date must have the format dd/mm/yyyy.',
            'dateMS5.date_format' => 'Backflow [Ba] monitoring date must have the format dd/mm/yyyy.',

            'dateFB1.date_format' => '[Al] on Produced Water monitoring date must have the format dd/mm/yyyy.',
            'dateFB2.date_format' => '[Si] on produced water monitoring date must have the format dd/mm/yyyy.',
            'dateFB3.date_format' => 'Critical Radius derived from maximum critical velocity, Vc monitoring date must have the format dd/mm/yyyy.',
            'dateFB4.date_format' => 'Mineralogy Factor monitoring date must have the format dd/mm/yyyy.',
            'dateFB5.date_format' => 'Mass of crushed proppant inside Hydraulic Fractures monitoring date must have the format dd/mm/yyyy.',

            'dateOS1.date_format' => 'CII Factor: Colloidal Instability Index monitoring date must have the format dd/mm/yyyy.',
            'dateOS2.date_format' => 'Volume of HCL pumped into the formation monitoring date must have the format dd/mm/yyyy.',
            'dateOS3.date_format' => 'Cumulative Gas Produced monitoring date must have the format dd/mm/yyyy.',
            'dateOS4.date_format' => 'Number Of Days Below Saturation Pressure monitoring date must have the format dd/mm/yyyy.',
            'dateOS5.date_format' => 'De Boer Criteria monitoring date must have the format dd/mm/yyyy.',

            'dateRP1.date_format' => 'Number of days below Saturation Pressure monitoring date must have the format dd/mm/yyyy.',
            'dateRP2.date_format' => 'Delta Pressure From Saturation Pressure monitoring date must have the format dd/mm/yyyy.',
            'dateRP3.date_format' => 'Cumulative Water Produced monitoring date must have the format dd/mm/yyyy.',
            'dateRP4.date_format' => 'Pore Size Diameter Approximation By Katz And Thompson Correlation monitoring date must have the format dd/mm/yyyy.',

            'dateID1.date_format' => 'Gross Pay monitoring date must have the format dd/mm/yyyy.',
            'dateID2.date_format' => 'Total polymer pumped during Hydraulic Fracturing monitoring date must have the format dd/mm/yyyy.',
            'dateID3.date_format' => 'Total volume of water based fluids pumped into the well monitoring date must have the format dd/mm/yyyy.',
            'dateID4.date_format' => 'Mud Losses monitoring date must have the format dd/mm/yyyy.',

            'dateGD1.date_format' => 'Percentage of Net Pay exihibiting Natural monitoring date must have the format dd/mm/yyyy.',
            'dateGD2.date_format' => 'Drawdown monitoring date must have the format dd/mm/yyyy.',
            'dateGD3.date_format' => 'Ratio of KH)matrix + fracture / KH)matrix monitoring date must have the format dd/mm/yyyy.',
            'dateGD4.date_format' => 'Geomechanical damage expressed as fraction of base permeability at BHFP monitoring date must have the format dd/mm/yyyy.',

            'dateMS1.before' => 'Scale index of CaCO3 monitoring date must be a date before or equal than ' . Carbon::now()->format('d/m/Y') . '.',
            'dateMS2.before' => 'Scale index of BaSO4 monitoring date must be a date before or equal than ' . Carbon::now()->format('d/m/Y') . '.',
            'dateMS3.before' => 'Scale index of iron scales monitoring date must be a date before or equal than ' . Carbon::now()->format('d/m/Y') . '.',
            'dateMS4.before' => 'Backflow [Ca] monitoring date must be a date before or equal than ' . Carbon::now()->format('d/m/Y') . '.',
            'dateMS5.before' => 'Backflow [Ba] monitoring date must be a date before or equal than ' . Carbon::now()->format('d/m/Y') . '.',

            'dateFB1.before' => '[Al] on Produced Water monitoring date must be a date before or equal than ' . Carbon::now()->format('d/m/Y') . '.',
            'dateFB2.before' => '[Si] on produced water monitoring date must be a date before or equal than ' . Carbon::now()->format('d/m/Y') . '.',
            'dateFB3.before' => 'Critical Radius derived from maximum critical velocity, Vc monitoring date must be a date before or equal than ' . Carbon::now()->format('d/m/Y') . '.',
            'dateFB4.before' => 'Mineralogy Factor monitoring date must be a date before or equal than ' . Carbon::now()->format('d/m/Y') . '.',
            'dateFB5.before' => 'Mass of crushed proppant inside Hydraulic Fractures monitoring date must be a date before or equal than ' . Carbon::now()->format('d/m/Y') . '.',

            'dateOS1.before' => 'CII Factor: Colloidal Instability Index monitoring date must be a date before or equal than ' . Carbon::now()->format('d/m/Y') . '.',
            'dateOS2.before' => 'Volume of HCL pumped into the formation monitoring date must be a date before or equal than ' . Carbon::now()->format('d/m/Y') . '.',
            'dateOS3.before' => 'Cumulative Gas Produced monitoring date must be a date before or equal than ' . Carbon::now()->format('d/m/Y') . '.',
            'dateOS4.before' => 'Number Of Days Below Saturation Pressure monitoring date must be a date before or equal than ' . Carbon::now()->format('d/m/Y') . '.',
            'dateOS5.before' => 'De Boer Criteria monitoring date must be a date before or equal than ' . Carbon::now()->format('d/m/Y') . '.',

            'dateRP1.before' => 'Number of days below Saturation Pressure monitoring date must be a date before or equal than ' . Carbon::now()->format('d/m/Y') . '.',
            'dateRP2.before' => 'Delta Pressure From Saturation Pressure monitoring date must be a date before or equal than ' . Carbon::now()->format('d/m/Y') . '.',
            'dateRP3.before' => 'Cumulative Water Produced monitoring date must be a date before or equal than ' . Carbon::now()->format('d/m/Y') . '.',
            'dateRP4.before' => 'Pore Size Diameter Approximation By Katz And Thompson Correlation monitoring date must be a date before or equal than ' . Carbon::now()->format('d/m/Y') . '.',

            'dateID1.before' => 'Gross Pay monitoring date must be a date before or equal than ' . Carbon::now()->format('d/m/Y') . '.',
            'dateID2.before' => 'Total polymer pumped during Hydraulic Fracturing monitoring date must be a date before or equal than ' . Carbon::now()->format('d/m/Y') . '.',
            'dateID3.before' => 'Total volume of water based fluids pumped into the well monitoring date must be a date before or equal than ' . Carbon::now()->format('d/m/Y') . '.',
            'dateID4.before' => 'Mud Losses monitoring date must be a date before or equal than ' . Carbon::now()->format('d/m/Y') . '.',

            'dateGD1.before' => 'Percentage of Net Pay exihibiting Natural monitoring date must be a date before or equal than ' . Carbon::now()->format('d/m/Y') . '.',
            'dateGD2.before' => 'Drawdown monitoring date must be a date before or equal than ' . Carbon::now()->format('d/m/Y') . '.',
            'dateGD3.before' => 'Ratio of KH)matrix + fracture / KH)matrix monitoring date must be a date before or equal than ' . Carbon::now()->format('d/m/Y') . '.',
            'dateGD4.before' => 'Geomechanical damage expressed as fraction of base permeability at BHFP monitoring date must be a date before or equal than ' . Carbon::now()->format('d/m/Y') . '.',

            'MS1comment.string' => 'Scale index of CaCO3 comment must be a text.',
            'MS2comment.string' => 'Scale index of BaSO4 comment must be a text.',
            'MS3comment.string' => 'Scale index of iron scales comment must be a text.',
            'MS4comment.string' => 'Backflow [Ca] comment must be a text.',
            'MS5comment.string' => 'Backflow [Ba] comment must be a text.',

            'FB1comment.string' => '[Al] on Produced Water comment must be a text.',
            'FB2comment.string' => '[Si] on produced water comment must be a text.',
            'FB3comment.string' => 'Critical Radius derived from maximum critical velocity, Vc comment must be a text.',
            'FB4comment.string' => 'Mineralogy Factor comment must be a text.',
            'FB5comment.string' => 'Mass of crushed proppant inside Hydraulic Fractures comment must be a text.',

            'OS1comment.string' => 'CII Factor: Colloidal Instability Index comment must be a text.',
            'OS2comment.string' => 'Volume of HCL pumped into the formation comment must be a text.',
            'OS3comment.string' => 'Cumulative Gas Produced comment must be a text.',
            'OS4comment.string' => 'Number Of Days Below Saturation Pressure comment must be a text.',
            'OS5comment.string' => 'De Boer Criteria comment must be a text.',

            'RP1comment.string' => 'Number Of Days Below Saturation Pressure comment must be a text.',
            'RP2comment.string' => 'Delta Pressure From Saturation Pressure comment must be a text.',
            'RP3comment.string' => 'Cumulative Water Produced comment must be a text.',
            'RP4comment.string' => 'Pore Size Diameter Approximation By Katz And Thompson Correlation comment must be a text.',

            'ID1comment.string' => 'Gross Pay comment must be a text.',
            'ID2comment.string' => 'Total polymer pumped during Hydraulic Fracturing comment must be a text.',
            'ID3comment.string' => 'Total volume of water based fluids pumped into the well comment must be a text.',
            'ID4comment.string' => 'Mud Losses comment must be a text.',

            'GD1comment.string' => 'Percentage of Net Pay exihibiting Natural comment must be a text.',
            'GD2comment.string' => 'Drawdown comment must be a text.',
            'GD3comment.string' => 'Ratio of KH)matrix + fracture / KH)matrix comment must be a text.',
            'GD4comment.string' => 'Geomechanical damage expressed as fraction of base permeability at BHFP comment must be a text.',

            'MS1comment.max' => 'Scale index of CaCO3 comment may not be greater than :max characters.',
            'MS2comment.max' => 'Scale index of BaSO4 comment may not be greater than :max characters.',
            'MS3comment.max' => 'Scale index of iron scales comment may not be greater than :max characters.',
            'MS4comment.max' => 'Backflow [Ca] comment may not be greater than :max characters.',
            'MS5comment.max' => 'Backflow [Ba] comment may not be greater than :max characters.',

            'FB1comment.max' => '[Al] on Produced Water comment may not be greater than :max characters.',
            'FB2comment.max' => '[Si] on produced water comment may not be greater than :max characters.',
            'FB3comment.max' => 'Critical Radius derived from maximum critical velocity, Vc comment may not be greater than :max characters.',
            'FB4comment.max' => 'Mineralogy Factor comment may not be greater than :max characters.',
            'FB5comment.max' => 'Mass of crushed proppant inside Hydraulic Fractures comment may not be greater than :max characters.',

            'OS1comment.max' => 'CII Factor: Colloidal Instability Index comment may not be greater than :max characters.',
            'OS2comment.max' => 'Volume of HCL pumped into the formation comment may not be greater than :max characters.',
            'OS3comment.max' => 'Cumulative Gas Produced comment may not be greater than :max characters.',
            'OS4comment.max' => 'Number Of Days Below Saturation Pressure comment may not be greater than :max characters.',
            'OS5comment.max' => 'De Boer Criteria comment may not be greater than :max characters.',

            'RP1comment.max' => 'Number Of Days Below Saturation Pressure comment may not be greater than :max characters.',
            'RP2comment.max' => 'Delta Pressure From Saturation Pressure comment may not be greater than :max characters.',
            'RP3comment.max' => 'Cumulative Water Produced comment may not be greater than :max characters.',
            'RP4comment.max' => 'Pore Size Diameter Approximation By Katz And Thompson Correlation comment may not be greater than :max characters.',

            'ID1comment.max' => 'Gross Pay comment may not be greater than :max characters.',
            'ID2comment.max' => 'Total polymer pumped during Hydraulic Fracturing comment may not be greater than :max characters.',
            'ID3comment.max' => 'Total volume of water based fluids pumped into the well comment may not be greater than :max characters.',
            'ID4comment.max' => 'Mud Losses comment may not be greater than :max characters.',

            'GD1comment.max' => 'Percentage of Net Pay exihibiting Natural comment may not be greater than :max characters.',
            'GD2comment.max' => 'Drawdown comment may not be greater than :max characters.',
            'GD3comment.max' => 'Ratio of KH)matrix + fracture / KH)matrix comment may not be greater than :max characters.',
            'GD4comment.max' => 'Geomechanical damage expressed as fraction of base permeability at BHFP comment may not be greater than :max characters.',

            'p10_MS1.numeric' => 'Scale index of CaCO3 p10 must be numeric.',
            'p10_MS2.numeric' => 'Scale index of BaSO4 p10 must be numeric.',
            'p10_MS3.numeric' => 'Scale index of iron scales p10 must be numeric.',
            'p10_MS4.numeric' => 'Backflow [Ca] p10 must be numeric.',
            'p10_MS5.numeric' => 'Backflow [Ba] p10 must be numeric.',

            'p10_FB1.numeric' => '[Al] on Produced Water p10 must be numeric.',
            'p10_FB2.numeric' => '[Si] on produced water p10 must be numeric.',
            'p10_FB3.numeric' => 'Critical Radius derived from maximum critical velocity, Vc p10 must be numeric.',
            'p10_FB4.numeric' => 'Mineralogy Factor p10 must be numeric.',
            'p10_FB5.numeric' => 'Mass of crushed proppant inside Hydraulic Fractures p10 must be numeric.',

            'p10_OS1.numeric' => 'CII Factor: Colloidal Instability Index p10 must be numeric.',
            'p10_OS2.numeric' => 'Volume of HCL pumped into the formation p10 must be numeric.',
            'p10_OS3.numeric' => 'Cumulative Gas Produced p10 must be numeric.',
            'p10_OS4.numeric' => 'Number Of Days Below Saturation Pressure p10 must be numeric.',
            'p10_OS5.numeric' => 'De Boer Criteria p10 must be numeric.',

            'p10_RP1.numeric' => 'Number Of Days Below Saturation Pressure p10 must be numeric.',
            'p10_RP2.numeric' => 'Delta Pressure From Saturation Pressure p10 must be numeric.',
            'p10_RP3.numeric' => 'Cumulative Water Produced p10 must be numeric.',
            'p10_RP4.numeric' => 'Pore Size Diameter Approximation By Katz And Thompson Correlation p10 must be numeric.',

            'p10_ID1.numeric' => 'Gross Pay p10 must be numeric.',
            'p10_ID2.numeric' => 'Total polymer pumped during Hydraulic Fracturing p10 must be numeric.',
            'p10_ID3.numeric' => 'Total volume of water based fluids pumped into the well p10 must be numeric.',
            'p10_ID4.numeric' => 'Mud Losses p10 must be numeric.',

            'p10_GD1.numeric' => 'Percentage of Net Pay exihibiting Natural p10 must be numeric.',
            'p10_GD2.numeric' => 'Drawdown p10 must be numeric.',
            'p10_GD3.numeric' => 'Ratio of KH)matrix + fracture / KH)matrix p10 must be numeric.',
            'p10_GD4.numeric' => 'Geomechanical damage expressed as fraction of base permeability at BHFP p10 must be numeric.',

            'p90_MS1.numeric' => 'Scale index of CaCO3 p90 must be numeric.',
            'p90_MS2.numeric' => 'Scale index of BaSO4 p90 must be numeric.',
            'p90_MS3.numeric' => 'Scale index of iron scales p90 must be numeric.',
            'p90_MS4.numeric' => 'Backflow [Ca] p90 must be numeric.',
            'p90_MS5.numeric' => 'Backflow [Ba] p90 must be numeric.',

            'p90_FB1.numeric' => '[Al] on Produced Water p90 must be numeric.',
            'p90_FB2.numeric' => '[Si] on produced water p90 must be numeric.',
            'p90_FB3.numeric' => 'Critical Radius derived from maximum critical velocity, Vc p90 must be numeric.',
            'p90_FB4.numeric' => 'Mineralogy Factor p90 must be numeric.',
            'p90_FB5.numeric' => 'Mass of crushed proppant inside Hydraulic Fractures p90 must be numeric.',

            'p90_OS1.numeric' => 'CII Factor: Colloidal Instability Index p90 must be numeric.',
            'p90_OS2.numeric' => 'Volume of HCL pumped into the formation p90 must be numeric.',
            'p90_OS3.numeric' => 'Cumulative Gas Produced p90 must be numeric.',
            'p90_OS4.numeric' => 'Number Of Days Below Saturation Pressure p90 must be numeric.',
            'p90_OS5.numeric' => 'De Boer Criteria p90 must be numeric.',

            'p90_RP1.numeric' => 'Number Of Days Below Saturation Pressure p90 must be numeric.',
            'p90_RP2.numeric' => 'Delta Pressure From Saturation Pressure p90 must be numeric.',
            'p90_RP3.numeric' => 'Cumulative Water Produced p90 must be numeric.',
            'p90_RP4.numeric' => 'Pore Size Diameter Approximation By Katz And Thompson Correlation p90 must be numeric.',

            'p90_ID1.numeric' => 'Gross Pay p90 must be numeric.',
            'p90_ID2.numeric' => 'Total polymer pumped during Hydraulic Fracturing p90 must be numeric.',
            'p90_ID3.numeric' => 'Total volume of water based fluids pumped into the well p90 must be numeric.',
            'p90_ID4.numeric' => 'Mud Losses p90 must be numeric.',

            'p90_GD1.numeric' => 'Percentage of Net Pay exihibiting Natural p90 must be numeric.',
            'p90_GD2.numeric' => 'Drawdown p90 must be numeric.',
            'p90_GD3.numeric' => 'Ratio of KH)matrix + fracture / KH)matrix p90 must be numeric.',
            'p90_GD4.numeric' => 'Geomechanical damage expressed as fraction of base permeability at BHFP p90 must be numeric.',

            'p90_MS1.not_in' => "Scale index of CaCO3 p10 and p90 can't have the same value.",
            'p90_MS2.not_in' => "Scale index of BaSO4 p10 and p90 can't have the same value.",
            'p90_MS3.not_in' => "Scale index of iron scales p10 and p90 can't have the same value.",
            'p90_MS4.not_in' => "Backflow [Ca] p10 and p90 can't have the same value.",
            'p90_MS5.not_in' => "Backflow [Ba] p10 and p90 can't have the same value.",

            'p90_FB1.not_in' => "[Al] on Produced Water p10 and p90 can't have the same value.",
            'p90_FB2.not_in' => "[Si] on produced water p10 and p90 can't have the same value.",
            'p90_FB3.not_in' => "Critical Radius derived from maximum critical velocity, Vc p10 and p90 can't have the same value.",
            'p90_FB4.not_in' => "Mineralogy Factor p10 and p90 can't have the same value.",
            'p90_FB5.not_in' => "Mass of crushed proppant inside Hydraulic Fractures p10 and p90 can't have the same value.",

            'p90_OS1.not_in' => "CII Factor: Colloidal Instability Index p10 and p90 can't have the same value.",
            'p90_OS2.not_in' => "Volume of HCL pumped into the formation p10 and p90 can't have the same value.",
            'p90_OS3.not_in' => "Cumulative Gas Produced p10 and p90 can't have the same value.",
            'p90_OS4.not_in' => "Number Of Days Below Saturation Pressure p10 and p90 can't have the same value.",
            'p90_OS5.not_in' => "De Boer Criteria p10 and p90 can't have the same value.",

            'p90_RP1.not_in' => "Number Of Days Below Saturation Pressure p10 and p90 can't have the same value.",
            'p90_RP2.not_in' => "Delta Pressure From Saturation Pressure p10 and p90 can't have the same value.",
            'p90_RP3.not_in' => "Cumulative Water Produced p10 and p90 can't have the same value.",
            'p90_RP4.not_in' => "Pore Size Diameter Approximation By Katz And Thompson Correlation p10 and p90 can't have the same value.",

            'p90_ID1.not_in' => "Gross Pay p10 and p90 can't have the same value.",
            'p90_ID2.not_in' => "Total polymer pumped during Hydraulic Fracturing p10 and p90 can't have the same value.",
            'p90_ID3.not_in' => "Total volume of water based fluids pumped into the well p10 and p90 can't have the same value.",
            'p90_ID4.not_in' => "Mud Losses p10 and p90 can't have the same value.",

            'p90_GD1.not_in' => "Percentage of Net Pay exihibiting Natural p10 and p90 can't have the same value.",
            'p90_GD2.not_in' => "Drawdown p10 and p90 can't have the same value.",
            'p90_GD3.not_in' => "Ratio of KH)matrix + fracture / KH)matrix p10 and p90 can't have the same value.",
            'p90_GD4.not_in' => "Geomechanical damage expressed as fraction of base permeability at BHFP p10 and p90 can't have the same value.",

            'p10_MS1.min' => 'Scale index of CaCO3 p10 must be higher or equal than :min.',
            'p10_MS2.min' => 'Scale index of BaSO4 p10 must be higher or equal than :min.',
            'p10_MS3.min' => 'Scale index of iron scales p10 must be higher or equal than :min.',
            'p10_MS4.min' => 'Backflow [Ca] p10 must be higher or equal than :min.',
            'p10_MS5.min' => 'Backflow [Ba] p10 must be higher or equal than :min.',

            'p10_FB1.min' => '[Al] on Produced Water p10 must be higher or equal than :min.',
            'p10_FB2.min' => '[Si] on produced water p10 must be higher or equal than :min.',
            'p10_FB3.min' => 'Critical Radius derived from maximum critical velocity, Vc p10 must be higher or equal than :min.',
            'p10_FB4.min' => 'Mineralogy Factor p10 must be higher or equal than :min.',
            'p10_FB5.min' => 'Mass of crushed proppant inside Hydraulic Fractures p10 must be higher or equal than :min.',

            'p10_OS1.min' => 'CII Factor: Colloidal Instability Index p10 must be higher or equal than :min.',
            'p10_OS2.min' => 'Volume of HCL pumped into the formation p10 must be higher or equal than :min.',
            'p10_OS3.min' => 'Cumulative Gas Produced p10 must be higher or equal than :min.',
            'p10_OS4.min' => 'Number Of Days Below Saturation Pressure p10 must be higher or equal than :min.',
            'p10_OS5.min' => 'De Boer Criteria p10 must be higher or equal than :min.',

            'p10_RP1.min' => 'Number Of Days Below Saturation Pressure p10 must be higher or equal than :min.',
            'p10_RP2.min' => 'Delta Pressure From Saturation Pressure p10 must be higher or equal than :min.',
            'p10_RP3.min' => 'Cumulative Water Produced p10 must be higher or equal than :min.',
            'p10_RP4.min' => 'Pore Size Diameter Approximation By Katz And Thompson Correlation p10 must be higher or equal than :min.',

            'p10_ID1.min' => 'Gross Pay p10 must be higher or equal than :min.',
            'p10_ID2.min' => 'Total polymer pumped during Hydraulic Fracturing p10 must be higher or equal than :min.',
            'p10_ID3.min' => 'Total volume of water based fluids pumped into the well p10 must be higher or equal than :min.',
            'p10_ID4.min' => 'Mud Losses p10 must be higher or equal than :min.',

            'p10_GD1.min' => 'Percentage of Net Pay exihibiting Natural p10 must be higher or equal than :min.',
            'p10_GD2.min' => 'Drawdown p10 must be higher or equal than :min.',
            'p10_GD3.min' => 'Ratio of KH)matrix + fracture / KH)matrix p10 must be higher or equal than :min.',
            'p10_GD4.min' => 'Geomechanical damage expressed as fraction of base permeability at BHFP p10 must be higher or equal than :min.',

            'p90_MS1.min' => 'Scale index of CaCO3 p90 must be higher or equal than :min.',
            'p90_MS2.min' => 'Scale index of BaSO4 p90 must be higher or equal than :min.',
            'p90_MS3.min' => 'Scale index of iron scales p90 must be higher or equal than :min.',
            'p90_MS4.min' => 'Backflow [Ca] p90 must be higher or equal than :min.',
            'p90_MS5.min' => 'Backflow [Ba] p90 must be higher or equal than :min.',

            'p90_FB1.min' => '[Al] on Produced Water p90 must be higher or equal than :min.',
            'p90_FB2.min' => '[Si] on produced water p90 must be higher or equal than :min.',
            'p90_FB3.min' => 'Critical Radius derived from maximum critical velocity, Vc p90 must be higher or equal than :min.',
            'p90_FB4.min' => 'Mineralogy Factor p90 must be higher or equal than :min.',
            'p90_FB5.min' => 'Mass of crushed proppant inside Hydraulic Fractures p90 must be higher or equal than :min.',

            'p90_OS1.min' => 'CII Factor: Colloidal Instability Index p90 must be higher or equal than :min.',
            'p90_OS2.min' => 'Volume of HCL pumped into the formation p90 must be higher or equal than :min.',
            'p90_OS3.min' => 'Cumulative Gas Produced p90 must be higher or equal than :min.',
            'p90_OS4.min' => 'Number Of Days Below Saturation Pressure p90 must be higher or equal than :min.',
            'p90_OS5.min' => 'De Boer Criteria p90 must be higher or equal than :min.',

            'p90_RP1.min' => 'Number Of Days Below Saturation Pressure p90 must be higher or equal than :min.',
            'p90_RP2.min' => 'Delta Pressure From Saturation Pressure p90 must be higher or equal than :min.',
            'p90_RP3.min' => 'Cumulative Water Produced p90 must be higher or equal than :min.',
            'p90_RP4.min' => 'Pore Size Diameter Approximation By Katz And Thompson Correlation p90 must be higher or equal than :min.',

            'p90_ID1.min' => 'Gross Pay p90 must be higher or equal than :min.',
            'p90_ID2.min' => 'Total polymer pumped during Hydraulic Fracturing p90 must be higher or equal than :min.',
            'p90_ID3.min' => 'Total volume of water based fluids pumped into the well p90 must be higher or equal than :min.',
            'p90_ID4.min' => 'Mud Losses p90 must be higher or equal than :min.',

            'p90_GD1.min' => 'Percentage of Net Pay exihibiting Natural p90 must be higher or equal than :min.',
            'p90_GD2.min' => 'Drawdown p90 must be higher or equal than :min.',
            'p90_GD3.min' => 'Ratio of KH)matrix + fracture / KH)matrix p90 must be higher or equal than :min.',
            'p90_GD4.min' => 'Geomechanical damage expressed as fraction of base permeability at BHFP p90 must be higher or equal than :min.',

            'ms_scale_index_caco3.numeric' => 'Scale index of CaCO3 p90 must be numeric.',
            'ms_scale_index_baso4.numeric' => 'Scale index of BaSO4 p90 must be numeric.',
            'ms_scale_index_iron_scales.numeric' => 'Scale index of iron scales p90 must be numeric.',
            'ms_calcium_concentration.numeric' => 'Backflow [Ca] p90 must be numeric.',
            'ms_barium_concentration.numeric' => 'Backflow [Ba] p90 must be numeric.',

            'fb_aluminum_concentration.numeric' => '[Al] on Produced Water p90 must be numeric.',
            'fb_silicon_concentration.numeric' => '[Si] on produced water p90 must be numeric.',
            'fb_critical_radius_factor.numeric' => 'Critical Radius derived from maximum critical velocity, Vc p90 must be numeric.',
            'fb_mineralogic_factor.numeric' => 'Mineralogy Factor p90 must be numeric.',
            'fb_crushed_proppant_factor.numeric' => 'Mass of crushed proppant inside Hydraulic Fractures p90 must be numeric.',

            'os_cll_factor.numeric' => 'CII Factor: Colloidal Instability Index p90 must be numeric.',
            'os_volume_of_hcl.numeric' => 'Volume of HCL pumped into the formation p90 must be numeric.',
            'os_compositional_factor.numeric' => 'Cumulative Gas Produced p90 must be numeric.',
            'os_pressure_factor.numeric' => 'Number Of Days Below Saturation Pressure p90 must be numeric.',
            'os_high_impact_factor.numeric' => 'De Boer Criteria p90 must be numeric.',

            'rp_days_below_saturation_pressure.numeric' => 'Number Of Days Below Saturation Pressure p90 must be numeric.',
            'rp_delta_pressure_saturation.numeric' => 'Delta Pressure From Saturation Pressure p90 must be numeric.',
            'rp_water_intrusion.numeric' => 'Cumulative Water Produced p90 must be numeric.',
            'rp_high_impact_factor.numeric' => 'Pore Size Diameter Approximation By Katz And Thompson Correlation p90 must be numeric.',

            'id_gross_pay.numeric' => 'Gross Pay p90 must be numeric.',
            'id_polymer_damage_factor.numeric' => 'Total polymer pumped during Hydraulic Fracturing p90 must be numeric.',
            'id_total_volume_water.numeric' => 'Total volume of water based fluids pumped into the well p90 must be numeric.',
            'id_mud_damage_factor.numeric' => 'Mud Losses p90 must be numeric.',

            'gd_fraction_netpay.numeric' => 'Percentage of Net Pay exihibiting Natural p90 must be numeric.',
            'gd_drawdown.numeric' => 'Drawdown p90 must be numeric.',
            'gd_ratio_kh_fracture.numeric' => 'Ratio of KH)matrix + fracture / KH)matrix p90 must be numeric.',
            'gd_geomechanical_damage_fraction.numeric' => 'Geomechanical damage expressed as fraction of base permeability at BHFP p90 must be numeric.',
        ];
    }
}
