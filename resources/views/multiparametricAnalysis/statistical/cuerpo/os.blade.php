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
                     {!! Form::checkbox('osAvailable[]',1,false, array('id'=>'weight_11', 'class' => 'check_weight')) !!}
                     {!! Form::label('available', 'Available') !!}
                     {!! Form::hidden('weight_11_hidden', '', array('class' => 'form-control', 'id' => 'weight_11_hidden')) !!}
                  </div>
               </div>
            </div>
         </div>

         <br>

         <div id="weight_11_div">
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group">
                     {!! Form::label('value', 'Value') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     <div class="input-group {{$errors->has('OS1') ? 'has-error' : ''}}">
                        @if (Session::has('OS1'))
                        {!! Form::text('OS1',Session::get('OS1')->valor, ['placeholder' => '-', 'class' =>'form-control value_edit', 'id' => 'OS1']) !!}
                        @else
                        {!! Form::text('OS1',null, ['placeholder' => '-', 'class' =>'form-control value_edit', 'id' => 'OS1']) !!}
                        @endif
                        <span class="input-group-addon" id="basic-addon2">-</span>
                     </div>
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('dateOS1') ? 'has-error' : ''}}">
                     {!! Form::label('date', 'Monitoring Date') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     @if (Session::has('OS1'))
                     {!! Form::text('dateOS1', Session::get('OS1')->fecha, ['placeholder' => 'dd/mm/yyyy', 'class' =>'form-control value_edit jquery-datepicker', 'id' => 'dateOS1']); !!}
                     @else
                     {!! Form::text('dateOS1', null, ['placeholder' => 'dd/mm/yyyy', 'class' =>'form-control value_edit jquery-datepicker', 'id' => 'dateOS1']); !!}
                     @endif
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('OS1comment') ? 'has-error' : ''}}">
                     {!! Form::label('comment', 'Comment') !!}
                     @if (Session::has('OS1'))
                     {!! Form::text('OS1comment', Session::get('OS1')->comentario, ['class' =>'form-control validate', 'id' => 'OS1comment']) !!}    
                     @else 
                     {!! Form::text('OS1comment',null, ['class' =>'form-control validate', 'id' => 'OS1comment']) !!}
                     @endif
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p10_OS1') ? 'has-error' : ''}}">
                     {!! Form::label('p10', 'p10') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p10_OS1',null, ['class' =>'form-control validate', 'id' => 'p10_11']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p90_OS1') ? 'has-error' : ''}}">
                     {!! Form::label('p90', 'p90') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p90_OS1',null, ['class' =>'form-control validate', 'id' => 'p90_11']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('os_cll_factor') ? 'has-error' : ''}}">
                     {!! Form::label('Weight', 'Weight') !!}
                     {!! Form::text('os_cll_factor',$statistical->subparameters->os_cll_factor ? $statistical->subparameters->os_cll_factor : 0.2, ['class' =>'form-control weight_count', 'id' => 'weight_11_value']) !!}
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
                     {!! Form::checkbox('osAvailable[]',2,false, array('id'=>'weight_30', 'class' => 'check_weight')) !!}
                     {!! Form::label('available', 'Available') !!}
                     {!! Form::hidden('weight_30_hidden', '', array('class' => 'form-control', 'id' => 'weight_30_hidden')) !!}
                  </div>
               </div>
            </div>
         </div>

         <br>

         <div id="weight_30_div">
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group">
                     {!! Form::label('value', 'Value') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     <div class="input-group {{$errors->has('OS2') ? 'has-error' : ''}}">
                        @if (Session::has('OS2'))
                        {!! Form::text('OS2',Session::get('OS2')->valor, ['placeholder' => 'bbl', 'class' =>'form-control value_edit', 'id' => 'OS2']) !!} 
                        @else
                        {!! Form::text('OS2',null, ['placeholder' => 'bbl', 'class' =>'form-control value_edit', 'id' => 'OS2']) !!}
                        @endif 
                        <span class="input-group-addon" id="basic-addon2">bbl</span>
                     </div>
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('dateOS2') ? 'has-error' : ''}}">
                     {!! Form::label('date', 'Monitoring Date') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     @if (Session::has('OS2'))
                     {!! Form::text('dateOS2', Session::get('OS2')->fecha, ['placeholder' => 'dd/mm/yyyy', 'class' =>'form-control value_edit jquery-datepicker', 'id' => 'dateOS2']); !!}
                     @else
                     {!! Form::text('dateOS2', null, ['placeholder' => 'dd/mm/yyyy', 'class' =>'form-control value_edit jquery-datepicker', 'id' => 'dateOS2']); !!}
                     @endif
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('OS2comment') ? 'has-error' : ''}}">
                     {!! Form::label('comment', 'Comment') !!}
                     @if (Session::has('OS2'))
                     {!! Form::text('OS2comment',Session::get('OS2')->comentario, ['class' =>'form-control validate', 'id' => 'OS2comment']) !!}    
                     @else 
                     {!! Form::text('OS2comment',null, ['class' =>'form-control validate', 'id' => 'OS2comment']) !!}
                     @endif
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p10_OS2') ? 'has-error' : ''}}">
                     {!! Form::label('p10', 'p10') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p10_OS2',null, ['class' =>'form-control validate', 'id' => 'p10_30']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p90_OS2') ? 'has-error' : ''}}">
                     {!! Form::label('p90', 'p90') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p90_OS2',null, ['class' =>'form-control validate', 'id' => 'p90_30']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('os_volume_of_hcl') ? 'has-error' : ''}}">
                     {!! Form::label('Weight', 'Weight') !!}
                     {!! Form::text('os_volume_of_hcl',$statistical->subparameters->os_volume_of_hcl ? $statistical->subparameters->os_volume_of_hcl : 0.2, ['class' =>'form-control weight_count', 'id' => 'weight_30_value']) !!}
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
                     {!! Form::checkbox('osAvailable[]',3,false, array('id'=>'weight_12', 'class' => 'check_weight')) !!}
                     {!! Form::label('available', 'Available') !!}
                     {!! Form::hidden('weight_12_hidden', '', array('class' => 'form-control', 'id' => 'weight_12_hidden')) !!}
                  </div>
               </div>
            </div>
         </div>

         <br>

         <div id="weight_12_div">
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group">
                     {!! Form::label('value', 'Value') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     <div class="input-group {{$errors->has('OS3') ? 'has-error' : ''}}">
                        @if (Session::has('OS3'))
                        {!! Form::text('OS3',Session::get('OS3')->valor, ['placeholder' => 'mMMSCF', 'class' =>'form-control value_edit', 'id' => 'OS3']) !!} 
                        @else
                        {!! Form::text('OS3',null, ['placeholder' => 'mMMSCF', 'class' =>'form-control value_edit', 'id' => 'OS3']) !!}
                        @endif 
                        <span class="input-group-addon" id="basic-addon2">mMMSCF</span>
                     </div>
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('dateOS3') ? 'has-error' : ''}}">
                     {!! Form::label('date', 'Monitoring Date') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     @if (Session::has('OS3'))
                     {!! Form::text('dateOS3', Session::get('OS3')->fecha, ['placeholder' => 'dd/mm/yyyy', 'class' =>'form-control value_edit jquery-datepicker', 'id' => 'dateOS3']); !!}
                     @else
                     {!! Form::text('dateOS3', null, ['placeholder' => 'dd/mm/yyyy', 'class' =>'form-control value_edit jquery-datepicker', 'id' => 'dateOS3']); !!}
                     @endif
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('OS3comment') ? 'has-error' : ''}}">
                     {!! Form::label('comment', 'Comment') !!}
                     @if (Session::has('OS3'))
                     {!! Form::text('OS3comment',Session::get('OS3')->comentario, ['class' =>'form-control validate', 'id' => 'OS3comment']) !!}    
                     @else 
                     {!! Form::text('OS3comment',null, ['class' =>'form-control validate', 'id' => 'OS3comment']) !!}
                     @endif
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p10_OS3') ? 'has-error' : ''}}">
                     {!! Form::label('p10', 'p10') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p10_OS3',null, ['class' =>'form-control validate', 'id' => 'p10_12']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p90_OS3') ? 'has-error' : ''}}">
                     {!! Form::label('p90', 'p90') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p90_OS3',null, ['class' =>'form-control validate', 'id' => 'p90_12']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('os_compositional_factor') ? 'has-error' : ''}}">
                     {!! Form::label('Weight', 'Weight') !!}
                     {!! Form::text('os_compositional_factor',$statistical->subparameters->os_compositional_factor ? $statistical->subparameters->os_compositional_factor : 0.2, ['class' =>'form-control weight_count', 'id' => 'weight_12_value']) !!}
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
                     {!! Form::checkbox('osAvailable[]',4,false, array('id'=>'weight_13', 'class' => 'check_weight')) !!}
                     {!! Form::label('available', 'Available') !!}
                     {!! Form::hidden('weight_13_hidden', '', array('class' => 'form-control', 'id' => 'weight_13_hidden')) !!}
                  </div>
               </div>
            </div>
         </div>

         <br>

         <div id="weight_13_div">
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group">
                     {!! Form::label('value', 'Value') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     <div class="input-group {{$errors->has('OS4') ? 'has-error' : ''}}">
                        @if (Session::has('OS4'))
                        {!! Form::text('OS4',Session::get('OS4')->valor, ['placeholder' => 'Days', 'class' =>'form-control value_edit', 'id' => 'OS4']) !!}
                        @else
                        {!! Form::text('OS4',null, ['placeholder' => 'Days', 'class' =>'form-control value_edit', 'id' => 'OS4']) !!}
                        @endif
                        <span class="input-group-addon" id="basic-addon2">Days</span>
                     </div>
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('dateOS4') ? 'has-error' : ''}}">
                     {!! Form::label('date', 'Monitoring Date') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     @if (Session::has('OS4'))
                     {!! Form::text('dateOS4', Session::get('OS4')->fecha, ['placeholder' => 'dd/mm/yyyy', 'class' =>'form-control value_edit jquery-datepicker', 'id' => 'dateOS4']); !!}
                     @else
                     {!! Form::text('dateOS4', null, ['placeholder' => 'dd/mm/yyyy', 'class' =>'form-control value_edit jquery-datepicker', 'id' => 'dateOS4']); !!}
                     @endif
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('OS4comment') ? 'has-error' : ''}}">
                     {!! Form::label('comment', 'Comment') !!}
                     @if (Session::has('OS4'))
                     {!! Form::text('OS4comment',Session::get('OS4')->comentario, ['class' =>'form-control validate', 'id' => 'OS4comment']) !!}    
                     @else 
                     {!! Form::text('OS4comment',null, ['class' =>'form-control validate', 'id' => 'OS4comment']) !!}
                     @endif
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p10_OS4') ? 'has-error' : ''}}">
                     {!! Form::label('p10', 'p10') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p10_OS4',null, ['class' =>'form-control validate', 'id' => 'p10_13']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p90_OS4') ? 'has-error' : ''}}">
                     {!! Form::label('p90', 'p90') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p90_OS4',null, ['class' =>'form-control validate', 'id' => 'p90_13']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('os_pressure_factor') ? 'has-error' : ''}}">
                     {!! Form::label('Weight', 'Weight') !!}
                     {!! Form::text('os_pressure_factor',$statistical->subparameters->os_pressure_factor ? $statistical->subparameters->os_pressure_factor : 0.2, ['class' =>'form-control weight_count', 'id' => 'weight_13_value']) !!}
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
                     {!! Form::checkbox('osAvailable[]',5,false, array('id'=>'weight_14', 'class' => 'check_weight')) !!}
                     {!! Form::label('available', 'Available') !!}
                     {!! Form::hidden('weight_14_hidden', '', array('class' => 'form-control', 'id' => 'weight_14_hidden')) !!}
                  </div>
               </div>
            </div>
         </div>

         <br>

         <div id="weight_14_div">
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group">
                     {!! Form::label('value', 'Value') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     <div class="input-group {{$errors->has('OS5') ? 'has-error' : ''}}">
                        @if (Session::get('OS5'))
                        {!! Form::text('OS5',Session::get('OS5')->valor, ['placeholder' => '-', 'class' =>'form-control value_edit', 'id' => 'OS5']) !!}
                        @else
                        {!! Form::text('OS5',null, ['placeholder' => '-', 'class' =>'form-control value_edit', 'id' => 'OS5']) !!}
                        @endif
                        <span class="input-group-addon" id="basic-addon2">-</span>
                     </div>
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('dateOS5') ? 'has-error' : ''}}">
                     {!! Form::label('date', 'Monitoring Date') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     @if (Session::has('OS5'))
                     {!! Form::text('dateOS5', Session::get('OS5')->fecha, ['placeholder' => 'dd/mm/yyyy', 'class' =>'form-control value_edit jquery-datepicker', 'id' => 'dateOS5']); !!}
                     @else
                     {!! Form::text('dateOS5', null, ['placeholder' => 'dd/mm/yyyy', 'class' =>'form-control value_edit jquery-datepicker', 'id' => 'dateOS5']); !!}
                     @endif
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('OS5comment') ? 'has-error' : ''}}">
                     {!! Form::label('comment', 'Comment') !!}
                     @if (Session::get('OS5'))
                     {!! Form::text('OS5comment',Session::get('OS5')->comentario, ['class' =>'form-control validate', 'id' => 'OS5comment']) !!}    
                     @else 
                     {!! Form::text('OS5comment',null, ['class' =>'form-control validate', 'id' => 'OS5comment']) !!}
                     @endif
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p10_OS5') ? 'has-error' : ''}}">
                     {!! Form::label('p10', 'p10') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p10_OS5',null, ['class' =>'form-control validate', 'id' => 'p10_14']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p90_OS5') ? 'has-error' : ''}}">
                     {!! Form::label('p90', 'p90') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p90_OS5',null, ['class' =>'form-control validate', 'id' => 'p90_14']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('os_high_impact_factor') ? 'has-error' : ''}}">
                     {!! Form::label('Weight', 'Weight') !!}
                     {!! Form::text('os_high_impact_factor',$statistical->subparameters->os_high_impact_factor ? $statistical->subparameters->os_high_impact_factor : 0.2, ['class' =>'form-control weight_count', 'id' => 'weight_14_value']) !!}
                  </div>
               </div>
            </div>
         </div>
         <hr>
      </div>
   </div>
</div>