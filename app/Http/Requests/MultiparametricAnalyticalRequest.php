<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Carbon\Carbon;

class MultiparametricAnalyticalRequest extends Request
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
        if (request()->calculate == true) {
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

            if ($request->msAvailable) {
                $rulesSpecial = array();
                $implodedArray = implode(",", $request->msAvailable);

                if (in_array('1', $implodedArray)) {
                    $rulesSpecial['MS1'] = 'required|numeric';
                    $rulesSpecial['dateMS1'] = 'required|date_format:d/m/Y|before:' . Carbon::now()->addDays(1)->format('d/m/Y');
                    $rulesSpecial['MS1comment'] = 'string|max:100';
                    $rulesSpecial['p10_MS1'] = 'required|numeric|not_in:' . request()->p90_MS1;
                    $rulesSpecial['p90_MS1'] = 'required|numeric';
                    $rulesSpecial['ms_scale_index_caco3'] = 'numeric';
                }

                if (in_array('2', $implodedArray)) {
                    $rulesSpecial['MS2'] = 'required|numeric';
                    $rulesSpecial['dateMS2'] = 'required|date_format:d/m/Y|before:' . Carbon::now()->addDays(1)->format('d/m/Y');
                    $rulesSpecial['MS2comment'] = 'string|max:100';
                    $rulesSpecial['p10_MS2'] = 'required|numeric|not_in:' . request()->p90_MS2;
                    $rulesSpecial['p90_MS2'] = 'required|numeric';
                    $rulesSpecial['ms_scale_index_baso4'] = 'numeric';
                }

                if (in_array('3', $implodedArray)) {
                    $rulesSpecial['MS3'] = 'required|numeric';
                    $rulesSpecial['dateMS3'] = 'required|date_format:d/m/Y|before:' . Carbon::now()->addDays(1)->format('d/m/Y');
                    $rulesSpecial['MS3comment'] = 'string|max:100';
                    $rulesSpecial['p10_MS3'] = 'required|numeric|not_in:' . request()->p90_MS3;
                    $rulesSpecial['p90_MS3'] = 'required|numeric';
                    $rulesSpecial['ms_scale_index_iron_scales'] = 'numeric';
                }

                if (in_array('4', $implodedArray)) {
                    $rulesSpecial['MS4'] = 'required|numeric';
                    $rulesSpecial['dateMS4'] = 'required|date_format:d/m/Y|before:' . Carbon::now()->addDays(1)->format('d/m/Y');
                    $rulesSpecial['MS4comment'] = 'string|max:100';
                    $rulesSpecial['p10_MS4'] = 'required|numeric|not_in:' . request()->p90_MS4;
                    $rulesSpecial['p90_MS4'] = 'required|numeric';
                    $rulesSpecial['ms_calcium_concentration'] = 'numeric';
                }

                if (in_array('5', $implodedArray)) {
                    $rulesSpecial['MS5'] = 'required|numeric';
                    $rulesSpecial['dateMS5'] = 'required|date_format:d/m/Y|before:' . Carbon::now()->addDays(1)->format('d/m/Y');
                    $rulesSpecial['MS5comment'] = 'string|max:100';
                    $rulesSpecial['p10_MS5'] = 'required|numeric|not_in:' . request()->p90_MS5;
                    $rulesSpecial['p90_MS5'] = 'required|numeric';
                    $rulesSpecial['ms_barium_concentration'] = 'numeric';
                }

                $rules = array_merge($rules, $rulesSpecial);
            }

            if ($request->fbAvailable) {
                $rulesSpecial = array();
                $implodedArray = implode(",", $request->fbAvailable);

                if (in_array('1', $implodedArray)) {
                    $rulesSpecial['FB1'] = 'required|numeric';
                    $rulesSpecial['dateFB1'] = 'required|date_format:d/m/Y|before:' . Carbon::now()->addDays(1)->format('d/m/Y');
                    $rulesSpecial['FB1comment'] = 'string|max:100';
                    $rulesSpecial['p10_FB1'] = 'required|numeric|not_in:' . request()->p90_FB1;
                    $rulesSpecial['p90_FB1'] = 'required|numeric';
                    $rulesSpecial['fb_aluminum_concentration'] = 'numeric';
                }

                if (in_array('2', $implodedArray)) {
                    $rulesSpecial['FB2'] = 'required|numeric';
                    $rulesSpecial['dateFB2'] = 'required|date_format:d/m/Y|before:' . Carbon::now()->addDays(1)->format('d/m/Y');
                    $rulesSpecial['FB2comment'] = 'string|max:100';
                    $rulesSpecial['p10_FB2'] = 'required|numeric|not_in:' . request()->p90_FB2;
                    $rulesSpecial['p90_FB2'] = 'required|numeric';
                    $rulesSpecial['fb_silicon_concentration'] = 'numeric';
                }

                if (in_array('3', $implodedArray)) {
                    $rulesSpecial['FB3'] = 'required|numeric';
                    $rulesSpecial['dateFB3'] = 'required|date_format:d/m/Y|before:' . Carbon::now()->addDays(1)->format('d/m/Y');
                    $rulesSpecial['FB3comment'] = 'string|max:100';
                    $rulesSpecial['p10_FB3'] = 'required|numeric|not_in:' . request()->p90_FB3;
                    $rulesSpecial['p90_FB3'] = 'required|numeric';
                    $rulesSpecial['fb_critical_radius_factor'] = 'numeric';
                }

                if (in_array('4', $implodedArray)) {
                    $rulesSpecial['FB4'] = 'required|numeric';
                    $rulesSpecial['dateFB4'] = 'required|date_format:d/m/Y|before:' . Carbon::now()->addDays(1)->format('d/m/Y');
                    $rulesSpecial['FB4comment'] = 'string|max:100';
                    $rulesSpecial['p10_FB4'] = 'required|numeric|not_in:' . request()->p90_FB4;
                    $rulesSpecial['p90_FB4'] = 'required|numeric';
                    $rulesSpecial['fb_mineralogic_factor'] = 'numeric';
                }

                if (in_array('5', $implodedArray)) {
                    $rulesSpecial['FB5'] = 'required|numeric';
                    $rulesSpecial['dateFB5'] = 'required|date_format:d/m/Y|before:' . Carbon::now()->addDays(1)->format('d/m/Y');
                    $rulesSpecial['FB5comment'] = 'string|max:100';
                    $rulesSpecial['p10_FB5'] = 'required|numeric|not_in:' . request()->p90_FB5;
                    $rulesSpecial['p90_FB5'] = 'required|numeric';
                    $rulesSpecial['fb_crushed_proppant_factor'] = 'numeric';
                }

                $rules = array_merge($rules, $rulesSpecial);
            }

            if ($request->osAvailable) {
                $rulesSpecial = array();
                $implodedArray = implode(",", $request->osAvailable);

                if (in_array('1', $implodedArray)) {
                    $rulesSpecial['OS1'] = 'required|numeric';
                    $rulesSpecial['dateOS1'] = 'required|date_format:d/m/Y|before:' . Carbon::now()->addDays(1)->format('d/m/Y');
                    $rulesSpecial['OS1comment'] = 'string|max:100';
                    $rulesSpecial['p10_OS1'] = 'required|numeric|not_in:' . request()->p90_OS1;
                    $rulesSpecial['p90_OS1'] = 'required|numeric';
                    $rulesSpecial['os_cll_factor'] = 'numeric';
                }

                if (in_array('2', $implodedArray)) {
                    $rulesSpecial['OS2'] = 'required|numeric';
                    $rulesSpecial['dateOS2'] = 'required|date_format:d/m/Y|before:' . Carbon::now()->addDays(1)->format('d/m/Y');
                    $rulesSpecial['OS2comment'] = 'string|max:100';
                    $rulesSpecial['p10_OS2'] = 'required|numeric|not_in:' . request()->p90_OS2;
                    $rulesSpecial['p90_OS2'] = 'required|numeric';
                    $rulesSpecial['os_volume_of_hcl'] = 'numeric';
                }

                if (in_array('3', $implodedArray)) {
                    $rulesSpecial['OS3'] = 'required|numeric';
                    $rulesSpecial['dateOS3'] = 'required|date_format:d/m/Y|before:' . Carbon::now()->addDays(1)->format('d/m/Y');
                    $rulesSpecial['OS3comment'] = 'string|max:100';
                    $rulesSpecial['p10_OS3'] = 'required|numeric|not_in:' . request()->p90_OS3;
                    $rulesSpecial['p90_OS3'] = 'required|numeric';
                    $rulesSpecial['os_compositional_factor'] = 'numeric';
                }

                if (in_array('4', $implodedArray)) {
                    $rulesSpecial['OS4'] = 'required|numeric';
                    $rulesSpecial['dateOS4'] = 'required|date_format:d/m/Y|before:' . Carbon::now()->addDays(1)->format('d/m/Y');
                    $rulesSpecial['OS4comment'] = 'string|max:100';
                    $rulesSpecial['p10_OS4'] = 'required|numeric|not_in:' . request()->p90_OS4;
                    $rulesSpecial['p90_OS4'] = 'required|numeric';
                    $rulesSpecial['os_pressure_factor'] = 'numeric';
                }

                if (in_array('5', $implodedArray)) {
                    $rulesSpecial['OS5'] = 'required|numeric';
                    $rulesSpecial['dateOS5'] = 'required|date_format:d/m/Y|before:' . Carbon::now()->addDays(1)->format('d/m/Y');
                    $rulesSpecial['OS5comment'] = 'string|max:100';
                    $rulesSpecial['p10_OS5'] = 'required|numeric|not_in:' . request()->p90_OS5;
                    $rulesSpecial['p90_OS5'] = 'required|numeric';
                    $rulesSpecial['os_high_impact_factor'] = 'numeric';
                }

                $rules = array_merge($rules, $rulesSpecial);
            }

            if ($request->rpAvailable) {
                $rulesSpecial = array();
                $implodedArray = implode(",", $request->rpAvailable);

                if (in_array('1', $implodedArray)) {
                    $rulesSpecial['RP1'] = 'required|numeric';
                    $rulesSpecial['dateRP1'] = 'required|date_format:d/m/Y|before:' . Carbon::now()->addDays(1)->format('d/m/Y');
                    $rulesSpecial['RP1comment'] = 'string|max:100';
                    $rulesSpecial['p10_RP1'] = 'required|numeric|not_in:' . request()->p90_RP1;
                    $rulesSpecial['p90_RP1'] = 'required|numeric';
                    $rulesSpecial['rp_days_below_saturation_pressure'] = 'numeric';
                }

                if (in_array('2', $implodedArray)) {
                    $rulesSpecial['RP2'] = 'required|numeric';
                    $rulesSpecial['dateRP2'] = 'required|date_format:d/m/Y|before:' . Carbon::now()->addDays(1)->format('d/m/Y');
                    $rulesSpecial['RP2comment'] = 'string|max:100';
                    $rulesSpecial['p10_RP2'] = 'required|numeric|not_in:' . request()->p90_RP2;
                    $rulesSpecial['p90_RP2'] = 'required|numeric';
                    $rulesSpecial['rp_delta_pressure_saturation'] = 'numeric';
                }

                if (in_array('3', $implodedArray)) {
                    $rulesSpecial['RP3'] = 'required|numeric';
                    $rulesSpecial['dateRP3'] = 'required|date_format:d/m/Y|before:' . Carbon::now()->addDays(1)->format('d/m/Y');
                    $rulesSpecial['RP3comment'] = 'string|max:100';
                    $rulesSpecial['p10_RP3'] = 'required|numeric|not_in:' . request()->p90_RP3;
                    $rulesSpecial['p90_RP3'] = 'required|numeric';
                    $rulesSpecial['rp_water_intrusion'] = 'numeric';
                }

                if (in_array('4', $implodedArray)) {
                    $rulesSpecial['RP4'] = 'required|numeric';
                    $rulesSpecial['dateRP4'] = 'required|date_format:d/m/Y|before:' . Carbon::now()->addDays(1)->format('d/m/Y');
                    $rulesSpecial['RP4comment'] = 'string|max:100';
                    $rulesSpecial['p10_RP4'] = 'required|numeric|not_in:' . request()->p90_RP4;
                    $rulesSpecial['p90_RP4'] = 'required|numeric';
                    $rulesSpecial['rp_high_impact_factor'] = 'numeric';
                }

                $rules = array_merge($rules, $rulesSpecial);
            }

            if ($request->idAvailable) {
                $rulesSpecial = array();
                $implodedArray = implode(",", $request->idAvailable);

                if (in_array('1', $implodedArray)) {
                    $rulesSpecial['ID1'] = 'required|numeric';
                    $rulesSpecial['dateID1'] = 'required|date_format:d/m/Y|before:' . Carbon::now()->addDays(1)->format('d/m/Y');
                    $rulesSpecial['ID1comment'] = 'string|max:100';
                    $rulesSpecial['p10_ID1'] = 'required|numeric|not_in:' . request()->p90_ID1;
                    $rulesSpecial['p90_ID1'] = 'required|numeric';
                    $rulesSpecial['id_gross_pay'] = 'numeric';
                }

                if (in_array('2', $implodedArray)) {
                    $rulesSpecial['ID2'] = 'required|numeric';
                    $rulesSpecial['dateID2'] = 'required|date_format:d/m/Y|before:' . Carbon::now()->addDays(1)->format('d/m/Y');
                    $rulesSpecial['ID2comment'] = 'string|max:100';
                    $rulesSpecial['p10_ID2'] = 'required|numeric|not_in:' . request()->p90_ID2;
                    $rulesSpecial['p90_ID2'] = 'required|numeric';
                    $rulesSpecial['id_polymer_damage_factor'] = 'numeric';
                }

                if (in_array('3', $implodedArray)) {
                    $rulesSpecial['ID3'] = 'required|numeric';
                    $rulesSpecial['dateID3'] = 'required|date_format:d/m/Y|before:' . Carbon::now()->addDays(1)->format('d/m/Y');
                    $rulesSpecial['ID3comment'] = 'string|max:100';
                    $rulesSpecial['p10_ID3'] = 'required|numeric|not_in:' . request()->p90_ID3;
                    $rulesSpecial['p90_ID3'] = 'required|numeric';
                    $rulesSpecial['id_total_volume_water'] = 'numeric';
                }

                if (in_array('4', $implodedArray)) {
                    $rulesSpecial['ID4'] = 'required|numeric';
                    $rulesSpecial['dateID4'] = 'required|date_format:d/m/Y|before:' . Carbon::now()->addDays(1)->format('d/m/Y');
                    $rulesSpecial['ID4comment'] = 'string|max:100';
                    $rulesSpecial['p10_ID4'] = 'required|numeric|not_in:' . request()->p90_ID4;
                    $rulesSpecial['p90_ID4'] = 'required|numeric';
                    $rulesSpecial['id_mud_damage_factor'] = 'numeric';
                }

                $rules = array_merge($rules, $rulesSpecial);
            }

            if ($request->gdAvailable) {
                $rulesSpecial = array();
                $implodedArray = implode(",", $request->gdAvailable);

                if (in_array('1', $implodedArray)) {
                    $rulesSpecial['GD1'] = 'required|numeric';
                    $rulesSpecial['dateGD1'] = 'required|date_format:d/m/Y|before:' . Carbon::now()->addDays(1)->format('d/m/Y');
                    $rulesSpecial['GD1comment'] = 'string|max:100';
                    $rulesSpecial['p10_GD1'] = 'required|numeric|not_in:' . request()->p90_GD1;
                    $rulesSpecial['p90_GD1'] = 'required|numeric';
                    $rulesSpecial['gd_fraction_netpay'] = 'numeric';
                }

                if (in_array('2', $implodedArray)) {
                    $rulesSpecial['GD2'] = 'required|numeric';
                    $rulesSpecial['dateGD2'] = 'required|date_format:d/m/Y|before:' . Carbon::now()->addDays(1)->format('d/m/Y');
                    $rulesSpecial['GD2comment'] = 'string|max:100';
                    $rulesSpecial['p10_GD2'] = 'required|numeric|not_in:' . request()->p90_GD2;
                    $rulesSpecial['p90_GD2'] = 'required|numeric';
                    $rulesSpecial['gd_drawdown'] = 'numeric';
                }

                if (in_array('3', $implodedArray)) {
                    $rulesSpecial['GD3'] = 'required|numeric';
                    $rulesSpecial['dateGD3'] = 'required|date_format:d/m/Y|before:' . Carbon::now()->addDays(1)->format('d/m/Y');
                    $rulesSpecial['GD3comment'] = 'string|max:100';
                    $rulesSpecial['p10_GD3'] = 'required|numeric|not_in:' . request()->p90_GD3;
                    $rulesSpecial['p90_GD3'] = 'required|numeric';
                    $rulesSpecial['gd_ratio_kh_fracture'] = 'numeric';
                }

                if (in_array('4', $implodedArray)) {
                    $rulesSpecial['GD4'] = 'required|numeric';
                    $rulesSpecial['dateGD4'] = 'required|date_format:d/m/Y|before:' . Carbon::now()->addDays(1)->format('d/m/Y');
                    $rulesSpecial['GD4comment'] = 'string|max:100';
                    $rulesSpecial['p10_GD4'] = 'required|numeric|not_in:' . request()->p90_GD4;
                    $rulesSpecial['p90_GD4'] = 'required|numeric';
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

            'MS1.required_with' => 'Scale index of CaCO3 value is required when date is present.',
            'MS2.required_with' => 'Scale index of BaSO4 value is required when date is present.',
            'MS3.required_with' => 'Scale index of iron scales value is required when date is present.',
            'MS4.required_with' => 'Backflow [Ca] value is required when date is present.',
            'MS5.required_with' => 'Backflow [Ba] value is required when date is present.',

            'FB1.required_with' => '[Al] on Produced Water value is required when date is present.',
            'FB2.required_with' => '[Si] on produced water value is required when date is present.',
            'FB3.required_with' => 'Critical Radius derived from maximum critical velocity, Vc value is required when date is present.',
            'FB4.required_with' => 'Mineralogy Factor value is required when date is present.',
            'FB5.required_with' => 'Mass of crushed proppant inside Hydraulic Fractures value is required when date is present.',

            'OS1.required_with' => 'CII Factor: Colloidal Instability Index value is required when date is present.',
            'OS2.required_with' => 'Volume of HCL pumped into the formation value is required when date is present.',
            'OS3.required_with' => 'Cumulative Gas Produced value is required when date is present.',
            'OS4.required_with' => 'Number Of Days Below Saturation Pressure value is required when date is present.',
            'OS5.required_with' => 'De Boer Criteria value is required when date is present.',

            'RP1.required_with' => 'Number of days below Saturation Pressure value is required when date is present.',
            'RP2.required_with' => 'Delta Pressure From Saturation Pressure value is required when date is present.',
            'RP3.required_with' => 'Cumulative Water Produced value is required when date is present.',
            'RP4.required_with' => 'Pore Size Diameter Approximation By Katz And Thompson Correlation value is required when date is present.',

            'ID1.required_with' => 'Gross Pay is required when date is present.',
            'ID2.required_with' => 'Total polymer pumped during Hydraulic Fracturing value is required when date is present.',
            'ID3.required_with' => 'Total volume of water based fluids pumped into the well value is required when date is present.',
            'ID4.required_with' => 'Mud Losses value is required when date is present.',

            'GD1.required_with' => 'Percentage of Net Pay exihibiting Natural value is required when date is present.',
            'GD2.required_with' => 'Drawdown value is required when date is present.',
            'GD3.required_with' => 'Ratio of KH)matrix + fracture / KH)matrix value is required when date is present.',
            'GD4.required_with' => 'Geomechanical damage expressed as fraction of base permeability at BHFP value is required when date is present.',

            'dateMS1.required_with' => 'Date is required when Scale index of CaCO3 value is present.',
            'dateMS2.required_with' => 'Date is required when Scale index of BaSO4 value is present.',
            'dateMS3.required_with' => 'Date is required when Scale index of iron scales value is present.',
            'dateMS4.required_with' => 'Date is required when Backflow [Ca] value is present.',
            'dateMS5.required_with' => 'Date is required when Backflow [Ba] value is present.',

            'dateFB1.required_with' => 'Date is required when [Al] on Produced Water value is present.',
            'dateFB2.required_with' => 'Date is required when [Si] on produced water value is present.',
            'dateFB3.required_with' => 'Date is required when Critical Radius derived from maximum critical velocity, Vc value is present.',
            'dateFB4.required_with' => 'Date is required when Mineralogy Factor value is present.',
            'dateFB5.required_with' => 'Date is required when Mass of crushed proppant inside Hydraulic Fractures value is present.',

            'dateOS1.required_with' => 'Date is required when CII Factor: Colloidal Instability Index value is present.',
            'dateOS2.required_with' => 'Date is required when Volume of HCL pumped into the formation value is present.',
            'dateOS3.required_with' => 'Date is required when Cumulative Gas Produced value is present.',
            'dateOS4.required_with' => 'Date is required when Number Of Days Below Saturation Pressure value is present.',
            'dateOS5.required_with' => 'Date is required when De Boer Criteria value is present.',

            'dateRP1.required_with' => 'Date is required when Number of days below Saturation Pressure value is present.',
            'dateRP2.required_with' => 'Date is required when Delta Pressure From Saturation Pressure value is present.',
            'dateRP3.required_with' => 'Date is required when Cumulative Water Produced value is present.',
            'dateRP4.required_with' => 'Date is required when Pore Size Diameter Approximation By Katz And Thompson Correlation value is present.',

            'dateID1.required_with' => 'Date is required when Gross Pay value is present.',
            'dateID2.required_with' => 'Date is required when Total polymer pumped during Hydraulic Fracturing value is present.',
            'dateID3.required_with' => 'Date is required when Total volume of water based fluids pumped into the well value is present.',
            'dateID4.required_with' => 'Date is required when Mud Losses value is present.',

            'dateGD1.required_with' => 'Date is required when Percentage of Net Pay exihibiting Natural value is present.',
            'dateGD2.required_with' => 'Date is required when Drawdown value is present.',
            'dateGD3.required_with' => 'Date is required when Ratio of KH)matrix + fracture / KH)matrix value is present.',
            'dateGD4.required_with' => 'Date is required when Geomechanical damage expressed as fraction of base permeability at BHFP value is present.',

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

            'p10_MS1.not_in' => "Scale index of CaCO3 p10 and p90 can't have the same value.",
            'p10_MS2.not_in' => "Scale index of BaSO4 p10 and p90 can't have the same value.",
            'p10_MS3.not_in' => "Scale index of iron scales p10 and p90 can't have the same value.",
            'p10_MS4.not_in' => "Backflow [Ca] p10 and p90 can't have the same value.",
            'p10_MS5.not_in' => "Backflow [Ba] p10 and p90 can't have the same value.",

            'p10_FB1.not_in' => "[Al] on Produced Water p10 and p90 can't have the same value.",
            'p10_FB2.not_in' => "[Si] on produced water p10 and p90 can't have the same value.",
            'p10_FB3.not_in' => "Critical Radius derived from maximum critical velocity, Vc p10 and p90 can't have the same value.",
            'p10_FB4.not_in' => "Mineralogy Factor p10 and p90 can't have the same value.",
            'p10_FB5.not_in' => "Mass of crushed proppant inside Hydraulic Fractures p10 and p90 can't have the same value.",

            'p10_OS1.not_in' => "CII Factor: Colloidal Instability Index p10 and p90 can't have the same value.",
            'p10_OS2.not_in' => "Volume of HCL pumped into the formation p10 and p90 can't have the same value.",
            'p10_OS3.not_in' => "Cumulative Gas Produced p10 and p90 can't have the same value.",
            'p10_OS4.not_in' => "Number Of Days Below Saturation Pressure p10 and p90 can't have the same value.",
            'p10_OS5.not_in' => "De Boer Criteria p10 and p90 can't have the same value.",

            'p10_RP1.not_in' => "Number Of Days Below Saturation Pressure p10 and p90 can't have the same value.",
            'p10_RP2.not_in' => "Delta Pressure From Saturation Pressure p10 and p90 can't have the same value.",
            'p10_RP3.not_in' => "Cumulative Water Produced p10 and p90 can't have the same value.",
            'p10_RP4.not_in' => "Pore Size Diameter Approximation By Katz And Thompson Correlation p10 and p90 can't have the same value.",

            'p10_ID1.not_in' => "Gross Pay p10 and p90 can't have the same value.",
            'p10_ID2.not_in' => "Total polymer pumped during Hydraulic Fracturing p10 and p90 can't have the same value.",
            'p10_ID3.not_in' => "Total volume of water based fluids pumped into the well p10 and p90 can't have the same value.",
            'p10_ID4.not_in' => "Mud Losses p10 and p90 can't have the same value.",

            'p10_GD1.not_in' => "Percentage of Net Pay exihibiting Natural p10 and p90 can't have the same value.",
            'p10_GD2.not_in' => "Drawdown p10 and p90 can't have the same value.",
            'p10_GD3.not_in' => "Ratio of KH)matrix + fracture / KH)matrix p10 and p90 can't have the same value.",
            'p10_GD4.not_in' => "Geomechanical damage expressed as fraction of base permeability at BHFP p10 and p90 can't have the same value.",

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

            'FB1.between' => '[Al] on Produced Water must be between 0 and 8.55.',
            'FB2.between' => '[Si] on produced water must be between 4 and 102.',
            'FB3.between' => 'Critical Radius derived from maximum critical velocity, Vc must be between 0.8 and 26.',
            'FB4.between' => 'Mineralogy Factor must be between 0.3 and 1.',
            'FB5.between' => 'Mass of crushed proppant inside Hydraulic Fractures must be between 0.74 and 3.',

            'OS1.between' => 'CII Factor: Colloidal Instability Index must be between 0.592 and 13.557.',

            'ID1.between' => 'Gross Pay must be between 0.774 and 24.95.',
            'ID2.between' => 'Total polymer pumped during Hydraulic Fracturing must be between 0 and 9453.',
            'ID3.between' => 'Total volume of water based fluids pumped into the well must be between 100 and 5217.',
            'ID4.between' => 'Mud Losses must be between 4 and 14228.',

            'GD1.between' => 'Percentage of Net Pay exihibiting Natural must be between 0 and 1.',
            'GD2.between' => 'Drawdown must be between 1 and 6000.',
            'GD3.between' => 'Ratio of KH)matrix + fracture / KH)matrix must be between 0 and 100.',
            'GD4.between' => 'Geomechanical damage expressed as fraction of base permeability at BHFP must be between 0 and 1.',
        ];
    }
}
