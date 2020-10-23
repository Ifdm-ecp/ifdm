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

        $msp = $this->analytical($analytical, $pressures_data, $radius_data, $analytical->mineral_scale_kd, 'ms');
        $fbp = $this->analytical($analytical, $pressures_data, $radius_data, $analytical->fines_blockage_kd, 'fb');
        $osp = $this->analytical($analytical, $pressures_data, $radius_data, $analytical->organic_scale_kd, 'os');
        $rpp = $this->analytical($analytical, $pressures_data, $radius_data, $analytical->relative_permeability_kd, 'rp');
        $idp = $this->analytical($analytical, $pressures_data, $radius_data, $analytical->induced_damage_kd, 'id');
        $gdp = $this->analytical($analytical, $pressures_data, $radius_data, $analytical->geomechanical_damage_kd, 'gd');

        $Total_Analytical = $msp + $fbp + $osp + $rpp + $idp + $gdp;
        $Total_Analytical = ($Total_Analytical == 0) ? 1 : $Total_Analytical;
        //dd($gdp);
        return collect([($msp / $Total_Analytical) * 100, ($fbp / $Total_Analytical) * 100, ($osp / $Total_Analytical) * 100, ($rpp / $Total_Analytical) * 100, ($idp / $Total_Analytical) * 100, ($gdp / $Total_Analytical) * 100]);
    }

    public function analytical($analytical, $pressures_data, $radius_data, $parametro, $funcion)
    {
        $damageRadius = $this->SkinRadius($analytical, $pressures_data, $radius_data, $funcion);
        if ($damageRadius == $analytical->well_radius || $damageRadius == 0 || $analytical->well_radius == 0) {
            return 0;
        } else {
            return ((1 / $parametro) - 1) * log($damageRadius / $analytical->well_radius);
        }
    }

    //Ok
    public function SkinRadius($analytical, $pressures_data, $radius_data, $funcion)
    {
        $skinRadius = 0.0;
        if ($funcion == "ms") {
            $skinRadius = $this->interpolation($analytical, $analytical->mineral_scale_cp, $pressures_data, $radius_data);
            #$skinRadius = $this->PvsR_profile($analytical, $analytical->mineral_scale_cp) + $analytical->well_radius;
        } else if ($funcion == "fb") {

            $skinRadius = $analytical->critical_radius;
            #$skinRadius = $analytical->critical_radius + $analytical->well_radius;
        } else if ($funcion == "os") {
            $skinRadius = $this->interpolation($analytical, $analytical->organic_scale_cp, $pressures_data, $radius_data);
            #$skinRadius = $this->PvsR_profile($analytical, $analytical->organic_scale_cp) + $analytical->well_radius;
        } else if ($funcion == "rp") {
            $skinRadius = $this->interpolation($analytical, $analytical->saturation_presure, $pressures_data, $radius_data);
            #$skinRadius = $this->PvsR_profile($analytical, $analytical->saturation_presure) + $analytical->well_radius;
        } else if ($funcion == "id") {
            $skinRadius = sqrt((($analytical->total_volumen * 5.615) / (pi() * $analytical->netpay * $analytical->porosity)) + pow($analytical->well_radius, 2));

        } else if ($funcion == "gd") {
            $skinRadius = $this->interpolation($analytical, $analytical->geomechanical_damage_cp + $analytical->bhp, $pressures_data, $radius_data);
            #$skinRadius = $this->PvsR_profile_GDP($analytical/*$analytical->geomechanical_damage_cp*/) + $analytical->well_radius;
        }

        return $skinRadius;
    }

    public function PvsR_profile($analytical)
    {
        $pressures_data = [];
        $radius_data = [];
        $Radius = $analytical->well_radius;
        $Pr = $analytical->bhp;
        while ($Radius < $analytical->drainage_radius) {
            if ($analytical->fluid_type == "Oil") {
                $Pr = $analytical->bhp + (((141.2 * $analytical->fluid_rate * $analytical->viscosity * $analytical->volumetric_factor) / ($analytical->netpay * $analytical->absolute_permeability)) * (log($analytical->well_radius) - (0.5 * $Radius / pow($analytical->drainage_radius, 2))));
                array_push($pressures_data, $Pr);
                array_push($radius_data, $Radius);

            } else {
                $Pr = $analytical->bhp + (((141.2 * $analytical->fluid_rate * (1000000) * $analytical->viscosity * $analytical->volumetric_factor) / (5.615 * $analytical->netpay * $analytical->absolute_permeability)) * (log($analytical->well_radius) - (0.5 * $Radius / pow($analytical->drainage_radius, 2))));
                array_push($pressures_data, $Pr);
                array_push($radius_data, $Radius);
            }

            $Radius = $Radius + 0.05;
        }
        return array($radius_data, $pressures_data);
    }

    public function interpolation($analytical, $critical_pressure, $pressures_data, $radius_data)
    {
        if ($critical_pressure >= end($pressures_data)) {
            return $analytical->drainage_radius;
        } else if ($critical_pressure <= $analytical->bhp) {
            return $analytical->bhp;
        } else {
            for ($j = 0; $j < count($pressures_data) - 1; $j++) {
                if ($critical_pressure >= $pressures_data[$j] && $critical_pressure < $pressures_data[$j + 1]) {
                    return (($radius_data[$j + 1] - $radius_data[$j]) / ($pressures_data[$j + 1] - $pressures_data[$j])) * ($critical_pressure - $pressures_data[$j]) + $radius_data[$j];
                }
            }
        }
    }
}
