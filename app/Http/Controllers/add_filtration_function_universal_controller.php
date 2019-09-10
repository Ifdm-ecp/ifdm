<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\cuenca;
use App\d_filtration_function;
use App\d_laboratory_test;
use App\d_laboratory_test_data;

class add_filtration_function_universal_controller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (\Auth::check()) {
            if(\Auth::User()->office!=2){
                $basins['basins'] = cuenca::all();
                return view('add_filtration_function_universal', $basins);
            }else{
                return view('permission');
            }
        }else{
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
    public function store(Request $request)
    {
        if(\Auth::check())
        {
            $filtration_function = new d_filtration_function();
            $filtration_function->formation_id = $request->input("formation");
            $filtration_function->name = $request->input("filtration_function_name");
            $filtration_function->mud_density = $request->input("mud_density");
            $filtration_function->kdki_cement_slurry = $request->input("kdki_cement_slurry");
            $filtration_function->kdki_mud = $request->input("kdki_mud");
            $filtration_function->core_diameter = $request->input("core_diameter");
            $filtration_function->save();

            $permeability_data_complete = json_decode($request->input("k_data"));    
            $pob_data_complete = json_decode($request->input("p_data"));    
            $lab_test_data_complete = json_decode($request->input("lab_test_data"));

            $flag_lab_test_data = 0; 
            $dv_dt_all = array();
            $kpob_all = array();
            foreach ($permeability_data_complete as $value) 
            {
                $laboratory_test = new d_laboratory_test();
                $laboratory_test->d_filtration_function_id = $filtration_function->id;
                $laboratory_test->permeability = $value;
                $laboratory_test->pob = $pob_data_complete[$flag_lab_test_data];
                $laboratory_test->kpob = floatval($pob_data_complete[$flag_lab_test_data])*floatval($value);
                $laboratory_test->save();

                $times = array();
                $filtered_volumes = array();
                foreach ($lab_test_data_complete[$flag_lab_test_data] as $value_2) 
                {
                    $laboratory_test_data = new d_laboratory_test_data();
                    $laboratory_test_data->d_laboratory_test_id = $laboratory_test->id;
                    $laboratory_test_data->time = $value_2[0];
                    $laboratory_test_data->filtered_volume = $value_2[1];
                    $laboratory_test_data->save();

                    array_push($times, sqrt(floatval($laboratory_test_data->time)));
                    array_push($filtered_volumes, floatval($laboratory_test_data->filtered_volume));
                }

                $n = count($times);
                $x = $times;
                $y = $filtered_volumes;
                $x_sum = array_sum($x);
                $y_sum = array_sum($y);

                $xx_sum = 0;
                $xy_sum = 0;

                for($i = 0; $i < $n; $i++) 
                {
                  $xy_sum+=($x[$i]*$y[$i]);
                  $xx_sum+=($x[$i]*$x[$i]);
                }

                $m = ($xy_sum/$xx_sum); 
                $b = ($y_sum - ($m * $x_sum)) / $n;

                $laboratory_test_aux =\App\d_laboratory_test::find($laboratory_test->id);
                $laboratory_test_aux->dv_dt = $m;
                $laboratory_test_aux->save();

                array_push($dv_dt_all, $laboratory_test_aux->dv_dt);
                array_push($kpob_all, $laboratory_test->kpob);


                $flag_lab_test_data++;
            }

            $n = count($dv_dt_all);
            $x = $kpob_all;
            $y = $dv_dt_all;
            $x_sum = array_sum($x);
            $y_sum = array_sum($y);

            $xx_sum = 0;
            $xy_sum = 0;

            for($i = 0; $i < $n; $i++) 
            {
              $xy_sum+=($x[$i]*$y[$i]);
              $xx_sum+=($x[$i]*$x[$i]);
            }

            $b = (($y_sum*$xx_sum)-($x_sum*$xy_sum)) / (($n * $xx_sum) - ($x_sum * $x_sum));
            $m = (($n*$xy_sum)-($x_sum)*($y_sum))/(($n*$xx_sum)-pow($x_sum,2));
            
            $filtration_function_update = \App\d_filtration_function::find($filtration_function->id);
            $filtration_function_update->a_factor = $m;
            $filtration_function_update->b_factor = $b;
            $filtration_function_update->save();
            return redirect('database');


        }   
        else
        {
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
