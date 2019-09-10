<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use DB;
use View;

class scenario_report_controller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show_asphaltene_report($id)
    {
        if (\Auth::check()) {
            $scenary = DB::table('escenarios')->where('id',$id)->first();
            $scenary_name = $scenary->nombre;
            $scenary_description = $scenary->descripcion;
            $scenary_date = $scenary->fecha;

            #Asphaltene Precipitated
            $asphaltenes_d_precipitated_analysis = DB::table('asphaltenes_d_precipitated_analysis')->where('scenario_id',$scenary->id)->first();

            $asphaltenes_d_precipitated_analysis_id = null;
            if($asphaltenes_d_precipitated_analysis != null){
                $asphaltenes_d_precipitated_analysis_id = $asphaltenes_d_precipitated_analysis->id;
                $asphaltenes_d_precipitated_analysis_components_data = DB::table('asphaltenes_d_precipitated_analysis_components_data')->where('asphaltenes_d_precipitated_analysis_id',$asphaltenes_d_precipitated_analysis->id)->get();
                $asphaltenes_d_precipitated_analysis_temperatures = DB::table('asphaltenes_d_precipitated_analysis_temperatures')->where('asphaltenes_d_precipitated_analysis_id',$asphaltenes_d_precipitated_analysis->id)->get();
            }
            #Asphaltene Diagnosis
            $asphaltenes_d_diagnosis = DB::table('asphaltenes_d_diagnosis')->where('scenario_id',$scenary->id)->first();
            $dates_data = [];

            if($asphaltenes_d_diagnosis != null){
                $asphaltenes_d_diagnosis_pvt = DB::table('asphaltenes_d_diagnosis_pvt')->where('asphaltenes_d_diagnosis_id',$asphaltenes_d_diagnosis->id)->get();
                $asphaltenes_d_diagnosis_historical_data = DB::table('asphaltenes_d_diagnosis_historical_data')->where('asphaltenes_d_diagnosis_id',$asphaltenes_d_diagnosis->id)->get();
                $asphaltenes_d_diagnosis_soluble_asphaltenes = DB::table('asphaltenes_d_diagnosis_soluble_asphaltenes')->where('asphaltenes_d_diagnosis_id',$asphaltenes_d_diagnosis->id)->get();

                foreach ($asphaltenes_d_diagnosis_historical_data as $value) {
                    array_push($dates_data, $value->date);
                }
            }

            #Asphaltene Stability Analysis
            $asphaltenes_d_stability_analysis = DB::table('asphaltenes_d_stability_analysis')->where('scenario_id',$scenary->id)->first();
            $asphaltenes_d_stability_analysis_components = [];
            $asphaltenes_d_stability_analysis_results = [];
            if($asphaltenes_d_stability_analysis != null){
                $asphaltenes_d_stability_analysis_components = DB::table('asphaltenes_d_stability_analysis_components')->where('asphaltenes_d_stability_analysis_id',$asphaltenes_d_stability_analysis->id)->get();
                $asphaltenes_d_stability_analysis_results = DB::table('asphaltenes_d_stability_analysis_results')->where('asphaltenes_d_stability_analysis_id',$asphaltenes_d_stability_analysis->id)->first();
            }

            return View::make('asphaltene_scenario_report', compact(['scenary_name', 'scenary_description', 'scenary_date', 'asphaltenes_d_precipitated_analysis','asphaltenes_d_diagnosis', 'asphaltenes_d_stability_analysis', 'asphaltenes_d_stability_analysis_components','asphaltenes_d_stability_analysis_results','asphaltenes_d_diagnosis_pvt','asphaltenes_d_diagnosis_historical_data','asphaltenes_d_diagnosis_soluble_asphaltenes','dates_data','asphaltenes_d_precipitated_analysis_components_data','asphaltenes_d_precipitated_analysis_temperatures','asphaltenes_d_precipitated_analysis_id']));
        }else{
            return view('loginfirst');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show_fines_report($id)
    {
        if (\Auth::check()) {
            $scenary = DB::table('escenarios')->where('id',$id)->first();
            $scenary_name = $scenary->nombre;
            $scenary_description = $scenary->descripcion;
            $scenary_date = $scenary->fecha;

            #Asphaltene Precipitated
            $fines_d_diagnosis = DB::table('fines_d_diagnosis')->where('scenario_id',$scenary->id)->first();

            if($fines_d_diagnosis != null){
                $fines_d_pvt = DB::table('fines_d_pvt')->where('fines_d_diagnosis_id',$fines_d_diagnosis->id)->get();
                $fines_d_historical_data = DB::table('fines_d_historical_data')->where('fines_d_diagnosis_id',$fines_d_diagnosis->id)->get();
                $fines_d_phenomenological_constants = DB::table('fines_d_phenomenological_constants')->where('fines_d_diagnosis_id',$fines_d_diagnosis->id)->get();

                $dates_data = [];
                if($fines_d_historical_data){
                    foreach ($fines_d_historical_data as $value) {
                        array_push($dates_data, $value->date);
                    }
                }
                
            }
            
            return View::make('fines_scenario_report', compact(['scenary_name', 'scenary_description', 'scenary_date', 'fines_d_diagnosis', 'fines_d_pvt', 'fines_d_historical_data', 'fines_d_phenomenological_constants', 'dates_data']));
        }else{
            return view('loginfirst');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
