<?php

namespace App\Http\Controllers;

if(!isset($_SESSION)) {
    session_start();
}

use DB;
use Validator;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Requests\ScenaryCreateRequest;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Support\MessageBag;
use App\escenario;
use App\multiparametrico;
use App\cuenca;
use App\campo;
use App\pozo;
use App\formacion;
use App\formacionxpozo;
use App\proyecto;
use App\ipr;
use App\asphaltenes_d_stability_analysis;
use App\asphaltenes_d_diagnosis;
use App\asphaltenes_d_precipitated_analysis;
use Redirect;
use View;

class add_scenario_controller extends Controller
{
    /**
    * Despliega la vista add_scenario con la información de proyectos para popular los select.
    *
    * @return \Illuminate\Http\Response
    */
    public function index()
    {
        if (\Auth::check()) {

            $cuenca =  cuenca::orderBy('nombre')->get();

            if(\Auth::User()->office == 0){

                $proyectos= proyecto::orderBy('nombre')->get();

            } else if(\Auth::User()->office == 1){

                $proyectos = proyecto::where('proyectos.compania','=',\Auth::User()->company)->orderBy('nombre')->get();

            } else if(\Auth::User()->office == 2){

                $proyectos = proyecto::where('proyectos.usuario_id','=',\Auth::User()->id)->orderBy('nombre')->get();

            }
            
            return View::make('add_scenario', compact(['cuenca', 'proyectos']));

        } else {

            return view('loginfirst');

        }
    }

    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function create()
    {
        /* */ 
    }

    /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    // public function store(Request $request)
    public function store(ScenaryCreateRequest $request)
    {
        dd('store');
        if (\Auth::check()) {
            /* Validaciones para formulario */
            $nombre_escenario = $request->scenary;

            /* Validación del nombre de escenario unico por compañia */            
            $user = \Auth::user();
            $company = $user->company;
            $val_escenario = escenario::where('user_id',$user->id)->where('nombre',$nombre_escenario)->first();
            
            if ($request->input('type') == "Asphaltene precipitation" && $request->id_escenario_dup != "") {

                //Escenario ya está creado? 
                $proyecto_destino = proyecto::where('id', $request->project)->first();
                $escenario_destino = escenario::where('nombre', $request->scenary)->where('proyecto_id', $proyecto_destino->id)->first();
                if($escenario_destino != null) {
                    
                    /* Si el escenario de asfaltenos ya tiene los 3 módulos (está completo) */     
                    if ($escenario_destino->completo == 1) {
                        return Redirect::back()->withErrors(['msg' => 'Asphaltene escenary is already full']);
                    }
                    
                    /* Si el escenario de asfaltenos ya tiene el submódulo creado (el submódulo ya existe) */     
                    if ($request->input('asphaltene_type') == 'Asphaltene stability analysis') {
                        $stability_destino = asphaltenes_d_stability_analysis::where('scenario_id', $escenario_destino->id)->first();
                        if ($stability_destino) {
                            return Redirect::back()->withErrors(['msg' => 'Asphaltene escenary submodule already exists']);
                        }
                        
                    }else if ($request->input('asphaltene_type') == 'Asphaltene diagnosis') {
                        $diagnosis_destino = asphaltenes_d_diagnosis::where('scenario_id', $escenario_destino->id)->first();
                        if ($diagnosis_destino) {
                            return Redirect::back()->withErrors(['msg' => 'Asphaltene escenary submodule already exists']);
                        }
                        
                    }else if ($request->input('asphaltene_type') == 'Precipitated asphaltene analysis') {
                        $precipitated_destino = asphaltenes_d_precipitated_analysis::where('scenario_id', $escenario_destino->id)->first();
                        if ($precipitated_destino) {
                            return Redirect::back()->withErrors(['msg' => 'Asphaltene escenary submodule already exists']);
                        }
                        
                    }
    
                    $escenario_destino->asphaltene_type = $request->input('asphaltene_type');
                    $escenario_destino->save();
                    $id_escenario_dup = $request->id_escenario_dup;
                    return $this->defineEvent($request, $escenario_destino, $id_escenario_dup);
                }

            }
            // } else if ($val_escenario) {
                
            //     $validator = Validator::make([], []);
            //     $validator->errors()->add('scenary', 'Scenary name has already been taken');
                
            //     $url = $request->fullUrl();
            //     $action = str_replace('ScenaryCS', 'ScenaryC', $url);
            //     dd($val_escenario);
            //     return redirect($action)
            //     ->withErrors($validator)
            //     ->withInput();
            // }
            /* Fin de validación del nombre de escenario unico por compañia */            
            
            /* Guardar datos generales de escenario */
            $scenary = new escenario;
            $scenary->user_id = $user->id;
            $scenary->nombre = $nombre_escenario;
            $scenary->descripcion = $request->input('SDescription');
            $scenary->tipo = $request->input('type');

            $scenary->asphaltene_type = null;
            $scenary->multiparametricType = null;
            $scenary->asphaltene_remediation = null;
            
            if ($request->input('type') == "IPR") {
                $formation_ids = json_encode($request->formation_ipr);
                $formation_ids = str_replace('"', '', $formation_ids);
                $formation_ids = str_replace('[', '', $formation_ids);
                $formation_ids = str_replace(']', '', $formation_ids);

                $scenary->formacion_id = $formation_ids;
            } else if ($request->input('type') == 'Drilling') {
                $scenary->formacion_id = 0;

            } else if ($request->input('type') == "Multiparametric") {
                $scenary->multiparametricType = $request->input('multiparametricType');

                if ($request->input('multiparametricType') == 'statistical') {
                    $formation_ids = json_encode($request->formation_multiparametric_statistical);
                    $formation_ids = str_replace('"', '', $formation_ids);
                    $formation_ids = str_replace('[', '', $formation_ids);
                    $formation_ids = str_replace(']', '', $formation_ids);

                    $scenary->formacion_id = $formation_ids;
                }
            } else {
                $scenary->formacion_id = $request->input('formation');
            }
            

            if ($request->input('type') == "Asphaltene precipitation"){
                $scenary->asphaltene_type = $request->input('asphaltene_type');
            } elseif ($request->input('type') == "Asphaltene remediation"){
                $scenary->asphaltene_remediation = $request->input('asphaltene_remediation');
            }

            $scenary->cuenca_id = $request->input('basin');
            $scenary->campo_id = $request->input('field');
            $scenary->pozo_id = $request->input('well');
            $scenary->fecha = $request->input('date');
            $scenary->proyecto_id = $request->input('project');
            $scenary->estado = 1;
            $scenary->completo = 0;
            
            /* $scenary->enable_advisor = ($request->input('advisor')==='1')? true : false ;*/
            $scenary->enable_advisor = true; /* Advisor fijo */
            $scenary->save();

            /* Variables de sesion para informacion del escenario en las vistas */
            $scenaryId = $scenary->id;

            /* Controlar el tipo de escenario */
            $id_escenario_dup = $request->id_escenario_dup;
            return $this->defineEvent($request, $scenary,$id_escenario_dup);
        } else {
            return view('loginfirst');
        }
    }

    /* Se define esta función para iniciar el post escenario creado*/
    private function defineEvent($request, $scenary, $id_escenario_dup)
    {
        
        $scenaryId = $scenary->id;
        if($scenary->tipo == 'IPR') { /* Terminado duplicar */

            if (empty($request->id_escenario_dup)) {
                return redirect(url('IPR',$scenaryId));
            } else {
                return redirect(url('IPR/duplicate/'.$scenaryId,$id_escenario_dup));
            }

        }  else if($scenary->tipo == "Dissagregation") {  /* Terminado duplicar */

            if (empty($request->id_escenario_dup)) {
                return \Redirect::action('DesagregacionController@create', compact('scenaryId'));
            } else {
                return \Redirect(url('Desagregacion/duplicate/'.$scenaryId.'/'.$id_escenario_dup));
            }

        } else if($scenary->tipo == 'Fines Treatment Selection') {  /* Terminado duplicar */

            if (empty($request->id_escenario_dup)) {
                return \Redirect::action('FinesTreatmentSelectionController@create', compact('scenaryId'));
            } else {
                return \Redirect(url('fts/duplicate/'.$scenaryId.'/'.$id_escenario_dup));
            }

        } else if($scenary->tipo == "Swelling and fines migration") { /* Terminado duplicar */

            if (empty($request->id_escenario_dup)) {
                return \Redirect::action('add_fines_migration_diagnosis_controller@index', compact('scenaryId'));
            } else {
                return \Redirect(url('finesMigrationDiagnosis/duplicate/'.$scenaryId.'/'.$id_escenario_dup));
            }

        } else if($scenary->tipo == "Asphaltene remediation") { /* Terminado duplicar */

            if (empty($request->id_escenario_dup)) {
                return \Redirect::action('asphaltene_remediationController@index', compact('scenaryId'));
            } else {
                return \Redirect(url('asphalteneremediation/duplicate/'.$scenaryId.'/'.$id_escenario_dup));
            }

        }  else if($scenary->tipo == "Fines remediation") { /* Terminado duplicar */

            if (empty($request->id_escenario_dup)) {
                return \Redirect::action('fines_remediationController@index', compact('scenaryId'));
            } else {
                return \Redirect(url('finesremediation/duplicate/'.$scenaryId.'/'.$id_escenario_dup));
            }

        } else if($scenary->tipo == 'Geomechanics') { /* Terminado duplicar */

            if (empty($request->id_escenario_dup)) {
                return \Redirect::action('geomechanical_diagnosis_controller@index', compact('scenaryId'));
            } else {
                return \Redirect(url('geomechanical_diagnosis/duplicate/'.$scenaryId.'/'.$id_escenario_dup));
            }

        } else if($scenary->tipo == 'Asphaltene precipitation') {

            if ($scenary->asphaltene_type == 'Asphaltene stability analysis') {

                if (empty($request->id_escenario_dup)) {
                    return \Redirect::action('add_asphaltene_stability_analysis_controller@index', compact('scenaryId'));
                } else {
                    return \Redirect(url('asphalteneStabilityAnalysis/duplicate/'.$scenaryId.'/'.$id_escenario_dup));
                }

            } else if($scenary->asphaltene_type == 'Asphaltene diagnosis') {

                if (empty($request->id_escenario_dup)) {
                    return \Redirect::action('add_asphaltenes_diagnosis_controller@index', compact('scenaryId'));
                } else {
                    return \Redirect(url('asphaltenesDiagnosis/duplicate/'.$scenaryId.'/'.$id_escenario_dup));
                }

            } else if($scenary->asphaltene_type == 'Precipitated asphaltene analysis') {

                if (empty($request->id_escenario_dup)) {
                    return \Redirect::action('add_precipitated_asphaltenes_analysis_controller@index', compact('scenaryId'));
                } else {
                    return \Redirect(url('asphaltenesPrecipitated/duplicate/'.$scenaryId.'/'.$id_escenario_dup));
                }

            }

        } else if($scenary->tipo == 'Drilling') { /* En proceso de duplicar */

            if (empty($request->id_escenario_dup)) {
                return \Redirect::action('drilling_controller@index', compact('scenaryId'));
            } else {
                return \Redirect(url('Drilling/duplicate/'.$scenaryId.'/'.$id_escenario_dup));
            }


        } else if($scenary->tipo == 'Multiparametric') { /* ************************************ Pendiente Terminar duplicar */

            if (empty($request->id_escenario_dup)) {
                return redirect()->route($scenary->multiparametricType.'.create', compact('scenaryId'));
            } else {
                return \Redirect(url($scenary->multiparametricType.'/duplicate/'.$scenaryId.'/'.$id_escenario_dup));
            }
        }
    }

    /**
    * Display the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function show($id)
    {
        /*  */
    }

    /**
    * Show the form for editing the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function edit($id)
    {
        if (\Auth::check()) {
            return redirect('share_scenario');
            
            // $scenary = escenario::find($id);
            // $cuenca = cuenca::select('id','nombre')->get();
            // $campo = campo::select('id','nombre')->where('cuenca_id','=',$scenary->cuenca_id)->get();
            // $pozo = pozo::select('id','nombre')->where('campo_id','=',$scenary->campo_id)->get();
            // $proyecto = proyecto::select('id','nombre')->get();
            // $formacion = formacionxpozo::select('id','nombre')->where('pozo_id','=',$scenary->pozo_id)->get();

            // return view('edit_scenario', ['cuenca' => $cuenca, 'campo' => $campo, 'pozo' => $pozo, 'proyecto' => $proyecto, 'formacion' => $formacion, 'scenary' => $scenary, 'project' => $proyecto]);
        }else{
            return view('loginfirst');
        }
    }

    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function update(ScenaryCreateRequest $request, $id)
    {
        dd('update');
        if (\Auth::check()) {
            /* Guardar datos generales de escenario */
            $scenary = escenario::find($id);
            $scenary->user_id = \Auth::User()->id;
            $scenary->nombre = $request->input('scenary');
            $scenary->descripcion = $request->input('SDescription');
            $scenary->tipo = $request->input('type');

            $scenary->asphaltene_type = null;
            $scenary->multiparametricType = null;
            $scenary->asphaltene_remediation = null;

            if ($request->input('type') == "Asphaltene precipitation"){
                $scenary->asphaltene_type = $request->input('asphaltene_type');
            } elseif ($request->input('type') == "Asphaltene remediation"){
                $scenary->asphaltene_remediation = $request->input('asphaltene_remediation');
            } elseif ($request->input('type') == "Multiparametric"){
                $scenary->multiparametricType = $request->input('multiparametricType');
            }

            if ($request->input('type') == "IPR") {
                $formation_ids = json_encode($request->formation_ipr);
                $formation_ids = str_replace('"', '', $formation_ids);
                $formation_ids = str_replace('[', '', $formation_ids);
                $formation_ids = str_replace(']', '', $formation_ids);

                $scenary->formacion_id = $formation_ids;
            } else {
                $scenary->formacion_id = $request->input('formation');
            }

            $scenary->cuenca_id = $request->input('basin');
            $scenary->campo_id = $request->input('field');
            $scenary->pozo_id = $request->input('well');
            $_SESSION['formation'] = DB::table('formacionxpozos')->where('id', $request->input('formation'))->value('nombre');
            $scenary->fecha = $request->input('date');
            $scenary->proyecto_id = $request->input('project');
            $scenary->estado = 1;
            $scenary->completo = 0;
            $scenary->enable_advisor = true; /* Advisor fijo */
            $scenary->save();

            /* Variables de sesion para informacion del escenario en las vistas */
            $scenaryId = $scenary->id;

            $_SESSION['basin'] = DB::table('cuencas')->where('id', $request->input('basin'))->value('nombre');
            $_SESSION['field'] = DB::table('campos')->where('id', $request->input('field'))->value('nombre');
            $_SESSION['well'] = DB::table('pozos')->where('id', $request->input('well'))->value('nombre');
            $_SESSION['scenary'] = $request->input('scenary');
            $_SESSION['formation_id'] = DB::table('formacionxpozos')->where('id', $request->input('formation'))->value('nombre');
            $_SESSION['scenary_id'] = $scenary->id;

            $id_escenario_dup = $request->id_escenario_dup;
            return $this->defineEvent($request, $scenary,$id_escenario_dup);
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
        /*  */  
    }
}
