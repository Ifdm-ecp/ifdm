<?php

namespace App\Http\Controllers;

require(__DIR__.'/../../../vendor/autoload.php');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

use DB;
use Redirect;
use App\cuenca;
use App\formacion;
use App\Http\Controllers\Controller;
use App\Http\Requests\measurementRequest;
use App\medicion;
use Illuminate\Http\Request;
use Carbon\Carbon;

class add_damage_variables_controller extends Controller
{
    /**
     * Despliega la vista add_damage_variables con la información de formaciones para popular los select de la vista.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (\Auth::check()) {
            if (\Auth::User()->office != 2) {
                $formacion = formacion::select('id', 'nombre')->orderBy('nombre')->get();
                $cuenca = cuenca::select('id', 'nombre')->orderBy('nombre')->get();
                return view('add_damage_variables', ['formacion' => $formacion, 'cuenca' => $cuenca]);
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
    public function store(measurementRequest $request)
    {
        if (\Auth::check()) {
            // Si hay un archivo por importar, haga esto
            if ($request->file('uploadedFile')) {

                $response = ""; 
                $file = $request->file('uploadedFile');

                global $cuenca;
                $cuenca = $request->input('basin');
                global $campo;
                $campo = $request->input('field');
                $tabs = ['Mineral Scales', 'Fine Blockage', 'Organic Scales', 'Relative Permeability', 'Induced Damage', 'Geomechanical Damage'];
                foreach ($tabs as $tab) {
                    $response_aux = $this->readTab($tab, $file);
                    if ($response_aux === "error1") {
                        dd('pita');
                        $response = "error1";
                    }elseif ($response_aux === "error") {
                        dd('pita');
                        $response = "error";
                    }
                }

                if ($response === "error1") {
                    return Redirect::back()->withErrors(['msg' => 'Please check import data.']);
                }
                
                if ($response === "error") {
                    return Redirect::back()->withErrors(['msg' => 'Some records were not imported. Duplicated dates.']);
                }

                return Redirect::back()->with('success', 'Damage Variables Successfully Imported.');

            // Si NO hay un archivo por importar, haga esto
            } else {

                //Verificar si los valores de cada SP son ingresados para guardar todo el formulario

                if ($request->input('MS1')) {
                    $measurement = new medicion;
                    $measurement->valor = $request->input('MS1');
                    $measurement->fecha = Carbon::createFromFormat('d/m/Y', $request->dateMS1)->format('Y-m-d');
                    $measurement->comentario = $request->input('MS1comment');
                    $measurement->formacion_id = $request->input('formation');
                    $measurement->pozo_id = $request->input('well');
                    $measurement->subparametro_id = 1;
                    $measurement->save();
                }
                
                if ($request->input('MS2')) {
                    $measurement = new medicion;
                    $measurement->valor = $request->input('MS2');
                    $measurement->fecha = Carbon::createFromFormat('d/m/Y', $request->dateMS2)->format('Y-m-d');
                    $measurement->comentario = $request->input('MS2comment');
                    $measurement->formacion_id = $request->input('formation');
                    $measurement->pozo_id = $request->input('well');
                    $measurement->subparametro_id = 2;
                    $measurement->save();
                }

                if ($request->input('MS3')) {
                    $measurement = new medicion;
                    $measurement->valor = $request->input('MS3');
                    $measurement->fecha = Carbon::createFromFormat('d/m/Y', $request->dateMS3)->format('Y-m-d');
                    $measurement->comentario = $request->input('MS3comment');
                    $measurement->formacion_id = $request->input('formation');
                    $measurement->pozo_id = $request->input('well');
                    $measurement->subparametro_id = 3;
                    $measurement->save();
                }

                if ($request->input('MS4')) {
                    $measurement = new medicion;
                    $measurement->valor = $request->input('MS4');
                    $measurement->fecha = Carbon::createFromFormat('d/m/Y', $request->dateMS4)->format('Y-m-d');
                    $measurement->comentario = $request->input('MS4comment');
                    $measurement->formacion_id = $request->input('formation');
                    $measurement->pozo_id = $request->input('well');
                    $measurement->subparametro_id = 4;
                    $measurement->save();
                }

                if ($request->input('MS5')) {
                    $measurement = new medicion;
                    $measurement->valor = $request->input('MS5');
                    $measurement->fecha = Carbon::createFromFormat('d/m/Y', $request->dateMS5)->format('Y-m-d');
                    $measurement->comentario = $request->input('MS5comment');
                    $measurement->formacion_id = $request->input('formation');
                    $measurement->pozo_id = $request->input('well');
                    $measurement->subparametro_id = 5;
                    $measurement->save();
                }

                if ($request->input('FB1')) {
                    $measurement = new medicion;
                    $measurement->valor = $request->input('FB1');
                    $measurement->fecha = Carbon::createFromFormat('d/m/Y', $request->dateFB1)->format('Y-m-d');
                    $measurement->comentario = $request->input('FB1comment');
                    $measurement->formacion_id = $request->input('formation');
                    $measurement->pozo_id = $request->input('well');
                    $measurement->subparametro_id = 6;
                    $measurement->save();
                }

                if ($request->input('FB2')) {
                    $measurement = new medicion;
                    $measurement->valor = $request->input('FB2');
                    $measurement->fecha = Carbon::createFromFormat('d/m/Y', $request->dateFB2)->format('Y-m-d');
                    $measurement->comentario = $request->input('FB2comment');
                    $measurement->formacion_id = $request->input('formation');
                    $measurement->pozo_id = $request->input('well');
                    $measurement->subparametro_id = 7;
                    $measurement->save();
                }

                if ($request->input('FB3')) {
                    $measurement = new medicion;
                    $measurement->valor = $request->input('FB3');
                    $measurement->fecha = Carbon::createFromFormat('d/m/Y', $request->dateFB3)->format('Y-m-d');
                    $measurement->comentario = $request->input('FB3comment');
                    $measurement->formacion_id = $request->input('formation');
                    $measurement->pozo_id = $request->input('well');
                    $measurement->subparametro_id = 8;
                    $measurement->save();
                }

                if ($request->input('FB4')) {
                    $measurement = new medicion;
                    $measurement->valor = $request->input('FB4');
                    $measurement->fecha = Carbon::createFromFormat('d/m/Y', $request->dateFB4)->format('Y-m-d');
                    $measurement->comentario = $request->input('FB4comment');
                    $measurement->formacion_id = $request->input('formation');
                    $measurement->pozo_id = $request->input('well');
                    $measurement->subparametro_id = 9;
                    $measurement->save();
                }

                if ($request->input('FB5')) {
                    $measurement = new medicion;
                    $measurement->valor = $request->input('FB5');
                    $measurement->fecha = Carbon::createFromFormat('d/m/Y', $request->dateFB5)->format('Y-m-d');
                    $measurement->comentario = $request->input('FB5comment');
                    $measurement->formacion_id = $request->input('formation');
                    $measurement->pozo_id = $request->input('well');
                    $measurement->subparametro_id = 10;
                    $measurement->save();
                }

                if ($request->input('OS1')) {
                    $measurement = new medicion;
                    $measurement->valor = $request->input('OS1');
                    $measurement->fecha = Carbon::createFromFormat('d/m/Y', $request->dateOS1)->format('Y-m-d');
                    $measurement->comentario = $request->input('OS1comment');
                    $measurement->formacion_id = $request->input('formation');
                    $measurement->pozo_id = $request->input('well');
                    $measurement->subparametro_id = 11;
                    $measurement->save();
                }

                if ($request->input('OS2')) {
                    $measurement = new medicion;
                    $measurement->valor = $request->input('OS2');
                    $measurement->fecha = Carbon::createFromFormat('d/m/Y', $request->dateOS2)->format('Y-m-d');
                    $measurement->comentario = $request->input('OS2comment');
                    $measurement->formacion_id = $request->input('formation');
                    $measurement->pozo_id = $request->input('well');
                    $measurement->subparametro_id = 30;
                    $measurement->save();
                }

                if ($request->input('OS3')) {
                    $measurement = new medicion;
                    $measurement->valor = $request->input('OS3');
                    $measurement->fecha = Carbon::createFromFormat('d/m/Y', $request->dateOS3)->format('Y-m-d');
                    $measurement->comentario = $request->input('OS3comment');
                    $measurement->formacion_id = $request->input('formation');
                    $measurement->pozo_id = $request->input('well');
                    $measurement->subparametro_id = 12;
                    $measurement->save();
                }

                if ($request->input('OS4')) {
                    $measurement = new medicion;
                    $measurement->valor = $request->input('OS4');
                    $measurement->fecha = Carbon::createFromFormat('d/m/Y', $request->dateOS4)->format('Y-m-d');
                    $measurement->comentario = $request->input('OS4comment');
                    $measurement->formacion_id = $request->input('formation');
                    $measurement->pozo_id = $request->input('well');
                    $measurement->subparametro_id = 13;
                    $measurement->save();
                }

                if ($request->input('OS5')) {
                    $measurement = new medicion;
                    $measurement->valor = $request->input('OS5');
                    $measurement->fecha = Carbon::createFromFormat('d/m/Y', $request->dateOS5)->format('Y-m-d');
                    $measurement->comentario = $request->input('OS5comment');
                    $measurement->formacion_id = $request->input('formation');
                    $measurement->pozo_id = $request->input('well');
                    $measurement->subparametro_id = 14;
                    $measurement->save();
                }

                if ($request->input('RP1')) {
                    $measurement = new medicion;
                    $measurement->valor = $request->input('RP1');
                    $measurement->fecha = Carbon::createFromFormat('d/m/Y', $request->dateRP1)->format('Y-m-d');
                    $measurement->comentario = $request->input('RP1comment');
                    $measurement->formacion_id = $request->input('formation');
                    $measurement->pozo_id = $request->input('well');
                    $measurement->subparametro_id = 15;
                    $measurement->save();
                }

                if ($request->input('RP2')) {
                    $measurement = new medicion;
                    $measurement->valor = $request->input('RP2');
                    $measurement->fecha = Carbon::createFromFormat('d/m/Y', $request->dateRP2)->format('Y-m-d');
                    $measurement->comentario = $request->input('RP2comment');
                    $measurement->formacion_id = $request->input('formation');
                    $measurement->pozo_id = $request->input('well');
                    $measurement->subparametro_id = 16;
                    $measurement->save();
                }

                if ($request->input('RP3')) {
                    $measurement = new medicion;
                    $measurement->valor = $request->input('RP3');
                    $measurement->fecha = Carbon::createFromFormat('d/m/Y', $request->dateRP3)->format('Y-m-d');
                    $measurement->comentario = $request->input('RP3comment');
                    $measurement->formacion_id = $request->input('formation');
                    $measurement->pozo_id = $request->input('well');
                    $measurement->subparametro_id = 17;
                    $measurement->save();
                }

                if ($request->input('RP4')) {
                    $measurement = new medicion;
                    $measurement->valor = $request->input('RP4');
                    $measurement->fecha = Carbon::createFromFormat('d/m/Y', $request->dateRP4)->format('Y-m-d');
                    $measurement->comentario = $request->input('RP4comment');
                    $measurement->formacion_id = $request->input('formation');
                    $measurement->pozo_id = $request->input('well');
                    $measurement->subparametro_id = 18;
                    $measurement->save();
                }

                if ($request->input('RP5')) {
                    $measurement = new medicion;
                    $measurement->valor = $request->input('RP5');
                    $measurement->fecha = Carbon::createFromFormat('d/m/Y', $request->dateRP5)->format('Y-m-d');
                    $measurement->comentario = $request->input('RP5comment');
                    $measurement->formacion_id = $request->input('formation');
                    $measurement->pozo_id = $request->input('well');
                    $measurement->subparametro_id = 31;
                    $measurement->save();
                }

                if ($request->input('ID1')) {
                    $measurement = new medicion;
                    $measurement->valor = $request->input('ID1');
                    $measurement->fecha = Carbon::createFromFormat('d/m/Y', $request->dateID1)->format('Y-m-d');
                    $measurement->comentario = $request->input('ID1comment');
                    $measurement->formacion_id = $request->input('formation');
                    $measurement->pozo_id = $request->input('well');
                    $measurement->subparametro_id = 19;
                    $measurement->save();
                }

                if ($request->input('ID2')) {
                    $measurement = new medicion;
                    $measurement->valor = $request->input('ID2');
                    $measurement->fecha = Carbon::createFromFormat('d/m/Y', $request->dateID2)->format('Y-m-d');
                    $measurement->comentario = $request->input('ID2comment');
                    $measurement->formacion_id = $request->input('formation');
                    $measurement->pozo_id = $request->input('well');
                    $measurement->subparametro_id = 20;
                    $measurement->save();
                }

                if ($request->input('ID3')) {
                    $measurement = new medicion;
                    $measurement->valor = $request->input('ID3');
                    $measurement->fecha = Carbon::createFromFormat('d/m/Y', $request->dateID3)->format('Y-m-d');
                    $measurement->comentario = $request->input('ID3comment');
                    $measurement->formacion_id = $request->input('formation');
                    $measurement->pozo_id = $request->input('well');
                    $measurement->subparametro_id = 21;
                    $measurement->save();
                }

                if ($request->input('ID4')) {
                    $measurement = new medicion;
                    $measurement->valor = $request->input('ID4');
                    $measurement->fecha = Carbon::createFromFormat('d/m/Y', $request->dateID4)->format('Y-m-d');
                    $measurement->comentario = $request->input('ID4comment');
                    $measurement->formacion_id = $request->input('formation');
                    $measurement->pozo_id = $request->input('well');
                    $measurement->subparametro_id = 22;
                    $measurement->save();
                }

                if ($request->input('GD1')) {
                    $measurement = new medicion;
                    $measurement->valor = $request->input('GD1');
                    $measurement->fecha = Carbon::createFromFormat('d/m/Y', $request->dateGD1)->format('Y-m-d');
                    $measurement->comentario = $request->input('GD1comment');
                    $measurement->formacion_id = $request->input('formation');
                    $measurement->pozo_id = $request->input('well');
                    $measurement->subparametro_id = 23;
                    $measurement->save();
                }

                if ($request->input('GD2')) {
                    $measurement = new medicion;
                    $measurement->valor = $request->input('GD2');
                    $measurement->fecha = Carbon::createFromFormat('d/m/Y', $request->dateGD2)->format('Y-m-d');
                    $measurement->comentario = $request->input('GD2comment');
                    $measurement->formacion_id = $request->input('formation');
                    $measurement->pozo_id = $request->input('well');
                    $measurement->subparametro_id = 24;
                    $measurement->save();
                }

                if ($request->input('GD3')) {
                    $measurement = new medicion;
                    $measurement->valor = $request->input('GD3');
                    $measurement->fecha = Carbon::createFromFormat('d/m/Y', $request->dateGD3)->format('Y-m-d');
                    $measurement->comentario = $request->input('GD3comment');
                    $measurement->formacion_id = $request->input('formation');
                    $measurement->pozo_id = $request->input('well');
                    $measurement->subparametro_id = 25;
                    $measurement->save();
                }

                if ($request->input('GD4')) {
                    $measurement = new medicion;
                    $measurement->valor = $request->input('GD4');
                    $measurement->fecha = Carbon::createFromFormat('d/m/Y', $request->dateGD4)->format('Y-m-d');
                    $measurement->comentario = $request->input('GD4comment');
                    $measurement->formacion_id = $request->input('formation');
                    $measurement->pozo_id = $request->input('well');
                    $measurement->subparametro_id = 26;
                    $measurement->save();
                }

                $request->session()->flash('mensaje', 'Record successfully entered.');

                return view('database');
            }
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

    public function readTab($tab, $file) {

        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $reader->setLoadSheetsOnly([$tab, $tab]);
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load($file);
        $worksheet = $spreadsheet->getActiveSheet();
        $response = "";

        // Starts always from 3.
        $rowsNumber = $this->countRows($worksheet);

        for ($row=3; $row <= $rowsNumber + 2; $row++) { 
            $response = $this->readTriplex($tab, $row, $worksheet);
        }

        if ($response === "error1") {
            return "error1";
        }elseif ($response === "error") {
            return "error";
        }
    }

    public function readTriplex($tab, $row, $worksheet) {
        
        // $triplexColumns = ['C', 'F', 'I', 'L', 'O'];
        $response_pozo = $response_formacion = "";
        $response1 = $response2 = $response3 = $response4 = $response5 = $response6 = $response7 = $response8 = $response9 = $response10 = $response11 = $response12 = $response13 = $response14 = $response15 = $response16 = $response17 = $response18 = $response19 = $response20 = $response21 = $response22 = $response23 = $response24 = $response25 = $response26 = $response27 = $response28 = "";

        // Averiguar formacion y pozo
        $pozo_nombre = $worksheet->getCell('A'.$row)->getValue();
        $pozo = DB::table('pozos')->where('nombre', $pozo_nombre)->first();
        if ($pozo == null) {
            //dd('pozo', $pozo, $pozo_nombre, $row);
            $response_pozo = "error1";
        }
        $formacion = $worksheet->getCell('B'.$row)->getValue();
        global $campo;
        $formacion = DB::table('formaciones')->where('nombre', $formacion)->where('campo_id', $campo)->first();
        // dd($formacion);
        if ($formacion == null) {
            $response_formacion = "error1";
        }

        switch ($tab) {
            
            case 'Mineral Scales':

                $value = $worksheet->getCell('C'.$row)->getValue();
                $fecha = $worksheet->getCell('D'.$row)->getValue();
                $comentario = $worksheet->getCell('E'.$row)->getValue();
                if (null !== $value) { 
                    // dd('MS1', $value, $fecha, $comentario, $formacion, $pozo);
                    $response1 = $this->guardarTripleta('MS1', $value, $fecha, $comentario, $formacion, $pozo);
                }

                $value = $worksheet->getCell('F'.$row)->getValue();
                $fecha = $worksheet->getCell('G'.$row)->getValue();
                $comentario = $worksheet->getCell('H'.$row)->getValue();
                if (null !== $value) { 
                    $response2 = $this->guardarTripleta('MS2', $value, $fecha, $comentario, $formacion, $pozo);
                }

                $value = $worksheet->getCell('I'.$row)->getValue();
                $fecha = $worksheet->getCell('J'.$row)->getValue();
                $comentario = $worksheet->getCell('K'.$row)->getValue();
                if (null !== $value) { 
                    $response3 = $this->guardarTripleta('MS3', $value, $fecha, $comentario, $formacion, $pozo);
                }

                $value = $worksheet->getCell('L'.$row)->getValue();
                $fecha = $worksheet->getCell('M'.$row)->getValue();
                $comentario = $worksheet->getCell('N'.$row)->getValue();
                if (null !== $value) { 
                    $response4 = $this->guardarTripleta('MS4', $value, $fecha, $comentario, $formacion, $pozo);
                }

                $value = $worksheet->getCell('O'.$row)->getValue();
                $fecha = $worksheet->getCell('P'.$row)->getValue();
                $comentario = $worksheet->getCell('Q'.$row)->getValue();
                if (null !== $value) { 
                    $response5 = $this->guardarTripleta('MS5', $value, $fecha, $comentario, $formacion, $pozo);
                }
            
            break;

            case 'Fine Blockage':
                
                $value = $worksheet->getCell('C'.$row)->getValue();
                $fecha = $worksheet->getCell('D'.$row)->getValue();
                $comentario = $worksheet->getCell('E'.$row)->getValue();
                if (null !== $value) { 
                    $response6 = $this->guardarTripleta('FB1', $value, $fecha, $comentario, $formacion, $pozo);
                }

                $value = $worksheet->getCell('F'.$row)->getValue();
                $fecha = $worksheet->getCell('G'.$row)->getValue();
                $comentario = $worksheet->getCell('H'.$row)->getValue();
                if (null !== $value) { 
                    $response7 = $this->guardarTripleta('FB2', $value, $fecha, $comentario, $formacion, $pozo);
                }

                $value = $worksheet->getCell('I'.$row)->getValue();
                $fecha = $worksheet->getCell('J'.$row)->getValue();
                $comentario = $worksheet->getCell('K'.$row)->getValue();
                if (null !== $value) { 
                    $response8 = $this->guardarTripleta('FB3', $value, $fecha, $comentario, $formacion, $pozo);
                }

                $value = $worksheet->getCell('L'.$row)->getValue();
                $fecha = $worksheet->getCell('M'.$row)->getValue();
                $comentario = $worksheet->getCell('N'.$row)->getValue();
                if (null !== $value) { 
                    $response9 = $this->guardarTripleta('FB4', $value, $fecha, $comentario, $formacion, $pozo);
                }

                $value = $worksheet->getCell('O'.$row)->getValue();
                $fecha = $worksheet->getCell('P'.$row)->getValue();
                $comentario = $worksheet->getCell('Q'.$row)->getValue();
                if (null !== $value) { 
                    $response10 = $this->guardarTripleta('FB5', $value, $fecha, $comentario, $formacion, $pozo);
                }

            break;

            case 'Organic Scales':

                $value = $worksheet->getCell('C'.$row)->getValue();
                $fecha = $worksheet->getCell('D'.$row)->getValue();
                $comentario = $worksheet->getCell('E'.$row)->getValue();
                if (null !== $value) { 
                    $response11 = $this->guardarTripleta('OS1', $value, $fecha, $comentario, $formacion, $pozo);
                }
                
                $value = $worksheet->getCell('F'.$row)->getValue();
                $fecha = $worksheet->getCell('G'.$row)->getValue();
                $comentario = $worksheet->getCell('H'.$row)->getValue();
                if (null !== $value) { 
                    $response12 = $this->guardarTripleta('OS2', $value, $fecha, $comentario, $formacion, $pozo);
                }

                $value = $worksheet->getCell('I'.$row)->getValue();
                $fecha = $worksheet->getCell('J'.$row)->getValue();
                $comentario = $worksheet->getCell('K'.$row)->getValue();
                if (null !== $value) { 
                    $response13 = $this->guardarTripleta('OS3', $value, $fecha, $comentario, $formacion, $pozo);
                }

                $value = $worksheet->getCell('L'.$row)->getValue();
                $fecha = $worksheet->getCell('M'.$row)->getValue();
                $comentario = $worksheet->getCell('N'.$row)->getValue();
                if (null !== $value) { 
                    $response14 = $this->guardarTripleta('OS4', $value, $fecha, $comentario, $formacion, $pozo);
                }

                $value = $worksheet->getCell('O'.$row)->getValue();
                $fecha = $worksheet->getCell('P'.$row)->getValue();
                $comentario = $worksheet->getCell('Q'.$row)->getValue();
                if (null !== $value) { 
                    $response15 = $this->guardarTripleta('OS5', $value, $fecha, $comentario, $formacion, $pozo);
                }

            break;

            case 'Relative Permeability':

                $value = $worksheet->getCell('C'.$row)->getValue();
                $fecha = $worksheet->getCell('D'.$row)->getValue();
                $comentario = $worksheet->getCell('E'.$row)->getValue();
                if (null !== $value) { 
                    $response16 = $this->guardarTripleta('RP1', $value, $fecha, $comentario, $formacion, $pozo);
                }

                $value = $worksheet->getCell('F'.$row)->getValue();
                $fecha = $worksheet->getCell('G'.$row)->getValue();
                $comentario = $worksheet->getCell('H'.$row)->getValue();
                if (null !== $value) {  
                    $response17 = $this->guardarTripleta('RP2', $value, $fecha, $comentario, $formacion, $pozo);
                }

                $value = $worksheet->getCell('O'.$row)->getValue();
                $fecha = $worksheet->getCell('J'.$row)->getValue();
                $comentario = $worksheet->getCell('K'.$row)->getValue();
                if (null !== $value) { 
                    $response18 = $this->guardarTripleta('RP3', $value, $fecha, $comentario, $formacion, $pozo);
                }

                $value = $worksheet->getCell('L'.$row)->getValue();
                $fecha = $worksheet->getCell('M'.$row)->getValue();
                $comentario = $worksheet->getCell('N'.$row)->getValue();
                if (null !== $value) { 
                    $response19 = $this->guardarTripleta('RP4', $value, $fecha, $comentario, $formacion, $pozo);
                }

                $value = $worksheet->getCell('O'.$row)->getValue();
                $fecha = $worksheet->getCell('P'.$row)->getValue();
                $comentario = $worksheet->getCell('Q'.$row)->getValue();
                if (null !== $value) { 
                    $response20 = $this->guardarTripleta('RP5', $value, $fecha, $comentario, $formacion, $pozo);
                }

            break;

            case 'Induced Damage':

                $value = $worksheet->getCell('C'.$row)->getValue();
                $fecha = $worksheet->getCell('D'.$row)->getValue();
                $comentario = $worksheet->getCell('E'.$row)->getValue();
                if (null !== $value) { 
                    $response21 = $this->guardarTripleta('ID1', $value, $fecha, $comentario, $formacion, $pozo);
                }

                $value = $worksheet->getCell('F'.$row)->getValue();
                $fecha = $worksheet->getCell('G'.$row)->getValue();
                $comentario = $worksheet->getCell('H'.$row)->getValue();
                if (null !== $value) { 
                    $response22 = $this->guardarTripleta('ID2', $value, $fecha, $comentario, $formacion, $pozo);
                }

                $value = $worksheet->getCell('I'.$row)->getValue();
                $fecha = $worksheet->getCell('J'.$row)->getValue();
                $comentario = $worksheet->getCell('K'.$row)->getValue();
                if (null !== $value) { 
                    $response23 = $this->guardarTripleta('ID3', $value, $fecha, $comentario, $formacion, $pozo);
                }

                $value = $worksheet->getCell('L'.$row)->getValue();
                $fecha = $worksheet->getCell('M'.$row)->getValue();
                $comentario = $worksheet->getCell('N'.$row)->getValue();
                if (null !== $value) { 
                    $response24 = $this->guardarTripleta('ID4', $value, $fecha, $comentario, $formacion, $pozo);
                }

            break;

            case 'Geomechanical Damage':

                $value = $worksheet->getCell('C'.$row)->getValue();
                $fecha = $worksheet->getCell('D'.$row)->getValue();
                $comentario = $worksheet->getCell('E'.$row)->getValue();
                if (null !== $value) { 
                    $response25 = $this->guardarTripleta('GD1', $value, $fecha, $comentario, $formacion, $pozo);
                }

                $value = $worksheet->getCell('F'.$row)->getValue();
                $fecha = $worksheet->getCell('G'.$row)->getValue();
                $comentario = $worksheet->getCell('H'.$row)->getValue();
                if (null !== $value) { 
                    $response26 = $this->guardarTripleta('GD2', $value, $fecha, $comentario, $formacion, $pozo);
                }

                $value = $worksheet->getCell('I'.$row)->getValue();
                $fecha = $worksheet->getCell('J'.$row)->getValue();
                $comentario = $worksheet->getCell('K'.$row)->getValue();
                if (null !== $value) { 
                    $response27 = $this->guardarTripleta('GD3', $value, $fecha, $comentario, $formacion, $pozo);
                }

                $value = $worksheet->getCell('L'.$row)->getValue();
                $fecha = $worksheet->getCell('M'.$row)->getValue();
                $comentario = $worksheet->getCell('N'.$row)->getValue();
                if (null !== $value) { 
                    $response28 = $this->guardarTripleta('GD4', $value, $fecha, $comentario, $formacion, $pozo);
                }

            break;
        }

        // dd($response1, $response2, $response3, $response4, $response5, $response6, $response7, $response8, $response9, $response10, $response11, $response12, $response13, $response14, 
        // $response15, $response16, $response17, $response18, $response19, $response20, $response21, $response22, $response23, $response24, $response25, $response26, $response27, $response28, $tab, $row);
        
        if ( $response_pozo === "error1" || $response_formacion === "error1" ) {
            return "error1";
        } elseif ($response1 === "error" ||
        $response2 === "error" ||
        $response3 === "error" ||
        $response4 === "error" ||
        $response5 === "error" ||
        $response6 === "error" ||
        $response7 === "error" ||
        $response8 === "error" ||
        $response9 === "error" ||
        $response10 === "error" ||
        $response11 === "error" ||
        $response12 === "error" ||
        $response13 === "error" ||
        $response14 === "error" ||
        $response15 === "error" ||
        $response16 === "error" ||
        $response17 === "error" ||
        $response18 === "error" ||
        $response19 === "error" ||
        $response20 === "error" ||
        $response21 === "error" ||
        $response22 === "error" ||
        $response23 === "error" ||
        $response24 === "error" ||
        $response25 === "error" ||
        $response26 === "error" ||
        $response27 === "error" ||
        $response28 === "error"
        ) {
            return "error";
        }
    }

    // Cuenta las filas del documento a leer
    public function countRows($worksheet) {

        $count = 0;
        $count = $worksheet->getHighestRow();

        return ($count - 2);
    }

    public function guardarTripleta($codigoTripleta, $valor, $fecha, $comentario, $formacion, $pozo) {

        if ($fecha != null) { 
            $fecha = Carbon::createFromFormat('d/m/Y', $fecha)->format('Y-m-d');
        }
        
        if ($codigoTripleta == 'MS1') {
            $medicion = DB::table('mediciones')->where('fecha', $fecha)->where('subparametro_id', 1)->first();
            if (null !== $medicion) {
                // validacion o reemplazo ?
                DB::table('mediciones')->where('fecha', $fecha)->where('subparametro_id', 1)->limit(1)->update([
                    'valor'             => $valor,
                    'fecha'             => $fecha,
                    'comentario'        => $comentario,
                    'formacion_id'      => $formacion->id,
                    'pozo_id'           => $pozo->id,
                    'subparametro_id'   => 1
                ]);
                // return "error";
            }else{
                $measurement = new medicion;
                $measurement->valor = $valor;
                $measurement->fecha = $fecha;
                $measurement->comentario = $comentario;
                $measurement->formacion_id = $formacion->id;
                $measurement->pozo_id = $pozo->id;
                $measurement->subparametro_id = 1;
                $measurement->save();
            }
        }

        if ($codigoTripleta == 'MS2') {
            $medicion = DB::table('mediciones')->where('fecha', $fecha)->where('subparametro_id', 2)->first();
            if (null !== $medicion) {
                // validacion o reemplazo ?
                DB::table('mediciones')->where('fecha', $fecha)->where('subparametro_id', 2)->limit(1)->update([
                    'valor'             => $valor,
                    'fecha'             => $fecha,
                    'comentario'        => $comentario,
                    'formacion_id'      => $formacion->id,
                    'pozo_id'           => $pozo->id,
                    'subparametro_id'   => 2
                ]);
            }else{
                $measurement = new medicion;
                $measurement->valor = $valor;
                $measurement->fecha = $fecha;
                $measurement->comentario = $comentario;
                $measurement->formacion_id = $formacion->id;
                $measurement->pozo_id = $pozo->id;
                $measurement->subparametro_id = 2;
                $measurement->save();
            }
        }

        if ($codigoTripleta == 'MS3') {
            $medicion = DB::table('mediciones')->where('fecha', $fecha)->where('subparametro_id', 3)->first();
            if (null !== $medicion) {
                // validacion o reemplazo ?
                DB::table('mediciones')->where('fecha', $fecha)->where('subparametro_id', 3)->limit(1)->update([
                    'valor'             => $valor,
                    'fecha'             => $fecha,
                    'comentario'        => $comentario,
                    'formacion_id'      => $formacion->id,
                    'pozo_id'           => $pozo->id,
                    'subparametro_id'   => 3
                ]);
            }else{
                $measurement = new medicion;
                $measurement->valor = $valor;
                $measurement->fecha = $fecha;
                $measurement->comentario = $comentario;
                $measurement->formacion_id = $formacion->id;
                $measurement->pozo_id = $pozo->id;
                $measurement->subparametro_id = 3;
                $measurement->save();
            }
        }

        if ($codigoTripleta == 'MS4') {
            $medicion = DB::table('mediciones')->where('fecha', $fecha)->where('subparametro_id', 4)->first();
            if (null !== $medicion) {
                // validacion o reemplazo ?
                DB::table('mediciones')->where('fecha', $fecha)->where('subparametro_id', 4)->limit(1)->update([
                    'valor'             => $valor,
                    'fecha'             => $fecha,
                    'comentario'        => $comentario,
                    'formacion_id'      => $formacion->id,
                    'pozo_id'           => $pozo->id,
                    'subparametro_id'   => 4
                ]);
            }else{
                $measurement = new medicion;
                $measurement->valor = $valor;
                $measurement->fecha = $fecha;
                $measurement->comentario = $comentario;
                $measurement->formacion_id = $formacion->id;
                $measurement->pozo_id = $pozo->id;
                $measurement->subparametro_id = 4;
                $measurement->save();
            }
        }

        if ($codigoTripleta == 'MS5') {
            $medicion = DB::table('mediciones')->where('fecha', $fecha)->where('subparametro_id', 5)->first();
            if (null !== $medicion) {
                // validacion o reemplazo ?
                DB::table('mediciones')->where('fecha', $fecha)->where('subparametro_id', 5)->limit(1)->update([
                    'valor'             => $valor,
                    'fecha'             => $fecha,
                    'comentario'        => $comentario,
                    'formacion_id'      => $formacion->id,
                    'pozo_id'           => $pozo->id,
                    'subparametro_id'   => 5
                ]);
            }else{
                $measurement = new medicion;
                $measurement->valor = $valor;
                $measurement->fecha = $fecha;
                $measurement->comentario = $comentario;
                $measurement->formacion_id = $formacion->id;
                $measurement->pozo_id = $pozo->id;
                $measurement->subparametro_id = 5;
                $measurement->save();
            }
        }

        if ($codigoTripleta == 'FB1') {
            $medicion = DB::table('mediciones')->where('fecha', $fecha)->where('subparametro_id', 6)->first();
            if (null !== $medicion) {
                // validacion o reemplazo ?
                DB::table('mediciones')->where('fecha', $fecha)->where('subparametro_id', 6)->limit(1)->update([
                    'valor'             => $valor,
                    'fecha'             => $fecha,
                    'comentario'        => $comentario,
                    'formacion_id'      => $formacion->id,
                    'pozo_id'           => $pozo->id,
                    'subparametro_id'   => 6
                ]);
            }else{
                $measurement = new medicion;
                $measurement->valor = $valor;
                $measurement->fecha = $fecha;
                $measurement->comentario = $comentario;
                $measurement->formacion_id = $formacion->id;
                $measurement->pozo_id = $pozo->id;
                $measurement->subparametro_id = 6;
                $measurement->save();
            }
        }

        if ($codigoTripleta == 'FB2') {
            $medicion = DB::table('mediciones')->where('fecha', $fecha)->where('subparametro_id', 7)->first();
            if (null !== $medicion) {
                // validacion o reemplazo ?
                DB::table('mediciones')->where('fecha', $fecha)->where('subparametro_id', 7)->limit(1)->update([
                    'valor'             => $valor,
                    'fecha'             => $fecha,
                    'comentario'        => $comentario,
                    'formacion_id'      => $formacion->id,
                    'pozo_id'           => $pozo->id,
                    'subparametro_id'   => 7
                ]);
            }else{
                $measurement = new medicion;
                $measurement->valor = $valor;
                $measurement->fecha = $fecha;
                $measurement->comentario = $comentario;
                $measurement->formacion_id = $formacion->id;
                $measurement->pozo_id = $pozo->id;
                $measurement->subparametro_id = 7;
                $measurement->save();
            }
        }

        if ($codigoTripleta == 'FB3') {
            $medicion = DB::table('mediciones')->where('fecha', $fecha)->where('subparametro_id', 8)->first();
            if (null !== $medicion) {
                // validacion o reemplazo ?
                DB::table('mediciones')->where('fecha', $fecha)->where('subparametro_id', 8)->limit(1)->update([
                    'valor'             => $valor,
                    'fecha'             => $fecha,
                    'comentario'        => $comentario,
                    'formacion_id'      => $formacion->id,
                    'pozo_id'           => $pozo->id,
                    'subparametro_id'   => 8
                ]);
            }else{
                $measurement = new medicion;
                $measurement->valor = $valor;
                $measurement->fecha = $fecha;
                $measurement->comentario = $comentario;
                $measurement->formacion_id = $formacion->id;
                $measurement->pozo_id = $pozo->id;
                $measurement->subparametro_id = 8;
                $measurement->save();
            }
        }

        if ($codigoTripleta == 'FB4') {
            $medicion = DB::table('mediciones')->where('fecha', $fecha)->where('subparametro_id', 9)->first();
            if (null !== $medicion) {
                // validacion o reemplazo ?
                DB::table('mediciones')->where('fecha', $fecha)->where('subparametro_id', 9)->limit(1)->update([
                    'valor'             => $valor,
                    'fecha'             => $fecha,
                    'comentario'        => $comentario,
                    'formacion_id'      => $formacion->id,
                    'pozo_id'           => $pozo->id,
                    'subparametro_id'   => 9
                ]);
            }else{
                $measurement = new medicion;
                $measurement->valor = $valor;
                $measurement->fecha = $fecha;
                $measurement->comentario = $comentario;
                $measurement->formacion_id = $formacion->id;
                $measurement->pozo_id = $pozo->id;
                $measurement->subparametro_id = 9;
                $measurement->save();
            }
        }

        if ($codigoTripleta == 'FB5') {
            $medicion = DB::table('mediciones')->where('fecha', $fecha)->where('subparametro_id', 10)->first();
            if (null !== $medicion) {
                // validacion o reemplazo ?
                DB::table('mediciones')->where('fecha', $fecha)->where('subparametro_id', 10)->limit(1)->update([
                    'valor'             => $valor,
                    'fecha'             => $fecha,
                    'comentario'        => $comentario,
                    'formacion_id'      => $formacion->id,
                    'pozo_id'           => $pozo->id,
                    'subparametro_id'   => 10
                ]);
            }else{
                $measurement = new medicion;
                $measurement->valor = $valor;
                $measurement->fecha = $fecha;
                $measurement->comentario = $comentario;
                $measurement->formacion_id = $formacion->id;
                $measurement->pozo_id = $pozo->id;
                $measurement->subparametro_id = 10;
                $measurement->save();
            }
        }

        if ($codigoTripleta == 'OS1') {
            $medicion = DB::table('mediciones')->where('fecha', $fecha)->where('subparametro_id', 11)->first();
            if (null !== $medicion) {
                // validacion o reemplazo ?
                DB::table('mediciones')->where('fecha', $fecha)->where('subparametro_id', 11)->limit(1)->update([
                    'valor'             => $valor,
                    'fecha'             => $fecha,
                    'comentario'        => $comentario,
                    'formacion_id'      => $formacion->id,
                    'pozo_id'           => $pozo->id,
                    'subparametro_id'   => 11
                ]);
            }else{
                $measurement = new medicion;
                $measurement->valor = $valor;
                $measurement->fecha = $fecha;
                $measurement->comentario = $comentario;
                $measurement->formacion_id = $formacion->id;
                $measurement->pozo_id = $pozo->id;
                $measurement->subparametro_id = 11;
                $measurement->save();
            }
        }

        if ($codigoTripleta == 'OS2') {
            $medicion = DB::table('mediciones')->where('fecha', $fecha)->where('subparametro_id', 30)->first();
            if (null !== $medicion) {
                // validacion o reemplazo ?
                DB::table('mediciones')->where('fecha', $fecha)->where('subparametro_id', 30)->limit(1)->update([
                    'valor'             => $valor,
                    'fecha'             => $fecha,
                    'comentario'        => $comentario,
                    'formacion_id'      => $formacion->id,
                    'pozo_id'           => $pozo->id,
                    'subparametro_id'   => 30
                ]);
            }else{
                $measurement = new medicion;
                $measurement->valor = $valor;
                $measurement->fecha = $fecha;
                $measurement->comentario = $comentario;
                $measurement->formacion_id = $formacion->id;
                $measurement->pozo_id = $pozo->id;
                $measurement->subparametro_id = 30;
                $measurement->save();
            }
        }

        if ($codigoTripleta == 'OS3') {
            $medicion = DB::table('mediciones')->where('fecha', $fecha)->where('subparametro_id', 12)->first();
            if (null !== $medicion) {
                // validacion o reemplazo ?
                DB::table('mediciones')->where('fecha', $fecha)->where('subparametro_id', 12)->limit(1)->update([
                    'valor'             => $valor,
                    'fecha'             => $fecha,
                    'comentario'        => $comentario,
                    'formacion_id'      => $formacion->id,
                    'pozo_id'           => $pozo->id,
                    'subparametro_id'   => 12
                ]);
            }else{
                $measurement = new medicion;
                $measurement->valor = $valor;
                $measurement->fecha = $fecha;
                $measurement->comentario = $comentario;
                $measurement->formacion_id = $formacion->id;
                $measurement->pozo_id = $pozo->id;
                $measurement->subparametro_id = 12;
                $measurement->save();
            }
        }

        if ($codigoTripleta == 'OS4') {
            $medicion = DB::table('mediciones')->where('fecha', $fecha)->where('subparametro_id', 13)->first();
            if (null !== $medicion) {
                // validacion o reemplazo ?
                DB::table('mediciones')->where('fecha', $fecha)->where('subparametro_id', 13)->limit(1)->update([
                    'valor'             => $valor,
                    'fecha'             => $fecha,
                    'comentario'        => $comentario,
                    'formacion_id'      => $formacion->id,
                    'pozo_id'           => $pozo->id,
                    'subparametro_id'   => 13
                ]);
            }else{
                $measurement = new medicion;
                $measurement->valor = $valor;
                $measurement->fecha = $fecha;
                $measurement->comentario = $comentario;
                $measurement->formacion_id = $formacion->id;
                $measurement->pozo_id = $pozo->id;
                $measurement->subparametro_id = 13;
                $measurement->save();
            }
        }

        if ($codigoTripleta == 'OS5') {
            $medicion = DB::table('mediciones')->where('fecha', $fecha)->where('subparametro_id', 14)->first();
            if (null !== $medicion) {
                // validacion o reemplazo ?
                DB::table('mediciones')->where('fecha', $fecha)->where('subparametro_id', 14)->limit(1)->update([
                    'valor'             => $valor,
                    'fecha'             => $fecha,
                    'comentario'        => $comentario,
                    'formacion_id'      => $formacion->id,
                    'pozo_id'           => $pozo->id,
                    'subparametro_id'   => 14
                ]);
            }else{
                $measurement = new medicion;
                $measurement->valor = $valor;
                $measurement->fecha = $fecha;
                $measurement->comentario = $comentario;
                $measurement->formacion_id = $formacion->id;
                $measurement->pozo_id = $pozo->id;
                $measurement->subparametro_id = 14;
                $measurement->save();
            }
        }

        if ($codigoTripleta == 'RP1') {
            $medicion = DB::table('mediciones')->where('fecha', $fecha)->where('subparametro_id', 15)->first();
            if (null !== $medicion) {
                // validacion o reemplazo ?
                DB::table('mediciones')->where('fecha', $fecha)->where('subparametro_id', 15)->limit(1)->update([
                    'valor'             => $valor,
                    'fecha'             => $fecha,
                    'comentario'        => $comentario,
                    'formacion_id'      => $formacion->id,
                    'pozo_id'           => $pozo->id,
                    'subparametro_id'   => 15
                ]);
            }else{
                $measurement = new medicion;
                $measurement->valor = $valor;
                $measurement->fecha = $fecha;
                $measurement->comentario = $comentario;
                $measurement->formacion_id = $formacion->id;
                $measurement->pozo_id = $pozo->id;
                $measurement->subparametro_id = 15;
                $measurement->save();
            }
        }

        if ($codigoTripleta == 'RP2') {
            $medicion = DB::table('mediciones')->where('fecha', $fecha)->where('subparametro_id', 16)->first();
            if (null !== $medicion) {
                // validacion o reemplazo ?
                DB::table('mediciones')->where('fecha', $fecha)->where('subparametro_id', 16)->limit(1)->update([
                    'valor'             => $valor,
                    'fecha'             => $fecha,
                    'comentario'        => $comentario,
                    'formacion_id'      => $formacion->id,
                    'pozo_id'           => $pozo->id,
                    'subparametro_id'   => 16
                ]);
            }else{
                $measurement = new medicion;
                $measurement->valor = $valor;
                $measurement->fecha = $fecha;
                $measurement->comentario = $comentario;
                $measurement->formacion_id = $formacion->id;
                $measurement->pozo_id = $pozo->id;
                $measurement->subparametro_id = 16;
                $measurement->save();
            }
        }

        if ($codigoTripleta == 'RP3') {
            $medicion = DB::table('mediciones')->where('fecha', $fecha)->where('subparametro_id', 17)->first();
            if (null !== $medicion) {
                // validacion o reemplazo ?
                DB::table('mediciones')->where('fecha', $fecha)->where('subparametro_id', 17)->limit(1)->update([
                    'valor'             => $valor,
                    'fecha'             => $fecha,
                    'comentario'        => $comentario,
                    'formacion_id'      => $formacion->id,
                    'pozo_id'           => $pozo->id,
                    'subparametro_id'   => 17
                ]);
            }else{
                $measurement = new medicion;
                $measurement->valor = $valor;
                $measurement->fecha = $fecha;
                $measurement->comentario = $comentario;
                $measurement->formacion_id = $formacion->id;
                $measurement->pozo_id = $pozo->id;
                $measurement->subparametro_id = 17;
                $measurement->save();
            }
        }

        if ($codigoTripleta == 'RP4') {
            $medicion = DB::table('mediciones')->where('fecha', $fecha)->where('subparametro_id', 18)->first();
            if (null !== $medicion) {
                // validacion o reemplazo ?
                DB::table('mediciones')->where('fecha', $fecha)->where('subparametro_id', 18)->limit(1)->update([
                    'valor'             => $valor,
                    'fecha'             => $fecha,
                    'comentario'        => $comentario,
                    'formacion_id'      => $formacion->id,
                    'pozo_id'           => $pozo->id,
                    'subparametro_id'   => 18
                ]);
            }else{
                $measurement = new medicion;
                $measurement->valor = $valor;
                $measurement->fecha = $fecha;
                $measurement->comentario = $comentario;
                $measurement->formacion_id = $formacion->id;
                $measurement->pozo_id = $pozo->id;
                $measurement->subparametro_id = 18;
                $measurement->save();
            }
        }

        if ($codigoTripleta == 'RP5') {
            $medicion = DB::table('mediciones')->where('fecha', $fecha)->where('subparametro_id', 31)->first();
            if (null !== $medicion) {
                // validacion o reemplazo ?
                DB::table('mediciones')->where('fecha', $fecha)->where('subparametro_id', 31)->limit(1)->update([
                    'valor'             => $valor,
                    'fecha'             => $fecha,
                    'comentario'        => $comentario,
                    'formacion_id'      => $formacion->id,
                    'pozo_id'           => $pozo->id,
                    'subparametro_id'   => 31
                ]);
            }else{
                $measurement = new medicion;
                $measurement->valor = $valor;
                $measurement->fecha = $fecha;
                $measurement->comentario = $comentario;
                $measurement->formacion_id = $formacion->id;
                $measurement->pozo_id = $pozo->id;
                $measurement->subparametro_id = 31;
                $measurement->save();
            }
        }

        if ($codigoTripleta == 'ID1') {
            $medicion = DB::table('mediciones')->where('fecha', $fecha)->where('subparametro_id', 19)->first();
            if (null !== $medicion) {
                // validacion o reemplazo ?
                DB::table('mediciones')->where('fecha', $fecha)->where('subparametro_id', 19)->limit(1)->update([
                    'valor'             => $valor,
                    'fecha'             => $fecha,
                    'comentario'        => $comentario,
                    'formacion_id'      => $formacion->id,
                    'pozo_id'           => $pozo->id,
                    'subparametro_id'   => 19
                ]);
            }else{
                $measurement = new medicion;
                $measurement->valor = $valor;
                $measurement->fecha = $fecha;
                $measurement->comentario = $comentario;
                $measurement->formacion_id = $formacion->id;
                $measurement->pozo_id = $pozo->id;
                $measurement->subparametro_id = 19;
                $measurement->save();
            }
        }

        if ($codigoTripleta == 'ID2') {
            $medicion = DB::table('mediciones')->where('fecha', $fecha)->where('subparametro_id', 20)->first();
            if (null !== $medicion) {
                // validacion o reemplazo ?
                DB::table('mediciones')->where('fecha', $fecha)->where('subparametro_id', 20)->limit(1)->update([
                    'valor'             => $valor,
                    'fecha'             => $fecha,
                    'comentario'        => $comentario,
                    'formacion_id'      => $formacion->id,
                    'pozo_id'           => $pozo->id,
                    'subparametro_id'   => 20
                ]);
            }else{
                $measurement = new medicion;
                $measurement->valor = $valor;
                $measurement->fecha = $fecha;
                $measurement->comentario = $comentario;
                $measurement->formacion_id = $formacion->id;
                $measurement->pozo_id = $pozo->id;
                $measurement->subparametro_id = 20;
                $measurement->save();
            }
        }

        if ($codigoTripleta == 'ID3') {
            $medicion = DB::table('mediciones')->where('fecha', $fecha)->where('subparametro_id', 21)->first();
            if (null !== $medicion) {
                // validacion o reemplazo ?
                DB::table('mediciones')->where('fecha', $fecha)->where('subparametro_id', 21)->limit(1)->update([
                    'valor'             => $valor,
                    'fecha'             => $fecha,
                    'comentario'        => $comentario,
                    'formacion_id'      => $formacion->id,
                    'pozo_id'           => $pozo->id,
                    'subparametro_id'   => 21
                ]);
            }else{
                $measurement = new medicion;
                $measurement->valor = $valor;
                $measurement->fecha = $fecha;
                $measurement->comentario = $comentario;
                $measurement->formacion_id = $formacion->id;
                $measurement->pozo_id = $pozo->id;
                $measurement->subparametro_id = 21;
                $measurement->save();
            }
        }

        if ($codigoTripleta == 'ID4') {
            $medicion = DB::table('mediciones')->where('fecha', $fecha)->where('subparametro_id', 22)->first();
            if (null !== $medicion) {
                // validacion o reemplazo ?
                DB::table('mediciones')->where('fecha', $fecha)->where('subparametro_id', 22)->limit(1)->update([
                    'valor'             => $valor,
                    'fecha'             => $fecha,
                    'comentario'        => $comentario,
                    'formacion_id'      => $formacion->id,
                    'pozo_id'           => $pozo->id,
                    'subparametro_id'   => 22
                ]);
            }else{
                $measurement = new medicion;
                $measurement->valor = $valor;
                $measurement->fecha = $fecha;
                $measurement->comentario = $comentario;
                $measurement->formacion_id = $formacion->id;
                $measurement->pozo_id = $pozo->id;
                $measurement->subparametro_id = 22;
                $measurement->save();
            }
        }

        if ($codigoTripleta == 'GD1') {
            $medicion = DB::table('mediciones')->where('fecha', $fecha)->where('subparametro_id', 23)->first();
            if (null !== $medicion) {
                // validacion o reemplazo ?
                DB::table('mediciones')->where('fecha', $fecha)->where('subparametro_id', 23)->limit(1)->update([
                    'valor'             => $valor,
                    'fecha'             => $fecha,
                    'comentario'        => $comentario,
                    'formacion_id'      => $formacion->id,
                    'pozo_id'           => $pozo->id,
                    'subparametro_id'   => 23
                ]);
            }else{
                $measurement = new medicion;
                $measurement->valor = $valor;
                $measurement->fecha = $fecha;
                $measurement->comentario = $comentario;
                $measurement->formacion_id = $formacion->id;
                $measurement->pozo_id = $pozo->id;
                $measurement->subparametro_id = 23;
                $measurement->save();
            }
        }

        if ($codigoTripleta == 'GD2') {
            $medicion = DB::table('mediciones')->where('fecha', $fecha)->where('subparametro_id', 24)->first();
            if (null !== $medicion) {
                // validacion o reemplazo ?
                DB::table('mediciones')->where('fecha', $fecha)->where('subparametro_id', 24)->limit(1)->update([
                    'valor'             => $valor,
                    'fecha'             => $fecha,
                    'comentario'        => $comentario,
                    'formacion_id'      => $formacion->id,
                    'pozo_id'           => $pozo->id,
                    'subparametro_id'   => 24
                ]);
            }else{
                $measurement = new medicion;
                $measurement->valor = $valor;
                $measurement->fecha = $fecha;
                $measurement->comentario = $comentario;
                $measurement->formacion_id = $formacion->id;
                $measurement->pozo_id = $pozo->id;
                $measurement->subparametro_id = 24;
                $measurement->save();
            }
        }

        if ($codigoTripleta == 'GD3') {
            $medicion = DB::table('mediciones')->where('fecha', $fecha)->where('subparametro_id', 25)->first();
            if (null !== $medicion) {
                // validacion o reemplazo ?
                DB::table('mediciones')->where('fecha', $fecha)->where('subparametro_id', 25)->limit(1)->update([
                    'valor'             => $valor,
                    'fecha'             => $fecha,
                    'comentario'        => $comentario,
                    'formacion_id'      => $formacion->id,
                    'pozo_id'           => $pozo->id,
                    'subparametro_id'   => 25
                ]);
            }else{
                $measurement = new medicion;
                $measurement->valor = $valor;
                $measurement->fecha = $fecha;
                $measurement->comentario = $comentario;
                $measurement->formacion_id = $formacion->id;
                $measurement->pozo_id = $pozo->id;
                $measurement->subparametro_id = 25;
                $measurement->save();
            }
        }

        if ($codigoTripleta == 'GD4') {
            $medicion = DB::table('mediciones')->where('fecha', $fecha)->where('subparametro_id', 26)->first();
            if (null !== $medicion) {
                // validacion o reemplazo ?
                DB::table('mediciones')->where('fecha', $fecha)->where('subparametro_id', 26)->limit(1)->update([
                    'valor'             => $valor,
                    'fecha'             => $fecha,
                    'comentario'        => $comentario,
                    'formacion_id'      => $formacion->id,
                    'pozo_id'           => $pozo->id,
                    'subparametro_id'   => 26
                ]);
            }else{
                $measurement = new medicion;
                $measurement->valor = $valor;
                $measurement->fecha = $fecha;
                $measurement->comentario = $comentario;
                $measurement->formacion_id = $formacion->id;
                $measurement->pozo_id = $pozo->id;
                $measurement->subparametro_id = 26;
                $measurement->save();
            }
        }
    }
}
