    <div class="tab-pane" id="FB">
      <div class="panel panel-default">
         <div class="panel-heading">
            <h4>Fine Blockage</h4>
         </div>

         <div class="panel-body">
         <div class="row">
            <div class="col-md-12">
               <div class="form-inline" role="form">
                  <div class="form-group">
                     <a target="_blank" href="{{ URL::route('histo', ['subp' => 6, 'statistical' => $statistical])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="{{asset('images/historics.png')}}" width="25" height="25"/>
                     </button></a>
                     <a target="_blank" href="{{ URL::route('freq', ['subp' => 6, 'statistical' => $statistical])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="{{asset('images/est.png')}}" width="25" height="25"/>
                     </button></a>
                     <button type="button" class="btn btn-default" aria-label="Left Align" id="popover6">
                     <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>
                     </button>
                     <a target="_blank" href="{{ URL::route('Geor', ['subp' => 6, 'multi' => $statistical->id])}}"><button type="button" class="btn btn-default" aria-label="Left Align">
                     <span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span>
                     </button></a>&nbsp&nbsp&nbsp
                     {!! Form::label('ACW', '[Al] on Produced Water') !!}
                  </div>
                  <div class="pull-right">
                     {!! Form::checkbox('fbAvailable[]',1,false, array('id'=>'weight_6', 'class' => 'check_weight')) !!}
                     {!! Form::label('available', 'Available') !!}
                     {!! Form::hidden('weight_6_hidden', '', array('class' => 'form-control', 'id' => 'weight_6_hidden')) !!}
                  </div>
               </div>
            </div>
         </div>

         <br>

         <div id="weight_6_div">
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group">
                     {!! Form::label('value', 'Value') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     <div class="input-group {{$errors->has('FB1') ? 'has-error' : ''}}">
                        @if (Session::has('FB1'))
                        {!! Form::text('FB1',Session::get('FB1')->valor, ['placeholder' => 'ppm', 'class' =>'form-control pull-right value_edit', 'id' => 'FB1']) !!}
                        @else 
                        {!! Form::text('FB1',null, ['placeholder' => 'ppm', 'class' =>'form-control pull-right value_edit', 'id' => 'FB1']) !!}
                        @endif
                        <span class="input-group-addon" id="basic-addon2">ppm</span>
                     </div>
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('dateFB1') ? 'has-error' : ''}}">
                     {!! Form::label('date', 'Monitoring Date') !!}
                     @if (Session::has('FB1'))
                     {!! Form::text('dateFB1', Session::get('FB1')->fecha, ['placeholder' => 'dd/mm/yyyy', 'class' =>'form-control value_edit jquery-datepicker', 'id' => 'dateFB1']); !!}
                     @else
                     {!! Form::text('dateFB1', null, ['placeholder' => 'dd/mm/yyyy', 'class' =>'form-control value_edit jquery-datepicker', 'id' => 'dateFB1']); !!}
                     @endif
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('FB1comment') ? 'has-error' : ''}}">
                     {!! Form::label('comment', 'Comment') !!}
                     @if (Session::has('FB1'))
                     {!! Form::text('FB1comment',Session::get('FB1')->comentario, ['class' =>'form-control validate', 'id' => 'FB1comment']) !!}    
                     @else 
                     {!! Form::text('FB1comment',null, ['class' =>'form-control validate', 'id' => 'FB1comment']) !!}
                     @endif
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p10_FB1') ? 'has-error' : ''}}">
                     {!! Form::label('p10', 'p10') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p10_FB1',null, ['class' =>'form-control validate', 'id' => 'p10_6']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p90_FB1') ? 'has-error' : ''}}">
                     {!! Form::label('p90', 'p90') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p90_FB1',null, ['class' =>'form-control validate', 'id' => 'p90_6']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('fb_aluminum_concentration') ? 'has-error' : ''}}">
                     {!! Form::label('Weight', 'Weight') !!}
                     {!! Form::text('fb_aluminum_concentration',$statistical->subparameters->fb_aluminum_concentration ? $statistical->subparameters->fb_aluminum_concentration : 0.2, ['class' =>'form-control weight_count', 'id' => 'weight_6_value']) !!}
                  </div>
               </div>
            </div>
         </div>

         <hr>

         <div class="row">
            <div class="col-md-12">
               <div class="form-inline" role="form">
                  <div class="form-group">
                     <a target="_blank" href="{{ URL::route('histo', ['subp' => 7, 'statistical' => $statistical])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="{{asset('images/historics.png')}}" width="25" height="25"/>
                     </button></a>
                     <a target="_blank" href="{{ URL::route('freq', ['subp' => 7, 'statistical' => $statistical])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="{{asset('images/est.png')}}" width="25" height="25"/>
                     </button></a>
                     <button type="button" class="btn btn-default" aria-label="Left Align" id="popover7">
                     <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>
                     </button>
                     <a target="_blank" href="{{ URL::route('Geor', ['subp' => 7, 'multi' => $statistical->id])}}"><button type="button" class="btn btn-default" aria-label="Left Align">
                     <span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span>
                     </button></a>&nbsp&nbsp&nbsp
                     {!! Form::label('SCW', '[Si] on produced water') !!}
                  </div>
                  <div class="pull-right">
                     {!! Form::checkbox('fbAvailable[]',2,false, array('id'=>'weight_7', 'class' => 'check_weight')) !!}
                     {!! Form::label('available', 'Available') !!}
                     {!! Form::hidden('weight_7_hidden', '', array('class' => 'form-control', 'id' => 'weight_7_hidden')) !!}
                  </div>
               </div>
            </div>
         </div>

         <br>

         <div id="weight_7_div">
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group">
                     {!! Form::label('value', 'Value') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     <div class="input-group {{$errors->has('FB2') ? 'has-error' : ''}}">
                        @if (Session::has('FB2'))
                        {!! Form::text('FB2',Session::get('FB2')->valor, ['placeholder' => 'ppm', 'class' =>'form-control pull-right value_edit', 'id' => 'FB2']) !!}
                        @else
                        {!! Form::text('FB2',null, ['placeholder' => 'ppm', 'class' =>'form-control pull-right value_edit', 'id' => 'FB2']) !!}
                        @endif
                        <span class="input-group-addon" id="basic-addon2">ppm</span>
                     </div>
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('dateFB2') ? 'has-error' : ''}}">
                     {!! Form::label('date', 'Monitoring Date') !!}
                     @if (Session::has('FB2'))
                     {!! Form::text('dateFB2', Session::get('FB2')->fecha, ['placeholder' => 'dd/mm/yyyy', 'class' =>'form-control value_edit jquery-datepicker', 'id' => 'dateFB2']); !!}
                     @else
                     {!! Form::text('dateFB2', null, ['placeholder' => 'dd/mm/yyyy', 'class' =>'form-control value_edit jquery-datepicker', 'id' => 'dateFB2']); !!}
                     @endif
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('FB2comment') ? 'has-error' : ''}}">
                     {!! Form::label('comment', 'Comment') !!}
                     @if (Session::has('FB2'))
                     {!! Form::text('FB2comment',Session::get('FB2')->comentario, ['class' =>'form-control validate', 'id' => 'FB2comment']) !!}    
                     @else 
                     {!! Form::text('FB2comment',null, ['class' =>'form-control validate', 'id' => 'FB2comment']) !!}
                     @endif
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p10_FB2') ? 'has-error' : ''}}">
                     {!! Form::label('p10', 'p10') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p10_FB2',null, ['class' =>'form-control validate', 'id' => 'p10_7']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p90_FB2') ? 'has-error' : ''}}">
                     {!! Form::label('p90', 'p90') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p90_FB2',null, ['class' =>'form-control validate', 'id' => 'p90_7']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('fb_silicon_concentration') ? 'has-error' : ''}}">
                     {!! Form::label('Weight', 'Weight') !!}
                     {!! Form::text('fb_silicon_concentration',$statistical->subparameters->fb_silicon_concentration ? $statistical->subparameters->fb_silicon_concentration : 0.2, ['class' =>'form-control weight_count', 'id' => 'weight_7_value']) !!} 
                  </div>
               </div>
            </div>
         </div>

         <hr>

         <div class="row">
            <div class="col-md-12">
               <div class="form-inline" role="form">
                  <div class="form-group">
                     <a target="_blank" href="{{ URL::route('histo', ['subp' => 8, 'statistical' => $statistical])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="{{asset('images/historics.png')}}" width="25" height="25"/>
                     </button></a>
                     <a target="_blank" href="{{ URL::route('freq', ['subp' => 8, 'statistical' => $statistical])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="{{asset('images/est.png')}}" width="25" height="25"/>
                     </button></a>
                     <button type="button" class="btn btn-default" aria-label="Left Align" id="popover8">
                     <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>
                     </button>
                     <a target="_blank" href="{{ URL::route('Geor', ['subp' => 8, 'multi' => $statistical->id])}}"><button type="button" class="btn btn-default" aria-label="Left Align">
                     <span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span>
                     </button></a>&nbsp&nbsp&nbsp
                     {!! Form::label('CRF', 'Critical Radius derived from maximum critical velocity, Vc') !!}
                  </div>
                  <div class="pull-right">
                     {!! Form::checkbox('fbAvailable[]',3,false, array('id'=>'weight_8', 'class' => 'check_weight')) !!}
                     {!! Form::label('available', 'Available') !!}
                     {!! Form::hidden('weight_8_hidden', '', array('class' => 'form-control', 'id' => 'weight_8_hidden')) !!}
                  </div>
               </div>
            </div>
         </div>

         <br>

         <div id="weight_8_div">
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group">
                     {!! Form::label('value', 'Value') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     <div class="input-group {{$errors->has('FB3') ? 'has-error' : ''}}">
                        @if (Session::has('FB3'))
                        {!! Form::text('FB3',Session::get('FB3')->valor, ['placeholder' => 'ft', 'class' =>'form-control pull-right value_edit', 'id' => 'FB3']) !!}  
                        @else
                        {!! Form::text('FB3',null, ['placeholder' => 'ft', 'class' =>'form-control pull-right value_edit', 'id' => 'FB3']) !!}  
                        @endif
                        <span class="input-group-addon" id="basic-addon2">ft</span>
                     </div>
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('dateFB3') ? 'has-error' : ''}}">
                     {!! Form::label('date', 'Monitoring Date') !!}
                     @if (Session::has('FB3'))
                     {!! Form::text('dateFB3', Session::get('FB3')->fecha, ['placeholder' => 'dd/mm/yyyy', 'class' =>'form-control value_edit jquery-datepicker', 'id' => 'dateFB3']); !!}
                     @else
                     {!! Form::text('dateFB3', null, ['placeholder' => 'dd/mm/yyyy', 'class' =>'form-control value_edit jquery-datepicker', 'id' => 'dateFB3']); !!}
                     @endif
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('FB3comment') ? 'has-error' : ''}}">
                     {!! Form::label('comment', 'Comment') !!}
                     @if (Session::has('FB3'))
                     {!! Form::text('FB3comment',Session::get('FB3')->comentario , ['class' =>'form-control validate', 'id' => 'FB3comment']) !!}    
                     @else 
                     {!! Form::text('FB3comment',null, ['class' =>'form-control validate', 'id' => 'FB3comment']) !!}
                     @endif
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p10_FB3') ? 'has-error' : ''}}">
                     {!! Form::label('p10', 'p10') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p10_FB3',null, ['class' =>'form-control validate', 'id' => 'p10_8']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p90_FB3') ? 'has-error' : ''}}">
                     {!! Form::label('p90', 'p90') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p90_FB3',null, ['class' =>'form-control validate', 'id' => 'p90_8']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('fb_critical_radius_factor') ? 'has-error' : ''}}">
                     {!! Form::label('Weight', 'Weight') !!}
                     {!! Form::text('fb_critical_radius_factor',$statistical->subparameters->fb_critical_radius_factor ? $statistical->subparameters->fb_critical_radius_factor : 0.2, ['class' =>'form-control weight_count', 'id' => 'weight_8_value']) !!}
                  </div>
               </div>
            </div>
         </div>

         <hr>

         <div class="row">
            <div class="col-md-12">
               <div class="form-inline" role="form">
                  <div class="form-group">
                     <a target="_blank" href="{{ URL::route('histo', ['subp' => 9, 'statistical' => $statistical])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="{{asset('images/historics.png')}}" width="25" height="25"/>
                     </button></a>
                     <a target="_blank" href="{{ URL::route('freq', ['subp' => 9, 'statistical' => $statistical])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="{{asset('images/est.png')}}" width="25" height="25"/>
                     </button></a>
                     <button type="button" class="btn btn-default" aria-label="Left Align" id="popover9">
                     <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>
                     </button>
                     <a target="_blank" href="{{ URL::route('Geor', ['subp' => 9, 'multi' => $statistical->id])}}"><button type="button" class="btn btn-default" aria-label="Left Align">
                     <span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span>
                     </button></a>&nbsp&nbsp&nbsp
                     {!! Form::label('MF', 'Mineralogy Factor') !!}
                  </div>
                  <div class="pull-right">
                     {!! Form::checkbox('fbAvailable[]',4,false, array('id'=>'weight_9', 'class' => 'check_weight')) !!}
                     {!! Form::label('available', 'Available') !!}
                     {!! Form::hidden('weight_9_hidden', '', array('class' => 'form-control', 'id' => 'weight_9_hidden')) !!}
                  </div>
               </div>
            </div>
         </div>

         <br>

         <div id="weight_9_div">
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group">
                     {!! Form::label('value', 'Value') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     <div class="input-group {{$errors->has('FB4') ? 'has-error' : ''}}">
                        @if(Session::has('FB4'))
                        {!! Form::text('FB4',Session::get('FB4')->valor, ['placeholder' => '-', 'class' =>'form-control pull-right value_edit', 'id' => 'FB4']) !!}
                        @else
                        {!! Form::text('FB4',null, ['placeholder' => '-', 'class' =>'form-control pull-right value_edit', 'id' => 'FB4']) !!}
                        @endif
                        <span class="input-group-addon" id="basic-addon2">-</span>
                     </div>
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('dateFB4') ? 'has-error' : ''}}">
                     {!! Form::label('date', 'Monitoring Date') !!}
                     @if (Session::has('FB4'))
                     {!! Form::text('dateFB4', Session::get('FB4')->fecha, ['placeholder' => 'dd/mm/yyyy', 'class' =>'form-control value_edit jquery-datepicker', 'id' => 'dateFB4']); !!}
                     @else
                     {!! Form::text('dateFB4', null, ['placeholder' => 'dd/mm/yyyy', 'class' =>'form-control value_edit jquery-datepicker', 'id' => 'dateFB4']); !!}
                     @endif
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('FB4comment') ? 'has-error' : ''}}">
                     {!! Form::label('comment', 'Comment') !!}
                     @if (Session::has('FB4'))
                     {!! Form::text('FB4comment', Session::get('FB4')->comentario, ['class' =>'form-control validate', 'id' => 'FB4comment']) !!}    
                     @else 
                     {!! Form::text('FB4comment',null, ['class' =>'form-control validate', 'id' => 'FB4comment']) !!}
                     @endif
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p10_FB4') ? 'has-error' : ''}}">
                     {!! Form::label('p10', 'p10') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p10_FB4',null, ['class' =>'form-control validate', 'id' => 'p10_9']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p90_FB4') ? 'has-error' : ''}}">
                     {!! Form::label('p90', 'p90') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p90_FB4',null, ['class' =>'form-control validate', 'id' => 'p90_9']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('fb_mineralogic_factor') ? 'has-error' : ''}}">
                     {!! Form::label('Weight', 'Weight') !!}
                     {!! Form::text('fb_mineralogic_factor',$statistical->subparameters->fb_mineralogic_factor ? $statistical->subparameters->fb_mineralogic_factor : 0.2, ['class' =>'form-control weight_count', 'id' => 'weight_9_value']) !!}
                  </div>
               </div>
            </div>
         </div>

         <hr>

         <div class="row">
            <div class="col-md-12">
               <div class="form-inline" role="form">
                  <div class="form-group">
                     <a target="_blank" href="{{ URL::route('histo', ['subp' => 10, 'statistical' => $statistical])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="{{asset('images/historics.png')}}" width="25" height="25"/>
                     </button></a>
                     <a target="_blank" href="{{ URL::route('freq', ['subp' => 10, 'statistical' => $statistical])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="{{asset('images/est.png')}}" width="25" height="25"/>
                     </button></a>
                     <button type="button" class="btn btn-default" aria-label="Left Align" id="popover10">
                     <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>
                     </button>
                     <a target="_blank" href="{{ URL::route('Geor', ['subp' => 10, 'multi' => $statistical->id])}}"><button type="button" class="btn btn-default" aria-label="Left Align">
                     <span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span>
                     </button></a>&nbsp&nbsp&nbsp
                     {!! Form::label('CPF', 'Mass of crushed proppant inside Hydraulic Fractures') !!}
                  </div>
                  <div class="pull-right">
                     {!! Form::checkbox('fbAvailable[]',5,false, array('id'=>'weight_10', 'class' => 'check_weight')) !!}
                     {!! Form::label('available', 'Available') !!}
                     {!! Form::hidden('weight_10_hidden', '', array('class' => 'form-control', 'id' => 'weight_10_hidden')) !!}
                  </div>
               </div>
            </div>
         </div>

         <br>  

         <div id="weight_10_div">
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group">
                     {!! Form::label('value', 'Value') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     <div class="input-group {{$errors->has('FB5') ? 'has-error' : ''}}">
                        @if (Session::has('FB5'))
                        {!! Form::text('FB5',Session::get('FB5')->valor, ['placeholder' => 'lbs', 'class' =>'form-control pull-right value_edit', 'id' => 'FB5']) !!}
                        @else
                        {!! Form::text('FB5',null, ['placeholder' => 'lbs', 'class' =>'form-control pull-right value_edit', 'id' => 'FB5']) !!}
                        @endif
                        <span class="input-group-addon" id="basic-addon2">lbs</span>
                     </div>
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('dateFB5') ? 'has-error' : ''}}">
                     {!! Form::label('date', 'Monitoring Date') !!}
                     @if (Session::has('FB5'))
                     {!! Form::text('dateFB5', Session::get('FB5')->fecha, ['placeholder' => 'dd/mm/yyyy', 'class' =>'form-control value_edit jquery-datepicker', 'id' => 'dateFB5']); !!}
                     @else
                     {!! Form::text('dateFB5', null, ['placeholder' => 'dd/mm/yyyy', 'class' =>'form-control value_edit jquery-datepicker', 'id' => 'dateFB5']); !!}
                     @endif
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('FB5comment') ? 'has-error' : ''}}">
                     {!! Form::label('comment', 'Comment') !!}
                     @if (Session::has('FB5'))
                     {!! Form::text('FB5comment', Session::get('FB5')->comentario, ['class' =>'form-control validate', 'id' => 'FB5comment']) !!}    
                     @else 
                     {!! Form::text('FB5comment',null, ['class' =>'form-control validate', 'id' => 'FB5comment']) !!}
                     @endif
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p10_FB5') ? 'has-error' : ''}}">
                     {!! Form::label('p10', 'p10') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p10_FB5',null, ['class' =>'form-control validate', 'id' => 'p10_10']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p90_FB5') ? 'has-error' : ''}}">
                     {!! Form::label('p90', 'p90') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p90_FB5',null, ['class' =>'form-control validate', 'id' => 'p90_10']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('fb_crushed_proppant_factor') ? 'has-error' : ''}}">
                     {!! Form::label('Weight', 'Weight') !!}
                     {!! Form::text('fb_crushed_proppant_factor',$statistical->subparameters->fb_crushed_proppant_factor ? $statistical->subparameters->fb_crushed_proppant_factor : 0.2, ['class' =>'form-control weight_count', 'id' => 'weight_10_value']) !!}     
                  </div>
               </div>
            </div>
         </div>
         <hr>
      </div>
      </div>
   </div>