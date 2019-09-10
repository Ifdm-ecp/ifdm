<?php
namespace App\Traits;

use App\Models\MultiparametricAnalysis\Statistical;
use App\subparameters_weight;
use Session;
use Validator;

trait StatisticalTrait {


    public function storeStatistical($request)
    {
        $input = $request->all();
        /* se modifica el array del campo field_statistical con implode */
        if($request->field_statistical) {
            $input['field_statistical'] = implode(",",$request->field_statistical);
        }

        if($request->statistical == null) {
            $validacion = $this->validacion($request);
            $this->validate($request, $validacion['rules'], $validacion['messages']);
        }

        /* se pasa la variable calculate al funcion edit */
        Session::flash('calculate', $request->calculate);

        /* se ingresa los datos de la tabla statistical */
        $statistical = Statistical::create($input);

        /* se guarda el parametro en la tabla subparameters_weight */
        subparameters_weight::create(['multiparametric_id' => $statistical->id]);

        return $statistical;
    }

    public function editStatistical($id)
    {
        /* se destruyen todos los campos de calculate */
        $this->sessionParametros(null, 'false');

        /* se trae todos los datos de la tabla statistical con el id = $id */
        $statistical = Statistical::find($id);
        if(!$statistical) {
            $statistical = Statistical::where('escenario_id', $id)->first();
            if (!$statistical) {
                abort('404');
            }
        }

        /* se convierten  los datos autoriazados por bloques de string a arrays */
        $statistical->msAvailable = $this->available($statistical->msAvailable);
        $statistical->fbAvailable = $this->available($statistical->fbAvailable);
        $statistical->osAvailable = $this->available($statistical->osAvailable);
        $statistical->rpAvailable = $this->available($statistical->rpAvailable);
        $statistical->idAvailable = $this->available($statistical->idAvailable);
        $statistical->gdAvailable = $this->available($statistical->gdAvailable);

        if(Session::get('calculate') == 'true') {
            $data = $this->sessionParametros($statistical, 'true');        
        }

        return $statistical;
    }



    public function updateStatistical($request, $id)
    {
        if($request->calculate == 'true') {

            /* se valida los campos statistical, basin_statistical, field_statistical, de que los tres no lleguen vacios */
            if($request->statistical == $id || $request->statistical == null) {
                $validacion = $this->validacion($request);
                $this->validate($request, $validacion['rules'], $validacion['messages']);
            }

            $statistical = Statistical::updateCampos($request, $id);
            /* se pasa la variable calculate al funcion edit */
            Session::flash('calculate', $request->calculate);

            /* se redirecciona a la vista edit de statistical */
            return collect(['opcion' => 'campos', 'statistical' => $statistical]);
        } else {
            /* se actualizan todos los campos */
            $statistical = Statistical::updateTodos($request, $id);
            /* ingresa los datos en la tabla subparameters_weight */
            
            $inputs = $request->all();
            $inputs = array_merge($inputs, ['status_wr' => $request->button_wr]);
            $statistical->subparameters->update($inputs);

            return collect(['opcion' => 'todos', 'statistical' => $statistical]);
        }   
    }


    public function showStatistical($id)
    {
        $statistical =  Statistical::find($id);
        if($statistical == null) {
            $statistical = Statistical::where('escenario_id', $id)->first();
        }

        /* se trae los arrays autoriazados por bloques */
        $statistical->msAvailable = $this->available($statistical->msAvailable);
        $statistical->fbAvailable = $this->available($statistical->fbAvailable);
        $statistical->osAvailable = $this->available($statistical->osAvailable);
        $statistical->rpAvailable = $this->available($statistical->rpAvailable);
        $statistical->idAvailable = $this->available($statistical->idAvailable);
        $statistical->gdAvailable = $this->available($statistical->gdAvailable);

        return $statistical;

    }


    /* funciones internas */
    public function validacion($request)
    {
        $rules = [
            'basin_statistical' => 'required',
            'field_statistical' => 'required',
        ];

        $messages = [
            'basin_statistical.required' => 'You must choose a basin.',
            'field_statistical.required' =>'must choose a minimum field.',
        ];

        return collect(['rules' => $rules, 'messages' => $messages]);
    }

    public function sessionParametros($statistical, $estado)
    {
        $parametros = collect([ 'MS1', 'MS2', 'MS3', 'MS4', 'MS5', 'FB1', 'FB2', 'FB3', 'FB4', 'FB5', 'OS1', 'OS2', 'OS3', 'OS4', 'RP1', 'RP2', 'RP3', 'RP4', 'ID1', 'ID2', 'ID3', 'ID4', 'GD1', 'GD2', 'GD3', 'GD4']);

        $subparametro = collect([ 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 13, 14, 15, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28,]);
        $a = collect();

        if($estado == 'false') {
            for ($i=0; $i <26 ; $i++) { 
                Session::forget($parametros[$i]);
            }
        } elseif($estado == 'true') {
            for ($i=0; $i <26 ; $i++) { 
                Session::flash($parametros[$i], Statistical::subparametro($subparametro[$i], $statistical));
                $a->push(Statistical::subparametro($subparametro[$i], $statistical));
            }
        }   

        return $a;
    }


    public function graficoStatistical($statistical)
    {
        /* se busca que parametros estan activos o desactivados */
        $ms1=0; $ms2=0; $ms3=0; $ms4=0; $ms5=0;
        if($statistical->msAvailable[$this->buscarArray(1, $statistical->msAvailable)] == 1) {
            $ms1 = $this->normalizacion($statistical->ms1, $statistical->p10_ms1, $statistical->p90_ms1, $statistical->subparameters->ms_scale_index_caco3);
        }

        if($statistical->msAvailable[$this->buscarArray(2, $statistical->msAvailable)] == 2) {
            $ms2 = $this->normalizacion($statistical->ms2, $statistical->p10_ms2, $statistical->p90_ms2, $statistical->subparameters->ms_scale_index_baso4);
        }

        if($statistical->msAvailable[$this->buscarArray(3, $statistical->msAvailable)] == 3) {
            $ms3 = $this->normalizacion($statistical->ms3, $statistical->p10_ms3, $statistical->p90_ms3, $statistical->subparameters->ms_scale_index_iron_scales);
        }

        if($statistical->msAvailable[$this->buscarArray(4, $statistical->msAvailable)] == 4) {
            $ms4 = $this->normalizacion($statistical->ms4, $statistical->p10_ms4, $statistical->p90_ms4, $statistical->subparameters->ms_calcium_concentration);
        }

        if($statistical->msAvailable[$this->buscarArray(5, $statistical->msAvailable)] == 5) {
            $ms5 = $this->normalizacion($statistical->ms5, $statistical->p10_ms5, $statistical->p90_ms5, $statistical->subparameters->ms_barium_concentration);
        }
        $msp = ($ms1+$ms2+$ms3+$ms4+$ms5)/count($statistical->msAvailable);
        /* ---------------------------------------------------------------------------- */

        $fb1=0; $fb2=0; $fb3=0; $fb4=0; $fb5=0;
        if($statistical->fbAvailable[$this->buscarArray(1, $statistical->fbAvailable)] == 1) {
            $fb1 = $this->normalizacion($statistical->fb1, $statistical->p10_fb1, $statistical->p90_fb1, $statistical->subparameters->fb_aluminum_concentration);
        }

        if($statistical->fbAvailable[$this->buscarArray(2, $statistical->fbAvailable)] == 2) {
            $fb2 = $this->normalizacion($statistical->fb2, $statistical->p10_fb2, $statistical->p90_fb2, $statistical->subparameters->fb_silicon_concentration);
        }

        if($statistical->fbAvailable[$this->buscarArray(3, $statistical->fbAvailable)] == 3) {
            $fb3 = $this->normalizacion($statistical->fb3, $statistical->p10_fb3, $statistical->p90_fb3, $statistical->subparameters->fb_critical_radius_factor);
        }

        if($statistical->fbAvailable[$this->buscarArray(4, $statistical->fbAvailable)] == 4) {
            $fb4 = $this->normalizacion($statistical->fb4, $statistical->p10_fb4, $statistical->p90_fb4, $statistical->subparameters->fb_mineralogic_factor);
        }

        if($statistical->fbAvailable[$this->buscarArray(5, $statistical->fbAvailable)] == 5) {
            $fb5 = $this->normalizacion($statistical->fb5, $statistical->p10_fb5, $statistical->p90_fb5, $statistical->subparameters->fb_crushed_proppant_factor);
        }

        $fbp = ($fb1+$fb2+$fb3+$fb4+$fb5)/count($statistical->fbAvailable);
        /* ---------------------------------------------------------------------------- */

        $os1=0; $os2=0; $os3=0; $os4=0;
        if($statistical->osAvailable[$this->buscarArray(1, $statistical->osAvailable)] == 1) {
            $os1 = $this->normalizacion($statistical->os1, $statistical->p10_os1, $statistical->p90_os1, $statistical->subparameters->os_cll_factor);
        }

        if($statistical->osAvailable[$this->buscarArray(2, $statistical->osAvailable)] == 2) {
            $os2 = $this->normalizacion($statistical->os2, $statistical->p10_os2, $statistical->p90_os2, $statistical->subparameters->os_compositional_factor);
        }

        if($statistical->osAvailable[$this->buscarArray(3, $statistical->osAvailable)] == 3) {
            $os3 = $this->normalizacion($statistical->os3, $statistical->p10_os3, $statistical->p90_os3, $statistical->subparameters->os_pressure_factor);
        }

        if($statistical->osAvailable[$this->buscarArray(4, $statistical->osAvailable)] == 4) {
            $os4 = $this->normalizacion($statistical->os4, $statistical->p10_os4, $statistical->p90_os4, $statistical->subparameters->os_high_impact_factor);
        }
        $osp = ($os1+$os2+$os3+$os4)/count($statistical->osAvailable);

        /* ---------------------------------------------------------------------------- */

        $rp1=0; $rp2=0; $rp3=0; $rp4=0;
        if($statistical->rpAvailable[$this->buscarArray(1, $statistical->rpAvailable)] == 1) {
            $rp1 = $this->normalizacion($statistical->rp1, $statistical->p10_rp1, $statistical->p90_rp1, $statistical->subparameters->rp_days_below_saturation_pressure);
        }

        if($statistical->rpAvailable[$this->buscarArray(2, $statistical->rpAvailable)] == 2) {
            $rp2 = $this->normalizacion($statistical->rp2, $statistical->p10_rp2, $statistical->p90_rp2, $statistical->subparameters->rp_delta_pressure_saturation);
        }

        if($statistical->rpAvailable[$this->buscarArray(3, $statistical->rpAvailable)] == 3) {
            $rp3 = $this->normalizacion($statistical->rp3, $statistical->p10_rp3, $statistical->p90_rp3, $statistical->subparameters->rp_water_intrusion);
        }

        if($statistical->rpAvailable[$this->buscarArray(4, $statistical->rpAvailable)] == 4) {
            $rp4 = $this->normalizacion($statistical->rp4, $statistical->p10_rp4, $statistical->p90_rp4, $statistical->subparameters->rp_high_impact_factor);
        }
        $rpp = ($rp1+$rp2+$rp3+$rp4)/count($statistical->rpAvailable);

        /* ---------------------------------------------------------------------------- */

        $id1=0; $id2=0; $id3=0; $id4=0; 
        if($statistical->idAvailable[$this->buscarArray(1, $statistical->idAvailable)] == 1) {
            $id1 = $this->normalizacion($statistical->id1, $statistical->p10_id1, $statistical->p90_id1, $statistical->subparameters->id_gross_pay);
        }

        if($statistical->idAvailable[$this->buscarArray(2, $statistical->idAvailable)] == 2) {
            $id2 = $this->normalizacion($statistical->id2, $statistical->p10_id2, $statistical->p90_id2, $statistical->subparameters->id_polymer_damage_factor);
        }

        if($statistical->idAvailable[$this->buscarArray(3, $statistical->idAvailable)] == 3) {
            $id3 = $this->normalizacion($statistical->id3, $statistical->p10_id3, $statistical->p90_id3, $statistical->subparameters->id_total_volume_water);
        }

        if($statistical->idAvailable[$this->buscarArray(4, $statistical->idAvailable)] == 4) {
            $id4 = $this->normalizacion($statistical->id4, $statistical->p10_id4, $statistical->p90_id4, $statistical->subparameters->id_mud_damage_factor);
        }
        $idp = ($id1+$id2+$id3+$id4)/count($statistical->idAvailable);

        /* ---------------------------------------------------------------------------- */

        $gd1=0; $gd2=0; $gd3=0; $gd4=0; 
        if($statistical->gdAvailable[$this->buscarArray(1, $statistical->gdAvailable)] == 1) {
            $gd1 = $this->normalizacion($statistical->gd1, $statistical->p10_gd1, $statistical->p90_gd1, $statistical->subparameters->gd_fraction_netpay);
        }

        if($statistical->gdAvailable[$this->buscarArray(2, $statistical->gdAvailable)] == 2) {
            $gd2 = $this->normalizacion($statistical->gd2, $statistical->p10_gd2, $statistical->p90_gd2, $statistical->subparameters->gd_drawdown);
        }

        if($statistical->gdAvailable[$this->buscarArray(3, $statistical->gdAvailable)] == 3) {
            $gd3 = $this->normalizacion($statistical->gd3, $statistical->p10_gd3, $statistical->p90_gd3, $statistical->subparameters->gd_ratio_kh_fracture);
        }

        if($statistical->gdAvailable[$this->buscarArray(4, $statistical->gdAvailable)] == 4) {
            $gd4 = $this->normalizacion($statistical->gd4, $statistical->p10_gd4, $statistical->p90_gd4, $statistical->subparameters->gd_geomechanical_damage_fraction);
        }
        $gdp = ($gd1+$gd2+$gd3+$gd4)/count($statistical->gdAvailable);


        $totalStatistical = $msp+$fbp+$osp+$rpp+$idp+$gdp;

        return collect([($msp/$totalStatistical)*100, ($fbp/$totalStatistical)*100, ($osp/$totalStatistical)*100, ($rpp/$totalStatistical)*100, ($idp/$totalStatistical)*100, ($gdp/$totalStatistical)*100]);
    }

    public function normalizacion($valor, $p10, $p90, $peso)
    {   
        return (($valor - $p10)/($p90 - $p10))*$peso;
    }

    public function available($available)
    {
        return array_map('intval', explode(',', $available));
    }

    public function buscarArray($n, $available){
        return array_search($n, $available, false);
    }
}