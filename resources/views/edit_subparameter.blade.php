@extends('layouts.parameters')
@section('title', 'IFDM Project')

@section('parameters')
@include('layouts/modal_error')

{!!Form::model($multiparametrico, array('route' => array('AddMPAnalysis.update', $multiparametrico->id), 'method' => 'PATCH', 'id' => 'form'), array('role' => 'form'))!!}
{!! Form::text('count',null, ['class' =>'form-control hidden', 'id' => 'count']) !!} 
<div name="MS" id="MS">
   <div class="panel panel-default">
      <div class="panel-heading">
         <h4>Mineral Scales</h4>
      </div>

      <div class="panel-body">
         <div class="row">
            <div class="col-md-12">
               <div class="form-inline" role="form">
                  <div class="form-group">
                     <a target="_blank" href="{{ URL::route('histo', ['subp' => 1])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="images/historics.png" width="25" height="25"/>
                     </button></a>
                     <a target="_blank" href="{{ URL::route('freq', ['subp' => 1])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="images/est.png" width="25" height="25"/>
                     </button></a>
                     <button type="button" class="btn btn-default" aria-label="Left Align" id="popover1">
                     <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>
                     </button>
                     <a target="_blank" href="{{ URL::route('Geor', ['subp' => 1, 'multi' => $multiparametrico->id])}}"><button type="button" class="btn btn-default" aria-label="Left Align">
                     <span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span>
                     </button></a>&nbsp&nbsp&nbsp
                     {!! Form::label('SIC', 'Scale Index Of CaCO3') !!}
                  </div>
                  <div class="pull-right">
                     {!! Form::checkbox('weight_1',null,null, array('id'=>'weight_1', 'name'=>'weight_1', 'class' => 'check_weight')) !!}
                     {!! Form::label('available', 'Available') !!}
                     {!! Form::hidden('weight_1_hidden', '', array('class' => 'form-control', 'id' => 'weight_1_hidden')) !!}
                  </div>
               </div>
            </div>
         </div>

         <br>

         <div id="weight_1_div" class="weight_1_div">
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('MS1') ? 'has-error' : ''}}">
                     {!! Form::label('value', 'Value') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     <div class="input-group">
                        {!! Form::text('MS1',$multiparametrico->ms1, ['placeholder' => '-', 'class' =>'form-control validate value_edit', 'id' => 'MS1']) !!} 
                        <span class="input-group-addon" id="basic-addon2">-</span>
                     </div>
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('dateMS1') ? 'has-error' : ''}}">
                     {!! Form::label('date', 'Monitoring Date') !!}
                     {!! Form::date('dateMS1', $multiparametrico->date_ms1, ['class' =>'form-control validate value_edit', 'id' => 'dateMS1']); !!}
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('MS1comment') ? 'has-error' : ''}}">
                     {!! Form::label('comment', 'Comment') !!}
                     {!! Form::text('MS1comment',$multiparametrico->comment_ms1, ['class' =>'form-control validate', 'id' => 'MS1comment']) !!}   
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p10_ms1') ? 'has-error' : ''}}">
                     {!! Form::label('p10', 'p10') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p10_ms1',$multiparametrico->p10_ms1, ['class' =>'form-control validate', 'id' => 'p10_ms1']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p90_ms1') ? 'has-error' : ''}}">
                     {!! Form::label('p90', 'p90') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p90_ms1',$multiparametrico->p90_ms1, ['class' =>'form-control validate', 'id' => 'p90_ms1']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('ms_scale_index_caco3') ? 'has-error' : ''}}">
                     {!! Form::label('Weight', 'Weight') !!}
                     @if (count($subparameters_weight))
                     {!! Form::text('ms_scale_index_caco3',$subparameters_weight->ms_scale_index_caco3, ['class' =>'form-control validate weight_count', 'id' => 'weight_1_value']) !!}  
                     @else
                     {!! Form::text('ms_scale_index_caco3',$subparameters_weight['ms_scale_index_caco3'], ['class' =>'form-control validate weight_count', 'id' => 'weight_1_value']) !!} 
                     @endif  
                  </div>
               </div>
            </div>
         </div>

         <hr>

         <div class="row">
            <div class="col-md-12">
               <div class="form-inline" role="form">
                  <div class="form-group">
                     <a target="_blank" href="{{ URL::route('histo', ['subp' => 2])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="images/historics.png" width="25" height="25"/>
                     </button></a>
                     <a target="_blank" href="{{ URL::route('freq', ['subp' => 2])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="images/est.png" width="25" height="25"/>
                     </button></a>
                     <button type="button" class="btn btn-default" aria-label="Left Align" id="popover2">
                     <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>
                     </button>
                     <a target="_blank" href="{{ URL::route('Geor', ['subp' => 2, 'multi' => $multiparametrico->id])}}"><button type="button" class="btn btn-default" aria-label="Left Align">
                     <span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span>
                     </button></a>&nbsp&nbsp&nbsp
                     {!! Form::label('BaSO4', 'Scale Index Of BaSO4') !!}
                  </div>
                  <div class="pull-right">
                     {!! Form::checkbox('weight_2',null,null, array('id'=>'weight_2', 'name'=>'weight_2', 'class' => 'check_weight')) !!}
                     {!! Form::label('available', 'Available') !!}
                     {!! Form::hidden('weight_2_hidden', '', array('class' => 'form-control', 'id' => 'weight_2_hidden')) !!}
                  </div>
               </div>
            </div>
         </div>

         <br>  

         <div id="weight_2_div" class="weight_2_div">
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('MS2') ? 'has-error' : ''}}">
                     {!! Form::label('value', 'Value') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     <div class="input-group">
                        {!! Form::text('MS2',$multiparametrico->ms2, ['placeholder' => '-', 'class' =>'form-control validate pull-right value_edit', 'id' => 'MS2']) !!}  
                        <span class="input-group-addon" id="basic-addon2">-</span>
                     </div>
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('dateMS2') ? 'has-error' : ''}}">
                     {!! Form::label('date', 'Monitoring Date') !!}
                     {!! Form::date('dateMS2', $multiparametrico->date_ms2, ['class' =>'form-control validate value_edit', 'id' => 'dateMS2']); !!}
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('MS2comment') ? 'has-error' : ''}}">
                     {!! Form::label('comment', 'Comment') !!}
                     {!! Form::text('MS2comment',$multiparametrico->comment_ms2, ['class' =>'form-control validate', 'id' => 'MS2comment']) !!}    
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p10_ms2') ? 'has-error' : ''}}">
                     {!! Form::label('p10', 'p10') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p10_ms2',$multiparametrico->p10_ms2, ['class' =>'form-control validate', 'id' => 'p10_ms2']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p90_ms2') ? 'has-error' : ''}}">
                     {!! Form::label('p90', 'p90') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p90_ms2',$multiparametrico->p90_ms2, ['class' =>'form-control validate', 'id' => 'p90_ms2']) !!}   
                  </div>
               </div>
               <div  class="col-md-4">
                  <div class="form-group {{$errors->has('ms_scale_index_baso4') ? 'has-error' : ''}}">
                     {!! Form::label('Weight', 'Weight') !!}
                     @if (count($subparameters_weight))
                     {!! Form::text('ms_scale_index_baso4',$subparameters_weight->ms_scale_index_baso4, ['class' =>'form-control validate weight_count', 'id' => 'weight_2_value']) !!}  
                     @else
                     {!! Form::text('ms_scale_index_baso4',$subparameters_weight['ms_scale_index_baso4'], ['class' =>'form-control validate weight_count', 'id' => 'weight_2_value']) !!} 
                     @endif 
                  </div>
               </div>
            </div>
         </div>

         <hr>

         <div class="row">
            <div class="col-md-12">
               <div class="form-inline" role="form">
                  <div class="form-group">
                     <a target="_blank" href="{{ URL::route('histo', ['subp' => 3])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="images/historics.png" width="25" height="25"/>
                     </button></a>
                     <a target="_blank" href="{{ URL::route('freq', ['subp' => 3])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="images/est.png" width="25" height="25"/>
                     </button></a>
                     <button type="button" class="btn btn-default" aria-label="Left Align" id="popover3">
                     <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>
                     </button>
                     <a target="_blank" href="{{ URL::route('Geor', ['subp' => 3, 'multi' => $multiparametrico->id])}}"><button type="button" class="btn btn-default" aria-label="Left Align">
                     <span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span>
                     </button></a>&nbsp&nbsp&nbsp
                     {!! Form::label('SIS', 'Scale Index Of Iron Scales') !!}
                  </div>
                  <div class="pull-right">
                     {!! Form::checkbox('weight_3',null,null, array('id'=>'weight_3', 'name'=>'weight_3', 'class' => 'check_weight')) !!}
                     {!! Form::label('available', 'Available') !!}
                     {!! Form::hidden('weight_3_hidden', '', array('class' => 'form-control', 'id' => 'weight_3_hidden')) !!}
                  </div>
               </div>
            </div>
         </div>

         <br>

         <div id="weight_3_div" class="weight_3_div">
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('MS3') ? 'has-error' : ''}}">
                     {!! Form::label('value', 'Value') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     <div class="input-group">
                        {!! Form::text('MS3',$multiparametrico->ms3, ['placeholder' => '-', 'class' =>'form-control validate pull-right value_edit', 'id' => 'MS3']) !!} 
                        <span class="input-group-addon" id="basic-addon2">-</span>
                     </div>
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('dateMS3') ? 'has-error' : ''}}">
                     {!! Form::label('date', 'Monitoring Date') !!}
                     {!! Form::date('dateMS3', $multiparametrico->date_ms3, ['class' =>'form-control validate value_edit', 'id' => 'dateMS3']); !!}
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('MS3comment') ? 'has-error' : ''}}">
                     {!! Form::label('comment', 'Comment') !!}
                     {!! Form::text('MS3comment',$multiparametrico->comment_ms3, ['class' =>'form-control validate', 'id' => 'MS3comment']) !!}  
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p10_ms3') ? 'has-error' : ''}}">
                     {!! Form::label('p10', 'p10') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p10_ms3',$multiparametrico->p10_ms3, ['class' =>'form-control validate', 'id' => 'p10_ms3']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p90_ms3') ? 'has-error' : ''}}">
                     {!! Form::label('p90', 'p90') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p90_ms3',$multiparametrico->p90_ms3, ['class' =>'form-control validate', 'id' => 'p90_ms3']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('ms_scale_index_iron_scales') ? 'has-error' : ''}}">
                     {!! Form::label('Weight', 'Weight') !!}
                     @if (count($subparameters_weight))
                     {!! Form::text('ms_scale_index_iron_scales',$subparameters_weight->ms_scale_index_iron_scales, ['class' =>'form-control validate weight_count', 'id' => 'weight_3_value']) !!}  
                     @else
                     {!! Form::text('ms_scale_index_iron_scales',$subparameters_weight['ms_scale_index_iron_scales'], ['class' =>'form-control validate weight_count', 'id' => 'weight_3_value']) !!} 
                     @endif    
                  </div>
               </div>
            </div>
         </div>

         <hr>

         <div class="row">
            <div class="col-md-12">
               <div class="form-inline" role="form">
                  <div class="form-group">
                     <a target="_blank" href="{{ URL::route('histo', ['subp' => 4])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="images/historics.png" width="25" height="25"/>
                     </button></a>
                     <a target="_blank" href="{{ URL::route('freq', ['subp' => 4])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="images/est.png" width="25" height="25"/>
                     </button></a>
                     <button type="button" class="btn btn-default" aria-label="Left Align" id="popover4">
                     <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>
                     </button>
                     <a target="_blank" href="{{ URL::route('Geor', ['subp' => 4, 'multi' => $multiparametrico->id])}}"><button type="button" class="btn btn-default" aria-label="Left Align">
                     <span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span>
                     </button></a>&nbsp&nbsp&nbsp
                     {!! Form::label('', '[Ca]: Calcium Concentration On Backflow Samples') !!}
                  </div>
                  <div class="pull-right">
                     {!! Form::checkbox('weight_4',null,null, array('id'=>'weight_4', 'name'=>'weight_4', 'class' => 'check_weight')) !!}
                     {!! Form::label('available', 'Available') !!}
                     {!! Form::hidden('weight_4_hidden', '', array('class' => 'form-control', 'id' => 'weight_4_hidden')) !!}
                  </div>
               </div>
            </div>
         </div>

         <br> 

         <div id="weight_4_div" class="weight_4_div">
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group">
                     {!! Form::label('value', 'Value') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     <div class="input-group {{$errors->has('MS4') ? 'has-error' : ''}}">
                        {!! Form::text('MS4',$multiparametrico->ms4, ['placeholder' => 'ppm', 'class' =>'form-control validate pull-right value_edit', 'id' => 'MS4']) !!}   
                        <span class="input-group-addon" id="basic-addon2">ppm</span>
                     </div>
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('dateMS4') ? 'has-error' : ''}}">
                     {!! Form::label('date', 'Monitoring Date') !!}
                     {!! Form::date('dateMS4', $multiparametrico->date_ms4, ['class' =>'form-control validate value_edit', 'id' => 'dateMS4']); !!}
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('MS4comment') ? 'has-error' : ''}}">
                     {!! Form::label('comment', 'Comment') !!}
                     {!! Form::text('MS4comment',$multiparametrico->comment_ms4, ['class' =>'form-control validate', 'id' => 'MS4comment']) !!}   
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p10_ms4') ? 'has-error' : ''}}">
                     {!! Form::label('p10', 'p10') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p10_ms4',$multiparametrico->p10_ms4, ['class' =>'form-control validate', 'id' => 'p10_ms4']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p90_ms4') ? 'has-error' : ''}}">
                     {!! Form::label('p90', 'p90') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p90_ms4',$multiparametrico->p90_ms4, ['class' =>'form-control validate', 'id' => 'p90_ms4']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('ms_calcium_concentration') ? 'has-error' : ''}}">
                     {!! Form::label('Weight', 'Weight') !!}
                     @if (count($subparameters_weight))
                     {!! Form::text('ms_calcium_concentration',$subparameters_weight->ms_calcium_concentration, ['class' =>'form-control validate weight_count', 'id' => 'weight_4_value']) !!}  
                     @else
                     {!! Form::text('ms_calcium_concentration',$subparameters_weight['ms_calcium_concentration'], ['class' =>'form-control validate weight_count', 'id' => 'weight_4_value']) !!} 
                     @endif   
                  </div>
               </div>
            </div>
         </div>

         <hr>

         <div class="row">
            <div class="col-md-12">
               <div class="form-inline" role="form">
                  <div class="form-group">
                     <a target="_blank" href="{{ URL::route('histo', ['subp' => 5])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="images/historics.png" width="25" height="25"/>
                     </button></a>
                     <a target="_blank" href="{{ URL::route('freq', ['subp' => 5])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="images/est.png" width="25" height="25"/>
                     </button></a>
                     <button type="button" class="btn btn-default" aria-label="Left Align" id="popover5">
                     <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>
                     </button>
                     <a target="_blank" href="{{ URL::route('Geor', ['subp' => 5, 'multi' => $multiparametrico->id])}}"><button type="button" class="btn btn-default" aria-label="Left Align">
                     <span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span>
                     </button></a>&nbsp&nbsp&nbsp
                     {!! Form::label('', '[Ba]: Barium Concentration On Backflow Samples') !!}
                  </div>
                  <div class="pull-right">
                     {!! Form::checkbox('weight_5',null,null, array('id'=>'weight_5', 'name'=>'weight_5', 'class' => 'check_weight')) !!}
                     {!! Form::label('available', 'Available') !!}
                     {!! Form::hidden('weight_5_hidden', '', array('class' => 'form-control', 'id' => 'weight_5_hidden')) !!}
                  </div>
               </div>
            </div>
         </div>

         <br>  

         <div id="weight_5_div" class="weight_5_div">
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('MS5') ? 'has-error' : ''}}">
                     {!! Form::label('value', 'Value') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     <div class="input-group">
                        {!! Form::text('MS5',$multiparametrico->ms5, ['placeholder' => 'ppm', 'class' =>'form-control validate pull-right value_edit', 'id' => 'MS5']) !!}  
                        <span class="input-group-addon" id="basic-addon2">ppm</span>
                     </div>
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('dateMS5') ? 'has-error' : ''}}">
                     {!! Form::label('date', 'Monitoring Date') !!}
                     {!! Form::date('dateMS5', $multiparametrico->date_ms5, ['class' =>'form-control validate value_edit', 'id' => 'dateMS5']); !!}
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('MS5comment') ? 'has-error' : ''}}">
                     {!! Form::label('comment', 'Comment') !!}
                     {!! Form::text('MS5comment',$multiparametrico->comment_ms5, ['class' =>'form-control validate', 'id' => 'MS5comment']) !!}   
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p10_ms5') ? 'has-error' : ''}}">
                     {!! Form::label('p10', 'p10') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p10_ms5',$multiparametrico->p10_ms5, ['class' =>'form-control validate', 'id' => 'p10_ms5']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p90_ms5') ? 'has-error' : ''}}">
                     {!! Form::label('p90', 'p90') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p90_ms5',$multiparametrico->p90_ms5, ['class' =>'form-control validate', 'id' => 'p90_ms5']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('ms_barium_concentration') ? 'has-error' : ''}}">
                     {!! Form::label('Weight', 'Weight') !!}
                     @if (count($subparameters_weight))
                     {!! Form::text('ms_barium_concentration',$subparameters_weight->ms_barium_concentration, ['class' =>'form-control validate weight_count', 'id' => 'weight_5_value']) !!}  
                     @else
                     {!! Form::text('ms_barium_concentration',$subparameters_weight['ms_barium_concentration'], ['class' =>'form-control validate weight_count', 'id' => 'weight_5_value']) !!} 
                     @endif  
                  </div>
               </div>
            </div>
         </div>
         <hr>
      </div>
   </div>
</div>


<div name="FB" id="FB">
   <div class="panel panel-default">
      <div class="panel-heading">
         <h4>Fine Blockage</h4>
      </div>

      <div class="panel-body">
         <div class="row">
            <div class="col-md-12">
               <div class="form-inline" role="form">
                  <div class="form-group">
                     <a target="_blank" href="{{ URL::route('histo', ['subp' => 6])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="images/historics.png" width="25" height="25"/>
                     </button></a>
                     <a target="_blank" href="{{ URL::route('freq', ['subp' => 6])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="images/est.png" width="25" height="25"/>
                     </button></a>
                     <button type="button" class="btn btn-default" aria-label="Left Align" id="popover6">
                     <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>
                     </button>
                     <a target="_blank" href="{{ URL::route('Geor', ['subp' => 6, 'multi' => $multiparametrico->id])}}"><button type="button" class="btn btn-default" aria-label="Left Align">
                     <span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span>
                     </button></a>&nbsp&nbsp&nbsp
                     {!! Form::label('ACW', '[Al]: Aluminum Concentration On Produced Water') !!}
                  </div>
                  <div class="pull-right">
                     {!! Form::checkbox('weight_6',null,null, array('id'=>'weight_6', 'name'=>'weight_6', 'class' => 'check_weight')) !!}
                     {!! Form::label('available', 'Available') !!}
                     {!! Form::hidden('weight_6_hidden', '', array('class' => 'form-control', 'id' => 'weight_6_hidden')) !!}
                  </div>
               </div>
            </div>
         </div>

         <br>

         <div id="weight_6_div" class="weight_6_div">
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group">
                     {!! Form::label('value', 'Value') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     <div class="input-group {{$errors->has('FB1') ? 'has-error' : ''}}">
                        {!! Form::text('FB1',$multiparametrico->fb1, ['placeholder' => 'ppm', 'class' =>'form-control validate pull-right value_edit', 'id' => 'FB1']) !!}
                        <span class="input-group-addon" id="basic-addon2">ppm</span>
                     </div>
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('dateFB1') ? 'has-error' : ''}}">
                     {!! Form::label('date', 'Monitoring Date') !!}
                     {!! Form::date('dateFB1', $multiparametrico->date_fb1, ['class' =>'form-control validate value_edit', 'id' => 'dateFB1']); !!}
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('FB1comment') ? 'has-error' : ''}}">
                     {!! Form::label('comment', 'Comment') !!}
                     {!! Form::text('FB1comment',$multiparametrico->comment_fb1, ['class' =>'form-control validate', 'id' => 'FB1comment']) !!}    
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p10_fb1') ? 'has-error' : ''}}">
                     {!! Form::label('p10', 'p10') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p10_fb1',$multiparametrico->p10_fb1, ['class' =>'form-control validate', 'id' => 'p10_fb1']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p90_fb1') ? 'has-error' : ''}}">
                     {!! Form::label('p90', 'p90') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p90_fb1',$multiparametrico->p90_fb1, ['class' =>'form-control validate', 'id' => 'p90_fb1']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('fb_aluminum_concentration') ? 'has-error' : ''}}">
                     {!! Form::label('Weight', 'Weight') !!}
                     @if (count($subparameters_weight))
                     {!! Form::text('fb_aluminum_concentration',$subparameters_weight->fb_aluminum_concentration, ['class' =>'form-control validate weight_count', 'id' => 'weight_6_value']) !!}  
                     @else
                     {!! Form::text('fb_aluminum_concentration',$subparameters_weight['fb_aluminum_concentration'], ['class' =>'form-control validate weight_count', 'id' => 'weight_6_value']) !!} 
                     @endif 
                  </div>
               </div>
            </div>
         </div>

         <hr>

         <div class="row">
            <div class="col-md-12">
               <div class="form-inline" role="form">
                  <div class="form-group">
                     <a target="_blank" href="{{ URL::route('histo', ['subp' => 7])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="images/historics.png" width="25" height="25"/>
                     </button></a>
                     <a target="_blank" href="{{ URL::route('freq', ['subp' => 7])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="images/est.png" width="25" height="25"/>
                     </button></a>
                     <button type="button" class="btn btn-default" aria-label="Left Align" id="popover7">
                     <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>
                     </button>
                     <a target="_blank" href="{{ URL::route('Geor', ['subp' => 7, 'multi' => $multiparametrico->id])}}"><button type="button" class="btn btn-default" aria-label="Left Align">
                     <span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span>
                     </button></a>&nbsp&nbsp&nbsp
                     {!! Form::label('SCW', '[Si]: Silicon Concentration On Produced Water') !!}
                  </div>
                  <div class="pull-right">
                     {!! Form::checkbox('weight_7',null,null, array('id'=>'weight_7', 'name'=>'weight_7', 'class' => 'check_weight')) !!}
                     {!! Form::label('available', 'Available') !!}
                     {!! Form::hidden('weight_7_hidden', '', array('class' => 'form-control', 'id' => 'weight_7_hidden')) !!}
                  </div>
               </div>
            </div>
         </div>

         <br>

         <div id="weight_7_div" class="weight_7_div">
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group">
                     {!! Form::label('value', 'Value') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     <div class="input-group {{$errors->has('FB2') ? 'has-error' : ''}}">
                        {!! Form::text('FB2',$multiparametrico->fb2, ['placeholder' => 'ppm', 'class' =>'form-control validate pull-right value_edit', 'id' => 'FB2']) !!}
                        <span class="input-group-addon" id="basic-addon2">ppm</span>
                     </div>
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('dateFB2') ? 'has-error' : ''}}">
                     {!! Form::label('date', 'Monitoring Date') !!}
                     {!! Form::date('dateFB2', $multiparametrico->date_fb2, ['class' =>'form-control validate value_edit', 'id' => 'dateFB2']); !!}
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('FB2comment') ? 'has-error' : ''}}">
                     {!! Form::label('comment', 'Comment') !!}
                     {!! Form::text('FB2comment',$multiparametrico->comment_fb2, ['class' =>'form-control validate', 'id' => 'FB2comment']) !!}    
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p10_fb2') ? 'has-error' : ''}}">
                     {!! Form::label('p10', 'p10') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p10_fb2',$multiparametrico->p10_fb2, ['class' =>'form-control validate', 'id' => 'p10_fb2']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p90_fb2') ? 'has-error' : ''}}">
                     {!! Form::label('p90', 'p90') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p90_fb2',$multiparametrico->p90_fb2, ['class' =>'form-control validate', 'id' => 'p90_fb2']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('fb_silicon_concentration') ? 'has-error' : ''}}">
                     {!! Form::label('Weight', 'Weight') !!}
                     @if (count($subparameters_weight))
                     {!! Form::text('fb_silicon_concentration',$subparameters_weight->fb_silicon_concentration, ['class' =>'form-control validate weight_count', 'id' => 'weight_7_value']) !!}  
                     @else
                     {!! Form::text('fb_silicon_concentration',$subparameters_weight['fb_silicon_concentration'], ['class' =>'form-control validate weight_count', 'id' => 'weight_7_value']) !!} 
                     @endif 
                  </div>
               </div>
            </div>
         </div>

         <hr>

         <div class="row">
            <div class="col-md-12">
               <div class="form-inline" role="form">
                  <div class="form-group">
                     <a target="_blank" href="{{ URL::route('histo', ['subp' => 8])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="images/historics.png" width="25" height="25"/>
                     </button></a>
                     <a target="_blank" href="{{ URL::route('freq', ['subp' => 8])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="images/est.png" width="25" height="25"/>
                     </button></a>
                     <button type="button" class="btn btn-default" aria-label="Left Align" id="popover8">
                     <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>
                     </button>
                     <a target="_blank" href="{{ URL::route('Geor', ['subp' => 8, 'multi' => $multiparametrico->id])}}"><button type="button" class="btn btn-default" aria-label="Left Align">
                     <span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span>
                     </button></a>&nbsp&nbsp&nbsp
                     {!! Form::label('CRF', 'Critical Radius Factor Rc') !!}
                  </div>
                  <div class="pull-right">
                     {!! Form::checkbox('weight_8',null,null, array('id'=>'weight_8', 'name'=>'weight_8', 'class' => 'check_weight')) !!}
                     {!! Form::label('available', 'Available') !!}
                     {!! Form::hidden('weight_8_hidden', '', array('class' => 'form-control', 'id' => 'weight_8_hidden')) !!}
                  </div>
               </div>
            </div>
         </div>

         <br>

         <div id="weight_8_div" class="weight_8_div">
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group">
                     {!! Form::label('value', 'Value') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     <div class="input-group {{$errors->has('FB3') ? 'has-error' : ''}}">
                        {!! Form::text('FB3',$multiparametrico->fb3, ['placeholder' => 'ft', 'class' =>'form-control validate pull-right value_edit', 'id' => 'FB3']) !!} 
                        <span class="input-group-addon" id="basic-addon2">ft</span>
                     </div>
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('dateFB3') ? 'has-error' : ''}}">
                     {!! Form::label('date', 'Monitoring Date') !!}
                     {!! Form::date('dateFB3', $multiparametrico->date_fb3, ['class' =>'form-control validate value_edit', 'id' => 'dateFB3']); !!}
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('FB3comment') ? 'has-error' : ''}}">
                     {!! Form::label('comment', 'Comment') !!}
                     {!! Form::text('FB3comment',$multiparametrico->comment_fb3, ['class' =>'form-control validate', 'id' => 'FB3comment']) !!}  
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p10_fb3') ? 'has-error' : ''}}">
                     {!! Form::label('p10', 'p10') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p10_fb3',$multiparametrico->p10_fb3, ['class' =>'form-control validate', 'id' => 'p10_fb3']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p90_fb3') ? 'has-error' : ''}}">
                     {!! Form::label('p90', 'p90') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p90_fb3',$multiparametrico->p90_fb3, ['class' =>'form-control validate', 'id' => 'p90_fb3']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('fb_critical_radius_factor') ? 'has-error' : ''}}">
                     {!! Form::label('Weight', 'Weight') !!}
                     @if (count($subparameters_weight))
                     {!! Form::text('fb_critical_radius_factor',$subparameters_weight->fb_critical_radius_factor, ['class' =>'form-control validate weight_count', 'id' => 'weight_8_value']) !!}  
                     @else
                     {!! Form::text('fb_critical_radius_factor',$subparameters_weight['fb_critical_radius_factor'], ['class' =>'form-control validate weight_count', 'id' => 'weight_8_value']) !!} 
                     @endif 
                  </div>
               </div>
            </div>
         </div>

         <hr>

         <div class="row">
            <div class="col-md-12">
               <div class="form-inline" role="form">
                  <div class="form-group">
                     <a target="_blank" href="{{ URL::route('histo', ['subp' => 9])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="images/historics.png" width="25" height="25"/>
                     </button></a>
                     <a target="_blank" href="{{ URL::route('freq', ['subp' => 9])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="images/est.png" width="25" height="25"/>
                     </button></a>
                     <button type="button" class="btn btn-default" aria-label="Left Align" id="popover9">
                     <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>
                     </button>
                     <a target="_blank" href="{{ URL::route('Geor', ['subp' => 9, 'multi' => $multiparametrico->id])}}"><button type="button" class="btn btn-default" aria-label="Left Align">
                     <span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span>
                     </button></a>&nbsp&nbsp&nbsp
                     {!! Form::label('MF', 'Mineralogic Factor') !!}
                  </div>
                  <div class="pull-right">
                     {!! Form::checkbox('weight_9',null,null, array('id'=>'weight_9', 'name'=>'weight_9', 'class' => 'check_weight')) !!}
                     {!! Form::label('available', 'Available') !!}
                     {!! Form::hidden('weight_9_hidden', '', array('class' => 'form-control', 'id' => 'weight_9_hidden')) !!}
                  </div>
               </div>
            </div>
         </div>

         <br>

         <div id="weight_9_div" class="weight_9_div">
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group">
                     {!! Form::label('value', 'Value') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     <div class="input-group {{$errors->has('FB4') ? 'has-error' : ''}}">
                        {!! Form::text('FB4',$multiparametrico->fb4, ['placeholder' => '-', 'class' =>'form-control validate pull-right value_edit', 'id' => 'FB4']) !!}
                        <span class="input-group-addon" id="basic-addon2">-</span>
                     </div>
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('dateFB4') ? 'has-error' : ''}}">
                     {!! Form::label('date', 'Monitoring Date') !!}
                     {!! Form::date('dateFB4', $multiparametrico->date_fb4, ['class' =>'form-control validate value_edit', 'id' => 'dateFB4']); !!}
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('FB4comment') ? 'has-error' : ''}}">
                     {!! Form::label('comment', 'Comment') !!}
                     {!! Form::text('FB4comment',$multiparametrico->comment_fb4, ['class' =>'form-control validate', 'id' => 'FB4comment']) !!}    
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p10_fb4') ? 'has-error' : ''}}">
                     {!! Form::label('p10', 'p10') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p10_fb4',$multiparametrico->p10_fb4, ['class' =>'form-control validate', 'id' => 'p10_fb4']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p90_fb4') ? 'has-error' : ''}}">
                     {!! Form::label('p90', 'p90') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p90_fb4',$multiparametrico->p90_fb4, ['class' =>'form-control validate', 'id' => 'p90_fb4']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('fb_mineralogic_factor') ? 'has-error' : ''}}">
                     {!! Form::label('Weight', 'Weight') !!}
                     @if (count($subparameters_weight))
                     {!! Form::text('fb_mineralogic_factor',$subparameters_weight->fb_mineralogic_factor, ['class' =>'form-control validate weight_count', 'id' => 'weight_9_value']) !!}  
                     @else
                     {!! Form::text('fb_mineralogic_factor',$subparameters_weight['fb_mineralogic_factor'], ['class' =>'form-control validate weight_count', 'id' => 'weight_9_value']) !!} 
                     @endif   
                  </div>
               </div>
            </div>
         </div>

         <hr>

         <div class="row">
            <div class="col-md-12">
               <div class="form-inline" role="form">
                  <div class="form-group">
                     <a target="_blank" href="{{ URL::route('histo', ['subp' => 10])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="images/historics.png" width="25" height="25"/>
                     </button></a>
                     <a target="_blank" href="{{ URL::route('freq', ['subp' => 10])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="images/est.png" width="25" height="25"/>
                     </button></a>
                     <button type="button" class="btn btn-default" aria-label="Left Align" id="popover10">
                     <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>
                     </button>
                     <a target="_blank" href="{{ URL::route('Geor', ['subp' => 10, 'multi' => $multiparametrico->id])}}"><button type="button" class="btn btn-default" aria-label="Left Align">
                     <span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span>
                     </button></a>&nbsp&nbsp&nbsp
                     {!! Form::label('CPF', 'Crushed Proppant Factor') !!}
                  </div>
                  <div class="pull-right">
                     {!! Form::checkbox('weight_10',null,null, array('id'=>'weight_10', 'name'=>'weight_10', 'class' => 'check_weight')) !!}
                     {!! Form::label('available', 'Available') !!}
                     {!! Form::hidden('weight_10_hidden', '', array('class' => 'form-control', 'id' => 'weight_10_hidden')) !!}
                  </div>
               </div>
            </div>
         </div>

         <br>  

         <div id="weight_10_div" class="weight_10_div">
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group">
                     {!! Form::label('value', 'Value') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     <div class="input-group {{$errors->has('FB5') ? 'has-error' : ''}}">
                        {!! Form::text('FB5',$multiparametrico->fb5, ['placeholder' => 'lbs', 'class' =>'form-control validate pull-right value_edit', 'id' => 'FB5']) !!}
                        <span class="input-group-addon" id="basic-addon2">lbs</span>
                     </div>
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('dateFB5') ? 'has-error' : ''}}">
                     {!! Form::label('date', 'Monitoring Date') !!}
                     {!! Form::date('dateFB5', $multiparametrico->date_fb5, ['class' =>'form-control validate value_edit', 'id' => 'dateFB5']); !!}
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('FB5comment') ? 'has-error' : ''}}">
                     {!! Form::label('comment', 'Comment') !!}
                     {!! Form::text('FB5comment',$multiparametrico->comment_fb5, ['class' =>'form-control validate', 'id' => 'FB5comment']) !!}    
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p10_fb5') ? 'has-error' : ''}}">
                     {!! Form::label('p10', 'p10') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p10_fb5',$multiparametrico->p10_fb5, ['class' =>'form-control validate', 'id' => 'p10_fb5']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p90_fb5') ? 'has-error' : ''}}">
                     {!! Form::label('p90', 'p90') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p90_fb5',$multiparametrico->p90_fb5, ['class' =>'form-control validate', 'id' => 'p90_fb5']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('fb_crushed_proppant_factor') ? 'has-error' : ''}}">
                     {!! Form::label('Weight', 'Weight') !!}
                     @if (count($subparameters_weight))
                     {!! Form::text('fb_crushed_proppant_factor',$subparameters_weight->fb_crushed_proppant_factor, ['class' =>'form-control validate weight_count', 'id' => 'weight_10_value']) !!}  
                     @else
                     {!! Form::text('fb_crushed_proppant_factor',$subparameters_weight['fb_crushed_proppant_factor'], ['class' =>'form-control validate weight_count', 'id' => 'weight_10_value']) !!} 
                     @endif   
                  </div>
               </div>
            </div>
         </div>
         <hr>
      </div>
   </div>
</div>


<div name="OS" id="OS">
   <div class="panel panel-default">
      <div class="panel-heading">
         <h4>Organic Scales</h4>
      </div>

      <div class="panel-body">
         <div class="row">
            <div class="col-md-12">
               <div class="form-inline" role="form">
                  <div class="form-group">
                     <a target="_blank" href="{{ URL::route('histo', ['subp' => 11])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="images/historics.png" width="25" height="25"/>
                     </button></a>
                     <a target="_blank" href="{{ URL::route('freq', ['subp' => 11])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="images/est.png" width="25" height="25"/>
                     </button></a>
                     <button type="button" class="btn btn-default" aria-label="Left Align" id="popover11">
                     <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>
                     </button>
                     <a target="_blank" href="{{ URL::route('Geor', ['subp' => 11, 'multi' => $multiparametrico->id])}}"><button type="button" class="btn btn-default" aria-label="Left Align">
                     <span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span>
                     </button></a>&nbsp&nbsp&nbsp
                     {!! Form::label('', 'CII Factor: Colloidal Instability Index') !!}
                  </div>
                  <div class="pull-right">
                     {!! Form::checkbox('weight_11',null,null, array('id'=>'weight_11', 'name'=>'weight_11', 'class' => 'check_weight')) !!}
                     {!! Form::label('available', 'Available') !!}
                     {!! Form::hidden('weight_11_hidden', '', array('class' => 'form-control', 'id' => 'weight_11_hidden')) !!}
                  </div>
               </div>
            </div>
         </div>

         <br>

         <div id="weight_11_div" class="weight_11_div">
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group">
                     {!! Form::label('value', 'Value') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     <div class="input-group {{$errors->has('OS1') ? 'has-error' : ''}}">
                        {!! Form::text('OS1',$multiparametrico->os1, ['placeholder' => '-', 'class' =>'form-control validate value_edit', 'id' => 'OS1']) !!}
                        <span class="input-group-addon" id="basic-addon2">-</span>
                     </div>
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('dateOS1') ? 'has-error' : ''}}">
                     {!! Form::label('date', 'Monitoring Date') !!}
                     {!! Form::date('dateOS1', $multiparametrico->date_os1, ['class' =>'form-control validate value_edit', 'id' => 'dateOS1']); !!}
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('OS1comment') ? 'has-error' : ''}}">
                     {!! Form::label('comment', 'Comment') !!}
                     {!! Form::text('OS1comment',$multiparametrico->comment_os1, ['class' =>'form-control validate', 'id' => 'OS1comment']) !!}    
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p10_os1') ? 'has-error' : ''}}">
                     {!! Form::label('p10', 'p10') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p10_os1',$multiparametrico->p10_os1, ['class' =>'form-control validate', 'id' => 'p10_os1']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p90_os1') ? 'has-error' : ''}}">
                     {!! Form::label('p90', 'p90') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p90_os1',$multiparametrico->p90_os1, ['class' =>'form-control validate', 'id' => 'p90_os1']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('os_cll_factor') ? 'has-error' : ''}}">
                     {!! Form::label('Weight', 'Weight') !!}
                     @if (count($subparameters_weight))
                     {!! Form::text('os_cll_factor',$subparameters_weight->os_cll_factor, ['class' =>'form-control validate weight_count', 'id' => 'weight_11_value']) !!}  
                     @else
                     {!! Form::text('os_cll_factor',$subparameters_weight['os_cll_factor'], ['class' =>'form-control validate weight_count', 'id' => 'weight_11_value']) !!} 
                     @endif    
                  </div>
               </div>
            </div>
         </div>

         <hr>

         <div class="row">
            <div class="col-md-12">
               <div class="form-inline" role="form">
                  <div class="form-group">
                     <a target="_blank" href="{{ URL::route('histo', ['subp' => 13])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="images/historics.png" width="25" height="25"/>
                     </button></a>
                     <a target="_blank" href="{{ URL::route('freq', ['subp' => 13])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="images/est.png" width="25" height="25"/>
                     </button></a>
                     <button type="button" class="btn btn-default" aria-label="Left Align" id="popover12">
                     <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>
                     </button>
                     <a target="_blank" href="{{ URL::route('Geor', ['subp' => 13, 'multi' => $multiparametrico->id])}}"><button type="button" class="btn btn-default" aria-label="Left Align">
                     <span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span>
                     </button></a>&nbsp&nbsp&nbsp
                     {!! Form::label('', 'Compositional Factor: Cumulative Gas Produced') !!}
                  </div>
                  <div class="pull-right">
                     {!! Form::checkbox('weight_12',null,null, array('id'=>'weight_12', 'name'=>'weight_12', 'class' => 'check_weight')) !!}
                     {!! Form::label('available', 'Available') !!}
                     {!! Form::hidden('weight_12_hidden', '', array('class' => 'form-control', 'id' => 'weight_12_hidden')) !!}
                  </div>
               </div>
            </div>
         </div>

         <br>

         <div id="weight_12_div" class="weight_12_div">
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group">
                     {!! Form::label('value', 'Value') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     <div class="input-group {{$errors->has('OS2') ? 'has-error' : ''}}">
                        {!! Form::text('OS2',$multiparametrico->os2, ['placeholder' => 'mMMSCF', 'class' =>'form-control validate value_edit', 'id' => 'OS2']) !!} 
                        <span class="input-group-addon" id="basic-addon2">mMMSCF</span>
                     </div>
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('dateOS2') ? 'has-error' : ''}}">
                     {!! Form::label('date', 'Monitoring Date') !!}
                     {!! Form::date('dateOS2', $multiparametrico->date_os2, ['class' =>'form-control validate value_edit', 'id' => 'dateOS2']); !!}
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('OS2comment') ? 'has-error' : ''}}">
                     {!! Form::label('comment', 'Comment') !!}
                     {!! Form::text('OS2comment',$multiparametrico->comment_os12, ['class' =>'form-control validate', 'id' => 'OS2comment']) !!}
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p10_os2') ? 'has-error' : ''}}">
                     {!! Form::label('p10', 'p10') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p10_os2',$multiparametrico->p10_os2, ['class' =>'form-control validate', 'id' => 'p10_os2']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p90_os2') ? 'has-error' : ''}}">
                     {!! Form::label('p90', 'p90') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p90_os2',$multiparametrico->p90_os2, ['class' =>'form-control validate', 'id' => 'p90_os2']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('os_compositional_factor') ? 'has-error' : ''}}">
                     {!! Form::label('Weight', 'Weight') !!}
                     @if (count($subparameters_weight))
                     {!! Form::text('os_compositional_factor',$subparameters_weight->os_compositional_factor, ['class' =>'form-control validate weight_count', 'id' => 'weight_12_value']) !!}  
                     @else
                     {!! Form::text('os_compositional_factor',$subparameters_weight['os_compositional_factor'], ['class' =>'form-control validate weight_count', 'id' => 'weight_12_value']) !!} 
                     @endif  
                  </div>
               </div>
            </div>
         </div>

         <hr>

         <div class="row">
            <div class="col-md-12">
               <div class="form-inline" role="form">
                  <div class="form-group">
                     <a target="_blank" href="{{ URL::route('histo', ['subp' => 14])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="images/historics.png" width="25" height="25"/>
                     </button></a>
                     <a target="_blank" href="{{ URL::route('freq', ['subp' => 14])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="images/est.png" width="25" height="25"/>
                     </button></a>
                     <button type="button" class="btn btn-default" aria-label="Left Align" id="popover13">
                     <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>
                     </button>
                     <a target="_blank" href="{{ URL::route('Geor', ['subp' => 14, 'multi' => $multiparametrico->id])}}"><button type="button" class="btn btn-default" aria-label="Left Align">
                     <span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span>
                     </button></a>&nbsp&nbsp&nbsp
                     {!! Form::label('', 'Pressure Factor: Number Of Days Below Saturation Pressure') !!}
                  </div>
                  <div class="pull-right">
                     {!! Form::checkbox('weight_13',null,null, array('id'=>'weight_13', 'name'=>'weight_13', 'class' => 'check_weight')) !!}
                     {!! Form::label('available', 'Available') !!}
                     {!! Form::hidden('weight_13_hidden', '', array('class' => 'form-control', 'id' => 'weight_13_hidden')) !!}
                  </div>
               </div>
            </div>
         </div>

         <br>

         <div id="weight_13_div" class="weight_13_div">
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group">
                     {!! Form::label('value', 'Value') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     <div class="input-group {{$errors->has('OS3') ? 'has-error' : ''}}">
                        {!! Form::text('OS3',$multiparametrico->os3, ['placeholder' => 'Days', 'class' =>'form-control validate value_edit', 'id' => 'OS3']) !!}
                        <span class="input-group-addon" id="basic-addon2">Days</span>
                     </div>
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('dateOS3') ? 'has-error' : ''}}">
                     {!! Form::label('date', 'Monitoring Date') !!}
                     {!! Form::date('dateOS3', $multiparametrico->date_os3, ['class' =>'form-control validate value_edit', 'id' => 'dateOS3']); !!}
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('OS3comment') ? 'has-error' : ''}}">
                     {!! Form::label('comment', 'Comment') !!}
                     {!! Form::text('OS3comment',$multiparametrico->comment_os3, ['class' =>'form-control validate', 'id' => 'OS3comment']) !!}    
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p10_os3') ? 'has-error' : ''}}">
                     {!! Form::label('p10', 'p10') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p10_os3',$multiparametrico->p10_os3, ['class' =>'form-control validate', 'id' => 'p10_os3']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p90_os3') ? 'has-error' : ''}}">
                     {!! Form::label('p90', 'p90') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p90_os3',$multiparametrico->p90_os3, ['class' =>'form-control validate', 'id' => 'p90_os3']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('os_pressure_factor') ? 'has-error' : ''}}">
                     {!! Form::label('Weight', 'Weight') !!}
                     @if (count($subparameters_weight))
                     {!! Form::text('os_pressure_factor',$subparameters_weight->os_pressure_factor, ['class' =>'form-control validate weight_count', 'id' => 'weight_13_value']) !!}  
                     @else
                     {!! Form::text('os_pressure_factor',$subparameters_weight['os_pressure_factor'], ['class' =>'form-control validate weight_count', 'id' => 'weight_13_value']) !!} 
                     @endif   
                  </div>
               </div>
            </div>
         </div>

         <hr>

         <div class="row">
            <div class="col-md-12">
               <div class="form-inline" role="form">
                  <div class="form-group">
                     <a target="_blank" href="{{ URL::route('histo', ['subp' => 15])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="images/historics.png" width="25" height="25"/>
                     </button></a>
                     <a target="_blank" href="{{ URL::route('freq', ['subp' => 15])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="images/est.png" width="25" height="25"/>
                     </button></a>
                     <button type="button" class="btn btn-default" aria-label="Left Align" id="popover14">
                     <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>
                     </button>
                     <a target="_blank" href="{{ URL::route('Geor', ['subp' => 15, 'multi' => $multiparametrico->id])}}"><button type="button" class="btn btn-default" aria-label="Left Align">
                     <span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span>
                     </button></a>&nbsp&nbsp&nbsp
                     {!! Form::label('', 'High Impact Factor: De Boer Criteria') !!}
                  </div>
                  <div class="pull-right">
                     {!! Form::checkbox('weight_14',null,null, array('id'=>'weight_14', 'name'=>'weight_14', 'class' => 'check_weight')) !!}
                     {!! Form::label('available', 'Available') !!}
                     {!! Form::hidden('weight_14_hidden', '', array('class' => 'form-control', 'id' => 'weight_14_hidden')) !!}
                  </div>
               </div>
            </div>
         </div>

         <br>

         <div id="weight_14_div" class="weight_14_div">
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group">
                     {!! Form::label('value', 'Value') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     <div class="input-group {{$errors->has('OS4') ? 'has-error' : ''}}">
                        {!! Form::text('OS4',$multiparametrico->os4, ['placeholder' => '-', 'class' =>'form-control validate value_edit', 'id' => 'OS4']) !!}
                        <span class="input-group-addon" id="basic-addon2">-</span>
                     </div>
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('dateOS4') ? 'has-error' : ''}}">
                     {!! Form::label('date', 'Monitoring Date') !!}
                     {!! Form::date('dateOS4', $multiparametrico->date_os4, ['class' =>'form-control validate value_edit', 'id' => 'dateOS4']); !!}
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('OS4comment') ? 'has-error' : ''}}">
                     {!! Form::label('comment', 'Comment') !!}
                     {!! Form::text('OS4comment',$multiparametrico->comment_os4, ['class' =>'form-control validate', 'id' => 'OS4comment']) !!} 
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p10_os4') ? 'has-error' : ''}}">
                     {!! Form::label('p10', 'p10') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p10_os4',$multiparametrico->p10_os4, ['class' =>'form-control validate', 'id' => 'p10_os4']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p90_os4') ? 'has-error' : ''}}">
                     {!! Form::label('p90', 'p90') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p90_os4',$multiparametrico->p90_os4, ['class' =>'form-control validate', 'id' => 'p90_os4']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('os_high_impact_factor') ? 'has-error' : ''}}">
                     {!! Form::label('Weight', 'Weight') !!}
                     @if (count($subparameters_weight))
                     {!! Form::text('os_high_impact_factor',$subparameters_weight->os_high_impact_factor, ['class' =>'form-control validate weight_count', 'id' => 'weight_14_value']) !!}  
                     @else
                     {!! Form::text('os_high_impact_factor',$subparameters_weight['os_high_impact_factor'], ['class' =>'form-control validate weight_count', 'id' => 'weight_14_value']) !!} 
                     @endif    
                  </div>
               </div>
            </div>
         </div>
         <hr>
      </div>
   </div>
</div>


<div name="RP" id="RP">
   <div class="panel panel-default">
      <div class="panel-heading">
         <h4>Relative Permeability</h4>
      </div>

      <div class="panel-body">
         <div class="row">
            <div class="col-md-12">
               <div class="form-inline" role="form">
                  <div class="form-group pull-left">
                     <a target="_blank" href="{{ URL::route('histo', ['subp' => 16])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="images/historics.png" width="25" height="25"/>
                     </button></a>
                     <a target="_blank" href="{{ URL::route('freq', ['subp' => 16])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="images/est.png" width="25" height="25"/>
                     </button></a>
                     <button type="button" class="btn btn-default" aria-label="Left Align" id="popover15">
                     <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>
                     </button>
                     <a target="_blank" href="{{ URL::route('Geor', ['subp' => 16, 'multi' => $multiparametrico->id])}}"><button type="button" class="btn btn-default" aria-label="Left Align">
                     <span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span>
                     </button></a>&nbsp&nbsp&nbsp
                     {!! Form::label('', 'Number Of Days Below Saturation Pressure') !!}
                  </div>
                  <div class="pull-right">
                     {!! Form::checkbox('weight_15',null,null, array('id'=>'weight_15', 'name'=>'weight_15', 'class' => 'check_weight')) !!}
                     {!! Form::label('available', 'Available') !!}
                     {!! Form::hidden('weight_15_hidden', '', array('class' => 'form-control', 'id' => 'weight_15_hidden')) !!}
                  </div>
               </div>
            </div>
         </div>

         <br>

         <div id="weight_15_div" class="weight_15_div">
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group">
                     {!! Form::label('value', 'Value') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     <div class="input-group {{$errors->has('RP1') ? 'has-error' : ''}}">
                        {!! Form::text('RP1',$multiparametrico->rp1, ['placeholder' => 'days', 'class' =>'form-control validate value_edit', 'id' => 'RP1']) !!}
                        <span class="input-group-addon" id="basic-addon2">days</span>
                     </div>
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('dateRP1') ? 'has-error' : ''}}">
                     {!! Form::label('date', 'Monitoring Date') !!}
                     {!! Form::date('dateRP1', $multiparametrico->date_rp1, ['class' =>'form-control validate value_edit', 'id' => 'dateRP1']); !!}
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('RP1comment') ? 'has-error' : ''}}">
                     {!! Form::label('comment', 'Comment') !!}
                     {!! Form::text('RP1comment',$multiparametrico->comment_rp1, ['class' =>'form-control validate', 'id' => 'RP1comment']) !!}    
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p10_rp1') ? 'has-error' : ''}}">
                     {!! Form::label('p10', 'p10') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p10_rp1',$multiparametrico->p10_rp1, ['class' =>'form-control validate', 'id' => 'p10_rp1']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p90_rp1') ? 'has-error' : ''}}">
                     {!! Form::label('p90', 'p90') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p90_rp1',$multiparametrico->p90_rp1, ['class' =>'form-control validate', 'id' => 'p90_rp1']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('rp_days_below_saturation_pressure') ? 'has-error' : ''}}">
                     {!! Form::label('Weight', 'Weight') !!}
                     @if (count($subparameters_weight))
                     {!! Form::text('rp_days_below_saturation_pressure',$subparameters_weight->rp_days_below_saturation_pressure, ['class' =>'form-control validate weight_count', 'id' => 'weight_15_value']) !!}  
                     @else
                     {!! Form::text('rp_days_below_saturation_pressure',$subparameters_weight['rp_days_below_saturation_pressure'], ['class' =>'form-control validate weight_count', 'id' => 'weight_15_value']) !!} 
                     @endif   
                  </div>
               </div>
            </div>
         </div>

         <hr>

         <div class="row">
            <div class="col-md-12">
               <div class="form-inline" role="form">
                  <div class="form-group">
                     <a target="_blank" href="{{ URL::route('histo', ['subp' => 17])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="images/historics.png" width="25" height="25"/>
                     </button></a>
                     <a target="_blank" href="{{ URL::route('freq', ['subp' => 17])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="images/est.png" width="25" height="25"/>
                     </button></a>
                     <button type="button" class="btn btn-default" aria-label="Left Align" id="popover16">
                     <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>
                     </button>
                     <a target="_blank" href="{{ URL::route('Geor', ['subp' => 17, 'multi' => $multiparametrico->id])}}"><button type="button" class="btn btn-default" aria-label="Left Align">
                     <span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span>
                     </button></a>&nbsp&nbsp&nbsp
                     {!! Form::label('', 'Difference between current reservoir pressure and saturation pressure') !!}
                  </div>
                  <div class="pull-right">
                     {!! Form::checkbox('weight_16',null,null, array('id'=>'weight_16', 'name'=>'weight_16', 'class' => 'check_weight')) !!}
                     {!! Form::label('available', 'Available') !!}
                     {!! Form::hidden('weight_16_hidden', '', array('class' => 'form-control', 'id' => 'weight_16_hidden')) !!}
                  </div>
               </div>
            </div>
         </div>

         <br>

         <div id="weight_16_div" class="weight_16_div">
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group">
                     {!! Form::label('value', 'Value') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     <div class="input-group {{$errors->has('RP2') ? 'has-error' : ''}}">
                        {!! Form::text('RP2',$multiparametrico->rp2, ['placeholder' => 'psi', 'class' =>'form-control validate value_edit', 'id' => 'RP2']) !!}
                        <span class="input-group-addon" id="basic-addon2">psi</span>
                     </div>
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('dateRP2') ? 'has-error' : ''}}">
                     {!! Form::label('date', 'Monitoring Date') !!}
                     {!! Form::date('dateRP2', $multiparametrico->date_rp2, ['class' =>'form-control validate value_edit', 'id' => 'dateRP2']); !!}
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('RP2comment') ? 'has-error' : ''}}">
                     {!! Form::label('comment', 'Comment') !!}
                     {!! Form::text('RP2comment',$multiparametrico->comment_rp2, ['class' =>'form-control validate', 'id' => 'RP2comment']) !!}  
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p10_rp2') ? 'has-error' : ''}}">
                     {!! Form::label('p10', 'p10') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p10_rp2',$multiparametrico->p10_rp2, ['class' =>'form-control validate', 'id' => 'p10_rp2']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p90_rp2') ? 'has-error' : ''}}">
                     {!! Form::label('p90', 'p90') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p90_rp2',$multiparametrico->p90_rp2, ['class' =>'form-control validate', 'id' => 'p90_rp2']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('rp_delta_pressure_saturation') ? 'has-error' : ''}}">
                     {!! Form::label('Weight', 'Weight') !!}
                     @if (count($subparameters_weight))
                     {!! Form::text('rp_delta_pressure_saturation',$subparameters_weight->rp_delta_pressure_saturation, ['class' =>'form-control validate weight_count', 'id' => 'weight_16_value']) !!}  
                     @else
                     {!! Form::text('rp_delta_pressure_saturation',$subparameters_weight['rp_delta_pressure_saturation'], ['class' =>'form-control validate weight_count', 'id' => 'weight_16_value']) !!} 
                     @endif    
                  </div>
               </div>
            </div>
         </div>

         <hr>

         <div class="row">
            <div class="col-md-12">
               <div class="form-inline" role="form">
                  <div class="form-group">
                     <a target="_blank" href="{{ URL::route('histo', ['subp' => 18])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="images/historics.png" width="25" height="25"/>
                     </button></a>
                     <a target="_blank" href="{{ URL::route('freq', ['subp' => 18])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="images/est.png" width="25" height="25"/>
                     </button></a>
                     <button type="button" class="btn btn-default" aria-label="Left Align" id="popover17">
                     <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>
                     </button>
                     <a target="_blank" href="{{ URL::route('Geor', ['subp' => 18, 'multi' => $multiparametrico->id])}}"><button type="button" class="btn btn-default" aria-label="Left Align">
                     <span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span>
                     </button></a>&nbsp&nbsp&nbsp
                     {!! Form::label('', 'Water Intrusion: Cumulative Water Produced') !!}
                  </div>
                  <div class="pull-right">
                     {!! Form::checkbox('weight_17',null,null, array('id'=>'weight_17', 'name'=>'weight_17', 'class' => 'check_weight')) !!}
                     {!! Form::label('available', 'Available') !!}
                     {!! Form::hidden('weight_17_hidden', '', array('class' => 'form-control', 'id' => 'weight_17_hidden')) !!}
                  </div>
               </div>
            </div>
         </div>

         <br>

         <div id="weight_17_div" class="weight_17_div">
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group">
                     {!! Form::label('value', 'Value') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     <div class="input-group {{$errors->has('RP3') ? 'has-error' : ''}}">
                        {!! Form::text('RP3',$multiparametrico->rp3, ['placeholder' => 'MMbbl', 'class' =>'form-control validate value_edit', 'id' => 'RP3']) !!}
                        <span class="input-group-addon" id="basic-addon2">MMbbl</span>
                     </div>
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('dateRP3') ? 'has-error' : ''}}">
                     {!! Form::label('date', 'Monitoring Date') !!}
                     {!! Form::date('dateRP3', $multiparametrico->date_rp3, ['class' =>'form-control validate value_edit', 'id' => 'dateRP3']); !!}
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('RP3comment') ? 'has-error' : ''}}">
                     {!! Form::label('comment', 'Comment') !!}
                     {!! Form::text('RP3comment',$multiparametrico->comment_rp3, ['class' =>'form-control validate', 'id' => 'RP3comment']) !!}  
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p10_rp3') ? 'has-error' : ''}}">
                     {!! Form::label('p10', 'p10') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p10_rp3',$multiparametrico->p10_rp3, ['class' =>'form-control validate', 'id' => 'p10_rp3']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p90_rp3') ? 'has-error' : ''}}">
                     {!! Form::label('p90', 'p90') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p90_rp3',$multiparametrico->p90_rp3, ['class' =>'form-control validate', 'id' => 'p90_rp3']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('rp_water_intrusion') ? 'has-error' : ''}}">
                     {!! Form::label('Weight', 'Weight') !!}
                     @if (count($subparameters_weight))
                     {!! Form::text('rp_water_intrusion',$subparameters_weight->rp_water_intrusion, ['class' =>'form-control validate weight_count', 'id' => 'weight_17_value']) !!}  
                     @else
                     {!! Form::text('rp_water_intrusion',$subparameters_weight['rp_water_intrusion'], ['class' =>'form-control validate weight_count', 'id' => 'weight_17_value']) !!} 
                     @endif     
                  </div>
               </div>
            </div>
         </div>

         <hr>

         <div class="row">
            <div class="col-md-12">
               <div class="form-inline" role="form">
                  <div class="form-group">
                     <a target="_blank" href="{{ URL::route('histo', ['subp' => 19])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="images/historics.png" width="25" height="25"/>
                     </button></a>
                     <a target="_blank" href="{{ URL::route('freq', ['subp' => 19])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="images/est.png" width="25" height="25"/>
                     </button></a>
                     <button type="button" class="btn btn-default" aria-label="Left Align" id="popover18">
                     <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>
                     </button>
                     <a target="_blank" href="{{ URL::route('Geor', ['subp' => 19, 'multi' => $multiparametrico->id])}}"><button type="button" class="btn btn-default" aria-label="Left Align">
                     <span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span>
                     </button></a>&nbsp&nbsp&nbsp
                     {!! Form::label('', 'High Impact Factor:Pore Size Diameter Approximation By Katz And Thompson Correlation (d = 1/√(ϕ * k))') !!}
                  </div>
                  <div class="pull-right">
                     {!! Form::checkbox('weight_18',null,null, array('id'=>'weight_18', 'name'=>'weight_18', 'class' => 'check_weight')) !!}
                     {!! Form::label('available', 'Available') !!}
                     {!! Form::hidden('weight_18_hidden', '', array('class' => 'form-control', 'id' => 'weight_18_hidden')) !!}
                  </div>
               </div>
            </div>
         </div>

         <br>

         <div id="weight_18_div" class="weight_18_div">
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group">
                     {!! Form::label('value', 'Value') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     <div class="input-group {{$errors->has('RP4') ? 'has-error' : ''}}">
                        {!! Form::text('RP4',$multiparametrico->rp4, ['placeholder' => '-', 'class' =>'form-control validate value_edit', 'id' => 'RP4']) !!}
                        <span class="input-group-addon" id="basic-addon2">-</span>
                     </div>
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('dateRP4') ? 'has-error' : ''}}">
                     {!! Form::label('date', 'Monitoring Date') !!}
                     {!! Form::date('dateRP4', $multiparametrico->date_rp4, ['class' =>'form-control validate value_edit', 'id' => 'dateRP4']); !!}
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('RP4comment') ? 'has-error' : ''}}">
                     {!! Form::label('comment', 'Comment') !!}
                     {!! Form::text('RP4comment',$multiparametrico->comment_rp4, ['class' =>'form-control validate', 'id' => 'RP4comment']) !!}    
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p10_rp4') ? 'has-error' : ''}}">
                     {!! Form::label('p10', 'p10') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p10_rp4',$multiparametrico->p10_rp4, ['class' =>'form-control validate', 'id' => 'p10_rp4']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p90_rp4') ? 'has-error' : ''}}">
                     {!! Form::label('p90', 'p90') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p90_rp4',$multiparametrico->p90_rp4, ['class' =>'form-control validate', 'id' => 'p90_rp4']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('rp_high_impact_factor') ? 'has-error' : ''}}">
                     {!! Form::label('Weight', 'Weight') !!}
                     @if (count($subparameters_weight))
                     {!! Form::text('rp_high_impact_factor',$subparameters_weight->rp_high_impact_factor, ['class' =>'form-control validate weight_count', 'id' => 'weight_18_value']) !!}  
                     @else
                     {!! Form::text('rp_high_impact_factor',$subparameters_weight['rp_high_impact_factor'], ['class' =>'form-control validate weight_count', 'id' => 'weight_18_value']) !!} 
                     @endif       
                  </div>
               </div>
            </div>
         </div>
         <hr>
      </div>
   </div>
</div>


<div name="ID" id="ID">
   <div class="panel panel-default">
      <div class="panel-heading">
         <h4>Induced Damage</h4>
      </div>

      <div class="panel-body">
         <div class="row">
            <div class="col-md-12">
               <div class="form-inline" role="form">
                  <div class="form-group">
                     <a target="_blank" href="{{ URL::route('histo', ['subp' => 21])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="images/historics.png" width="25" height="25"/>
                     </button></a>
                     <a target="_blank" href="{{ URL::route('freq', ['subp' => 21])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="images/est.png" width="25" height="25"/>
                     </button></a>
                     <button type="button" class="btn btn-default" aria-label="Left Align" id="popover19">
                     <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>
                     </button>
                     <a target="_blank" href="{{ URL::route('Geor', ['subp' => 21, 'multi' => $multiparametrico->id])}}"><button type="button" class="btn btn-default" aria-label="Left Align">
                     <span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span>
                     </button></a>&nbsp&nbsp&nbsp
                     {!! Form::label('MDF', 'Invasion Radius') !!}
                  </div>
                  <div class="pull-right">
                     {!! Form::checkbox('weight_19',null,null, array('id'=>'weight_19', 'name'=>'weight_19', 'class' => 'check_weight')) !!}
                     {!! Form::label('available', 'Available') !!}
                     {!! Form::hidden('weight_19_hidden', '', array('class' => 'form-control', 'id' => 'weight_19_hidden')) !!}
                  </div>
               </div>
            </div>
         </div>

         <br>

         <div id="weight_19_div" class="weight_19_div">
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group">
                     {!! Form::label('value', 'Value') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     <div class="input-group {{$errors->has('ID1') ? 'has-error' : ''}}">
                        {!! Form::text('ID1',$multiparametrico->id1, ['placeholder' => 'ft', 'class' =>'form-control validate pull-right value_edit', 'id' => 'ID1']) !!}
                        <span class="input-group-addon" id="basic-addon2">ft</span>
                     </div>
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('dateID1') ? 'has-error' : ''}}">
                     {!! Form::label('date', 'Monitoring Date') !!}
                     {!! Form::date('dateID1', $multiparametrico->date_id1, ['class' =>'form-control validate value_edit', 'id' => 'dateID1']); !!}
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('ID1comment') ? 'has-error' : ''}}">
                     {!! Form::label('comment', 'Comment') !!}
                     {!! Form::text('ID1comment',$multiparametrico->comment_id1, ['class' =>'form-control validate', 'id' => 'ID1comment']) !!}    
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p10_id1') ? 'has-error' : ''}}">
                     {!! Form::label('p10', 'p10') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p10_id1',$multiparametrico->p10_id1, ['class' =>'form-control validate', 'id' => 'p10_id1']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p90_id1') ? 'has-error' : ''}}">
                     {!! Form::label('p90', 'p90') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p90_id1',$multiparametrico->p90_id1, ['class' =>'form-control validate', 'id' => 'p90_id1']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('id_gross_pay') ? 'has-error' : ''}}">
                     {!! Form::label('Weight', 'Weight') !!}
                     @if (count($subparameters_weight))
                     {!! Form::text('id_gross_pay',$subparameters_weight->id_gross_pay, ['class' =>'form-control validate weight_count', 'id' => 'weight_19_value']) !!}  
                     @else
                     {!! Form::text('id_gross_pay',$subparameters_weight['id_gross_pay'], ['class' =>'form-control validate weight_count', 'id' => 'weight_19_value']) !!} 
                     @endif    
                  </div>
               </div>
            </div>
         </div>

         <hr>

         <div class="row">
            <div class="col-md-12">
               <div class="form-inline" role="form">
                  <div class="form-group">
                     <a target="_blank" href="{{ URL::route('histo', ['subp' => 22])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="images/historics.png" width="25" height="25"/>
                     </button></a>
                     <a target="_blank" href="{{ URL::route('freq', ['subp' => 22])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="images/est.png" width="25" height="25"/>
                     </button></a>
                     <button type="button" class="btn btn-default" aria-label="Left Align" id="popover20">
                     <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>
                     </button>
                     <a target="_blank" href="{{ URL::route('Geor', ['subp' => 22, 'multi' => $multiparametrico->id])}}"><button type="button" class="btn btn-default" aria-label="Left Align">
                     <span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span>
                     </button></a>&nbsp&nbsp&nbsp
                     {!! Form::label('PDF', 'Polymer Damage Factor') !!}
                  </div>
                  <div class="pull-right">
                     {!! Form::checkbox('weight_20',null,null, array('id'=>'weight_20', 'name'=>'weight_20', 'class' => 'check_weight')) !!}
                     {!! Form::label('available', 'Available') !!}
                     {!! Form::hidden('weight_20_hidden', '', array('class' => 'form-control', 'id' => 'weight_20_hidden')) !!}
                  </div>
               </div>
            </div>
         </div>

         <br>

         <div id="weight_20_div" class="weight_20_div">
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group">
                     {!! Form::label('value', 'Value') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     <div class="input-group {{$errors->has('ID2') ? 'has-error' : ''}}">
                        {!! Form::text('ID2',$multiparametrico->id2, ['placeholder' => '-', 'class' =>'form-control validate pull-right value_edit', 'id' => 'ID2']) !!}  
                        <span class="input-group-addon" id="basic-addon2">-</span>
                     </div>
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('dateID2') ? 'has-error' : ''}}">
                     {!! Form::label('date', 'Monitoring Date') !!}
                     {!! Form::date('dateID2', $multiparametrico->date_id2, ['class' =>'form-control validate value_edit', 'id' => 'dateID2']); !!}
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('ID2comment') ? 'has-error' : ''}}">
                     {!! Form::label('comment', 'Comment') !!}
                     {!! Form::text('ID2comment',$multiparametrico->comment_id2, ['class' =>'form-control validate', 'id' => 'ID2comment']) !!}    
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p10_id2') ? 'has-error' : ''}}">
                     {!! Form::label('p10', 'p10') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p10_id2',$multiparametrico->p10_id2, ['class' =>'form-control validate', 'id' => 'p10_id2']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p90_id2') ? 'has-error' : ''}}">
                     {!! Form::label('p90', 'p90') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p90_id2',$multiparametrico->p90_id2, ['class' =>'form-control validate', 'id' => 'p90_id2']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('id_polymer_damage_factor') ? 'has-error' : ''}}">
                     {!! Form::label('Weight', 'Weight') !!}
                     @if (count($subparameters_weight))
                     {!! Form::text('id_polymer_damage_factor',$subparameters_weight->id_polymer_damage_factor, ['class' =>'form-control validate weight_count', 'id' => 'weight_20_value']) !!}  
                     @else
                     {!! Form::text('id_polymer_damage_factor',$subparameters_weight['id_polymer_damage_factor'], ['class' =>'form-control validate weight_count', 'id' => 'weight_20_value']) !!} 
                     @endif  
                  </div>
               </div>
            </div>
         </div>

         <hr>

         <div class="row">
            <div class="col-md-12">
               <div class="form-inline" role="form">
                  <div class="form-group">
                     <a target="_blank" href="{{ URL::route('histo', ['subp' => 23])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="images/historics.png" width="25" height="25"/>
                     </button></a>
                     <a target="_blank" href="{{ URL::route('freq', ['subp' => 23])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="images/est.png" width="25" height="25"/>
                     </button></a>
                     <button type="button" class="btn btn-default" aria-label="Left Align" id="popover21">
                     <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>
                     </button>
                     <a target="_blank" href="{{ URL::route('Geor', ['subp' => 23, 'multi' => $multiparametrico->id])}}"><button type="button" class="btn btn-default" aria-label="Left Align">
                     <span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span>
                     </button></a>&nbsp&nbsp&nbsp
                     {!! Form::label('IFF', 'Induced Skin') !!}
                  </div>
                  <div class="pull-right">
                     {!! Form::checkbox('weight_21',null,null, array('id'=>'weight_21', 'name'=>'weight_21', 'class' => 'check_weight')) !!}
                     {!! Form::label('available', 'Available') !!}
                     {!! Form::hidden('weight_21_hidden', '', array('class' => 'form-control', 'id' => 'weight_21_hidden')) !!}
                  </div>
               </div>
            </div>
         </div>

         <br>

         <div id="weight_21_div" class="weight_21_div">
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group">
                     {!! Form::label('value', 'Value') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     <div class="input-group {{$errors->has('ID3') ? 'has-error' : ''}}">
                        {!! Form::text('ID3',$multiparametrico->id3, ['placeholder' => '-', 'class' =>'form-control validate pull-right value_edit', 'id' => 'ID3']) !!}    
                        <span class="input-group-addon" id="basic-addon2">-</span>
                     </div>
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('dateID3') ? 'has-error' : ''}}">
                     {!! Form::label('date', 'Monitoring Date') !!}
                     {!! Form::date('dateID3', $multiparametrico->date_id3, ['class' =>'form-control validate value_edit', 'id' => 'dateID3']); !!}
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('ID3comment') ? 'has-error' : ''}}">
                     {!! Form::label('comment', 'Comment') !!}
                     {!! Form::text('ID3comment',$multiparametrico->comment_id3, ['class' =>'form-control validate', 'id' => 'ID3comment']) !!}    
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p10_id3') ? 'has-error' : ''}}">
                     {!! Form::label('p10', 'p10') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p10_id3',$multiparametrico->p10_id3, ['class' =>'form-control validate', 'id' => 'p10_id3']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p90_id3') ? 'has-error' : ''}}">
                     {!! Form::label('p90', 'p90') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p90_id3',$multiparametrico->p90_id3, ['class' =>'form-control validate', 'id' => 'p90_id3']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('id_total_volume_water') ? 'has-error' : ''}}">
                     {!! Form::label('Weight', 'Weight') !!}
                     @if (count($subparameters_weight))
                     {!! Form::text('id_total_volume_water',$subparameters_weight->id_total_volume_water, ['class' =>'form-control validate weight_count', 'id' => 'weight_21_value']) !!}  
                     @else
                     {!! Form::text('id_total_volume_water',$subparameters_weight['id_total_volume_water'], ['class' =>'form-control validate weight_count', 'id' => 'weight_21_value']) !!} 
                     @endif     
                  </div>
               </div>
            </div>
         </div>

         <hr>

         <div class="row">
            <div class="col-md-12">
               <div class="form-inline" role="form">
                  <div class="form-group">
                     <a target="_blank" href="{{ URL::route('histo', ['subp' => 24])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="images/historics.png" width="25" height="25"/>
                     </button></a>
                     <a target="_blank" href="{{ URL::route('freq', ['subp' => 24])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="images/est.png" width="25" height="25"/>
                     </button></a>
                     <button type="button" class="btn btn-default" aria-label="Left Align" id="popover22">
                     <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>
                     </button>
                     <a target="_blank" href="{{ URL::route('Geor', ['subp' => 24, 'multi' => $multiparametrico->id])}}"><button type="button" class="btn btn-default" aria-label="Left Align">
                     <span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span>
                     </button></a>&nbsp&nbsp&nbsp
                     {!! Form::label('ML', 'Mud Damage Factor: Mud Losses') !!}
                  </div>
                  <div class="pull-right">
                     {!! Form::checkbox('weight_22',null,null, array('id'=>'weight_22', 'name'=>'weight_22', 'class' => 'check_weight')) !!}
                     {!! Form::label('available', 'Available') !!}
                     {!! Form::hidden('weight_22_hidden', '', array('class' => 'form-control', 'id' => 'weight_22_hidden')) !!}
                  </div>
               </div>
            </div>
         </div>

         <br>

         <div id="weight_22_div" class="weight_22_div">
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group">
                     {!! Form::label('value', 'Value') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     <div class="input-group {{$errors->has('ID4') ? 'has-error' : ''}}">
                        {!! Form::text('ID4',$multiparametrico->id4, ['placeholder' => 'bbl', 'class' =>'form-control validate pull-right value_edit', 'id' => 'ID4']) !!}
                        <span class="input-group-addon" id="basic-addon2">bbl</span>
                     </div>
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('dateID4') ? 'has-error' : ''}}">
                     {!! Form::label('date', 'Monitoring Date') !!}
                     {!! Form::date('dateID4', $multiparametrico->date_id4, ['class' =>'form-control validate value_edit', 'id' => 'dateID4']); !!}
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('ID4comment') ? 'has-error' : ''}}">
                     {!! Form::label('comment', 'Comment') !!}
                     {!! Form::text('ID4comment',$multiparametrico->comment_id4, ['class' =>'form-control validate', 'id' => 'ID4comment']) !!}    
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p10_id4') ? 'has-error' : ''}}">
                     {!! Form::label('p10', 'p10') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p10_id4',$multiparametrico->p10_id4, ['class' =>'form-control validate', 'id' => 'p10_id4']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p90_id4') ? 'has-error' : ''}}">
                     {!! Form::label('p90', 'p90') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p90_id4',$multiparametrico->p90_id4, ['class' =>'form-control validate', 'id' => 'p90_id4']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('id_mud_damage_factor') ? 'has-error' : ''}}">
                     {!! Form::label('Weight', 'Weight') !!}
                     @if (count($subparameters_weight))
                     {!! Form::text('id_mud_damage_factor',$subparameters_weight->id_mud_damage_factor, ['class' =>'form-control validate weight_count', 'id' => 'weight_22_value']) !!}  
                     @else
                     {!! Form::text('id_mud_damage_factor',$subparameters_weight['id_mud_damage_factor'], ['class' =>'form-control validate weight_count', 'id' => 'weight_22_value']) !!} 
                     @endif  
                  </div>
               </div>
            </div>
         </div>
         <hr>
      </div>
   </div>
</div>


<div name="GD" id="GD">
   <div class="panel panel-default">
      <div class="panel-heading">
         <h4>Geomechanical Damage</h4>
      </div>

      <div class="panel-body">
         <div class="row">
            <div class="col-md-12">
               <div class="form-inline" role="form">
                  <div class="form-group">
                     <a target="_blank" href="{{ URL::route('histo', ['subp' => 25])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="images/historics.png" width="25" height="25"/>
                     </button></a>
                     <a target="_blank" href="{{ URL::route('freq', ['subp' => 25])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="images/est.png" width="25" height="25"/>
                     </button></a>
                     <button type="button" class="btn btn-default" aria-label="Left Align" id="popover23">
                     <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>
                     </button>
                     <a target="_blank" href="{{ URL::route('Geor', ['subp' => 25, 'multi' => $multiparametrico->id])}}"><button type="button" class="btn btn-default" aria-label="Left Align">
                     <span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span>
                     </button></a>&nbsp&nbsp&nbsp
                     {!! Form::label('PNP', 'Fraction Of NetPay Exhibiting Natural Fractures') !!}
                  </div>
                  <div class="pull-right">
                     {!! Form::checkbox('weight_23',null,null, array('id'=>'weight_23', 'name'=>'weight_23', 'class' => 'check_weight')) !!}
                     {!! Form::label('available', 'Available') !!}
                     {!! Form::hidden('weight_23_hidden', '', array('class' => 'form-control', 'id' => 'weight_23_hidden')) !!}
                  </div>
               </div>
            </div>
         </div>

         <br>

         <div id="weight_23_div" class="weight_23_div">
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group">
                     {!! Form::label('value', 'Value') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     <div class="input-group {{$errors->has('GD1') ? 'has-error' : ''}}">
                        {!! Form::text('GD1',$multiparametrico->gd1, ['placeholder' => '-', 'class' =>'form-control validate pull-right value_edit', 'id' => 'GD1']) !!}
                        <span class="input-group-addon" id="basic-addon2">-</span>
                     </div>
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('dateGD1') ? 'has-error' : ''}}">
                     {!! Form::label('date', 'Monitoring Date') !!}
                     {!! Form::date('dateGD1', $multiparametrico->date_gd1, ['class' =>'form-control validate value_edit', 'id' => 'dateGD1']); !!}
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('GD1comment') ? 'has-error' : ''}}">
                     {!! Form::label('comment', 'Comment') !!}
                     {!! Form::text('GD1comment',$multiparametrico->comment_gd1, ['class' =>'form-control validate', 'id' => 'GD1comment']) !!}    
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p10_gd1') ? 'has-error' : ''}}">
                     {!! Form::label('p10', 'p10') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p10_gd1',$multiparametrico->p10_gd1, ['class' =>'form-control validate', 'id' => 'p10_gd1']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p90_gd1') ? 'has-error' : ''}}">
                     {!! Form::label('p90', 'p90') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p90_gd1',$multiparametrico->p90_gd1, ['class' =>'form-control validate', 'id' => 'p90_gd1']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('gd_fraction_netpay') ? 'has-error' : ''}}">
                     {!! Form::label('Weight', 'Weight') !!}
                     @if (count($subparameters_weight))
                     {!! Form::text('gd_fraction_netpay',$subparameters_weight->gd_fraction_netpay, ['class' =>'form-control validate weight_count', 'id' => 'weight_23_value']) !!}  
                     @else
                     {!! Form::text('gd_fraction_netpay',$subparameters_weight['gd_fraction_netpay'], ['class' =>'form-control validate weight_count', 'id' => 'weight_23_value']) !!} 
                     @endif   
                  </div>
               </div>
            </div>
         </div>

         <hr>

         <div class="row">
            <div class="col-md-12">
               <div class="form-inline" role="form">
                  <div class="form-group">
                     <a target="_blank" href="{{ URL::route('histo', ['subp' => 26])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="images/historics.png" width="25" height="25"/>
                     </button></a>
                     <a target="_blank" href="{{ URL::route('freq', ['subp' => 26])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="images/est.png" width="25" height="25"/>
                     </button></a>
                     <button type="button" class="btn btn-default" aria-label="Left Align" id="popover24">
                     <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>
                     </button>
                     <a target="_blank" href="{{ URL::route('Geor', ['subp' => 26, 'multi' => $multiparametrico->id])}}"><button type="button" class="btn btn-default" aria-label="Left Align">
                     <span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span>
                     </button></a>&nbsp&nbsp&nbsp
                     {!! Form::label('BHFP', 'Drawdown') !!}
                  </div>
                  <div class="pull-right">
                     {!! Form::checkbox('weight_24',null,null, array('id'=>'weight_24', 'name'=>'weight_24', 'class' => 'check_weight')) !!}
                     {!! Form::label('available', 'Available') !!}
                     {!! Form::hidden('weight_24_hidden', '', array('class' => 'form-control', 'id' => 'weight_24_hidden')) !!}
                  </div>
               </div>
            </div>
         </div>

         <br>

         <div id="weight_24_div" class="weight_24_div">
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('GD2') ? 'has-error' : ''}}">
                     {!! Form::label('value', 'Value') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     <div class="input-group">
                        {!! Form::text('GD2',$multiparametrico->gd2, ['placeholder' => 'psi', 'class' =>'form-control validate pull-right value_edit', 'id' => 'GD2']) !!}
                        <span class="input-group-addon" id="basic-addon2">psi</span>
                     </div>
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('dateGD2') ? 'has-error' : ''}}">
                     {!! Form::label('date', 'Monitoring Date') !!}
                     {!! Form::date('dateGD2', $multiparametrico->date_gd2, ['class' =>'form-control validate value_edit', 'id' => 'dateGD2']); !!}
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('GD2comment') ? 'has-error' : ''}}">
                     {!! Form::label('comment', 'Comment') !!}
                     {!! Form::text('GD2comment',$multiparametrico->comment_gd2, ['class' =>'form-control validate', 'id' => 'GD2comment']) !!}    
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p10_gd2') ? 'has-error' : ''}}">
                     {!! Form::label('p10', 'p10') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p10_gd2',$multiparametrico->p10_gd2, ['class' =>'form-control validate', 'id' => 'p10_gd2']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p90_gd2') ? 'has-error' : ''}}">
                     {!! Form::label('p90', 'p90') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p90_gd2',$multiparametrico->p90_gd2, ['class' =>'form-control validate', 'id' => 'p90_gd2']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('gd_drawdown') ? 'has-error' : ''}}">
                     {!! Form::label('Weight', 'Weight') !!}
                     @if (count($subparameters_weight))
                     {!! Form::text('gd_drawdown',$subparameters_weight->gd_drawdown, ['class' =>'form-control validate weight_count', 'id' => 'weight_24_value']) !!}  
                     @else
                     {!! Form::text('gd_drawdown',$subparameters_weight['gd_drawdown'], ['class' =>'form-control validate weight_count', 'id' => 'weight_24_value']) !!} 
                     @endif      
                  </div>
               </div>
            </div>
         </div>

         <hr>

         <div class="row">
            <div class="col-md-12">
               <div class="form-inline" role="form">
                  <div class="form-group">
                     <a target="_blank" href="{{ URL::route('histo', ['subp' => 27])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="images/historics.png" width="25" height="25"/>
                     </button></a>
                     <a target="_blank" href="{{ URL::route('freq', ['subp' => 27])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="images/est.png" width="25" height="25"/>
                     </button></a>
                     <button type="button" class="btn btn-default" aria-label="Left Align" id="popover25">
                     <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>
                     </button>
                     <a target="_blank" href="{{ URL::route('Geor', ['subp' => 27, 'multi' => $multiparametrico->id])}}"><button type="button" class="btn btn-default" aria-label="Left Align">
                     <span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span>
                     </button></a>&nbsp&nbsp&nbsp
                     {!! Form::label('KH', 'Ratio Of KH + Fracture / KH') !!}
                  </div>
                  <div class="pull-right">
                     {!! Form::checkbox('weight_25',null,null, array('id'=>'weight_25', 'name'=>'weight_25', 'class' => 'check_weight')) !!}
                     {!! Form::label('available', 'Available') !!}
                     {!! Form::hidden('weight_25_hidden', '', array('class' => 'form-control', 'id' => 'weight_25_hidden')) !!}
                  </div>
               </div>
            </div>
         </div>

         <br>

         <div id="weight_25_div" class="weight_25_div">
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group">
                     {!! Form::label('value', 'Value') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     <div class="input-group {{$errors->has('GD3') ? 'has-error' : ''}}">
                        {!! Form::text('GD3',$multiparametrico->gd3, ['placeholder' => '-', 'class' =>'form-control validate pull-right value_edit', 'id' => 'GD3']) !!}
                        <span class="input-group-addon" id="basic-addon2">-</span>
                     </div>
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('dateGD3') ? 'has-error' : ''}}">
                     {!! Form::label('date', 'Monitoring Date') !!}
                     {!! Form::date('dateGD3', $multiparametrico->date_gd3, ['class' =>'form-control validate value_edit', 'id' => 'dateGD3']); !!}
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('GD3comment') ? 'has-error' : ''}}">
                     {!! Form::label('comment', 'Comment') !!}
                     {!! Form::text('GD3comment',$multiparametrico->comment_gd3, ['class' =>'form-control validate', 'id' => 'GD3comment']) !!}    
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p10_gd3') ? 'has-error' : ''}}">
                     {!! Form::label('p10', 'p10') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p10_gd3',$multiparametrico->p10_gd3, ['class' =>'form-control validate', 'id' => 'p10_gd3']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p90_gd3') ? 'has-error' : ''}}">
                     {!! Form::label('p90', 'p90') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p90_gd3',$multiparametrico->p90_gd3, ['class' =>'form-control validate', 'id' => 'p90_gd3']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('gd_ratio_kh_fracture') ? 'has-error' : ''}}">
                     {!! Form::label('Weight', 'Weight') !!}
                     @if (count($subparameters_weight))
                     {!! Form::text('gd_ratio_kh_fracture',$subparameters_weight->gd_ratio_kh_fracture, ['class' =>'form-control validate weight_count', 'id' => 'weight_25_value']) !!}  
                     @else
                     {!! Form::text('gd_ratio_kh_fracture',$subparameters_weight['gd_ratio_kh_fracture'], ['class' =>'form-control validate weight_count', 'id' => 'weight_25_value']) !!} 
                     @endif    
                  </div>
               </div>
            </div>
         </div>

         <hr>

         <div class="row">
            <div class="col-md-12">
               <div class="form-inline" role="form">
                  <div class="form-group">
                     <a target="_blank" href="{{ URL::route('histo', ['subp' => 28])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="images/historics.png" width="25" height="25"/>
                     </button></a>
                     <a target="_blank" href="{{ URL::route('freq', ['subp' => 28])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="images/est.png" width="25" height="25"/>
                     </button></a>
                     <button type="button" class="btn btn-default" aria-label="Left Align" id="popover26">
                     <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>
                     </button>
                     <a target="_blank" href="{{ URL::route('Geor', ['subp' => 28, 'multi' => $multiparametrico->id])}}"><button type="button" class="btn btn-default" aria-label="Left Align">
                     <span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span>
                     </button></a>&nbsp&nbsp&nbsp
                     {!! Form::label('KH', 'Geomechanical Damage Expressed As Fraction Of Base Permeability At BHFP') !!}
                  </div>
                  <div class="pull-right">
                     {!! Form::checkbox('weight_26',null,null, array('id'=>'weight_26', 'name'=>'weight_26', 'class' => 'check_weight')) !!}
                     {!! Form::label('available', 'Available') !!}
                     {!! Form::hidden('weight_26_hidden', '', array('class' => 'form-control', 'id' => 'weight_26_hidden')) !!}
                  </div>
               </div>
            </div>
         </div>

         <br>

         <div id="weight_26_div" class="weight_26_div">
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group">
                     {!! Form::label('value', 'Value') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     <div class="input-group {{$errors->has('GD4') ? 'has-error' : ''}}">
                        {!! Form::text('GD4',$multiparametrico->gd4, ['placeholder' => '-', 'class' =>'form-control validate pull-right value_edit', 'id' => 'GD4']) !!}
                        <span class="input-group-addon" id="basic-addon2">-</span>
                     </div>
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('dateGD4') ? 'has-error' : ''}}">
                     {!! Form::label('date', 'Monitoring Date') !!}
                     {!! Form::date('dateGD4', $multiparametrico->date_gd4, ['class' =>'form-control validate value_edit', 'id' => 'dateGD4']); !!}
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('GD4comment') ? 'has-error' : ''}}">
                     {!! Form::label('comment', 'Comment') !!}
                     {!! Form::text('GD4comment',$multiparametrico->comment_gd4, ['class' =>'form-control validate', 'id' => 'GD4comment']) !!}    
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p10_gd4') ? 'has-error' : ''}}">
                     {!! Form::label('p10', 'p10') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p10_gd4',$multiparametrico->p10_gd4, ['class' =>'form-control validate', 'id' => 'p10_gd4']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p90_gd4') ? 'has-error' : ''}}">
                     {!! Form::label('p90', 'p90') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p90_gd4',$multiparametrico->p90_gd4, ['class' =>'form-control validate', 'id' => 'p90_gd4']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('gd_geomechanical_damage_fraction') ? 'has-error' : ''}}">
                     {!! Form::label('Weight', 'Weight') !!}
                     @if (count($subparameters_weight))
                     {!! Form::text('gd_geomechanical_damage_fraction',$subparameters_weight->gd_geomechanical_damage_fraction, ['class' =>'form-control validate weight_count', 'id' => 'weight_26_value']) !!}  
                     @else
                     {!! Form::text('gd_geomechanical_damage_fraction',$subparameters_weight['gd_geomechanical_damage_fraction'], ['class' =>'form-control validate weight_count', 'id' => 'weight_26_value']) !!} 
                     @endif   
                  </div>
               </div>
            </div>
         </div>
         <hr>
      </div>
   </div>
</div>


<div class="modal fade" id="count_error" role="dialog" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Error</h4>
         </div>
         <div class="modal-body">
            <p>Sum of all subparameters weight must be 1.</p>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Ok</button>
         </div>
      </div>
   </div>
</div>

<div class="modal fade" id="exampleModal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Error</h4>
         </div>
         <div class="modal-body" id="modal_SP">
            <p></p>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Ok</button>
         </div>
      </div>
   </div>
</div>

<div class="row">
   <div class= "col-md-12">
      <br>
      <br>
      <center>
         <p class="pull-right">
            <a target="_blank"  href="{{ URL::route('multiparametrico',['id' => $multiparametrico->scenary_id]) }}" class="btn btn-info" id="plot">Run</a>
            {!! Form::submit('Save' , array('class' => 'btn btn-primary validate_weight')) !!}
            <a href="{!! url('share_scenario') !!}" class="btn btn-danger" role="button">Cancel</a>
            {!! Form::Close() !!}
         </p>
      </center>
   </div>
</div>

<a href="{{ URL::route('GeneralInformationC.edit',['id' => $multiparametrico->scenary_id]) }}"><button class="btn btn-default pull-right"  type="button" data-toggle="modal">Back</button></a>
@endsection


@section('Scripts')
    @include('js/edit_subparameter')
    @include('js/modal_error')
@endsection