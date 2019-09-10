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
                     <img src="{{asset('images/historics.png')}}" width="25" height="25"/>
                     </button></a>
                     <a target="_blank" href="{{ URL::route('freq', ['subp' => 1, 'statistical' => $statistical])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="{{asset('images/est.png')}}" width="25" height="25"/>
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
                     {!! Form::checkbox('msAvailable[]',1,false, array('id'=>'weight_1', 'class' => 'check_weight')) !!}
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
                        @if(Session::has('MS1'))
                        {!! Form::text('ms1', Session::get('MS1')->valor, ['placeholder' => '-', 'class' =>'form-control value_edit', 'id' => 'MS1']) !!}     
                        @else
                        {!! Form::text('ms1',null, ['placeholder' => '-', 'class' =>'form-control value_edit', 'id' => 'MS1']) !!}  
                        @endif   
                        <span class="input-group-addon" id="basic-addon2">-</span>
                     </div>
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('dateMS1') ? 'has-error' : ''}}">
                     {!! Form::label('date', 'Monitoring Date') !!}
                     @if (Session::has('$MS1'))
                     {!! Form::date('date_ms1', Session::get('MS1')->fecha, ['class' =>'form-control value_edit', 'id' => 'dateMS1']); !!}
                     @else
                     {!! Form::date('date_ms1', null, ['class' =>'form-control value_edit', 'id' => 'dateMS1']); !!}
                     @endif
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('MS1comment') ? 'has-error' : ''}}">
                     {!! Form::label('comment', 'Comment') !!}
                     @if (Session::has('$MS1'))
                     {!! Form::text('comment_ms1',Session::get('MS1')->comentario, ['class' =>'form-control validate', 'id' => 'MS1comment']) !!}    
                     @else 
                     {!! Form::text('comment_ms1',null, ['class' =>'form-control validate', 'id' => 'MS1comment']) !!}
                     @endif
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p10_ms1') ? 'has-error' : ''}}">
                     {!! Form::label('p10', 'p10') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p10_ms1', null, ['class' =>'form-control validate', 'id' => 'p10_1']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p90_ms1') ? 'has-error' : ''}}">
                     {!! Form::label('p90', 'p90') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p90_ms1',null, ['class' =>'form-control validate', 'id' => 'p90_1']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('ms_scale_index_caco3') ? 'has-error' : ''}}">
                     {!! Form::label('Weight', 'Weight') !!}
                     {!! Form::text('ms_scale_index_caco3',$statistical->subparameters->ms_scale_index_caco3 ? $statistical->subparameters->ms_scale_index_caco3 : 0.2, ['class' =>'form-control weight_count', 'id' => 'weight_1_value']) !!}
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
                     <img src="{{asset('images/historics.png')}}" width="25" height="25"/>
                     </button></a>
                     <a target="_blank" href="{{ URL::route('freq', ['subp' => 2, 'statistical' => $statistical])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="{{asset('images/est.png')}}" width="25" height="25"/>
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
                     {!! Form::checkbox('msAvailable[]',2,false, array('id'=>'weight_2', 'class' => 'check_weight')) !!}
                     {!! Form::label('available', 'Available') !!}
                     {!! Form::hidden('weight_2_hidden', '', array('class' => 'form-control', 'id' => 'weight_2_hidden')) !!}
                  </div>
               </div>
            </div>
         </div>

         <br>  

         <div id="weight_2_div">
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('MS2') ? 'has-error' : ''}}">
                     {!! Form::label('value', 'Value') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     <div class="input-group">
                        @if (Session::has('MS2'))                        
                        {!! Form::text('ms2', Session::get('MS2')->valor, ['placeholder' => '-', 'class' =>'form-control  value_edit pull-right', 'id' => 'MS2']) !!} 
                        @else
                        {!! Form::text('ms2',null, ['placeholder' => '-', 'class' =>'form-control  value_edit pull-right', 'id' => 'MS2']) !!}
                        @endif  
                        <span class="input-group-addon" id="basic-addon2">-</span>
                     </div>
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('dateMS2') ? 'has-error' : ''}}">
                     {!! Form::label('date', 'Monitoring Date') !!}
                     @if (Session::has('MS2'))
                     {!! Form::date('date_ms2', Session::get('MS2')->fecha, ['class' =>'form-control value_edit', 'id' => 'dateMS2']); !!}
                     @else
                     {!! Form::date('date_ms2', null, ['class' =>'form-control value_edit', 'id' => 'dateMS2']); !!}
                     @endif
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('MS2comment') ? 'has-error' : ''}}">
                     {!! Form::label('comment', 'Comment') !!}
                     @if (Session::has('MS2'))
                     {!! Form::text('comment_ms2',Session::get('MS2')->comentario, ['class' =>'form-control validate', 'id' => 'MS2comment']) !!}    
                     @else 
                     {!! Form::text('comment_ms2',null, ['class' =>'form-control validate', 'id' => 'MS2comment']) !!}
                     @endif
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p10_ms2') ? 'has-error' : ''}}">
                     {!! Form::label('p10', 'p10') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p10_ms2',null, ['class' =>'form-control validate', 'id' => 'p10_2']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p90_ms2') ? 'has-error' : ''}}">
                     {!! Form::label('p90', 'p90') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p90_ms2',null, ['class' =>'form-control validate', 'id' => 'p90_2']) !!}   
                  </div>
               </div>
               <div  class="col-md-4">
                  <div class="form-group {{$errors->has('ms_scale_index_baso4') ? 'has-error' : ''}}">
                     {!! Form::label('Weight', 'Weight') !!}
                     {!! Form::text('ms_scale_index_baso4',$statistical->subparameters->ms_scale_index_baso4 ? $statistical->subparameters->ms_scale_index_baso4 : 0.2, ['class' =>'form-control weight_count', 'id' => 'weight_2_value']) !!} 
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
                     <img src="{{asset('images/historics.png')}}" width="25" height="25"/>
                     </button></a>
                     <a target="_blank" href="{{ URL::route('freq', ['subp' => 3, 'statistical' => $statistical])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="{{asset('images/est.png')}}" width="25" height="25"/>
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
                     {!! Form::checkbox('msAvailable[]',3,false, array('id'=>'weight_3','class' => 'check_weight')) !!}
                     {!! Form::label('available', 'Available') !!}
                     {!! Form::hidden('weight_3_hidden', '', array('class' => 'form-control', 'id' => 'weight_3_hidden')) !!}
                  </div>
               </div>
            </div>
         </div>

         <br>

         <div id="weight_3_div">
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('MS3') ? 'has-error' : ''}}">
                     {!! Form::label('value', 'Value') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     <div class="input-group">
                        @if (Session::has('MS3'))
                        {!! Form::text('ms3', Session::get('MS3')->valor, ['placeholder' => '-', 'class' =>'form-control pull-right value_edit', 'id' => 'MS3']) !!}    
                        @else
                        {!! Form::text('ms3',null, ['placeholder' => '-', 'class' =>'form-control pull-right value_edit', 'id' => 'MS3']) !!}
                        @endif  
                        <span class="input-group-addon" id="basic-addon2">-</span>
                     </div>
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('dateMS3') ? 'has-error' : ''}}">
                     {!! Form::label('date', 'Monitoring Date') !!}
                     @if (Session::has('MS3'))
                     {!! Form::date('date_ms3', Session::get('MS3')->fecha, ['class' =>'form-control value_edit', 'id' => 'dateMS3']); !!}
                     @else
                     {!! Form::date('date_ms3', null, ['class' =>'form-control value_edit', 'id' => 'dateMS3']); !!}
                     @endif
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('MS3comment') ? 'has-error' : ''}}">
                     {!! Form::label('comment', 'Comment') !!}
                     @if (Session::has('MS3'))
                     {!! Form::text('comment_ms3',Session::get('MS3')->comentario, ['class' =>'form-control validate', 'id' => 'MS3comment']) !!}    
                     @else 
                     {!! Form::text('comment_ms3',null, ['class' =>'form-control validate', 'id' => 'MS3comment']) !!}
                     @endif
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p10_ms3') ? 'has-error' : ''}}">
                     {!! Form::label('p10', 'p10') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p10_ms3',null, ['class' =>'form-control validate', 'id' => 'p10_3']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p90_ms3') ? 'has-error' : ''}}">
                     {!! Form::label('p90', 'p90') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p90_ms3',null, ['class' =>'form-control validate', 'id' => 'p90_3']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('ms_scale_index_iron_scales') ? 'has-error' : ''}}">
                     {!! Form::label('Weight', 'Weight') !!}
                     {!! Form::text('ms_scale_index_iron_scales',$statistical->subparameters->ms_scale_index_iron_scales ? $statistical->subparameters->ms_scale_index_iron_scales : 0.2, ['class' =>'form-control weight_count', 'id' => 'weight_3_value']) !!}
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
                     <img src="{{asset('images/historics.png')}}" width="25" height="25"/>
                     </button></a>
                     <a target="_blank" href="{{ URL::route('freq', ['subp' => 4, 'statistical' => $statistical])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="{{asset('images/est.png')}}" width="25" height="25"/>
                     </button></a>
                     <button type="button" class="btn btn-default" aria-label="Left Align" id="popover4">
                     <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>
                     </button>
                     <a target="_blank" href="{{ URL::route('Geor', ['subp' => 4, 'multi' => $statistical->id])}}"><button type="button" class="btn btn-default" aria-label="Left Align">
                     <span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span>
                     </button></a>&nbsp&nbsp&nbsp
                     {!! Form::label('', '[Ca]: Calcium Concentration On Backflow Samples') !!}
                  </div>
                  <div class="pull-right">
                     {!! Form::checkbox('msAvailable[]',4,false, array('id'=>'weight_4', 'class' => 'check_weight')) !!}
                     {!! Form::label('available', 'Available') !!}
                     {!! Form::hidden('weight_4_hidden', '', array('class' => 'form-control', 'id' => 'weight_4_hidden')) !!}
                  </div>
               </div>
            </div>
         </div>

         <br> 

         <div id="weight_4_div">
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group">
                     {!! Form::label('value', 'Value') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     <div class="input-group {{$errors->has('MS4') ? 'has-error' : ''}}">
                        @if (Session::has('MS4'))
                        {!! Form::text('ms4',Session::get('MS4')->valor, ['placeholder' => 'ppm', 'class' =>'form-control pull-right value_edit', 'id' => 'MS4']) !!}   
                        @else
                        {!! Form::text('ms4',null, ['placeholder' => 'ppm', 'class' =>'form-control pull-right value_edit', 'id' => 'MS4']) !!}   
                        @endif
                        <span class="input-group-addon" id="basic-addon2">ppm</span>
                     </div>
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('dateMS4') ? 'has-error' : ''}}">
                     {!! Form::label('date', 'Monitoring Date') !!}
                     @if (Session::has('MS4'))
                     {!! Form::date('date_ms4', Session::get('MS4')->fecha, ['class' =>'form-control value_edit', 'id' => 'dateMS4']); !!}
                     @else
                     {!! Form::date('date_ms4',null, ['class' =>'form-control value_edit', 'id' => 'dateMS4']); !!}
                     @endif
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('MS4comment') ? 'has-error' : ''}}">
                     {!! Form::label('comment', 'Comment') !!}
                     @if (Session::has('MS4'))
                     {!! Form::text('comment_ms4',Session::get('MS4')->comentario, ['class' =>'form-control validate', 'id' => 'MS4comment']) !!}    
                     @else 
                     {!! Form::text('comment_ms4',null, ['class' =>'form-control validate', 'id' => 'MS4comment']) !!}
                     @endif
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p10_ms4') ? 'has-error' : ''}}">
                     {!! Form::label('p10', 'p10') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p10_ms4',null, ['class' =>'form-control validate', 'id' => 'p10_4']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p90_ms4') ? 'has-error' : ''}}">
                     {!! Form::label('p90', 'p90') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p90_ms4',null, ['class' =>'form-control validate', 'id' => 'p90_4']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('ms_calcium_concentration') ? 'has-error' : ''}}">
                     {!! Form::label('Weight', 'Weight') !!}
                     {!! Form::text('ms_calcium_concentration',$statistical->subparameters->ms_calcium_concentration ? $statistical->subparameters->ms_calcium_concentration : 0.2, ['class' =>'form-control weight_count', 'id' => 'weight_4_value']) !!} 
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
                     <img src="{{asset('images/historics.png')}}" width="25" height="25"/>
                     </button></a>
                     <a target="_blank" href="{{ URL::route('freq', ['subp' => 5, 'statistical' => $statistical])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="{{asset('images/est.png')}}" width="25" height="25"/>
                     </button></a>
                     <button type="button" class="btn btn-default" aria-label="Left Align" id="popover5">
                     <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>
                     </button>
                     <a target="_blank" href="{{ URL::route('Geor', ['subp' => 5, 'multi' => $statistical->id])}}"><button type="button" class="btn btn-default" aria-label="Left Align">
                     <span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span>
                     </button></a>&nbsp&nbsp&nbsp
                     {!! Form::label('', '[Ba]: Barium Concentration On Backflow Samples') !!}
                  </div>
                  <div class="pull-right">
                     {!! Form::checkbox('msAvailable[]',5,false, array('id'=>'weight_5', 'class' => 'check_weight')) !!}
                     {!! Form::label('available', 'Available') !!}
                     {!! Form::hidden('weight_5_hidden', '', array('class' => 'form-control', 'id' => 'weight_5_hidden')) !!}
                  </div>
               </div>
            </div>
         </div>

         <br>  

         <div id="weight_5_div">
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('MS5') ? 'has-error' : ''}}">
                     {!! Form::label('value', 'Value') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     <div class="input-group">
                        @if (Session::has('MS5'))
                        {!! Form::text('ms5', Session::get('MS5')->valor, ['placeholder' => 'ppm', 'class' =>'form-control pull-right value_edit', 'id' => 'MS5']) !!}  
                        @else
                        {!! Form::text('ms5',null, ['placeholder' => 'ppm', 'class' =>'form-control pull-right value_edit', 'id' => 'MS5']) !!} 
                        @endif
                        <span class="input-group-addon" id="basic-addon2">ppm</span>
                     </div>
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('dateMS5') ? 'has-error' : ''}}">
                     {!! Form::label('date', 'Monitoring Date') !!}
                     @if (Session::has('MS5'))
                     {!! Form::date('date_ms5', Session::get('MS5')->fecha, ['class' =>'form-control value_edit', 'id' => 'dateMS5']); !!}
                     @else
                     {!! Form::date('date_ms5', null, ['class' =>'form-control value_edit', 'id' => 'dateMS5']); !!}
                     @endif
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('MS5comment') ? 'has-error' : ''}}">
                     {!! Form::label('comment', 'Comment') !!}
                     @if (Session::has('MS5'))
                     {!! Form::text('comment_ms5',Session::get('MS5')->comentario, ['class' =>'form-control validate', 'id' => 'MS5comment']) !!}    
                     @else 
                     {!! Form::text('comment_ms5',null, ['class' =>'form-control validate', 'id' => 'MS5comment']) !!}
                     @endif
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p10_ms5') ? 'has-error' : ''}}">
                     {!! Form::label('p10', 'p10') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p10_ms5',null, ['class' =>'form-control validate', 'id' => 'p10_5']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p90_ms5') ? 'has-error' : ''}}">
                     {!! Form::label('p90', 'p90') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p90_ms5',null, ['class' =>'form-control validate', 'id' => 'p90_5']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('ms_barium_concentration') ? 'has-error' : ''}}">
                     {!! Form::label('Weight', 'Weight') !!}
                     {!! Form::text('ms_barium_concentration',$statistical->subparameters->ms_barium_concentration ? $statistical->subparameters->ms_barium_concentration : 0.2, ['class' =>'form-control weight_count', 'id' => 'weight_5_value']) !!} 
                  </div>
               </div>
            </div>
         </div>
         <hr>
      </div>
   </div>
</div>