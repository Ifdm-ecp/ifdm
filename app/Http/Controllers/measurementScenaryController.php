<?php

namespace App\Http\Controllers;
if(!isset($_SESSION)) {
     session_start();
}
use DB;
use Validator;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\GeneralInformationRequest;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Session;
use View;
use App\multiparametrico;
use App\subparameters_weight;
use App\Http\Requests\measurementScenaryRequest;


use App\escenario;



class measurementScenaryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(measurementScenaryRequest $request, $id)
    {
        if (\Auth::check()) {

            //Validaciones para formulario
            $validator = Validator::make($request->all(), [

            'MS1' => 'numeric|required_if:weight_1_hidden,false',
            'MS2' => 'numeric|required_if:weight_2_hidden,false',
            'MS3' => 'numeric|required_if:weight_3_hidden,false',
            'MS4' => 'numeric|required_if:weight_4_hidden,false',
            'MS5' => 'numeric|required_if:weight_5_hidden,false',
            'FB1' => 'numeric|required_if:weight_6_hidden,false',
            'FB2' => 'numeric|required_if:weight_7_hidden,false',
            'FB3' => 'numeric|required_if:weight_8_hidden,false',
            'FB4' => 'numeric|required_if:weight_9_hidden,false',
            'FB5' => 'numeric|required_if:weight_10_hidden,false',
            'OS1' => 'numeric|required_if:weight_11_hidden,false',
            'OS2' => 'numeric|required_if:weight_12_hidden,false',
            'OS3' => 'numeric|required_if:weight_13_hidden,false',
            'OS4' => 'numeric|required_if:weight_14_hidden,false',
            'RP1' => 'numeric|required_if:weight_15_hidden,false',
            'RP2' => 'numeric|required_if:weight_16_hidden,false',
            'RP3' => 'numeric|required_if:weight_17_hidden,false',
            'RP4' => 'numeric|required_if:weight_18_hidden,false',
            'ID1' => 'numeric|required_if:weight_19_hidden,false',
            'ID2' => 'numeric|required_if:weight_20_hidden,false',
            'ID3' => 'numeric|required_if:weight_21_hidden,false',
            'ID4' => 'numeric|required_if:weight_22_hidden,false',
            'GD1' => 'numeric|required_if:weight_23_hidden,false',
            'GD2' => 'numeric|required_if:weight_24_hidden,false',
            'GD3' => 'numeric|required_if:weight_25_hidden,false',
            'GD4' => 'numeric|required_if:weight_26_hidden,false',
            'p10_ms1' => 'numeric|required_if:weight_1_hidden,false',
            'p10_ms2' => 'numeric|required_if:weight_2_hidden,false',
            'p10_ms3' => 'numeric|required_if:weight_3_hidden,false',
            'p10_ms4' => 'numeric|required_if:weight_4_hidden,false',
            'p10_ms5' => 'numeric|required_if:weight_5_hidden,false',
            'p10_fb1' => 'numeric|required_if:weight_6_hidden,false',
            'p10_fb2' => 'numeric|required_if:weight_7_hidden,false',
            'p10_fb3' => 'numeric|required_if:weight_8_hidden,false',
            'p10_fb4' => 'numeric|required_if:weight_9_hidden,false',
            'p10_fb5' => 'numeric|required_if:weight_10_hidden,false',
            'p10_os1' => 'numeric|required_if:weight_11_hidden,false',
            'p10_os2' => 'numeric|required_if:weight_12_hidden,false',
            'p10_os3' => 'numeric|required_if:weight_13_hidden,false',
            'p10_os4' => 'numeric|required_if:weight_14_hidden,false',
            'p10_rp1' => 'numeric|required_if:weight_15_hidden,false',
            'p10_rp2' => 'numeric|required_if:weight_16_hidden,false',
            'p10_rp3' => 'numeric|required_if:weight_17_hidden,false',
            'p10_rp4' => 'numeric|required_if:weight_18_hidden,false',
            'p10_id1' => 'numeric|required_if:weight_19_hidden,false',
            'p10_id2' => 'numeric|required_if:weight_20_hidden,false',
            'p10_id3' => 'numeric|required_if:weight_21_hidden,false',
            'p10_id4' => 'numeric|required_if:weight_22_hidden,false',
            'p10_gd1' => 'numeric|required_if:weight_23_hidden,false',
            'p10_gd2' => 'numeric|required_if:weight_24_hidden,false',
            'p10_gd3' => 'numeric|required_if:weight_25_hidden,false',
            'p10_gd4' => 'numeric|required_if:weight_26_hidden,false',
            'p90_ms1' => 'numeric|required_if:weight_1_hidden,false',
            'p90_ms2' => 'numeric|required_if:weight_2_hidden,false',
            'p90_ms3' => 'numeric|required_if:weight_3_hidden,false',
            'p90_ms4' => 'numeric|required_if:weight_4_hidden,false',
            'p90_ms5' => 'numeric|required_if:weight_5_hidden,false',
            'p90_fb1' => 'numeric|required_if:weight_6_hidden,false',
            'p90_fb2' => 'numeric|required_if:weight_7_hidden,false',
            'p90_fb3' => 'numeric|required_if:weight_8_hidden,false',
            'p90_fb4' => 'numeric|required_if:weight_9_hidden,false',
            'p90_fb5' => 'numeric|required_if:weight_10_hidden,false',
            'p90_os1' => 'numeric|required_if:weight_11_hidden,false',
            'p90_os2' => 'numeric|required_if:weight_12_hidden,false',
            'p90_os3' => 'numeric|required_if:weight_13_hidden,false',
            'p90_os4' => 'numeric|required_if:weight_14_hidden,false',
            'p90_rp1' => 'numeric|required_if:weight_15_hidden,false',
            'p90_rp2' => 'numeric|required_if:weight_16_hidden,false',
            'p90_rp3' => 'numeric|required_if:weight_17_hidden,false',
            'p90_rp4' => 'numeric|required_if:weight_18_hidden,false',
            'p90_id1' => 'numeric|required_if:weight_19_hidden,false',
            'p90_id2' => 'numeric|required_if:weight_20_hidden,false',
            'p90_id3' => 'numeric|required_if:weight_21_hidden,false',
            'p90_id4' => 'numeric|required_if:weight_22_hidden,false',
            'p90_gd1' => 'numeric|required_if:weight_23_hidden,false',
            'p90_gd2' => 'numeric|required_if:weight_24_hidden,false',
            'p90_gd3' => 'numeric|required_if:weight_25_hidden,false',
            'p90_gd4' => 'numeric|required_if:weight_26_hidden,false',
            'ms_scale_index_caco3' => 'numeric|between:0,1',
            'ms_scale_index_baso4' => 'numeric|between:0,1',
            'ms_scale_index_iron_scales' => 'numeric|between:0,1',
            'ms_calcium_concentration' => 'numeric|between:0,1',
            'ms_barium_concentration' => 'numeric|between:0,1',
            'fb_aluminum_concentration' => 'numeric|between:0,1',
            'fb_silicon_concentration' => 'numeric|between:0,1',
            'fb_critical_radius_factor' => 'numeric|between:0,1',
            'fb_mineralogic_factor' => 'numeric|between:0,1',
            'fb_crushed_proppant_factor' => 'numeric|between:0,1',
            'os_cll_factor' => 'numeric|between:0,1',
            'os_compositional_factor' => 'numeric|between:0,1',
            'os_pressure_factor' => 'numeric|between:0,1',
            'os_high_impact_factor' => 'numeric|between:0,1',
            'rp_days_below_saturation_pressure' => 'numeric|between:0,1',
            'rp_delta_pressure_saturation' => 'numeric|between:0,1',
            'rp_water_intrusion' => 'numeric|between:0,1',
            'rp_high_impact_factor' => 'numeric|between:0,1',
            'id_gross_pay' => 'numeric|between:0,1',
            'id_polymer_damage_factor' => 'numeric|between:0,1',
            'id_total_volume_water' => 'numeric|between:0,1',
            'id_mud_damage_factor' => 'numeric|between:0,1',
            'gd_fraction_netpay' => 'numeric|between:0,1',
            'gd_drawdown' => 'numeric|between:0,1',
            'gd_ratio_kh_fracture' => 'numeric|between:0,1',
            'gd_geomechanical_damage_fraction' => 'numeric|between:0,1',



             ]);

            if ($validator->fails()) {
                $multId=$id;
                return \Redirect::action('edit_subparameter_controller@index', compact('multId'))
                    ->withErrors($validator)
                    ->withInput();
            }else{

                //Editar subparametros (valor, fecha y comentario) de multiparametrico
                $multiparametric=multiparametrico::find($id);

                $multiparametric->ms1 = $request->input('MS1');
                $multiparametric->ms2 = $request->input('MS2');
                $multiparametric->ms3 = $request->input('MS3');
                $multiparametric->ms4 = $request->input('MS4');
                $multiparametric->ms5 = $request->input('MS5');
                $multiparametric->date_ms1 = $request->input('dateMS1');
                $multiparametric->date_ms2 = $request->input('dateMS2');
                $multiparametric->date_ms3 = $request->input('dateMS3');
                $multiparametric->date_ms4 = $request->input('dateMS4');
                $multiparametric->date_ms5 = $request->input('dateMS5');
                $multiparametric->comment_ms1 = $request->input('MS1comment');
                $multiparametric->comment_ms2 = $request->input('MS2comment');
                $multiparametric->comment_ms3 = $request->input('MS3comment');
                $multiparametric->comment_ms4 = $request->input('MS4comment');
                $multiparametric->comment_ms5 = $request->input('MS5comment');
                $multiparametric->fb1 = $request->input('FB1');
                $multiparametric->fb2 = $request->input('FB2');
                $multiparametric->fb3 = $request->input('FB3');
                $multiparametric->fb4 = $request->input('FB4');
                $multiparametric->fb5 = $request->input('FB5');
                $multiparametric->date_fb1 = $request->input('dateFB1');
                $multiparametric->date_fb2 = $request->input('dateFB2');
                $multiparametric->date_fb3 = $request->input('dateFB3');
                $multiparametric->date_fb4 = $request->input('dateFB4');
                $multiparametric->date_fb5 = $request->input('dateFB5');
                $multiparametric->comment_fb1 = $request->input('FB1comment');
                $multiparametric->comment_fb2 = $request->input('FB2comment');
                $multiparametric->comment_fb3 = $request->input('FB3comment');
                $multiparametric->comment_fb4 = $request->input('FB4comment');
                $multiparametric->comment_fb5 = $request->input('FB5comment');
                $multiparametric->os1 = $request->input('OS1');
                $multiparametric->os2 = $request->input('OS2');
                $multiparametric->os3 = $request->input('OS3');
                $multiparametric->os4 = $request->input('OS4');
                $multiparametric->date_os1 = $request->input('dateOS1');
                $multiparametric->date_os2 = $request->input('dateOS2');
                $multiparametric->date_os3 = $request->input('dateOS3');
                $multiparametric->date_os4 = $request->input('dateOS4');
                $multiparametric->comment_os1 = $request->input('OS1comment');
                $multiparametric->comment_os2 = $request->input('OS2comment');
                $multiparametric->comment_os3 = $request->input('OS3comment');
                $multiparametric->comment_os4 = $request->input('OS4comment');
                $multiparametric->rp1 = $request->input('RP1');
                $multiparametric->rp2 = $request->input('RP2');
                $multiparametric->rp3 = $request->input('RP3');
                $multiparametric->rp4 = $request->input('RP4');
                $multiparametric->date_rp1 = $request->input('dateRP1');
                $multiparametric->date_rp2 = $request->input('dateRP2');
                $multiparametric->date_rp3 = $request->input('dateRP3');
                $multiparametric->date_rp4 = $request->input('dateRP4');
                $multiparametric->comment_rp1 = $request->input('RP1comment');
                $multiparametric->comment_rp2 = $request->input('RP2comment');
                $multiparametric->comment_rp3 = $request->input('RP3comment');
                $multiparametric->comment_rp4 = $request->input('RP4comment');
                $multiparametric->id1 = $request->input('ID1');
                $multiparametric->id2 = $request->input('ID2');
                $multiparametric->id3 = $request->input('ID3');
                $multiparametric->id4 = $request->input('ID4');
                $multiparametric->date_id1 = $request->input('dateID1');
                $multiparametric->date_id2 = $request->input('dateID2');
                $multiparametric->date_id3 = $request->input('dateID3');
                $multiparametric->date_id4 = $request->input('dateID4');
                $multiparametric->comment_id1 = $request->input('ID1comment');
                $multiparametric->comment_id2 = $request->input('ID2comment');
                $multiparametric->comment_id3 = $request->input('ID3comment');
                $multiparametric->comment_id4 = $request->input('ID4comment');
                $multiparametric->gd1 = $request->input('GD1');
                $multiparametric->gd2 = $request->input('GD2');
                $multiparametric->gd3 = $request->input('GD3');
                $multiparametric->gd4 = $request->input('GD4');
                $multiparametric->date_gd1 = $request->input('dateGD1');
                $multiparametric->date_gd2 = $request->input('dateGD2');
                $multiparametric->date_gd3 = $request->input('dateGD3');
                $multiparametric->date_gd4 = $request->input('dateGD4');
                $multiparametric->comment_gd1 = $request->input('GD1comment');
                $multiparametric->comment_gd2 = $request->input('GD2comment');
                $multiparametric->comment_gd3 = $request->input('GD3comment');
                $multiparametric->comment_gd4 = $request->input('GD4comment');
                $multiparametric->p10_ms1 = $request->input('p10_ms1');
                $multiparametric->p10_ms2 = $request->input('p10_ms2');
                $multiparametric->p10_ms3= $request->input('p10_ms3');
                $multiparametric->p10_ms4 = $request->input('p10_ms4');
                $multiparametric->p10_ms5 = $request->input('p10_ms5');
                $multiparametric->p10_fb1 = $request->input('p10_fb1');
                $multiparametric->p10_fb2 = $request->input('p10_fb2');
                $multiparametric->p10_fb3 = $request->input('p10_fb3');
                $multiparametric->p10_fb4 = $request->input('p10_fb4');
                $multiparametric->p10_fb5 = $request->input('p10_fb5');
                $multiparametric->p10_os1 = $request->input('p10_os1');
                $multiparametric->p10_os2 = $request->input('p10_os2');
                $multiparametric->p10_os3 = $request->input('p10_os3');
                $multiparametric->p10_os4 = $request->input('p10_os4');
                $multiparametric->p10_rp1 = $request->input('p10_rp1');
                $multiparametric->p10_rp2 = $request->input('p10_rp2');
                $multiparametric->p10_rp3 = $request->input('p10_rp3');
                $multiparametric->p10_rp4 = $request->input('p10_rp4');
                $multiparametric->p10_id1 = $request->input('p10_id1');
                $multiparametric->p10_id2 = $request->input('p10_id2');
                $multiparametric->p10_id3 = $request->input('p10_id3');
                $multiparametric->p10_id4 = $request->input('p10_id4');
                $multiparametric->p10_gd1 = $request->input('p10_gd1');
                $multiparametric->p10_gd2 = $request->input('p10_gd2');
                $multiparametric->p10_gd3 = $request->input('p10_gd3');
                $multiparametric->p10_gd4 = $request->input('p10_gd4');
                $multiparametric->p90_ms1 = $request->input('p90_ms1');
                $multiparametric->p90_ms2 = $request->input('p90_ms2');
                $multiparametric->p90_ms3 = $request->input('p90_ms3');
                $multiparametric->p90_ms4 = $request->input('p90_ms4');
                $multiparametric->p90_ms5 = $request->input('p90_ms5');
                $multiparametric->p90_fb1 = $request->input('p90_fb1');
                $multiparametric->p90_fb2 = $request->input('p90_fb2');
                $multiparametric->p90_fb3 = $request->input('p90_fb3');
                $multiparametric->p90_fb4 = $request->input('p90_fb4');
                $multiparametric->p90_fb5 = $request->input('p90_fb5');
                $multiparametric->p90_os1 = $request->input('p90_os1');
                $multiparametric->p90_os2 = $request->input('p90_os2');
                $multiparametric->p90_os3 = $request->input('p90_os3');
                $multiparametric->p90_os4 = $request->input('p90_os4');
                $multiparametric->p90_rp1 = $request->input('p90_rp1');
                $multiparametric->p90_rp2 = $request->input('p90_rp2');
                $multiparametric->p90_rp3 = $request->input('p90_rp3');
                $multiparametric->p90_rp4 = $request->input('p90_rp4');
                $multiparametric->p90_id1 = $request->input('p90_id1');
                $multiparametric->p90_id2 = $request->input('p90_id2');
                $multiparametric->p90_id3 = $request->input('p90_id3');
                $multiparametric->p90_id4 = $request->input('p90_id4');
                $multiparametric->p90_gd1 = $request->input('p90_gd1');
                $multiparametric->p90_gd2 = $request->input('p90_gd2');
                $multiparametric->p90_gd3 = $request->input('p90_gd3');
                $multiparametric->p90_gd4 = $request->input('p90_gd4');

                $multiparametric->save();

                $subparameters_weight = DB::table('subparameters_weight')->where('multiparametric_id', $multiparametric->id)->first();
                if($subparameters_weight == null){
                    $subparameters_weight=new subparameters_weight;
                    $subparameters_weight->multiparametric_id = $multiparametric->id;
                    $subparameters_weight->save();
                }

                //Editar pesos de subparametros para un multiparametrico
                $subparameters_weight=subparameters_weight::find($subparameters_weight->id);


                if(! $request->input('ms_scale_index_caco3') ){
                     $subparameters_weight->ms_scale_index_caco3 = 0;
                }else{
                    $subparameters_weight->ms_scale_index_caco3 = $request->input('ms_scale_index_caco3');
                }

                if(! $request->input('ms_scale_index_baso4') ){
                     $subparameters_weight->ms_scale_index_baso4 = 0;
                }else{
                    $subparameters_weight->ms_scale_index_baso4 = $request->input('ms_scale_index_baso4');
                }
                
                if(! $request->input('ms_scale_index_iron_scales') ){
                    $subparameters_weight->ms_scale_index_iron_scales = 0;
                }else{
                    $subparameters_weight->ms_scale_index_iron_scales = $request->input('ms_scale_index_iron_scales');
                }

                 if(! $request->input('ms_calcium_concentration') ){
                    $subparameters_weight->ms_calcium_concentration = 0;
                }else{
                    $subparameters_weight->ms_calcium_concentration = $request->input('ms_calcium_concentration');
                }
                
                if(! $request->input('ms_barium_concentration') ){
                    $subparameters_weight->ms_barium_concentration = 0;
                }else{
                    $subparameters_weight->ms_barium_concentration = $request->input('ms_barium_concentration');
                }

                if(! $request->input('fb_aluminum_concentration') ){
                    $subparameters_weight->fb_aluminum_concentration = 0;
                }else{
                    $subparameters_weight->fb_aluminum_concentration = $request->input('fb_aluminum_concentration');
                }

                if(! $request->input('fb_silicon_concentration') ){
                    $subparameters_weight->fb_silicon_concentration = 0;
                }else{
                    $subparameters_weight->fb_silicon_concentration = $request->input('fb_silicon_concentration');
                }

                if(! $request->input('fb_critical_radius_factor') ){
                    $subparameters_weight->fb_critical_radius_factor = 0;
                }else{
                    $subparameters_weight->fb_critical_radius_factor = $request->input('fb_critical_radius_factor');
                }

                if(! $request->input('fb_mineralogic_factor') ){
                    $subparameters_weight->fb_mineralogic_factor = 0;
                }else{
                    $subparameters_weight->fb_mineralogic_factor = $request->input('fb_mineralogic_factor');
                }

                if(! $request->input('fb_crushed_proppant_factor') ){
                    $subparameters_weight->fb_crushed_proppant_factor = 0;
                }else{
                    $subparameters_weight->fb_crushed_proppant_factor = $request->input('fb_crushed_proppant_factor');
                }

                if(! $request->input('os_cll_factor') ){
                    $subparameters_weight->os_cll_factor = 0;
                }else{
                    $subparameters_weight->os_cll_factor = $request->input('os_cll_factor');
                }

                if(! $request->input('os_compositional_factor') ){
                    $subparameters_weight->os_compositional_factor = 0;
                }else{
                    $subparameters_weight->os_compositional_factor = $request->input('os_compositional_factor');
                }

                if(! $request->input('os_pressure_factor') ){
                    $subparameters_weight->os_pressure_factor = 0;
                }else{
                    $subparameters_weight->os_pressure_factor = $request->input('os_pressure_factor');
                }

                if(! $request->input('os_high_impact_factor') ){
                    $subparameters_weight->os_high_impact_factor = 0;
                }else{
                    $subparameters_weight->os_high_impact_factor = $request->input('os_high_impact_factor');
                }

                if(! $request->input('rp_days_below_saturation_pressure') ){
                    $subparameters_weight->rp_days_below_saturation_pressure = 0;
                }else{
                    $subparameters_weight->rp_days_below_saturation_pressure = $request->input('rp_days_below_saturation_pressure');
                }

                if(! $request->input('rp_delta_pressure_saturation') ){
                    $subparameters_weight->rp_delta_pressure_saturation = 0;
                }else{
                    $subparameters_weight->rp_delta_pressure_saturation = $request->input('rp_delta_pressure_saturation');
                }

                if(! $request->input('rp_water_intrusion') ){
                    $subparameters_weight->rp_water_intrusion = 0;
                }else{
                    $subparameters_weight->rp_water_intrusion = $request->input('rp_water_intrusion');
                }

                if(! $request->input('rp_high_impact_factor') ){
                    $subparameters_weight->rp_high_impact_factor = 0;
                }else{
                    $subparameters_weight->rp_high_impact_factor = $request->input('rp_high_impact_factor');
                }

                if(! $request->input('id_gross_pay') ){
                    $subparameters_weight->id_gross_pay = 0;
                }else{
                    $subparameters_weight->id_gross_pay = $request->input('id_gross_pay');
                }

                if(! $request->input('id_polymer_damage_factor') ){
                    $subparameters_weight->id_polymer_damage_factor = 0;
                }else{
                    $subparameters_weight->id_polymer_damage_factor = $request->input('id_polymer_damage_factor');
                }

                if(! $request->input('id_total_volume_water') ){
                    $subparameters_weight->id_total_volume_water = 0;
                }else{
                    $subparameters_weight->id_total_volume_water = $request->input('id_total_volume_water');
                }

                if(! $request->input('id_mud_damage_factor') ){
                    $subparameters_weight->id_mud_damage_factor = 0;
                }else{
                    $subparameters_weight->id_mud_damage_factor = $request->input('id_mud_damage_factor');
                }

                if(! $request->input('gd_fraction_netpay') ){
                    $subparameters_weight->gd_fraction_netpay = 0;
                }else{
                    $subparameters_weight->gd_fraction_netpay = $request->input('gd_fraction_netpay');
                }

                if(! $request->input('gd_drawdown') ){
                    $subparameters_weight->gd_drawdown = 0;
                }else{
                    $subparameters_weight->gd_drawdown = $request->input('gd_drawdown');
                }

                if(! $request->input('gd_ratio_kh_fracture') ){
                    $subparameters_weight->gd_ratio_kh_fracture = 0;
                }else{
                    $subparameters_weight->gd_ratio_kh_fracture = $request->input('gd_ratio_kh_fracture');
                }

                if(! $request->input('gd_geomechanical_damage_fraction') ){
                    $subparameters_weight->gd_geomechanical_damage_fraction = 0;
                }else{
                    $subparameters_weight->gd_geomechanical_damage_fraction = $request->input('gd_geomechanical_damage_fraction');
                }
                
                $subparameters_weight->save();
                
                $multId=$id;
                $formation_id=$multiparametric->formation_id;
                $field_id=$multiparametric->field_id;
                $_SESSION['multId'] = $multiparametric->id;

               

                $scenary=escenario::find($multiparametric->scenary_id);
                $scenary->completo=1;
                $scenary->save();
               

                return \Redirect::action('edit_subparameter_controller@index', compact('multId', 'formation_id', 'field_id'));

            }
        }else{
            return view('loginfirst');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
