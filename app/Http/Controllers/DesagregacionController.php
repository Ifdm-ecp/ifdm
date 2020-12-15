<?php

namespace App\Http\Controllers;

if (!isset($_SESSION)) {
    session_start();
}
use App\desagregacion;
use App\desagregacion_tabla;
use App\escenario;
use App\Http\Controllers\Controller;
use App\Http\Requests\disaggregation_request;
use App\permeabilidades_resultado_desagregacion;
use App\radios_resultado_desagregacion;
use App\resultado_desagregacion;
use DB;
use Illuminate\Http\Request;
use View;

class desagregacionController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Despliega la vista de creación de un módulo específico de desagregación
     *
     */
    public function create()
    {
        if (\Auth::check()) {
            $scenario_id = \Request::get('scenaryId');
            $scenario = escenario::find(\Request::get('scenaryId'));

            //Checks if disaggregation scenery already exists. If not, creates a table row. Avoids duplicates.
            $desagregacion = DB::table('desagregacion')->where('id_escenario', $scenario_id)->first();
            if (is_null($desagregacion)) {
                $desagregacion = new desagregacion;
                $desagregacion->id_escenario = $scenario_id;
                $desagregacion->save();
            }

            return view('desagregacion.create', compact('scenario', 'scenario_id'));
        } else {
            return view('loginfirst');
        }
    }

    /**
     * Guarda los datos del escenario de desagregación con base en la información almacenada en el formluario e inserta en la base de datos.
     *
     * @param  \Illuminate\Http\disaggregation_request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(disaggregation_request $request)
    {
        if (\Auth::check()) {
            $scenaryId = $request->scenario_id;
            $scenary = escenario::find($scenaryId);

            DB::table('desagregacion')->where('id_escenario', $scenaryId)->update([
                'well_radius' => $request->well_radius !== "" ? $request->well_radius : null,
                'reservoir_pressure' => $request->reservoir_pressure !== "" ? $request->reservoir_pressure : null,
                'measured_well_depth' => $request->measured_well_depth !== "" ? $request->measured_well_depth : null,
                'true_vertical_depth' => $request->true_vertical_depth !== "" ? $request->true_vertical_depth : null,
                'formation_thickness' => $request->formation_thickness !== "" ? $request->formation_thickness : null,
                'perforated_thickness' => $request->perforated_thickness !== "" ? $request->perforated_thickness : null,
                'well_completitions' => $request->well_completitions !== "" ? $request->well_completitions : null,
                'perforation_penetration_depth' => $request->perforation_penetration_depth !== "" ? $request->perforation_penetration_depth : null,
                'perforating_phase_angle' => $request->perforating_phase_angle !== "" ? $request->perforating_phase_angle : null,
                'perforating_radius' => $request->perforating_radius !== "" ? $request->perforating_radius : null,
                'production_formation_thickness' => $request->production_formation_thickness !== "" ? $request->production_formation_thickness : null,
                'horizontal_vertical_permeability_ratio' => $request->horizontal_vertical_permeability_ratio !== "" ? $request->horizontal_vertical_permeability_ratio : null,
                'drainage_area_shape' => $request->drainage_area_shape !== "" ? $request->drainage_area_shape : null,
                'fluid_of_interest' => $request->fluid_of_interest !== "" ? $request->fluid_of_interest : null,
                'skin' => $request->skin !== "" ? $request->skin : null,
                'permeability' => $request->permeability !== "" ? $request->permeability : null,
                'rock_type' => $request->rock_type !== "" ? $request->rock_type : null,
                'porosity' => $request->porosity !== "" ? $request->porosity : null,
                'status_wr' => $request->only_s == "save" ? 1 : 0,
                'id_escenario' => $scenaryId,
            ]);

            if ($request->input('fluid_of_interest') == 1) {
                DB::table('desagregacion')->where('id_escenario', $scenaryId)->update([
                    'oil_rate' => $request->input('oil_rate'),
                    'oil_bottomhole_flowing_pressure' => $request->input('oil_bottomhole_flowing_pressure'),
                    'oil_viscosity' => $request->input('oil_viscosity'),
                    'oil_volumetric_factor' => $request->input('oil_volumetric_factor'),
                ]);
            } elseif ($request->input('fluid_of_interest') == 2) {
                DB::table('desagregacion')->where('id_escenario', $scenaryId)->update([
                    'gas_rate' => $request->input('gas_rate'),
                    'gas_bottomhole_flowing_pressure' => $request->input('gas_bottomhole_flowing_pressure'),
                    'gas_viscosity' => $request->input('gas_viscosity'),
                    'gas_volumetric_factor' => $request->input('gas_volumetric_factor'),
                    'gas_specific_gravity' => $request->input('gas_specific_gravity'),
                ]);
            } elseif ($request->input('fluid_of_interest') == 3) {
                DB::table('desagregacion')->where('id_escenario', $scenaryId)->update([
                    'water_rate' => $request->input('water_rate'),
                    'water_bottomhole_flowing_pressure' => $request->input('water_bottomhole_flowing_pressure'),
                    'water_viscosity' => $request->input('water_viscosity'),
                    'water_volumetric_factor' => $request->input('water_volumetric_factor'),
                ]);
            }elseif ($request->input('fluid_of_interest') == 4) {
                if ($request->input('emulsion') == 1) {
                    if ($request->input('characterized_mixture') == 1) {
                        DB::table('desagregacion')->where('id_escenario', $scenaryId)->update([
                            'emulsion' => $request->input('emulsion'),
                            'characterized_mixture' => $request->input('characterized_mixture'),
                            'flow_rate_1_1' => $request->input('flow_rate_1_1'),
                            'mixture_bottomhole_flowing_pressure_1_1' => $request->input('mixture_bottomhole_flowing_pressure_1_1'),
                            'mixture_viscosity_1_1' => $request->input('mixture_viscosity_1_1'),
                            'mixture_oil_volumetric_factor_1_1' => $request->input('mixture_oil_volumetric_factor_1_1'),
                            'mixture_water_volumetric_factor_1_1' => $request->input('mixture_water_volumetric_factor_1_1'),
                            'mixture_oil_fraction_1_1' => $request->input('mixture_oil_fraction_1_1'),
                            'mixture_water_fraction_1_1' => $request->input('mixture_water_fraction_1_1'),
                            'flow_rate_1_2' => null,
                            'mixture_bottomhole_flowing_pressure_1_2' => null,
                            'mixture_oil_viscosity_1_2' => null,
                            'mixture_water_viscosity_1_2' => null,
                            'mixture_oil_fraction_1_2' => null,
                            'mixture_water_fraction_1_2' => null,
                            'mixture_oil_volumetric_factor_1_2' => null,
                            'mixture_water_volumetric_factor_1_2' => null,
                            'flow_rate_2' => null,
                            'mixture_bottomhole_flowing_pressure_2' => null,
                            'mixture_oil_viscosity_2' => null,
                            'mixture_water_viscosity_2' => null,
                            'mixture_oil_fraction_2' => null,
                            'mixture_water_fraction_2' => null,
                            'mixture_oil_volumetric_factor_2' => null,
                            'mixture_water_volumetric_factor_2' => null,
                        ]);
                    } elseif ($request->input('characterized_mixture') == 2) {
                        DB::table('desagregacion')->where('id_escenario', $scenaryId)->update([
                            'emulsion' => $request->input('emulsion'),
                            'characterized_mixture' => $request->input('characterized_mixture'),
                            'flow_rate_1_2' => $request->input('flow_rate_1_2'),
                            'mixture_bottomhole_flowing_pressure_1_2' => $request->input('mixture_bottomhole_flowing_pressure_1_2'),
                            'mixture_oil_viscosity_1_2' => $request->input('mixture_oil_viscosity_1_2'),
                            'mixture_water_viscosity_1_2' => $request->input('mixture_water_viscosity_1_2'),
                            'mixture_oil_fraction_1_2' => $request->input('mixture_oil_fraction_1_2'),
                            'mixture_water_fraction_1_2' => $request->input('mixture_water_fraction_1_2'),
                            'mixture_oil_volumetric_factor_1_2' => $request->input('mixture_oil_volumetric_factor_1_2'),
                            'mixture_water_volumetric_factor_1_2' => $request->input('mixture_water_volumetric_factor_1_2'),
                            'flow_rate_1_1' => null,
                            'mixture_bottomhole_flowing_pressure_1_1' => null,
                            'mixture_viscosity_1_1' => null,
                            'mixture_oil_volumetric_factor_1_1' => null,
                            'mixture_water_volumetric_factor_1_1' => null,
                            'mixture_oil_fraction_1_1' => null,
                            'mixture_water_fraction_1_1' => null,
                            'flow_rate_2' => null,
                            'mixture_bottomhole_flowing_pressure_2' => null,
                            'mixture_oil_viscosity_2' => null,
                            'mixture_water_viscosity_2' => null,
                            'mixture_oil_fraction_2' => null,
                            'mixture_water_fraction_2' => null,
                            'mixture_oil_volumetric_factor_2' => null,
                            'mixture_water_volumetric_factor_2' => null,
                        ]);
                    }
                }elseif ($request->input('emulsion') == 2) {
                    DB::table('desagregacion')->where('id_escenario', $scenaryId)->update([
                        'characterized_mixture' => null,
                        'emulsion' => $request->input('emulsion'),
                        'flow_rate_2' => $request->input('flow_rate_2'),
                        'mixture_bottomhole_flowing_pressure_2' => $request->input('mixture_bottomhole_flowing_pressure_2'),
                        'mixture_oil_viscosity_2' => $request->input('mixture_oil_viscosity_2'),
                        'mixture_water_viscosity_2' => $request->input('mixture_water_viscosity_2'),
                        'mixture_oil_fraction_2' => $request->input('mixture_oil_fraction_2'),
                        'mixture_water_fraction_2' => $request->input('mixture_water_fraction_2'),
                        'mixture_oil_volumetric_factor_2' => $request->input('mixture_oil_volumetric_factor_2'),
                        'mixture_water_volumetric_factor_2' => $request->input('mixture_water_volumetric_factor_2'),
                        'flow_rate_1_1' => null,
                        'mixture_bottomhole_flowing_pressure_1_1' => null,
                        'mixture_viscosity_1_1' => null,
                        'mixture_oil_volumetric_factor_1_1' => null,
                        'mixture_water_volumetric_factor_1_1' => null,
                        'mixture_oil_fraction_1_1' => null,
                        'mixture_water_fraction_1_1' => null,
                        'flow_rate_1_2' => null,
                        'mixture_bottomhole_flowing_pressure_1_2' => null,
                        'mixture_oil_viscosity_1_2' => null,
                        'mixture_water_viscosity_1_2' => null,
                        'mixture_oil_fraction_1_2' => null,
                        'mixture_water_fraction_1_2' => null,
                        'mixture_oil_volumetric_factor_1_2' => null,
                        'mixture_water_volumetric_factor_1_2' => null,
                    ]);
                }
            }

            $desagregacion = DB::table('desagregacion')->where('id_escenario', $scenaryId)->first();

            $desagregacion_tabla = DB::table('desagregacion_tabla')->where('id_desagregacion', $desagregacion->id)->first();
            if (!is_null($desagregacion_tabla)) {
                DB::table('desagregacion_tabla')->where('id_desagregacion', $desagregacion->id)->delete();
            }
            $tabla = str_replace(",[null,null,null,null]", "", $request->input("unidades_table_hidden"));
            $tabla = json_decode($tabla);
            foreach ($tabla as $value) {
                $desagregacion_tabla = new desagregacion_tabla;
                $desagregacion_tabla->Espesor = str_replace(",", ".", $value[0]);
                $desagregacion_tabla->fzi = str_replace(",", ".", $value[1]);
                $desagregacion_tabla->porosidad_promedio = str_replace(",", ".", $value[2]);
                $desagregacion_tabla->permeabilidad = str_replace(",", ".", $value[3]);
                $desagregacion_tabla->id_desagregacion = $desagregacion->id;
                $desagregacion_tabla->save();
            }

            $scenary->completo = $request->only_s == "save" ? 0 : 1;
            $scenary->estado = 1;
            $scenary->save();

            if (!$desagregacion->status_wr) {
                $pozo = DB::table('pozos')->find($scenary->pozo_id);
                $cuenca = DB::table('cuencas')->find($scenary->cuenca_id);
                $formacion = DB::table('formacionxpozos')->where('nombre', $scenary->formacion_id)->first();
                
                $arreglo = json_decode($request->get("unidades_table_hidden"));
                $datos_unidades_hidraulicas = array();
                foreach ($arreglo as $value) {
                    if ($value[0] != null) {
                        $datos_unidades_hidraulicas[] = $value;
                    }
                }

                $hidraulic_units_data = json_encode($datos_unidades_hidraulicas);

                $well_radius = $request->get("well_radius");
                $reservoir_pressure = $request->get("reservoir_pressure");
                $measured_well_depth = $request->get("measured_well_depth");
                $true_vertical_depth = $request->get("true_vertical_depth");
                $formation_thickness = $request->get("formation_thickness");
                $perforated_thickness = $request->get("perforated_thickness");
                $well_completitions = $request->get("well_completitions");
                $perforation_penetration_depth = $request->get("perforation_penetration_depth");
                $perforating_phase_angle = $request->get("perforating_phase_angle");
                $perforating_radius = $request->get("perforating_radius");
                $production_formation_thickness = $request->get("production_formation_thickness");
                $horizontal_vertical_permeability_ratio = $request->get("horizontal_vertical_permeability_ratio");
                $drainage_area_shape = $request->get("drainage_area_shape");
                $fluid_of_interest = $request->get("fluid_of_interest");
                $skin = $request->get("skin");
                $permeability = $request->get("permeability");
                $rock_type = $request->get("rock_type");
                $porosity = $request->get("porosity");

                if ($request->input('fluid_of_interest') == 1) {
                    $fluid_rate = $request->get("oil_rate");
                    $bottomhole_flowing_pressure = $request->get("oil_bottomhole_flowing_pressure");
                    $fluid_viscosity = $request->get("oil_viscosity");
                    $fluid_volumetric_factor = $request->get("oil_volumetric_factor");
                    $emulsion = $characterized_mixture = $mixture_rate = $mixture_bottomhole_flowing_pressure = $mixture_viscosity = $mixture_volumetric_factor = $mixture_oil_fraction_1_1 = $mixture_water_fraction_1_1 = $flow_rate_1_1 = $mixture_bottomhole_flowing_pressure_1_1 = $mixture_viscosity_1_1 = $mixture_oil_volumetric_factor_1_1 = $mixture_water_volumetric_factor_1_1 = $mixture_oil_fraction_1_1 = $mixture_water_fraction_1_1 = $flow_rate_1_2 = $mixture_bottomhole_flowing_pressure_1_2 = $mixture_oil_viscosity_1_2 = $mixture_water_viscosity_1_2 = $mixture_oil_fraction_1_2 = $mixture_water_fraction_1_2 = $mixture_oil_volumetric_factor_1_2 = $mixture_water_volumetric_factor_1_2 = $flow_rate_2 = $mixture_bottomhole_flowing_pressure_2 = $mixture_oil_viscosity_2 = $mixture_water_viscosity_2 = $mixture_oil_fraction_2 = $mixture_water_fraction_2 = $mixture_oil_volumetric_factor_2 = $mixture_water_volumetric_factor_2 = $fluid_specific_gravity = 1;
                } elseif ($request->input('fluid_of_interest') == 2) {
                    $fluid_rate = $request->get("gas_rate");
                    $bottomhole_flowing_pressure = $request->get("gas_bottomhole_flowing_pressure");
                    $fluid_viscosity = $request->get("gas_viscosity");
                    $fluid_volumetric_factor = $request->get("gas_volumetric_factor");
                    $fluid_specific_gravity = $request->get("gas_specific_gravity");
                    $emulsion = $characterized_mixture = $mixture_rate = $mixture_bottomhole_flowing_pressure = $mixture_viscosity = $mixture_volumetric_factor = $mixture_oil_fraction_1_1 = $mixture_water_fraction_1_1 = $flow_rate_1_1 = $mixture_bottomhole_flowing_pressure_1_1 = $mixture_viscosity_1_1 = $mixture_oil_volumetric_factor_1_1 = $mixture_water_volumetric_factor_1_1 = $mixture_oil_fraction_1_1 = $mixture_water_fraction_1_1 = $flow_rate_1_2 = $mixture_bottomhole_flowing_pressure_1_2 = $mixture_oil_viscosity_1_2 = $mixture_water_viscosity_1_2 = $mixture_oil_fraction_1_2 = $mixture_water_fraction_1_2 = $mixture_oil_volumetric_factor_1_2 = $mixture_water_volumetric_factor_1_2 = $flow_rate_2 = $mixture_bottomhole_flowing_pressure_2 = $mixture_oil_viscosity_2 = $mixture_water_viscosity_2 = $mixture_oil_fraction_2 = $mixture_water_fraction_2 = $mixture_oil_volumetric_factor_2 = $mixture_water_volumetric_factor_2 = 1;
                } elseif ($request->input('fluid_of_interest') == 3) {
                    $fluid_rate = $request->get("water_rate");
                    $bottomhole_flowing_pressure = $request->get("water_bottomhole_flowing_pressure");
                    $fluid_viscosity = $request->get("water_viscosity");
                    $fluid_volumetric_factor = $request->get("water_volumetric_factor");
                    $emulsion = $characterized_mixture = $mixture_rate = $mixture_bottomhole_flowing_pressure = $mixture_viscosity = $mixture_volumetric_factor = $mixture_oil_fraction_1_1 = $mixture_water_fraction_1_1 = $flow_rate_1_1 = $mixture_bottomhole_flowing_pressure_1_1 = $mixture_viscosity_1_1 = $mixture_oil_volumetric_factor_1_1 = $mixture_water_volumetric_factor_1_1 = $mixture_oil_fraction_1_1 = $mixture_water_fraction_1_1 = $flow_rate_1_2 = $mixture_bottomhole_flowing_pressure_1_2 = $mixture_oil_viscosity_1_2 = $mixture_water_viscosity_1_2 = $mixture_oil_fraction_1_2 = $mixture_water_fraction_1_2 = $mixture_oil_volumetric_factor_1_2 = $mixture_water_volumetric_factor_1_2 = $flow_rate_2 = $mixture_bottomhole_flowing_pressure_2 = $mixture_oil_viscosity_2 = $mixture_water_viscosity_2 = $mixture_oil_fraction_2 = $mixture_water_fraction_2 = $mixture_oil_volumetric_factor_2 = $mixture_water_volumetric_factor_2 = $fluid_specific_gravity = 1;
                }elseif ($request->input('fluid_of_interest') == 4) {
                    if ($request->input('emulsion') == 1) {
                        if ($request->input('characterized_mixture') == 1) {
                            $emulsion = $request->get('emulsion');
                            $characterized_mixture = $request->get('characterized_mixture');
                            $flow_rate_1_1 = $request->get('flow_rate_1_1');
                            $mixture_bottomhole_flowing_pressure_1_1 = $request->get('mixture_bottomhole_flowing_pressure_1_1');
                            $mixture_viscosity_1_1 = $request->get('mixture_viscosity_1_1');
                            $mixture_oil_volumetric_factor_1_1 = $request->get('mixture_oil_volumetric_factor_1_1');
                            $mixture_water_volumetric_factor_1_1 = $request->get('mixture_water_volumetric_factor_1_1');
                            $mixture_oil_fraction_1_1 = $request->get('mixture_oil_fraction_1_1');
                            $mixture_water_fraction_1_1 = $request->get('mixture_water_fraction_1_1');
                            $fluid_rate = $bottomhole_flowing_pressure = $fluid_viscosity = $fluid_volumetric_factor = $mixture_oil_fraction_1_1 = $mixture_water_fraction_1_1 = $flow_rate_1_2 = $mixture_bottomhole_flowing_pressure_1_2 = $mixture_oil_viscosity_1_2 = $mixture_water_viscosity_1_2 = $mixture_oil_fraction_1_2 = $mixture_water_fraction_1_2 = $mixture_oil_volumetric_factor_1_2 = $mixture_water_volumetric_factor_1_2 = $flow_rate_2 = $mixture_bottomhole_flowing_pressure_2 = $mixture_oil_viscosity_2 = $mixture_water_viscosity_2 = $mixture_oil_fraction_2 = $mixture_water_fraction_2 = $mixture_oil_volumetric_factor_2 = $mixture_water_volumetric_factor_2 = $fluid_specific_gravity = 1;
                        } elseif ($request->input('characterized_mixture') == 2) {
                            $emulsion = $request->get('emulsion');
                            $characterized_mixture = $request->get('characterized_mixture');
                            $flow_rate_1_2 = $request->get('flow_rate_1_2');
                            $mixture_bottomhole_flowing_pressure_1_2 = $request->get('mixture_bottomhole_flowing_pressure_1_2');
                            $mixture_oil_viscosity_1_2 = $request->get('mixture_oil_viscosity_1_2');
                            $mixture_water_viscosity_1_2 = $request->get('mixture_water_viscosity_1_2');
                            $mixture_oil_fraction_1_2 = $request->get('mixture_oil_fraction_1_2');
                            $mixture_water_fraction_1_2 = $request->get('mixture_water_fraction_1_2');
                            $mixture_oil_volumetric_factor_1_2 = $request->get('mixture_oil_volumetric_factor_1_2');
                            $mixture_water_volumetric_factor_1_2 = $request->get('mixture_water_volumetric_factor_1_2');
                            $fluid_rate = $bottomhole_flowing_pressure = $fluid_viscosity = $fluid_volumetric_factor = $flow_rate_1_1 = $mixture_bottomhole_flowing_pressure_1_1 = $mixture_viscosity_1_1 = $mixture_oil_volumetric_factor_1_1 = $mixture_water_volumetric_factor_1_1 = $mixture_oil_fraction_1_1 = $mixture_water_fraction_1_1 = $flow_rate_2 = $mixture_bottomhole_flowing_pressure_2 = $mixture_oil_viscosity_2 = $mixture_water_viscosity_2 = $mixture_oil_fraction_2 = $mixture_water_fraction_2 = $mixture_oil_volumetric_factor_2 = $mixture_water_volumetric_factor_2 = $fluid_specific_gravity = 1;
                        }
                    }elseif ($request->input('emulsion') == 2) {
                        $emulsion = $request->get('emulsion');
                        $flow_rate_2 = $request->get('flow_rate_2');
                        $mixture_bottomhole_flowing_pressure_2 = $request->get('mixture_bottomhole_flowing_pressure_2');
                        $mixture_oil_viscosity_2 = $request->get('mixture_oil_viscosity_2');
                        $mixture_water_viscosity_2 = $request->get('mixture_water_viscosity_2');
                        $mixture_oil_fraction_2 = $request->get('mixture_oil_fraction_2');
                        $mixture_water_fraction_2 = $request->get('mixture_water_fraction_2');
                        $mixture_oil_volumetric_factor_2 = $request->get('mixture_oil_volumetric_factor_2');
                        $mixture_water_volumetric_factor_2 = $request->get('mixture_water_volumetric_factor_2');
                        $fluid_rate = $bottomhole_flowing_pressure = $fluid_viscosity = $fluid_volumetric_factor = $characterized_mixture = $flow_rate_1_1 = $mixture_bottomhole_flowing_pressure_1_1 = $mixture_viscosity_1_1 = $mixture_oil_volumetric_factor_1_1 = $mixture_water_volumetric_factor_1_1 = $mixture_oil_fraction_1_1 = $mixture_water_fraction_1_1 = $flow_rate_1_2 = $mixture_bottomhole_flowing_pressure_1_2 = $mixture_oil_viscosity_1_2 = $mixture_water_viscosity_1_2 = $mixture_oil_fraction_1_2 = $mixture_water_fraction_1_2 = $mixture_oil_volumetric_factor_1_2 = $mixture_water_volumetric_factor_1_2 = $fluid_specific_gravity = 1;
                    }
                }

                $results = 0;
                $radios = 0;
                $permeabilidades = 0;
                $coeficiente_friccion = 0;
                $modulo_permeabilidad = 0;

                $results = $this->run_disaggregation_analysis($well_radius, $reservoir_pressure, $measured_well_depth, $true_vertical_depth, $formation_thickness, $perforated_thickness, $well_completitions, $perforation_penetration_depth, $perforating_phase_angle, $perforating_radius, $production_formation_thickness, $horizontal_vertical_permeability_ratio, $drainage_area_shape, $fluid_rate, $bottomhole_flowing_pressure, $fluid_viscosity, $fluid_volumetric_factor, $fluid_specific_gravity, $skin, $permeability, $rock_type, $porosity, $hidraulic_units_data, $emulsion, $characterized_mixture, $flow_rate_1_1, $mixture_bottomhole_flowing_pressure_1_1, $mixture_viscosity_1_1, $mixture_oil_volumetric_factor_1_1, $mixture_water_volumetric_factor_1_1, $mixture_oil_fraction_1_1, $mixture_water_fraction_1_1, $flow_rate_1_2, $mixture_bottomhole_flowing_pressure_1_2, $mixture_oil_viscosity_1_2, $mixture_water_viscosity_1_2, $mixture_oil_fraction_1_2, $mixture_water_fraction_1_2, $mixture_oil_volumetric_factor_1_2, $mixture_water_volumetric_factor_1_2, $flow_rate_2, $mixture_bottomhole_flowing_pressure_2, $mixture_oil_viscosity_2, $mixture_water_viscosity_2, $mixture_oil_fraction_2, $mixture_water_fraction_2, $mixture_oil_volumetric_factor_2, $mixture_water_volumetric_factor_2, $fluid_of_interest);

                $radios = $results[5];
                $permeabilidades = $results[6];
                $coeficiente_friccion = $results[7];
                $modulo_permeabilidad = $results[8];
                $suma = $results[1] + $results[2] + $results[3] + $results[4];
                $total = $results[0];
                $ri = $results[9];
                $skin_by_stress = $results[10];
                $skin_by_stress = $skin_by_stress[0];
                $results = array_slice($results, 0, 5);

                /* Resultados spider */
                $resultado_desagregacion = DB::table('resultado_desagregacion')->where('id_desagregacion', $desagregacion->id)->first();
                if (!is_null($resultado_desagregacion)) {
                    DB::table('resultado_desagregacion')->where('id_desagregacion', $desagregacion->id)->delete();
                }
                $resultado_desagregacion = new resultado_desagregacion;
                $resultado_desagregacion->id_desagregacion = $desagregacion->id;
                $resultado_desagregacion->total_skin = $results[0];
                $resultado_desagregacion->mechanical_skin = $results[1];
                $resultado_desagregacion->stress_skin = $results[2];
                $resultado_desagregacion->pseudo_skin = $results[3];
                $resultado_desagregacion->rate_skin = $results[4];
                $resultado_desagregacion->permeability_module = $modulo_permeabilidad;
                $resultado_desagregacion->friction_coefficient = $coeficiente_friccion;
                $resultado_desagregacion->save();

                /* Resultados radios */
                $resultado_ri = DB::table('radios_resultado_desagregacion')->where('id_desagregacion', $desagregacion->id)->first();
                if (!is_null($resultado_ri)) {
                    DB::table('radios_resultado_desagregacion')->where('id_desagregacion', $desagregacion->id)->delete();
                }
                foreach ($ri as $value) {
                    $resultado_ri = new radios_resultado_desagregacion;
                    $resultado_ri->id_desagregacion = $desagregacion->id;
                    $resultado_ri->radio = $value;
                    $resultado_ri->save();
                }

                /* Resultados permeabilidades */
                $resultado_skin_by_stress = DB::table('permeabilidades_resultado_desagregacion')->where('id_desagregacion', $desagregacion->id)->first();
                if (!is_null($resultado_skin_by_stress)) {
                    DB::table('permeabilidades_resultado_desagregacion')->where('id_desagregacion', $desagregacion->id)->delete();
                }
                foreach ($skin_by_stress as $value) {
                    $resultado_skin_by_stress = new permeabilidades_resultado_desagregacion;
                    $resultado_skin_by_stress->id_desagregacion = $desagregacion->id;
                    $resultado_skin_by_stress->permeabilidad = $value;
                    $resultado_skin_by_stress->save();
                }

                $results = json_encode($results);
                $radios = json_encode($radios);
                $permeabilidades = json_encode($permeabilidades);
                $ri = json_encode($ri);
                $skin_by_stress = json_encode($skin_by_stress);

                $scenary_s = DB::table('escenarios')->where('id', $desagregacion->id_escenario)->first();

                $intervalo = DB::table('formacionxpozos')->where('id', $scenary_s->formacion_id)->first();
                $campo = DB::table('campos')->where('id', $scenary_s->campo_id)->first();

                return view('desagregacion.show', compact('results', 'formacion', 'cuenca', 'pozo', 'ri', 'skin_by_stress', 'desagregacion', 'suma', 'total', 'modulo_permeabilidad', 'coeficiente_friccion', 'scenary_s', 'intervalo', 'campo'));
            } else {
                return view('projectmanagement');
            }
        } else {
            return view('loginfirst');
        }
    }

    /**
     * Organiza los datos requeridos para mostrar los resultados y los envía a la vista "show".
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (\Auth::check()) {

            $scenaryId = $id;

            $desagregacion = DB::table('desagregacion')->where('id_escenario', $scenaryId)->first();

            $scenary_s = DB::table('escenarios')->where('id', $desagregacion->id_escenario)->first();

            $pozo = DB::table('pozos')->where('id', $scenary_s->pozo_id)->first();
            $formacion = DB::table('formaciones')->where('id', $scenary_s->formacion_id)->first();
            $intervalo = DB::table('formacionxpozos')->where('id', $scenary_s->formacion_id)->where('pozo_id', $scenary_s->pozo_id)->first();
            $campo = DB::table('campos')->where('id', $scenary_s->campo_id)->first();

            $ri_array_query = DB::table('radios_resultado_desagregacion')->where('id_desagregacion', $desagregacion->id)->get();
            $skin_by_stress_array_query = DB::table('permeabilidades_resultado_desagregacion')->where('id_desagregacion', $desagregacion->id)->get();

            $ri = [];
            foreach ($ri_array_query as $key => $value) {
                $ri[$key] = $value->radio;
            }

            $skin_by_stress = [];
            foreach ($skin_by_stress_array_query as $key => $value) {
                $skin_by_stress[$key] = $value->permeabilidad;
            }

            $ri = json_encode($ri);
            $skin_by_stress = json_encode($skin_by_stress);

            $total = 0;
            $suma = 0;
            $coeficiente_friccion = 0;
            $modulo_permeabilidad = 0;
            $results = json_encode([]);

            $disaggregation_results_query = DB::table('resultado_desagregacion')->where('id_desagregacion', $desagregacion->id)->first();
            $total = $disaggregation_results_query->total_skin;
            $suma = $disaggregation_results_query->mechanical_skin + $disaggregation_results_query->stress_skin + $disaggregation_results_query->pseudo_skin + $disaggregation_results_query->rate_skin;
            $coeficiente_friccion = $disaggregation_results_query->friction_coefficient;
            $modulo_permeabilidad = $disaggregation_results_query->permeability_module;
            $results = json_encode(array($disaggregation_results_query->total_skin, $disaggregation_results_query->mechanical_skin, $disaggregation_results_query->stress_skin, $disaggregation_results_query->pseudo_skin, $disaggregation_results_query->rate_skin));

            return view('desagregacion.show', compact('results', 'formacion', 0, 'pozo', 'ri', 'skin_by_stress', 'desagregacion', 'suma', 'total', 'modulo_permeabilidad', 'coeficiente_friccion', 'scenary_s', 'intervalo', 'campo'));
        } else {
            return view('loginfirst');
        }
    }

    public function edit($scenario_id)
    {
        if (\Auth::check()) {

            $scenario = escenario::find($scenario_id);

            $disaggregation = DB::table('desagregacion')->where('id_escenario', $scenario_id)->first();
            #$disaggregation = desagregacion::find($id);

            /* Leyendo datos desde base de datos */
            $hidraulic_units_data_query = DB::table('desagregacion_tabla')->where('id_desagregacion', $disaggregation->id)->get();

            //dd($hidraulic_units_data_query, $disaggregation->id);

            /* Organizando datos para tablas */
            $hidraulic_units_data = [];
            foreach ($hidraulic_units_data_query as $value) {
                array_push($hidraulic_units_data, array($value->espesor, $value->fzi, $value->porosidad_promedio, $value->permeabilidad));
            }

            $hidraulic_units_data_saved = $hidraulic_units_data;

            //$duplicateFrom = isset($_SESSION['scenary_id_dup']) ? $_SESSION['scenary_id_dup'] : null;
            return View::make('desagregacion.edit', compact(['scenario_id', 'scenario', 'disaggregation', 'hidraulic_units_data', 'hidraulic_units_data_saved']));
        } else {
            return view('loginfirst');
        }
    }

    /**
     * Actualiza un escenario de desagregación y todos sus componentes con base en un id específico.
     *
     * @param  \Illuminate\Http\disaggregation_request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(disaggregation_request $request, $id)
    {
        if (\Auth::check()) {
            $scenaryId = $request->scenario_id;
            $scenary = escenario::find($scenaryId);

            DB::table('desagregacion')->where('id_escenario', $scenaryId)->update([
                'well_radius' => $request->well_radius !== "" ? $request->well_radius : null,
                'reservoir_pressure' => $request->reservoir_pressure !== "" ? $request->reservoir_pressure : null,
                'measured_well_depth' => $request->measured_well_depth !== "" ? $request->measured_well_depth : null,
                'true_vertical_depth' => $request->true_vertical_depth !== "" ? $request->true_vertical_depth : null,
                'formation_thickness' => $request->formation_thickness !== "" ? $request->formation_thickness : null,
                'perforated_thickness' => $request->perforated_thickness !== "" ? $request->perforated_thickness : null,
                'well_completitions' => $request->well_completitions !== "" ? $request->well_completitions : null,
                'perforation_penetration_depth' => $request->perforation_penetration_depth !== "" ? $request->perforation_penetration_depth : null,
                'perforating_phase_angle' => $request->perforating_phase_angle !== "" ? $request->perforating_phase_angle : null,
                'perforating_radius' => $request->perforating_radius !== "" ? $request->perforating_radius : null,
                'production_formation_thickness' => $request->production_formation_thickness !== "" ? $request->production_formation_thickness : null,
                'horizontal_vertical_permeability_ratio' => $request->horizontal_vertical_permeability_ratio !== "" ? $request->horizontal_vertical_permeability_ratio : null,
                'drainage_area_shape' => $request->drainage_area_shape !== "" ? $request->drainage_area_shape : null,
                'fluid_of_interest' => $request->fluid_of_interest !== "" ? $request->fluid_of_interest : null,
                'skin' => $request->skin !== "" ? $request->skin : null,
                'permeability' => $request->permeability !== "" ? $request->permeability : null,
                'rock_type' => $request->rock_type !== "" ? $request->rock_type : null,
                'porosity' => $request->porosity !== "" ? $request->porosity : null,
                'status_wr' => $request->only_s == "save" ? 1 : 0,
                'id_escenario' => $scenaryId,
            ]);

            if ($request->input('fluid_of_interest') == 1) {
                DB::table('desagregacion')->where('id_escenario', $scenaryId)->update([
                    'oil_rate' => $request->input('oil_rate'),
                    'oil_bottomhole_flowing_pressure' => $request->input('oil_bottomhole_flowing_pressure'),
                    'oil_viscosity' => $request->input('oil_viscosity'),
                    'oil_volumetric_factor' => $request->input('oil_volumetric_factor'),
                ]);
            } elseif ($request->input('fluid_of_interest') == 2) {
                DB::table('desagregacion')->where('id_escenario', $scenaryId)->update([
                    'gas_rate' => $request->input('gas_rate'),
                    'gas_bottomhole_flowing_pressure' => $request->input('gas_bottomhole_flowing_pressure'),
                    'gas_viscosity' => $request->input('gas_viscosity'),
                    'gas_volumetric_factor' => $request->input('gas_volumetric_factor'),
                    'gas_specific_gravity' => $request->input('gas_specific_gravity'),
                ]);
            } elseif ($request->input('fluid_of_interest') == 3) {
                DB::table('desagregacion')->where('id_escenario', $scenaryId)->update([
                    'water_rate' => $request->input('water_rate'),
                    'water_bottomhole_flowing_pressure' => $request->input('water_bottomhole_flowing_pressure'),
                    'water_viscosity' => $request->input('water_viscosity'),
                    'water_volumetric_factor' => $request->input('water_volumetric_factor'),
                ]);
            }elseif ($request->input('fluid_of_interest') == 4) {
                if ($request->input('emulsion') == 1) {
                    if ($request->input('characterized_mixture') == 1) {
                        DB::table('desagregacion')->where('id_escenario', $scenaryId)->update([
                            'emulsion' => $request->input('emulsion'),
                            'characterized_mixture' => $request->input('characterized_mixture'),
                            'flow_rate_1_1' => $request->input('flow_rate_1_1'),
                            'mixture_bottomhole_flowing_pressure_1_1' => $request->input('mixture_bottomhole_flowing_pressure_1_1'),
                            'mixture_viscosity_1_1' => $request->input('mixture_viscosity_1_1'),
                            'mixture_oil_volumetric_factor_1_1' => $request->input('mixture_oil_volumetric_factor_1_1'),
                            'mixture_water_volumetric_factor_1_1' => $request->input('mixture_water_volumetric_factor_1_1'),
                            'mixture_oil_fraction_1_1' => $request->input('mixture_oil_fraction_1_1'),
                            'mixture_water_fraction_1_1' => $request->input('mixture_water_fraction_1_1'),
                            'flow_rate_1_2' => null,
                            'mixture_bottomhole_flowing_pressure_1_2' => null,
                            'mixture_oil_viscosity_1_2' => null,
                            'mixture_water_viscosity_1_2' => null,
                            'mixture_oil_fraction_1_2' => null,
                            'mixture_water_fraction_1_2' => null,
                            'mixture_oil_volumetric_factor_1_2' => null,
                            'mixture_water_volumetric_factor_1_2' => null,
                            'flow_rate_2' => null,
                            'mixture_bottomhole_flowing_pressure_2' => null,
                            'mixture_oil_viscosity_2' => null,
                            'mixture_water_viscosity_2' => null,
                            'mixture_oil_fraction_2' => null,
                            'mixture_water_fraction_2' => null,
                            'mixture_oil_volumetric_factor_2' => null,
                            'mixture_water_volumetric_factor_2' => null,
                        ]);
                    } elseif ($request->input('characterized_mixture') == 2) {
                        DB::table('desagregacion')->where('id_escenario', $scenaryId)->update([
                            'emulsion' => $request->input('emulsion'),
                            'characterized_mixture' => $request->input('characterized_mixture'),
                            'flow_rate_1_2' => $request->input('flow_rate_1_2'),
                            'mixture_bottomhole_flowing_pressure_1_2' => $request->input('mixture_bottomhole_flowing_pressure_1_2'),
                            'mixture_oil_viscosity_1_2' => $request->input('mixture_oil_viscosity_1_2'),
                            'mixture_water_viscosity_1_2' => $request->input('mixture_water_viscosity_1_2'),
                            'mixture_oil_fraction_1_2' => $request->input('mixture_oil_fraction_1_2'),
                            'mixture_water_fraction_1_2' => $request->input('mixture_water_fraction_1_2'),
                            'mixture_oil_volumetric_factor_1_2' => $request->input('mixture_oil_volumetric_factor_1_2'),
                            'mixture_water_volumetric_factor_1_2' => $request->input('mixture_water_volumetric_factor_1_2'),
                            'flow_rate_1_1' => null,
                            'mixture_bottomhole_flowing_pressure_1_1' => null,
                            'mixture_viscosity_1_1' => null,
                            'mixture_oil_volumetric_factor_1_1' => null,
                            'mixture_water_volumetric_factor_1_1' => null,
                            'mixture_oil_fraction_1_1' => null,
                            'mixture_water_fraction_1_1' => null,
                            'flow_rate_2' => null,
                            'mixture_bottomhole_flowing_pressure_2' => null,
                            'mixture_oil_viscosity_2' => null,
                            'mixture_water_viscosity_2' => null,
                            'mixture_oil_fraction_2' => null,
                            'mixture_water_fraction_2' => null,
                            'mixture_oil_volumetric_factor_2' => null,
                            'mixture_water_volumetric_factor_2' => null,
                        ]);
                    }
                }elseif ($request->input('emulsion') == 2) {
                    DB::table('desagregacion')->where('id_escenario', $scenaryId)->update([
                        'characterized_mixture' => null,
                        'emulsion' => $request->input('emulsion'),
                        'flow_rate_2' => $request->input('flow_rate_2'),
                        'mixture_bottomhole_flowing_pressure_2' => $request->input('mixture_bottomhole_flowing_pressure_2'),
                        'mixture_oil_viscosity_2' => $request->input('mixture_oil_viscosity_2'),
                        'mixture_water_viscosity_2' => $request->input('mixture_water_viscosity_2'),
                        'mixture_oil_fraction_2' => $request->input('mixture_oil_fraction_2'),
                        'mixture_water_fraction_2' => $request->input('mixture_water_fraction_2'),
                        'mixture_oil_volumetric_factor_2' => $request->input('mixture_oil_volumetric_factor_2'),
                        'mixture_water_volumetric_factor_2' => $request->input('mixture_water_volumetric_factor_2'),
                        'flow_rate_1_1' => null,
                        'mixture_bottomhole_flowing_pressure_1_1' => null,
                        'mixture_viscosity_1_1' => null,
                        'mixture_oil_volumetric_factor_1_1' => null,
                        'mixture_water_volumetric_factor_1_1' => null,
                        'mixture_oil_fraction_1_1' => null,
                        'mixture_water_fraction_1_1' => null,
                        'flow_rate_1_2' => null,
                        'mixture_bottomhole_flowing_pressure_1_2' => null,
                        'mixture_oil_viscosity_1_2' => null,
                        'mixture_water_viscosity_1_2' => null,
                        'mixture_oil_fraction_1_2' => null,
                        'mixture_water_fraction_1_2' => null,
                        'mixture_oil_volumetric_factor_1_2' => null,
                        'mixture_water_volumetric_factor_1_2' => null,
                    ]);
                }
            }

            $desagregacion = DB::table('desagregacion')->where('id_escenario', $scenaryId)->first();

            $desagregacion_tabla = DB::table('desagregacion_tabla')->where('id_desagregacion', $desagregacion->id)->first();
            if (!is_null($desagregacion_tabla)) {
                DB::table('desagregacion_tabla')->where('id_desagregacion', $desagregacion->id)->delete();
            }
            $tabla = str_replace(",[null,null,null,null]", "", $request->input("unidades_table_hidden"));
            $tabla = json_decode($tabla);
            foreach ($tabla as $value) {
                $desagregacion_tabla = new desagregacion_tabla;
                $desagregacion_tabla->Espesor = str_replace(",", ".", $value[0]);
                $desagregacion_tabla->fzi = str_replace(",", ".", $value[1]);
                $desagregacion_tabla->porosidad_promedio = str_replace(",", ".", $value[2]);
                $desagregacion_tabla->permeabilidad = str_replace(",", ".", $value[3]);
                $desagregacion_tabla->id_desagregacion = $desagregacion->id;
                $desagregacion_tabla->save();
            }

            $scenary->completo = $request->only_s == "save" ? 0 : 1;
            $scenary->estado = 1;
            $scenary->save();

            if (!$desagregacion->status_wr) {
                $pozo = DB::table('pozos')->find($scenary->pozo_id);
                $cuenca = DB::table('cuencas')->find($scenary->cuenca_id);
                $formacion = DB::table('formacionxpozos')->where('nombre', $scenary->formacion_id)->first();

                $arreglo = json_decode($request->get("unidades_table_hidden"));
                $datos_unidades_hidraulicas = array();
                foreach ($arreglo as $value) {
                    if ($value[0] != null) {
                        $datos_unidades_hidraulicas[] = $value;
                    }
                }

                $hidraulic_units_data = json_encode($datos_unidades_hidraulicas);

                $well_radius = $request->get("well_radius");
                $reservoir_pressure = $request->get("reservoir_pressure");
                $measured_well_depth = $request->get("measured_well_depth");
                $true_vertical_depth = $request->get("true_vertical_depth");
                $formation_thickness = $request->get("formation_thickness");
                $perforated_thickness = $request->get("perforated_thickness");
                $well_completitions = $request->get("well_completitions");
                $perforation_penetration_depth = $request->get("perforation_penetration_depth");
                $perforating_phase_angle = $request->get("perforating_phase_angle");
                $perforating_radius = $request->get("perforating_radius");
                $production_formation_thickness = $request->get("production_formation_thickness");
                $horizontal_vertical_permeability_ratio = $request->get("horizontal_vertical_permeability_ratio");
                $drainage_area_shape = $request->get("drainage_area_shape");
                $fluid_of_interest = $request->get("fluid_of_interest");
                $skin = $request->get("skin");
                $permeability = $request->get("permeability");
                $rock_type = $request->get("rock_type");
                $porosity = $request->get("porosity");

                if ($request->input('fluid_of_interest') == 1) {
                    $fluid_rate = $request->get("oil_rate");
                    $bottomhole_flowing_pressure = $request->get("oil_bottomhole_flowing_pressure");
                    $fluid_viscosity = $request->get("oil_viscosity");
                    $fluid_volumetric_factor = $request->get("oil_volumetric_factor");
                    $emulsion = $characterized_mixture = $mixture_rate = $mixture_bottomhole_flowing_pressure = $mixture_viscosity = $mixture_volumetric_factor = $mixture_oil_fraction_1_1 = $mixture_water_fraction_1_1 = $flow_rate_1_1 = $mixture_bottomhole_flowing_pressure_1_1 = $mixture_viscosity_1_1 = $mixture_oil_volumetric_factor_1_1 = $mixture_water_volumetric_factor_1_1 = $mixture_oil_fraction_1_1 = $mixture_water_fraction_1_1 = $flow_rate_1_2 = $mixture_bottomhole_flowing_pressure_1_2 = $mixture_oil_viscosity_1_2 = $mixture_water_viscosity_1_2 = $mixture_oil_fraction_1_2 = $mixture_water_fraction_1_2 = $mixture_oil_volumetric_factor_1_2 = $mixture_water_volumetric_factor_1_2 = $flow_rate_2 = $mixture_bottomhole_flowing_pressure_2 = $mixture_oil_viscosity_2 = $mixture_water_viscosity_2 = $mixture_oil_fraction_2 = $mixture_water_fraction_2 = $mixture_oil_volumetric_factor_2 = $mixture_water_volumetric_factor_2 = $fluid_specific_gravity = 1;
                } elseif ($request->input('fluid_of_interest') == 2) {
                    $fluid_rate = $request->get("gas_rate");
                    $bottomhole_flowing_pressure = $request->get("gas_bottomhole_flowing_pressure");
                    $fluid_viscosity = $request->get("gas_viscosity");
                    $fluid_volumetric_factor = $request->get("gas_volumetric_factor");
                    $fluid_specific_gravity = $request->get("gas_specific_gravity");
                    $emulsion = $characterized_mixture = $mixture_rate = $mixture_bottomhole_flowing_pressure = $mixture_viscosity = $mixture_volumetric_factor = $mixture_oil_fraction_1_1 = $mixture_water_fraction_1_1 = $flow_rate_1_1 = $mixture_bottomhole_flowing_pressure_1_1 = $mixture_viscosity_1_1 = $mixture_oil_volumetric_factor_1_1 = $mixture_water_volumetric_factor_1_1 = $mixture_oil_fraction_1_1 = $mixture_water_fraction_1_1 = $flow_rate_1_2 = $mixture_bottomhole_flowing_pressure_1_2 = $mixture_oil_viscosity_1_2 = $mixture_water_viscosity_1_2 = $mixture_oil_fraction_1_2 = $mixture_water_fraction_1_2 = $mixture_oil_volumetric_factor_1_2 = $mixture_water_volumetric_factor_1_2 = $flow_rate_2 = $mixture_bottomhole_flowing_pressure_2 = $mixture_oil_viscosity_2 = $mixture_water_viscosity_2 = $mixture_oil_fraction_2 = $mixture_water_fraction_2 = $mixture_oil_volumetric_factor_2 = $mixture_water_volumetric_factor_2 = 1;
                } elseif ($request->input('fluid_of_interest') == 3) {
                    $fluid_rate = $request->get("water_rate");
                    $bottomhole_flowing_pressure = $request->get("water_bottomhole_flowing_pressure");
                    $fluid_viscosity = $request->get("water_viscosity");
                    $fluid_volumetric_factor = $request->get("water_volumetric_factor");
                    $emulsion = $characterized_mixture = $mixture_rate = $mixture_bottomhole_flowing_pressure = $mixture_viscosity = $mixture_volumetric_factor = $mixture_oil_fraction_1_1 = $mixture_water_fraction_1_1 = $flow_rate_1_1 = $mixture_bottomhole_flowing_pressure_1_1 = $mixture_viscosity_1_1 = $mixture_oil_volumetric_factor_1_1 = $mixture_water_volumetric_factor_1_1 = $mixture_oil_fraction_1_1 = $mixture_water_fraction_1_1 = $flow_rate_1_2 = $mixture_bottomhole_flowing_pressure_1_2 = $mixture_oil_viscosity_1_2 = $mixture_water_viscosity_1_2 = $mixture_oil_fraction_1_2 = $mixture_water_fraction_1_2 = $mixture_oil_volumetric_factor_1_2 = $mixture_water_volumetric_factor_1_2 = $flow_rate_2 = $mixture_bottomhole_flowing_pressure_2 = $mixture_oil_viscosity_2 = $mixture_water_viscosity_2 = $mixture_oil_fraction_2 = $mixture_water_fraction_2 = $mixture_oil_volumetric_factor_2 = $mixture_water_volumetric_factor_2 = $fluid_specific_gravity = 1;
                }elseif ($request->input('fluid_of_interest') == 4) {
                    if ($request->input('emulsion') == 1) {
                        if ($request->input('characterized_mixture') == 1) {
                            $emulsion = $request->get('emulsion');
                            $characterized_mixture = $request->get('characterized_mixture');
                            $flow_rate_1_1 = $request->get('flow_rate_1_1');
                            $mixture_bottomhole_flowing_pressure_1_1 = $request->get('mixture_bottomhole_flowing_pressure_1_1');
                            $mixture_viscosity_1_1 = $request->get('mixture_viscosity_1_1');
                            $mixture_oil_volumetric_factor_1_1 = $request->get('mixture_oil_volumetric_factor_1_1');
                            $mixture_water_volumetric_factor_1_1 = $request->get('mixture_water_volumetric_factor_1_1');
                            $mixture_oil_fraction_1_1 = $request->get('mixture_oil_fraction_1_1');
                            $mixture_water_fraction_1_1 = $request->get('mixture_water_fraction_1_1');
                            $fluid_rate = $bottomhole_flowing_pressure = $fluid_viscosity = $fluid_volumetric_factor = $flow_rate_1_2 = $mixture_bottomhole_flowing_pressure_1_2 = $mixture_oil_viscosity_1_2 = $mixture_water_viscosity_1_2 = $mixture_oil_fraction_1_2 = $mixture_water_fraction_1_2 = $mixture_oil_volumetric_factor_1_2 = $mixture_water_volumetric_factor_1_2 = $flow_rate_2 = $mixture_bottomhole_flowing_pressure_2 = $mixture_oil_viscosity_2 = $mixture_water_viscosity_2 = $mixture_oil_fraction_2 = $mixture_water_fraction_2 = $mixture_oil_volumetric_factor_2 = $mixture_water_volumetric_factor_2 = $fluid_specific_gravity = 1;
                        } elseif ($request->input('characterized_mixture') == 2) {
                            $emulsion = $request->get('emulsion');
                            $characterized_mixture = $request->get('characterized_mixture');
                            $flow_rate_1_2 = $request->get('flow_rate_1_2');
                            $mixture_bottomhole_flowing_pressure_1_2 = $request->get('mixture_bottomhole_flowing_pressure_1_2');
                            $mixture_oil_viscosity_1_2 = $request->get('mixture_oil_viscosity_1_2');
                            $mixture_water_viscosity_1_2 = $request->get('mixture_water_viscosity_1_2');
                            $mixture_oil_fraction_1_2 = $request->get('mixture_oil_fraction_1_2');
                            $mixture_water_fraction_1_2 = $request->get('mixture_water_fraction_1_2');
                            $mixture_oil_volumetric_factor_1_2 = $request->get('mixture_oil_volumetric_factor_1_2');
                            $mixture_water_volumetric_factor_1_2 = $request->get('mixture_water_volumetric_factor_1_2');
                            $fluid_rate = $bottomhole_flowing_pressure = $fluid_viscosity = $fluid_volumetric_factor = $flow_rate_1_1 = $mixture_bottomhole_flowing_pressure_1_1 = $mixture_viscosity_1_1 = $mixture_oil_volumetric_factor_1_1 = $mixture_water_volumetric_factor_1_1 = $mixture_oil_fraction_1_1 = $mixture_water_fraction_1_1 = $flow_rate_2 = $mixture_bottomhole_flowing_pressure_2 = $mixture_oil_viscosity_2 = $mixture_water_viscosity_2 = $mixture_oil_fraction_2 = $mixture_water_fraction_2 = $mixture_oil_volumetric_factor_2 = $mixture_water_volumetric_factor_2 = $fluid_specific_gravity = 1;
                        }
                    }elseif ($request->input('emulsion') === 2) {
                        $emulsion = $request->get('emulsion');
                        $flow_rate_2 = $request->get('flow_rate_2');
                        $mixture_bottomhole_flowing_pressure_2 = $request->get('mixture_bottomhole_flowing_pressure_2');
                        $mixture_oil_viscosity_2 = $request->get('mixture_oil_viscosity_2');
                        $mixture_water_viscosity_2 = $request->get('mixture_water_viscosity_2');
                        $mixture_oil_fraction_2 = $request->get('mixture_oil_fraction_2');
                        $mixture_water_fraction_2 = $request->get('mixture_water_fraction_2');
                        $mixture_oil_volumetric_factor_2 = $request->get('mixture_oil_volumetric_factor_2');
                        $mixture_water_volumetric_factor_2 = $request->get('mixture_water_volumetric_factor_2');
                        $fluid_rate = $bottomhole_flowing_pressure = $fluid_viscosity = $fluid_volumetric_factor = $characterized_mixture = $flow_rate_1_1 = $mixture_bottomhole_flowing_pressure_1_1 = $mixture_viscosity_1_1 = $mixture_oil_volumetric_factor_1_1 = $mixture_water_volumetric_factor_1_1 = $mixture_oil_fraction_1_1 = $mixture_water_fraction_1_1 = $flow_rate_1_2 = $mixture_bottomhole_flowing_pressure_1_2 = $mixture_oil_viscosity_1_2 = $mixture_water_viscosity_1_2 = $mixture_oil_fraction_1_2 = $mixture_water_fraction_1_2 = $mixture_oil_volumetric_factor_1_2 = $mixture_water_volumetric_factor_1_2 = $fluid_specific_gravity = 1;
                    }
                }

                $results = 0;
                $radios = 0;
                $permeabilidades = 0;
                $coeficiente_friccion = 0;
                $modulo_permeabilidad = 0;

                $results = $this->run_disaggregation_analysis($well_radius, $reservoir_pressure, $measured_well_depth, $true_vertical_depth, $formation_thickness, $perforated_thickness, $well_completitions, $perforation_penetration_depth, $perforating_phase_angle, $perforating_radius, $production_formation_thickness, $horizontal_vertical_permeability_ratio, $drainage_area_shape, $fluid_rate, $bottomhole_flowing_pressure, $fluid_viscosity, $fluid_volumetric_factor, $fluid_specific_gravity, $skin, $permeability, $rock_type, $porosity, $hidraulic_units_data, $emulsion, $characterized_mixture, $flow_rate_1_1, $mixture_bottomhole_flowing_pressure_1_1, $mixture_viscosity_1_1, $mixture_oil_volumetric_factor_1_1, $mixture_water_volumetric_factor_1_1, $mixture_oil_fraction_1_1, $mixture_water_fraction_1_1, $flow_rate_1_2, $mixture_bottomhole_flowing_pressure_1_2, $mixture_oil_viscosity_1_2, $mixture_water_viscosity_1_2, $mixture_oil_fraction_1_2, $mixture_water_fraction_1_2, $mixture_oil_volumetric_factor_1_2, $mixture_water_volumetric_factor_1_2, $flow_rate_2, $mixture_bottomhole_flowing_pressure_2, $mixture_oil_viscosity_2, $mixture_water_viscosity_2, $mixture_oil_fraction_2, $mixture_water_fraction_2, $mixture_oil_volumetric_factor_2, $mixture_water_volumetric_factor_2, $fluid_of_interest);

                $radios = $results[5];
                $permeabilidades = $results[6];
                $coeficiente_friccion = $results[7];
                $modulo_permeabilidad = $results[8];
                $suma = $results[1] + $results[2] + $results[3] + $results[4];
                $total = $results[0];
                $ri = $results[9];
                $skin_by_stress = $results[10];
                $skin_by_stress = $skin_by_stress[0];
                $results = array_slice($results, 0, 5);

                /* Resultados spider */
                $resultado_desagregacion = DB::table('resultado_desagregacion')->where('id_desagregacion', $desagregacion->id)->first();
                if (!is_null($resultado_desagregacion)) {
                    DB::table('resultado_desagregacion')->where('id_desagregacion', $desagregacion->id)->delete();
                }
                $resultado_desagregacion = new resultado_desagregacion;
                $resultado_desagregacion->id_desagregacion = $desagregacion->id;
                $resultado_desagregacion->total_skin = $results[0];
                $resultado_desagregacion->mechanical_skin = $results[1];
                $resultado_desagregacion->stress_skin = $results[2];
                $resultado_desagregacion->pseudo_skin = $results[3];
                $resultado_desagregacion->rate_skin = $results[4];
                $resultado_desagregacion->permeability_module = $modulo_permeabilidad;
                $resultado_desagregacion->friction_coefficient = $coeficiente_friccion;
                $resultado_desagregacion->save();

                /* Resultados radios */
                $resultado_radios = DB::table('radios_resultado_desagregacion')->where('id_desagregacion', $desagregacion->id)->first();
                if (!is_null($resultado_radios)) {
                    DB::table('radios_resultado_desagregacion')->where('id_desagregacion', $desagregacion->id)->delete();
                }
                foreach ($radios as $value) {
                    $resultado_radios = new radios_resultado_desagregacion;
                    $resultado_radios->id_desagregacion = $desagregacion->id;
                    $resultado_radios->radio = $value;
                    $resultado_radios->save();
                }

                /* Resultados permeabilidades */
                $resultado_permeabilidades = DB::table('permeabilidades_resultado_desagregacion')->where('id_desagregacion', $desagregacion->id)->first();
                if (!is_null($resultado_permeabilidades)) {
                    DB::table('permeabilidades_resultado_desagregacion')->where('id_desagregacion', $desagregacion->id)->delete();
                }
                foreach ($permeabilidades as $value) {
                    $resultado_permeabilidades = new permeabilidades_resultado_desagregacion;
                    $resultado_permeabilidades->id_desagregacion = $desagregacion->id;
                    $resultado_permeabilidades->permeabilidad = $value;
                    $resultado_permeabilidades->save();
                }

                $results = json_encode($results);
                $radios = json_encode($radios);
                $permeabilidades = json_encode($permeabilidades);
                $ri = json_encode($ri);
                $skin_by_stress = json_encode($skin_by_stress);

                $scenary_s = DB::table('escenarios')->where('id', $desagregacion->id_escenario)->first();

                $intervalo = DB::table('formacionxpozos')->where('id', $scenary_s->formacion_id)->first();
                $campo = DB::table('campos')->where('id', $scenary_s->campo_id)->first();

                return view('desagregacion.show', compact('results', 'formacion', 'cuenca', 'pozo', 'ri', 'skin_by_stress', 'desagregacion', 'suma', 'total', 'modulo_permeabilidad', 'coeficiente_friccion', 'scenary_s', 'intervalo', 'campo'));
            } else {
                return view('projectmanagement');
            }
        } else {
            return view('loginfirst');
        }
    }

    /* Módulo de cálculo */
    /* Cálculo del daño por efecto de los esfuerzos */

    /* Módulo de permeabilidad (4.3) (verificada) */
    /* Parámetro 1: @indice_zona_flujo_para_tipo_roca_j, Índice de zona de flujo para */
    /* el tipo de roca j */
    /* Parámetro 2: @tipo_roca, tipo de roca */
    /* Return: Vector de permeabilidad */
    public function permeability_module($hidraulic_units_data, $rock_type)
    {
        $calculated_hidraulic_units_data = [];

        if ($rock_type == "consolidada") {
            $a = 0.000809399;
            $b = -0.986179237;
        } else if ($rock_type == "poco consolidada") {
            $a = 0.000433696;
            $b = -0.587596095;
        } else if ($rock_type == "microfracturada") {
            $a = 0.000613657;
            $b = 0.371958564;
        }

        foreach ($hidraulic_units_data as $hidraulic_unit) {
            array_push($hidraulic_unit, ($a * (pow(floatval($hidraulic_unit[1]), $b))));
            array_push($calculated_hidraulic_units_data, $hidraulic_unit);
        }

        //dd($hidraulic_unit, $calculated_hidraulic_units_data);

        return $calculated_hidraulic_units_data;
    }

    /* Módulo de permebilidad del pozo (4.6) (Verificada) */
    /* Parámetro 1: @datos_unidades_hidraulicas, matriz con información de las unidades */
    /* hidráulicas */
    /* Return: Float del módulo de permeabilidad del pozo            */
    public function well_permeability_module($hidraulic_units_data)
    {
        $a = 0; /* Dividendo */
        $b = 0; /* Divisor */

        foreach ($hidraulic_units_data as $hidraulic_unit) {
            /* *unidad[0] = Espesor, unidad[4] = Permeabilidad promedio */
            $a += floatval($hidraulic_unit[0]) * $hidraulic_unit[4];
            $b += floatval($hidraulic_unit[0]);
        }
        //dd($a/$b, $hidraulic_units_data);
        return $a / $b; //Revisar divisiòn por cero
    }

    /* Coeficiente  de fricción (vector) (4.9) (verificada) */
    public function friction_coefficient($hidraulic_units_data)
    {
        $a = 140043503030.2;
        $b = 1.096638;
        $c = -1.588135;
        $flag = 0;

        $new_hidraulic_units_data = [];
        foreach ($hidraulic_units_data as $hidraulic_unit) {
            if ($hidraulic_unit[3] == 0) {
                $hidraulic_unit[3] = 0.000001;
                $flag = 1;
            }

            // B = a * b^porosity * permeabibility^c
            /* Posible error: división por cero */
            array_push($hidraulic_unit, ($a * pow($b, $hidraulic_unit[2]) * pow($hidraulic_unit[3], $c)));

            if ($flag == 1) {
                $hidraulic_unit[3] = 0;
            }

            array_push($new_hidraulic_units_data, $hidraulic_unit);
        }

        return $new_hidraulic_units_data;
    }

    /* -Coeficiente de fricción del pozo (escalar) (4.10) (verificada) */
    public function well_friction_coefficient($hidraulic_units_data)
    {
        $a = 0; #Numerador
        $b = 1; #Denominador
        foreach ($hidraulic_units_data as $hidraulic_unit) {
            $a += $hidraulic_unit[0] * $hidraulic_unit[5];
            $b += $hidraulic_unit[0];
        }

        return $a / $b;
    }

    /* Presión de poro (4.2) (Verificada) */
    /* Parámetro 1: @presion_fondo_pozo, Presión en el fondo del pozo */
    /* Parámetro 2: @caudal_produccion_aceite, Caudal de produccion de aceite */
    /* Parámetro 3: @viscosidad_aceite, Viscosidad del aceite */
    /* Parámetro 4: @factor_volumetrico_aceite, Factor volumétrico del aceite */
    /* Parámetro 5: @permeabilidad_estimada_para_formacion, */
    /* Permeabilidad estimada para la formación */
    /* Parámetro 6: @espesor_formacion_productora, Espesor de la formación productora */
    /* Parámetro 7: @radio_pozo, Radio del pozo */
    /* Parámetro 8: @radio_drenaje_pozo, Radio de drenaje del pozo */
    /* Parámetro 9: @dano_total_pozo, Daño total en el pozo */
    /* Parámetro 10: @presion_promedio_yacimiento, Presión promedio del yacimiento */
    /* Return: Vector de presión de poro */
    public function pore_pressure($bottomhole_flowing_pressure, $fluid_rate, $fluid_viscosity, $fluid_volumetric_factor, $permeability, $formation_thickness, $well_radius, $skin, $reservoir_pressure)
    {
        $pore_pressure_at_point_i = [];
        $well_point_i_distance = $this->well_point_i_distance($well_radius, 10);

        /* El primer valor de presión es la presión en el fondo del pozo*/
        //array_push($pore_pressure_at_point_i, $bottomhole_flowing_pressure);

        /* Se llena los valores de presión para cada punto del vector de distancias*/
        foreach ($well_point_i_distance as $key => $pressure) {
            $pressure = $bottomhole_flowing_pressure + (((141.2 * $fluid_rate * $fluid_viscosity * $fluid_volumetric_factor) / ($permeability * $formation_thickness)) * (log($well_point_i_distance[$key] / $well_radius) - 0.75 + $skin));

            //dd($fluid_viscosity);

            //dd($bottomhole_flowing_pressure, $fluid_rate, $fluid_viscosity, $fluid_volumetric_factor, $permeability, $formation_thickness, $well_point_i_distance[$key] ,$well_radius, $pressure);

            /** Si la presión calculada es igual o superior a la presión promedio, se ignora la calculada y se coloca en su lugar la promedio */

            if ($pressure >= $reservoir_pressure) {
                $pressure = $reservoir_pressure;
            }

            array_push($pore_pressure_at_point_i, $pressure);
        }

        //dd($well_point_i_distance, $pore_pressure_at_point_i, $reservoir_pressure);

        return $pore_pressure_at_point_i;
    }

    /**Distancia del pozo al punto i*/
    /* Parámetro 1: @well_radius, radio del pozo */
    /* Parámetro 2: @drainage_radius, radio de drenaje del pozo */
    /* Return: Vector de intervalos de distancias del pozo a distintos puntos */
    public function well_point_i_distance($well_radius, $drainage_radius)
    {
        $well_point_i_distance = [];
        #Distancia en la que aumentan los puntos a partir de los 10 pies
        $longitude = 1;
        $cont_1 = 0;

        /**Se establecen las distancias del pozo a los puntos a estudiar. Los primeros 10 pies se estudian con minuciosidad,  por ello las distancias aumentan 0.2 */
        while ($well_radius + (0.1 * $cont_1) <= 10) {
            array_push($well_point_i_distance, ($well_radius + (0.1 * $cont_1)));
            $cont_1++;
        }

        /* Después de los 10 pies las distancias aumentan en la longitud ya calculada */
        for ($j = 0; $j < 100; $j++) {
            $element = ($longitude + $well_point_i_distance[count($well_point_i_distance) - 1]); /* Revisar último índice */
            if ($element <= $drainage_radius) {
                array_push($well_point_i_distance, ($longitude + $well_point_i_distance[count($well_point_i_distance) - 1]));
            }

        }

        //array_push($well_point_i_distance, $drainage_radius);

        return $well_point_i_distance;
    }

    /**Esfuerzo efectivo (4.5) (verificada) */
    /* Parámetro 1: @esfuerzo_total, esfuerzo total */
    /* Parámetro 2: @presion_poro, vector de presión de poro en el punto i */
    /* Return: Vector del esfuerzo efectivo */
    public function effective_stress($reservoir_pressure, $pore_pressure)
    {
        $effective_stress = [];

        foreach ($pore_pressure as $pressure) {
            array_push($effective_stress, ($reservoir_pressure - $pressure));
        }

        return $effective_stress;
    }

    /* Daño por esfuerzos (4.8) */
    /* Parámetro 1: @permeabilidad_original */
    /* Parámetro 2: @radio_drenaje_pozo */
    /* Parámetro 3: @radio_pozo */
    /* Parámetro 4: @permeabilidad_zona_afectada */
    public function stress_damage($well_permeability_module, $well_radius, $effective_stress)
    {
        #Promedio de permeablidad_zona_afectada de a dos, en lugar de valor puntual
        $well_point_i_distance = $this->well_point_i_distance($well_radius, 10);
        $sum = 0;

        //$arrayyyy = [];

        for ($i = 0; $i < count($well_point_i_distance) - 1; $i++) {
            $sum += (log($well_point_i_distance[$i + 1] / $well_point_i_distance[$i])) * (pow(M_E, /*(-1) * */ $well_permeability_module * $effective_stress[$i]));
            //array_push($arrayyyy, log($well_point_i_distance[$i+1] / $well_point_i_distance[$i]) / (pow(M_E, (-1) * $well_permeability_module * $effective_stress[$i])) );
            //dd($arrayyyy, $well_point_i_distance[$i+1], $well_point_i_distance[$i], $well_permeability_module, $effective_stress[$i] );
        }

        //$result = $original_permeability * $sum;
        $result = $sum - log(10 / $well_radius);

        //dd($arrayyyy );

        return array($result, $well_point_i_distance);
    }

    /* Daño por esfuerzos (4.8) */
    /* Parámetro 1: @permeabilidad_original */
    /* Parámetro 2: @radio_drenaje_pozo */
    /* Parámetro 3: @radio_pozo */
    /* Parámetro 4: @permeabilidad_zona_afectada */
    public function stress_damage_view($well_permeability_module, $well_radius, $effective_stress)
    {
        #Promedio de permeablidad_zona_afectada de a dos, en lugar de valor puntual
        $well_point_i_distance = $this->well_point_i_distance($well_radius, 10);
        $sum = [];

        for ($i = 0; $i < count($well_point_i_distance) - 1; $i++) {
            array_push($sum, (log($well_point_i_distance[$i + 1] / $well_point_i_distance[$i])) * (pow(M_E, $well_permeability_module * $effective_stress[$i])));
        }

        //$result = $original_permeability * $sum;
        $result = $sum;

        return array($result);
    }

    /* -Convierte el valor de barriles por día (bp/d) a ft^3/d */
    public function bpd_to_ftd($value, $fluid_of_interest)
    {
        if ($fluid_of_interest == 2) {
            return $value;
        }else{
            return $value * 5.615 / 1000;
        }
    }

    /* --Coeficiente de flujo no Darcy (escalar)  */
    public function non_darcy_flow_coefficient($fluid_specific_gravity, $permeability, $fluid_viscosity, $well_radius, $perforated_thickness, $well_friction_coefficient)
    {
        $a = (2.22 * pow(10, -15)) * $fluid_specific_gravity * $permeability * $well_friction_coefficient;
        $b = $fluid_viscosity * $well_radius * $perforated_thickness;

        return ($a / $b);
    }

    /* ----Daño por tasa (escalar) */
    public function damage_rate($non_darcy_flow_coefficient, $fluid_rate_in_ftpd)
    {
        return $non_darcy_flow_coefficient * $fluid_rate_in_ftpd;
    }

    public function damage_by_deflection($true_vertical_depth, $measured_well_depth, $horizontal_vertical_permeability_ratio, $formation_thickness, $well_radius)
    {
        $well_angle = asin($true_vertical_depth / $measured_well_depth);

        $pseudo_angle = atan(sqrt($horizontal_vertical_permeability_ratio) * tan($well_angle));

        $hd = ($formation_thickness / $well_radius) * (sqrt(pow($horizontal_vertical_permeability_ratio, -1)));
        $damage = ((-1) * pow($pseudo_angle / 41, 2.06)) - ((pow($pseudo_angle / 56, 1.865)) * log10($hd / 100));

        return ($damage);
    }

    public function damage_by_partial_penetration($formation_thickness, $perforated_thickness, $horizontal_vertical_permeability_ratio, $well_radius)
    {
        $damage1 = ($formation_thickness / $perforated_thickness) - 1;

        $damage2 = (sqrt(pow($horizontal_vertical_permeability_ratio, -1)) * log($formation_thickness / $well_radius)) - 2;

        return ($damage1 * $damage2);
    }

    /* -Pseudo-daño por la forma del reservorio (4.17) */
    public function pseudo_damage_reservoir_shape($drainage_area_shape)
    {
        if ($drainage_area_shape == 1) {
            $reservoir_shape = 30.88;
        } else if ($drainage_area_shape == 2) {
            $reservoir_shape = 12.99;
        } else if ($drainage_area_shape == 3) {
            $reservoir_shape = 4.51;
        } else if ($drainage_area_shape == 4) {
            $reservoir_shape = 3.34;
        } else if ($drainage_area_shape == 5) {
            $reservoir_shape = 21.84;
        } else if ($drainage_area_shape == 6) {
            $reservoir_shape = 10.84;
        } else if ($drainage_area_shape == 7) {
            $reservoir_shape = 4.51;
        } else if ($drainage_area_shape == 8) {
            $reservoir_shape = 2.08;
        } else if ($drainage_area_shape == 9) {
            $reservoir_shape = 3.16;
        } else if ($drainage_area_shape == 10) {
            $reservoir_shape = 0.581;
        } else if ($drainage_area_shape == 11) {
            $reservoir_shape = 0.111;
        } else if ($drainage_area_shape == 12) {
            $reservoir_shape = 5.38;
        } else if ($drainage_area_shape == 13) {
            $reservoir_shape = 2.69;
        } else if ($drainage_area_shape == 14) {
            $reservoir_shape = 0.232;
        } else if ($drainage_area_shape == 15) {
            $reservoir_shape = 0.116;
        } else if ($drainage_area_shape == 16) {
            $reservoir_shape = 2.36;
        }

        return (0.5 * log(31.62 / $reservoir_shape));
    }

    /* ---Pseudo-daño por cañoneo 3 (4.20) */
    public function pseudo_damage_perforation_3($phase, $cannon_penetrating_depth, $well_radius)
    {
        $alpha0 = 0;

        if ($phase == 0) {
            $alpha0 = 0.25;
        }
        if ($phase == 360) {
            $alpha0 = 0.25;
        } else if ($phase == 45) {
            $alpha0 = 0.86;
        } else if ($phase == 60) {
            $alpha0 = 0.813;
        } else if ($phase == 90) {
            $alpha0 = 0.726;
        } else if ($phase == 120) {
            $alpha0 = 0.648;
        } else if ($phase == 180) {
            $alpha0 = 0.5;
        }

        if ($phase == 0) {
            $pseudo_damage_perforation_3 = $cannon_penetrating_depth / 4;
        } else {
            $pseudo_damage_perforation_3 = ($alpha0 * ($well_radius + $cannon_penetrating_depth));
        }

        $result = log($well_radius / $pseudo_damage_perforation_3);

        return ($result);

    }

    /* ---Pseudo-daño por cañoneo 5 (4.22) */
    public function pseudo_damage_perforation_5($phase, $well_radius, $perforation_penetration_depth)
    {
        if ($phase == 0 or $phase == 360) {
            $c1 = 0.16;
            $c2 = 2.675;
        } else if ($phase == 45) {
            $c1 = 0.00046;
            $c2 = 8.791;
        } else if ($phase == 60) {
            $c1 = 0.0003;
            $c2 = 7.509;
        } else if ($phase == 90) {
            $c1 = 0.0019;
            $c2 = 6.155;
        } else if ($phase == 120) {
            $c1 = 0.0066;
            $c2 = 5.32;
        } else if ($phase == 180) {
            $c1 = 0.026;
            $c2 = 4.532;
        }

        $rwd = $well_radius * ($well_radius + $perforation_penetration_depth);

        return ($c1 * pow(M_E, $c2 * $rwd * 0.3048));
    }

    /* ---Pseudo-daño por cañoneo 10 (4.27) */
    public function pseudo_damage_perforation_8($phase, $formation_thickness, $perforated_thickness, $horizontal_vertical_permeability_ratio, $perforating_radius, $well_radius, $perforation_penetration_depth)
    {
        if ($phase == 0 or $phase == 360) {
            $a1 = -2.091;
            $a2 = 0.0453;
            $b1 = 5.1313;
            $b2 = 1.8672;
        } else if ($phase == 45) {
            $a1 = -1.788;
            $a2 = 0.2398;
            $b1 = 1.1915;
            $b2 = 1.6392;
        } else if ($phase == 60) {
            $a1 = -1.898;
            $a2 = 0.1023;
            $b1 = 1.3654;
            $b2 = 1.6490;
        } else if ($phase == 90) {
            $a1 = -1.905;
            $a2 = 0.1038;
            $b1 = 1.5674;
            $b2 = 1.6935;
        } else if ($phase == 120) {
            $a1 = -20.18;
            $a2 = 0.0634;
            $b1 = 1.6136;
            $b2 = 1.7770;
        } else if ($phase == 180) {
            $a1 = -2.025;
            $a2 = 0.953;
            $b1 = 3.0373;
            $b2 = 1.8115;
        }

        $hdc = ($perforated_thickness / $perforation_penetration_depth) * sqrt(pow($horizontal_vertical_permeability_ratio, -1));

        $rdc = ($perforating_radius / (24 * $perforated_thickness)) * (1 + sqrt($horizontal_vertical_permeability_ratio));

        $a = ($a1 * log10($rdc)) + $a2;
        $b = $b1 * $rdc + $b2;

        $damage = (pow(10, $a * (-1)) * pow($hdc, ($b - 1)) * pow($rdc, $b));

        return ($damage);
    }

    public function damage_by_perforation($damage_sm, $damage_wb, $damage_sv)
    {
        return $damage_sm + $damage_wb + $damage_sv;
    }

    public function total_pseudo_skin($damage_by_deflection, $damage_by_partial_penetration, $damage_by_shape, $damage_by_perforation)
    {
        return $damage_by_deflection + $damage_by_partial_penetration + $damage_by_shape + $damage_by_perforation;
    }

    public function run_disaggregation_analysis($well_radius, $reservoir_pressure, $measured_well_depth, $true_vertical_depth, $formation_thickness, $perforated_thickness, $well_completitions, $perforation_penetration_depth, $perforating_phase_angle, $perforating_radius, $production_formation_thickness, $horizontal_vertical_permeability_ratio, $drainage_area_shape, $fluid_rate, $bottomhole_flowing_pressure, $fluid_viscosity, $fluid_volumetric_factor, $fluid_specific_gravity, $skin, $permeability, $rock_type, $porosity, $hidraulic_units_data, $emulsion, $characterized_mixture, $flow_rate_1_1, $mixture_bottomhole_flowing_pressure_1_1, $mixture_viscosity_1_1, $mixture_oil_volumetric_factor_1_1, $mixture_water_volumetric_factor_1_1, $mixture_oil_fraction_1_1, $mixture_water_fraction_1_1, $flow_rate_1_2, $mixture_bottomhole_flowing_pressure_1_2, $mixture_oil_viscosity_1_2, $mixture_water_viscosity_1_2, $mixture_oil_fraction_1_2, $mixture_water_fraction_1_2, $mixture_oil_volumetric_factor_1_2, $mixture_water_volumetric_factor_1_2, $flow_rate_2, $mixture_bottomhole_flowing_pressure_2, $mixture_oil_viscosity_2, $mixture_water_viscosity_2, $mixture_oil_fraction_2, $mixture_water_fraction_2, $mixture_oil_volumetric_factor_2, $mixture_water_volumetric_factor_2, $fluid_of_interest)
    {

        $fluid_of_interest = floatval($fluid_of_interest);
        $well_radius = floatval($well_radius);
        $reservoir_pressure = floatval($reservoir_pressure);
        $measured_well_depth = floatval($measured_well_depth);
        $true_vertical_depth = floatval($true_vertical_depth);
        $formation_thickness = json_decode($formation_thickness);
        $perforated_thickness = floatval($perforated_thickness);
        $well_completitions = floatval($well_completitions);
        $perforation_penetration_depth = floatval($perforation_penetration_depth);
        $perforating_phase_angle = floatval($perforating_phase_angle);
        $perforating_radius = floatval($perforating_radius);
        $production_formation_thickness = floatval($production_formation_thickness);
        $horizontal_vertical_permeability_ratio = floatval($horizontal_vertical_permeability_ratio);
        $drainage_area_shape = floatval($drainage_area_shape);
        // Si el fluido contiene gas, pasarlo a barriles.
        if ($fluid_of_interest == 2) {
            $fluid_rate = floatval($fluid_rate);
            $fluid_rate = $fluid_rate * 178101;
        }else{
            $fluid_rate = floatval($fluid_rate);
        }
        $fluid_rate = floatval($fluid_rate);
        $bottomhole_flowing_pressure = floatval($bottomhole_flowing_pressure);
        $fluid_viscosity = floatval($fluid_viscosity);
        $fluid_volumetric_factor = floatval($fluid_volumetric_factor);
        $fluid_specific_gravity = floatval($fluid_specific_gravity);
        $skin = floatval($skin);
        $permeability = floatval($permeability);
        $porosity = floatval($porosity);
        $hidraulic_units_data = json_decode($hidraulic_units_data);
        $emulsion = floatval($emulsion);
        $characterized_mixture = floatval($characterized_mixture);
        //
        $flow_rate_1_1 = floatval($flow_rate_1_1);
        $mixture_bottomhole_flowing_pressure_1_1 = floatval($mixture_bottomhole_flowing_pressure_1_1);
        $mixture_viscosity_1_1 = floatval($mixture_viscosity_1_1);
        $mixture_oil_volumetric_factor_1_1 = floatval($mixture_oil_volumetric_factor_1_1);
        $mixture_water_volumetric_factor_1_1 = floatval($mixture_water_volumetric_factor_1_1);
        $mixture_oil_fraction_1_1 = floatval($mixture_oil_fraction_1_1);
        $mixture_water_fraction_1_1 = floatval($mixture_water_fraction_1_1);
        $flow_rate_1_2 = floatval($flow_rate_1_2);
        $mixture_bottomhole_flowing_pressure_1_2 = floatval($mixture_bottomhole_flowing_pressure_1_2);
        $mixture_oil_viscosity_1_2 = floatval($mixture_oil_viscosity_1_2);
        $mixture_water_viscosity_1_2 = floatval($mixture_water_viscosity_1_2);
        $mixture_oil_fraction_1_2 = floatval($mixture_oil_fraction_1_2);
        $mixture_water_fraction_1_2 = floatval($mixture_water_fraction_1_2);
        $mixture_oil_volumetric_factor_1_2 = floatval($mixture_oil_volumetric_factor_1_2);
        $mixture_water_volumetric_factor_1_2 = floatval($mixture_water_volumetric_factor_1_2);
        $flow_rate_2 = floatval($flow_rate_2);
        $mixture_bottomhole_flowing_pressure_2 = floatval($mixture_bottomhole_flowing_pressure_2);
        $mixture_oil_viscosity_2 = floatval($mixture_oil_viscosity_2);
        $mixture_water_viscosity_2 = floatval($mixture_water_viscosity_2);
        $mixture_oil_fraction_2 = floatval($mixture_oil_fraction_2);
        $mixture_water_fraction_2 = floatval($mixture_water_fraction_2);
        $mixture_oil_volumetric_factor_2 = floatval($mixture_oil_volumetric_factor_2);
        $mixture_water_volumetric_factor_2 = floatval($mixture_water_volumetric_factor_2);
        //
        //$fluid_of_interest = floatval($fluid_of_interest);

        // ENCONTRAR VISCOSIDAD Y FACTOR VOLUMETRICO DEPENDIENDO DE LA EMULISIÓN Y MEZCLA CARACTERIZADA
        if ( $fluid_of_interest == 4 ) {
            // CON EMULSIÓN Y CON MEZCLA CARACTERIZADA
            if ( $emulsion == 1 && $characterized_mixture == 1 ) {  
                // VISCOSIDAD
                $fluid_viscosity = $mixture_viscosity_1_1;
                //FACTOR VOLUMÉTRICO
                 if ( $mixture_water_fraction_1_1 >= 0 && $mixture_water_fraction_1_1 <= 0.7 ) {
                    $fluid_volumetric_factor = $mixture_oil_volumetric_factor_1_1;
                } else {
                    $fluid_volumetric_factor = $mixture_water_volumetric_factor_1_1;
                }
                // FLUID RATE
                $fluid_rate = $flow_rate_1_1;
                // BOTTOMHOLE FLOWING PRESSURE
                $bottomhole_flowing_pressure = $mixture_bottomhole_flowing_pressure_1_1;
            }
            // CON EMULSIÓN Y SIN MEZCLA CARACTERIZADA
            if ( $emulsion == 1 && $characterized_mixture == 2 ) {  
                // VISCOSIDAD
                if ( $mixture_water_fraction_1_2 >= 0 && $mixture_water_fraction_1_2 <= 0.7 ) {
                    $fluid_viscosity = ( 1 + (2.5 * $mixture_water_fraction_1_2) + (10 * pow($mixture_water_fraction_1_2, 2)) ) * $mixture_oil_viscosity_1_2;
                } else {
                    $fluid_viscosity = ( 1 + (2.5 * $mixture_oil_fraction_1_2) + (10 * pow($mixture_oil_fraction_1_2, 2)) ) * $mixture_water_viscosity_1_2;
                }
                // FACTOR VOLUMÉTRICO
                if ( $mixture_water_fraction_1_2 >= 0 && $mixture_water_fraction_1_2 <= 0.7 ) {
                    $fluid_volumetric_factor = $mixture_oil_volumetric_factor_1_2;
                } else {
                    $fluid_volumetric_factor = $mixture_water_volumetric_factor_1_2;
                }
                // FLUID RATE
                $fluid_rate = $flow_rate_1_2;
                // BOTTOMHOLE FLOWING PRESSURE
                $bottomhole_flowing_pressure = $mixture_bottomhole_flowing_pressure_1_2;
            }
            // SIN EMULSIÓN Y SIN MEZCLA CARACTERIZADA
            if ( $emulsion == 2) {  
                // VISCOSIDAD
                $fluid_viscosity = ($mixture_water_fraction_2 * $mixture_water_viscosity_2) + ($mixture_oil_fraction_2 * $mixture_oil_viscosity_2);
                // FACTOR VOLUMÉTRICO
                $fluid_volumetric_factor = ($mixture_water_fraction_2 * $mixture_water_volumetric_factor_2) + ($mixture_oil_fraction_2 * $mixture_oil_volumetric_factor_2);
                // FLUID RATE
                $fluid_rate = $flow_rate_2;
                // BOTTOMHOLE FLOWING PRESSURE
                $bottomhole_flowing_pressure = $mixture_bottomhole_flowing_pressure_2;
            }
        }

        //dd('fluid viscosity', $fluid_viscosity, 'fluid volumetric factor', $fluid_volumetric_factor);

        /* HIDRAULC UNITS DATA */

        //REVISADO 1
        $permeability_module = $this->permeability_module($hidraulic_units_data, $rock_type);

        //REVISADO 2
        $well_permeability_module = $this->well_permeability_module($permeability_module); /* Nuevos datos de unidades hidráulicas calculadas en la función permeability_module (Antes estaba hidraulic_units_data) */

        //REVISADO 3
        $hidraulic_units_data = $this->friction_coefficient($permeability_module); /* Nuevos datos de unidades hidráulicas calculadas en la función permeability_module (Antes estaba hidraulic_units_data) */

        //dd($hidraulic_units_data);

        //REVISADO 4
        $well_friction_coefficient = $this->well_friction_coefficient($hidraulic_units_data);

        //dd($well_friction_coefficient, $hidraulic_units_data);

        //dd($well_permeability_module, $well_friction_coefficient);

        /* SKIN BY STRESS */

        //REVISADO 5
        $pore_pressure = $this->pore_pressure($bottomhole_flowing_pressure, $fluid_rate, $fluid_viscosity, $fluid_volumetric_factor, $permeability, $formation_thickness, $well_radius, $skin, $reservoir_pressure, $formation_thickness);

        //REVISADO 5.1 - VIEW
        $pore_pressure_view = $this->well_point_i_distance($well_radius, 10);

        //dd($pore_pressure_view, $pore_pressure);

        //ESTO YA NO SE CALCULA
        //$total_stress = $this->total_stress($vertical_stress_gradient, $min_horizontal_stress_gradient, $max_horizontal_stress_gradient, $true_vertical_depth);

        //SE TIENE QUE VER PARA QUÉ SIRVE
        //$initial_effective_stress = $this->initial_effective_stress($total_stress, $reservoir_pressure);

        //REVISADO 6
        $effective_stress = $this->effective_stress($reservoir_pressure, $pore_pressure);

        //dd($pore_pressure_view, $effective_stress);

        //MIRAR PARA QUÉ SIRVE
        //$affected_area_permeability = $this->affected_area_permeability($permeability, $well_permeability_module, $initial_effective_stress, $effective_stress);

        //$permeability_axis = $affected_area_permeability;

        //REVISADO 7
        $stress_damage_results = $this->stress_damage($well_permeability_module, $well_radius, $effective_stress);

        $stress_damage = $stress_damage_results[0];
        $pressure_axis = $stress_damage_results[1];

        //REVISADO 7.1 - VIEW
        $stress_damage_results_view = $this->stress_damage_view($well_permeability_module, $well_radius, $effective_stress);

        //dd($stress_damage_results);

        /* SKIN BY NON DARCY */

        //REVISADO 8
        $fluid_rate_in_ftpd = $this->bpd_to_ftd($fluid_rate, $fluid_of_interest);

        //REVISADO 9
        $non_darcy_flow_coefficient = $this->non_darcy_flow_coefficient($fluid_specific_gravity, $permeability, $fluid_viscosity, $well_radius, $perforated_thickness, $well_friction_coefficient);

        //REVISADO 10
        $damage_ratio = $this->damage_rate($non_darcy_flow_coefficient, $fluid_rate_in_ftpd);

        //dd($damage_ratio);

        /* PSEUDO-SKIN */

        if ($well_completitions == 3) {
            //REVISADO 11
            $damage_by_deflection = $this->damage_by_deflection($true_vertical_depth, $measured_well_depth, $horizontal_vertical_permeability_ratio, $formation_thickness, $well_radius);

            //REVISADO 12
            $damage_by_partial_penetration = $this->damage_by_partial_penetration($formation_thickness, $perforated_thickness, $horizontal_vertical_permeability_ratio, $well_radius);

            //REVISADO 13
            $damage_by_shape = $this->pseudo_damage_reservoir_shape($drainage_area_shape);

            //REVISADO 14 ///// POSIBLE ERROR..  RESULTADO CON CASO DE PRUEBA 2 = 2.0
            $damage_sm = $this->pseudo_damage_perforation_3($perforating_phase_angle, $perforation_penetration_depth, $well_radius);

            //REVISADO 15
            $damage_wb = $this->pseudo_damage_perforation_5($perforating_phase_angle, $well_radius, $perforation_penetration_depth);

            //REVISADO 16 ////// MUY POSIBLE ERROR... RESULTADO DESORBITADO
            $damage_sv = $this->pseudo_damage_perforation_8($perforating_phase_angle, $formation_thickness, $perforated_thickness, $horizontal_vertical_permeability_ratio, $perforating_radius, $well_radius, $perforation_penetration_depth);

            //REVISADO 17
            $damage_by_perforation = $this->damage_by_perforation($damage_sm, $damage_wb, $damage_sv);

            //REVISADO 18
            $total_pseudo_skin = $this->total_pseudo_skin($damage_by_deflection, $damage_by_partial_penetration, $damage_by_shape, $damage_by_perforation);

            //dd($damage_by_deflection, $damage_by_partial_penetration, $damage_by_shape, $damage_by_perforation, $total_pseudo_skin);
        } else {
            $total_pseudo_skin = 0;
        }

        /* SKIN BY COMPONENTS */

        //dd($stress_damage, $damage_ratio, $total_pseudo_skin);
        $mechanical_damage = $stress_damage + $damage_ratio + $total_pseudo_skin;
        if ($skin > $mechanical_damage) {
            $mechanical_damage = ($skin - $stress_damage - $damage_ratio - $total_pseudo_skin);
        } else {
            //mostrar 'ERROR 105'
            $mechanical_damage = ($skin - $stress_damage - $damage_ratio - $total_pseudo_skin);
        }

        //dd($mechanical_damage);

        $results = array($skin, $mechanical_damage, $stress_damage, $total_pseudo_skin, $damage_ratio, $pressure_axis, $pressure_axis/* $permeability_axis */, $well_friction_coefficient, $well_permeability_module, $pore_pressure_view, $stress_damage_results_view);

        return $results;
    }
}
