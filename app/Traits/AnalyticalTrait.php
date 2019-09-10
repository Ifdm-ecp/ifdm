<?php
namespace App\Traits;
use Validator;

trait AnalyticalTrait {

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

        $Total_Analytical = $msp +$fbp +$osp +$rpp +$idp +$gdp;
        $Total_Analytical = ($Total_Analytical == 0) ? 1 : $Total_Analytical;
        //dd($gdp);
        return collect([($msp/$Total_Analytical)*100, ($fbp/$Total_Analytical)*100, ($osp/$Total_Analytical)*100, ($rpp/$Total_Analytical)*100, ($idp/$Total_Analytical)*100, ($gdp/$Total_Analytical)*100]);

    }

    public function analytical($analytical, $pressures_data, $radius_data, $parametro, $funcion)
    {
        $damageRadius = $this->SkinRadius($analytical, $pressures_data, $radius_data, $funcion);
        if($damageRadius == $analytical->well_radius || $damageRadius == 0 || $analytical->well_radius == 0){
            return 0;
        } else {
            return ((1/$parametro)-1) * log($damageRadius/$analytical->well_radius);
        }
    }

     //Ok
    public function SkinRadius($analytical, $pressures_data, $radius_data, $funcion)
    {
        
        $skinRadius = 0.0;
        if($funcion == "ms")
        {
            $skinRadius = $this->interpolation($analytical, $analytical->mineral_scale_cp, $pressures_data, $radius_data);
            #$skinRadius = $this->PvsR_profile($analytical, $analytical->mineral_scale_cp) + $analytical->well_radius; 
        }
        else if($funcion =="fb")
        {

            $skinRadius = $analytical->critical_radius; 
            #$skinRadius = $analytical->critical_radius + $analytical->well_radius; 
        }
        else if($funcion == "os")
        {
            $skinRadius = $this->interpolation($analytical, $analytical->organic_scale_cp, $pressures_data, $radius_data);
            #$skinRadius = $this->PvsR_profile($analytical, $analytical->organic_scale_cp) + $analytical->well_radius; 
        }
        else if($funcion == "rp")
        {
            $skinRadius = $this->interpolation($analytical, $analytical->saturation_presure, $pressures_data, $radius_data);
            #$skinRadius = $this->PvsR_profile($analytical, $analytical->saturation_presure) + $analytical->well_radius;
        }
        else if($funcion == "id")
        {
            $skinRadius = sqrt((($analytical->total_volumen * 5.615)/ (pi() * $analytical->netpay * $analytical->porosity)) + pow($analytical->well_radius, 2));

        }
        else if($funcion == "gd")
        {
            $skinRadius = $this->interpolation($analytical, $analytical->geomechanical_damage_cp + $analytical->bhp, $pressures_data, $radius_data);
            #$skinRadius = $this->PvsR_profile_GDP($analytical/*$analytical->geomechanical_damage_cp*/) + $analytical->well_radius;
        }

        return $skinRadius;
    }

    function PvsR_profile($analytical){
        $pressures_data = [];
        $radius_data = [];
        $Radius = $analytical->well_radius; 
        $Pr = $analytical->bhp; 
        while($Radius < $analytical->drainage_radius)
        {
            if($analytical->fluid_type == "Oil")
            {
                $Pr = $analytical->bhp + (((141.2 * $analytical->fluid_rate * $analytical->viscosity * $analytical->volumetric_factor)/($analytical->netpay * $analytical->absolute_permeability)) * (log( $Radius/$analytical->well_radius)-0.75)); 
                array_push($pressures_data, $Pr);
                array_push($radius_data, $Radius);

            }else{
                $Pr = $analytical->bhp + (((141.2 * $analytical->fluid_rate * (1000000) * $analytical->viscosity * $analytical->volumetric_factor)/(5.615 * $analytical->netpay * $analytical->absolute_permeability)) * (log($Radius/$analytical->well_radius)-0.75)); 
                array_push($pressures_data, $Pr);
                array_push($radius_data, $Radius);
            }
            
            $Radius = $Radius + 0.05;
        }
        return array($radius_data, $pressures_data);
    }

    function interpolation($analytical, $critical_pressure, $pressures_data, $radius_data){
        if ($critical_pressure >= end($pressures_data)){
            return $analytical->drainage_radius;
        } else if($critical_pressure <= $analytical->bhp ){
            return $analytical->bhp;
        } else {
            for ($j = 0; $j < count($pressures_data)-1; $j++) {
                if ($critical_pressure >= $pressures_data[$j] && $critical_pressure < $pressures_data[$j+1]){
                    return (($radius_data[$j+1]-$radius_data[$j])/($pressures_data[$j+1]-$pressures_data[$j])) * ($critical_pressure-$pressures_data[$j]) + $radius_data[$j];
                }
            }
        }
    }

    // function PvsR_profileffrff($analytical, $CriticalPressure)
    // {
          
    //         $Radius = $analytical->well_radius; 
    //         $Pr = $analytical->bhp; 
    //         $DeltaP = $Pr - $CriticalPressure; 
    //         while($DeltaP < 0.0 && $Radius < $analytical->drainage_radius)
    //         {
    //           $Radius = $Radius + 0.05;
    //           if($analytical->fluid_type == "Oil")
    //           {

    //               $Pr = $analytical->bhp + (((141.2 * $analytical->fluid_rate * $analytical->viscosity * $analytical->volumetric_factor)/($analytical->netpay * $analytical->absolute_permeability)) * log( $Radius/$analytical->well_radius))-(0.5*(pow(($Radius/$analytical->drainage_radius), 2))); 

    //           }else{
    //               $Pr = $analytical->bhp + (((141.2 * $analytical->fluid_rate * (1000000) * $analytical->viscosity * $analytical->volumetric_factor)/(5.615 * $analytical->netpay * $analytical->absolute_permeability)) * (log($Radius/$analytical->well_radius)-(0.5*(pow(($Radius/$analytical->drainage_radius), 2))))); 
    //           }
              
    //           $DeltaP = $Pr - $CriticalPressure;

    //           if($Radius >= $analytical->drainage_radius)
    //           {
    //               $Radius = $analytical->drainage_radius - $analytical->well_radius;

    //           }
    //       }
    //       return $Radius;
    //   }


    // function PvsR_profile_GDP($analytical)
    // {       
          
    //       $Radius = $analytical->well_radius; 
    //       if ($analytical->fluid_type == "Oil")
    //       {
    //           $Pr_Rmax = $analytical->bhp + (((141.2 * $analytical->fluid_rate * $analytical->viscosity * $analytical->volumetric_factor)/($analytical->netpay * $analytical->absolute_permeability)) * log($analytical->drainage_radius/$analytical->well_radius)) - (0.5 * (pow(($analytical->drainage_radius/$analytical->drainage_radius), 2)));
    //           $Pr_Rmin = $analytical->bhp + (((141.2 * $analytical->fluid_rate * $analytical->viscosity * $analytical->volumetric_factor)/($analytical->netpay * $analytical->absolute_permeability)) * log($analytical->well_radius/$analytical->well_radius)) - (0.5 * (pow(($analytical->well_radius/$analytical->drainage_radius), 2)));
    //       }
    //       else
    //       {
    //           $Pr_Rmax = $analytical->bhp + (((141.2 * $analytical->fluid_rate * (1000000) *  $analytical->viscosity * $analytical->volumetric_factor)/(5.615 * $analytical->netpay * $analytical->absolute_permeability)) * log($analytical->drainage_radius/$analytical->well_radius))-(0.5 * (pow(($analytical->drainage_radius/$analytical->drainage_radius), 2)));
    //           $Pr_Rmin = $analytical->bhp + (((141.2 * $analytical->fluid_rate *(1000000) * $analytical->viscosity * $analytical->volumetric_factor)/(5.615 * $analytical->netpay * $analytical->absolute_permeability)) * log($analytical->well_radius/$analytical->well_radius))-(0.5 * (pow(($analytical->well_radius/$analytical->drainage_radius), 2)));
    //       }

    //       $MaxDrawDown_fraction = ($Pr_Rmax - $Pr_Rmin) / ($Pr_Rmax - $Pr_Rmin);
    //       $CummDrawDown = 1.0 - $MaxDrawDown_fraction;


    //       while ($CummDrawDown < 0.25 && $Radius < $analytical->drainage_radius)
    //       {
    //           $Radius = $Radius + 0.01;
    //           if ($analytical->fluid_type == "Oil")
    //           {
    //               $Pr = $analytical->bhp + (((141.2 * $analytical->fluid_rate * $analytical->viscosity * $analytical->volumetric_factor)/($analytical->netpay * $analytical->absolute_permeability))* log($Radius/$analytical->well_radius))-(0.5*(pow(($Radius/$analytical->drainage_radius), 2)));
    //           }
    //           else
    //           {
    //               $Pr = $analytical->bhp + (((141.2 * $analytical->fluid_rate * (1000000) * $analytical->viscosity * $analytical->volumetric_factor)/(5.615 * $analytical->netpay * $analytical->absolute_permeability)) * log($Radius/$analytical->well_radius))-(0.5 * (pow(($Radius/$analytical->drainage_radius), 2)));
    //           }

    //           $MaxDrawDown_fraction = ($Pr_Rmax - $Pr) / ($Pr_Rmax - $Pr_Rmin);
    //           $CummDrawDown = 1.0 - $MaxDrawDown_fraction;
    //       }

    //       if ($Radius >= $analytical->drainage_radius)
    //       {
    //           $Radius = $analytical->drainage_radius - $analytical->well_radius;
    //       }

    //       return $Radius;
    // }
}