<?php
namespace App\Traits;



Class PvtTraits
{
	public function editPvt($data)
	{
		
		if($data != null)
		{
			$pressure = $this->stringToArray($data->pressure);
	        $uo = $this->stringToArray($data->uo);
	        $ug = $this->stringToArray($data->ug);
	        $uw = $this->stringToArray($data->uw);
	        $bo = $this->stringToArray($data->bo);
	        $bg = $this->stringToArray($data->bg);
	        $bw = $this->stringToArray($data->bw);
	        $rs = $this->stringToArray($data->rs);
	        $rv = $this->stringToArray($data->rv);
	        
	        
	        $datos = collect();
	        for ($i=0; $i < count($pressure); $i++) { 
	        	$datos[$i] = collect();
	        	$datos[$i]->push($pressure[$i]);
	        	$datos[$i]->push($uo[$i]);
	        	$datos[$i]->push($ug[$i]);
	        	$datos[$i]->push($uw[$i]);
	        	$datos[$i]->push($bo[$i]);
	        	$datos[$i]->push($bg[$i]);
	        	$datos[$i]->push($bw[$i]);
	        	$datos[$i]->push($rs[$i]);
	        	$datos[$i]->push($rv[$i]);
	        }

	        return $datos;
		}else{
			return null;
			
		}
	}


	public function saveAndUpdate($request, $action)
	{
		if(!empty(json_decode($request->pvt_table)))
		{
			$pvt_table = json_decode($request->pvt_table);
			
			$pressure = collect();
			$uo = collect();
			$ug = collect();
			$uw = collect();
			$bo = collect();
			$bg = collect();
			$bw = collect();
			$rs = collect();
			$rv = collect();
			

			foreach ($pvt_table as $data) {
				if($data[0] != null || $data[2] != null){
					$pressure->push($data[0]);
					$uo->push($data[1]);
					$ug->push($data[2]);
					$uw->push($data[3]);
					$bo->push($data[4]);
					$bg->push($data[5]);
					$bw->push($data[6]);
					$rs->push($data[7]);
					$rv->push($data[8]);
				}
			}
					//$test = collect([$pressure, $uo, $ug, $uw, $bo, $bg, $bw, $rs, $rv]);
					//dd(collect($uo)->implode(','));
					//dd($test);
			if(isset($request->descripcion))
			{
				$action->descripcion = $request->descripcion;
			}

			if($request->formacion_id)
			{
	        	$action->formacion_id = $request->formacion_id;

			} elseif($request->formacionxpozos_id) {
	        	$action->formacionxpozos_id = $request->formacionxpozos_id;
			}

			

			$action->saturation_pressure = $request->saturation_pressure;
	        $action->pressure = collect($pressure)->implode(',');
	        $action->uo = collect($uo)->implode(',');
	        $action->ug = collect($ug)->implode(',');
	        $action->uw = collect($uw)->implode(',');
	        $action->bo = collect($bo)->implode(',');
	        $action->bg = collect($bg)->implode(',');
	        $action->bw = collect($bw)->implode(',');
	        $action->rs = collect($rs)->implode(',');
	        $action->rv = collect($rv)->implode(',');
	        $action->save();
	    }
	}


	public function stringToArray($data)
    { 

    	return json_decode('[' . $data . ']', true);
    }

}