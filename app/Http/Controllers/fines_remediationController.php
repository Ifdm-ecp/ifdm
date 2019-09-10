<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use DB;
use Auth;
use App\company;
use App\proyecto;
use App\pozo;
use App\User;
use App\cuenca;
use App\escenario;
use App\mineral_data;
use App\formacionxpozo;
use App\fines_remediation;

use App\fines_d_diagnosis;
use App\fines_d_diagnosis_results;
use App\Models\FormationMineralogy\FormationMineralogy;

use Carbon\Carbon;
use Validator;
use SplFixedArray;

class fines_remediationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($scenaryId)
    {
        $scenary_s = escenario::where('id', $scenaryId)->first();
        $pozo = pozo::find($scenary_s->pozo_id)->first();
        $formacion = formacionxpozo::where('pozo_id', $pozo->id)->first();
        $basin = cuenca::where('id',$pozo->cuenca_id)->first();

        $escenario = DB::table('escenarios')->where('id',$scenary_s->id)->first();
        $campo = DB::table('campos')->where('id', $escenario->campo_id)->first();
        $user = DB::table('users')->join('escenarios','users.id','=','escenarios.user_id')->select('users.fullName')->where('escenarios.id','=',$scenary_s->id)->first();
        $intervalo = DB::table('formacionxpozos')->where('id',$scenary_s->formacion_id)->first();
        $advisor = $scenary_s->enable_advisor;
        $mineral_data = mineral_data::get();

        $mineralogies = FormationMineralogy::where('formacion_id','=',$escenario->formacion_id)->first();
        $data_excel_rock_composition = [];
        if ($mineralogies) {
            $data_excel_rock_composition[] = [ "Quartz",$mineralogies->quarts,2.65 ];
            $data_excel_rock_composition[] = [ 'Feldspar',$mineralogies->feldspar,2.57 ];
            $data_excel_rock_composition[] = [ 'Clay',$mineralogies->clays,2.59 ];
        } else {
            $data_excel_rock_composition[] = ['Quartz',0.0,2.65];
            $data_excel_rock_composition[] = ['Feldspar',0.0,2.57];
            $data_excel_rock_composition[] = ['Clay',0.0,2.59];
        }

        $data_excel_rock_composition = json_encode($data_excel_rock_composition);
        
        return View('template.fines_remediation.create', compact(['pozo','formacion','basin','escenario','campo','scenary_s','user','intervalo','advisor','mineral_data','data_excel_rock_composition']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($res = $this->validateData($request,'store') && !$request->_button_swr) {
            return $res;
        }

        $fines = new fines_remediation;

        $fines->id_scenary = $request->id_scenary;
        $fines->initial_porosity = $request->initial_porosity;
        $fines->initial_permeability = $request->initial_permeability;
        $fines->temperature = $request->temperature;
        $fines->well_radius = $request->well_radius;
        $fines->damage_radius = $request->damage_radius;
        $fines->net_pay = $request->net_pay;
        $fines->rock_compressibility = $request->rock_compressibility;
        $fines->pressure = $request->pressure;
        $fines->viscosity = 1;  /* Se define 1 por defecto */
        $fines->excel_damage_diagnosis_input = $request->excel_damage_diagnosis_input;
        $fines->acid_concentration = $request->acid_concentration;
        $fines->injection_rate = $request->injection_rate;
        $fines->invasion_radius = $request->invasion_radius;
        $fines->excel_rock_composition_input = $request->excel_rock_composition_input;
        $fines->check_minerals_input = $request->check_minerals_input;
        if ($request->_button_swr == "1") {
            $fines->status_wr = false;
        } else {
            $fines->status_wr = true;
        }


        $fines->save();

        $scenary = escenario::find($fines->id_scenary);
        $scenary->estado = 1;
        $scenary->completo = 1;
        $scenary->save();

        return redirect(url('finesremediation/results',$fines->id));
    }

    public function validateData($request,$type,$id = null)
    {
        $validator = Validator::make($request->all(), [
            'id_scenary' => 'required|numeric|min:0',
            
            /* Primera Sección */
            'initial_porosity' => 'required|numeric|regex:/^\s*(?=.*[1-9])\d*(?:\.\d{1,11})?\s*$/',
            'initial_permeability' => 'required|numeric|regex:/^\s*(?=.*[1-9])\d*(?:\.\d{1,11})?\s*$/',
            'temperature' => 'required|numeric|regex:/^\s*(?=.*[1-9])\d*(?:\.\d{1,11})?\s*$/',
            'well_radius' => 'required|numeric|regex:/^\s*(?=.*[1-9])\d*(?:\.\d{1,11})?\s*$/',
            'damage_radius' => 'required|numeric|regex:/^\s*(?=.*[1-9])\d*(?:\.\d{1,11})?\s*$/',
            'net_pay' => 'required|numeric|regex:/^\s*(?=.*[1-9])\d*(?:\.\d{1,11})?\s*$/',
            'rock_compressibility' => 'required|numeric|regex:/^\s*(?=.*[1-9])\d*(?:\.\d{1,11})?\s*$/',
            'pressure' => 'required|numeric|regex:/^\s*(?=.*[1-9])\d*(?:\.\d{1,11})?\s*$/',

            /* Segunda Sección */
            'excel_damage_diagnosis_input' => 'required',

            /* Tercera Sección*/
            'acid_concentration' => 'required|numeric|regex:/^\s*(?=.*[1-9])\d*(?:\.\d{1,11})?\s*$/',
            'injection_rate' => 'required|numeric|regex:/^\s*(?=.*[1-9])\d*(?:\.\d{1,11})?\s*$/',
            'invasion_radius' => 'required|numeric|regex:/^\s*(?=.*[1-9])\d*(?:\.\d{1,11})?\s*$/',
            /* 'viscosity' => 'required|numeric|regex:/^\s*(?=.*[1-9])\d*(?:\.\d{1,11})?\s*$/', */
            
            /* Cuarta Sección - 1 */
            'excel_rock_composition_input' => 'required',

            /* Cuarta Sección - 2 */
            'check_minerals_input' => 'required',
        ])->setAttributeNames(array(
            'initial_porosity' => 'Initial Porosity',
            'initial_permeability' => 'Initial Permeability',
            'temperature' => 'Temperature',
            'well_radius' => 'Well Radius',
            'damage_radius' => 'Damage Radius',
            'net_pay' => 'Net Pay',
            'rock_compressibility' => 'Rock Compressibility',
            'pressure' => 'Pressure',
            'excel_damage_diagnosis_input' => 'Table Damage Diagnosis on Damage Diagnosis',
            'acid_concentration' => 'Acid Concentration',
            'injection_rate' => 'Injection Rate',
            'invasion_radius' => 'Invasion Radius',
            'viscosity' => 'Viscosity',
            'excel_rock_composition_input' => 'Table Rock Composition on Minerals Data',
            'check_minerals_input' => 'Choose Minerals on Minerals Data',
        ));

        if ($type == 'store') {
            if ($validator->fails()) {
                return redirect('finesremediation/'.$request->id_scenary)
                ->withErrors($validator)
                ->withInput();
            }
        } else {
            if ($validator->fails()) {
                return redirect('finesremediation/edit/'.$id)
                ->withErrors($validator)
                ->withInput();
            }
        }

        return false;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $fines = $this->validateId($id);
        if(!$fines){
            return redirect(url('share_scenario'));
        }
        
        $scenary_s = escenario::find($fines->id_scenary);
        $user = User::join('escenarios','users.id','=','escenarios.user_id')->select('users.fullName')->where('escenarios.id','=',$scenary_s->id)->first();

        $pozo = Pozo::find($scenary_s->pozo_id)->first();
        $basin = cuenca::where('id',$pozo->cuenca_id)->first();
        $formacion = formacionxpozo::where('pozo_id', $pozo->id)->first();
        $campo = DB::table('campos')->where('id', $scenary_s->campo_id)->first();
        $intervalo = DB::table('formacionxpozos')->where('id',$scenary_s->formacion_id)->first();

        $acid_test = null;
        if (($fines['excel_damage_diagnosis_input'] != '' && $fines['excel_damage_diagnosis_input'] != '[]') && $fines['check_minerals_input'] != '') {
            $acid_test = $this->acidtest($fines);
        }

        return view('template.fines_remediation.results', compact('fines', 'scenary_s','user','pozo','basin','formacion','campo','intervalo', 'acid_test'));
    }

    /* Esta función se encarga de validar que el id que llega sea de escenario o directamente de asphaltene remediation */
    private function validateId($id)
    {
        $asphaltene = fines_remediation::find($id);
        $scenario = escenario::find($id);

        if ($asphaltene) {

            return $asphaltene;

        } else if (!$asphaltene && $scenario) {

            $asphal_new = fines_remediation::where('id_scenary',$scenario->id)->first();
            if (!$asphal_new) {
                $scenario->delete();
                $asphal_new = false;
            }

            return $asphal_new;

        } else if (!$asphaltene && !$scenario) {
            return false;
        }
    }

    /* Recibe id del nuevo escenario, duplicateFrom seria el id del duplicado */    
    public function duplicate($id,$duplicateFrom)
    {
        $_SESSION['scenary_id_dup'] = $id;
        return $this->edit($duplicateFrom);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $fines = $this->validateId($id);
        if(!$fines){
            return redirect(url('share_scenario'));
        }

        $escenario = escenario::find($fines->id_scenary);
        $user = User::join('escenarios','users.id','=','escenarios.user_id')->select('users.fullName')->where('escenarios.id','=',$escenario->id)->first();
        $pozo = Pozo::find($escenario->pozo_id)->first();
        $basin = cuenca::where('id',$pozo->cuenca_id)->first();
        $formacion = formacionxpozo::where('pozo_id', $pozo->id)->first();
        $campo = DB::table('campos')->where('id', $escenario->campo_id)->first();
        $intervalo = DB::table('formacionxpozos')->where('id',$escenario->formacion_id)->first();
        $mineral_data = mineral_data::get();

        $advisor = $escenario->enable_advisor;

        /* Formato para la tabla*/
        $fines->rock_compressibility = number_format($fines->rock_compressibility,10,'.','');
        $fines->excel_damage_diagnosis_input = ($fines->excel_damage_diagnosis_input) ? json_encode($fines->excel_damage_diagnosis_input) : '{}';
        $fines->excel_rock_composition_input = ($fines->excel_rock_composition_input) ? json_encode($fines->excel_rock_composition_input) : '{}';

        $mineralogies = FormationMineralogy::where('formacion_id','=',$escenario->formacion_id)->first();
        if ($mineralogies) {
            $data_excel_rock_composition = "[['Quartz',".$mineralogies->quarts.",2.65],['Feldspar',".$mineralogies->feldspar.",2.57],['Clay',".$mineralogies->clays.",2.59]]";
        } else {
            $data_excel_rock_composition = "[['Quartz',0.0,2.65],['Feldspar',0.0,2.57],['Clay',0.0,2.59]]";
        }

        $data_excel_rock_composition = json_encode($data_excel_rock_composition);
        $duplicateFrom = isset($_SESSION['scenary_id_dup']) ? $_SESSION['scenary_id_dup'] : null;


        return view('template.fines_remediation.edit', compact('fines', 'escenario','user','advisor','basin','campo','intervalo','pozo','mineral_data','data_excel_rock_composition','duplicateFrom'));
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
        if ($res = $this->validateData($request,'update',$id) && !$request->_button_swr) {
            return $res;
        }

        if (isset($_SESSION['scenary_id_dup'])) {
            $fines = new fines_remediation();
        } else {
            $fines = fines_remediation::find($id);
        }

        $fines->id_scenary = $request->id_scenary;
        $fines->initial_porosity = $request->initial_porosity;
        $fines->initial_permeability = $request->initial_permeability;
        $fines->temperature = $request->temperature;
        $fines->well_radius = $request->well_radius;
        $fines->damage_radius = $request->damage_radius;
        $fines->net_pay = $request->net_pay;
        $fines->rock_compressibility = $request->rock_compressibility;
        $fines->pressure = $request->pressure;
        $fines->viscosity = 1; /* Se define 1 por defecto */
        $fines->excel_damage_diagnosis_input = $request->excel_damage_diagnosis_input;
        $fines->acid_concentration = $request->acid_concentration;
        $fines->injection_rate = $request->injection_rate;
        $fines->invasion_radius = $request->invasion_radius;
        $fines->excel_rock_composition_input = $request->excel_rock_composition_input;
        $fines->check_minerals_input = $request->check_minerals_input;

        if ($request->_button_swr == "1") {
            $fines->status_wr = false;
        } else {
            $fines->status_wr = true;
        }

        $fines->save();

        $scenary = escenario::find($fines->id_scenary);
        $scenary->estado = 1;
        $scenary->completo = 1;
        $scenary->save();

        unset($_SESSION['scenary_id_dup']);

        return redirect(url('finesremediation/results',$fines->id));
    }
    

    public function acidtest($datos)
    {
        /* Se comienza la recolección de información */

        /* Reservoir Properties */
        $por = $datos->initial_porosity;
        $rad = $datos->damage_radius;
        $crw = $datos->well_radius; /* Se pasa de ft a in */
        $per = $datos->initial_permeability;
        $temp = $datos->temperature;
        $UN = 50; /* Esta definido por defecto */
        $h = $datos->net_pay;
        $cr = $datos->rock_compressibility;
        $pinit = $datos->pressure;
        $CTR = 3; /* Esta definido por defecto */
        $muot = $datos->viscosity; /* Reemplazo de PVT por un solo campo tipo viscosidad */

        $data_phi = []; /* Se definen para iniciar el proceso de graficación */
        $data_perm = []; /* Se definen para iniciar el proceso de graficación */

        /* Damage Diagnosis */
        $damage_diagnosis_data = json_decode($datos->excel_damage_diagnosis_input);
        $count_dd = count($damage_diagnosis_data);

        $x = 0;
        $rd = new SplFixedArray($count_dd);
        $perd = new SplFixedArray($count_dd);
        $pord = new SplFixedArray($count_dd);

        foreach ($damage_diagnosis_data as $k => $v) {
            $rd[$k] = (float) $v[0];
            $perd[$k] = (float) $v[1];
            $pord[$k] = (float) $v[2];
        }

        /* Treatment Data */
        $acid_con = $datos->acid_concentration;
        $qwi = $datos->injection_rate;
        $pt_rad = $datos->invasion_radius;

        /* Minerals Data */
        /* --------- Rock composition */
        $rock_composition_data = json_decode($datos->excel_rock_composition_input);
        $count_rc = count($rock_composition_data);

        $mfor_wt_m = new SplFixedArray($count_rc);
        $mfor_den_m = new SplFixedArray($count_rc);

        foreach ($rock_composition_data as $k => $v) {
            $mfor_wt_m[$k] = (float) $v[1];
            $mfor_den_m[$k] = (float) $v[2];
        }

        /* --------- Choose Minerals */ 
        $check_minerals_data = explode(',',$datos->check_minerals_input);
        $count_cm = count($check_minerals_data);

        $mfor_name = new SplFixedArray($count_cm);
        $mfor_fami = new SplFixedArray($count_cm);
        $mfor_form = new SplFixedArray($count_cm);
        $mfor_wt = new SplFixedArray($count_cm);
        $mfor_mw = new SplFixedArray($count_cm);
        $mfor_den = new SplFixedArray($count_cm);
        $mfor_beta = new SplFixedArray($count_cm);
        $mfor_ent = new SplFixedArray($count_cm);
        $mfor_ener = new SplFixedArray($count_cm);
        $mfor_a1 = new SplFixedArray($count_cm);
        $mfor_a2 = new SplFixedArray($count_cm);
        $mfor_a3 = new SplFixedArray($count_cm);
        $mfor_a4 = new SplFixedArray($count_cm);

        if (isset($check_minerals_data[0]) && $check_minerals_data[0] != '') {

            foreach ($check_minerals_data as $k => $v) {
                $mineral = mineral_data::find($v);

                $mfor_name[$k] = $mineral->name;
                $mfor_fami[$k] = $mineral->family;
                $mfor_form[$k] = $mineral->formula;
                $mfor_wt[$k] = $mineral->wt;
                $mfor_mw[$k] = $mineral->mw;
                $mfor_den[$k] = $mineral->density;
                $mfor_beta[$k] = $mineral->dissolution_power; /* Preguntar si este si es igual a BETA */
                $mfor_ent[$k] = $mineral->enthalpy;
                $mfor_ener[$k] = $mineral->free_energy;
                $mfor_a1[$k] = $mineral->a1;
                $mfor_a2[$k] = $mineral->a2;
                $mfor_a3[$k] = $mineral->a3;
                $mfor_a4[$k] = $mineral->a4;
            }
        }

        /* Se termina la recolección de información */

        /* Cálculo de volumen de acido por mineral */
        /* Cálculo de volumen poroso a saturar dañado y sin dañar */
        $vol_por = 0;
        $vol_por_ini = 0;
        
        $vp = new SplFixedArray($count_dd);
        $vpi = new SplFixedArray($count_dd);
        $vol_fine = new SplFixedArray($count_dd);
        $Pi = Pi();
        
        for ($i = 0; $i < $count_dd ; $i++) { 
            $a_p = $pord[$i];
            $a_rd = pow($rd[$i],2);
            
            if($i == 0) {
                $pod = pow(($crw / 12),2);

                $vp[$i] = (float) $Pi * ($a_rd - $pod) * $a_p;
                $vpi[$i] = (float) $Pi * ($a_rd - $pod) * $por;
            } else {
                $pod = pow($rd[$i - 1],2);

                $vp[$i] = (float) $Pi * ($a_rd - $pod) * $a_p;
                $vpi[$i] = (float) $Pi * ($a_rd - $pod) * $por;
            }

            if ($rd[$i] < $pt_rad) {
                $vol_por = (float) $vol_por + $vp[$i];
                $vol_por_ini = (float) $vol_por_ini + $vpi[$i];
            }
            $vol_fine[$i] = (float) $vpi[$i] - $vp[$i];
        }

        /* Densidad promedio de finos en el daño */
        $den_fine = 0;
        for ($i = 0; $i < $count_cm; $i++) { 
            $den_fine = (float) $den_fine + ($mfor_den[$i] * ($mfor_wt[$i] / 100)) * 62.4;
        }

        /* Densidad promedio de finos en la formación */
        $den_sol = 0;
        for ($i = 0; $i < $count_rc; $i++) { 
            $den_sol = (float) $den_sol + (($mfor_wt_m[$i] / 100) * $mfor_den_m[$i]) * 62.4;
        }

        /* Cálculo del volumen a través del área superficial */
        $Ag = new SplFixedArray($count_dd);
        $vola_fine = new SplFixedArray($count_dd);
        for ($i = 0; $i < $count_dd; $i++) { 
            $Ag[$i] = (float) ($vol_fine[$i] * 4.23 * (1 - $pord[$i])) / (0.0000328084);
            $vola_fine[$i] = (float) $Ag[$i] * 0.0000328084 * 0.1;
        }

        /* masa de finos acumulada en cada celda  */
        $mass_fine = new SplFixedArray($count_dd);
        for ($i = 0; $i < $count_dd; $i++) {
            if ($rd[$i] < $pt_rad) {
                $mass_fine[$i] = (float) $vola_fine[$i] * $den_fine;
            }
        }

        /* Formulación del acido "densidad" */
        $acid_den = (float) (1 * (1 - ($acid_con / 100)) + (1.15 * ($acid_con / 100))) * 62.4;

        /* Corrección epsilon */
        /* $temp = ($temp - 32) * (5# / 9#) + 273.15 */
        $epsilon = new SplFixedArray($count_cm);
        $temp = (float) ($temp - 32) * (5 / 9) + 273.15;
        $epsilon = $this->Equilibrio($count_cm, $temp, $mfor_a1, $mfor_a2, $mfor_a3, $mfor_a4, $epsilon);

        /* Disolución del mineral por cada sección */
        $acid_vol = new SplFixedArray($count_dd);

        $x_am = new SplFixedArray($count_cm);
        $mfor_vol = new SplFixedArray($count_cm);
        $acid_volt = 0;

        for ($j = 0; $j < $count_dd; $j++) { 
            $sum_xam = 0;
            $sum_vol = 0;
            $sum_eps = 0;

            for ($i = 0; $i < $count_cm; $i++) { 
                /* masa de finos por cada celda por mineral  'lbm */
                $mfor_vol[$i] = (float) ($mass_fine[$j] * ($mfor_wt[$i] / 100)) / $mfor_den[$i];

                /* Cantidad que se puede disolver por mineral 'ft3 X/ft3 HF */
                $x_am[$i] = (float) ($mfor_beta[$i]) * ($acid_con / 100) * ($acid_den / $mfor_den[$i]);

                /* Volumen de disolución para cada mineral */
                $sum_xam = (float) $sum_xam + $x_am[$i];

                /* Cantidad que se puede tener de acido para todos los minerales */
                $sum_vol = (float) $sum_vol + $mfor_vol[$i];

                /* Grado de avance general */
                $sum_eps = (float) $sum_eps + $mfor_wt[$i] * ($epsilon[$i] / 100);
            }

            $acid_vol[$j] = (float) $acid_vol[$j] + (($sum_vol / $sum_xam) / $sum_eps);
            $acid_volt = (float) $acid_volt + $acid_vol[$j];
        }

        /* Resultados */
        /* Acid volume for Fines */ $Acid_volume_for_fines = ($acid_volt * 7.48052) * $h;
        /* Acid volume for Volume Porous */ $Acid_volume_for_volume_porous = ($vol_por * 7.48052) * $h;
        /* volume inicial sin finos */ $volume_inicial_sin_finos = ($vol_por_ini * 7.48052) * $h;
        /* Total solution volume */ $Total_solution_volume = ((($vol_por + $acid_volt) * 7.48052) * $h) * 0.023809524;

        /* Variable de ingreso manuales */
        $dt = 10; /* Delta de tiempo */
        $tmax = 8000; /* Tiempo maximo */
        $t = 0; /* Tiempo inicial */
        $nm = 100; /* Numero de bloques en el sistema */

        /* Conversión de unidades */
        $h = $h * 30.45;
        $crw = $crw * 2.54; /* (in to cm) */
        $cr = $cr * (1 / 14.7); /* (1/psi to 1/atm) */
        $dt = $dt * 60; /* (min to sec) */
        $rad = $rad * 30.48;
        $tmax = $tmax * 60; /* (min to sec) */
        $pinit = $pinit / 14.7; /* (psi to atm) */
        $qwi = -$qwi * 63.0902; /* GAL/MIN to (cm3 / s) inyeccion */
        
        for ($i = 0; $i < $count_dd; $i++) { 
            $rd[$i] = $rd[$i] * 30.48; /* (ft to cm) */
            $perd[$i] = $perd[$i] / 1000; /* mD to D */
        }

        /* Interpolando presiones, saturaciones y porosidades */ 
        $R = new SplFixedArray($nm);
        $dr1 = new SplFixedArray($nm - 1);
        $r1 = new SplFixedArray($nm);
        $Dr = new SplFixedArray($nm);

        $trunk = 0.8;
        $Ri = 0;
        $alfa = pow((1.5 * $rad / $crw),(1 / ($nm - 1)));
        $R[0] = $crw;

        for ($i = 1; $i < $nm; $i++) { 
            $R[$i] = (float) $alfa * $R[$i - 1];
        }

        for ($i = 0; $i < ($nm - 1); $i++) { 
            $dr1[$i] = (float) $R[$i + 1] - $R[$i];
        }

        for ($i = 0; $i < $nm; $i++) { 
            if ($i == $nm -1 ) {
                $r1[$i] = (float) 1.5 * $rad;
            } else {
                $r1[$i] = (float) (($alfa - 1) * $R[$i]) / (Log($alfa));
                if ($r1[$i] < $Ri) { $x = (float) $i; }
            }
        }

        for ($i = 0; $i < $nm; $i++) { 
            if ($i == 0) {
                $Dr[$i] = (float) $r1[$i] - $R[$i];
            } else {
                $Dr[$i] = (float) $R[$i] - $R[$i - 1];
            }
        }

        /* Condiciones iniciales */
        $phini = new SplFixedArray($nm);
        $perm = new SplFixedArray($nm);
        $phi = new SplFixedArray($nm);
        $muo = new SplFixedArray($nm);
        $po = new SplFixedArray($nm);
        $ch = new SplFixedArray($nm);
        
        for ($i = $nm -1; $i >= 0; $i--) { 
            $po[$i] = $pinit;
            $phini[$i] = (float) $por;
            $perm[$i] = (float) $this->interp($R[$i], $perm[$i], 0, 0, $count_dd, $rd, $perd);
            $phi[$i] = (float) $this->interp($R[$i], $phi[$i], 0, 0, $count_dd, $rd, $pord);
            $muo[$i] = (float) $muot;
        }

        /* Para las condiciones iniciales */
        for ($i = 0; $i < $nm; $i++) { 
            $ch[$i] = (float) 0;
            $perm[$i] = (float) $this->interp($R[$i], $perm[$i], 0, 0, $count_dd, $rd, $perd);
            $phi[$i] = (float) $this->interp($R[$i], $phi[$i], 0, 0, $count_dd, $rd, $pord);
        }

        $ch[0] = $acid_con;

        $A = new SplFixedArray($nm);
        $B = new SplFixedArray($nm);
        $C = new SplFixedArray($nm);
        $D = new SplFixedArray($nm);
        $F = new SplFixedArray($nm);
        $dp = new SplFixedArray($nm);
        $u = new SplFixedArray($nm);

        $nda = new SplFixedArray($nm);
        $nac = new SplFixedArray($nm);
        $CM = new SplFixedArray($nm);

        $conacid = new SplFixedArray($nm);
        $Ld = new SplFixedArray($nm);
        
        foreach ($nda as $k => $v) {
            $nda[$k] = new SplFixedArray($count_cm);
            $nac[$k] = new SplFixedArray($count_cm);
            $CM[$k] = new SplFixedArray($count_cm);
        }

        $R_mass = new SplFixedArray($nm);
        $R_fine = new SplFixedArray($nm);

        $kappa = new SplFixedArray($nm);
        $xam = new SplFixedArray($nm);
        $R_acid = new SplFixedArray($nm);

        $qq = new SplFixedArray($nm);
        $gg = new SplFixedArray($nm);
        $w = new SplFixedArray($nm);

        $phik = new SplFixedArray($nm);
        $ponew = new SplFixedArray($nm);
        $permm = new SplFixedArray($nm);
        $phii= new SplFixedArray($nm);

        /* NO SE */
        for ($j = 0; $j < 9999; $j++) {
            $t = $t + $dt;

            /* Control del tiempo de acido bajo investigacion */
            $control = (float) $CTR * ($j + 1) * 0.0001;

            if ($t > $tmax) {
                /* GoTo L101 */

                for ($i = $nm -1; $i >= 0; $i--) {
                    $permm[$i] = (float) $this->interp($R[$i], $perm[$i], 0, 0, $count_dd, $rd, $perd);
                    $phii[$i] = (float) $this->interp($R[$i], $phi[$i], 0, 0, $count_dd, $rd, $pord);
                }

                for ($i=0; $i < count($perm); $i++) { 
                    $perm[$i] = $perm[$i]*1000;
                }

                $data_phi_ant = [];
                $data_perm_ant = [];
                for ($i = 0; $i < $count_dd; $i++) { 
                    $radius_ant = $rd[$i] / 30.48; /* Radius */
                    $permeability_ant = $perd[$i] * 1000; /* Permeabilidad */
                    $phi_ant = $pord[$i]; /* PHI */

                    $data_phi_ant[] = [$radius_ant,$phi_ant];
                    $data_perm_ant[] = [$radius_ant,$permeability_ant];
                }

                $data_acid_test = [];
                $data_phi = [];
                $data_perm = [];
                for ($i = 0; $i < $nm; $i++) {
                    $data = [];
                    $radio = (float) number_format(($R[$i] / 30.48),4,'.','.');
                    $phi_ = (float) number_format($phi[$i],4,'.','.');
                    $perm_ = (float) number_format($perm[$i] * 1000,4,'.','.');

                    $data[] = (float) $R[$i];
                    $data[] = (float) $ponew[$i] * 14.7;
                    $data[] = (float) $phi[$i];
                    $data[] = (float) $permm[$i] * 1000;

                    $data_phi[] = [$radio,$phi_];
                    $data_perm[] = [$radio,$perm_];

                    $data_acid_test[] = $data;
                }

                // dd($data_acid_test);

                /* calculo de skin antes y despues de estimulacion */
                /* Numero de datos de permeabilidad (mD), porosidad (fracción) y volumen de finos en el daño (ft3) */
                $crw = (float) $crw / (2.54 * 12);
                $rad = (float) $rad / 30.48;
                
                $mj = $count_dd;
                $kj = new SplFixedArray($mj);
                $Sj = new SplFixedArray($mj);
                for ($i = 0; $i < $mj; $i++) { 
                    $kj[$i] = (float) $perd[$i] * 1000;
                    $Sj[$i] = (float) (($per / $kj[$i]) - 1) * Log($rad / $crw);
                }

                $mj = $nm;
                $k2j = new SplFixedArray($mj);
                $S2j = new SplFixedArray($mj);
                /* Para el Skin luego de la estimulacion */                
                for ($i = 0; $i < $mj; $i++) { 
                    $k2j[$i] = $data_acid_test[$i][3];
                    if ($i > 1 & $k2j[$i] < $k2j[$i]) {
                        $kmin = (float) $k2j[$i];
                        $smin = (float) $S2j[$i];
                    }
                    $S2j[$i] = (float) (($per / $k2j[$i]) - 1) * Log($rad / $crw);
                }

                return [
                    'total_sv' => round($Total_solution_volume,2), 
                    'data_phi' => json_encode($data_phi), 
                    'data_perm' => json_encode($data_perm),
                    'data_phi_ant' => json_encode($data_phi_ant), 
                    'data_perm_ant' => json_encode($data_perm_ant),
                ];

            }

            /* Coeficientes matriz tridiagonal */
            $i = 0;
            do {
                $i = $i + 1;
                $B[$i] = (float) pow($r1[$i - 1], $trunk) / (pow($R[$i],$trunk) * $Dr[$i] * $dr1[$i - 1]);
            } while ($i == $x + 1);

            for ($i = $x + 1; $i < $nm; $i++) { 
                $B[$i] = (float) $r1[$i - 1] / ($R[$i] * $Dr[$i] * $dr1[$i - 1]);
            }

            if ($x == 0) {
                $x = 1;
            }

            $i = 0;
            do {
                $A[$i] = pow($r1[$i],$trunk) / (pow($R[$i],$trunk) * $Dr[$i] * $dr1[$i]);
                $i = $i + 1;
            } while ($i == $x - 1);

            for ($i = $x -1; $i < $nm -1; $i++) { 
                $A[$i] = (float) $r1[$i] / ($R[$i] * $Dr[$i] * $dr1[$i]);
            }

            $i = 0;
            while ($i == $x) {
                $G1 = (float) $trunk * $phi[$i] * $cr / $perm[$i];
                $F[$i] = (float) $G1 * $muo[$i] / $dt;  /* G1 * Uapp(i) / dt */
                $i = $i + 1;
            }

            for ($i = $x; $i < $nm - 1; $i++) { 
                $G2 = (float) 1000 * $phi[$i] * $cr * $UN / $perm[$i];
                $F[$i] = $G2 / $dt;
            }

            if ($Ri > 0 And $Ri < $rad) {
                $B[$x] = (float) $dr1[$x] / $dr1[$x - 1];
                $A[$x] = (float) $muo[$i] / (1000 * UN);    /* Uapp(x) / UN */
                $F[$x] = (float) 0;
            }

            $C[0] = (float) -($A[0] + $F[0]);
            $C[$nm-1] = (float) -($B[$nm-1] + $F[$nm-1]);

            for ($i = 1; $i < $nm - 1; $i++) { 
                $C[$i] = (float) -($A[$i] + $B[$i] + $F[$i]);
            }

            for ($i = 0; $i < $nm -1; $i++) { 
                if ($i == 0) {
                    $vm = $Pi * $h * (pow($r1[0],2) - pow($crw,2));
                    $D[$i] = (float) -$F[$i] * $po[$i] + ($qwi / ($perm[$i] * $vm)) * $muo[$i];
                } else {
                    $D[$i] = (float) -$F[$i] * $po[$i];
                }
            }
            $qq[0] = (float) $A[0] / $C[0];
            $gg[0] = (float) $D[0] / $C[0];

            for ($i = 1; $i < $nm; $i++) { 
                $w[$i] = (float) $C[$i] - ($B[$i] * $qq[$i - 1]);
                $gg[$i] = (float) ($D[$i] - ($B[$i] * $gg[$i - 1])) / $w[$i];
                $qq[$i] = (float) $A[$i] / $w[$i];
            }            

            $ponew[$nm - 1] = $gg[$nm-1];
            for ($i = $nm - 2; $i >= 0 ; $i--) { 
                $ponew[$i] = (float) ($gg[$i] - ($qq[$i] * $ponew[$i + 1]));
            }

            /* delta de presion  ! (atm/cm) */
            for ($i = 0; $i < $nm; $i++) { 
                if ($i == 0) {
                    $dp[$i] = (float) -(4 * $ponew[1] - $ponew[2] - 3 * $ponew[0]) / (2 * $Dr[0]);
                } elseif ($i == ($nm - 1)) {
                    $dp[$i] = (float) $dp[$i - 1] - 0.0000001;
                } else {
                    $dp[$i] = (float) ($ponew[$i - 1] - $ponew[$i]) / $Dr[$i];
                }
            }


            /* velocidad del fluido  (cm/s) */
            for ($i = 0; $i < $nm; $i++) { 
                $u[$i] = (float) $perm[$i] * $dp[$i] * $h / $muo[$i];
            }

            /* Concentracion acido adimensional */
            for ($i = 1; $i < $nm; $i++) { 
                $ch[$i] = (float) $acid_con * ($u[$i] / $u[1]);
            }


            /* masa de finos acumulada en cada celda  'lbm */
            for ($i = 0; $i < $nm; $i++) { 
                $R_mass[$i] = (float) $R_fine[$i] * $den_fine;
            }

            
            /* Calculo del mineral despues del acido en el frente */
            for ($k = 0; $k < $nm; $k++) { 
                $sum_xam = (float) 0;
                $sum_vol = (float) 0;
                $sum_eps = (float) 0;


                for ($i = 0; $i < $count_cm; $i++) { 
                    /* masa de finos por cada celda por mineral  'lbm */
                    $mfor_vol[$i] = (float) ($R_mass[$k] * ($mfor_wt[$i] / 100)) / ($mfor_den[$i]);
                    /* Cantidad que se puede disolver por mineral 'ft3 X/ft3 HF */
                    $x_am[$i] = (float) ($mfor_beta[$i]) * ($ch[$k] / 100) * ($acid_den / $mfor_den[$i]);

                    /* Volumen de disolución para cada mineral */
                    $sum_xam = (float) $sum_xam + $x_am[$i];

                    /* Grado de avance general */
                    // $sum_eps = (float) $sum_eps + $mfor_wt[$i] * $epsilon[$i] / 100;
                    $sum_eps = (float) $sum_eps + (($mfor_wt[$i] * $epsilon[$i]) / 100);
                }

                $xam[$k] = (float) $sum_xam * $sum_eps;
            }

            /* Recuperación del volumen poroso */
            /* Volumen poroso recuperado = Volumen poroso dañado - volumen poroso recuerado */
            /* porosidad como una funcion de la concentracion de los minerales */
            for ($i = 0; $i < $nm; $i++) { 
                if ($R[$i] < ($pt_rad * 30.48)) {
                    $phik[$i] = (float) $xam[$i] * $control;
                } else {
                    $phik[$i] = (float) 0;
                }
            }

            /* Volumen poroso recuperado */
            for ($i = 0; $i < $nm; $i++) { 
                $Ld[$i] = (float) (($por - $phi[$i]) * $phik[$i]) / 100;
            }

            for ($i = 0; $i < $nm; $i++) { 
                $phi[$i] = (float) $phi[$i] + $Ld[$i];
                if ($phi[$i] > ($por)) {
                    $phi[$i] = (float) $por;
                }
            }

            /* cambio de permeabilidad */
            for ($i = 0; $i < $nm; $i++) { 
                if ($R[$i] < $pt_rad * 30.48) {
                    $perm[$i] = (float) ($per / 1000) * (pow(($phi[$i] / $phini[$i]),2.5));
                }
            }
        }

        return ['total_sv' => $Total_solution_volume, 'data_phi' => $data_phi, 'data_perm' => $data_perm];

    }

    private function Equilibrio($n, $t, $a1, $a2, $a3, $a4, $epsilon)
    {
        for ($i = 0; $i < $n; $i++) { 
            $epsilon[$i] = (float) ($a1[$i] * pow($t,3)) + ($a2[$i] * pow($t,2)) + ($a3[$i] * $t) + $a4[$i];
        }

        for ($i = 0; $i < $n; $i++) { 
            if ($epsilon[$i] < 0.5) { $epsilon[$i] = (float) 0.5; }  /* Criterio en intestigación */
            if ($epsilon[$i] > 1.5) { $epsilon[$i] = (float) 1.5; }  /* Criterio en intestigación */
            if ($a1[$i] == 0) { $epsilon[$i] = (float) 1; }
            if ($a2[$i] == 0) { $epsilon[$i] = (float) 1; }
            if ($a3[$i] == 0) { $epsilon[$i] = (float) 1; }
            if ($a4[$i] == 0) { $epsilon[$i] = (float) 1; }
        }

        return $epsilon;
    }

    /* Revisar interpolaridad */
    private function interp($x, $y, $dy, $isw, $n, $xt, $yt)
    {
        $n = $n - 2;

        if ($x < $xt[$n]) { 
            if ($x > $xt[0]) { 
                for ($i = 1; $i < $n; $i++) {

                    if ($x > $xt[$i]) { 
                        continue; 
                    }

                    $y = $yt[$i - 1] + ($x - $xt[$i - 1]) * ($yt[$i] - $yt[$i - 1]) / ($xt[$i] - $xt[$i - 1]);
                    if ($isw != 0) { 
                        $dy = ($yt[$i] - $yt[$i - 1]) / ($xt[$i] - $xt[$i - 1]);
                    }
                    return $y;
                }
            }

            $y = $yt[1];
            if ($isw != 0) {
                $dy = ($yt[1] - $yt[0]) / ($xt[1] - $xt[0]);
            }
            return $y;
        }

        $y = $yt[$n];
        if ($isw != 0) {
            $dy = ($yt[$n] - $yt[$n - 1]) / ($xt[$n] - $xt[$n - 1]);
        }

        return $y;
    }

    public function getImportExternalTree(Request $request)
    {
        $user_id = Auth::id();
        $user = User::where('id', '=', $user_id)->first();
        $rol = $user->office;
        $type = $request->type;

        if($rol == 0){
            $company_tree = company::select('company.id', 'company.name')
            ->join('proyectos', 'proyectos.compania', '=', 'company.id')
            ->join('escenarios', 'escenarios.proyecto_id', '=', 'proyectos.id')
            ->raw('COUNT(escenarios.id) > 0')
            ->where('escenarios.estado','=',1)
            ->where('escenarios.tipo', '=', $type)
            ->distinct()
            ->get();

            $tree = [];
            foreach ($company_tree as $company) {
                $company['icon'] = url('images/icon-company.png');
                $company['href'] = '';
                $projects = proyecto::select('proyectos.id as id', 'proyectos.nombre as name')
                ->join('users', 'users.id', '=', 'proyectos.usuario_id')
                ->join('escenarios', 'escenarios.proyecto_id', '=', 'proyectos.id')
                ->raw('COUNT(escenarios.id) > 0')
                ->where('escenarios.estado','=',1)
                ->where('escenarios.tipo', '=', $type)
                ->where('proyectos.compania',"=", $company->id)
                ->distinct()
                ->get();
                $this->add_simulations_to_projects($projects, $type);

                $company['child'] = $projects;
            }
            $tree = $company_tree;
        } else {
            $projects = proyecto::select('proyectos.id', 'proyectos.nombre', 'proyectos.compania')
            ->join('escenarios', 'escenarios.proyecto_id', '=', 'proyectos.id')
            ->raw('COUNT(escenarios.id) > 0')
            ->where('escenarios.estado','=',1)
            ->where('escenarios.tipo', '=', $type)
            ->where('proyectos.compania',"=", $user->company)
            ->distinct()
            ->get();
            $this->add_simulations_to_projects($projects, $type);

            $tree = $projects;
        }

        return Response()->json($tree);
    }

    private function add_simulations_to_projects($project_tree, $type)
    {
        $group = [];
        foreach ($project_tree as $project) {
            $project['name'] = $project->name;
            $project['icon'] = url('images/icon-folder.png');
            $project['href'] = '';

            $wells = pozo::join('escenarios', 'escenarios.pozo_id', '=', 'pozos.id')
            ->where('escenarios.proyecto_id', '=', $project->id)
            ->where('escenarios.estado','=',1)
            ->where('escenarios.tipo', '=', $type)
            ->select('pozos.id as id', 'pozos.nombre as name')
            ->raw('COUNT(escenarios.id) > 0')
            ->distinct()
            ->get();

            foreach ($wells as $well) {
                $well['icon'] = url('images/icon-well.png');
                $well['href'] = '';
                $well['id'] = $well['id'];
                $well['name'] = $well['name'];

                $scenary = escenario::where('pozo_id', '=', $well->id)
                ->where('proyecto_id', '=', $project->id)
                ->where('escenarios.estado','=',1)
                ->where('escenarios.tipo', '=', $type)
                ->select('id', 'nombre')
                ->get();

                foreach ($scenary as $sce) {
                    $sce['icon'] = url('images/icon-scenario.png');
                    $sce['href'] = url('#link_external');
                    $sce['id'] = $sce['id'];
                    $sce['name'] = $sce['nombre'];
                    $sce['child'] = [];
                }
                $well['child'] = $scenary;
            }
            $project['child'] = $wells;
        }
    }

    public function getTreeExternalData($id)
    {
        $fdd = fines_d_diagnosis::where('scenario_id','=',$id)->orderBy('created_at','DESC')->first();
        if (!$fdd || empty($fdd)) {
            return json_encode([]);
        }

        $fdds = fines_d_diagnosis_results::where('fines_d_diagnosis_id','=',$fdd->id)->orderBy('date','DESC')->take(100)->get();
        if(!$fdds || empty($fdds) || $fdds->count() == 0) {
            return json_encode([]);
        }

        $arreglo = [];
        foreach ($fdds as $v) {
            $arreglo[] = [$v->radius,$v->permeability,$v->porosity];
        }

        return json_encode($arreglo);
    }

}
