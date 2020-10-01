<div class="tab-pane" id="MS">
	<div class="panel panel-default">
      <div class="panel-heading">
         <h4 id="ms-title">Mineral Scales </h4>
      </div>

       <div class="panel-body">
         <div class="row">
            <div class="col-md-12">
               <div class="form-inline" role="form">
                  <div class="form-group">
                     <a target="_blank" href="{{ URL::route('histo', ['subp' => 1, 'statistical' => $statistical])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="{{asset('images/historics.png')}}" width="25" height="24"/>
                     </button></a>
                     <a target="_blank" href="{{ URL::route('freq', ['subp' => 1, 'statistical' => $statistical])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="{{asset('images/est.png')}}" width="25" height="24"/>
                     </button></a>
                     <button type="button" class="btn btn-default" aria-label="Left Align" id="popover1">
                     <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>
                     </button>
                     <a target="_blank" href="{{ URL::route('Geor', ['subp' => 1, 'multi' => $statistical->id])}}"><button type="button" class="btn btn-default" aria-label="Left Align">
                     <span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span>
                     </button></a>&nbsp&nbsp&nbsp
                     {!! Form::label('SIC', 'Scale Index Of CaCO3') !!}
                  </div>
                  <div class="pull-right">
                     {!! Form::checkbox('msAvailable[]',1,false, array('id'=>'weight_ms_1', 'class' => 'check_weight')) !!}
                     {!! Form::label('available', 'Available') !!}
                     {!! Form::hidden('weight_ms_1_hidden', '', array('class' => 'form-control', 'id' => 'weight_ms_1_hidden')) !!}
                  </div>
               </div>
            </div>
         </div>

         <br>

         <div id="weight_ms_1_div" class="weight_ms_1_div">
            <div class="row">
               <div class="col-xs-12 col-md-4">
                  <div class="form-group">
                     {!! Form::label('stored', 'Stored previously') !!}
                     {!! Form::select('selectStoredMS1', array(), null, array('placeholder' => '', 'class' => 'form-control selectpicker show-tick ms-subparameter-picker select-stored-1', 'data-style' => 'btn-default', 'id' => 'selectStoredMS1')) !!}
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('MS1') ? 'has-error' : ''}}">
                     {!! Form::label('value', 'Value') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     <div class="input-group">
                        {!! Form::text('MS1', $statistical->ms1, ['placeholder' => '-', 'class' =>'form-control value_edit', 'id' => 'MS1']) !!}  
                        <span class="input-group-addon" id="basic-addon2">-</span>
                     </div>
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('dateMS1') ? 'has-error' : ''}}">
                     {!! Form::label('date', 'Monitoring Date') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('dateMS1', $statistical->date_ms1, ['placeholder' => 'dd/mm/yyyy', 'class' =>'form-control value_edit jquery-datepicker', 'id' => 'dateMS1']); !!}
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('MS1comment') ? 'has-error' : ''}}">
                     {!! Form::label('comment', 'Comment') !!}
                     {!! Form::text('MS1comment', $statistical->comment_ms1, ['class' =>'form-control validate', 'id' => 'MS1comment']) !!}
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p10_MS1') ? 'has-error' : ''}}">
                     {!! Form::label('p10', 'p10') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p10_MS1', $statistical->p10_ms1, ['class' =>'form-control validate', 'id' => 'p10_1']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p90_MS1') ? 'has-error' : ''}}">
                     {!! Form::label('p90', 'p90') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p90_MS1', $statistical->p90_ms1, ['class' =>'form-control validate', 'id' => 'p90_1']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('ms_scale_index_caco3') ? 'has-error' : ''}}">
                     {!! Form::label('Weight', 'Weight') !!}
                     {!! Form::text('ms_scale_index_caco3', $statistical->subparameters->ms_scale_index_caco3 ? $statistical->subparameters->ms_scale_index_caco3 : 0.2, ['class' =>'form-control weight_ms_count', 'id' => 'weight_ms_1_value']) !!}
                  </div>
               </div>
            </div>
         </div>

         <hr>

         <div class="row">
            <div class="col-md-12">
               <div class="form-inline" role="form">
                  <div class="form-group">
                     <a target="_blank" href="{{ URL::route('histo', ['subp' => 2, 'statistical' => $statistical])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="{{asset('images/historics.png')}}" width="25" height="24"/>
                     </button></a>
                     <a target="_blank" href="{{ URL::route('freq', ['subp' => 2, 'statistical' => $statistical])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="{{asset('images/est.png')}}" width="25" height="24"/>
                     </button></a>
                     <button type="button" class="btn btn-default" aria-label="Left Align" id="popover2">
                     <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>
                     </button>
                     <a target="_blank" href="{{ URL::route('Geor', ['subp' => 2, 'multi' => $statistical->id])}}"><button type="button" class="btn btn-default" aria-label="Left Align">
                     <span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span>
                     </button></a>&nbsp&nbsp&nbsp
                     {!! Form::label('BaSO4', 'Scale Index Of BaSO4') !!}
                  </div>

                  <div class="pull-right">
                     {!! Form::checkbox('msAvailable[]',2,false, array('id'=>'weight_ms_2', 'class' => 'check_weight')) !!}
                     {!! Form::label('available', 'Available') !!}
                     {!! Form::hidden('weight_ms_2_hidden', '', array('class' => 'form-control', 'id' => 'weight_ms_2_hidden')) !!}
                  </div>
               </div>
            </div>
         </div>

         <br>  

         <div id="weight_ms_2_div">
            <div class="row">
               <div class="col-xs-12 col-md-4">
                  <div class="form-group">
                     {!! Form::label('stored', 'Stored previously') !!}
                     {!! Form::select('selectStoredMS2', array(), null, array('placeholder' => '', 'class' => 'form-control selectpicker show-tick ms-subparameter-picker select-stored-2', 'data-style' => 'btn-default', 'id' => 'selectStoredMS2')) !!}
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('MS2') ? 'has-error' : ''}}">
                     {!! Form::label('value', 'Value') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     <div class="input-group">
                        {!! Form::text('MS2', $statistical->ms2, ['placeholder' => '-', 'class' =>'form-control  value_edit pull-right', 'id' => 'MS2']) !!}
                        <span class="input-group-addon" id="basic-addon2">-</span>
                     </div>
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('dateMS2') ? 'has-error' : ''}}">
                     {!! Form::label('date', 'Monitoring Date') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('dateMS2', $statistical->date_ms2, ['placeholder' => 'dd/mm/yyyy', 'class' =>'form-control value_edit jquery-datepicker', 'id' => 'dateMS2']); !!}
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('MS2comment') ? 'has-error' : ''}}">
                     {!! Form::label('comment', 'Comment') !!}
                     {!! Form::text('MS2comment', $statistical->comment_ms1, ['class' =>'form-control validate', 'id' => 'MS2comment']) !!}
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p10_MS2') ? 'has-error' : ''}}">
                     {!! Form::label('p10', 'p10') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p10_MS2', $statistical->p10_ms2, ['class' =>'form-control validate', 'id' => 'p10_2']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p90_MS2') ? 'has-error' : ''}}">
                     {!! Form::label('p90', 'p90') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p90_MS2', $statistical->p90_ms2, ['class' =>'form-control validate', 'id' => 'p90_2']) !!}   
                  </div>
               </div>
               <div  class="col-md-4">
                  <div class="form-group {{$errors->has('ms_scale_index_baso4') ? 'has-error' : ''}}">
                     {!! Form::label('Weight', 'Weight') !!}
                     {!! Form::text('ms_scale_index_baso4', $statistical->subparameters->ms_scale_index_baso4 ? $statistical->subparameters->ms_scale_index_baso4 : 0.2, ['class' =>'form-control weight_ms_count', 'id' => 'weight_ms_2_value']) !!} 
                  </div>
               </div>
            </div>
         </div>

         <hr>

         <div class="row">
            <div class="col-md-12">
               <div class="form-inline" role="form">
                  <div class="form-group">
                     <a target="_blank" href="{{ URL::route('histo', ['subp' => 3, 'statistical' => $statistical])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="{{asset('images/historics.png')}}" width="25" height="24"/>
                     </button></a>
                     <a target="_blank" href="{{ URL::route('freq', ['subp' => 3, 'statistical' => $statistical])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="{{asset('images/est.png')}}" width="25" height="24"/>
                     </button></a>
                     <button type="button" class="btn btn-default" aria-label="Left Align" id="popover3">
                     <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>
                     </button>
                     <a target="_blank" href="{{ URL::route('Geor', ['subp' => 3, 'multi' => $statistical->id])}}"><button type="button" class="btn btn-default" aria-label="Left Align">
                     <span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span>
                     </button></a>&nbsp&nbsp&nbsp
                     {!! Form::label('SIS', 'Scale Index Of Iron Scales') !!}
                  </div>
                  <div class="pull-right">
                     {!! Form::checkbox('msAvailable[]',3,false, array('id'=>'weight_ms_3','class' => 'check_weight')) !!}
                     {!! Form::label('available', 'Available') !!}
                     {!! Form::hidden('weight_ms_3_hidden', '', array('class' => 'form-control', 'id' => 'weight_ms_3_hidden')) !!}
                  </div>
               </div>
            </div>
         </div>

         <br>

         <div id="weight_ms_3_div">
            <div class="row">
               <div class="col-xs-12 col-md-4">
                  <div class="form-group">
                     {!! Form::label('stored', 'Stored previously') !!}
                     {!! Form::select('selectStoredMS3', array(), null, array('placeholder' => '', 'class' => 'form-control selectpicker show-tick ms-subparameter-picker select-stored-3', 'data-style' => 'btn-default', 'id' => 'selectStoredMS3')) !!}
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('MS3') ? 'has-error' : ''}}">
                     {!! Form::label('value', 'Value') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     <div class="input-group">
                        {!! Form::text('MS3', $statistical->ms3, ['placeholder' => '-', 'class' =>'form-control pull-right value_edit', 'id' => 'MS3']) !!}
                        <span class="input-group-addon" id="basic-addon2">-</span>
                     </div>
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('dateMS3') ? 'has-error' : ''}}">
                     {!! Form::label('date', 'Monitoring Date') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('dateMS3', $statistical->date_ms3, ['placeholder' => 'dd/mm/yyyy', 'class' =>'form-control value_edit jquery-datepicker', 'id' => 'dateMS3']); !!}
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('MS3comment') ? 'has-error' : ''}}">
                     {!! Form::label('comment', 'Comment') !!}
                     {!! Form::text('MS3comment', $statistical->comment_ms3, ['class' =>'form-control validate', 'id' => 'MS3comment']) !!}
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p10_MS3') ? 'has-error' : ''}}">
                     {!! Form::label('p10', 'p10') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p10_MS3', $statistical->p10_ms3, ['class' =>'form-control validate', 'id' => 'p10_3']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p90_MS3') ? 'has-error' : ''}}">
                     {!! Form::label('p90', 'p90') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p90_MS3', $statistical->p90_ms3, ['class' =>'form-control validate', 'id' => 'p90_3']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('ms_scale_index_iron_scales') ? 'has-error' : ''}}">
                     {!! Form::label('Weight', 'Weight') !!}
                     {!! Form::text('ms_scale_index_iron_scales', $statistical->subparameters->ms_scale_index_iron_scales ? $statistical->subparameters->ms_scale_index_iron_scales : 0.2, ['class' =>'form-control weight_ms_count', 'id' => 'weight_ms_3_value']) !!}
                  </div>
               </div>
            </div>
         </div>

         <hr>

         <div class="row">
            <div class="col-md-12">
               <div class="form-inline" role="form">
                  <div class="form-group">
                     <a target="_blank" href="{{ URL::route('histo', ['subp' => 4, 'statistical' => $statistical])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="{{asset('images/historics.png')}}" width="25" height="24"/>
                     </button></a>
                     <a target="_blank" href="{{ URL::route('freq', ['subp' => 4, 'statistical' => $statistical])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="{{asset('images/est.png')}}" width="25" height="24"/>
                     </button></a>
                     <button type="button" class="btn btn-default" aria-label="Left Align" id="popover4">
                     <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>
                     </button>
                     <a target="_blank" href="{{ URL::route('Geor', ['subp' => 4, 'multi' => $statistical->id])}}"><button type="button" class="btn btn-default" aria-label="Left Align">
                     <span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span>
                     </button></a>&nbsp&nbsp&nbsp
                     {!! Form::label('', 'Backflow [Ca]') !!}
                  </div>
                  <div class="pull-right">
                     {!! Form::checkbox('msAvailable[]',4,false, array('id'=>'weight_ms_4', 'class' => 'check_weight')) !!}
                     {!! Form::label('available', 'Available') !!}
                     {!! Form::hidden('weight_ms_4_hidden', '', array('class' => 'form-control', 'id' => 'weight_ms_4_hidden')) !!}
                  </div>
               </div>
            </div>
         </div>

         <br> 

         <div id="weight_ms_4_div">
            <div class="row">
               <div class="col-xs-12 col-md-4">
                  <div class="form-group">
                     {!! Form::label('stored', 'Stored previously') !!}
                     {!! Form::select('selectStoredMS4', array(), null, array('placeholder' => '', 'class' => 'form-control selectpicker show-tick ms-subparameter-picker select-stored-4', 'data-style' => 'btn-default', 'id' => 'selectStoredMS4')) !!}
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group">
                     {!! Form::label('value', 'Value') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     <div class="input-group {{$errors->has('MS4') ? 'has-error' : ''}}">
                        {!! Form::text('MS4', $statistical->ms4, ['placeholder' => 'ppm', 'class' =>'form-control pull-right value_edit', 'id' => 'MS4']) !!}
                        <span class="input-group-addon" id="basic-addon2">ppm</span>
                     </div>
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('dateMS4') ? 'has-error' : ''}}">
                     {!! Form::label('date', 'Monitoring Date') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('dateMS4', $statistical->date_ms4, ['placeholder' => 'dd/mm/yyyy', 'class' =>'form-control value_edit jquery-datepicker', 'id' => 'dateMS4']); !!}
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('MS4comment') ? 'has-error' : ''}}">
                     {!! Form::label('comment', 'Comment') !!}
                     {!! Form::text('MS4comment', $statistical->comment_ms4, ['class' =>'form-control validate', 'id' => 'MS4comment']) !!}
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p10_MS4') ? 'has-error' : ''}}">
                     {!! Form::label('p10', 'p10') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p10_MS4', $statistical->p10_ms4, ['class' =>'form-control validate', 'id' => 'p10_4']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p90_MS4') ? 'has-error' : ''}}">
                     {!! Form::label('p90', 'p90') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p90_MS4', $statistical->p90_ms4, ['class' =>'form-control validate', 'id' => 'p90_4']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('ms_calcium_concentration') ? 'has-error' : ''}}">
                     {!! Form::label('Weight', 'Weight') !!}
                     {!! Form::text('ms_calcium_concentration', $statistical->subparameters->ms_calcium_concentration ? $statistical->subparameters->ms_calcium_concentration : 0.2, ['class' =>'form-control weight_ms_count', 'id' => 'weight_ms_4_value']) !!} 
                  </div>
               </div>
            </div>
         </div>

         <hr>

         <div class="row">
            <div class="col-md-12">
               <div class="form-inline" role="form">
                  <div class="form-group">
                     <a target="_blank" href="{{ URL::route('histo', ['subp' => 5, 'statistical' => $statistical])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="{{asset('images/historics.png')}}" width="25" height="24"/>
                     </button></a>
                     <a target="_blank" href="{{ URL::route('freq', ['subp' => 5, 'statistical' => $statistical])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="{{asset('images/est.png')}}" width="25" height="24"/>
                     </button></a>
                     <button type="button" class="btn btn-default" aria-label="Left Align" id="popover5">
                     <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>
                     </button>
                     <a target="_blank" href="{{ URL::route('Geor', ['subp' => 5, 'multi' => $statistical->id])}}"><button type="button" class="btn btn-default" aria-label="Left Align">
                     <span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span>
                     </button></a>&nbsp&nbsp&nbsp
                     {!! Form::label('', 'Backflow [Ba]') !!}
                  </div>
                  <div class="pull-right">
                     {!! Form::checkbox('msAvailable[]',5,false, array('id'=>'weight_ms_5', 'class' => 'check_weight')) !!}
                     {!! Form::label('available', 'Available') !!}
                     {!! Form::hidden('weight_ms_5_hidden', '', array('class' => 'form-control', 'id' => 'weight_ms_5_hidden')) !!}
                  </div>
               </div>
            </div>
         </div>

         <br>  

         <div id="weight_ms_5_div">
            <div class="row">
               <div class="col-xs-12 col-md-4">
                  <div class="form-group">
                     {!! Form::label('stored', 'Stored previously') !!}
                     {!! Form::select('selectStoredMS5', array(), null, array('placeholder' => '', 'class' => 'form-control selectpicker show-tick ms-subparameter-picker select-stored-5', 'data-style' => 'btn-default', 'id' => 'selectStoredMS5')) !!}
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('MS5') ? 'has-error' : ''}}">
                     {!! Form::label('value', 'Value') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     <div class="input-group">
                        {!! Form::text('MS5', $statistical->ms5, ['placeholder' => 'ppm', 'class' =>'form-control pull-right value_edit', 'id' => 'MS5']) !!}
                        <span class="input-group-addon" id="basic-addon2">ppm</span>
                     </div>
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('dateMS5') ? 'has-error' : ''}}">
                     {!! Form::label('date', 'Monitoring Date') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('dateMS5', $statistical->date_ms5, ['placeholder' => 'dd/mm/yyyy', 'class' =>'form-control value_edit jquery-datepicker', 'id' => 'dateMS5']); !!}
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('MS5comment') ? 'has-error' : ''}}">
                     {!! Form::label('comment', 'Comment') !!}
                     {!! Form::text('MS5comment', $statistical->comment_ms5, ['class' =>'form-control validate', 'id' => 'MS5comment']) !!}
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p10_MS5') ? 'has-error' : ''}}">
                     {!! Form::label('p10', 'p10') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p10_MS5', $statistical->p10_ms5, ['class' =>'form-control validate', 'id' => 'p10_5']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p90_MS5') ? 'has-error' : ''}}">
                     {!! Form::label('p90', 'p90') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p90_MS5', $statistical->p90_ms5, ['class' =>'form-control validate', 'id' => 'p90_5']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('ms_barium_concentration') ? 'has-error' : ''}}">
                     {!! Form::label('Weight', 'Weight') !!}
                     {!! Form::text('ms_barium_concentration', $statistical->subparameters->ms_barium_concentration ? $statistical->subparameters->ms_barium_concentration : 0.2, ['class' =>'form-control weight_ms_count', 'id' => 'weight_ms_5_value']) !!} 
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>