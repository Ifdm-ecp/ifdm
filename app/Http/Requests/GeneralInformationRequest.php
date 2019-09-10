<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class GeneralInformationRequest extends Request
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
            'top' => 'required|numeric|min:0',
            'bottom' => 'required|numeric|min:0',
            'netpay' => 'required|numeric|min:0',
            'porosity' => 'required|numeric|between:0,100',
            'permeability' => 'required|numeric|min:0',
            'FType' => 'required',
            'SPressure' => 'required|numeric|min:0',
            'viscosityG' => 'required|numeric|min:0',
            'FVFG' => 'required|numeric|min:0',
            'RV' => 'required|numeric|min:0',
            'viscosityO' => 'required|numeric|min:0',
            'FVFO' => 'required|numeric|min:0',
            'RS' => 'required|numeric|min:0',
            'viscosityW' => 'required|numeric|min:0',
            'FVFW' => 'required|numeric|min:0',
            'wellR' => 'required|numeric|between:0,1',
            'drainageR' => 'required|numeric|min:0',
            'ReservoirP' => 'required|numeric|min:0',
            'BHP' => 'required|numeric|min:0',
            'Qg' => 'required|numeric|min:0',
            'Qo' => 'required|numeric|min:0',
            'Qw' => 'required|numeric|min:0',
            'MS' => 'required|numeric|min:0',
            'OS' => 'required|numeric|min:0',
            'RPE' => 'required|numeric|min:0',
            'critical_radius' => 'required|numeric|min:0',
            'total_volumen' => 'required|numeric|min:0',
            'GD' => 'required|numeric|min:0',
            'Kc' => 'required|numeric',
            'MSFormation' => 'required|numeric|between:0,100',
            'FBFormation' => 'required|numeric|between:0,100',
            'OSFormation' => 'required|numeric|between:0,100',
            'KrFormation' => 'required|numeric|between:0,100',
            'IDFormation' => 'required|numeric|between:0,100',
            'GDFormation' => 'required|numeric|between:0,100',
        ];
    }

    public function messages()
    {
        return [
            'top.required'=> 'Top required.',
            'bottom.required'=> 'TVD required.',
            'netpay.required'=> 'NetPay required.',
            'porosity.required'=> 'Porosity required.',
            'permeability.required'=> 'Relative permeabiity required.',
            'FType.required'=> 'Fluid type required.',
            'SPressure.required'=> 'Saturation pressure required.',
            'viscosityG.required'=> 'Gas viscosity required.',
            'FVFG.required'=> 'FVF gas required.',
            'RV.required'=> 'RV  required.',
            'viscosityO.required'=> 'Oil viscosity required.',
            'FVFO.required'=> 'FVF oil required.',
            'RS.required'=> 'RS required.',
            'viscosityW.required'=> 'Water viscosity required.',
            'FVFW.required'=> 'FVF water required.',
            'wellR.required'=> 'Well radius required.',
            'drainageR.required'=> 'Drainage radius required.',
            'ReservoirP.required'=> 'Reservoir pressure required.',
            'BHP.required'=> 'BHP required.',
            'Qg.required'=> 'Gas rate required.',
            'Qo.required'=> 'Oil rate required.',
            'Qw.required'=> 'Water rate required.',
            'MS.required'=> 'MS required.',
            'OS.required'=> 'OS required.',
            'GD.required'=> 'GD required.',
            'RPE.required'=> 'Relative permeability required.',
            'Kc.required'=> 'Kc required.',
            'MSFormation.required'=> 'MS required.',
            'FBFormation.required'=> 'FB required.',
            'OSFormation.required'=> 'OS required.',
            'KrFormation.required'=> 'Relative permeability required.',
            'IDFormation.required'=> 'ID required.',
            'GDFormation.required'=> 'GD required.',
            'critical_radius.required'=> 'Critical Radius derived from maximum critical velocity, Vc required.',
            'total_volumen.required'=> 'Total volumen of water based fluids pumped into the well required.',

            'critical_radius.numeric'=> 'Critical Radius derived from maximum critical velocity, Vc must be numeric.',
            'total_volumen.numeric'=> 'Total volumen of water based fluids pumped into the well must be numeric.',
            'top.numeric'=> 'Top must be numeric.',
            'bottom.numeric'=> 'TVD must be numeric.',
            'netpay.numeric'=> 'NetPay must be numeric.',
            'porosity.numeric'=> 'Porosity must be numeric.',
            'permeability.numeric'=> 'Relative permeability must be numeric.',
            'SPressure.numeric'=> 'Saturation pressure must be numeric.',
            'viscosityG.numeric'=> 'Gas viscosity must be numeric.',
            'FVFG.numeric'=> 'FVF gas must be numeric.',
            'RV.numeric'=> 'RV must be numeric.',
            'viscosityO.numeric'=> 'Oil viscosity must be numeric.',
            'FVFO.numeric'=> 'FVF oil must be numeric.',
            'RS.numeric'=> 'RS must be numeric.',
            'viscosityW.numeric'=> 'Water viscosity must be numeric.',
            'FVFW.numeric'=> 'FVF water must be numeric.',
            'wellR.numeric'=> 'Well radius must be numeric.',
            'drainageR.numeric'=> 'Drainage radius must be numeric.',
            'ReservoirP.numeric'=> 'Reservoir pressure must be numeric.',
            'BHP.numeric'=> 'BHP must be numeric.',
            'Qg.numeric'=> 'Gas rate must be numeric.',
            'Qo.numeric'=> 'Oil rate must be numeric.',
            'Qw.numeric'=> 'Water rate must be numeric.',
            'MS.numeric'=> 'MS must be numeric.',
            'OS.numeric'=> 'OS must be numeric.',
            'GD.numeric'=> 'GD must be numeric.',
            'RPE.numeric'=> 'Relative permeability must be numeric.',
            'Kc.numeric'=> 'Kc must be numeric.',
            'MSFormation.numeric'=> 'MS must be numeric.',
            'FBFormation.numeric'=> 'FB must be numeric.',
            'OSFormation.numeric'=> 'OS must be numeric.',
            'KrFormation.numeric'=> 'Relative permeability must be numeric.',
            'IDFormation.numeric'=> 'ID must be numeric.',
            'GDFormation.numeric'=> 'GD must be numeric.',


            'critical_radius.min'=> 'Critical Radius derived from maximum critical velocity, Vc must be positive.',
            'total_volumen.min'=> 'Total volumen of water based fluids pumped into the well must be positive.',
            'top.min'=> 'Top must be positive.',
            'bottom.min'=> 'TVD must be positive.',
            'netpay.min'=> 'NetPay must be positive.',
            'permeability.min'=> 'Relative permeabiity must be positive.',
            'SPressure.min'=> 'Saturation pressure must be positive.',
            'viscosityG.min'=> 'Gas viscosity must be positive.',
            'FVFG.min'=> 'FVF gas must be positive.',
            'RV.min'=> 'RV must be positive.',
            'viscosityO.min'=> 'Oil viscosity must be positive.',
            'FVFO.min'=> 'FVF oil must be positive.',
            'RS.min'=> 'RS must be positive.',
            'viscosityW.min'=> 'Water viscosity must be positive.',
            'FVFW.min'=> 'FVF water must be positive.',
            'drainageR.min'=> 'Drainage radius must be positive.',
            'ReservoirP.min'=> 'Reservoir pressure must be positive.',
            'BHP.min'=> 'BHP must be positive.',
            'Qg.min'=> 'Gas rate must be positive.',
            'Qo.min'=> 'Oil rate must be positive.',
            'Qw.min'=> 'Water rate must be positive.',
            'MS.min'=> 'MS must be positive.',
            'OS.min'=> 'OS must be positive.',
            'GD.min'=> 'GD must be positive.',
            'RPE.min'=> 'Relative permeability must be positive.',
            'Kc.min'=> 'Kc must be positive.',

            'porosity.between'=> 'Porosity must be between 0% and 100%.',
            'wellR.between'=> 'Well radius must be between 0% and 100%.',
            'MSFormation.between'=> 'MS must be between 0% and 100%.',
            'FBFormation.between'=> 'FB must be between 0% and 100%.',
            'OSFormation.between'=> 'OS must be between 0% and 100%.',
            'KrFormation.between'=> 'Relative permeability must be between 0% and 100%.',
            'IDFormation.between'=> 'ID must be between 0% and 100%.',
            'GDFormation.between'=> 'GD must be between 0% and 100%.',
        ];
    
    }
}
