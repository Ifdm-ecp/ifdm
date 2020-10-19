<div class="tab-pane" id="RP">
	<div class="panel panel-default">
      <div class="panel-heading">
         <h4>Relative Permeability</h4>
      </div>

      <div class="panel-body">
         <div class="row">
            <div class="col-md-12">
               <div class="form-inline" role="form">
                  <div class="form-group pull-left">
                     <a target="_blank" href="{{ URL::route('histo', ['subp' => 15, 'statistical' => $statistical])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="{{asset('images/historics.png')}}" width="25" height="24"/>
                     </button></a>
                     <a target="_blank" href="{{ URL::route('freq', ['subp' => 15, 'statistical' => $statistical])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="{{asset('images/est.png')}}" width="25" height="24"/>
                     </button></a>
                     <button type="button" class="btn btn-default" aria-label="Left Align" id="popover15">
                     <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>
                     </button>
                     <a target="_blank" href="{{ URL::route('Geor', ['subp' => 15, 'multi' => $statistical->id])}}"><button type="button" class="btn btn-default" aria-label="Left Align">
                     <span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span>
                     </button></a>&nbsp&nbsp&nbsp
                     {!! Form::label('', 'Number Of Days Below Saturation Pressure') !!}
                  </div>
                  <div class="pull-right">
                     {!! Form::checkbox('rpAvailable[]',1,false, array('id'=>'weight_rp_1', 'class' => 'check_weight')) !!}
                     {!! Form::label('available', 'Available') !!}
                     {!! Form::hidden('weight_rp_1_hidden', '', array('class' => 'form-control', 'id' => 'weight_rp_1_hidden')) !!}
                  </div>
               </div>
            </div>
         </div>

         <br>

         <div id="weight_rp_1_div">
            <div class="row">
               <div class="col-xs-12 col-md-4">
                  <div class="form-group">
                     {!! Form::label('stored', 'Stored previously') !!}
                     {!! Form::select('selectStoredRP1', array(), null, array('placeholder' => '', 'class' => 'form-control selectpicker show-tick ms-subparameter-picker select-stored-15', 'data-style' => 'btn-default', 'id' => 'selectStoredRP1')) !!}
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group">
                     {!! Form::label('value', 'Value') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     <div class="input-group {{$errors->has('RP1') ? 'has-error' : ''}}">
                        {!! Form::text('RP1', $statistical->rp1, ['placeholder' => 'days', 'class' =>'form-control value_edit', 'id' => 'RP1']) !!}
                        <span class="input-group-addon" id="basic-addon2">days</span>
                     </div>
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('dateRP1') ? 'has-error' : ''}}">
                     {!! Form::label('date', 'Monitoring Date') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('dateRP1', $statistical->date_rp1, ['placeholder' => 'dd/mm/yyyy', 'class' =>'form-control value_edit jquery-datepicker', 'id' => 'dateRP1']); !!}
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('RP1comment') ? 'has-error' : ''}}">
                     {!! Form::label('comment', 'Comment') !!}
                     {!! Form::text('RP1comment', $statistical->comment_rp1, ['class' =>'form-control validate', 'id' => 'RP1comment']) !!}
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p10_RP1') ? 'has-error' : ''}}">
                     {!! Form::label('p10', 'p10') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p10_RP1', $statistical->p10_rp1, ['class' =>'form-control validate', 'id' => 'p10_15']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p90_RP1') ? 'has-error' : ''}}">
                     {!! Form::label('p90', 'p90') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p90_RP1', $statistical->p90_rp1, ['class' =>'form-control validate', 'id' => 'p90_15']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('rp_days_below_saturation_pressure') ? 'has-error' : ''}}">
                     {!! Form::label('Weight', 'Weight') !!}
                     {!! Form::text('rp_days_below_saturation_pressure', $statistical->subparameters->rp_days_below_saturation_pressure ? $statistical->subparameters->rp_days_below_saturation_pressure : 0.20, ['class' =>'form-control weight_rp_count', 'id' => 'weight_rp_1_value']) !!}   
                  </div>
               </div>
            </div>
         </div>

         <hr>

         <div class="row">
            <div class="col-md-12">
               <div class="form-inline" role="form">
                  <div class="form-group">
                     <a target="_blank" href="{{ URL::route('histo', ['subp' => 16, 'statistical' => $statistical])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="{{asset('images/historics.png')}}" width="25" height="24"/>
                     </button></a>
                     <a target="_blank" href="{{ URL::route('freq', ['subp' => 16, 'statistical' => $statistical])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="{{asset('images/est.png')}}" width="25" height="24"/>
                     </button></a>
                     <button type="button" class="btn btn-default" aria-label="Left Align" id="popover16">
                     <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>
                     </button>
                     <a target="_blank" href="{{ URL::route('Geor', ['subp' => 16, 'multi' => $statistical->id])}}"><button type="button" class="btn btn-default" aria-label="Left Align">
                     <span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span>
                     </button></a>&nbsp&nbsp&nbsp
                     {!! Form::label('', 'Difference between current reservoir pressure and saturation pressure') !!}
                  </div>
                  <div class="pull-right">
                     {!! Form::checkbox('rpAvailable[]',2,false, array('id'=>'weight_rp_2', 'class' => 'check_weight')) !!}
                     {!! Form::label('available', 'Available') !!}
                     {!! Form::hidden('weight_rp_2_hidden', '', array('class' => 'form-control', 'id' => 'weight_rp_2_hidden')) !!}
                  </div>
               </div>
            </div>
         </div>

         <br>

         <div id="weight_rp_2_div">
            <div class="row">
               <div class="col-xs-12 col-md-4">
                  <div class="form-group">
                     {!! Form::label('stored', 'Stored previously') !!}
                     {!! Form::select('selectStoredRP2', array(), null, array('placeholder' => '', 'class' => 'form-control selectpicker show-tick ms-subparameter-picker select-stored-16', 'data-style' => 'btn-default', 'id' => 'selectStoredRP2')) !!}
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group">
                     {!! Form::label('value', 'Value') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     <div class="input-group {{$errors->has('RP2') ? 'has-error' : ''}}">
                        {!! Form::text('RP2', $statistical->rp2, ['placeholder' => 'psi', 'class' =>'form-control value_edit', 'id' => 'RP2']) !!}
                        <span class="input-group-addon" id="basic-addon2">psi</span>
                     </div>
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('dateRP2') ? 'has-error' : ''}}">
                     {!! Form::label('date', 'Monitoring Date') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('dateRP2', $statistical->date_rp2, ['placeholder' => 'dd/mm/yyyy', 'class' =>'form-control value_edit jquery-datepicker', 'id' => 'dateRP2']); !!}
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('RP2comment') ? 'has-error' : ''}}">
                     {!! Form::label('comment', 'Comment') !!}
                     {!! Form::text('RP2comment', $statistical->comment_rp2, ['class' =>'form-control validate', 'id' => 'RP2comment']) !!}
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p10_RP2') ? 'has-error' : ''}}">
                     {!! Form::label('p10', 'p10') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p10_RP2', $statistical->p10_rp2, ['class' =>'form-control validate', 'id' => 'p10_16']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p90_RP2') ? 'has-error' : ''}}">
                     {!! Form::label('p90', 'p90') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p90_RP2', $statistical->p90_rp2, ['class' =>'form-control validate', 'id' => 'p90_16']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('rp_delta_pressure_saturation') ? 'has-error' : ''}}">
                     {!! Form::label('Weight', 'Weight') !!}
                     {!! Form::text('rp_delta_pressure_saturation', $statistical->subparameters->rp_delta_pressure_saturation ? $statistical->subparameters->rp_delta_pressure_saturation : 0.20, ['class' =>'form-control weight_rp_count', 'id' => 'weight_rp_2_value']) !!}  
                  </div>
               </div>
            </div>
         </div>

         <hr>

         <div class="row">
            <div class="col-md-12">
               <div class="form-inline" role="form">
                  <div class="form-group">
                     <a target="_blank" href="{{ URL::route('histo', ['subp' => 17, 'statistical' => $statistical])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="{{asset('images/historics.png')}}" width="25" height="24"/>
                     </button></a>
                     <a target="_blank" href="{{ URL::route('freq', ['subp' => 17, 'statistical' => $statistical])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="{{asset('images/est.png')}}" width="25" height="24"/>
                     </button></a>
                     <button type="button" class="btn btn-default" aria-label="Left Align" id="popover17">
                     <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>
                     </button>
                     <a target="_blank" href="{{ URL::route('Geor', ['subp' => 17, 'multi' => $statistical->id])}}"><button type="button" class="btn btn-default" aria-label="Left Align">
                     <span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span>
                     </button></a>&nbsp&nbsp&nbsp
                     {!! Form::label('', 'Cumulative Water Produced') !!}
                  </div>
                  <div class="pull-right">
                     {!! Form::checkbox('rpAvailable[]',3,false, array('id'=>'weight_rp_3', 'class' => 'check_weight')) !!}
                     {!! Form::label('available', 'Available') !!}
                     {!! Form::hidden('weight_rp_3_hidden', '', array('class' => 'form-control', 'id' => 'weight_rp_3_hidden')) !!}
                  </div>
               </div>
            </div>
         </div>

         <br>

         <div id="weight_rp_3_div">
            <div class="row">
               <div class="col-xs-12 col-md-4">
                  <div class="form-group">
                     {!! Form::label('stored', 'Stored previously') !!}
                     {!! Form::select('selectStoredRP3', array(), null, array('placeholder' => '', 'class' => 'form-control selectpicker show-tick ms-subparameter-picker select-stored-17', 'data-style' => 'btn-default', 'id' => 'selectStoredRP3')) !!}
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group">
                     {!! Form::label('value', 'Value') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     <div class="input-group {{$errors->has('RP3') ? 'has-error' : ''}}">
                        {!! Form::text('RP3', $statistical->rp3, ['placeholder' => 'MMbbl', 'class' =>'form-control value_edit', 'id' => 'RP3']) !!} 
                        <span class="input-group-addon" id="basic-addon2">MMbbl</span>
                     </div>
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('dateRP3') ? 'has-error' : ''}}">
                     {!! Form::label('date', 'Monitoring Date') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('dateRP3', $statistical->date_rp3, ['placeholder' => 'dd/mm/yyyy', 'class' =>'form-control value_edit jquery-datepicker', 'id' => 'dateRP3']); !!}
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('RP3comment') ? 'has-error' : ''}}">
                     {!! Form::label('comment', 'Comment') !!}
                     {!! Form::text('RP3comment', $statistical->comment_rp3, ['class' =>'form-control validate', 'id' => 'RP3comment']) !!}
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p10_RP3') ? 'has-error' : ''}}">
                     {!! Form::label('p10', 'p10') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p10_RP3', $statistical->p10_rp3, ['class' =>'form-control validate', 'id' => 'p10_17']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p90_RP3') ? 'has-error' : ''}}">
                     {!! Form::label('p90', 'p90') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p90_RP3', $statistical->p90_rp3, ['class' =>'form-control validate', 'id' => 'p90_17']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('rp_water_intrusion') ? 'has-error' : ''}}">
                     {!! Form::label('Weight', 'Weight') !!}
                     {!! Form::text('rp_water_intrusion', $statistical->subparameters->rp_water_intrusion ? $statistical->subparameters->rp_water_intrusion : 0.20, ['class' =>'form-control weight_rp_count', 'id' => 'weight_rp_3_value']) !!}     
                  </div>
               </div>
            </div>
         </div>

         <hr>

         <div class="row">
            <div class="col-md-12">
               <div class="form-inline" role="form">
                  <div class="form-group">
                     <a target="_blank" href="{{ URL::route('histo', ['subp' => 18, 'statistical' => $statistical])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="{{asset('images/historics.png')}}" width="25" height="24"/>
                     </button></a>
                     <a target="_blank" href="{{ URL::route('freq', ['subp' => 18, 'statistical' => $statistical])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="{{asset('images/est.png')}}" width="25" height="24"/>
                     </button></a>
                     <button type="button" class="btn btn-default" aria-label="Left Align" id="popover18">
                     <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>
                     </button>
                     <a target="_blank" href="{{ URL::route('Geor', ['subp' => 18, 'multi' => $statistical->id])}}"><button type="button" class="btn btn-default" aria-label="Left Align">
                     <span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span>
                     </button></a>&nbsp&nbsp&nbsp
                     {!! Form::label('', 'Pore Size Diameter Approximation By Katz And Thompson Correlation (d = 1/√(ϕ * k))') !!}
                  </div>
                  <div class="pull-right">
                     {!! Form::checkbox('rpAvailable[]',4,false, array('id'=>'weight_rp_4', 'class' => 'check_weight')) !!}
                     {!! Form::label('available', 'Available') !!}
                     {!! Form::hidden('weight_rp_4_hidden', '', array('class' => 'form-control', 'id' => 'weight_rp_4_hidden')) !!}
                  </div>
               </div>
            </div>
         </div>

         <br>

         <div id="weight_rp_4_div">
            <div class="row">
               <div class="col-xs-12 col-md-4">
                  <div class="form-group">
                     {!! Form::label('stored', 'Stored previously') !!}
                     {!! Form::select('selectStoredRP4', array(), null, array('placeholder' => '', 'class' => 'form-control selectpicker show-tick ms-subparameter-picker select-stored-18', 'data-style' => 'btn-default', 'id' => 'selectStoredRP4')) !!}
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group">
                     {!! Form::label('value', 'Value') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     <div class="input-group {{$errors->has('RP4') ? 'has-error' : ''}}">
                        {!! Form::text('RP4', $statistical->rp4, ['placeholder' => '-', 'class' =>'form-control value_edit', 'id' => 'RP4']) !!}
                        <span class="input-group-addon" id="basic-addon2">-</span>
                     </div>
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('dateRP4') ? 'has-error' : ''}}">
                     {!! Form::label('date', 'Monitoring Date') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('dateRP4', $statistical->date_rp4, ['placeholder' => 'dd/mm/yyyy', 'class' =>'form-control value_edit jquery-datepicker', 'id' => 'dateRP4']); !!}
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('RP4comment') ? 'has-error' : ''}}">
                     {!! Form::label('comment', 'Comment') !!}
                     {!! Form::text('RP4comment', $statistical->comment_rp4, ['class' =>'form-control validate', 'id' => 'RP4comment']) !!}
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p10_RP4') ? 'has-error' : ''}}">
                     {!! Form::label('p10', 'p10') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p10_RP4', $statistical->p10_rp4, ['class' =>'form-control validate', 'id' => 'p10_18']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p90_RP4') ? 'has-error' : ''}}">
                     {!! Form::label('p90', 'p90') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p90_RP4', $statistical->p90_rp4, ['class' =>'form-control validate', 'id' => 'p90_18']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('rp_high_impact_factor') ? 'has-error' : ''}}">
                     {!! Form::label('Weight', 'Weight') !!}
                     {!! Form::text('rp_high_impact_factor', $statistical->subparameters->rp_high_impact_factor ? $statistical->subparameters->rp_high_impact_factor : 0.20, ['class' =>'form-control weight_rp_count', 'id' => 'weight_rp_4_value']) !!} 
                  </div>
               </div>
            </div>
         </div>

         <hr>

         <div class="row">
            <div class="col-md-12">
               <div class="form-inline" role="form">
                  <div class="form-group">
                     <a target="_blank" href="{{ URL::route('histo', ['subp' => 31, 'statistical' => $statistical])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="{{asset('images/historics.png')}}" width="25" height="24"/>
                     </button></a>
                     <a target="_blank" href="{{ URL::route('freq', ['subp' => 31, 'statistical' => $statistical])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="{{asset('images/est.png')}}" width="25" height="24"/>
                     </button></a>
                     <button type="button" class="btn btn-default" aria-label="Left Align" id="popover31">
                     <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>
                     </button>
                     <a target="_blank" href="{{ URL::route('Geor', ['subp' => 31, 'multi' => $statistical->id])}}"><button type="button" class="btn btn-default" aria-label="Left Align">
                     <span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span>
                     </button></a>&nbsp&nbsp&nbsp
                     {!! Form::label('', 'Velocity parameter estimated from maximum critical velocity') !!}
                  </div>
                  <div class="pull-right">
                     {!! Form::checkbox('rpAvailable[]',5,false, array('id'=>'weight_rp_5', 'class' => 'check_weight')) !!}
                     {!! Form::label('available', 'Available') !!}
                     {!! Form::hidden('weight_rp_5_hidden', '', array('class' => 'form-control', 'id' => 'weight_rp_5_hidden')) !!}
                  </div>
               </div>
            </div>
         </div>

         <br>

         <div id="weight_rp_5_div">
            <div class="row">
               <div class="col-xs-12 col-md-4">
                  <div class="form-group">
                     {!! Form::label('stored', 'Stored previously') !!}
                     {!! Form::select('selectStoredRP5', array(), null, array('placeholder' => '', 'class' => 'form-control selectpicker show-tick ms-subparameter-picker select-stored-31', 'data-style' => 'btn-default', 'id' => 'selectStoredRP5')) !!}
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group">
                     {!! Form::label('value', 'Value') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     <div class="input-group {{$errors->has('RP5') ? 'has-error' : ''}}">
                        {!! Form::text('RP5', $statistical->rp5, ['placeholder' => 'cc/min', 'class' =>'form-control value_edit', 'id' => 'RP5']) !!}
                        <span class="input-group-addon" id="basic-addon2">cc/min</span>
                     </div>
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('dateRP5') ? 'has-error' : ''}}">
                     {!! Form::label('date', 'Monitoring Date') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('dateRP5', $statistical->date_rp5, ['placeholder' => 'dd/mm/yyyy', 'class' =>'form-control value_edit jquery-datepicker', 'id' => 'dateRP5']); !!}
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('RP5comment') ? 'has-error' : ''}}">
                     {!! Form::label('comment', 'Comment') !!}
                     {!! Form::text('RP5comment', $statistical->comment_rp5, ['class' =>'form-control validate', 'id' => 'RP5comment']) !!}
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p10_RP5') ? 'has-error' : ''}}">
                     {!! Form::label('p10', 'p10') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p10_RP5', $statistical->p10_rp5, ['class' =>'form-control validate', 'id' => 'p10_31']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p90_RP5') ? 'has-error' : ''}}">
                     {!! Form::label('p90', 'p90') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p90_RP5', $statistical->p90_rp5, ['class' =>'form-control validate', 'id' => 'p90_31']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('rp_velocity_estimated') ? 'has-error' : ''}}">
                     {!! Form::label('Weight', 'Weight') !!}
                     {!! Form::text('rp_velocity_estimated', $statistical->subparameters->rp_velocity_estimated ? $statistical->subparameters->rp_velocity_estimated : 0.20, ['class' =>'form-control weight_rp_count', 'id' => 'weight_rp_5_value']) !!} 
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>