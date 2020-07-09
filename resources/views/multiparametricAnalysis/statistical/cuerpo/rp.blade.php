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
                     <img src="{{asset('images/historics.png')}}" width="25" height="25"/>
                     </button></a>
                     <a target="_blank" href="{{ URL::route('freq', ['subp' => 15, 'statistical' => $statistical])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="{{asset('images/est.png')}}" width="25" height="25"/>
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
                     {!! Form::checkbox('rpAvailable[]',1,false, array('id'=>'weight_15', 'class' => 'check_weight')) !!}
                     {!! Form::label('available', 'Available') !!}
                     {!! Form::hidden('weight_15_hidden', '', array('class' => 'form-control', 'id' => 'weight_15_hidden')) !!}
                  </div>
               </div>
            </div>
         </div>

         <br>

         <div id="weight_15_div">
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group">
                     {!! Form::label('value', 'Value') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     <div class="input-group {{$errors->has('RP1') ? 'has-error' : ''}}">
                        @if (Session::has('RP1'))
                        {!! Form::text('RP1',Session::get('RP1')->valor, ['placeholder' => 'days', 'class' =>'form-control value_edit', 'id' => 'RP1']) !!}
                        @else
                        {!! Form::text('RP1',null, ['placeholder' => 'days', 'class' =>'form-control value_edit', 'id' => 'RP1']) !!}
                        @endif
                        <span class="input-group-addon" id="basic-addon2">days</span>
                     </div>
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('dateRP1') ? 'has-error' : ''}}">
                     {!! Form::label('date', 'Monitoring Date') !!}
                     @if (Session::has('RP1'))
                     {!! Form::text('dateRP1', Session::get('RP1')->fecha, ['placeholder' => 'dd/mm/yyyy', 'class' =>'form-control value_edit jquery-datepicker', 'id' => 'dateRP1']); !!}
                     @else
                     {!! Form::text('dateRP1', null, ['placeholder' => 'dd/mm/yyyy', 'class' =>'form-control value_edit jquery-datepicker', 'id' => 'dateRP1']); !!}
                     @endif
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('RP1comment') ? 'has-error' : ''}}">
                     {!! Form::label('comment', 'Comment') !!}
                     @if (Session::has('RP1'))
                     {!! Form::text('RP1comment',Session::get('RP1')->comentario, ['class' =>'form-control validate', 'id' => 'RP1comment']) !!}    
                     @else 
                     {!! Form::text('RP1comment',null, ['class' =>'form-control validate', 'id' => 'RP1comment']) !!}
                     @endif
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p10_RP1') ? 'has-error' : ''}}">
                     {!! Form::label('p10', 'p10') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p10_RP1',null, ['class' =>'form-control validate', 'id' => 'p10_15']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p90_RP1') ? 'has-error' : ''}}">
                     {!! Form::label('p90', 'p90') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p90_RP1',null, ['class' =>'form-control validate', 'id' => 'p90_15']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('rp_days_below_saturation_pressure') ? 'has-error' : ''}}">
                     {!! Form::label('Weight', 'Weight') !!}
                     {!! Form::text('rp_days_below_saturation_pressure',$statistical->subparameters->rp_days_below_saturation_pressure ? $statistical->subparameters->rp_days_below_saturation_pressure : 0.25, ['class' =>'form-control weight_count', 'id' => 'weight_15_value']) !!}   
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
                     <img src="{{asset('images/historics.png')}}" width="25" height="25"/>
                     </button></a>
                     <a target="_blank" href="{{ URL::route('freq', ['subp' => 16, 'statistical' => $statistical])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="{{asset('images/est.png')}}" width="25" height="25"/>
                     </button></a>
                     <button type="button" class="btn btn-default" aria-label="Left Align" id="popover16">
                     <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>
                     </button>
                     <a target="_blank" href="{{ URL::route('Geor', ['subp' => 16, 'multi' => $statistical->id])}}"><button type="button" class="btn btn-default" aria-label="Left Align">
                     <span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span>
                     </button></a>&nbsp&nbsp&nbsp
                     {!! Form::label('', 'Delta Pressure From Saturation Pressure') !!}
                  </div>
                  <div class="pull-right">
                     {!! Form::checkbox('rpAvailable[]',2,false, array('id'=>'weight_16', 'class' => 'check_weight')) !!}
                     {!! Form::label('available', 'Available') !!}
                     {!! Form::hidden('weight_16_hidden', '', array('class' => 'form-control', 'id' => 'weight_16_hidden')) !!}
                  </div>
               </div>
            </div>
         </div>

         <br>

         <div id="weight_16_div">
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group">
                     {!! Form::label('value', 'Value') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     <div class="input-group {{$errors->has('RP2') ? 'has-error' : ''}}">
                        @if (Session::has('RP2'))
                        {!! Form::text('RP2',Session::get('RP2')->valor, ['placeholder' => 'psi', 'class' =>'form-control value_edit', 'id' => 'RP2']) !!}
                        @else
                        {!! Form::text('RP2',null, ['placeholder' => 'psi', 'class' =>'form-control value_edit', 'id' => 'RP2']) !!}
                        @endif
                        <span class="input-group-addon" id="basic-addon2">psi</span>
                     </div>
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('dateRP2') ? 'has-error' : ''}}">
                     {!! Form::label('date', 'Monitoring Date') !!}
                     @if (Session::has('RP2'))
                     {!! Form::text('dateRP2', Session::get('RP2')->fecha, ['placeholder' => 'dd/mm/yyyy', 'class' =>'form-control value_edit jquery-datepicker', 'id' => 'dateRP2']); !!}
                     @else
                     {!! Form::text('dateRP2', null, ['placeholder' => 'dd/mm/yyyy', 'class' =>'form-control value_edit jquery-datepicker', 'id' => 'dateRP2']); !!}
                     @endif
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('RP2comment') ? 'has-error' : ''}}">
                     {!! Form::label('comment', 'Comment') !!}
                     @if (Session::has('RP2'))
                     {!! Form::text('RP2comment',Session::get('RP2')->comentario, ['class' =>'form-control validate', 'id' => 'RP2comment']) !!}    
                     @else 
                     {!! Form::text('RP2comment',null, ['class' =>'form-control validate', 'id' => 'RP2comment']) !!}
                     @endif
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p10_RP2') ? 'has-error' : ''}}">
                     {!! Form::label('p10', 'p10') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p10_RP2',null, ['class' =>'form-control validate', 'id' => 'p10_16']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p90_RP2') ? 'has-error' : ''}}">
                     {!! Form::label('p90', 'p90') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p90_RP2',null, ['class' =>'form-control validate', 'id' => 'p90_16']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('rp_delta_pressure_saturation') ? 'has-error' : ''}}">
                     {!! Form::label('Weight', 'Weight') !!}
                     {!! Form::text('rp_delta_pressure_saturation',$statistical->subparameters->rp_delta_pressure_saturation ? $statistical->subparameters->rp_delta_pressure_saturation : 0.25, ['class' =>'form-control weight_count', 'id' => 'weight_16_value']) !!}  
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
                     <img src="{{asset('images/historics.png')}}" width="25" height="25"/>
                     </button></a>
                     <a target="_blank" href="{{ URL::route('freq', ['subp' => 17, 'statistical' => $statistical])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="{{asset('images/est.png')}}" width="25" height="25"/>
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
                     {!! Form::checkbox('rpAvailable[]',3,false, array('id'=>'weight_17', 'class' => 'check_weight')) !!}
                     {!! Form::label('available', 'Available') !!}
                     {!! Form::hidden('weight_17_hidden', '', array('class' => 'form-control', 'id' => 'weight_17_hidden')) !!}
                  </div>
               </div>
            </div>
         </div>

         <br>

         <div id="weight_17_div">
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group">
                     {!! Form::label('value', 'Value') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     <div class="input-group {{$errors->has('RP3') ? 'has-error' : ''}}">
                        @if (Session::has('RP3'))
                        {!! Form::text('RP3',Session::get('RP3')->valor, ['placeholder' => 'MMbbl', 'class' =>'form-control value_edit', 'id' => 'RP3']) !!}
                        @else
                        {!! Form::text('RP3',null, ['placeholder' => 'MMbbl', 'class' =>'form-control value_edit', 'id' => 'RP3']) !!}
                        @endif       
                        <span class="input-group-addon" id="basic-addon2">MMbbl</span>
                     </div>
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('dateRP3') ? 'has-error' : ''}}">
                     {!! Form::label('date', 'Monitoring Date') !!}
                     @if (Session::has('RP3'))
                     {!! Form::text('dateRP3', Session::get('RP3')->fecha, ['placeholder' => 'dd/mm/yyyy', 'class' =>'form-control value_edit jquery-datepicker', 'id' => 'dateRP3']); !!}
                     @else
                     {!! Form::text('dateRP3', null, ['placeholder' => 'dd/mm/yyyy', 'class' =>'form-control value_edit jquery-datepicker', 'id' => 'dateRP3']); !!}
                     @endif
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('RP3comment') ? 'has-error' : ''}}">
                     {!! Form::label('comment', 'Comment') !!}
                     @if (Session::has('RP3'))
                     {!! Form::text('RP3comment',Session::get('RP3')->comentario, ['class' =>'form-control validate', 'id' => 'RP3comment']) !!}    
                     @else 
                     {!! Form::text('RP3comment',null, ['class' =>'form-control validate', 'id' => 'RP3comment']) !!}
                     @endif
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p10_RP3') ? 'has-error' : ''}}">
                     {!! Form::label('p10', 'p10') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p10_RP3',null, ['class' =>'form-control validate', 'id' => 'p10_17']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p90_RP3') ? 'has-error' : ''}}">
                     {!! Form::label('p90', 'p90') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p90_RP3',null, ['class' =>'form-control validate', 'id' => 'p90_17']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('rp_water_intrusion') ? 'has-error' : ''}}">
                     {!! Form::label('Weight', 'Weight') !!}
                     {!! Form::text('rp_water_intrusion',$statistical->subparameters->rp_water_intrusion ? $statistical->subparameters->rp_water_intrusion : 0.25, ['class' =>'form-control weight_count', 'id' => 'weight_17_value']) !!}     
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
                     <img src="{{asset('images/historics.png')}}" width="25" height="25"/>
                     </button></a>
                     <a target="_blank" href="{{ URL::route('freq', ['subp' => 18, 'statistical' => $statistical])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="{{asset('images/est.png')}}" width="25" height="25"/>
                     </button></a>
                     <button type="button" class="btn btn-default" aria-label="Left Align" id="popover18">
                     <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>
                     </button>
                     <a target="_blank" href="{{ URL::route('Geor', ['subp' => 18, 'multi' => $statistical->id])}}"><button type="button" class="btn btn-default" aria-label="Left Align">
                     <span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span>
                     </button></a>&nbsp&nbsp&nbsp
                     {!! Form::label('', 'Pore Size Diameter Approximation By Katz And Thompson Correlation') !!}
                  </div>
                  <div class="pull-right">
                     {!! Form::checkbox('rpAvailable[]',4,false, array('id'=>'weight_18', 'class' => 'check_weight')) !!}
                     {!! Form::label('available', 'Available') !!}
                     {!! Form::hidden('weight_18_hidden', '', array('class' => 'form-control', 'id' => 'weight_18_hidden')) !!}
                  </div>
               </div>
            </div>
         </div>

         <br>

         <div id="weight_18_div">
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group">
                     {!! Form::label('value', 'Value') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     <div class="input-group {{$errors->has('RP4') ? 'has-error' : ''}}">
                        @if (Session::has('RP4'))
                        {!! Form::text('RP4',Session::get('RP4')->valor, ['placeholder' => '-', 'class' =>'form-control value_edit', 'id' => 'RP4']) !!}
                        @else
                        {!! Form::text('RP4',null, ['placeholder' => '-', 'class' =>'form-control value_edit', 'id' => 'RP4']) !!}
                        @endif
                        <span class="input-group-addon" id="basic-addon2">-</span>
                     </div>
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('dateRP4') ? 'has-error' : ''}}">
                     {!! Form::label('date', 'Monitoring Date') !!}
                     @if (Session::has('RP4'))
                     {!! Form::text('dateRP4', Session::get('RP4')->fecha, ['placeholder' => 'dd/mm/yyyy', 'class' =>'form-control value_edit jquery-datepicker', 'id' => 'dateRP4']); !!}
                     @else
                     {!! Form::text('dateRP4', null, ['placeholder' => 'dd/mm/yyyy', 'class' =>'form-control value_edit jquery-datepicker', 'id' => 'dateRP4']); !!}
                     @endif
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('RP4comment') ? 'has-error' : ''}}">
                     {!! Form::label('comment', 'Comment') !!}
                     @if (Session::has('RP4'))
                     {!! Form::text('RP4comment',Session::get('RP4')->comentario, ['class' =>'form-control validate', 'id' => 'RP4comment']) !!}    
                     @else 
                     {!! Form::text('RP4comment',null, ['class' =>'form-control validate', 'id' => 'RP4comment']) !!}
                     @endif
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p10_RP4') ? 'has-error' : ''}}">
                     {!! Form::label('p10', 'p10') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p10_RP4',null, ['class' =>'form-control validate', 'id' => 'p10_18']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p90_RP4') ? 'has-error' : ''}}">
                     {!! Form::label('p90', 'p90') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p90_RP4',null, ['class' =>'form-control validate', 'id' => 'p90_18']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('rp_high_impact_factor') ? 'has-error' : ''}}">
                     {!! Form::label('Weight', 'Weight') !!}
                     {!! Form::text('rp_high_impact_factor',$statistical->subparameters->rp_high_impact_factor ? $statistical->subparameters->rp_high_impact_factor : 0.25, ['class' =>'form-control weight_count', 'id' => 'weight_18_value']) !!} 
                  </div>
               </div>
            </div>
         </div>
         <hr>
      </div>
   </div>
</div>