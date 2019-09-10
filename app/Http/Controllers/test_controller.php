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
        #$this->run_simulation();
        return View::make('test_xxx');
    }

    function concentration_change($rw, $l, $hf, $cr, $qwi, $nx, $rc, $bo, $phin, $phic, $uc, $dt, $con, $deadt, $dphi, $rl)
    { 
        $v = array_fill(1,100,0); 
        $radio = array_fill(0,100,0); 
        $vporo = array_fill(1,100,0); 
        $vc = array_fill(1,100,0); 
        $u = array_fill(1,100,0);
        $deadt_co = array_fill(1,100,0); 
        $dphi_co = array_fill(1,100,0); 
        $coc = array_fill(1,100,0); 
        $phi_co = array_fill(1,100,0); 
        $bo_co = array_fill(1,100,0); 
        $dbo = array_fill(1,100,0);
        $cpoo = array_fill(1,100,0); 
        $cswo = array_fill(1,100,0); 
        $cpow = array_fill(1,100,0); 
        $csww = array_fill(1,100,0);
        $a = array_fill(1,100,0); 
        $c = array_fill(1,100,0); 
        $b = array_fill(1,100,0); 
        $d = array_fill(1,100,0); 
        $bb = array_fill(1,100,0); 
        $dd = array_fill(1,100,0); 
        $p = array_fill(1,100,0);

        $dv = $l / 100;
        $v[1] = 0.001;
        $radio[1] = $v[1];
        for ($i=2; $i <= 100 ; $i++) 
        { 
            $v[$i] = $v[$i - 1] + $dv;
            $radio[$i] = $v[$i];
        }
        $area = $dv * $hf;

        for ($i=1; $i <= 100 ; $i++) 
        { 
            $uci = $this->interpolation($radio[$i], $nx, $rc, $uc);
            $coi = $this->interpolation($radio[$i], $nx, $rc, $con);
            $deadti = $this->interpolation($radio[$i], $nx, $rc, $deadt);
            $dphii = $this->interpolation($radio[$i], $nx, $rc, $dphi);
            $phici = $this->interpolation($radio[$i], $nx, $rc, $phic);
            $boi = $this->interpolation($radio[$i], $nx, $rc, $bo);
            $rl[$i] = $radio[$i];
            $u[$i] = 0.0000092903 * $uci;
            $coc[$i] = $coi;
            $deadt_co[$i] = $deadti;
            $dphi_co[$i] = $dphii;
            $phi_co[$i] = $phici;
            $bo_co[$i] = $boi;
        }

        $dbo[1] = ($bo_co[2] - $bo_co[1]) / ($rl[2] - $rl[1]);
        $dbo[100] = ($bo_co[100] - $bo_co[99]) / ($rl[100] - $rl[99]);

        for ($i=2; $i < 100 ; $i++) 
        { 
            $dbo[$i] = ($bo_co[$i] - $bo_co[$i - 1]) / ($rl[$i] - $rl[$i - 1]);
        }

        for ($i=1; $i <= 100 ; $i++) 
        { 
            #-----------------------------------storage coefficients
            $cpoo[$i] = (1.0 - $con[$i]) * $phin[$i] * ($cr * $bo_co[$i] + $dbo[$i]) / $dt;
            $cswo[$i] = -$phic[$i] * $bo_co[$i] / $dt;
            $cpow[$i] = $con[$i] * $phic[$i] * $cr / $dt;
            $csww[$i] = $phic[$i] / $dt;
            #-----------------------------------matrix coefficients
            $alfa = -$cswo[$i] / $csww[$i];
            $a[$i] = $u[$i] + $alfa * $u[$i];
            $c[$i] = $u[$i] + $alfa * $u[$i];
            if ($i != 1)
            {
                $b[$i] = -(2 * $u[$i] + $cpoo[$i]) - (2 * $u[$i] + $cpow[$i]) * $alfa;
            }

            if ($i == 1)
            {
                $d[$i] = -($cpoo[$i] + $alfa * $cpow[$i]) * $coc[$i] + $alfa * (-$qwi / $dv / $area);
                $b[$i] = -($u[$i] + $cpoo[$i]) - (2 * $u[$i]) * $alfa;
            }
            else if ($i == 100)
            {
                $d[$i] = -($cpoo[$i] + $alfa * $cpow[$i]) * $coc[$i] - ($u[$i] + $alfa * $u[$i]) * $coc[100];
            } 
            else
            {
                $d[$i] = -($cpoo[$i] + $alfa * $cpow[$i]) * $coc[$i];
            }
        }

        $bb[1] = $b[1];
        $dd[1] = $d[1];
        for ($i=2; $i <= 100 ; $i++) 
        { 
          $x = $a[$i] / $bb[$i - 1];
          $bb[$i] = $b[$i] - $x * $c[$i - 1];
          $dd[$i] = $d[$i] - $x * $dd[$i - 1];
        }

        $co[100] = $dd[100] / $bb[100];
        for ($kk=2; $kk <= 100 ; $kk++) 
        { 
          $i = 100 - $kk + 1;
          $co[$i] = ($dd[$i] - $c[$i] * $co[$i + 1]) / $bb[$i];
        }

        return array($co, $rl);
    }

    function porosity_change($nx, $t, $dt, $ko, $phin, $u, $ucri_esc, $sigmaini, $dp, $rhop, $con, $k1, $k2, $k3, $k4, $k5, $k6, $dpdl, $dpdlc, $sigmai, $ab, $ab2)
    {
        $sigma1 = array_fill(1, $nx, 0); 
        $dphi = array_fill(1, $nx, 0); 
        $phisw = array_fill(1, $nx, 0); 
        $phip = array_fill(1, $nx, 0); 
        $sigma = array_fill(1, $nx, 0);

        #Rango de constantes fenomenológicas        
        #Cálculo de la tasa de depositacion y la depositacion de finos.
        #Daño de porosidad por hinchamiento.
        $s = $ab * pow($t, -0.5);
        $relperm = $k6 + (1 - $k6) * exp(-$ab2 * pow($t, 0.5));

        for ($i=1; $i <= $nx ; $i++) 
        { 
            $phisw[$i] = $phin[$i] * pow($relperm, (1.0 / 3.0));
            if (-$dp[$i] < $dpdl)
            {
                $dsigma[$i] = $k1 * $u[$i] * $rhop * $con[$i] - $k2 * $sigmai * (-$dp[$i] - $dpdl);
            }
            else
            {
                $dsigma[$i] = $k1 * $u[$i] * $rhop * $con[$i];
            }

            if ($u[$i] == 0)
            {
                $sigma[$i] = $sigmaini[$i];
            }
            else
            {
                $sigma[$i] = $sigmaini[$i] + 0.0000092903 * $dsigma[$i] * $dt;
            }
        }

        #Cálculo de la tasa de liberación y la liberación de finos
        for ($i=1; $i <= $nx ; $i++) 
        { 
            if(-$dp[$i] > $dpdlc)
            {
                $dsigma1[$i] = -$k3 * $sigma1[$i] * (1.0 - exp(-0.00092903 * $k4 * pow($t, 0.5))) * exp(-0.00092903 * $k5 * $sigmai) * (-$dp[$i] - $dpdlc);
            }
            else
            {
                $dsigma1[$i] = 0.0;
            }

            if ($u[$i] == 0)
            {
                $sigma1[$i] = $sigma1[$i]; #*
            }
            else
            {
                $sigma1[$i] = $sigma1[$i] + 0.0000092903 * $dt * $dsigma1[$i];
            }
        }


        #Cálculo de la porosidad efectiva y la permeabilidad en el modelo, derivada de porosidad.
        for ($i=1; $i <= $nx ; $i++) 
        { 
            $phip[$i] = 7.9242214121959e-05 * $sigma[$i] / $rhop;
            #beta=((8.91*10^-8)*tao)/(phio*ko)  ---- ajuste del modelo multitasa
            $dphi[$i] = -$phin[$i] / 3.0 / pow($relperm, (2.0 / 3.0)) * (1.0 - 0.00092903 * $k6) * exp(-$ab * pow($t, 0.5)) * $ab / (2.0 * pow($t, 0.5)) - $dsigma[$i] / $rhop;

            if ($sigma[$i] > 0.00001)
            {
                $phic[$i] = $phisw[$i] - $phip[$i];
            }
            else
            {
                $phic[$i] = $phin[$i];
                $sigma[$i] = 0;
            }

            $sigmasal[$i] = $sigma[$i];
        }

        return array($phic, $dsigma, $dsigma1, $sigmasal);
    }

    function rate_scaling($rw, $tcri, $hf, $rplug, $tpp, $rp)
    {
        $bw = 1.1;
        #Hueco abierto
        $tcri_esc = 0.009057 * $tcri * (1.0 / $bw) * 2 * $rw * $hf / pow($rplug, 2); #stb/dia

        if ($rw < 0.375)
        {
            $fp3 = 1.036 * ($tpp * $rp) / (0.9932 * $tpp * $rp + 0.7718);
            $tcri_esc = $tcri_esc * $fp3;
        }
        else
        {
            $fp6 = 1.0258 * ($tpp * $rp) / (0.9742 * $tpp * $rp + 0.8845);
            $tcri_esc = $tcri_esc * $fp6;
        }

        return $tcri_esc;
    }

    function fines_interpolation($x, $n, $xt, $yt)
    {
        $y = 0.0;
              
        #interpolación entre dos puntos.
        if ($x < $xt[1])
        {
            $y = $yt[1];
        }

        if ($x > $xt[$n])
        {
            $y = $yt[$n];
        }

        if ($x < $xt[$n])
        {
            for ($i=2; $i <= $n ; $i++) 
            {                 
                if (!($x >= $xt[$i]))
                {
                    $y = $yt[$i - 1];
                    return $y;
                }
                if ($x = $xt[$i])
                {
                    $y = $yt[$i];
                }
            }
        }
        return $y;
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
    function simulate_deposited_fines($rdre, $hf, $rw, $swi, $cr, $pini, $phio, $ko, $dporo, $dpart, $rhop, $coi, $sigmai, $tcri, $fmov, $tpp, $rp, $pvt_data, $historical_data, $fines_data) 
    {
        set_time_limit(400); //Cambiar
        $complete_simulated_results = [];

        $nv = count($pvt_data[0]);
        $ppvt = $pvt_data[0];
        $dopvt = $pvt_data[1];
        $uopvt = $pvt_data[2];
        $bopvt = $pvt_data[3];

        #Revisar ajuste nh
        $nh = count($historical_data[0]);
        $hist = $historical_data[0];
        $bopd = $historical_data[1];
        $bwpd = $historical_data[2];

        $ns = count($fines_data[0]);
        $qlab = $fines_data[0];
        $k1_lab = $fines_data[1];
        $k2_lab = $fines_data[2];
        $dpdl_lab = $fines_data[3];
        $k3_lab = $fines_data[4];
        $k4_lab = $fines_data[5];
        $k5_lab = $fines_data[6];
        $dpdls_lab = $fines_data[7];
        $sigma_lab = $fines_data[8];
        $k6_lab = $fines_data[9];
        $ab2_lab = $fines_data[10];
        $ab_lab = $fines_data[11];

        $pi = 3.14159265359;
        $x = 0;
        $ki = [];

        #Escalamiento de tasa crítica escalada
        $rplug = 0.061; #Medición estandar para un núcleo de laboratorio [ft]
        $tcri_esc = $this->rate_scaling($rw, $tcri, $hf, $rplug, $tpp, $rp);

        #Conversión tasas de laboratorio
        for ($i=1; $i <= $ns ; $i++) 
        { 
          $qlab[$i] = $this->rate_scaling($rw, $qlab[$i], $hf, $rplug, $tpp, $rp, $qlab[$i]);
        }

        #Discretizando el medio (geometría radial)
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
                if ($r1[$i] < $ri)
                {
                    $x = $i;
                }
            }
        }

        for ($i=1; $i <= $nr ; $i++) 
        { 
            if ($i == 1)
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
        $co = array_fill(1, $nr, $coi); 
        $cod = array_fill(1, $nr, 0); 
        $sigmaini = array_fill(1, $nr, $sigmai); 
        $bo = array_fill(1, $nr, 0);
        $rho = $this->interpolation($pini, $nv, $ppvt, $dopvt);


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
        $cocal = array_fill(1, 100, 0);
        $rl = array_fill(1, 100, 0); 
        $coc = array_fill(1, $nr, 0); 
        $cod = array_fill(1, $nr, 0); 
        $sigmasal = array_fill(1, $nr, 0); 
        $dsigma = array_fill(1, $nr, 0); 
        $dsigma1 = array_fill(1, $nr, 0); 
        $rdamage = array_fill(1, $nr, 0);
        $tiempo = array_fill(1, $nh, 0);
        
        $n = 0.5;
        $dt = 30;
        $un = 3;
        
        #Delta de tiempo
        for ($i=1; $i <= $nh ; $i++) 
        { 
            if ($i == 1)
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
            $ndt = $tiempo[$kk] / $dt;
            $qo = -$bopd[$kk];

            for ($v=1; $v <= $ndt ; $v++) 
            { 
                #coeficientes matriz tridiagonal
                $i = 1;
                while ($i == $x + 1)
                {
                    $i = $i + 1;
                    $b[$i] = pow($r1[$i - 1], $n) / (pow($r[$i], $n) * $dr[$i] * $dr1[$i - 1]);
                }

                for ($i= $x + 2; $i <= $nr ; $i++) 
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

                for ($i=$x + 1; $i < $nr ; $i++) 
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
                        $d[$i] = -$f[$i] * $pn[$i] - 158.024370659982 * ($qo / ($kn[$i] * $vm)) * $mu;
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

                for ($j=$nr - 1; $j >= 1 ; $j--) 
                { 
                    $pcal[$j] = ($gg[$j] - ($qq[$j] * $pcal[$j + 1]));
                }

                for ($i=1; $i <= $nr ; $i++) 
                { 
                    $rho = $this->interpolation($pcal[$i], $nv, $ppvt, $dopvt); #Revisar -->quitar y evaluar con último valor de pcal.
                }

                #Cálculo del flux
                for ($i=2; $i < $nr; $i++) 
                { 
                    $dpre[$i] = -($pcal[$i] - $pcal[$i - 1]) / (2 * $dr[$i]);
                }

                $dpre[$nr] = 0;
                $u[1] = -2.5 * 158.024370659982 * $qo / (2 * $pi * $rw * $hf); #ft/dia
                $ucri_esc = 2.5 * 158.024370659982 * $tcri_esc / (2 * $pi * $rw * $hf);  #ft/dia
                for ($i=2; $i <= $nr ; $i++) 
                { 
                    $mu = $this->interpolation($pcal[$i], $nv, $ppvt, $uopvt);
                    $u[$i] = -$kn[$i] * $dpre[$i] / $mu;
                    if ($u[$i] < 0.00001)
                    {
                        $u[$i] = 0;
                    }
                }

                #Identificación de fenomenos
                $k1i = $this->fines_interpolation(-$qo, $ns, $qlab, $k1_lab);
                $k2i = $this->fines_interpolation(-$qo, $ns, $qlab, $k2_lab);
                $dpdli = $this->fines_interpolation(-$qo, $ns, $qlab, $dpdl_lab);
                $k3i = $this->fines_interpolation(-$qo, $ns, $qlab, $k3_lab);
                $k4i = $this->fines_interpolation(-$qo, $ns, $qlab, $k4_lab);
                $k5i = $this->fines_interpolation(-$qo, $ns, $qlab, $k5_lab);
                $dpdlsi = $this->fines_interpolation(-$qo, $ns, $qlab, $dpdls_lab);
                $sigmai = $this->fines_interpolation(-$qo, $ns, $qlab, $sigma_lab);
                $k6i = $this->fines_interpolation(-$qo, $ns, $qlab, $k6_lab);
                $ab2i = $this->fines_interpolation(-$qo, $ns, $qlab, $ab2_lab);
                $abi = $this->fines_interpolation(-$qo, $ns, $qlab, $ab_lab);

                #Cambio de porosidad - No se usa ki para estos escenarios --> ajuste del modelo multitasa. Revisar y quitar
                $porosity_change = $this->porosity_change($nr, $ndt * $tiempo[$kk], $tiempo[$kk], $ki, $phin, $u, $ucri_esc, $sigmaini, $dpre, $rhop, $co, $k1i, $k2i, $k3i, $k4i, $k5i, $k6i, $dpdli, $dpdlsi, $sigmai, $abi, $ab2i);
                $phic = $porosity_change[0];
                $dsigma = $porosity_change[1];
                $dsigma1 = $porosity_change[2];
                $sigmasal = $porosity_change[3];

                #Delta de porosidad
                for ($i=1; $i <= $nr ; $i++) 
                { 
                    if ($u[$i] > $ucri_esc)
                    {
                        $dphi[$i] = $phin[$i] - $phic[$i];
                    }
                    else
                    {
                        $dphi[$i] = 0;
                        $sigmasal[$i] = 0;
                    }
                    $beta = $this->interpolation($pcal[$i], $nv, $ppvt, $bopvt, $beta);
                    $boi[$i] = $beta;
                }

                #Factor de corrección ecuación de partículas
                $fcorr = 0.899;
                $concentration_change_results = $this->concentration_change($rw, $rdre, $hf, $cr, $qo, $nr, $r1, $boi, $phin, $phic, $u, $dt, $co, $dsigma1, $dphi, $rl);
                $cocal = $concentration_change_results[0];
                $rl = $concentration_change_results[1];

                for ($i=1; $i < $nr ; $i++) 
                { 
                    $coi = $this->interpolation($r[$i], 100, $rl, $cocal);
                    $coc[$i] = $coi;
                }

                $coc[$nr] = $co[$nr];

                #Cambio de permeabilidad
                for ($i=1; $i <= $nr ; $i++) 
                { 
                    $cod[$i] = $cod[$i] + $sigmasal[$i];
                    $kc[$i] = $kn[$i] * pow($phic[$i] / $phin[$i], 3.0); #Revisar
                }

                for ($i=1; $i <= $nr ; $i++) 
                { 
                    $pn[$i] = $pcal[$i];
                    $phin[$i] = $phic[$i];
                    $kn[$i] = $kc[$i];
                    $co[$i] = $coc[$i];
                    $sigmaini[$i] = $sigmasal[$i];
                }
            }

            #Radio de daño
            for ($i=2; $i <= $nr ; $i++) 
            { 
                $rdamage[$i] = (($ko - $kc[$i]) / $phio) * 100;
            }

            #Reducción del 10% de permeabilidad
            $radio_dam = $this->interpolation(10, $nr, $r, $rdamage);

            if ($radio_dam >= $rdre)
            {
                $radio_dam = $rw; #fallo inter
            }

            if ($radio_dam < $rw)
            {
                $radio_dam = $rw; #fallo inter
            }

            $skinprom = 0;
            $npro = 1;
            for ($i=1; $i <= $nr ; $i++) 
            { 
                if ($r[$i] < $radio_dam)
                {
                    $npro = $npro + 1;
                    $skinprom = $kc[$i] + $skinprom;
                }
            }

            $skinprom = $skinprom / $npro;
            $skin = (($kc[1] / $ko) - 1.0) * log($rw / $radio_dam);

            for ($i=1; $i <= $nr ; $i++) 
            { 
                $simulation_results[$i] = array($r[$i], $pcal[$i], $phic[$i], $kc[$i], $coc[$i]);
            }
            array_push($complete_simulated_results, $simulation_results);
            $damage_results[$kk] = array($hist[$kk], $radio_dam, $skin);
        }

        return array($complete_simulated_results, $damage_results);
    }
    function run_simulation()
    {
        $rdre = 3160;
        $hf = 72;
        $rw = 0.208;
        $swi = 0.32;
        $cr = 0.000001;
        $pini = 3800;
        $phio = 0.07;
        $ko = 170;
        $dporo = 2.54;
        $dpart = 0.1;
        $rhop = 1.2;
        $coi = 0.9185;
        $sigmai = 0.0051658;
        $tcri = 4;
        $fmov = 1;
        $tpp = 132;
        $rp = 0.16;

        $pressure = [1 => 147, 294, 441, 588, 735, 882, 1029, 1176, 1323, 1470, 1617, 1764, 1911, 2058, 2205, 2352, 2499, 2646, 2793, 2940, 3087, 3234, 3381, 3528, 3675, 3822, 3969, 4116, 4263, 4410, 4557, 4704, 4851, 4998, 5145, 5292, 5439, 5586, 5733, 5880, 6027, 6174, 6321, 6468, 6615, 6762, 6909, 7056, 7203, 7350, 7497, 7644, 7791, 7938];
        $den = [1 => 0.771, 0.771, 0.771, 0.771, 0.771, 0.771, 0.771, 0.771, 0.771, 0.771, 0.771, 0.771, 0.771, 0.771, 0.771, 0.771, 0.771, 0.771, 0.771, 0.771, 0.771, 0.771, 0.771, 0.771, 0.771, 0.771, 0.771, 0.771, 0.771, 0.771, 0.771, 0.771, 0.771, 0.771, 0.771, 0.771, 0.771, 0.771, 0.771, 0.771, 0.771, 0.771, 0.771, 0.771, 0.771, 0.771, 0.771, 0.771, 0.771, 0.771, 0.771, 0.771, 0.771, 0.771];
        $miuo = [1 => 0.92, 0.92, 0.92, 0.92, 0.92, 0.92, 0.92, 0.92, 0.92, 0.92, 0.92, 0.92, 0.92, 0.92, 0.92, 0.92, 0.92, 0.92, 0.92, 0.92, 0.92, 0.92, 0.92, 0.92, 0.92, 0.92, 0.92, 0.92, 0.92, 0.92, 0.92, 0.92, 0.92, 0.92, 0.92, 0.92, 0.92, 0.92, 0.92, 0.92, 0.92, 0.92, 0.92, 0.92, 0.92, 0.92, 0.92, 0.92, 0.92, 0.92, 0.92, 0.92, 0.92, 0.92];
        $bo = [1 => 0.99, 0.99, 0.99, 0.99, 0.99, 0.99, 0.99, 0.99, 0.99, 0.99, 0.99, 0.99, 0.99, 0.99, 0.99, 0.99, 0.99, 0.99, 0.99, 0.99, 0.99, 0.99, 0.99, 0.99, 0.99, 0.99, 0.99, 0.99, 0.99, 0.99, 0.99, 0.99, 0.99, 0.99, 0.99, 0.99, 0.99, 0.99, 0.99, 0.99, 0.99, 0.99, 0.99, 0.99, 0.99, 0.99, 0.99, 0.99, 0.99, 0.99, 0.99, 0.99, 0.99, 0.99];
        $bw = [1 => 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1];
        $miuw = [1 => 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1];
        $denw = [1 => 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1];

        $pvt_data = [$pressure, $den, $miuo, $bo, $bw, $miuw, $denw];

        $date_raw = [1 => "1/04/2015", "1/05/2015", "1/06/2015", "1/07/2015", "1/08/2015", "1/09/2015", "1/10/2015", "1/11/2015", "1/12/2015", "1/01/2016", "1/02/2016", "1/03/2016", "1/04/2016", "1/05/2016", "1/06/2016", "1/07/2016", "1/08/2016", "1/09/2016", "1/10/2016", "1/11/2016", "1/12/2016", "1/01/2017", "1/02/2017", "1/03/2017", "1/04/2017", "1/05/2017", "1/06/2017", "1/07/2017", "1/08/2017", "1/09/2017", "1/10/2017", "1/11/2017", "1/12/2017"];

        foreach ($date_raw as $key => $value) 
        {
            $date_split = explode("/", $value);
            $dates[$key] = $date_split[0]."-".$date_split[1]."-".$date_split[2];
        }

        $bopd = [1 => 652.9325677, 718.7232611, 634.072521, 621.820584, 612.0566631, 602.73931, 593.2679625, 584.3028552, 575.1179618, 566.076172, 557.7473285, 549.0471366, 540.6999448, 532.2051416, 524.1233376, 515.8957484, 507.7998493, 500.105772, 492.2682006, 484.7626475, 477.1776746, 467.2067985, 458.453746, 448.8921229, 439.86246, 430.7062656, 422.005603, 413.2403232, 404.6097177, 396.4692348, 388.2097128, 380.3693428, 372.4674176];
        $bwpd = [1 => 34.0074779, 96.6063, 106.0059, 39.39012, 44.07566695, 49.1133894, 54.89294816, 61.10006906, 68.21320231, 76.11120391, 84.28375436, 93.9398482, 104.2815823, 116.1006234, 128.7456, 131.4208938, 134.1346988, 136.7984234, 139.5889279, 142.3272767, 145.1950241, 147.3109846, 149.2184481, 151.3212632, 153.3494172, 155.4360435, 157.4469207, 159.5143241, 161.5709901, 163.550412, 165.5834907, 167.5390109, 169.5455628];

        $historical_data = [$dates, $bopd, $bwpd];
        
        $qlab = [1 => 1.6,2,2.4,2.8,3.2,4];
        $k1_lab = [1 => 1.74E-06, 0.2808, 0.2861, 0.2913, 0.2965, 0.3019];
        $k2_lab = [1 => 2.7687,2.8037,2.8043,2.8048,2.8051,2.8044];
        $dpdl_lab = [1 => 0.1288, 0.0457, 0.045, 0.0445, 0.0442, 0.045];
        $k3_lab = [1 => 0.6928, 0.6889, 0.6895, 0.6902, 0.6912, 0.6935];
        $k4_lab = [1 => 0.5449, 0.4946, 0.495, 0.4955, 0.4957, 0.4966];
        $k5_lab = [1 => 0.3005, 0.2999, 0.2999, 0.2999, 0.2999, 0.2999];
        $dpdls_lab = [1 => 0.4601, 0.4031, 0.4029, 0.4026, 0.4022, 0.4016];
        $sigma_lab = [1 => 0.0286, 0.027, 0.0322, 0.0376, 0.0432, 0.0552];
        $k6_lab = [1 => 0, 0, 0, 0, 0, 0];
        $ab2_lab = [1 => 0, 0, 0, 0, 0, 0];
        $ab_lab = [1 => 0, 0, 0, 0, 0, 0];

        $fines_data = [$qlab, $k1_lab, $k2_lab, $dpdl_lab, $k3_lab, $k4_lab, $k5_lab, $dpdls_lab, $sigma_lab, $k6_lab, $ab2_lab, $ab_lab];

        $simulation_results = $this->simulate_deposited_fines($rdre, $hf, $rw, $swi, $cr, $pini, $phio, $ko, $dporo, $dpart, $rhop, $coi, $sigmai, $tcri, $fmov, $tpp, $rp, $pvt_data, $historical_data, $fines_data);

        return $simulation_results;
    }
}

