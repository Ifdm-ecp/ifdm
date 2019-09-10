<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

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
            'MS1' => 'numeric|required_with:dateMS1',
            'MS2' => 'numeric|required_with:dateMS2',
            'MS3' => 'numeric|required_with:dateMS3',
            'MS4' => 'numeric|required_with:dateMS4',
            'MS5' => 'numeric|required_with:dateMS5',
            'FB1' => 'numeric|required_with:dateFB1',
            'FB2' => 'numeric|required_with:dateFB2',
            'FB3' => 'numeric|required_with:dateFB3',
            'FB4' => 'numeric|required_with:dateFB4',
            'FB5' => 'numeric|required_with:dateFB5',
            'OS1' => 'numeric|required_with:dateOS1',
            'OS2' => 'numeric|required_with:dateOS2',
            'OS3' => 'numeric|required_with:dateOS3',
            'OS4' => 'numeric|required_with:dateOS4',
            'RP1' => 'numeric|required_with:dateRP1',
            'RP2' => 'numeric|required_with:dateRP2',
            'RP3' => 'numeric|required_with:dateRP3',
            'RP4' => 'numeric|required_with:dateRP4',
            'ID1' => 'numeric|required_with:dateID1',
            'ID2' => 'numeric|required_with:dateID2',
            'ID3' => 'numeric|required_with:dateID3',
            'ID4' => 'numeric|required_with:dateID4',
            'GD1' => 'numeric|required_with:dateGD1',
            'GD2' => 'numeric|required_with:dateGD2',
            'GD3' => 'numeric|required_with:dateGD3',
            'GD4' => 'numeric|required_with:dateGD4',

            'dateMS1' => 'required_with:MS1',
            'dateMS2' => 'required_with:MS2',
            'dateMS3' => 'required_with:MS3',
            'dateMS4' => 'required_with:MS4',
            'dateMS5' => 'required_with:MS5',
            'dateFB1' => 'required_with:FB1',
            'dateFB2' => 'required_with:FB2',
            'dateFB3' => 'required_with:FB3',
            'dateFB4' => 'required_with:FB4',
            'dateFB5' => 'required_with:FB5',
            'dateOS1' => 'required_with:OS1',
            'dateOS2' => 'required_with:OS2',
            'dateOS3' => 'required_with:OS3',
            'dateOS4' => 'required_with:OS4',
            'dateRP1' => 'required_with:RP1',
            'dateRP2' => 'required_with:RP2',
            'dateRP3' => 'required_with:RP3',
            'dateRP4' => 'required_with:RP4',
            'dateID1' => 'required_with:ID1',
            'dateID2' => 'required_with:ID2',
            'dateID3' => 'required_with:ID3',
            'dateID4' => 'required_with:ID4',
            'dateGD1' => 'required_with:GD1',
            'dateGD2' => 'required_with:GD2',
            'dateGD3' => 'required_with:GD3',
            'dateGD4' => 'required_with:GD4',

            'well' => 'required',

        ];
    }


    public function messages()
    {
        return [
        'MS1.numeric' => 'Scale index of CaCO3 must be numeric.',
        'MS2.numeric' => 'Scale index of BaSO4 must be numeric.',
        'MS3.numeric' => 'Scale index of iron scales must be numeric.',
        'MS4.numeric' => '[Ca] must be numeric.',
        'MS5.numeric' => '[Ba] must be numeric.',

        'FB1.numeric' => '[Al] must be numeric.',
        'FB2.numeric' => '[Si] must be numeric.',
        'FB3.numeric' => 'Critical Radius factor Rc must be numeric.',
        'FB4.numeric' => 'Mineralogic factor must be numeric.',
        'FB5.numeric' => 'Crushed proppant factor must be numeric.',

        'OS1.numeric' => 'Cll factor Index must be numeric.',
        'OS2.numeric' => 'Compositional factor must be numeric.',
        'OS3.numeric' => 'Pressure factor must be numeric.',
        'OS4.numeric' => 'High Impact Factor must be numeric.',

        'RP1.numeric' => 'Number of days below Saturation Pressure must be numeric.',
        'RP2.numeric' => 'Delta pressure from saturation pressure must be numeric.',
        'RP3.numeric' => 'Water Intrusion must be numeric.',
        'RP4.numeric' => 'High impact factor and Thompson correlation must be numeric.',

        'ID1.numeric' => 'Invasion radius must be numeric.',
        'ID2.numeric' => 'Polymer damage factor must be numeric.',
        'ID3.numeric' => 'Induced skin must be numeric.',
        'ID4.numeric' => 'Mud damage factor must be numeric.',

        'GD1.numeric' => 'Natural fractures index must be numeric.',
        'GD2.numeric' => 'Drawdown must be numeric.',
        'GD3.numeric' => 'Ratio of KH + fracture / KH must be numeric.',
        'GD4.numeric' => 'Geomechanical damage expressed as fraction of base permeability at BHFP must be numeric.',





        'MS1.required_with' => 'Scale index of CaCO3 is required when date is present.',
        'MS2.required_with' => 'Scale index of BaSO4 is required when date is present.',
        'MS3.required_with' => 'Scale index of iron scales is required when date is present.',
        'MS4.required_with' => '[Ca] is required when date is present.',
        'MS5.required_with' => '[Ba] is required when date is present.',

        'FB1.required_with' => '[Al] is required when date is present.',
        'FB2.required_with' => '[Si] is required when date is present.',
        'FB3.required_with' => 'Critical Radius factor Rc is required when date is present.',
        'FB4.required_with' => 'Mineralogic factor is required when date is present.',
        'FB5.required_with' => 'Crushed proppant factor is required when date is present.',

        'OS1.required_with' => 'Cll factor Index is required when date is present.',
        'OS2.required_with' => 'Compositional factor is required when date is present.',
        'OS3.required_with' => 'Pressure factor is required when date is present.',
        'OS4.required_with' => 'High Impact Factor is required when date is present.',

        'RP1.required_with' => 'Number of days below Saturation Pressure is required when date is present.',
        'RP2.required_with' => 'Delta pressure from saturation pressure is required when date is present.',
        'RP3.required_with' => 'Water Intrusion is required when date is present.',
        'RP4.required_with' => 'High impact factor and Thompson correlation is required when date is present.',

        'ID1.required_with' => 'Invasion radius is required when date is present.',
        'ID2.required_with' => 'Polymer damage factor is required when date is present.',
        'ID3.required_with' => 'Induced skin is required when date is present.',
        'ID4.required_with' => 'Mud damage factor is required when date is present.',

        'GD1.required_with' => 'Natural fractures index is required when date is present.',
        'GD2.required_with' => 'Drawdown is required when date is present.',
        'GD3.required_with' => 'Ratio of KH + fracture / KH is required when date is present.',
        'GD4.required_with' => 'Geomechanical damage expressed as fraction of base permeability at BHFP is required when date is present.',

        'well.required' => 'Well is required.',





        'dateMS1.required_with' => 'Date is required when Scale index of CaCO3 is present.',
        'dateMS2.required_with' => 'Date is required when Scale index of BaSO4 is present.',
        'dateMS3.required_with' => 'Date is required when Scale index of iron scales is present.',
        'dateMS4.required_with' => 'Date is required when [Ca] is present.',
        'dateMS5.required_with' => 'Date is required when [Ba] is present.',

        'dateFB1.required_with' => 'Date is required when [Al] is present.',
        'dateFB2.required_with' => 'Date is required when [Si] is present.',
        'dateFB3.required_with' => 'Date is required when Critical Radius factor Rc is present.',
        'dateFB4.required_with' => 'Date is required when Mineralogic factor is present.',
        'dateFB5.required_with' => 'Date is required when Crushed proppant factor is present.',

        'dateOS1.required_with' => 'Date is required when Cll factor Index is present.',
        'dateOS2.required_with' => 'Date is required when Compositional factor is present.',
        'dateOS3.required_with' => 'Date is required when Pressure factor is present.',
        'dateOS4.required_with' => 'Date is required when High Impact Factor is present.',

        'dateRP1.required_with' => 'Date is required when Number of days below Saturation Pressure is present.',
        'dateRP2.required_with' => 'Date is required when Delta pressure from saturation pressure is present.',
        'dateRP3.required_with' => 'Date is required when Water Intrusion is present.',
        'dateRP4.required_with' => 'Date is required when High impact factor and Thompson correlation is present.',

        'dateID1.required_with' => 'Date is required when Invasion radius is present.',
        'dateID2.required_with' => 'Date is required when Polymer damage factor is present.',
        'dateID3.required_with' => 'Date is required when Induced skin is present.',
        'dateID4.required_with' => 'Date is required when Mud damage factor is present.',
        
        'dateGD1.required_with' => 'Date is required when Natural fractures index is present.',
        'dateGD2.required_with' => 'Date is required when Drawdown is present.',
        'dateGD3.required_with' => 'Date is required when Ratio of KH + fracture / KH is present.',
        'dateGD4.required_with' => 'Date is required when Geomechanical damage expressed as fraction of base permeability at BHFP is present.',




        'FB1.between' => '[Al] must be between 0 and 8.55.',
        'FB2.between' => '[Si] must be between 4 and 102.',
        'FB3.between' => 'Critical Radius factor Rc must be between 0.8 and 26.',
        'FB4.between' => 'Mineralogic factor must be between 0.3 and 1.',
        'FB5.between' => 'Crushed proppant factor must be between 0.74 and 3.',

        'OS1.between' => 'Cll factor Index must be between 0.592 and 13.557.',

        'ID1.between' => 'Invasion radius must be between 0.774 and 24.95.',
        'ID2.between' => 'Polymer damage factor must be between 0 and 9453.',
        'ID3.between' => 'Induced skin must be between 100 and 5217.',
        'ID4.between' => 'Mud damage factor must be between 4 and 14228.',

        'GD1.between' => 'Natural fractures index must be between 0 and 1.',
        'GD2.between' => 'Drawdown must be between 1 and 6000.',
        'GD3.between' => 'Ratio of KH + fracture / KH must be between 0 and 100.',
        'GD4.between' => 'Geomechanical damage expressed as fraction of base permeability at BHFP must be between 0 and 1.',
        ];
    }
}


