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
                        {!! Form::text('os1',Session::get('OS1')->valor, ['placeholder' => '-', 'class' =>'form-control value_edit', 'id' => 'OS1']) !!}
                        @else
                        {!! Form::text('os1',null, ['placeholder' => '-', 'class' =>'form-control value_edit', 'id' => 'OS1']) !!}
                        @endif
                        <span class="input-group-addon" id="basic-addon2">-</span>
                     </div>
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('dateOS1') ? 'has-error' : ''}}">
                     {!! Form::label('date', 'Monitoring Date') !!}
                     @if (Session::has('OS1'))
                     {!! Form::date('date_os1', Session::get('OS1')->fecha, ['class' =>'form-control value_edit', 'id' => 'dateOS1']); !!}
                     @else
                     {!! Form::date('date_os1',null, ['class' =>'form-control value_edit', 'id' => 'dateOS1']); !!}
                     @endif
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('OS1comment') ? 'has-error' : ''}}">
                     {!! Form::label('comment', 'Comment') !!}
                     @if (Session::has('OS1'))
                     {!! Form::text('comment_os1', Session::get('OS1')->comentario, ['class' =>'form-control validate', 'id' => 'OS1comment']) !!}    
                     @else 
                     {!! Form::text('comment_os1',null, ['class' =>'form-control validate', 'id' => 'OS1comment']) !!}
                     @endif
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p10_os1') ? 'has-error' : ''}}">
                     {!! Form::label('p10', 'p10') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p10_os1',null, ['class' =>'form-control validate', 'id' => 'p10_11']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p90_os1') ? 'has-error' : ''}}">
                     {!! Form::label('p90', 'p90') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p90_os1',null, ['class' =>'form-control validate', 'id' => 'p90_11']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('os_cll_factor') ? 'has-error' : ''}}">
                     {!! Form::label('Weight', 'Weight') !!}
                     {!! Form::text('os_cll_factor',$statistical->subparameters->os_cll_factor ? $statistical->subparameters->os_cll_factor : 0.25, ['class' =>'form-control weight_count', 'id' => 'weight_11_value']) !!}
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
                     <button type="button" class="btn btn-default" aria-label="Left Align" id="popover12">
                     <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>
                     </button>
                     <a target="_blank" href="{{ URL::route('Geor', ['subp' => 13, 'multi' => $statistical->id])}}"><button type="button" class="btn btn-default" aria-label="Left Align">
                     <span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span>
                     </button></a>&nbsp&nbsp&nbsp
                     {!! Form::label('', 'Compositional Factor: Cumulative Gas Produced') !!}
                  </div>
                  <div class="pull-right">
                     {!! Form::checkbox('osAvailable[]',2,false, array('id'=>'weight_12', 'class' => 'check_weight')) !!}
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
                     <div class="input-group {{$errors->has('OS2') ? 'has-error' : ''}}">
                        @if (Session::has('OS2'))
                        {!! Form::text('os2',Session::get('OS2')->valor, ['placeholder' => 'mMMSCF', 'class' =>'form-control value_edit', 'id' => 'OS2']) !!} 
                        @else
                        {!! Form::text('os2',null, ['placeholder' => 'mMMSCF', 'class' =>'form-control value_edit', 'id' => 'OS2']) !!}
                        @endif 
                        <span class="input-group-addon" id="basic-addon2">mMMSCF</span>
                     </div>
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('dateOS2') ? 'has-error' : ''}}">
                     {!! Form::label('date', 'Monitoring Date') !!}
                     @if (Session::has('OS2'))
                     {!! Form::date('date_os2', Session::get('OS2')->fecha, ['class' =>'form-control value_edit', 'id' => 'dateOS2']); !!}
                     @else
                     {!! Form::date('date_os2',null, ['class' =>'form-control value_edit', 'id' => 'dateOS2']); !!}
                     @endif
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('OS2comment') ? 'has-error' : ''}}">
                     {!! Form::label('comment', 'Comment') !!}
                     @if (Session::has('OS2'))
                     {!! Form::text('comment_os2',Session::get('OS2')->comentario, ['class' =>'form-control validate', 'id' => 'OS2comment']) !!}    
                     @else 
                     {!! Form::text('comment_os2',null, ['class' =>'form-control validate', 'id' => 'OS2comment']) !!}
                     @endif
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p10_os2') ? 'has-error' : ''}}">
                     {!! Form::label('p10', 'p10') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p10_os2',null, ['class' =>'form-control validate', 'id' => 'p10_12']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p90_os2') ? 'has-error' : ''}}">
                     {!! Form::label('p90', 'p90') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p90_os2',null, ['class' =>'form-control validate', 'id' => 'p90_12']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('os_compositional_factor') ? 'has-error' : ''}}">
                     {!! Form::label('Weight', 'Weight') !!}
                     {!! Form::text('os_compositional_factor',$statistical->subparameters->os_compositional_factor ? $statistical->subparameters->os_compositional_factor : 0.25, ['class' =>'form-control weight_count', 'id' => 'weight_12_value']) !!}
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
                     <button type="button" class="btn btn-default" aria-label="Left Align" id="popover13">
                     <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>
                     </button>
                     <a target="_blank" href="{{ URL::route('Geor', ['subp' => 14, 'multi' => $statistical->id])}}"><button type="button" class="btn btn-default" aria-label="Left Align">
                     <span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span>
                     </button></a>&nbsp&nbsp&nbsp
                     {!! Form::label('', 'Pressure Factor: Number Of Days Below Saturation Pressure') !!}
                  </div>
                  <div class="pull-right">
                     {!! Form::checkbox('osAvailable[]',3,false, array('id'=>'weight_13', 'class' => 'check_weight')) !!}
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
                     <div class="input-group {{$errors->has('OS3') ? 'has-error' : ''}}">
                        @if (Session::has('OS3'))
                        {!! Form::text('os3',Session::get('OS3')->valor, ['placeholder' => 'Days', 'class' =>'form-control value_edit', 'id' => 'OS3']) !!}
                        @else
                        {!! Form::text('os3',null, ['placeholder' => 'Days', 'class' =>'form-control value_edit', 'id' => 'OS3']) !!}
                        @endif
                        <span class="input-group-addon" id="basic-addon2">Days</span>
                     </div>
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('dateOS3') ? 'has-error' : ''}}">
                     {!! Form::label('date', 'Monitoring Date') !!}
                     @if (Session::has('OS3'))
                     {!! Form::date('date_os3', Session::get('OS3')->fecha, ['class' =>'form-control value_edit', 'id' => 'dateOS3']); !!}
                     @else
                     {!! Form::date('date_os3',null, ['class' =>'form-control value_edit', 'id' => 'dateOS3']); !!}
                     @endif
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('OS3comment') ? 'has-error' : ''}}">
                     {!! Form::label('comment', 'Comment') !!}
                     @if (Session::has('OS3'))
                     {!! Form::text('comment_os3',Session::get('OS3')->comentario, ['class' =>'form-control validate', 'id' => 'OS3comment']) !!}    
                     @else 
                     {!! Form::text('comment_os3',null, ['class' =>'form-control validate', 'id' => 'OS3comment']) !!}
                     @endif
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p10_os3') ? 'has-error' : ''}}">
                     {!! Form::label('p10', 'p10') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p10_os3',null, ['class' =>'form-control validate', 'id' => 'p10_13']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p90_os3') ? 'has-error' : ''}}">
                     {!! Form::label('p90', 'p90') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p90_os3',null, ['class' =>'form-control validate', 'id' => 'p90_13']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('os_pressure_factor') ? 'has-error' : ''}}">
                     {!! Form::label('Weight', 'Weight') !!}
                     {!! Form::text('os_pressure_factor',$statistical->subparameters->os_pressure_factor ? $statistical->subparameters->os_pressure_factor : 0.25, ['class' =>'form-control weight_count', 'id' => 'weight_13_value']) !!}
                  </div>
               </div>
            </div>
         </div>

         <hr>

         <div class="row">
            <div class="col-md-12">
               <div class="form-inline" role="form">
                  <div class="form-group">
                     <a target="_blank" href="{{ URL::route('histo', ['subp' => 15, 'statistical' => $statistical])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="{{asset('images/historics.png')}}" width="25" height="25"/>
                     </button></a>
                     <a target="_blank" href="{{ URL::route('freq', ['subp' => 15, 'statistical' => $statistical])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="{{asset('images/est.png')}}" width="25" height="25"/>
                     </button></a>
                     <button type="button" class="btn btn-default" aria-label="Left Align" id="popover14">
                     <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>
                     </button>
                     <a target="_blank" href="{{ URL::route('Geor', ['subp' => 15, 'multi' => $statistical->id])}}"><button type="button" class="btn btn-default" aria-label="Left Align">
                     <span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span>
                     </button></a>&nbsp&nbsp&nbsp
                     {!! Form::label('', 'High Impact Factor: De Boer Criteria') !!}
                  </div>
                  <div class="pull-right">
                     {!! Form::checkbox('osAvailable[]',4,false, array('id'=>'weight_14', 'class' => 'check_weight')) !!}
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
                     <div class="input-group {{$errors->has('OS4') ? 'has-error' : ''}}">
                        @if (Session::get('OS4'))
                        {!! Form::text('os4',Session::get('OS4')->valor, ['placeholder' => '-', 'class' =>'form-control value_edit', 'id' => 'OS4']) !!}
                        @else
                        {!! Form::text('os4',null, ['placeholder' => '-', 'class' =>'form-control value_edit', 'id' => 'OS4']) !!}
                        @endif
                        <span class="input-group-addon" id="basic-addon2">-</span>
                     </div>
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('dateOS4') ? 'has-error' : ''}}">
                     {!! Form::label('date', 'Monitoring Date') !!}
                     @if (Session::get('OS4'))
                     {!! Form::date('date_os4', Session::get('OS4')->fecha, ['class' =>'form-control value_edit', 'id' => 'dateOS4']); !!}
                     @else
                     {!! Form::date('date_os4',null, ['class' =>'form-control value_edit', 'id' => 'dateOS4']); !!}
                     @endif
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('OS4comment') ? 'has-error' : ''}}">
                     {!! Form::label('comment', 'Comment') !!}
                     @if (Session::get('OS4'))
                     {!! Form::text('comment_os4',Session::get('OS4')->comentario, ['class' =>'form-control validate', 'id' => 'OS4comment']) !!}    
                     @else 
                     {!! Form::text('comment_os4',null, ['class' =>'form-control validate', 'id' => 'OS4comment']) !!}
                     @endif
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p10_os4') ? 'has-error' : ''}}">
                     {!! Form::label('p10', 'p10') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p10_os4',null, ['class' =>'form-control validate', 'id' => 'p10_14']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p90_os4') ? 'has-error' : ''}}">
                     {!! Form::label('p90', 'p90') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p90_os4',null, ['class' =>'form-control validate', 'id' => 'p90_14']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('os_high_impact_factor') ? 'has-error' : ''}}">
                     {!! Form::label('Weight', 'Weight') !!}
                     {!! Form::text('os_high_impact_factor',$statistical->subparameters->os_high_impact_factor ? $statistical->subparameters->os_high_impact_factor : 0.25, ['class' =>'form-control weight_count', 'id' => 'weight_14_value']) !!}
                  </div>
               </div>
            </div>
         </div>
         <hr>
      </div>
   </div>
</div>