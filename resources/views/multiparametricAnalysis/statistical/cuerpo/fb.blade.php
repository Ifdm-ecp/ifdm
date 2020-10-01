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
                     <img src="{{asset('images/historics.png')}}" width="25" height="24"/>
                     </button></a>
                     <a target="_blank" href="{{ URL::route('freq', ['subp' => 6, 'statistical' => $statistical])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="{{asset('images/est.png')}}" width="25" height="24"/>
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
                     {!! Form::checkbox('fbAvailable[]',1,false, array('id'=>'weight_fb_1', 'class' => 'check_weight')) !!}
                     {!! Form::label('available', 'Available') !!}
                     {!! Form::hidden('weight_fb_1_hidden', '', array('class' => 'form-control', 'id' => 'weight_fb_1_hidden')) !!}
                  </div>
               </div>
            </div>
         </div>

         <br>

         <div id="weight_fb_1_div">
            <div class="row">
               <div class="col-xs-12 col-md-4">
                  <div class="form-group">
                     {!! Form::label('stored', 'Stored previously') !!}
                     {!! Form::select('selectStoredFB1', array(), null, array('placeholder' => '', 'class' => 'form-control selectpicker show-tick ms-subparameter-picker select-stored-6', 'data-style' => 'btn-default', 'id' => 'selectStoredFB1')) !!}
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group">
                     {!! Form::label('value', 'Value') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     <div class="input-group {{$errors->has('FB1') ? 'has-error' : ''}}">
                        {!! Form::text('FB1', $statistical->fb1, ['placeholder' => 'ppm', 'class' =>'form-control pull-right value_edit', 'id' => 'FB1']) !!}
                        <span class="input-group-addon" id="basic-addon2">ppm</span>
                     </div>
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('dateFB1') ? 'has-error' : ''}}">
                     {!! Form::label('date', 'Monitoring Date') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('dateFB1', $statistical->date_fb1, ['placeholder' => 'dd/mm/yyyy', 'class' =>'form-control value_edit jquery-datepicker', 'id' => 'dateFB1']); !!}
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('FB1comment') ? 'has-error' : ''}}">
                     {!! Form::label('comment', 'Comment') !!}
                     {!! Form::text('FB1comment', $statistical->comment_fb1, ['class' =>'form-control validate', 'id' => 'FB1comment']) !!}
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p10_FB1') ? 'has-error' : ''}}">
                     {!! Form::label('p10', 'p10') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p10_FB1', $statistical->p10_fb1, ['class' =>'form-control validate', 'id' => 'p10_6']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p90_FB1') ? 'has-error' : ''}}">
                     {!! Form::label('p90', 'p90') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p90_FB1', $statistical->p90_fb1, ['class' =>'form-control validate', 'id' => 'p90_6']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('fb_aluminum_concentration') ? 'has-error' : ''}}">
                     {!! Form::label('Weight', 'Weight') !!}
                     {!! Form::text('fb_aluminum_concentration', $statistical->subparameters->fb_aluminum_concentration ? $statistical->subparameters->fb_aluminum_concentration : 0.2, ['class' =>'form-control weight_fb_count', 'id' => 'weight_fb_1_value']) !!}
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
                     <img src="{{asset('images/historics.png')}}" width="25" height="24"/>
                     </button></a>
                     <a target="_blank" href="{{ URL::route('freq', ['subp' => 7, 'statistical' => $statistical])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="{{asset('images/est.png')}}" width="25" height="24"/>
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
                     {!! Form::checkbox('fbAvailable[]',2,false, array('id'=>'weight_fb_2', 'class' => 'check_weight')) !!}
                     {!! Form::label('available', 'Available') !!}
                     {!! Form::hidden('weight_fb_2_hidden', '', array('class' => 'form-control', 'id' => 'weight_fb_2_hidden')) !!}
                  </div>
               </div>
            </div>
         </div>

         <br>

         <div id="weight_fb_2_div">
            <div class="row">
               <div class="col-xs-12 col-md-4">
                  <div class="form-group">
                     {!! Form::label('stored', 'Stored previously') !!}
                     {!! Form::select('selectStoredFB2', array(), null, array('placeholder' => '', 'class' => 'form-control selectpicker show-tick ms-subparameter-picker select-stored-7', 'data-style' => 'btn-default', 'id' => 'selectStoredFB2')) !!}
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group">
                     {!! Form::label('value', 'Value') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     <div class="input-group {{$errors->has('FB2') ? 'has-error' : ''}}">
                        {!! Form::text('FB2', $statistical->fb2, ['placeholder' => 'ppm', 'class' =>'form-control pull-right value_edit', 'id' => 'FB2']) !!}
                        <span class="input-group-addon" id="basic-addon2">ppm</span>
                     </div>
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('dateFB2') ? 'has-error' : ''}}">
                     {!! Form::label('date', 'Monitoring Date') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('dateFB2', $statistical->date_fb2, ['placeholder' => 'dd/mm/yyyy', 'class' =>'form-control value_edit jquery-datepicker', 'id' => 'dateFB2']); !!}
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('FB2comment') ? 'has-error' : ''}}">
                     {!! Form::label('comment', 'Comment') !!}
                     {!! Form::text('FB2comment', $statistical->comment_fb2, ['class' =>'form-control validate', 'id' => 'FB2comment']) !!}
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p10_FB2') ? 'has-error' : ''}}">
                     {!! Form::label('p10', 'p10') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p10_FB2', $statistical->p10_fb2, ['class' =>'form-control validate', 'id' => 'p10_7']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p90_FB2') ? 'has-error' : ''}}">
                     {!! Form::label('p90', 'p90') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p90_FB2', $statistical->p90_fb2, ['class' =>'form-control validate', 'id' => 'p90_7']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('fb_silicon_concentration') ? 'has-error' : ''}}">
                     {!! Form::label('Weight', 'Weight') !!}
                     {!! Form::text('fb_silicon_concentration', $statistical->subparameters->fb_silicon_concentration ? $statistical->subparameters->fb_silicon_concentration : 0.2, ['class' =>'form-control weight_fb_count', 'id' => 'weight_fb_2_value']) !!} 
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
                     <img src="{{asset('images/historics.png')}}" width="25" height="24"/>
                     </button></a>
                     <a target="_blank" href="{{ URL::route('freq', ['subp' => 8, 'statistical' => $statistical])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="{{asset('images/est.png')}}" width="25" height="24"/>
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
                     {!! Form::checkbox('fbAvailable[]',3,false, array('id'=>'weight_fb_3', 'class' => 'check_weight')) !!}
                     {!! Form::label('available', 'Available') !!}
                     {!! Form::hidden('weight_fb_3_hidden', '', array('class' => 'form-control', 'id' => 'weight_fb_3_hidden')) !!}
                  </div>
               </div>
            </div>
         </div>

         <br>

         <div id="weight_fb_3_div">
            <div class="row">
               <div class="col-xs-12 col-md-4">
                  <div class="form-group">
                     {!! Form::label('stored', 'Stored previously') !!}
                     {!! Form::select('selectStoredFB3', array(), null, array('placeholder' => '', 'class' => 'form-control selectpicker show-tick ms-subparameter-picker select-stored-8', 'data-style' => 'btn-default', 'id' => 'selectStoredFB3')) !!}
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group">
                     {!! Form::label('value', 'Value') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     <div class="input-group {{$errors->has('FB3') ? 'has-error' : ''}}">
                        {!! Form::text('FB3', $statistical->fb3, ['placeholder' => 'ft', 'class' =>'form-control pull-right value_edit', 'id' => 'FB3']) !!}
                        <span class="input-group-addon" id="basic-addon2">ft</span>
                     </div>
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('dateFB3') ? 'has-error' : ''}}">
                     {!! Form::label('date', 'Monitoring Date') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('dateFB3', $statistical->date_fb3, ['placeholder' => 'dd/mm/yyyy', 'class' =>'form-control value_edit jquery-datepicker', 'id' => 'dateFB3']); !!}
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('FB3comment') ? 'has-error' : ''}}">
                     {!! Form::label('comment', 'Comment') !!}
                     {!! Form::text('FB3comment',$statistical->comment_fb3, ['class' =>'form-control validate', 'id' => 'FB3comment']) !!}
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p10_FB3') ? 'has-error' : ''}}">
                     {!! Form::label('p10', 'p10') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p10_FB3', $statistical->p10_fb3, ['class' =>'form-control validate', 'id' => 'p10_8']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p90_FB3') ? 'has-error' : ''}}">
                     {!! Form::label('p90', 'p90') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p90_FB3', $statistical->p90_fb3, ['class' =>'form-control validate', 'id' => 'p90_8']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('fb_critical_radius_factor') ? 'has-error' : ''}}">
                     {!! Form::label('Weight', 'Weight') !!}
                     {!! Form::text('fb_critical_radius_factor', $statistical->subparameters->fb_critical_radius_factor ? $statistical->subparameters->fb_critical_radius_factor : 0.2, ['class' =>'form-control weight_fb_count', 'id' => 'weight_fb_3_value']) !!}
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
                     <img src="{{asset('images/historics.png')}}" width="25" height="24"/>
                     </button></a>
                     <a target="_blank" href="{{ URL::route('freq', ['subp' => 9, 'statistical' => $statistical])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="{{asset('images/est.png')}}" width="25" height="24"/>
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
                     {!! Form::checkbox('fbAvailable[]',4,false, array('id'=>'weight_fb_4', 'class' => 'check_weight')) !!}
                     {!! Form::label('available', 'Available') !!}
                     {!! Form::hidden('weight_fb_4_hidden', '', array('class' => 'form-control', 'id' => 'weight_fb_4_hidden')) !!}
                  </div>
               </div>
            </div>
         </div>

         <br>

         <div id="weight_fb_4_div">
            <div class="row">
               <div class="col-xs-12 col-md-4">
                  <div class="form-group">
                     {!! Form::label('stored', 'Stored previously') !!}
                     {!! Form::select('selectStoredFB4', array(), null, array('placeholder' => '', 'class' => 'form-control selectpicker show-tick ms-subparameter-picker select-stored-9', 'data-style' => 'btn-default', 'id' => 'selectStoredFB4')) !!}
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group">
                     {!! Form::label('value', 'Value') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     <div class="input-group {{$errors->has('FB4') ? 'has-error' : ''}}">
                        {!! Form::text('FB4', $statistical->fb4, ['placeholder' => '-', 'class' =>'form-control pull-right value_edit', 'id' => 'FB4']) !!}
                        <span class="input-group-addon" id="basic-addon2">-</span>
                     </div>
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('dateFB4') ? 'has-error' : ''}}">
                     {!! Form::label('date', 'Monitoring Date') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('dateFB4', $statistical->date_fb4, ['placeholder' => 'dd/mm/yyyy', 'class' =>'form-control value_edit jquery-datepicker', 'id' => 'dateFB4']); !!}
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('FB4comment') ? 'has-error' : ''}}">
                     {!! Form::label('comment', 'Comment') !!}
                     {!! Form::text('FB4comment', $statistical->comment_fb4, ['class' =>'form-control validate', 'id' => 'FB4comment']) !!}
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p10_FB4') ? 'has-error' : ''}}">
                     {!! Form::label('p10', 'p10') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p10_FB4', $statistical->p10_fb4, ['class' =>'form-control validate', 'id' => 'p10_9']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p90_FB4') ? 'has-error' : ''}}">
                     {!! Form::label('p90', 'p90') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p90_FB4', $statistical->p90_fb4, ['class' =>'form-control validate', 'id' => 'p90_9']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('fb_mineralogic_factor') ? 'has-error' : ''}}">
                     {!! Form::label('Weight', 'Weight') !!}
                     {!! Form::text('fb_mineralogic_factor', $statistical->subparameters->fb_mineralogic_factor ? $statistical->subparameters->fb_mineralogic_factor : 0.2, ['class' =>'form-control weight_fb_count', 'id' => 'weight_fb_4_value']) !!}
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
                     <img src="{{asset('images/historics.png')}}" width="25" height="24"/>
                     </button></a>
                     <a target="_blank" href="{{ URL::route('freq', ['subp' => 10, 'statistical' => $statistical])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="{{asset('images/est.png')}}" width="25" height="24"/>
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
                     {!! Form::checkbox('fbAvailable[]',5,false, array('id'=>'weight_fb_5', 'class' => 'check_weight')) !!}
                     {!! Form::label('available', 'Available') !!}
                     {!! Form::hidden('weight_fb_5_hidden', '', array('class' => 'form-control', 'id' => 'weight_fb_5_hidden')) !!}
                  </div>
               </div>
            </div>
         </div>

         <br>  

         <div id="weight_fb_5_div">
            <div class="row">
               <div class="col-xs-12 col-md-4">
                  <div class="form-group">
                     {!! Form::label('stored', 'Stored previously') !!}
                     {!! Form::select('selectStoredFB5', array(), null, array('placeholder' => '', 'class' => 'form-control selectpicker show-tick ms-subparameter-picker select-stored-10', 'data-style' => 'btn-default', 'id' => 'selectStoredFB5')) !!}
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group">
                     {!! Form::label('value', 'Value') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     <div class="input-group {{$errors->has('FB5') ? 'has-error' : ''}}">
                        {!! Form::text('FB5', $statistical->fb5, ['placeholder' => 'lbs', 'class' =>'form-control pull-right value_edit', 'id' => 'FB5']) !!}
                        <span class="input-group-addon" id="basic-addon2">lbs</span>
                     </div>
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('dateFB5') ? 'has-error' : ''}}">
                     {!! Form::label('date', 'Monitoring Date') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('dateFB5', $statistical->date_fb5, ['placeholder' => 'dd/mm/yyyy', 'class' =>'form-control value_edit jquery-datepicker', 'id' => 'dateFB5']); !!}
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('FB5comment') ? 'has-error' : ''}}">
                     {!! Form::label('comment', 'Comment') !!}
                     {!! Form::text('FB5comment', $statistical->comment_fb5, ['class' =>'form-control validate', 'id' => 'FB5comment']) !!}
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p10_FB5') ? 'has-error' : ''}}">
                     {!! Form::label('p10', 'p10') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p10_FB5', $statistical->p10_fb5, ['class' =>'form-control validate', 'id' => 'p10_10']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p90_FB5') ? 'has-error' : ''}}">
                     {!! Form::label('p90', 'p90') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p90_FB5', $statistical->p90_fb5, ['class' =>'form-control validate', 'id' => 'p90_10']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('fb_crushed_proppant_factor') ? 'has-error' : ''}}">
                     {!! Form::label('Weight', 'Weight') !!}
                     {!! Form::text('fb_crushed_proppant_factor', $statistical->subparameters->fb_crushed_proppant_factor ? $statistical->subparameters->fb_crushed_proppant_factor : 0.2, ['class' =>'form-control weight_fb_count', 'id' => 'weight_fb_5_value']) !!}     
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>