<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Carbon\Carbon;

class measurementRequest extends Request
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
        return [
            'basin' => 'required|exists:cuencas,id',
            'field' => 'required|exists:campos,id',
            'well' => 'required|exists:pozos,id',

            'MS1' => 'numeric|required_with:dateMS1|min:0',
            'MS2' => 'numeric|required_with:dateMS2|min:0',
            'MS3' => 'numeric|required_with:dateMS3|min:0',
            'MS4' => 'numeric|required_with:dateMS4|between:0,1000000',
            'MS5' => 'numeric|required_with:dateMS5|between:0,1000000',
            'FB1' => 'numeric|required_with:dateFB1|between:0,1000000',
            'FB2' => 'numeric|required_with:dateFB2|between:0,1000000',
            'FB3' => 'numeric|required_with:dateFB3|between:0,100',
            'FB4' => 'numeric|required_with:dateFB4|between:0,1',
            'FB5' => 'numeric|required_with:dateFB5|min:0',
            'OS1' => 'numeric|required_with:dateOS1|between:0,14',
            'OS2' => 'numeric|required_with:dateOS2|min:0',
            'OS3' => 'numeric|required_with:dateOS3|min:0',
            'OS4' => 'numeric|required_with:dateOS4|between:0,20000',
            'OS5' => 'numeric|required_with:dateOS5',
            'RP1' => 'numeric|required_with:dateRP1|between:0,20000',
            'RP2' => 'numeric|required_with:dateRP2|between:-15000,15000',
            'RP3' => 'numeric|required_with:dateRP3|min:0|not_in:0',
            'RP4' => 'numeric|required_with:dateRP4|min:0|not_in:0',
            'ID1' => 'numeric|required_with:dateID1|between:0,10000',
            'ID2' => 'numeric|required_with:dateID2|min:0',
            'ID3' => 'numeric|required_with:dateID3|between:0,1000000',
            'ID4' => 'numeric|required_with:dateID4|between:0,10000',
            'GD1' => 'numeric|required_with:dateGD1|between:0,1',
            'GD2' => 'numeric|required_with:dateGD2|between:0,10000',
            'GD3' => 'numeric|required_with:dateGD3|between:0,1',
            'GD4' => 'numeric|required_with:dateGD4|between:0,1',

            'dateMS1' => 'required_with:MS1|date_format:d/m/Y|before:' . Carbon::now()->addDays(1)->format('d/m/Y'),
            'dateMS2' => 'required_with:MS2|date_format:d/m/Y|before:' . Carbon::now()->addDays(1)->format('d/m/Y'),
            'dateMS3' => 'required_with:MS3|date_format:d/m/Y|before:' . Carbon::now()->addDays(1)->format('d/m/Y'),
            'dateMS4' => 'required_with:MS4|date_format:d/m/Y|before:' . Carbon::now()->addDays(1)->format('d/m/Y'),
            'dateMS5' => 'required_with:MS5|date_format:d/m/Y|before:' . Carbon::now()->addDays(1)->format('d/m/Y'),
            'dateFB1' => 'required_with:FB1|date_format:d/m/Y|before:' . Carbon::now()->addDays(1)->format('d/m/Y'),
            'dateFB2' => 'required_with:FB2|date_format:d/m/Y|before:' . Carbon::now()->addDays(1)->format('d/m/Y'),
            'dateFB3' => 'required_with:FB3|date_format:d/m/Y|before:' . Carbon::now()->addDays(1)->format('d/m/Y'),
            'dateFB4' => 'required_with:FB4|date_format:d/m/Y|before:' . Carbon::now()->addDays(1)->format('d/m/Y'),
            'dateFB5' => 'required_with:FB5|date_format:d/m/Y|before:' . Carbon::now()->addDays(1)->format('d/m/Y'),
            'dateOS1' => 'required_with:OS1|date_format:d/m/Y|before:' . Carbon::now()->addDays(1)->format('d/m/Y'),
            'dateOS2' => 'required_with:OS2|date_format:d/m/Y|before:' . Carbon::now()->addDays(1)->format('d/m/Y'),
            'dateOS3' => 'required_with:OS3|date_format:d/m/Y|before:' . Carbon::now()->addDays(1)->format('d/m/Y'),
            'dateOS4' => 'required_with:OS4|date_format:d/m/Y|before:' . Carbon::now()->addDays(1)->format('d/m/Y'),
            'dateOS5' => 'required_with:OS5|date_format:d/m/Y|before:' . Carbon::now()->addDays(1)->format('d/m/Y'),
            'dateRP1' => 'required_with:RP1|date_format:d/m/Y|before:' . Carbon::now()->addDays(1)->format('d/m/Y'),
            'dateRP2' => 'required_with:RP2|date_format:d/m/Y|before:' . Carbon::now()->addDays(1)->format('d/m/Y'),
            'dateRP3' => 'required_with:RP3|date_format:d/m/Y|before:' . Carbon::now()->addDays(1)->format('d/m/Y'),
            'dateRP4' => 'required_with:RP4|date_format:d/m/Y|before:' . Carbon::now()->addDays(1)->format('d/m/Y'),
            'dateID1' => 'required_with:ID1|date_format:d/m/Y|before:' . Carbon::now()->addDays(1)->format('d/m/Y'),
            'dateID2' => 'required_with:ID2|date_format:d/m/Y|before:' . Carbon::now()->addDays(1)->format('d/m/Y'),
            'dateID3' => 'required_with:ID3|date_format:d/m/Y|before:' . Carbon::now()->addDays(1)->format('d/m/Y'),
            'dateID4' => 'required_with:ID4|date_format:d/m/Y|before:' . Carbon::now()->addDays(1)->format('d/m/Y'),
            'dateGD1' => 'required_with:GD1|date_format:d/m/Y|before:' . Carbon::now()->addDays(1)->format('d/m/Y'),
            'dateGD2' => 'required_with:GD2|date_format:d/m/Y|before:' . Carbon::now()->addDays(1)->format('d/m/Y'),
            'dateGD3' => 'required_with:GD3|date_format:d/m/Y|before:' . Carbon::now()->addDays(1)->format('d/m/Y'),
            'dateGD4' => 'required_with:GD4|date_format:d/m/Y|before:' . Carbon::now()->addDays(1)->format('d/m/Y'),

            'MS1comment' => 'string|max:100',
            'MS2comment' => 'string|max:100',
            'MS3comment' => 'string|max:100',
            'MS4comment' => 'string|max:100',
            'MS5comment' => 'string|max:100',
            'FB1comment' => 'string|max:100',
            'FB2comment' => 'string|max:100',
            'FB3comment' => 'string|max:100',
            'FB4comment' => 'string|max:100',
            'FB5comment' => 'string|max:100',
            'OS1comment' => 'string|max:100',
            'OS2comment' => 'string|max:100',
            'OS3comment' => 'string|max:100',
            'OS4comment' => 'string|max:100',
            'OS5comment' => 'string|max:100',
            'RP1comment' => 'string|max:100',
            'RP2comment' => 'string|max:100',
            'RP3comment' => 'string|max:100',
            'RP4comment' => 'string|max:100',
            'ID1comment' => 'string|max:100',
            'ID2comment' => 'string|max:100',
            'ID3comment' => 'string|max:100',
            'ID4comment' => 'string|max:100',
            'GD1comment' => 'string|max:100',
            'GD2comment' => 'string|max:100',
            'GD3comment' => 'string|max:100',
            'GD4comment' => 'string|max:100',
        ];
    }

    public function messages()
    {
        return [
            'basin.required' => 'Basin is required.',
            'field.required' => 'Field is required.',
            'well.required' => 'Well is required.',

            'basin.exists' => "This Basin doesn't exist.",
            'field.exists' => "This Field doesn't exist.",
            'well.exists' => "This Well doesn't exist.",

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
