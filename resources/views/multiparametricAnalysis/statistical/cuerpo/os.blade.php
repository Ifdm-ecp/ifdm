<div class="tab-pane" id="OS">
	<div class="panel panel-default">
      <div class="panel-heading">
         <h4>Organic Scales</h4>
      </div>

      <div class="panel-body">
         <div class="row">
            <div class="col-md-12">
               <div class="form-inline" role="form">
                  <div class="form-group">
                     <a target="_blank" href="{{ URL::route('histo', ['subp' => 11, 'statistical' => $statistical])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="{{asset('images/historics.png')}}" width="25" height="25"/>
                     </button></a>
                     <a target="_blank" href="{{ URL::route('freq', ['subp' => 11, 'statistical' => $statistical])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="{{asset('images/est.png')}}" width="25" height="25"/>
                     </button></a>
                     <button type="button" class="btn btn-default" aria-label="Left Align" id="popover11">
                     <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>
                     </button>
                     <a target="_blank" href="{{ URL::route('Geor', ['subp' => 11, 'multi' => $statistical->id])}}"><button type="button" class="btn btn-default" aria-label="Left Align">
                     <span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span>
                     </button></a>&nbsp&nbsp&nbsp
                     {!! Form::label('', 'CII Factor: Colloidal Instability Index') !!}
                  </div>
                  <div class="pull-right">
                     {!! Form::checkbox('osAvailable[]',1,false, array('id'=>'weight_os_1', 'class' => 'check_weight')) !!}
                     {!! Form::label('available', 'Available') !!}
                     {!! Form::hidden('weight_os_1_hidden', '', array('class' => 'form-control', 'id' => 'weight_os_1_hidden')) !!}
                  </div>
               </div>
            </div>
         </div>

         <br>

         <div id="weight_os_1_div">
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group">
                     {!! Form::label('value', 'Value') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     <div class="input-group {{$errors->has('OS1') ? 'has-error' : ''}}">
                        {!! Form::text('OS1', $statistical->os1, ['placeholder' => '-', 'class' =>'form-control value_edit', 'id' => 'OS1']) !!}
                        <span class="input-group-addon" id="basic-addon2">-</span>
                     </div>
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('dateOS1') ? 'has-error' : ''}}">
                     {!! Form::label('date', 'Monitoring Date') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('dateOS1', $statistical->date_os1, ['placeholder' => 'dd/mm/yyyy', 'class' =>'form-control value_edit jquery-datepicker', 'id' => 'dateOS1']); !!}
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('OS1comment') ? 'has-error' : ''}}">
                     {!! Form::label('comment', 'Comment') !!}
                     {!! Form::text('OS1comment', $statistical->comment_os1, ['class' =>'form-control validate', 'id' => 'OS1comment']) !!}
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p10_OS1') ? 'has-error' : ''}}">
                     {!! Form::label('p10', 'p10') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p10_OS1', $statistical->p10_os1, ['class' =>'form-control validate', 'id' => 'p10_11']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p90_OS1') ? 'has-error' : ''}}">
                     {!! Form::label('p90', 'p90') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p90_OS1', $statistical->p90_os1, ['class' =>'form-control validate', 'id' => 'p90_11']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('os_cll_factor') ? 'has-error' : ''}}">
                     {!! Form::label('Weight', 'Weight') !!}
                     {!! Form::text('os_cll_factor', $statistical->subparameters->os_cll_factor ? $statistical->subparameters->os_cll_factor : 0.2, ['class' =>'form-control weight_os_count', 'id' => 'weight_os_1_value']) !!}
                  </div>
               </div>
            </div>
         </div>

         <hr>

         <div class="row">
            <div class="col-md-12">
               <div class="form-inline" role="form">
                  <div class="form-group">
                     <a target="_blank" href="{{ URL::route('histo', ['subp' => 30, 'statistical' => $statistical])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="{{asset('images/historics.png')}}" width="25" height="25"/>
                     </button></a>
                     <a target="_blank" href="{{ URL::route('freq', ['subp' => 30, 'statistical' => $statistical])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="{{asset('images/est.png')}}" width="25" height="25"/>
                     </button></a>
                     <button type="button" class="btn btn-default" aria-label="Left Align" id="popover30">
                     <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>
                     </button>
                     <a target="_blank" href="{{ URL::route('Geor', ['subp' => 30, 'multi' => $statistical->id])}}"><button type="button" class="btn btn-default" aria-label="Left Align">
                     <span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span>
                     </button></a>&nbsp&nbsp&nbsp
                     {!! Form::label('', 'Volume of HCL pumped into the formation') !!}
                  </div>
                  <div class="pull-right">
                     {!! Form::checkbox('osAvailable[]',2,false, array('id'=>'weight_os_2', 'class' => 'check_weight')) !!}
                     {!! Form::label('available', 'Available') !!}
                     {!! Form::hidden('weight_os_2_hidden', '', array('class' => 'form-control', 'id' => 'weight_os_2_hidden')) !!}
                  </div>
               </div>
            </div>
         </div>

         <br>

         <div id="weight_os_2_div">
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group">
                     {!! Form::label('value', 'Value') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     <div class="input-group {{$errors->has('OS2') ? 'has-error' : ''}}">
                        {!! Form::text('OS2', $statistical->os2, ['placeholder' => 'bbl', 'class' =>'form-control value_edit', 'id' => 'OS2']) !!}
                        <span class="input-group-addon" id="basic-addon2">bbl</span>
                     </div>
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('dateOS2') ? 'has-error' : ''}}">
                     {!! Form::label('date', 'Monitoring Date') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('dateOS2', $statistical->date_os2, ['placeholder' => 'dd/mm/yyyy', 'class' =>'form-control value_edit jquery-datepicker', 'id' => 'dateOS2']); !!}
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('OS2comment') ? 'has-error' : ''}}">
                     {!! Form::label('comment', 'Comment') !!}
                     {!! Form::text('OS2comment', $statistical->comment_os2, ['class' =>'form-control validate', 'id' => 'OS2comment']) !!}
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p10_OS2') ? 'has-error' : ''}}">
                     {!! Form::label('p10', 'p10') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p10_OS2', $statistical->p10_os2, ['class' =>'form-control validate', 'id' => 'p10_30']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p90_OS2') ? 'has-error' : ''}}">
                     {!! Form::label('p90', 'p90') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p90_OS2', $statistical->p90_os2, ['class' =>'form-control validate', 'id' => 'p90_30']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('os_volume_of_hcl') ? 'has-error' : ''}}">
                     {!! Form::label('Weight', 'Weight') !!}
                     {!! Form::text('os_volume_of_hcl', $statistical->subparameters->os_volume_of_hcl ? $statistical->subparameters->os_volume_of_hcl : 0.2, ['class' =>'form-control weight_os_count', 'id' => 'weight_os_2_value']) !!}
                  </div>
               </div>
            </div>
         </div>

         <hr>

         <div class="row">
            <div class="col-md-12">
               <div class="form-inline" role="form">
                  <div class="form-group">
                     <a target="_blank" href="{{ URL::route('histo', ['subp' => 12, 'statistical' => $statistical])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="{{asset('images/historics.png')}}" width="25" height="25"/>
                     </button></a>
                     <a target="_blank" href="{{ URL::route('freq', ['subp' => 12, 'statistical' => $statistical])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="{{asset('images/est.png')}}" width="25" height="25"/>
                     </button></a>
                     <button type="button" class="btn btn-default" aria-label="Left Align" id="popover12">
                     <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>
                     </button>
                     <a target="_blank" href="{{ URL::route('Geor', ['subp' => 12, 'multi' => $statistical->id])}}"><button type="button" class="btn btn-default" aria-label="Left Align">
                     <span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span>
                     </button></a>&nbsp&nbsp&nbsp
                     {!! Form::label('', 'Cumulative Gas Produced') !!}
                  </div>
                  <div class="pull-right">
                     {!! Form::checkbox('osAvailable[]',3,false, array('id'=>'weight_os_3', 'class' => 'check_weight')) !!}
                     {!! Form::label('available', 'Available') !!}
                     {!! Form::hidden('weight_os_3_hidden', '', array('class' => 'form-control', 'id' => 'weight_os_3_hidden')) !!}
                  </div>
               </div>
            </div>
         </div>

         <br>

         <div id="weight_os_3_div">
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group">
                     {!! Form::label('value', 'Value') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     <div class="input-group {{$errors->has('OS3') ? 'has-error' : ''}}">
                        {!! Form::text('OS3', $statistical->os3, ['placeholder' => 'mMMSCF', 'class' =>'form-control value_edit', 'id' => 'OS3']) !!}
                        <span class="input-group-addon" id="basic-addon2">mMMSCF</span>
                     </div>
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('dateOS3') ? 'has-error' : ''}}">
                     {!! Form::label('date', 'Monitoring Date') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('dateOS3', $statistical->date_os3, ['placeholder' => 'dd/mm/yyyy', 'class' =>'form-control value_edit jquery-datepicker', 'id' => 'dateOS3']); !!}
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('OS3comment') ? 'has-error' : ''}}">
                     {!! Form::label('comment', 'Comment') !!}
                     {!! Form::text('OS3comment', $statistical->comment_os3, ['class' =>'form-control validate', 'id' => 'OS3comment']) !!}
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p10_OS3') ? 'has-error' : ''}}">
                     {!! Form::label('p10', 'p10') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p10_OS3', $statistical->p10_os3, ['class' =>'form-control validate', 'id' => 'p10_12']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p90_OS3') ? 'has-error' : ''}}">
                     {!! Form::label('p90', 'p90') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p90_OS3', $statistical->p90_os3, ['class' =>'form-control validate', 'id' => 'p90_12']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('os_compositional_factor') ? 'has-error' : ''}}">
                     {!! Form::label('Weight', 'Weight') !!}
                     {!! Form::text('os_compositional_factor', $statistical->subparameters->os_compositional_factor ? $statistical->subparameters->os_compositional_factor : 0.2, ['class' =>'form-control weight_os_count', 'id' => 'weight_os_3_value']) !!}
                  </div>
               </div>
            </div>
         </div>

         <hr>

         <div class="row">
            <div class="col-md-12">
               <div class="form-inline" role="form">
                  <div class="form-group">
                     <a target="_blank" href="{{ URL::route('histo', ['subp' => 13, 'statistical' => $statistical])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="{{asset('images/historics.png')}}" width="25" height="25"/>
                     </button></a>
                     <a target="_blank" href="{{ URL::route('freq', ['subp' => 13, 'statistical' => $statistical])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="{{asset('images/est.png')}}" width="25" height="25"/>
                     </button></a>
                     <button type="button" class="btn btn-default" aria-label="Left Align" id="popover13">
                     <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>
                     </button>
                     <a target="_blank" href="{{ URL::route('Geor', ['subp' => 13, 'multi' => $statistical->id])}}"><button type="button" class="btn btn-default" aria-label="Left Align">
                     <span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span>
                     </button></a>&nbsp&nbsp&nbsp
                     {!! Form::label('', 'Number Of Days Below Saturation Pressure') !!}
                  </div>
                  <div class="pull-right">
                     {!! Form::checkbox('osAvailable[]',4,false, array('id'=>'weight_os_4', 'class' => 'check_weight')) !!}
                     {!! Form::label('available', 'Available') !!}
                     {!! Form::hidden('weight_os_4_hidden', '', array('class' => 'form-control', 'id' => 'weight_os_4_hidden')) !!}
                  </div>
               </div>
            </div>
         </div>

         <br>

         <div id="weight_os_4_div">
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group">
                     {!! Form::label('value', 'Value') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     <div class="input-group {{$errors->has('OS4') ? 'has-error' : ''}}">
                        {!! Form::text('OS4', $statistical->os4, ['placeholder' => 'Days', 'class' =>'form-control value_edit', 'id' => 'OS4']) !!}
                        <span class="input-group-addon" id="basic-addon2">Days</span>
                     </div>
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('dateOS4') ? 'has-error' : ''}}">
                     {!! Form::label('date', 'Monitoring Date') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('dateOS4', $statistical->date_os4, ['placeholder' => 'dd/mm/yyyy', 'class' =>'form-control value_edit jquery-datepicker', 'id' => 'dateOS4']); !!}
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('OS4comment') ? 'has-error' : ''}}">
                     {!! Form::label('comment', 'Comment') !!}
                     {!! Form::text('OS4comment', $statistical->comment_os4, ['class' =>'form-control validate', 'id' => 'OS4comment']) !!}
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p10_OS4') ? 'has-error' : ''}}">
                     {!! Form::label('p10', 'p10') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p10_OS4', $statistical->p10_os4, ['class' =>'form-control validate', 'id' => 'p10_13']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p90_OS4') ? 'has-error' : ''}}">
                     {!! Form::label('p90', 'p90') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p90_OS4', $statistical->p90_os4, ['class' =>'form-control validate', 'id' => 'p90_13']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('os_pressure_factor') ? 'has-error' : ''}}">
                     {!! Form::label('Weight', 'Weight') !!}
                     {!! Form::text('os_pressure_factor', $statistical->subparameters->os_pressure_factor ? $statistical->subparameters->os_pressure_factor : 0.2, ['class' =>'form-control weight_os_count', 'id' => 'weight_os_4_value']) !!}
                  </div>
               </div>
            </div>
         </div>

         <hr>

         <div class="row">
            <div class="col-md-12">
               <div class="form-inline" role="form">
                  <div class="form-group">
                     <a target="_blank" href="{{ URL::route('histo', ['subp' => 14, 'statistical' => $statistical])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="{{asset('images/historics.png')}}" width="25" height="25"/>
                     </button></a>
                     <a target="_blank" href="{{ URL::route('freq', ['subp' => 14, 'statistical' => $statistical])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="{{asset('images/est.png')}}" width="25" height="25"/>
                     </button></a>
                     <button type="button" class="btn btn-default" aria-label="Left Align" id="popover14">
                     <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>
                     </button>
                     <a target="_blank" href="{{ URL::route('Geor', ['subp' => 14, 'multi' => $statistical->id])}}"><button type="button" class="btn btn-default" aria-label="Left Align">
                     <span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span>
                     </button></a>&nbsp&nbsp&nbsp
                     {!! Form::label('', 'De Boer Criteria') !!}
                  </div>
                  <div class="pull-right">
                     {!! Form::checkbox('osAvailable[]',5,false, array('id'=>'weight_os_5', 'class' => 'check_weight')) !!}
                     {!! Form::label('available', 'Available') !!}
                     {!! Form::hidden('weight_os_5_hidden', '', array('class' => 'form-control', 'id' => 'weight_os_5_hidden')) !!}
                  </div>
               </div>
            </div>
         </div>

         <br>

         <div id="weight_os_5_div">
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group">
                     {!! Form::label('value', 'Value') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     <div class="input-group {{$errors->has('OS5') ? 'has-error' : ''}}">
                        {!! Form::text('OS5', $statistical->os5, ['placeholder' => '-', 'class' =>'form-control value_edit', 'id' => 'OS5']) !!}
                        <span class="input-group-addon" id="basic-addon2">-</span>
                     </div>
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('dateOS5') ? 'has-error' : ''}}">
                     {!! Form::label('date', 'Monitoring Date') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('dateOS5', $statistical->date_os5, ['placeholder' => 'dd/mm/yyyy', 'class' =>'form-control value_edit jquery-datepicker', 'id' => 'dateOS5']); !!}
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('OS5comment') ? 'has-error' : ''}}">
                     {!! Form::label('comment', 'Comment') !!}
                     {!! Form::text('OS5comment', $statistical->comment_os5, ['class' =>'form-control validate', 'id' => 'OS5comment']) !!}
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p10_OS5') ? 'has-error' : ''}}">
                     {!! Form::label('p10', 'p10') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p10_OS5', $statistical->p10_os5, ['class' =>'form-control validate', 'id' => 'p10_14']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p90_OS5') ? 'has-error' : ''}}">
                     {!! Form::label('p90', 'p90') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p90_OS5', $statistical->p90_os5, ['class' =>'form-control validate', 'id' => 'p90_14']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('os_high_impact_factor') ? 'has-error' : ''}}">
                     {!! Form::label('Weight', 'Weight') !!}
                     {!! Form::text('os_high_impact_factor', $statistical->subparameters->os_high_impact_factor ? $statistical->subparameters->os_high_impact_factor : 0.2, ['class' =>'form-control weight_os_count', 'id' => 'weight_os_5_value']) !!}
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>