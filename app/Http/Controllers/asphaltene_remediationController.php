<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use DB;
use App\pozo;
use App\User;
use App\cuenca;
use App\escenario;
use App\formacionxpozo;
use App\asphaltene_treatment;
use App\asphaltene_remediations;

use Carbon\Carbon;
use Validator;

class asphaltene_remediationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($scenaryId)
    {
        $scenary_s = escenario::where('id', $scenaryId)->first();
        $pozo = Pozo::find($scenary_s->pozo_id)->first();
        $intervalo = DB::table('formacionxpozos')->where('id',$scenary_s->formacion_id)->first();
        $formacion = DB::table('formaciones')->where('id',$intervalo->formacion_id)->first();
        $basin = cuenca::where('id',$pozo->cuenca_id)->first();

        $fluido = DB::table('fluidoxpozos')->where('pozo_id', $pozo->id)->first();
        $escenario = DB::table('escenarios')->where('id',$scenary_s->id)->first();
        $campo = DB::table('campos')->where('id', $escenario->campo_id)->first();
        $user = DB::table('users')->join('escenarios','users.id','=','escenarios.user_id')->select('users.fullName')->where('escenarios.id','=',$scenary_s->id)->first();
        $pvt = DB::table('pvt')->where('formacion_id',$formacion->id)->get();
        $advisor = $scenary_s->enable_advisor;
        $asphaltene_treatment = $this->generateOptionsAsphalteneTreatment(asphaltene_treatment::all());


        return View('template.asphaltene_remediation.create', compact(['pozo','formacion','basin','asphaltene_treatment','fluido','escenario','campo','scenary_s','user','intervalo','pvt','advisor']));
    }


    private function generateOptionsAsphalteneTreatment($asphaltene_ops,$seleccionado = null)
    {
        $opciones = "<option value='' data_per=''>Select an option</option>";
        foreach ($asphaltene_ops as $option) {
            $id = $option['id'];
            $name = $option['name'];
            $dilution_capacity = floatval(str_replace('.', ',', $option['dilution_capacity']));
            $components = $option['components'];

            $componentes = explode(',', $components);
            $tabla = '<hr><table class="table table-hover table-bordered">';
            $tabla .= '<thead>';

            $tabla .= '<tr class="success">';
            $tabla .= '<th><strong><center>Dilution Capacity</center></strong></th>';
            $tabla .= "<th><center>$dilution_capacity ppm</center></th>";
            $tabla .= '</tr>';

            $tabla .= '<tr>';
            $tabla .= '<th><strong><center>Component</center></strong></th>';
            $tabla .= '<th><strong><center>% v/v</center></strong></th>';
            $tabla .= '</tr>';

            $tabla .= '</thead>';
            $tabla .= '<tbody>';
            foreach ($componentes as $c) {
                $componentes_ar = explode('|', $c);
                $componente = $componentes_ar[0];
                $porcentaje = floatval($componentes_ar[1]);

                $tabla .= "<tr>";
                $tabla .= "<th><center>$componente</center></th>";
                $tabla .= "<th><center>$porcentaje</center></th>";
                $tabla .= "</tr>";

            }

            $tabla .= '</tbody>';
            $tabla .= '</table>';

            if (!is_null($seleccionado) && $seleccionado == $id) {
                $opciones .=  "<option value='$id' selected class='opt_sel' data_per='$tabla'>$name</option>";
            } else {
                $opciones .=  "<option value='$id' class='opt_sel' data_per='$tabla'>$name</option>";
            }
        }

        return $opciones;
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

        $scenary_s = escenario::find($request->id_scenary);
        $user = User::join('escenarios','users.id','=','escenarios.user_id')->select('users.fullName')->where('escenarios.id','=',$scenary_s->id)->first();

        $asphaltene = new asphaltene_remediations;

        $asphaltene->id_scenary = $request->id_scenary;
        $asphaltene->type_asphaltene = $request->type_asphaltene;
        $asphaltene->initial_porosity = $request->initial_porosity;
        $asphaltene->net_pay = $request->net_pay;
        $asphaltene->water_saturation = $request->water_saturation;
        $asphaltene->initial_permeability = $request->initial_permeability;
        $asphaltene->current_permeability = $request->current_permeability;
        $asphaltene->skin_characterization_scale = $request->skin_characterization_scale;
        $asphaltene->skin_characterization_induced = $request->skin_characterization_induced;
        $asphaltene->skin_characterization_asphaltene = $request->skin_characterization_asphaltene;
        $asphaltene->skin_characterization_fines = $request->skin_characterization_fines;
        $asphaltene->skin_characterization_non_darcy = $request->skin_characterization_non_darcy;
        $asphaltene->skin_characterization_geomechanical = $request->skin_characterization_geomechanical;
        $asphaltene->asphaltene_apparent_density = $request->asphaltene_apparent_density;
        $asphaltene->stimate_ior = $request->stimate_ior_input;
        $asphaltene->monthly_decline_rate = $request->monthly_decline_rate;
        $asphaltene->current_oil_production = $request->current_oil_production;
        $asphaltene->data_input = $request->data_input;
        $asphaltene->chemical_treatment_impl = $request->chemical_treatment_impl;
        $asphaltene->option_treatment = $request->option_treatment;
        $asphaltene->asphaltene_dilution_capacity = $request->asphaltene_dilution_capacity;
        $asphaltene->treatment_radius = $request->treatment_radius;
        $asphaltene->wellbore_radius = $request->wellbore_radius;
        $asphaltene->asphaltene_remotion_efficiency_range_a = $request->asphaltene_remotion_efficiency_range_a;
        $asphaltene->asphaltene_remotion_efficiency_range_b = $request->asphaltene_remotion_efficiency_range_b;
        $asphaltene->excel_changes_along_the_radius = $request->excel_changes_along_the_radius_input;
        if ($request->_button_swr == "1") {
            $asphaltene->status_wr = false;
        } else {
            $asphaltene->status_wr = true;
        }

        $asphaltene->save();

        $scenary = escenario::find($asphaltene->id_scenary);
        $scenary->estado=1;
        $scenary->completo=1;
        $scenary->save();

        return redirect(url('asphalteneremediation/results',$asphaltene->id));
    }

    public function validateData($request,$type,$id = null)
    {
        $validator = Validator::make($request->all(), [
            'id_scenary' => 'required|numeric|min:0',
            'type_asphaltene' => 'required|numeric|min:0',

            /* Sección Reservoir Data - Petropyshic */
            'initial_porosity' => 'required|numeric|regex:/^\s*(?=.*[1-9])\d*(?:\.\d{1,11})?\s*$/',
            'net_pay' => 'required|numeric|regex:/^\s*(?=.*[1-9])\d*(?:\.\d{1,11})?\s*$/',
            'water_saturation' => 'required|numeric|regex:/^\s*(?=.*[1-9])\d*(?:\.\d{1,11})?\s*$/',
            'initial_permeability' => 'required|numeric|regex:/^\s*(?=.*[1-9])\d*(?:\.\d{1,11})?\s*$/',

            /* asphaltene_remediation == Based upon asphaltene diagnosis model */
            'excel_changes_along_the_radius_input' => 'required_if:type_asphaltene,==,1',

            /* asphaltene_remediation == Volumetric changes */
            'current_permeability' => 'required_if:type_asphaltene,==,2|numeric|regex:/^\s*(?=.*[1-9])\d*(?:\.\d{1,11})?\s*$/',

            /* Sección Reservoir Data - Skin Characterization */
            'skin_characterization_scale' => 'required_if:type_asphaltene,==,2|numeric|regex:/^\s*(?=.*[1-9])\d*(?:\.\d{1,11})?\s*$/',
            'skin_characterization_induced' => 'required_if:type_asphaltene,==,2|numeric|regex:/^\s*(?=.*[1-9])\d*(?:\.\d{1,11})?\s*$/',
            'skin_characterization_asphaltene' => 'required_if:type_asphaltene,==,2|numeric|regex:/^\s*(?=.*[1-9])\d*(?:\.\d{1,11})?\s*$/',
            'skin_characterization_fines' => 'required_if:type_asphaltene,==,2|numeric|regex:/^\s*(?=.*[1-9])\d*(?:\.\d{1,11})?\s*$/',
            'skin_characterization_non_darcy' => 'numeric|regex:/^\s*(?=.*[1-9])\d*(?:\.\d{1,11})?\s*$/',
            'skin_characterization_geomechanical' => 'numeric|regex:/^\s*(?=.*[1-9])\d*(?:\.\d{1,11})?\s*$/',

            /* Sección Asphaltene Data*/
            'asphaltene_apparent_density' => 'required|numeric|regex:/^\s*(?=.*[1-9])\d*(?:\.\d{1,11})?\s*$/',

            /* Sección Treatment Data */
            /* chemical_treatment_impl */
            /* SI*/ 'asphaltene_dilution_capacity' => 'required_if:chemical_treatment_impl,==,yes|numeric|regex:/^\s*(?=.*[1-9])\d*(?:\.\d{1,11})?\s*$/',
            /* NO */ 'option_treatment' => 'required_if:chemical_treatment_impl,==,no',
            /* chemical_treatment_impl */
            'treatment_radius' => 'required|numeric|regex:/^\s*(?=.*[1-9])\d*(?:\.\d{1,11})?\s*$/',
            'wellbore_radius' => 'required|numeric|regex:/^\s*(?=.*[1-9])\d*(?:\.\d{1,11})?\s*$/',
            'asphaltene_remotion_efficiency_range_a' => 'required|numeric|regex:/^\s*(?=.*[1-9])\d*(?:\.\d{1,11})?\s*$/',
            'asphaltene_remotion_efficiency_range_b' => 'required|numeric|regex:/^\s*(?=.*[1-9])\d*(?:\.\d{1,11})?\s*$/',

            /* Sección Treatment Reward */
            'monthly_decline_rate' => 'required_if:stimate_ior_input,==,on|numeric|regex:/^\s*(?=.*[1-9])\d*(?:\.\d{1,11})?\s*$/',
            'current_oil_production' => 'required_if:stimate_ior_input,==,on|numeric|regex:/^\s*(?=.*[1-9])\d*(?:\.\d{1,11})?\s*$/',
            'data_input' => 'required_if:stimate_ior_input,==,on'
        ])->setAttributeNames(array(
            'initial_porosity' => 'Initial Porosity',
            'net_pay' => 'Net Pay',
            'water_saturation' => 'Water Saturation',
            'initial_permeability' => 'Initial Permeability',
            'current_permeability' => 'Current Permeability',
            'excel_changes_along_the_radius_input' => 'Table Changes Along The Radius on Reservoir Data',
            'skin_characterization_scale' => 'Scale',
            'skin_characterization_induced' => 'Induced',
            'skin_characterization_asphaltene' => 'Asphaltene',
            'skin_characterization_fines' => 'Fines',
            'skin_characterization_non_darcy' => 'Non Darcy',
            'skin_characterization_geomechanical' => 'Geomechanical',
            'asphaltene_apparent_density' => 'Asphaltene Apparent Density',
            'asphaltene_dilution_capacity' => 'Asphaltene Dilution Capacity',
            'option_treatment' => 'Select one option treatment',
            'treatment_radius' => 'Treatment Radius',
            'wellbore_radius' => 'Wellbore Radius',
            'asphaltene_remotion_efficiency_range_a' => 'Asphaltene Remotion Efficiency Range - Start Range',
            'asphaltene_remotion_efficiency_range_b' => 'Asphaltene Remotion Efficiency Range - End Range',
            'monthly_decline_rate' => 'Monthly Decline Rate',
            'current_oil_production' => 'Current Oil Production',
            'data_input' => 'Date'
        ));

        if ($type == 'store') {
            if ($validator->fails()) {
                return redirect('asphalteneremediation/'.$request->id_scenary)
                ->withErrors($validator)
                ->withInput();
            }
        } else {
            if ($validator->fails()) {
                return redirect('asphalteneremediation/edit/'.$id)
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
        $asphaltene = $this->validateId($id);
        if(!$asphaltene){
            return redirect(url('share_scenario'));
        }

        $scenary = escenario::find($asphaltene->id_scenary);
        $data = asphaltene_remediations::results($scenary);

        if ($asphaltene->status_wr && ($data[8]->count() == 0 && $data[5]->count() == 0 && count($data[2][0]) == 0)) {
            $data = 'incomplete';
        }

        return view('template.asphaltene_remediation.results', compact('asphaltene','scenary', 'data'));
    }

    /* Esta función se encarga de validar que el id que llega sea de escenario o directamente de asphaltene remediation */
    private function validateId($id)
    {
        $asphaltene = asphaltene_remediations::find($id);
        $scenario = escenario::find($id);

        if ($asphaltene) {

            return $asphaltene;

        } else if (!$asphaltene && $scenario) {

            $asphal_new = asphaltene_remediations::where('id_scenary',$scenario->id)->first();
            if (!$asphal_new) {
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
        $asphaltene = $this->validateId($id);

        if(!$asphaltene) {
            return redirect(url('share_scenario'));
        }


        $scenary_s = escenario::find($asphaltene->id_scenary);
        $user = User::join('escenarios','users.id','=','escenarios.user_id')->select('users.fullName')->where('escenarios.id','=',$scenary_s->id)->first();
        $asphaltene_treatment = $this->generateOptionsAsphalteneTreatment(asphaltene_treatment::all(),$asphaltene->option_treatment);

        $pozo = Pozo::find($scenary_s->pozo_id)->first();
        $basin = cuenca::where('id',$pozo->cuenca_id)->first();
        $formacion = formacionxpozo::where('pozo_id', $pozo->id)->first();
        $campo = DB::table('campos')->where('id', $scenary_s->campo_id)->first();
        $advisor = $scenary_s->enable_advisor;

        /* Formato para la fecha */
        $asphaltene->data_input = Carbon::parse($asphaltene->data_input)->format('Y/m/d');

        /* Formato para la tabla*/
        $asphaltene->excel_changes_along_the_radius = ($asphaltene->excel_changes_along_the_radius) ? json_encode($asphaltene->excel_changes_along_the_radius) : '{}';


        foreach ($asphaltene->toArray() as $key => $value) {
            if ($value == 0 && gettype($value) == 'double') {
                $asphaltene->$key = null;
            } else {
                $asphaltene->$key = $value;
            }
        }

        $duplicateFrom = isset($_SESSION['scenary_id_dup']) ? $_SESSION['scenary_id_dup'] : null;

        return view('template.asphaltene_remediation.edit', compact('asphaltene', 'scenary_s','user','advisor','asphaltene_treatment','pozo','basin','formacion','campo','duplicateFrom'));
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
            $asphaltene = new asphaltene_remediations();
        } else {
            $asphaltene = asphaltene_remediations::find($id);
        }
        $scenary_s = escenario::find($request->id_scenary);

        $user = User::join('escenarios','users.id','=','escenarios.user_id')->select('users.fullName')->where('escenarios.id','=',$scenary_s->id)->first();

        $asphaltene->id_scenary = $request->id_scenary;
        $asphaltene->type_asphaltene = $request->type_asphaltene;
        $asphaltene->initial_porosity = $request->initial_porosity;
        $asphaltene->net_pay = $request->net_pay;
        $asphaltene->water_saturation = $request->water_saturation;
        $asphaltene->initial_permeability = $request->initial_permeability;
        $asphaltene->current_permeability = $request->current_permeability;
        $asphaltene->skin_characterization_scale = $request->skin_characterization_scale;
        $asphaltene->skin_characterization_induced = $request->skin_characterization_induced;
        $asphaltene->skin_characterization_asphaltene = $request->skin_characterization_asphaltene;
        $asphaltene->skin_characterization_fines = $request->skin_characterization_fines;
        $asphaltene->skin_characterization_non_darcy = $request->skin_characterization_non_darcy;
        $asphaltene->skin_characterization_geomechanical = $request->skin_characterization_geomechanical;
        $asphaltene->asphaltene_apparent_density = $request->asphaltene_apparent_density;
        $asphaltene->stimate_ior = $request->stimate_ior_input;
        $asphaltene->monthly_decline_rate = $request->monthly_decline_rate;
        $asphaltene->current_oil_production = $request->current_oil_production;
        $asphaltene->data_input = $request->data_input;
        $asphaltene->chemical_treatment_impl = $request->chemical_treatment_impl;
        $asphaltene->option_treatment = $request->option_treatment;
        $asphaltene->asphaltene_dilution_capacity = $request->asphaltene_dilution_capacity;
        $asphaltene->treatment_radius = $request->treatment_radius;
        $asphaltene->wellbore_radius = $request->wellbore_radius;
        $asphaltene->asphaltene_remotion_efficiency_range_a = $request->asphaltene_remotion_efficiency_range_a;
        $asphaltene->asphaltene_remotion_efficiency_range_b = $request->asphaltene_remotion_efficiency_range_b;
        $asphaltene->excel_changes_along_the_radius = $request->excel_changes_along_the_radius_input;
        if ($request->_button_swr == "1") {
            $asphaltene->status_wr = false;
        } else {
            $asphaltene->status_wr = true;
        }

        $asphaltene->save();

        $scenary = escenario::find($request->id_scenary);
        $scenary->estado=1;
        $scenary->completo=1;
        $scenary->save();

        unset($_SESSION['scenary_id_dup']);

        return redirect(url('asphalteneremediation/results',$asphaltene->id));
    }
}
