<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Http\Requests;
use App\Http\Requests\filtration_function_request;
use App\Http\Controllers\Controller;
use App\cuenca;
use App\formacion;
use App\campo;
use App\filtration_function;
use App\d_laboratory_test;
use App\d_laboratory_test_data;
use App\MudComposicion;
use DB;
use View;

class filtration_function_controller extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    if (\Auth::check()) {
      if (\Auth::User()->office != 2) {
        $basins['basins'] = cuenca::all();
        return view('filtrationFunction.create', $basins);
      } else {
        return view('permission');
      }
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
    //
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(filtration_function_request $request)
  {
    //dd($request->mudComposicion);
    if (\Auth::check()) {
      $filtration_function = new filtration_function();
      $filtration_function->formation_id = $request->formation;
      $filtration_function->name = $request->filtration_function_name;
      $filtration_function->mud_density = $request->mud_density;

      if ($request->kdki_cement_slurry == "") {
        $filtration_function->kdki_cement_slurry = null;
      } else {
        $filtration_function->kdki_cement_slurry = $request->kdki_cement_slurry;
      }

      $filtration_function->kdki_cement_slurry = $request->kdki_cement_slurry;
      $filtration_function->kdki_mud = $request->kdki_mud;
      $filtration_function->core_diameter = $request->core_diameter;
      $filtration_function->ph = $request->ph;
      $filtration_function->plastic_viscosity = $request->plastic_viscosity;
      $filtration_function->lplt_filtrate = $request->lplt_filtrate;
      $filtration_function->hpht_filtrate = $request->hpht_filtrate;
      $filtration_function->yield_point = $request->yield_point;
      $filtration_function->cement_slurry_density = $request->cement_density;
      $filtration_function->cement_plastic_viscosity = $request->cement_plastic_viscosity;
      $filtration_function->cement_yield_point = $request->cement_yield_point;
      $filtration_function->gel_strength = $request->gel_strength;
      $filtration_function->a_factor = $request->a_factor;
      $filtration_function->b_factor = $request->b_factor;
      $filtration_function->method = $request->filtration_function_factors_option;
      //dd($filtration_function->a_factor, $filtration_function->b_factor, 'lolala');
      $filtration_function->save();

      if ($request->filtration_function_factors_option == 1) {
        $factores = $this->factores($request, $filtration_function);
        $update = $filtration_function;
        $update->a_factor = $factores['a'];
        $update->b_factor = $factores['b'];
        $update->save();
        //dd($factores['a'], $factores['b'], 'dentro del if');
      }

      if ($request->mudComposicion) {
        MudComposicion::store($request->mudComposicion, $filtration_function);
      }

      return redirect('database');
    } else {
      return view('loginfirst');
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
    $dr = filtration_function::find($id);
    $dr->composicion;
    //dd($dr->composicion);
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
      if (\Auth::User()->office != 2) {
        $filtration_function = filtration_function::find($id);
        $filtration_function->mudComposicion = MudComposicion::getData($filtration_function->id);
        $basins = cuenca::all();
        $formation_id = $filtration_function->formation_id;
        $field_id = formacion::find($formation_id)->campo_id;
        $basin_id = campo::find($field_id)->cuenca_id;

        return View::make('filtrationFunction.edit', compact(['basins', 'filtration_function', 'formation_id', 'field_id', 'basin_id']));
      } else {
        return view('permission');
      }
    } else {
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
  public function update(filtration_function_request $request, $id)
  {
    if (\Auth::check()) {
      $filtration_function = filtration_function::find($id);
      $filtration_function->formation_id = $request->formation;
      $filtration_function->name = $request->filtration_function_name;
      $filtration_function->mud_density = $request->mud_density;
      
      if ($request->kdki_cement_slurry == "") {
        $filtration_function->kdki_cement_slurry = null;
      } else {
        $filtration_function->kdki_cement_slurry = $request->kdki_cement_slurry;
      }

      $filtration_function->kdki_cement_slurry = $request->kdki_cement_slurry;
      $filtration_function->kdki_mud = $request->kdki_mud;
      $filtration_function->core_diameter = $request->core_diameter;
      $filtration_function->ph = $request->ph;
      $filtration_function->plastic_viscosity = $request->plastic_viscosity;
      $filtration_function->lplt_filtrate = $request->lplt_filtrate;
      $filtration_function->hpht_filtrate = $request->hpht_filtrate;
      $filtration_function->yield_point = $request->yield_point;
      $filtration_function->cement_slurry_density = $request->cement_density;
      $filtration_function->cement_plastic_viscosity = $request->cement_plastic_viscosity;
      $filtration_function->cement_yield_point = $request->cement_yield_point;
      $filtration_function->gel_strength = $request->gel_strength;
      $filtration_function->a_factor = $request->a_factor;
      $filtration_function->b_factor = $request->b_factor;
      $filtration_function->method = $request->filtration_function_factors_option;
      //dd($filtration_function->a_factor, $filtration_function->b_factor, 'lolala');
      $filtration_function->save();

      if ($request->filtration_function_factors_option == 1) {
        //Eliminando laboratory_test y laboratory_test_data anteriores
        DB::table('d_laboratory_test')->where('d_filtration_function_id', $id)->delete();

        $factores = $this->factores($request, $filtration_function);
        $update = $filtration_function;
        $update->a_factor = $factores['a'];
        $update->b_factor = $factores['b'];
        $update->save();
      }

      MudComposicion::store($request->mudComposicion, $filtration_function);

      return redirect('database');
    } else {
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
    filtration_function::destroy($id);
    $basin = cuenca::select('id', 'nombre')->get();
    $filtration_functions = DB::table('d_filtration_function')->select('id', 'name')->paginate(15);
    return view('list_filtration_function', ['basin' => $basin, 'filtration_functions' => $filtration_functions]);
  }

  /**
   * List the available resources.
   *
   * 
   * @return \Illuminate\Http\Response
   */

  public function list_filtration_functions()
  {
    if (\Auth::check()) {
      if (\Auth::User()->office != 2) {
        $basin = cuenca::select('id', 'nombre')->get();
        $filtration_functions = DB::table('d_filtration_function')->select('id', 'name')->paginate(15);
        return view('list_filtration_function', ['basin' => $basin, 'filtration_functions' => $filtration_functions]);
      } else {
        return view('permission');
      }
    } else {
      return view('loginfirst');
    }
  }

  public function factores($request, $filtration_function)
  {
    $permeability_data_complete = json_decode($request->input("k_data"));
    $pob_data_complete = json_decode($request->input("p_data"));
    $lab_test_data_complete = json_decode($request->input("lab_test_data"));

    //dd($permeability_data_complete, $pob_data_complete, $lab_test_data_complete);

    $flag_lab_test_data = 0;
    $dv_dt_all = array();
    $kpob_all = array();
    $aux2 = [];
    foreach ($permeability_data_complete as $value) {
      $laboratory_test = new d_laboratory_test();
      $laboratory_test->d_filtration_function_id = $filtration_function->id;
      $laboratory_test->permeability = $value;
      $laboratory_test->pob = $pob_data_complete[$flag_lab_test_data];
      $laboratory_test->kpob = floatval($pob_data_complete[$flag_lab_test_data]) * floatval($value);
      //dd(floatval($pob_data_complete[$flag_lab_test_data]));
      //dd($laboratory_test);
      $laboratory_test->save();

      // $times = array();
      // $filtered_volumes = array();
      $aux = [];
      foreach ($lab_test_data_complete[$flag_lab_test_data] as $value_2) {
        $laboratory_test_data = new d_laboratory_test_data();
        $laboratory_test_data->d_laboratory_test_id = $laboratory_test->id;
        $laboratory_test_data->time = $value_2[0];
        $laboratory_test_data->filtered_volume = $value_2[1];
        $laboratory_test_data->save();

        // array_push($times, sqrt(floatval($laboratory_test_data->time)));
        // array_push($filtered_volumes, floatval($laboratory_test_data->filtered_volume));
        array_push($aux, array(sqrt(floatval($laboratory_test_data->time)), floatval($laboratory_test_data->filtered_volume)));
      }

      //dd($aux);

      // $n = count($times);
      // $x = $times;
      // $y = $filtered_volumes;
      // $x_sum = array_sum($x);
      // $y_sum = array_sum($y);

      // $xx_sum = 0;
      // $xy_sum = 0;

      // for($i = 0; $i < $n; $i++) 
      // {
      //   $xy_sum+=($x[$i]*$y[$i]);
      //   $xx_sum+=($x[$i]*$x[$i]);
      // }

      // $m = ($xy_sum/$xx_sum); 
      // $b = ($y_sum - ($m * $x_sum)) / $n;

      list($m, $intercept) = $this->linearRegression($aux);

      //dd($m, $intercept);

      if ($intercept < 0) {
        $intercept = 0;
      }

      $laboratory_test_aux = \App\d_laboratory_test::find($laboratory_test->id);
      $laboratory_test_aux->dv_dt = $m;
      $laboratory_test_aux->save();

      array_push($dv_dt_all, $laboratory_test_aux->dv_dt);
      array_push($kpob_all, $laboratory_test->kpob);
      array_push($aux2, array($laboratory_test->kpob, $laboratory_test_aux->dv_dt));

      //dd($dv_dt_all, $kpob_all, $aux2);

      $flag_lab_test_data++;
    }

    //dd($dv_dt_all, $kpob_all, $aux2);

    // $n = count($dv_dt_all);
    // $x = $kpob_all;
    // $y = $dv_dt_all;
    // $x_sum = array_sum($x);
    // $y_sum = array_sum($y);

    // $xx_sum = 0;
    // $xy_sum = 0;

    // for($i = 0; $i < $n; $i++) 
    // {
    //   $xy_sum+=($x[$i]*$y[$i]);
    //   $xx_sum+=($x[$i]*$x[$i]);
    // }

    // $b = (($y_sum*$xx_sum)-($x_sum*$xy_sum)) / (($n * $xx_sum) - ($x_sum * $x_sum));
    // $m = (($n*$xy_sum)-($x_sum)*($y_sum))/(($n*$xx_sum)-pow($x_sum,2));

    # En caso de solo existir una prueba de filtrado
    if (count($aux2) == 1) {
      array_push($aux2, array($intercept, 0));
    }

    list($a, $b) = $this->linearRegression($aux2);

    if ($b < 0) {
      $b = $intercept;
    }

    return collect(['a' => $a, 'b' => $b]);
  }

  /**
   * Find the linear regression for a set of data points such that
   * y = mx + b
   *
   * @param array $pairs a list of (x,y) data points
   * @returns array m, b
   */
  public function linearRegression($pairs)
  {
    $x = $y = 0;
    $Sx = $Sy = $Sxx = $Sxy = 0.0;
    $n = count($pairs);
    foreach ($pairs as $pair) {
      list($x, $y) = $pair;
      $Sx += $x;
      $Sy += $y;
      $Sxx += pow($x, 2);
      $Sxy += $x * $y;
    }
    $m = (($n * $Sxy) - ($Sx * $Sy)) / (($n * $Sxx) - pow($Sx, 2));
    $b = ($Sy - ($m * $Sx)) / $n;

    return array($m, $b);
  }

  /**
   * Find the exponential regression for a set of data points such that
   * y = a*e^(bx)
   *
   * @param array $pairs a list of (x,y) data points
   * @returns array a, b
   */
  public function exponentialRegression($pairs)
  {
    array_walk($pairs, function (&$value) {
      $value = array($value[0], log($value[1]));
    });
    list($m, $intercept) = $this->linearRegression($pairs);
    $a = $m;
    $b = exp($intercept);

    return array($a, $b);
  }
}
