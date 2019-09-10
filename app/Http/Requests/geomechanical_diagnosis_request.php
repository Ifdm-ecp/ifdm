<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class geomechanical_diagnosis_request extends Request
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
            'well_azimuth'=>'required_if:'.isset($_POST["button_sw"]).',==,"false",|numeric',
            'well_dip'=>'required_if:'.isset($_POST["button_sw"]).',==,"false",|numeric',
            'well_radius'=>'required_if:'.isset($_POST["button_sw"]).',==,"false",|numeric',
            'max_analysis_radius'=>'required_if:'.isset($_POST["button_sw"]).',==,"false",|numeric',
            'matrix_permeability'=>'required_if:'.isset($_POST["button_sw"]).',==,"false",|numeric',
            'poisson_ratio'=>'required_if:'.isset($_POST["button_sw"]).',==,"false",|numeric',
            'biot_coefficient'=>'required_if:'.isset($_POST["button_sw"]).',==,"false",|numeric',
            'azimuth_maximum_horizontal_stress'=>'required_if:'.isset($_POST["button_sw"]).',==,"false",|numeric',
            'minimum_horizontal_stress_gradient'=>'required_if:'.isset($_POST["button_sw"]).',==,"false",|numeric',
            'vertical_stress_gradient'=>'required_if:'.isset($_POST["button_sw"]).',==,"false",|numeric',
            'maximum_horizontal_stress_gradient'=>'required_if:'.isset($_POST["button_sw"]).',==,"false",|numeric',
            'initial_fracture_width'=>'required_if:'.isset($_POST["button_sw"]).',==,"false",|numeric',
            'initial_fracture_toughness'=>'required_if:'.isset($_POST["button_sw"]).',==,"false",|numeric',
            'fracture_closure_permeability'=>'required_if:'.isset($_POST["button_sw"]).',==,"false",|numeric',
            'residual_fracture_closure_permeability'=>'required_if:'.isset($_POST["button_sw"]).',==,"false",|numeric',
            'top'=>'required_if:'.isset($_POST["button_sw"]).',==,"false",|numeric',
            'netpay'=>'required_if:'.isset($_POST["button_sw"]).',==,"false",|numeric',
            'viscosity'=>'required_if:'.isset($_POST["button_sw"]).',==,"false",|numeric',
            'volumetric_factor'=>'required_if:'.isset($_POST["button_sw"]).',==,"false",|numeric',
            'rate'=>'required_if:'.isset($_POST["button_sw"]).',==,"false",|numeric'
        ];

    }

    public function messages()
    {
        $messages = [];

        return $messages;
    }
}
