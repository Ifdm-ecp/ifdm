<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\asphaltene_treatment;

class asphaltene_remediations extends Model
{
	protected $table = 'asphaltene_remediation';


	static function results($scenary){

		$data = $scenary->asphalteneRemediations;
		$radius_list = collect();
		$permeability_list = collect();
		$porosity_list = collect();
		$deposited_asphaltene_list = collect();
		$dataTable = json_decode($data->excel_changes_along_the_radius);
		$aux1 = collect();
		$aux2 = collect();
		foreach ($dataTable as $key => $value) {
			$radius_list->push($value[0]);
			$permeability_list->push($value[1]);
			$porosity_list->push($value[2]);
			$deposited_asphaltene_list->push($value[3]);
			$aux1->push([round($value[0], 4), $value[2]]);
			$aux2->push([round($value[0], 4), $value[1]]);
		}
        
        if ($data->chemical_treatment_impl == 'no') {
            $asphaltene_treatment = asphaltene_treatment::find($data->option_treatment);
            $data->asphaltene_dilution_capacity = $asphaltene_treatment->dilution_capacity;
        }

        $datos = [];

        if ($data->status_wr) {
            $datos =  self::from_diagnosis($data->initial_porosity, $data->initial_permeability, $data->net_pay, $data->water_saturation, $radius_list, $permeability_list, $porosity_list, $deposited_asphaltene_list,$data->asphaltene_apparent_density, $data->asphaltene_dilution_capacity, $data->asphaltene_remotion_efficiency_range_a, $data->asphaltene_remotion_efficiency_range_b, $data->treatment_radius, $data->wellbore_radius);
    		$datos->push($aux2);
    		$datos->push($aux1);
    		$datos->push($data->treatment_radius);
        }

/*
    	$datos =  self::from_diagnosis(0.2, 25, 45, 0.3, [0.25, 2.25, 4.25, 6.25, 8.25, 10.25], [10, 10.5, 11, 11.5, 12, 12.5],  [0.12, 0.13, 0.135, 0.14, 0.15, 0.155], [4, 3.8, 3.7, 3.6, 3.55, 3.5],
					0.96, 0.5,  0.3, 0.7, 8.25, 0.5);

    	$datos->push([10, 10.5, 11, 11.5, 12, 12.5]);
    	$datos->push([0.12, 0.13, 0.135, 0.14, 0.15, 0.155]);
    	*/

    	return $datos;
    }

    static function from_diagnosis($initial_porosity, $initial_permeability, $netpay, $water_saturation, $radius_list, $permeability_list, $porosity_list, $deposited_asphaltene_list,
    	$asphaltene_density, $asphaltene_dissolution_capacity, $min_efficiency, $max_efficiency, $treatment_radius, $well_radius)
    {

    	$netpay *= 0.3048;
    	$asphaltene_density *= 1000;
    	$asphaltene_dissolution_capacity *= 0.001;
    	$treatment_radius *= 0.3048;
    	$well_radius *= 0.3048;

    	$radius_list = self::data_list($radius_list, 0.3048);
    	$deposited_asphaltene_list = self::data_list($deposited_asphaltene_list, 0.001);

    	$mid_points = collect();
    	if ($radius_list->count() > 0) {
    		for ($i=0; $i < count($radius_list)-1; $i++) {
    			$mid_points->push(($radius_list[$i]+$radius_list[$i+1])/2);
    		}
    	}

    	$intervals = collect();
    	$last_radius = $well_radius;
    	if ($mid_points->count() > 0) {
    		foreach ($mid_points as $key => $value) {
    			if($treatment_radius > $mid_points[$key]) {
    				$intervals->push([$last_radius, $mid_points[$key]]);
    				$last_radius = $mid_points[$key];
    			} else {
    				$intervals->push([$last_radius, $treatment_radius]);
    				break;
    			}
    		}
    	}

    	if($treatment_radius > $mid_points->last()) {
    		$intervals->push([$mid_points->last(), $treatment_radius]);
    	}

    	$total_asphaltene_mass = 0;
    	$asphaltene_mass = collect();
    	if ($intervals->count() > 0) {
    		for ($i=0; $i < count($intervals); $i++) {
    			$asphaltene_mass->push((pi()*($intervals[$i][1]**2 - $intervals[$i][0]**2)*$initial_porosity*$netpay*(1-$water_saturation)*$asphaltene_density)*($deposited_asphaltene_list[$i]));
    			$total_asphaltene_mass += isset($asphaltene_mass[$i]) ? $asphaltene_mass[$i] : [];
    		}
    	}

    	$total_asphaltene_mass = $total_asphaltene_mass <= 0 ? 1 : $total_asphaltene_mass;
    	$asphaltene_dissolution_capacity = $asphaltene_dissolution_capacity <= 0 ? 1 : $asphaltene_dissolution_capacity;

    	$injected_volume = collect();
    	if ($intervals->count() > 0) {
    		for($i = count($intervals)-1; $i >= 0; $i--){
			#$injected_volume ->push($asphaltene_mass[$i]*$asphaltene_density);
    			$injected_volume ->push(pi()*($intervals[$i][1]**2 - $intervals[$i][0]**2) * $netpay * $initial_porosity);
    		}
    	}

			$treatment_volume = $injected_volume->sum()+($total_asphaltene_mass/$asphaltene_dissolution_capacity);
    	$treatment_volume *= 6.28981;

    	$efficiencies_list = collect();
    	$efficiencies_list->push($min_efficiency);
    	$efficiencies_list->push(($min_efficiency + $max_efficiency)/2);
    	$efficiencies_list->push($max_efficiency);

    	$max_dissolution = collect();
    	foreach ($efficiencies_list as $i => $value) {
    		$aux = collect();
    		foreach ($intervals as $j => $value) {
    			$aux->push($injected_volume[$j] * $asphaltene_dissolution_capacity * $efficiencies_list[$i]);
    		}
    		$max_dissolution->push($aux);
    	}
    	$remanent_asphaltene_dissolution_potencial = collect();
    	$final_remanent_asphaltene_mass = collect();
    	$posttreatment_porosity = collect();
    	$posttreatment_permeability = collect();

    	foreach ($efficiencies_list as $i => $value) {
    		$aux = collect();
    		$aux2 = collect();
    		$remanent_asphaltene_mass = collect();

    		if ($asphaltene_mass->count() > 0) {
    			if($asphaltene_mass[0] > $max_dissolution[$i][0]) {
    				$aux->push($asphaltene_mass[0] - $max_dissolution[$i][0]);
    				$aux2->push( 0 );
    			} else {
    				$aux->push( 0 );
    				$aux2->push( $max_dissolution[$i][0] - $asphaltene_mass[0] );
    			}


    			for ($j=1; $j < count($intervals); $j++) {
    				if($asphaltene_mass[$j] > $aux2[$j-1]) {
    					$aux->push( $asphaltene_mass[$j] - $aux2[$j-1] );
    					$aux2->push( 0 );
    				} else {
    					$aux->push( 0 );
    					$aux2->push( $aux2[$j-1] - $asphaltene_mass[$j] );
    				}
    			}

    			$remanent_asphaltene_mass->push($aux);


    			for ($j=1; $j < count($injected_volume); $j++) {
    				$aux = collect();
    				$aux2 = collect();
    				if($remanent_asphaltene_mass[$j-1][0] > $max_dissolution[$i][$j]) {
    					$aux->push( $remanent_asphaltene_mass[$j-1][0] -$max_dissolution[$i][$j] );
    					$aux2->push( 0 );
    				} else {
    					$aux->push( 0 );
    					$aux2->push(  $max_dissolution[$i][$j] - $remanent_asphaltene_mass[$j-1][0] );
    				}

    				for ($k=1; $k < count($intervals)-$j; $k++) {
    					if($remanent_asphaltene_mass[$j-1][$k] > $aux2[$k-1]) {
    						$aux->push( $remanent_asphaltene_mass[$j-1][$k] - $aux2[$k-1] );
    						$aux2->push( 0 );
    					} else {
    						$aux->push( 0 );
    						$aux2->push($aux2[$k-1] - $remanent_asphaltene_mass[$j-1][$k]);
    					}
    				}

    				$remanent_asphaltene_mass->push($aux);

    			}
    		}


    		$final_remanent_asphaltene_mass->push($remanent_asphaltene_mass);
    	}



    	$aux5 = collect();
    	foreach ($efficiencies_list as $i => $value) {
    		$aux4 = [];
    		foreach ($injected_volume as $j => $value) {
    			array_unshift($aux4, $final_remanent_asphaltene_mass[$i][$j]->last());
    		}
    		$aux5->push($aux4);
    	}
    	$remanent_asphaltene_mass = $aux5;

    	foreach ($efficiencies_list as $i => $value) {
    		$aux = collect();
    		foreach ($intervals as $j => $value) {
    			$aux->push($porosity_list[$j] + (($initial_porosity-$porosity_list[$j])*($asphaltene_mass[$j]-$remanent_asphaltene_mass[$i][$j])/$asphaltene_mass[$j]));
    		}
    		$posttreatment_porosity->push($aux);
    	}

    	foreach ($efficiencies_list as $i => $value) {
    		$aux = collect();
    		foreach ($intervals as $j => $value) {
    			$aux->push($initial_permeability*(($posttreatment_porosity[$i][$j]/$initial_porosity)**2));
    		}
    		$posttreatment_permeability->push($aux);
    	}

    	$skin= collect();
    	foreach ($efficiencies_list as $key => $value) {
    		$aux = 1;
    		$count = 0;
    		foreach ($intervals as $j => $value) {
    			$count += 1;
    			$aux *= $posttreatment_permeability[$key][$j];
    		}
    		$count = ($count == 0) ? 1 : $count;
    		$perm_post_treatment = pow($aux, (1/$count));
    		$well_radius = ($well_radius == 0) ? 1 : $well_radius;
    		$skin->push((($initial_permeability/$perm_post_treatment)-1)*log($treatment_radius/$well_radius));
    	}

    	$radius = collect();
    	foreach ($intervals as $i => $value) {
    		$radius->push($radius_list[$i] * 3.28084);
    	}

    	$posttreatment_permeability_graph = collect();
    	$posttreatment_porosity_graph = collect();

    	foreach ($efficiencies_list as $i => $value) {
    		$aux = collect();
    		$aux2 = collect();
    		foreach ($intervals as $j => $value) {
    			$aux->push([round($radius[$j], 4), $posttreatment_permeability[$i][$j]]);
    			$aux2->push([round($radius[$j], 4), $posttreatment_porosity[$i][$j]]);
    		}
    		$posttreatment_permeability_graph->push($aux);
    		$posttreatment_porosity_graph->push($aux2);
    	}

		# Units convertion
    	$treatment_radius /= 0.3048;

    	return collect([$treatment_volume, $skin,  $remanent_asphaltene_mass, $posttreatment_porosity_graph, $posttreatment_permeability_graph, $radius, $efficiencies_list]);
    }

    static function data_list($list, $val){
    	foreach ($list as $key => $value) {
    		$list[$key] *= $val;
    	}

    	return $list;
    }
}
