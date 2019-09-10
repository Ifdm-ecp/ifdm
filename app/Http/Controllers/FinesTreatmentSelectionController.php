<?php

namespace App\Http\Controllers;
use DB;
use Validator;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Requests\FinesTreatmentSelectionRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\GeneralInformationRequest;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Session;
use App\multiparametrico;
use App\formacionxpozo;
use App\escenario;
use App\production_data;
use View;
use App\cuenca;
use App\subparameters_weight;
use App\FinesTreatmentSelection;

class FinesTreatmentSelectionController extends Controller
{

    public function create()
    {
        if (\Auth::check()) {
            $scenary = escenario::find(\Request::get('scenaryId'));
            $advisor = $scenary->enable_advisor;

            //dd($fines);
            return View::make('fts.create', compact(['scenary', 'advisor']));
        }else{
            return view('loginfirst');
        }
    }


    public function store(FinesTreatmentSelectionRequest $request)
    {
        $storeFts = FinesTreatmentSelection::create($request->all());
        if($storeFts){
            escenario::escenarioCompleto($request->escenario_id);
        }
        return redirect()->route('fts.show', $request->escenario_id);

    }

    public function edit($id)
    {
        if (\Auth::check()){
            $scenary = escenario::find($id);
            $_SESSION['well'] = $scenary->pozo->nombre;//well
            $_SESSION['field'] = $scenary->campo->nombre;//field
            $_SESSION['basin'] = $scenary->cuenca->nombre;//basin
            $_SESSION['formation_id'] = $scenary->formacionxpozo->nombre;//basin
            $user = $scenary->user;
            $advisor = $scenary->enable_advisor;
            $datos = $scenary->fts;

            $duplicateFrom = isset($_SESSION['scenary_id_dup']) ? $_SESSION['scenary_id_dup'] : null;
            if($datos) {
                return View::make('fts.edit', compact(['scenary','user', 'advisor', 'datos', 'duplicateFrom']));
            }else{
                return View::make('fts.create', compact(['scenary','user', 'advisor']));
            }
            
            
        }else{
            return view('loginfirst');
        }
    }

    /* Recibe id del nuevo escenario, duplicateFrom seria el id del duplicado */    
    public function duplicate($id,$duplicateFrom)
    {
        $_SESSION['scenary_id_dup'] = $id;
        return $this->edit($duplicateFrom);
    }

    public function update(FinesTreatmentSelectionRequest $request, $id){
        $datos_ = $request->all();
        $datos_ = array_merge($datos_, ['status_wr' => !isset($_POST['button_wr'])]);
        if(!empty($request->duplicateFrom)){
            $update = FinesTreatmentSelection::create($datos_);
            $redirect = $update->escenario_id;
        } else {            
            $update = FinesTreatmentSelection::find($id)->update($datos_);
            $redirect = $request->escenario_id;
        }

        unset($_SESSION['scenary_id_dup']);

        return redirect()->route('fts.show', $redirect);
    }
    
    public function show($id)
    {
        $scenary = escenario::find($id);
        $db = $scenary->fts;
        $result = $this->result($db);

        $pickling['name'] = '1. Pickling';
        $pickling['data'] = $result[0]; 

        $solvent['name'] = '2. Solvent';
        $solvent['data'] = $result[1]; 

        $waterFormation['name'] = '3. Water Formation';
        $waterFormation['data'] = $result[2]; 

        $preControl['name'] = '4a. Pre-Control'; 
        $preControl['data'] = $result[3]; 

        $preflush['name'] = '4. Pre-Flush'; 
        $preflush['data'] = $result[4]; 

        $principalTreatment['name'] = '5. Principal Treatment'; 
        $principalTreatment['data'] = $result[5]; 


        $overFlush['name'] = '6. OverFlush';
        $overFlush['data'] = $result[6];
        $result = collect([$pickling,  $solvent, $waterFormation, $preControl, $preflush, $principalTreatment, $overFlush]);
        //dd($result);
        return view('fts.show', compact('result', 'db','scenary'));

    }

    public function result($fts)
    {
        //variables globales
        $chlorite = $fts->chloritem+$fts->chloritec+$fts->chloriteim;
        $ankerite = $fts->ankeritec+$fts->ankeriteim;
        $siderite = $fts->sideritec+$fts->sideriteim;
        $feldspars= $fts->microcline+$fts->orthoclase+$fts->albite+$fts->plagioClase;    
        $clays = $fts->kaolinite+$fts->illite+$fts->emectite+$chlorite+$fts->brucite+ $fts->gibbsite;
        $carbonates =  $fts->calcite+$fts->dolomite+$ankerite+$siderite;
        $ironM = $fts->hematite+$fts->magnetite+$fts->pyrrhotite+$fts->pyrite+$chlorite+$siderite+$ankerite+$fts->glauconite+$fts->chamosite+$fts->troilite; 
        $zeolites= $fts->stilbite+$fts->heulandite+$fts->chabazite+$fts->natrolite+$fts->analcime;
        $NH4Cl=round(-0.4082*$fts->sal+5.0408);
        //dd($request->all());

        //funciones globales
        $cin = $this->CIN($fts->ty);
        $fst = $this->FSt($fts->ty,$fts->k,$fts->tvd,$fts->finesna);
        $cfe = $this->CFe($fts->hematite, $fts->magnetite, $clays,$fts->ty,$fts->pyrite,$siderite,$fts->pyrrhotite,$zeolites, $fts->chamosite);
        $zeo = $this->ZEO($zeolites);

        //dd($zeolites);

        //1. pickling
        $pickling = $this->pickling( $fts->ty,  $fts->tc, $fts->iic, $fts->hematite,  $fts->magnetite, $clays, $fts->pyrite, $siderite, $fts->pyrrhotite, $zeolites,  $fts->chamosite, $fts->h2s, $cin, $cfe);


        //2. Solvent Displacement
        $solvent = $this->solvent($fts->em, $fts->wet, $fts->tc, $fts->ty, $fts->iic);

        ////%% 3. Water Formation Displacement
        $WaterFormation = $this->WaterFormation($fts->isl, $fts->calcite, $fts->melanterite, $fts->anhydrite, $fts->halite, $fts->baryte, $fts->brucite, $fts->cast, $fts->hematite, $fts->magnetite, $siderite, $fts->troilite, $fts->celestine, $fts->ty, $fts->illite, $fts->emectite, $cin, $NH4Cl);

        ////4a. Preflush: Iron Mineral Control////
        $preControl = $this->preControl($ironM, $cfe);


        //////4. Preflush (Optional)/////

        $preflush = $this->preflush($ankerite, $carbonates, $chlorite, $clays, $feldspars, $siderite, $zeolites, $fts->anhydrite, $fts->baryte, $fts->brucite, $fts->calcite,$fts->cast, $fts->celestine,$fts->dolomite, $fts->finesna, $fts->glauconite,$fts->halite, $fts->hematite, $fts->illite, $fts->isl, $fts->k, $fts->magnetite, $fts->melanterite,$fts->quartz, $fts->troilite, $fts->tvd, $fts->ty, $fst, $cin, $NH4Cl);

        $principalTreatment = $this->principalTreatment( $ankerite, $fts->calcite, $clays, $fts->dolomite, $feldspars, $fts->analcime, $fts->ty, $chlorite, $fts->illite, $fts->emectite, $fts->kaolinite, $fts->glauconite, $fts->quartz, $siderite, $zeolites, $fts->k, $fts->tvd, $fts->finesna, $zeo, $fst, $cin, $cfe, $NH4Cl);
        //dd($preflush);


        $overFlush = $this->overFlush($clays, $fts->ty, $zeolites, $cin, $fst, $NH4Cl);
        //dd($overFlush);


        return [$pickling, $solvent, $WaterFormation, $preControl, $preflush, $principalTreatment, $overFlush, $cfe, ]; 

    }

    public function overFlush($clays, $ty, $zeolites, $cin, $fst, $NH4Cl)
    {
        $data = collect();
        $additive1['additive'] = collect();
        $additive2['additive'] = collect();
        $additive3['additive'] = collect();
        $additive4['additive'] = collect();
        $additive5['additive'] = collect();
        $additive6['additive'] = collect();

        if($ty < 300 && $zeolites >= 2 && $zeolites <= 5)
        {
            $data1['name'] = 'Corrosion Inhibitor';
            $data1['data'] = $cin;
            $additive1['additive']->push($data1);
            $data1a['name'] = '10% Acetic Acid + 5% NH4Cl';
            $additive1['additive']->push($data1a);

            $additive1['tittle'] = '10% Acetic Acid + 5% NH4Cl';
            $additive1['score'] = 100;

            $data->push($additive1);
        }

        //% 10% Citric Acid + 5% NH4Cl + Corrosion Inhibitor
        if($ty < 300 && $zeolites <= 5 && $clays <= 5)
        {
            $data2['name'] = 'Corrosion Inhibitor';
            $data2['data'] = $cin;
            $additive2['additive']->push($data2);
            $data2a['name'] = '10% Acetic Acid + 5% NH4Cl';
            $additive2['additive']->push($data2a);

            $additive2['tittle'] = '10% Citric Acid + 5% NH4Cl';
            $additive2['score'] = 100;

            $data->push($additive2);
        }

        //% 10% EGMBE + 5% NH4Cl + Clay Stabilizer
        if($ty > 0)
        {
            if($clays > 5){
                $data3['name'] = 'Fines Stabilizer';
                $data3['data'] = $fst;
                $additive3['additive']->push($data3);
            }

            $additive3['tittle'] = '10% EGMBE + 5% NH4Cl';
            $additive3['score'] = 67;

            $data->push($additive3);
        }

        //% 3%-5% HCl + 5% NH4Cl + Corrosion Inhibitor
        if($ty < 250 && $zeolites <= 2 && $clays <= 5)
        {
            $data4['name'] = 'Corrosion Inhibitor';
            $data4['data'] = $cin;
            $additive4['additive']->push($data4);
            $additive4['tittle'] = '3%-5% HCl + 5% NH4Cl';
            $additive4['score'] = 100;

            $data->push($additive4);
        }

        //%  % NH4Cl + Corrosion Inhibitor
        if($ty > 0)
        {

            $data5['name'] = 'Corrosion Inhibitor';
            $data5['data'] = $cin;
            $additive5['additive']->push($data5);


            $data5a['name'] = 'Fines Stabilizer';
            $data5a['data'] = collect('0.5% Organosilane');
            $additive5['additive']->push($data5a);

            $additive5['tittle'] = $NH4Cl.'% NH4CL';
            $additive5['score'] = 100;

            $data->push($additive5);

        }

        //% Acetic Acid + HF
        if($zeolites > 5)
        {
            $data6a['name'] = '';
            $additive6['additive']->push($data6a);
            $additive6['tittle'] = '10% Citric Acid + 0.5 HF';
            $additive6['score'] = 100;

            $data->push($additive6);
        }


        return $data;
    }

    public function principalTreatment( $ankerite, $calcite, $clays, $dolomite, $feldspars, $analcime, $ty, $chlorite, $illite, $emectite, $kaolinite, $glauconite, $quartz, $siderite, $zeolites, $k, $tvd, $finesna, $zeo, $fst, $cin, $cfe, $NH4Cl)
    {

        $data = collect();
        $additive1['additive'] = collect();
        $additive6['additive'] = collect();
        $additive7['additive'] = collect();
        $additive8['additive'] = collect();
        $additive9['additive'] = collect();
        $additive10['additive'] = collect();
        $additive11['additive'] = collect();
        $additive2['additive'] = collect();
        $additive3['additive'] = collect();
        $additive4['additive'] = collect();
        $additive5['additive'] = collect();

        //////5. Principal Treatment /////
        if(($analcime > 0 && $ty < 107) || ($chlorite > 0 && $ty < 146) || ($illite > 0 && $ty < 163) || ($emectite > 0 && $ty < 181) || ($kaolinite > 0 && $ty < 196))
        {

            if($ty < 250 && $illite == 0 && $glauconite > 0 && ($chlorite+$glauconite) > 4 && ($chlorite+$glauconite) <= 6)
            {
                $data0['name'] = 'Zeolites Control';
                $data0['data'] = $zeo;
                $additive1['additive']->push($data0);

                $data1['name'] = 'Fines Stabilizer';
                $data1['data'] = $fst;
                $additive1['additive']->push($data1);

                $data2['name'] = 'Corrosion Inhibitor';
                $data2['data'] = $cin;
                $additive1['additive']->push($data2);

                $additive1['tittle'] =  '4.5% HCl + 0.5% HF';
                $additive1['score'] =  60;

                $data->push($additive1);
            //1
            }elseif($glauconite > 0 && ($chlorite+$glauconite) >= 6 && ($chlorite+$glauconite) <= 8 && $ty < 250 && $illite == 0)
            {

                $data1['name'] = 'Fines Stabilizer';
                $data1['data'] = $fst;
                $additive1['additive']->push($data1);

                $data2['name'] = 'Corrosion Inhibitor';
                $data2['data'] = $cin;
                $additive1['additive']->push($data2);

                $additive1['tittle'] =  '4.5% HCl + 0.5% HF + 5% Acetic Acid';
                $additive1['score'] =  60;

                $data->push($additive1);
            //2
            }elseif($ty <= 300 && $ty >= 225 && $chlorite > 5)
            {
                $data1['name'] = 'Fines Stabilizer';
                $data1['data'] = $fst;
                $additive1['additive']->push($data1);

                $data2['name'] = 'Corrosion Inhibitor';
                $data2['data'] = $cin;
                $additive1['additive']->push($data2);

                $additive1['tittle'] =  '10% Acetic Acid + 0.5% HF';
                $additive1['score'] =  60;

                $data->push($additive1);
            //3
            }elseif($ty < 250 && $illite == 0 && ($ankerite+$siderite) > 5 || ($calcite+$dolomite) > 5)
            {
                if(($ankerite+$siderite) > 5){
                    $dataIron['name'] = 'Iron Control';
                    $dataIron['data'] = $cfe;
                    $additive1['additive']->push($dataIron);
                }

                $data0['name'] = 'Zeolites Control';
                $data0['data'] = $zeo;
                $additive1['additive']->push($data0);

                $data1['name'] = 'Fines Stabilizer';
                $data1['data'] = $fst;
                $additive1['additive']->push($data1);

                $data2['name'] = 'Corrosion Inhibitor';
                $data2['data'] = $cin;
                $additive1['additive']->push($data2);

                $additive1['tittle'] =  '15% HCl';
                $additive1['score'] =  72;

                $data->push($additive1);

            //4                          
            }elseif($ty < 250 && $illite == 0 && $chlorite < 5 && ($clays > 10 && $feldspars > 15 && $k > 100) || ($clays < 5 && $k > 25 && $k <= 100) || ($clays > 10 && $feldspars > 10 && $k > 25 && $k <= 100) && ($k >= 1 && $k <= 25 && $feldspars > 10))
            {
                $data0['name'] = 'Zeolites Control';
                $data0['data'] = $zeo;
                $additive1['additive']->push($data0);

                $data1['name'] = 'Fines Stabilizer';
                $data1['data'] = $fst;
                $additive1['additive']->push($data1);

                $data2['name'] = 'Corrosion Inhibitor';
                $data2['data'] = $cin;
                $additive1['additive']->push($data2);

                $additive1['tittle'] =  '9% HCl + 1% HF';
                $additive1['score'] =  85;

                $data->push($additive1);
            //5
            }elseif($ty < 250 && $illite == 0 && $chlorite < 5 && $k > 100 && $clays >= 5 && $clays <= 10 && $feldspars <= 10)
            {
                $data0['name'] = 'Zeolites Control';
                $data0['data'] = $zeo;
                $additive1['additive']->push($data0);

                $data1['name'] = 'Fines Stabilizer';
                $data1['data'] = $fst;
                $additive1['additive']->push($data1);

                $data2['name'] = 'Corrosion Inhibitor';
                $data2['data'] = $cin;
                $additive1['additive']->push($data2);

                $additive1['tittle'] =  '7.5% HCl + 1.5% HF';
                $additive1['score'] =  85;

                $data->push($additive1);
            //6
            }elseif($ty < 250 && $illite == 0 && $chlorite < 5 && $k > 100 && $quartz >= 80 && $clays < 5)
            {
                $data0['name'] = 'Zeolites Control';
                $data0['data'] = $zeo;
                $additive1['additive']->push($data0);

                $data1['name'] = 'Fines Stabilizer';
                $data1['data'] = $fst;
                $additive1['additive']->push($data1);

                $data2['name'] = 'Corrosion Inhibitor';
                $data2['data'] = $cin;
                $additive1['additive']->push($data2);

                $additive1['tittle'] =  '12% HCl + 3% HF';
                $additive1['score'] =  81;

                $data->push($additive1);
            //7                          
            }elseif($ty < 250 && $illite == 0 && $chlorite < 5 && $k > 100 && $clays > 10)
            {
                $data0['name'] = 'Zeolites Control';
                $data0['data'] = $zeo;
                $additive1['additive']->push($data0);

                $data1['name'] = 'Fines Stabilizer';
                $data1['data'] = $fst;
                $additive1['additive']->push($data1);

                $data2['name'] = 'Corrosion Inhibitor';
                $data2['data'] = $cin;
                $additive1['additive']->push($data2);

                $additive1['tittle'] =  '6.5% HCl + 1% HF';
                $additive1['score'] =  77;

                $data->push($additive1);
             //8                         
            }elseif($ty < 250 && $illite == 0 && $chlorite < 5 && $k > 100 && $feldspars >= 15)
            {
                $data0['name'] = 'Zeolites Control';
                $data0['data'] = $zeo;
                $additive1['additive']->push($data0);

                $data1['name'] = 'Fines Stabilizer';
                $data1['data'] = $fst;
                $additive1['additive']->push($data1);

                $data2['name'] = 'Corrosion Inhibitor';
                $data2['data'] = $cin;
                $additive1['additive']->push($data2);

                $additive1['tittle'] =  '13.5% HCl + 1.5% HF';
                $additive1['score'] =  77;

                $data->push($additive1);
            //9                          
            }elseif($ty < 250 && $illite == 0 && $chlorite < 5 && ($clays >= 5 && $clays <= 10 && $k > 25 && $k <= 100) || ($k > 25 && $k <= 100 && $feldspars > 10 && $carbonates < 10))
            {
                $data0['name'] = 'Zeolites Control';
                $data0['data'] = $zeo;
                $additive1['additive']->push($data0);

                $data1['name'] = 'Fines Stabilizer';
                $data1['data'] = $fst;
                $additive1['additive']->push($data1);

                $data2['name'] = 'Corrosion Inhibitor';
                $data2['data'] = $cin;
                $additive1['additive']->push($data2);

                $additive1['tittle'] =  '6% HCl + 1.5% HF';
                $additive1['score'] =  91;

                $data->push($additive1);
            //10                          
            }elseif($ty < 250 && $illite == 0 && $chlorite < 5 && $k > 25 && $k <= 100 && $feldspars > 10)
            {
                $data0['name'] = 'Zeolites Control';
                $data0['data'] = $zeo;
                $additive1['additive']->push($data0);

                $data1['name'] = 'Fines Stabilizer';
                $data1['data'] = $fst;
                $additive1['additive']->push($data1);

                $data2['name'] = 'Corrosion Inhibitor';
                $data2['data'] = $cin;
                $additive1['additive']->push($data2);

                $additive1['tittle'] =  '12% HCl + 1.5% HF';
                $additive1['score'] =  85;

                $data->push($additive1);
            //11                          
            }elseif($ty < 250 && $illite == 0 && $chlorite < 5 && $k >= 1 && $k <= 25 && $clays > 8)
            {
                $data0['name'] = 'Zeolites Control';
                $data0['data'] = $zeo;
                $additive1['additive']->push($data0);

                $data1['name'] = 'Fines Stabilizer';
                $data1['data'] = $fst;
                $additive1['additive']->push($data1);

                $data2['name'] = 'Corrosion Inhibitor';
                $data2['data'] = $cin;
                $additive1['additive']->push($data2);

                $additive1['tittle'] =  '3% HCl + 0.5% HF';
                $additive1['score'] =  77;

                $data->push($additive1);
            }

        }

        //% Organic Acids (Table 24,25)
        //% 5% Formic Acid + HCl (Table 24)
        if(($analcime > 0 && $ty > 107 && $ty < 158) || ($chlorite > 0 && $ty > 146 && $ty < 200) || ($illite > 0 && $ty > 163 && $ty < 213) || ($emectite > 0 && $ty > 181 && $ty < 230) || ($kaolinite > 0 && $ty > 196 && $ty < 250))
        {
            if($ty >= 230 && $ty < 275 && $illite == 0 && $zeolites <= 3)
            {

                $datas2['name'] = 'Fines Stabilizer';
                $datas2['data'] = $fst;
                $additive2['additive']->push($datas2);

                $additive2['tittle'] =  '5% Formic Acid + 7.5% HCl';
                $additive2['score'] =  64;

              //  $data->push($additive2);

            }elseif($ty >= 220 && $ty < 230 && $illite == 0 && $zeolites <= 3)
            {
                $datas2['name'] = 'Fines Stabilizer';
                $datas2['data'] = $fst;
                $additive2['additive']->push($datas2);

                $additive2['tittle'] =  '5% Formic Acid + 15% HCl';
                $additive2['score'] =  64;

             //   $data->push($additive2);

            }elseif($ty >= 150 && $ty < 220 && $illite == 0 && $zeolites <= 3)
            {
                $datas2['name'] = 'Fines Stabilizer';
                $datas2['data'] = $fst;
                $additive2['additive']->push($datas2);

                $additive2['tittle'] =  '5% Formic Acid + 28% HCl';
                $additive2['score'] =  64;

              //  $data->push($additive2);
            }

            ////% 5% Formic Acid + 0.5% HF (Table 24)
            if($ty > 150 && $ty < 300)
            {
                $datas3['name'] = 'Fines Stabilizer';
                $datas3['data'] = $fst;
                $additive3['additive']->push($datas3);

                $additive3['tittle'] =  '5% Formic Acid + 0.5% HF + 5% NH4Cl';
                $additive3['score'] =  55;

               // $data->push($additive3);
            }

            ///% 12% Acetic Acid + 1% HF (Table 25)//
            if($ty > 225 && $ty < 300)
            {
                if($clays >= 5)
                {
                    $datas4a['name'] = $NH4Cl . ' % NH4Cl';
                    $additive4['additive']->push($datas4a);
                }

                $datas4['name'] = 'Corrosion Inhibitor';
                $datas4['data'] = $cin;
                $additive4['additive']->push($datas4);

                $additive4['tittle'] =  '12% Acetic Acid + 1% HF';
                $additive4['score'] =  72;

               // $data->push($additive4);
            }


            if($ty > 0)
            {
                $datas5['name'] = 'Corrosion Inhibitor';
                $datas5['data'] = $cin;
                $additive5['additive']->push($datas5);

                $additive5['tittle'] =  '10% Citric Acid + 0.5% HF';
                $additive5['score'] =  81;

               // $data->push($additive5);
            }

        }

        ///% Buffer-regulated hydrofluoric acid systems (Table 26-27)
        ////% Acetic, Formic or Citric + Ammonium Salt (Table 26)


        if($ty < 129)
        {
            $datas6['name'] = 'Corrosion Inhibitor';
            $datas6['data'] = $cin;
            $additive6['additive']->push($datas6);

            $additive6['tittle'] =  'Formic, Acetic or Citric Acid/ + Ammonium, Acetate or Citrate Formate + Ammonium Fluoride';
            $additive6['score'] =  42;


            $data->push($additive6);
        }elseif($ty >= 129 && $ty <= 360)
        {
            if($clays >= 5)
            {
                $datas6['name'] = 'Ammonium Fluoride';
                $additive6['additive']->push($datas6);
                $datas6a['name'] = 'Fines Stabilizer';
                $datas6a['data'] = $fst;
                $additive6['additive']->push($datas6a);
            }

            $additive6['tittle'] =  'Formic, Acetic or Citric Acid/ + Ammonium, Acetate or Citrate Formate';
            $additive6['score'] =  42;

            $data->push($additive6);

        }elseif($ty >= 360 && $ty <= 550)
        {
            $datas6['name'] = '';
            $additive6['additive']->push($datas6);
            $additive6['tittle'] =  'Formic, Acetic or Citric Acid + Ammonium Hydroxide';
            $additive6['score'] =  42;

            $data->push($additive6);
        }

        //% Phosphoric Acid + Ammonium Phosphate Salt (Table 27)
        if($ty > 165)
        {
            $datas7['name'] = 'Fines Stabilizer';
            $datas7['data'] = $fst;
            $additive7['additive']->push($datas7);

            if($ty > 165)
            {
                $datas7a['name'] = 'Residence time: less than 4 hours';
                $additive7['additive']->push($datas7a);
            }

            $additive7['tittle'] =  'Phosphoric Acid + Ammonium Phosphate Salt';
            $additive7['score'] =  42;

            $data->push($additive7);
        }

        //% Delayed Acid. (Table 28-29)
        //% Fluoboric Acid (Table 28)

        if($ty < 200)
        {
            $datas8['name'] = 'Fines Stabilizer';
            $datas8['data'] = $fst;
            $additive8['additive']->push($datas8);

            $additive8['tittle'] =  'Phosphoric Acid + Ammonium Phosphate Salt';
            $additive8['score'] =  64;

            $data->push($additive8);
        }

        //% Sequential Mud Acid (SHF) (Table 28)
        if($ty > 120 && $ty < 250)
        {

            $datas9['name'] = 'Fines Stabilizer';
            $datas9['data'] = $fst;
            $additive9['additive']->push($datas9);

            if($clays > 5)
            {
                $datas9a['name'] = 'Clay Stabilizer';
                $datas9a['data'] = $fst;
                $additive9['additive']->push($datas9a);
            }

            $additive9['tittle'] =  'Sequential Mud Acid (SHF)';
            $additive9['score'] =  42;

            $data->push($additive9);
        }

        //% Self-generating mud acid systems (SGMA)(Table 29)
        if($ty >= 130 && $ty < 180)
        {
            $datas10['name'] = 'Methyl Formate + Ammonium Fluoride';
            $additive10['additive']->push($datas10);
            $datas10a['name'] = 'Corrosion Inhibitor';
            $datas10a['data'] = $cin;
            $additive10['additive']->push($datas10a);

            if($clays > 5)
            {
                $datas10b['name'] = 'Fines Stabilizer';
                $datas10b['data'] = $fst;
                $additive10['additive']->push($datas10b);
            }

            $additive10['tittle'] =  'Self-generating mud acid systems (SGMA)';
            $additive10['score'] =  42;

            $data->push($additive10);

        }elseif($ty >= 180 && $ty <= 215)
        {
            $datas10['name'] = 'Monochloroacetic Acid (MCAA) + Ammonium Salt';
            $additive10['additive']->push($datas10);
            $datas10a['name'] = 'Corrosion Inhibitor';
            $datas10a['data'] = $cin;
            $additive10['additive']->push($datas10a);

            if($clays > 5)
            {
                $datas10b['name'] = 'Fines Stabilizer';
                $datas10b['data'] = $fst;
                $additive10['additive']->push($datas10b);
            }

            $additive10['tittle'] =  'Self-generating mud acid systems (SGMA)';
            $additive10['score'] =  42;

            $data->push($additive10);

        }elseif($ty >= 190 && $ty <= 250)
        {
            $datas10['name'] = 'Methyl Acetate';
            $additive10['additive']->push($datas10);
            $datas10a['name'] = 'Corrosion Inhibitor';
            $datas10a['data'] = $cin;
            $additive10['additive']->push($datas10a);

            if($clays > 5)
            {
                $datas10b['name'] = 'Fines Stabilizer';
                $datas10b['data'] = $fst;
                $additive10['additive']->push($datas10b);
            }

            $additive10['tittle'] =  'Self-generating mud acid systems (SGMA)';
            $additive10['score'] =  42;

            $data->push($additive10);
        }

        //% Chelant (Table 30)
        //% APCA + (1.25%-1%)HF
        if($ty > 250 && $ty <= 360)
        {
            $datas11['name'] = '';
            $additive11['additive']->push($datas11);
            $additive11['tittle'] =  '(1%-1.25%)HF + APCA (aminopolycarboxylic)';
            $additive11['score'] =  81;

           $data->push($additive11);
        }


        return $data;

    } 


    public function preflush($ankerite, $carbonates, $chlorite, $clays, $feldspars, $siderite, $zeolites, $anhydrite, $baryte, $brucite, $calcite, $cast, $celestine, $dolomite, $finesna, $glauconite, $halite, $hematite, $illite, $isl, $k, $magnetite, $melanterite, $quartz, $troilite, $tvd, $ty, $fst, $cin, $NH4Cl)
    {
        //dd($k);
        $result = collect();
        $preflushHcl['additive'] = collect();
        $preflushNH4Cl['additive'] = collect();

        if($carbonates >= 5 || $feldspars >= 10 || ($clays+$glauconite) > 0 || 
            ($ankerite+$siderite) >= 5 || $zeolites >= 0 || $isl > 0 )
        {
            /////% Special Conditions////
            if($zeolites > 0 || $glauconite > 0 && ($chlorite+$glauconite) > 6 || $chlorite >= 5 && $ty >= 200)
            {
                $data1['name'] = 'Fines Stabilizer';
                $data1['data'] = $fst;
                $preflushHcl['additive']->push($data1);
                $data2['name'] = 'Corrosion Inhibitor';
                $data2['data'] = $cin;
                $preflushHcl['additive']->push($data2);
                

                if($clays < 5 && $zeolites > 3 && $ty >= 200 && $ty <= 350)
                {
                    $data1['name'] = '9% Formic Acid + 5% NH4Cl';
                    $preflushHcl['additive']->push($data1);
                    $data2['name'] = 'Fines Stabilizer';
                    $data2['data'] = $fst;
                    $preflushHcl['additive']->push($data2);
                }

                $preflushHcl['tittle'] = '10% Acetic Acid + 5% NH4Cl';
                $preflushHcl['score'] = 94;

            }elseif($glauconite > 0 && ($chlorite+$glauconite) > 4 && ($chlorite+$glauconite) <= 6 && $ty >= 200 && $ty <= 250){

                $dat1['name'] = 'Fines Stabilizer';
                $data1['data'] = $fst;
                $preflushHcl['additive']->push($dat1);

                $data2['name'] = 'Corrosion Inhibitor';
                $data2['data'] = $cin;
                $preflushHcl['additive']->push($data2);

                $preflushHcl['tittle'] = '5% HCl + 5% Acetic Acid';
                $preflushHcl['score'] = 94;

            }elseif(($ankerite+$siderite) >= 5 && $ankerite >0 && $ty >= 200){

                $data1['name'] = 'Fines Stabilizer';
                $data1['data'] = $fst;
                $preflushHcl['additive']->push($data1);

                $data2['name'] = 'Corrosion Inhibitor';
                $data2['data'] = $cin;
                $preflushHcl['additive']->push($data2);

                $preflushHcl['tittle'] = '3% Acetic Acid + 5% NH4Cl';
                $preflushHcl['score'] = 94;

            }elseif(($calcite+$dolomite) >= 5){

                $data1['name'] = 'Fines Stabilizer';
                $data1['data'] = $fst;
                $preflushHcl['additive']->push($data1);

                $preflushHcl['tittle'] = '5% NH4Cl';
                $preflushHcl['score'] = 94;

            }elseif(($ty < 250 && ($illite+$zeolites) == 0 && $chlorite < 5) &&
                ($clays > 10 && $feldspars > 15 && $k >=100) || 
                ($clays >= 5 && $clays <= 10 && $feldspars <= 10 && k >= 100) ||
                ($clays > 0 && $clays <= 10 && $k > 25 && $k <=100) ||
                ($clays > 10 && $feldspars > 10 && $k > 25 && $k <= 100) ||
                ($feldspars > 10 && $k >= 1 && $k <= 25))
            {
                $data1['name'] = 'Fines Stabilizer';
                $data1['data'] = $fst;
                $preflushHcl['additive']->push($data1);

                $data2['name'] = 'Corrosion Inhibitor';
                $data2['data'] = $cin;
                $preflushHcl['additive']->push($data2);

                $preflushHcl['tittle'] = '10% HCl';
                $preflushHcl['score'] = 100;

            }elseif(($ty < 250 && ($illite+$zeolites) == 0 && $chlorite < 5) &&
                ($quartz >= 80 && $clays < 5 && $clays > 0 && $k >= 100) ||
                ($feldspars >= 15 && $k >= 100))
            {
                $data1['name'] = 'Fines Stabilizer';
                $data1['data'] = $fst;
                $preflushHcl['additive']->push($data1);

                $data2['name'] = 'Corrosion Inhibitor';
                $data2['data'] = $cin;
                $preflushHcl['additive']->push($data2);

                $preflushHcl['tittle'] = '15% HCl';
                $preflushHcl['score'] = 100;
            }elseif(($ty < 250 && ($illite+$zeolites) == 0 && $chlorite < 5 ) &&
                ($clays > 10 && $k >= 100))
            {
                $data1['name'] = 'Fines Stabilizer';
                $data1['data'] = $fst;
                $preflushHcl['additive']->push($data1);

                $data2['name'] = 'Corrosion Inhibitor';
                $data2['data'] = $cin;
                $preflushHcl['additive']->push($data2);

                $preflushHcl['tittle'] = '5%-10% HCl';
                $preflushHcl['score'] = 100;
            }elseif(($ty < 250 && ($illite+$zeolites) == 0 && $chlorite < 5) &&
                ($feldspars >= 10 && $k > 25 && $k <= 100))
            {
                $data1['name'] = 'Fines Stabilizer';
                $data1['data'] = $fst;
                $preflushHcl['additive']->push($data1);

                $data2['name'] = 'Corrosion Inhibitor';
                $data2['data'] = $cin;
                $preflushHcl['additive']->push($data2);

                $preflushHcl['tittle'] = '10%-15% HCl';
                $preflushHcl['score'] = 100;

            }elseif(($ty < 250 && ($illite+$zeolites) == 0 && $chlorite < 5) &&
                ($clays < 5 && $carbonates <= 10 && $k >= 1 && $k <= 25) ||
                ($clays > 8 && $k >= 1 && $k <= 25))
            {
                $data1['name'] = 'Fines Stabilizer';
                $data1['data'] = $fst;
                $preflushHcl['additive']->push($data1);

                $data2['name'] = 'Corrosion Inhibitor';
                $data2['data'] = $cin;
                $preflushHcl['additive']->push($data2);

                $preflushHcl['tittle'] = '5% HCl';
                $preflushHcl['score'] = 100;
            }

        }elseif($isl > 0){
            if($ty < 350){
                $data['name'] = 'EDTA';
                $preflushHcl['additive']->push($data);

                if(($anhydrite+$halite+$baryte+$brucite+$cast+$hematite+$magnetite+$siderite+$troilite+$celestine) > 5)
                {
                    $data1['name'] = 'Na4EDTA';
                    $preflushHcl['additive']->push($data1);
                }


                if(($calcite+$melanterite) > 5)
                {
                    $data2['name'] = 'Na2H2EDTA';
                    $preflushHcl['additive']->push($data2);
                }

                $preflushHcl['tittle'] = '15% HCl';
                $preflushHcl['score'] = 100;
            }

            if($ty < 600)
            {   
                $data['name'] = '% NH4Cl';
                $preflushNH4Cl['additive']->push();
                if(($anhydrite+$halite+$baryte+$brucite+$cast+$hematite+$magnetite+$siderite+$troilite+$celestine) > 5 || ($calcite+$melanterite) > 5 || 
                    $isl > 0)
                {
                    $data1['name'] = 'Scale Inhibitor';
                    $preflushNH4Cl['additive']->push($data1);

                    $data2['name'] = 'Corrosion Inhibitor';
                    $data2['date'] = $cin;
                    $preflushNH4Cl['additive']->push($data1);
                }

                if(($illite+$emectite) > 0){

                    $dato3['date'] = collect('0.5% Aluminum Hydroxide or 0.5% zirconium oxychloride');
                    $data3['name'] = 'Swelling inhibitor';
                    $preflushNH4Cl['additive']->push($data3);
                }

                $preflushNH4Cl['tittle'] =  '%d,'.$NH4Cl;
                $preflushNH4Cl['score'] = 100;
            }
            $result->push($preflushNH4Cl);
        }

        return $result->push($preflushHcl);
    }

    public function preControl($ironM, $cfe){
        $result = collect();
        $ironPreControl['additive'] = collect();
        if($ironM >= 5)
        {
            $data['name'] = 'Iron Control';
            $data['data'] = $cfe;
            $ironPreControl['additive']->push($data);
        }
        $ironPreControl['tittle'] = '';
        $ironPreControl['score'] = '';
        return $result->push($ironPreControl);

    }

    public function WaterFormation($isl, $calcite, $melanterite, $anhydrite, $halite, $baryte, $brucite, $cast, $hematite, $magnetite, $siderite, $troilite, $celestine, $ty, $illite, $emectite, $cin, $NH4Cl)
    {
        $result = collect([]);
        $WaterFormationHcl['additive'] = collect([]);
        $WaterFormationNH4Cl['additive'] = collect([]);

        $edtaWaterFormation['name'] = 'EDTA';
        $WaterFormationHcl['additive']->push($edtaWaterFormation);

        if($isl > 0 || ($calcite+$melanterite) > 5 || ($anhydrite+$halite+$baryte+$brucite+$cast+$hematite+$magnetite+$siderite+$troilite+$celestine) > 5 )
        {

            ////% 15% HCl + EDTA
            if($ty < 350)
            {
                if(($anhydrite+$halite+$baryte+$brucite+$cast+$hematite+$magnetite+$siderite+$troilite+$celestine) > 5)
                {
                    $Na4EDTAWaterFormation['name'] = 'Na4EDTA';
                    $WaterFormationHcl['additive']->push($Na4EDTAWaterFormation);
                }

                if(($calcite+$melanterite) > 5)
                {
                    $Na2H2EDTAWaterFormation['name'] = 'Na2H2EDTA';
                    $WaterFormationHcl['additive']->push($Na2H2EDTAWaterFormation);
                }

                $WaterFormationHcl['tittle'] = '15% HCl';
                $WaterFormationHcl['score'] = 100;
                $result->push($WaterFormationHcl);
                //$result1 = "<span class='title'>- 15% HCl</span><br><span class='categoria'>+ EDTA</span><br>".$a.''.$b."<span>Score: 100%</span>";
            }

            ///ty mayor a 600//////
            if($ty < 600)
            {
                if(($anhydrite+$halite+$baryte+$brucite+$cast+$hematite+$magnetite+$siderite+$troilite+$celestine) > 5 || ($calcite+$melanterite) > 5 || $isl > 0)
                {
                    $data1['name'] = 'Scale Inhibitor';
                    $WaterFormationNH4Cl['additive']->push($data1);
                    $data2['name'] = 'Corrosion Inhibitor';
                    $data2['data'] = $cin;
                    $WaterFormationNH4Cl['additive']->push($data2);
                }

                if(($illite+$emectite) > 0)
                {   

                    $subdata = collect('0.5% Aluminum Hydroxide or 0.5% zirconium oxychloride'); 
                    $data3['data'] = collect($subdata);
                    $data3['name'] = 'Swelling inhibitor';
                    $WaterFormationNH4Cl['additive']->push($data3);
                }

                $WaterFormationNH4Cl['tittle'] = $NH4Cl."% NH4CL";
                $WaterFormationNH4Cl['score'] = 100;
                $result->push($WaterFormationNH4Cl);
                //$result2 = "<span class='title'>- ".$NH4Cl."% NH4CL</span><br>".$a.''.$b."<span>Score: 100%</span>";
            }

            return $result;
        }
    }

    public function solvent($em, $wet, $tc, $ty, $iic)
    {
        $result = collect([]);
        $xylene['additive'] = collect([]);
        if($em == 1 || $wet == 2 || $wet == 3 || $tc <= $ty || $iic >= 0.9)
        {
            if($tc <= $ty || $iic >= 0.9){
                $dieselSolvent['name'] = 'Diesel';
                $xylene['additive']->push($dieselSolvent);
                $mutualSolvent['name'] = 'Mutual Solvent or alcohol';
                $xylene['additive']->push($mutualSolvent);
                $surfactantSolvent['name'] = 'Surfactant';
                $xylene['additive']->push($surfactantSolvent);
                $dispersantSolvent['name'] = 'Dispersant';
                $xylene['additive']->push($dispersantSolvent);
            }elseif ($em == 1 || $wet == 2 || $wet == 3){
                $dieselSolvent['name'] = 'Diesel or Varsol';
                $xylene['additive']->push($dieselSolvent);
                $mutualSolvent['name'] = 'Mutual Solvent';
                $xylene['additive']->push($mutualSolvent);
                $surfactantSolvent['name'] = 'Surfactant';
                $xylene['additive']->push($surfactantSolvent);
            }
            $xylene['tittle'] = 'Xylene';
            $xylene['score'] = 100;

           // return " <span class='title'>- Xylene </span><br>".$a.'<span>Score: 100%</span>'; 
        }else{
            $xylene['tittle'] = 'Diesel';
            $xylene['score'] = 100;
            //return "<span class='title'>- Diesel </span><br> <span>Score: 100%</span>"; 
        }
        return $result->push($xylene);
    }

    public function pickling($ty, $tc,$iic,$hematite, $magnetite,$clays,$pyrite,$siderite,$pyrrhotite,$zeolites, $chamosite,$h2s, $cin, $cfe)
    {
        $result = collect([]);
        $hcl['additive'] = collect([]);
        $nacl['additive'] = collect([]);
        $nearNeutral['additive'] = collect([]);
        $aceticAcid['additive'] = collect([]);

        ////////7.5% HCl result1/////////////
        if ($ty <= 300)
        {       
            $corrosionInhibitorHcl['data'] = $cin; 
            $corrosionInhibitorHcl['name'] = 'Corrosion Inhibitor'; 
            $hcl['additive']->push($corrosionInhibitorHcl);

            $ironControlHcl['data'] = $cfe;
            $ironControlHcl['name'] = 'Iron Control';
            $hcl['additive']->push($ironControlHcl);
            //h2s
            if($h2s >= 5)
            {
                //$infoH2s = '<span class="categoria"> + Sulfide Inhibitor </span><br>';
                $sulfideInhibitorHcl['name'] = 'Sulfide Inhibitor';
                $hcl['additive']->push($sulfideInhibitorHcl);
            }

             //tc y ty
            if ($tc <= $ty && $tc <> 0 ||$iic >= 0.9 && $iic <> 0)
            {
                $aromaticSolventHcl['name'] = 'Aromatic Solvent';
                $hcl['additive']->push($aromaticSolventHcl);
                //$infoTcTy = '<span class="categoria">+ Aromatic Solvent </span><br>';
            }

            

            $hcl['tittle'] = '7.5% HCl';
            $hcl['score'] =  100;

            $result->push($hcl);


           //$hcl = "<span class='title'>- 7.5% HCl</span><br> <span class='categoria'>+ Corrosion Inhibitor</span> <br>".$cin.'<span class="categoria">+ Iron Control </span><br>'.$cfe.''.$infoH2s.''.$infoTcTy.'<span>Score: 100% </span>';
        }


        ////////% 10% Fluoboric Acid + 15% NaCl  result2/////////////
        if ($ty < 175)
        {
            $data['name'] = '15% NaCl';
            $nacl['additive']->push($data);

            if ($h2s >= 5)
                {   $data['name'] = 'Sulfide Inhibitor';
            $nacl['additive']->push($data);
        }

             //tc y ty
        if ($tc <= $ty && $tc <> 0 ||$iic >= 0.9 && $iic <> 0)
        {
            $data['name'] = 'Aromatic Solvent';
            $nacl['additive']->push($data);
        }

        $nacl['tittle'] = '10% Fluoboric Acid';
        $nacl['score'] = 100;

        $result->push($nacl);
           // $result2 = "<span class='title'>-  </span><br><span class='categoria'> + </span><br>".$infoH2s.''.$infoTcTy.'<span>Score: 100%</span>';

    }


        ////////% 12% Near Neutral//////////////////

    if ($ty > 70 && $ty < 140)
    {

        if ($tc <= $ty || $iic >= 0.9)
        {
            $data['name'] = 'Aromatic Solvent';
            $nearNeutral['additive']->push($data);
        }
        $nearNeutral['additive'] = '12% Near Neutral';
        $nearNeutral['score'] = 100;

        $result->push($nearNeutral);
            //$result3 = " <span class='title'>-  </span><br>".$infoTcTy.'<span>Score: 100%</span>';
    }

         ////////% 5% Acetic Acid//////////////////

    if($ty < 400)
    {
        if($tc <= $ty || $iic >= 0.9)
        {
            $data['name'] = 'Aromatic Solvent';
            $aceticAcid['additive']->push($data);
        }

        if($h2s >= 5)
        {
            $data['name'] = 'Sulfide Inhibitor';
            $aceticAcid['additive']->push($data);
        }

        $data['name'] = 'Corrosion Inhibitor';
        $data['data'] = $cin;
        $aceticAcid['additive']->push($data);

        $aceticAcid['tittle'] = '5% Acetic Acid';
        $aceticAcid['score'] = 100;


        $result->push($aceticAcid);

            //$result4 = " <span class='title'>- 5% Acetic Acid </span><br>".$infoTcTy.''.$infoH2s.'<span class="categoria">+ Corrosion Inhibitor</span><br>'.$cin.'<span>Score: 100%</span>';            
    }


    return $result;
}



public function CIN($ty)
{
    $cin = collect([]);
    if($ty <= 200)
    {
        $cin->push('0.1%-1% Corrosion Inhibitor');
    }else if($ty > 200){
        $cin->push('0.2%-3% Iodized Salt');
        $cin->push('0.5%-5% Formic Acid');
    }

    return $cin;
}


public function CFe($hematite, $magnetite, $clays,$ty,$pyrite,$siderite,$pyrrhotite,$zeolites, $chamosite)
{

    $cfe = collect([]);
    if(($hematite+$magnetite)< 5 || $chamosite > 5 && $ty > 225 &&  $ty < 300){
        $cfe->push('10% Acetic Acid');
        if ($clays>5)
        {
            $cfe->push('5% NH4Cl');
        }
    }
        ///////////////////////////////////

    if ($ty < 350){
        if (($pyrite+$siderite+$magnetite+$pyrrhotite)<1)
        {
            $cfe->push('15% HCl + Erythorbic Acid');
        }elseif(($hematite+$magnetite)<1){
            $cfe->push('15% HCl + Lactic Acid'); 
        }elseif(($hematite+$magnetite)>=5 && ($hematite+$magnetite)>=10){
            $cfe->push('15% HCl + NTA ');
        }elseif(($hematite+$magnetite)>1){
            $cfe->push('15% HCl + Na2EDTA ');
        }elseif($zeolites>2 || $clays>5){
            $cfe->push('15% HCl + Erythorbic Acid');
        }

    }

    if (($hematite+$magnetite)<1 && $zeolites <= 2 && $clays <= 5){
        $cfe->push('10% Citric Acid');
    }

    return $cfe; 
}


public function FSt($ty,$k,$tvd,$finesna)
{
    $fst = collect([]);
        //////% Hydrolyzable Metal Ions (Table 36)
    if($ty <= 300 && $finesna == 0)
    {
        $fst->push('0.5% Aluminum Hydroxide or 0.5% zirconium oxychloride');
    }

        ///////% Minerals Fines Stabilizer (MFS) (Table 36)
    if($ty <= 560 && $k >30)
    {
        $fst->push('0.25% Minerals Fines Stabilizer (MFS) + 2% NH4Cl');
        $fst->push('"Use only in completed wells"');
    }

        ////////% Organosilane(Table 37)////
    if($ty <= 150)
    {
        $fst->push('Organosilane');
        $fst->push('Apply spacer if use solvent previously');
    }

        ///////% Polymerization Process Adsorbed (SAP) (Table 38)///
    if($ty <= 200 && $k >= 1000 && k <= 5000 && $tvd <= 4000)
    {
        $fst->push('Organosilane');
    }

        ///////% Nanoparticules (Table 39)////
    if($ty <= 300)
    {
        $fst->push('Magnesium Oxide, Iron Oxide, Aluminum Oxide');
        $fst->push('"Do not use Ethanol"');

    }

        /////% Zeta Potential (Table 40)//
    if($ty <= 400)
    {
        $fst->push('2% KCl + ZPAS');
    }

    return $fst;
}


public function ZEO($zeolites)
{
    $zeo = collect([]);
    if($zeolites < 2)
    {
        $zeo->Push('5% Acetic Acid');
    }elseif($zeolites >= 2 && $zeolites <= 5){
        $zeo->Push('10% Acetic Acid');
    }

    return $zeo;
}

}
