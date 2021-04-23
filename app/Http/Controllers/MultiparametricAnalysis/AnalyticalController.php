<?php

namespace App\Http\Controllers\MultiparametricAnalysis;

use App\escenario;
use App\Http\Controllers\Controller;
use App\Http\Requests\MultiparametricAnalysis\AnalyticalRequest;
use App\Models\MultiparametricAnalysis\Analytical;
use App\Traits\AnalyticalTrait;
use Illuminate\Http\Request;

class analyticalController extends Controller
{
    use AnalyticalTrait;
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
        if (\Auth::check()) {
            $escenario = escenario::find(\Request::get('scenaryId'));
            $complete = false;
            $advisor = 'true';
            $pozoId = $escenario->pozo_id;

            return view('multiparametricAnalysis.analytical.create', compact(['escenario', 'complete', 'advisor', 'pozoId']));
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AnalyticalRequest $request)
    {
        $scenario = escenario::find($request->escenario_id);

        $analytical = Analytical::create([
            'escenario_id' => $request->escenario_id,
            'netpay' => $request->netpay,
            'absolute_permeability' => $request->absolute_permeability,
            'porosity' => $request->porosity,
            'permeability' => $request->permeability,
            'fluid_type' => $request->fluid_type,
            'viscosity' => $request->has('viscosity_oil') ? $request->viscosity_oil : $request->viscosity_gas,
            'volumetric_factor' => $request->has('volumetric_factor_oil') ? $request->volumetric_factor_oil : $request->volumetric_factor_gas,
            'well_radius' => $request->well_radius,
            'drainage_radius' => $request->drainage_radius,
            'reservoir_pressure' => $request->reservoir_pressure,
            'fluid_rate' => $request->has('fluid_rate_oil') ? $request->fluid_rate_oil : $request->fluid_rate_gas,
            'bhp' => $request->bhp,
            'critical_radius' => $request->critical_radius,
            'total_volumen' => $request->total_volumen,
            'saturation_presure' => $request->saturation_presure,
            'mineral_scale_cp' => $request->mineral_scale_cp,
            'organic_scale_cp' => $request->organic_scale_cp,
            'geomechanical_damage_cp' => $request->geomechanical_damage_cp,
            'mineral_scale_kd' => $request->mineral_scale_kd,
            'organic_scale_kd' => $request->organic_scale_kd,
            'geomechanical_damage_kd' => $request->geomechanical_damage_kd,
            'fines_blockage_kd' => $request->fines_blockage_kd,
            'relative_permeability_kd' => $request->relative_permeability_kd,
            'induced_damage_kd' => $request->induced_damage_kd,
            'status_wr' => $request->only_s == "save" ? 1 : 0,
        ]);

        $scenario->completo = $request->only_s == "save" ? 0 : 1;
        $scenario->estado = 1;
        $scenario->save();

        return redirect()->route('analytical.show', $analytical->escenario_id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $escenario = escenario::find($id);
        $analytical = $escenario->analytical;

        if (!$analytical->status_wr) {
            $grafico = $this->graficoAnalytical($analytical);
        } else {
            $grafico = [];
        }

        return view('multiparametricAnalysis.analytical.show', compact(['analytical', 'grafico']));
    }

    /* Recibe id del nuevo escenario, duplicateFrom seria el id del duplicado */
    public function duplicate($id, $duplicateFrom)
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
        $analytical = Analytical::find($id);
        if (!$analytical) {
            $analytical = Analytical::where('escenario_id', $id)->first();
        }
        $escenario = escenario::find($analytical->escenario_id);
        $complete = false;
        $advisor = true;
        $pozoId = $escenario->pozo_id;
        $duplicateFrom = isset($_SESSION['scenary_id_dup']) ? $_SESSION['scenary_id_dup'] : null;

        return view('multiparametricAnalysis.analytical.edit', compact(['analytical', 'complete', 'advisor', 'duplicateFrom', 'pozoId']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AnalyticalRequest $request, $id)
    {
        $analytical = Analytical::find($id);
        $scenario = escenario::find($analytical->escenario_id);

        $analytical->update([
            'netpay' => $request->netpay,
            'absolute_permeability' => $request->absolute_permeability,
            'porosity' => $request->porosity,
            'permeability' => $request->permeability,
            'fluid_type' => $request->fluid_type,
            'viscosity' => $request->has('viscosity_oil') ? $request->viscosity_oil : $request->viscosity_gas,
            'volumetric_factor' => $request->has('volumetric_factor_oil') ? $request->volumetric_factor_oil : $request->volumetric_factor_gas,
            'well_radius' => $request->well_radius,
            'drainage_radius' => $request->drainage_radius,
            'reservoir_pressure' => $request->reservoir_pressure,
            'fluid_rate' => $request->has('fluid_rate_oil') ? $request->fluid_rate_oil : $request->fluid_rate_gas,
            'bhp' => $request->bhp,
            'critical_radius' => $request->critical_radius,
            'total_volumen' => $request->total_volumen,
            'saturation_presure' => $request->saturation_presure,
            'mineral_scale_cp' => $request->mineral_scale_cp,
            'organic_scale_cp' => $request->organic_scale_cp,
            'geomechanical_damage_cp' => $request->geomechanical_damage_cp,
            'mineral_scale_kd' => $request->mineral_scale_kd,
            'organic_scale_kd' => $request->organic_scale_kd,
            'geomechanical_damage_kd' => $request->geomechanical_damage_kd,
            'fines_blockage_kd' => $request->fines_blockage_kd,
            'relative_permeability_kd' => $request->relative_permeability_kd,
            'induced_damage_kd' => $request->induced_damage_kd,
            'status_wr' => $request->only_s == "save" ? 1 : 0,
        ]);

        $scenario->completo = $request->only_s == "save" ? 0 : 1;
        $scenario->estado = 1;
        $scenario->save();

        unset($_SESSION['scenary_id_dup']);

        return redirect()->route('analytical.show', $analytical->escenario_id);
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

    public function graficoAnalytical($analytical)
    {
        $result_profile = $this->PvsR_profile($analytical);
        $pressures_data = $result_profile[1];
        $radius_data = $result_profile[0];

        $PrPcMSP = $this->calculate_Pr($pressures_data, $analytical->mineral_scale_cp);
        $PrPcOSP = $this->calculate_Pr($pressures_data, $analytical->organic_scale_cp);
        $PrPcKRP = $this->calculate_Pr($pressures_data, $analytical->saturation_presure);
        $PrPGDP = $this->calculate_Pr($pressures_data, $analytical->geomechanical_damage_cp);

        $FractionMaxDD = $this->FractionMaxDD($pressures_data);
        $SumFractionMaxDD = array_sum($FractionMaxDD);
        $CummDD = $this->CummDD($FractionMaxDD);

        //Skin Radius By FD Mechanism
        $ms = $this->SkinRadius($analytical, $pressures_data, $radius_data, $PrPcMSP, $PrPcOSP, $PrPcKRP, $PrPGDP, $FractionMaxDD, $SumFractionMaxDD, $CummDD, 'ms');
        $fb = $this->SkinRadius($analytical, $pressures_data, $radius_data, $PrPcMSP, $PrPcOSP, $PrPcKRP, $PrPGDP, $FractionMaxDD, $SumFractionMaxDD, $CummDD, 'fb');
        $os = $this->SkinRadius($analytical, $pressures_data, $radius_data, $PrPcMSP, $PrPcOSP, $PrPcKRP, $PrPGDP, $FractionMaxDD, $SumFractionMaxDD, $CummDD, 'os');
        $rp = $this->SkinRadius($analytical, $pressures_data, $radius_data, $PrPcMSP, $PrPcOSP, $PrPcKRP, $PrPGDP, $FractionMaxDD, $SumFractionMaxDD, $CummDD, 'rp');
        $id = $this->SkinRadius($analytical, $pressures_data, $radius_data, $PrPcMSP, $PrPcOSP, $PrPcKRP, $PrPGDP, $FractionMaxDD, $SumFractionMaxDD, $CummDD, 'id');
        $gd = $this->SkinRadius($analytical, $pressures_data, $radius_data, $PrPcMSP, $PrPcOSP, $PrPcKRP, $PrPGDP, $FractionMaxDD, $SumFractionMaxDD, $CummDD, 'gd');
        $averageSkinRadius = ($ms + $fb + $os + $rp + $id + $gd) / 6;

        //Equivalent Skin By FD Mechanism
        $msp = $this->EquivalentSkinRadius($analytical, $ms, 'ms');
        $fbp = $this->EquivalentSkinRadius($analytical, $fb, 'fb');
        $osp = $this->EquivalentSkinRadius($analytical, $os, 'os');
        $rpp = $this->EquivalentSkinRadius($analytical, $rp, 'rp');
        $idp = $this->EquivalentSkinRadius($analytical, $id, 'id');
        $gdp = $this->EquivalentSkinRadius($analytical, $gd, 'gd');
        $Total_Analytical = $msp + $fbp + $osp + $rpp + $idp + $gdp;
        
        return collect([($msp / $Total_Analytical) * 100, ($fbp / $Total_Analytical) * 100, ($osp / $Total_Analytical) * 100, ($rpp / $Total_Analytical) * 100, ($idp / $Total_Analytical) * 100, ($gdp / $Total_Analytical) * 100]);
    }

    public function PvsR_profile($analytical)
    {
        $pressures_data = [];
        $radius_data = [];
        $Radius = $analytical->well_radius;
        $Pr = $analytical->bhp;
        array_push($pressures_data, $Pr);
        array_push($radius_data, $Radius);
  
        $counter = 1;

        while ($counter <= 3171) {

            if ($counter <= 200) {
                $Radius = $Radius + 0.05;
            } else {
                $Radius = $Radius + 0.5;
            }

            if ($analytical->fluid_type == "Oil") {
                $Pr = round($analytical->bhp + (((141.2 * $analytical->fluid_rate * $analytical->viscosity * $analytical->volumetric_factor) / ($analytical->netpay * $analytical->absolute_permeability)) * (log($Radius / $analytical->well_radius) - ( ($Radius * $Radius) / (2 * $analytical->drainage_radius * $analytical->drainage_radius )))), 8);
                array_push($pressures_data, $Pr);
                array_push($radius_data, $Radius);
            } else {
                $Pr = round($analytical->bhp + (((141.2 * ($analytical->fluid_rate * 1000000 / 5.615) * $analytical->viscosity * $analytical->volumetric_factor) / ($analytical->netpay * $analytical->absolute_permeability)) * (log($Radius / $analytical->well_radius) - ( ($Radius * $Radius) / (2 * $analytical->drainage_radius * $analytical->drainage_radius )))), 8);
                array_push($pressures_data, $Pr);
                array_push($radius_data, $Radius);
            }

            $counter = $counter + 1;
        }
        return array($radius_data, $pressures_data);
    }

    public function calculate_Pr($pressures_data, $value_aux) 
    {
        $PrArray = [];

        foreach($pressures_data as $pressure) {
            array_push($PrArray, $pressure - $value_aux);
        }

        return $PrArray;
    }

    public function FractionMaxDD($pressures_data) 
    {
        $FractionMaxDD = [];

        foreach($pressures_data as $pressure) {
            array_push($FractionMaxDD, (end($pressures_data)-$pressure)/(end($pressures_data)-$pressures_data[0]));
        }

        return $FractionMaxDD;
    }

    public function CummDD($FractionMaxDD)
    {
        $CummDD = [];

        foreach($FractionMaxDD as $fraction) {
            array_push($CummDD, 1 - $fraction);
        }

        return $CummDD;
    }

    public function SkinRadius($analytical, $pressures_data, $radius_data, $PrPcMSP, $PrPcOSP, $PrPcKRP, $PrPGDP, $FractionMaxDD, $SumFractionMaxDD, $CummDD, $funcion)
    {
        $skinRadius = 0.0;
        if ($funcion == "ms") {
            if ($PrPcMSP[0] > 0) {
                $skin = 0;
            } else {
                $counter_flag = 0;
                for ($i = 0; $i < count($PrPcMSP); ++$i) {
                    if($PrPcMSP[$i] > 0) {
                        $counter_flag = $i - 1;
                        break;
                    }
                }
                $skin = $analytical->well_radius + $radius_data[$counter_flag];
            }
        } else if ($funcion == "fb") {
            $skin = $analytical->critical_radius + $analytical->well_radius;
        } else if ($funcion == "os") {
            if ($PrPcOSP[0] > 0) {
                $skin = 0;
            } else {
                $counter_flag = 0;
                for ($i = 0; $i < count($PrPcOSP); ++$i) {
                    if($PrPcOSP[$i] > 0) {
                        $counter_flag = $i - 1;
                        break;
                    }
                }
                $skin = $analytical->well_radius + $radius_data[$counter_flag];
            }
        } else if ($funcion == "rp") {
            if ($analytical->reservoir_pressure < $analytical->saturation_presure) {
                $skin = $analytical->drainage_radius;
            } else {
                if ($PrPcKRP[0] > 0) {
                    $skin = 0;
                } else {
                    $counter_flag = 0;
                    for ($i = 0; $i < count($PrPcKRP); ++$i) {
                        if($PrPcKRP[$i] > 0) {
                            $counter_flag = $i - 1;
                            break;
                        }
                    }
                    $skin = $radius_data[$counter_flag];
                }
            }
        } else if ($funcion == "id") {
            $skin = $analytical->well_radius + sqrt(($analytical->total_volumen * 5.615) / (3.1416 * $analytical->netpay * $analytical->porosity));
        } else if ($funcion == "gd") {
            $counter_flag = 0;
            for ($i = 0; $i < count($CummDD); ++$i) {
                if($CummDD[$i] > 0.25) {
                    $counter_flag = $i - 1;
                    break;
                }
            }
            $skin = $analytical->well_radius + $radius_data[$counter_flag];
        }

        return $skin;
    }

    public function EquivalentSkinRadius($analytical, $funcion_value, $funcion)
    {
        $skinRadius = 0.0;
        if ($funcion == "ms") {
            if ($funcion_value == 0) {
                $skin = 0;
            } else { 
                $skin = (($analytical->permeability/($analytical->mineral_scale_kd * $analytical->permeability)) - 1) * log($funcion_value/$analytical->well_radius);
            }
        } else if ($funcion == "fb") {
            $skin = (($analytical->permeability/($analytical->fines_blockage_kd * $analytical->permeability)) - 1) * log($funcion_value/$analytical->well_radius);
        } else if ($funcion == "os") {
            if ($funcion_value == 0) {
                $skin = 0;
            } else { 
                $skin = (($analytical->permeability/($analytical->organic_scale_kd * $analytical->permeability)) - 1) * log($funcion_value/$analytical->well_radius);
            }
        } else if ($funcion == "rp") {
            $skin = (($analytical->permeability/($analytical->relative_permeability_kd * $analytical->permeability)) - 1) * log($funcion_value/$analytical->well_radius);
        } else if ($funcion == "id") {
            $skin = (($analytical->permeability/($analytical->induced_damage_kd * $analytical->permeability)) - 1) * log($funcion_value/$analytical->well_radius);
        } else if ($funcion == "gd") {
            $skin = (($analytical->permeability/($analytical->geomechanical_damage_kd * $analytical->permeability)) - 1) * log($funcion_value/$analytical->well_radius);
        }

        return $skin;
    }
}