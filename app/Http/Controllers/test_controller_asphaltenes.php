<?php

namespace App\Http\Controllers;
use \SplFixedArray;
use View;

class test_controller extends Controller
{
    /**
     * DDespliega la vista add_well con la información de cuencas para popular el select.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    }
    














































    #Diagnóstico asfaltenos
    public function index_2()
    {        
        #Datos de entrada
        #PVT
        $pressure = [1 => 147,294,441,588,735,882,1029,1176,1323,1470,1617,1764,1911,2058,2205,2352,2499,2646,2793,2940,3087,3234,3381,3528,3675,3822,3969,4116,4263,4410,4557,4704,4851,4998,5145,5292,5439,5586,5733,5880,6027,6174,6321,6468,6615,6762,6909,7056,7203,7350,7497,7644,7791,7938];
        $den = [1 => 0.771,0.771,0.771,0.771,0.771,0.771,0.771,0.771,0.771,0.771,0.771,0.771,0.771,0.771,0.771,0.771,0.771,0.771,0.771,0.771,0.771,0.771,0.771,0.771,0.771,0.771,0.771,0.771,0.771,0.771,0.771,0.771,0.771,0.771,0.771,0.771,0.771,0.771,0.771,0.771,0.771,0.771,0.771,0.771,0.771,0.771,0.771,0.771,0.771,0.771,0.771,0.771,0.771,0.771];
        $miu = [1 => 0.92,0.92,0.92,0.92,0.92,0.92,0.92,0.92,0.92,0.92,0.92,0.92,0.92,0.92,0.92,0.92,0.92,0.92,0.92,0.92,0.92,0.92,0.92,0.92,0.92,0.92,0.92,0.92,0.92,0.92,0.92,0.92,0.92,0.92,0.92,0.92,0.92,0.92,0.92,0.92,0.92,0.92,0.92,0.92,0.92,0.92,0.92,0.92,0.92,0.92,0.92,0.92,0.92,0.92];
        $nv = [1 => 0.99,0.99,0.99,0.99,0.99,0.99,0.99,0.99,0.99,0.99,0.99,0.99,0.99,0.99,0.99,0.99,0.99,0.99,0.99,0.99,0.99,0.99,0.99,0.99,0.99,0.99,0.99,0.99,0.99,0.99,0.99,0.99,0.99,0.99,0.99,0.99,0.99,0.99,0.99,0.99,0.99,0.99,0.99,0.99,0.99,0.99,0.99,0.99,0.99,0.99,0.99,0.99,0.99,0.99];
        $pvt_data = [$pressure, $den, $miu, $nv];

        #Históricos
        $date_raw = [1 => "15/01/2010","15/02/2010","15/03/2010","15/04/2010","15/05/2010","15/06/2010","15/07/2010","15/08/2010","15/09/2010","15/10/2010","15/11/2010","15/12/2010","15/01/2011","15/02/2011","15/03/2011","15/04/2011","15/05/2011","15/06/2011","15/07/2011","15/08/2011","15/09/2011","15/10/2011","15/11/2011","15/12/2011","15/01/2012","15/02/2012","15/03/2012","15/04/2012","15/05/2012","15/06/2012","15/07/2012","15/08/2012","15/09/2012","15/10/2012","15/11/2012","15/12/2012","15/01/2013","15/02/2013","15/03/2013","15/04/2013","15/05/2013","15/06/2013","15/07/2013","15/08/2013","15/09/2013","15/10/2013","15/11/2013","15/12/2013","15/01/2014","15/02/2014","15/03/2014","15/04/2014","15/05/2014","15/06/2014","15/07/2014","15/08/2014","15/09/2014","15/10/2014","15/11/2014","15/12/2014","15/01/2015","15/02/2015","15/03/2015","15/04/2015"];
        
        foreach ($date_raw as $key => $value) 
        {
            $date_split = explode("/", $value);
            $dates[$key] = $date_split[0]."-".$date_split[1]."-".$date_split[2];
        }

        $bopd = [1 => 839,110,874,961,957,946,932,922,867,1027,1012,1016,644,933,887,774,864,809,784,772,726,644,627,580,573,520,499,543,537,198,640,557,515,202,504,78,237,332,370,396,415,429,450,295,318,484,466,427,431,422,408,397,405,404,405,405,250,187,272,265,285,325,285,292];
        $wt_percentage = [1=>2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2];
        $historical_data = [$dates, $bopd, $wt_percentage];

        #Asfaltenos
        $asphaltenes_pressure = [1 => 147,294,441,588,735,882,1029,1176,1323,1470,1617,1764,1911,2058,2205,2352,2499,2646,2793,2940,3087,3234,3381,3528,3675,3822,3969,4116,4263,4410,4557,4704,4851,4998,5145,5292,5439,5586,5733,5880,6027,6174,6321,6468,6615,6762,6909,7056,7203,7350,7497,7644,7791,7938];
        $sol = [1 => 0.9585,0.95682,0.95514,0.95342,0.95163,0.94977,0.94783,0.94578,0.94363,0.94136,0.93896,0.93641,0.93372,0.93086,0.92782,0.92459,0.92114,0.91747,0.91354,0.90934,0.90485,0.90004,0.89487,0.88931,0.89185,0.89399,0.89606,0.89804,0.89995,0.9018,0.90357,0.90528,0.90693,0.90851,0.91005,0.91152,0.91295,0.91433,0.91566,0.91621,0.91818,0.91938,0.92054,0.92607,0.92694,0.92779,0.92862,0.92943,0.93022,0.93099,0.93174,0.93247,0.93009,0.93094];
        $asphaltenes_data = [$asphaltenes_pressure, $sol];

        $drainage_radius = 3160; #rdre
        $formation_height = 217; #hf
        $well_radius = 0.5; #rw
        $rock_fluid_compressibility = 0.000001355; #CR
        $reservoir_initial_pressure = 3800; #pini
        $reservoir_initial_porosity = 0.25; #phio
        $reservoir_initial_permeability = 100; #ko
        $pore_throat_diameter = 2.54; #dporo
        $asphaltene_particle_diameter = 0.1; #dpart
        $agregated_asphaltenes_density = 1.2; #rhop

        $simulation_results = $this->simulate_deposited_asphaltenes($drainage_radius, $formation_height, $well_radius, $rock_fluid_compressibility, $reservoir_initial_pressure, $reservoir_initial_porosity, $reservoir_initial_permeability, $pore_throat_diameter, $asphaltene_particle_diameter, $agregated_asphaltenes_density, $pvt_data, $historical_data, $asphaltenes_data);
        #return view::make('test_xxx');
    }

    function dateDifference($date_1 , $date_2 , $differenceFormat)
    {
        $datetime1 = date_create($date_1);
        $datetime2 = date_create($date_2);
        
        $interval = date_diff($datetime1, $datetime2);
        
        return $interval->format($differenceFormat);   
    }

    function interpolation($x, $n, $xt, $yt)
    {
        $y = 0;
        $aux_i = 1;
        if($x < $xt[1])
        {
            $extrapolation_result = $this->extrapolation($xt, $yt, 100, $x, $y);
            $y = $extrapolation_result[0];
        }
        if($x>$xt[$n])
        {
            $extrapolation_result = $this->extrapolation($xt, $yt, 100, $x, $y);
            $y = $extrapolation_result[0];
        }
        if($x < $xt[$n])
        {
            for ($i=2; $i <= $n ; $i++) 
            { 
                if(!($x >= $xt[$i]))
                {
                    $y = $yt[$i - 1] + ($x - $xt[$i-1]) * ($yt[$i] - $yt[$i - 1]) / ($xt[$i] - $xt[$i - 1]);
                    return $y;
                }
                $aux_i = $i;
            }
        }
        if($x == $xt[$aux_i])
        {
            $y = $yt[$aux_i];
        }

        return $y;
    }

    function extrapolation($xa, $ya, $n, $x)
    {
        $n_max = 10;
        $c = array_fill(1,10,0);
        $d = array_fill(1,10,0);
        $ns = 1;
        $dif = abs($x-$xa[1]);

        if($x>$xa[$n])
        {
            $y = $ya[$n];
            for ($i=1; $i <= $n_max; $i++) 
            { 
                $c[$i] = $ya[$n_max]; 
                $d[$i] = $ya[$n_max]; 
            }
        }
        else if($x < $xa[$n])
        {
            $y = $ya[1];
            for ($i=1; $i <= $n_max; $i++) 
            { 
                $c[$i] = $ya[$i];
                $d[$i] = $ya[$i];
            }
        }
        else
        {
            for ($i=1; $i <= $n_max; $i++) 
            { 
                $c[$i] = $ya[$i];
                $d[$i] = $ya[$i];
            }
        }

        $ns = $ns - 1;
        for ($m=1; $m < $n_max-1; $m++) 
        { 
            for ($i=1; $i < $n_max - $m; $i++) 
            { 
                $ho = $xa[$i] - $x;
                $hp = $xa[$i + $m] - $x;
                $w = $c[$i + 1] - $d[$i];
                $den = $ho - $hp;
                if($den == 0)
                {
                    #Error
                    return -555;
                }
                $den = $w / $den;
                $d[$i] = $hp * $den;
                $c[$i] = $ho * $den;
            }
            if((2 * $ns) < ($n_max - $m))
            {
                $dy = $c[$ns + 1];
            }
            else
            {
                $dy = $d[$ns];
                $ns = $ns-1;
            }
            $y = $y + $dy;
        }

        return [$y, $dy];
    }

    function porosity_change($nr, $dpart, $dporo, $rhop, $rhof, $muo, $boi, $rw, $hf, $r1, $r, $dr, $phin, $co, $ea, $u, $dt)
    {

        $pi = 3.14159265359;
        $fcor = 0.8;
         
        $dps = array_fill(1,$nr,0); 
        $sigma = array_fill(1,$nr,0); 
        $vporo = array_fill(1,$nr,0); 
        $rrpd = array_fill(1,$nr,0); 
        $ent = array_fill(1,$nr,0); 
        $dgp = array_fill(1,$nr,0);

        for ($i=1; $i <= $nr ; $i++) 
        { 
            $vt = 5450 * pow($dpart, 2) * ($rhop - $rhof[$i]) / ($muo[$i]);
            if ($vt < 0)
            {
                $vt = 0;
            }
            
            #Depositación superficial
            if ($muo[$i] > 300)
            {
                $param1 = 0;
            }
            if ($muo[$i] > 100 and $muo[$i] <= 300)
            {
                $pm1 = 0.01;
            }
            if ($muo[$i] > 10 and $muo[$i] <= 100)
            {
                $pm1 = 1.5;
            }
            if ($muo[$i] > 1 and $muo[$i] <= 10)
            {
                $pm1 = 4.5;
            }
            if ($muo[$i] <= 1)
            {
                $pm1 = 10.5;
            }

            $dps[$i] = 0.01 * (0.00092903) * $pm1 * $u[$i] * $dpart * $co[$i] * $dr[$i];

            #volumen poroso
            if ($i == 1)
            {
                $vporo[$i] = $phin[$i] * $pi * $hf * (pow($r1[1], 2) - pow($rw, 2)) / (5.615 * $boi[$i]);
            }
            else
            {
                $vporo[$i] = $phin[$i] * $pi * $hf * (pow($r[$i], 2) - pow($r[$i - 1], 2)) / (5.615 * $boi[$i]);
            }

            $mtotal = $vporo[$i] * $rhof[$i] * 28316.8;
            
            #Relación de reducción porosidad [g/g]
            $rrpd[$i] = 2 * (0.00092903) * $dps[$i] * $dt / $mtotal;
            
            #Entranpamiento  y arrastre
            if ($dporo > 2.5)
            {
                $pm2 = 0;
            }
            if ($dporo > 1.5 and $dporo <= 2.5)
            {
                $pm2 = 3.5;
            }
            if ($dporo > 0.5 and $dporo <= 1.5)
            {
                $pm2 = 5.8;
            }
            if ($vt < $u[$i])
            {
                $ent[$i] = $pm2 * $ea[$i] * ($u[$i] - $vt);
            }
            else
            {
                $ent[$i] = 0;
            }
            
            #Depositación en la garganta deporo
            if ($dpart / $dporo > 0.33)
            {
                $dgp[$i] = (0.00092903) * 1.05 * $u[$i] * (1 - $fcor) * $co[$i];
            }
            else
            {
                $dgp[$i] = 0;
            }
            #Cambio de porosidad
        }
        for ($i=1; $i <= $nr ; $i++) 
        { 
            $sigma[$i] = $rrpd[$i] - $ent[$i] + $dgp[$i];
            $phic[$i] = $phin[$i] - $sigma[$i];
            $deadt[$i] = 0.001 * $dps[$i] * $dt;
        }

        return array($phic, $deadt);
    }

    function concentration_change($rw, $l, $hf, $nx, $phic, $uc, $rc, $dt, $con, $deadt, $dphi, $rl)
    {

        $radio = array_fill(0,100,0); 
        $vporo = array_fill(1,100,0); 
        $vc = array_fill(1,100,0); 
        $u = array_fill(1,100,0); 
        $beta = array_fill(1,100,0); 
        $gamma = array_fill(1,100,0); 
        $a1 = array_fill(1,100,0); 
        $a2 = array_fill(1,100,0); 
        $a3 = array_fill(1,100,0); 
        $d = array_fill(1,100,0); 
        $c = array_fill(1,100,0);
        $deadt_co = array_fill(1,100,0); 
        $dphi_co = array_fill(1,100,0); 
        $coc = array_fill(1,100,0); 
        $phi_co = array_fill(1,100,0);

        $dx = $l / 100;
        $radio[1] = $rw;
        for ($i=2; $i <= 100 ; $i++) 
        { 
            $radio[$i] = $radio[$i - 1] + $dx;
            $uci = $this->interpolation($radio[$i], $nx, $rc, $uc);
            $coi = $this->interpolation($radio[$i], $nx, $rc, $con);
            $deadti = $this->interpolation($radio[$i], $nx, $rc, $deadt);
            $dphii = $this->interpolation($radio[$i], $nx, $rc, $dphi);
            $phici = $this->interpolation($radio[$i], $nx, $rc, $phic);
            $rl[$i] = $radio[$i];
            $u[$i] = 0.0000092903 * $uci;
            $coc[$i] = $coi;
            $deadt_co[$i] = $deadti;
            $dphi_co[$i] = $dphii;
            $phi_co[$i] = $phici;
        }

        #Solución de la ecuación de concentración de particulas
        for ($m2=2; $m2 < 100 ; $m2++) 
        { 
            $a1[$m2] = -$u[$m2] * $dt / (2.0 * $dx);
            $a2[$m2] = $phi_co[$m2] + 0.0001 * $dt * $dphi_co[$m2] + ($u[$m2 + 1] - $u[$m2 - 1]) * $dt / (2.0 * $dx);
            $a3[$m2] = $u[$m2] * $dt / (2.0 * $dx);
            $d[$m2] = $phi_co[$m2] * $coc[$m2] - 0.0001 * $dt * ($deadt_co[$m2]);

        }
        

        $d[2] = $d[2] - $a1[2] * $coc[1];
        $a1[2] = 0;
        $a1[100] = -$u[100] * $dt / $dx;
        $a2[100] = $phi_co[100] + $dt * $dphi_co[100] + (2.0 * $u[100] - $u[99]) * $dt / $dx;
        $a3[100] = 0;
        $d[100] = $phi_co[100] * $coc[100] - 0.0001 * $dt * ($deadt_co[100]);
            
        #Aalgoritmo de thomas
        $beta[2] = $a2[2];
        $gamma[2] = $d[2] / $a2[2];
        
        for ($m2=3; $m2 <= 100 ; $m2++) 
        { 
            $beta[$m2] = $a2[$m2] - $a1[$m2] * $a3[$m2 - 1] / $beta[$m2 - 1];
            $gamma[$m2] = $d[$m2] / $beta[$m2] - $a1[$m2] * $gamma[$m2 - 1] / $beta[$m2];
        }
        $co[100] = $gamma[100];
        for ($m2=99; $m2 >= 2 ; $m2--) 
        { 
           $co[$m2] = $gamma[$m2] - $a3[$m2] * $co[$m2 + 1] / $beta[$m2];
        }
        $co[1] = $co[2];

        return array($co, $rl);
    }

    function simulate_deposited_asphaltenes($rdre, $hf, $rw, $cr, $pini, $phio, $ko, $dporo, $dpart, $rhop, $pvt_data, $historical_data, $asphaltenes_data)
    {
        set_time_limit(300);

        $complete_simulated_results = [];
        $complete_damage_results = [];
        
        $simulated_results = [];
        $damage_results = [];

        $pi = 3.14159265359;
        $x = 0;

        #Datos pvt
        $nv = count($pvt_data[0]);
        $ppvt = $pvt_data[0];
        $dopvt = $pvt_data[1];
        $uopvt = $pvt_data[2];
        $bopvt = $pvt_data[3];

        #Datos históricos
        $nh = count($historical_data[0]);
        $hist = $historical_data[0];
        $bopd = $historical_data[1];
        $wtasf = $historical_data[2];

        #Datos asfaltenos
        $ns = count($asphaltenes_data[0]);
        $pasf = $asphaltenes_data[0];
        $sasf = $asphaltenes_data[1];

        #Discretizando el medio (Geometría radial)
        $nr = 500;
        $ri = 0;
         
        $r = array_fill(1, $nr, 0); 
        $dr = array_fill(1, $nr, 0); 
        $r1 = array_fill(1, $nr, 0);
        $dr1 = array_fill(1, $nr - 1, 0);
        $alfa = pow(($rdre / $rw), (1 / ($nr - 1)));

        $r[1] = $rw;
        for ($i=2; $i <= $nr ; $i++) 
        { 
            $r[$i] = $alfa * $r[$i - 1];
        }

        for ($i=1; $i < $nr ; $i++) 
        { 
            $dr1[$i] = $r[$i + 1] - $r[$i];
        }

        for ($i=1; $i <= $nr ; $i++) 
        { 
            if ($i == $nr)
            {
                $r1[$i] = $rdre;
            }
            else
            {
                $r1[$i] = (($alfa - 1) * $r[$i]) / (log($alfa));
                if($r1[$i] < $ri)
                {
                    $x = $i;
                }
            }
        }
        for ($i=1; $i <= $nr ; $i++) 
        { 
            if($i == 1)
            {
                $dr[$i] = $r1[$i] - $r[$i];
            }
            else
            {
                $dr[$i] = $r1[$i] - $r1[$i - 1];
            }
        }

        #Inicializando varibles iniciales
        $pn = array_fill(1, $nr, $pini); 
        $phin = array_fill(1, $nr, $phio); 
        $kn = array_fill(1, $nr, $ko); 
        $co = array_fill(1, $nr, 0); 
        $ea = array_fill(1, $nr, 0);

        for ($i=1; $i <= $nr ; $i++) #Optimizar
        { 
            $rho = $this->interpolation($pini, $nv, $ppvt, $dopvt);
            $coi = $this->interpolation($pini, $ns, $pasf, $sasf);
            $co[$i] = $wtasf[1] * 10000 / $rho * $coi;
            $ea[$i] = 0;
        }

        #Dimensionamiento
        $b = array_fill(1, $nr, 0); 
        $a = array_fill(1, $nr, 0); 
        $f = array_fill(1, $nr, 0); 
        $c = array_fill(1, $nr, 0); 
        $d = array_fill(1, $nr, 0);
        $qq = array_fill(1, $nr, 0); 
        $gg = array_fill(1, $nr, 0); 
        $w = array_fill(1, $nr, 0); 
        $pcal = array_fill(1, $nr, 0);
        $dpre = array_fill(1, $nr, 0); 
        $u = array_fill(1, $nr, 0); 
        $phic = array_fill(1, $nr, 0); 
        $dphi = array_fill(1, $nr, 0); 
        $deadt = array_fill(1, $nr, 0);
        $muo = array_fill(1, $nr, 0); 
        $rhof = array_fill(1, $nr, 0); 
        $boi = array_fill(1, $nr, 0); 
        $kc = array_fill(1, $nr, 0);
        $cocal = array_fill(1,100,0);
        $rl = array_fill(1,100,0); 
        $coc = array_fill(1, $nr, 0);
        $tiempo = array_fill(1, $nh, 0);
        
        $n = 0.5;
        $dt = 10;
        $un = 3;

        #Delta de tiempo
        for ($i=1; $i <= $nh ; $i++) 
        { 
            if($i == 1)
            {
                $tiempo[$i] = 30;
            }
            else
            {
                $tiempo[$i] = floatval($this->dateDifference($hist[$i], $hist[$i - 1], "%a"));
            }
        }

        for ($kk=1; $kk <= $nh ; $kk++) 
        { 
            $ndt = 24 * $tiempo[$kk] / $dt;
            $qo = -$bopd[$kk];
            for ($v=1; $v <= $ndt ; $v++) 
            { 
                #coeficientes matriz tridiagonal
                $i = 1;
                while($i == $x + 1)
                {
                    $i = $i + 1;
                    $b[$i] = pow($r1[$i - 1], $n) / (pow($r[$i], $n) * $dr[$i] * $dr1[$i - 1]);
                }
                for ($i=$x + 2; $i <= $nr ; $i++) 
                { 
                    $b[$i] = $r1[$i - 1] / ($r[$i] * $dr[$i] * $dr1[$i - 1]);
                }
                
                $i = 0;
                if ($x == 0)
                {
                    $x = 1;
                }
                while ($i == $x - 1)
                {
                    $i = $i + 1;
                    $a[$i] = pow($r1[$i], $n) / (pow($r[$i], $n) * $dr[$i] * $dr1[$i]);
                }

                for ($i=$x; $i < $nr ; $i++) 
                { 
                    $a[$i] = $r1[$i] / ($r[$i] * $dr[$i] * $dr1[$i]);
                }

                $i = 0;
                while ($i == $x)
                {
                    $i = $i + 1;
                    $mu = $this->interpolation($pn[$i], $nv, $ppvt, $uopvt);
                    $g1 = 3792.58489625175 * $n * $phin[$i] * $cr / $kn[$i];
                    $f[$i] = $g1 * $mu / $dt; #g1 * uapp[$i] / dt
                }

                for ($i = $x + 1; $i <= $nr ; $i++) 
                { 
                    $g2 = 3792.58489625175 * $phin[$i] * $cr * $un / $kn[$i];
                    $f[$i] = $g2 / $dt;
                }

                if ($ri > 0 and $ri < $re)
                {
                    $b[$x] = $dr1[$x] / $dr1[$x - 1];
                    $mu = $this->interpolation($pn[$i], $nv, $ppvt, $uopvt);
                    $a[$x] = $mu / $un; #uapp[$x] / un
                    $f[$x] = 0;
                }

                $c[1] = -($a[1] + $f[1]);
                $c[$nr] = -($b[$nr] + $f[$nr]);

                for ($i=2; $i < $nr ; $i++) 
                { 
                    $c[$i] = -($a[$i] + $b[$i] + $f[$i]);
                }
                
                for ($i=1; $i <= $nr ; $i++) 
                { 
                    if ($i == 1)
                    {
                        $beta = $this->interpolation($pn[$i], $nv, $ppvt, $bopvt);
                        $vm = $pi * $hf * (pow($r1[1], 2) - pow($rw, 2)) / (5.615 * $beta);
                        $mu = $this->interpolation($pn[$i], $nv, $ppvt, $uopvt);
                        $d[$i] = -$f[$i] * $pn[$i] - 158.024370659982 * ($qo / ($kn[$i] *$vm)) * $mu;
                    }
                    else
                    {
                        $d[$i] = -$f[$i] * $pn[$i];
                    }
                }
                $qq[1] = $a[1] / $c[1];
                $gg[1] = $d[1] / $c[1];

                for ($j=2; $j <= $nr ; $j++) 
                { 
                    $w[$j] = $c[$j] - ($b[$j] * $qq[$j - 1]);
                    $gg[$j] = ($d[$j] - ($b[$j] * $gg[$j - 1])) / $w[$j];
                    $qq[$j] = $a[$j] / $w[$j];
                }

                $pcal[$nr] = $gg[$nr];
                for ($j = $nr - 1; $j >= 1 ; $j--) 
                { 
                    $pcal[$j] = ($gg[$j] - ($qq[$j] * $pcal[$j + 1]));
                }

                for ($i=1; $i <= $nr ; $i++) 
                { 
                    $rho = $this->interpolation($pcal[$i], $nv, $ppvt, $dopvt);
                    $coi = $this->interpolation($pcal[$i], $ns, $pasf, $sasf);
                    $co[$i] = $wtasf[$kk] * 10000 / $rho * $coi;
                }

                #Cálculo del flux
                for ($i=2; $i < $nr ; $i++) 
                {                 
                    $dpre[$i] = -($pcal[$i] - $pcal[$i - 1]) / (2 * $dr[$i]);
                }
                $dpre[$nr] = 0;
                $u[1] = -2.5 * 158.024370659982 * $qo / (2 * $pi * $rw * $hf); #ft/dia
                
                for ($i=2; $i <= $nr ; $i++) 
                { 
                    $mu = $this->interpolation($pcal[$i], $nv, $ppvt, $uopvt);
                    $u[$i] = -$kn[$i] * $dpre[$i] / $mu;
                    if ($u[$i] < 0.000001)
                    {
                        $u[$i] = 0;
                    }
                }
                
                #Cambio de porosidad
                for ($i=1; $i <= $nr ; $i++) 
                { 
                    $mu = $this->interpolation($pcal[$i], $nv, $ppvt, $uopvt);
                    $rho = $this->interpolation($pcal[$i], $nv, $ppvt, $dopvt);
                    $beta = $this->interpolation($pcal[$i], $nv, $ppvt, $bopvt);
                    $muo[$i] = $mu;
                    $rhof[$i] = $rho;
                    $boi[$i] = $beta;
                }

                $porosity_change_results = $this->porosity_change($nr, $dpart, $dporo, $rhop, $rhof, $muo, $boi, $rw, $hf, $r1, $r, $dr, $phin, $co, $ea, $u, $dt);
                $phic = $porosity_change_results[0];
                $deadt = $porosity_change_results[1];
                
                for ($i=1; $i <= $nr ; $i++) 
                { 
                    $dphi[$i] = $phin[$i] - $phic[$i];
                }

                #Cambio de permeabilidad
                for ($i=1; $i <= $nr ; $i++) 
                { 
                    $ea[$i] = $ea[$i] + $deadt[$i];
                    $kc[$i] = $kn[$i] * pow((($phic[$i]) / $phin[$i]), 2.5);
                }

                #Solución de la ecuacion de concentracion de particulas
                $concentration_change_results = $this->concentration_change($rw, $rdre, $hf, $nr, $phic, $u, $r, $dt, $co, $deadt, $dphi, $rl);
                $cocal = $concentration_change_results[0];
                $rl = $concentration_change_results[1];

                for ($i=1; $i < $nr ; $i++) 
                { 
                    $coi = $this->interpolation($r[$i], 100, $rl, $cocal);
                    $coc[$i] = $coi;
                }
                
                $coc[$nr] = $co[$nr];

                for ($i=1; $i <= $nr ; $i++) 
                { 
                    $pn[$i] = $pcal[$i];
                    $phin[$i] = $phic[$i];
                    $kn[$i] = $kc[$i];
                    $co[$i] = $coc[$i];
                }

            }

            #Radio de daño
            for ($i=2; $i <= $nr ; $i++) 
            { 
                if(($ko - $kc[$i]) > 0.05)
                {
                    $radio_dam = ($r[$i] + $r[$i - 1]) / 2;
                }
            }

            $skin = 0;
            for ($i=1; $i <= $nr ; $i++) 
            { 
                if(($ko - $kc[$i]) > 0.05)
                {
                    $skin = $skin + (($ko / $kc[$i]) - 1.0) * log($radio_dam / $rw);
                }
            }

            $skin = $skin / ($i - 1);

            for ($i=1; $i <= $nr ; $i++) 
            { 
                $simulated_results[$i] = [$r[$i], $pcal[$i], $phic[$i], $kc[$i], $ea[$i], $co[$i]];
            }

            $damage_results[$kk] = [$hist[$kk], $radio_dam, $skin];

            array_push($complete_simulated_results, $simulated_results);
            array_push($complete_damage_results, $damage_results);
            
            dd($complete_simulated_results);
        }
    }


}
