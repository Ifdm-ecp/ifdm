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
use App\Http\Requests\measurementScenaryRequest;
use App\escenario;

class measurementAAController extends Controller
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
             ]);

            if ($validator->fails()) {
                return \Redirect::action('edit_subparameter_controller@index', compact('multId', 'formation_id', 'field_id'))
                    ->withErrors($validator)
                    ->withInput();
            }else{

                //Editar subparametros (valor, fecha y comentario) de multiparametrico
                $multiparametric=multiparametrico::find($id);
                
                if(! $request->input('MS1') ){
                    $multiparametric->ms1 = null;
                }else{
                    $multiparametric->ms1 = $request->input('MS1');
                }
                if(! $request->input('MS2') ){
                    $multiparametric->ms2 = null;
                }else{
                    $multiparametric->ms2 = $request->input('MS2');
                }
                if(! $request->input('MS3') ){
                    $multiparametric->ms3 = null;
                }else{
                    $multiparametric->ms3 = $request->input('MS3');
                }
                if(! $request->input('MS4') ){
                    $multiparametric->ms4 = null;
                }else{
                    $multiparametric->ms4 = $request->input('MS4');
                }
                if(! $request->input('MS5') ){
                    $multiparametric->ms5 = null;
                }else{
                    $multiparametric->ms5 = $request->input('MS5');
                }



                if(! $request->input('dateMS1') ){
                    $multiparametric->date_ms1 = null;
                }else{
                    $multiparametric->date_ms1 = $request->input('dateMS1');
                }
                if(! $request->input('dateMS2') ){
                    $multiparametric->date_ms2 = null;
                }else{
                    $multiparametric->date_ms2 = $request->input('dateMS2');
                }
                if(! $request->input('dateMS3') ){
                    $multiparametric->date_ms3 = null;
                }else{
                    $multiparametric->date_ms3 = $request->input('dateMS3');
                }
                if(! $request->input('dateMS4') ){
                    $multiparametric->date_ms4 = null;
                }else{
                    $multiparametric->date_ms4 = $request->input('dateMS4');
                }
                if(! $request->input('dateMS5') ){
                    $multiparametric->date_ms5 = null;
                }else{
                    $multiparametric->date_ms5 = $request->input('dateMS5');
                }



                if(! $request->input('MS1comment') ){
                    $multiparametric->comment_ms1 = null;
                }else{
                    $multiparametric->comment_ms1 = $request->input('MS1comment');
                }
                if(! $request->input('MS2comment') ){
                    $multiparametric->comment_ms2 = null;
                }else{
                    $multiparametric->comment_ms2 = $request->input('MS2comment');
                }
                if(! $request->input('MS3comment') ){
                    $multiparametric->comment_ms3 = null;
                }else{
                    $multiparametric->comment_ms3 = $request->input('MS3comment');
                }
                if(! $request->input('MS4comment') ){
                    $multiparametric->comment_ms4 = null;
                }else{
                    $multiparametric->comment_ms4 = $request->input('MS4comment');
                }
                if(! $request->input('MS5comment') ){
                    $multiparametric->comment_ms5 = null;
                }else{
                    $multiparametric->comment_ms5 = $request->input('MS5comment');
                }
                

                
                
                if(! $request->input('FB1') ){
                    $multiparametric->fb1 = null;
                }else{
                    $multiparametric->fb1 = $request->input('FB1');
                }
                if(! $request->input('FB2') ){
                    $multiparametric->fb2 = null;
                }else{
                    $multiparametric->fb2 = $request->input('FB2');
                }
                if(! $request->input('FB3') ){
                    $multiparametric->fb3 = null;
                }else{
                    $multiparametric->fb3 = $request->input('FB3');
                }
                if(! $request->input('FB4') ){
                    $multiparametric->fb4 = null;
                }else{
                    $multiparametric->fb4 = $request->input('FB4');
                }
                if(! $request->input('FB5') ){
                    $multiparametric->fb5 = null;
                }else{
                    $multiparametric->fb5 = $request->input('FB5');
                }


                if(! $request->input('dateFB1') ){
                    $multiparametric->date_fb1 = null;
                }else{
                    $multiparametric->date_fb1 = $request->input('dateFB1');
                }
                if(! $request->input('dateFB2') ){
                    $multiparametric->date_fb2 = null;
                }else{
                    $multiparametric->date_fb2 = $request->input('dateFB2');
                }
                if(! $request->input('dateFB3') ){
                    $multiparametric->date_fb3 = null;
                }else{
                    $multiparametric->date_fb3 = $request->input('dateFB3');
                }
                if(! $request->input('dateFB4') ){
                    $multiparametric->date_fb4 = null;
                }else{
                    $multiparametric->date_fb4 = $request->input('dateFB4');
                }
                if(! $request->input('dateFB5') ){
                    $multiparametric->date_fb5 = null;
                }else{
                    $multiparametric->date_fb5 = $request->input('dateFB5');
                }



                if(! $request->input('FB1comment') ){
                    $multiparametric->comment_fb1 = null;
                }else{
                    $multiparametric->comment_fb1 = $request->input('FB1comment');
                }
                if(! $request->input('FB2comment') ){
                    $multiparametric->comment_fb2 = null;
                }else{
                    $multiparametric->comment_fb2 = $request->input('FB2comment');
                }
                if(! $request->input('FB3comment') ){
                    $multiparametric->comment_fb3 = null;
                }else{
                    $multiparametric->comment_fb3 = $request->input('FB3comment');
                }
                if(! $request->input('FB4comment') ){
                    $multiparametric->comment_fb4 = null;
                }else{
                    $multiparametric->comment_fb4 = $request->input('FB4comment');
                }
                if(! $request->input('FB5comment') ){
                    $multiparametric->comment_fb5 = null;
                }else{
                    $multiparametric->comment_fb5 = $request->input('FB5comment');
                }



                if(! $request->input('OS1') ){
                    $multiparametric->os1 = null;
                }else{
                    $multiparametric->os1 = $request->input('OS1');
                }
                if(! $request->input('OS2') ){
                    $multiparametric->os2 = null;
                }else{
                    $multiparametric->os2 = $request->input('OS2');
                }
                if(! $request->input('OS3') ){
                    $multiparametric->os3 = null;
                }else{
                    $multiparametric->os3 = $request->input('OS3');
                }
                if(! $request->input('OS4') ){
                    $multiparametric->os4 = null;
                }else{
                    $multiparametric->os4 = $request->input('OS4');
                }


                if(! $request->input('dateOS1') ){
                    $multiparametric->date_os1 = null;
                }else{
                    $multiparametric->date_os1 = $request->input('dateOS1');
                }
                if(! $request->input('dateOS2') ){
                    $multiparametric->date_os2 = null;
                }else{
                    $multiparametric->date_os2 = $request->input('dateOS2');
                }
                if(! $request->input('dateOS3') ){
                    $multiparametric->date_os3 = null;
                }else{
                    $multiparametric->date_os3 = $request->input('dateOS3');
                }
                if(! $request->input('dateOS4') ){
                    $multiparametric->date_os4 = null;
                }else{
                    $multiparametric->date_os4 = $request->input('dateOS4');
                }


                if(! $request->input('OS1comment') ){
                    $multiparametric->comment_os1 = null;
                }else{
                    $multiparametric->comment_os1 = $request->input('OS1comment');
                }
                if(! $request->input('OS2comment') ){
                    $multiparametric->comment_os2 = null;
                }else{
                    $multiparametric->comment_os2 = $request->input('OS2comment');
                }
                if(! $request->input('OS3comment') ){
                    $multiparametric->comment_os3 = null;
                }else{
                    $multiparametric->comment_os3 = $request->input('OS3comment');
                }
                if(! $request->input('OS4comment') ){
                    $multiparametric->comment_os4 = null;
                }else{
                    $multiparametric->comment_os4 = $request->input('OS4comment');
                }



                if(! $request->input('RP1') ){
                   $multiparametric->rp1 = null;
                }else{
                    $multiparametric->rp1 = $request->input('RP1');
                }
                if(! $request->input('RP2') ){
                    $multiparametric->rp2 = null;
                }else{
                    $multiparametric->rp2 = $request->input('RP2');
                }
                if(! $request->input('RP3') ){
                    $multiparametric->rp3 = null;
                }else{
                    $multiparametric->rp3 = $request->input('RP3');
                }
                if(! $request->input('RP4') ){
                    $multiparametric->rp4 = null;
                }else{
                    $multiparametric->rp4 = $request->input('RP4');
                }


                if(! $request->input('dateRP1') ){
                   $multiparametric->date_rp1 = null;
                }else{
                    $multiparametric->date_rp1 = $request->input('dateRP1');
                }
                if(! $request->input('dateRP2') ){
                    $multiparametric->date_rp2 = null;
                }else{
                    $multiparametric->date_rp2 = $request->input('dateRP2');
                }
                if(! $request->input('dateRP3') ){
                    $multiparametric->date_rp3 = null;
                }else{
                    $multiparametric->date_rp3 = $request->input('dateRP3');
                }
                if(! $request->input('dateRP4') ){
                    $multiparametric->date_rp4 = null;
                }else{
                    $multiparametric->date_rp4 = $request->input('dateRP4');
                }


                if(! $request->input('RP1comment') ){
                   $multiparametric->comment_rp1 = null;
                }else{
                    $multiparametric->comment_rp1 = $request->input('RP1comment');
                }
                if(! $request->input('RP2comment') ){
                    $multiparametric->comment_rp2 = null;
                }else{
                    $multiparametric->comment_rp2 = $request->input('RP2comment');
                }
                if(! $request->input('RP3comment') ){
                    $multiparametric->comment_rp3 = null;
                }else{
                    $multiparametric->comment_rp3 = $request->input('RP3comment');
                }
                if(! $request->input('RP4comment') ){
                    $multiparametric->comment_rp4 = null;
                }else{
                    $multiparametric->comment_rp4 = $request->input('RP4comment');
                }



                if(! $request->input('ID1') ){
                   $multiparametric->id1 = null;
                }else{
                    $multiparametric->id1 = $request->input('ID1');
                }
                if(! $request->input('ID2') ){
                    $multiparametric->id2 = null;
                }else{
                    $multiparametric->id2 = $request->input('ID2');
                }
                if(! $request->input('ID3') ){
                    $multiparametric->id3 = null;
                }else{
                    $multiparametric->id3 = $request->input('ID3');
                }
                if(! $request->input('ID4') ){
                    $multiparametric->id4 = null;
                }else{
                    $multiparametric->id4 = $request->input('ID4');
                }
                

                if(! $request->input('dateID1') ){
                   $multiparametric->date_id1 = null;
                }else{
                    $multiparametric->date_id1 = $request->input('dateID1');
                }
                if(! $request->input('dateID2') ){
                    $multiparametric->date_id2 = null;
                }else{
                    $multiparametric->date_id2 = $request->input('dateID2');
                }
                if(! $request->input('dateID3') ){
                    $multiparametric->date_id3 = null;
                }else{
                    $multiparametric->date_id3 = $request->input('dateID3');
                }
                if(! $request->input('dateID4') ){
                    $multiparametric->date_id4 = null;
                }else{
                    $multiparametric->date_id4 = $request->input('dateID4');
                }           

                
                if(! $request->input('ID1comment') ){
                   $multiparametric->comment_id1 = null;
                }else{
                    $multiparametric->comment_id1 = $request->input('ID1comment');
                }
                if(! $request->input('ID2comment') ){
                    $multiparametric->comment_id2 = null;
                }else{
                    $multiparametric->comment_id2 = $request->input('ID2comment');
                }
                if(! $request->input('ID3comment') ){
                    $multiparametric->comment_id3 = null;
                }else{
                    $multiparametric->comment_id3 = $request->input('ID3comment');
                }
                if(! $request->input('ID4comment') ){
                    $multiparametric->comment_id4 = null;
                }else{
                    $multiparametric->comment_id4 = $request->input('ID4comment');
                }     
                

                if(! $request->input('GD1') ){
                   $multiparametric->gd1 = null;
                }else{
                    $multiparametric->gd1 = $request->input('GD1');
                }
                if(! $request->input('GD2') ){
                    $multiparametric->gd2 = null;
                }else{
                    $multiparametric->gd2 = $request->input('GD2');
                }
                if(! $request->input('GD3') ){
                    $multiparametric->gd3 = null;
                }else{
                    $multiparametric->gd3 = $request->input('GD3');
                }
                if(! $request->input('GD4') ){
                    $multiparametric->gd4 = null;
                }else{
                    $multiparametric->gd4 = $request->input('GD4');
                }   
                
                
                if(! $request->input('dateGD1') ){
                   $multiparametric->date_gd1 = null;
                }else{
                   $multiparametric->date_gd1 = $request->input('dateGD1');
                }
                if(! $request->input('dateGD2') ){
                    $multiparametric->date_gd2 = null;
                }else{
                    $multiparametric->date_gd2 = $request->input('dateGD2');
                }
                if(! $request->input('dateGD3') ){
                    $multiparametric->date_gd3 = null;
                }else{
                    $multiparametric->date_gd3 = $request->input('dateGD3');
                }
                if(! $request->input('dateGD4') ){
                    $multiparametric->date_gd4 = null;
                }else{
                    $multiparametric->date_gd4 = $request->input('dateGD4');
                }   


                if(! $request->input('GD1comment') ){
                   $multiparametric->comment_gd1 = null;
                }else{
                   $multiparametric->comment_gd1 = $request->input('GD1comment');
                }
                if(! $request->input('GD2comment') ){
                    $multiparametric->comment_gd2 = null;
                }else{
                    $multiparametric->comment_gd2 = $request->input('GD2comment');
                }
                if(! $request->input('GD3comment') ){
                    $multiparametric->comment_gd3 = null;
                }else{
                    $multiparametric->comment_gd3 = $request->input('GD3comment');
                }
                if(! $request->input('GD4comment') ){
                    $multiparametric->comment_gd4 = null;
                }else{
                    $multiparametric->comment_gd4 = $request->input('GD4comment');
                }   

                $multiparametrica->save();
          
                $multId=$id;
                $formation_id=$multiparametric->formation_id;
                $field_id=$multiparametric->field_id;
                $_SESSION['multId'] = $multiparametric->id;

               

                $escenario=escenario::find($multiparametric->scenary_id);
                $escenario->completo=1;
                $escenario->save();
               

                return \Redirect::action('add_subparameter_controller@index', compact('multId', 'formation_id', 'field_id'));

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
