<div class="tab-pane" id="ID">
	<div class="panel panel-default">
      <div class="panel-heading">
         <h4>Induced Damage</h4>
      </div>

      <div class="panel-body">
         <div class="row">
            <div class="col-md-12">
               <div class="form-inline" role="form">
                  <div class="form-group">
                     <a target="_blank" href="{{ URL::route('histo', ['subp' => 19, 'statistical' => $statistical])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="{{asset('images/historics.png')}}" width="25" height="25"/>
                     </button></a>
                     <a target="_blank" href="{{ URL::route('freq', ['subp' => 19, 'statistical' => $statistical])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="{{asset('images/est.png')}}" width="25" height="25"/>
                     </button></a>
                     <button type="button" class="btn btn-default" aria-label="Left Align" id="popover19">
                     <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>
                     </button>
                     <a target="_blank" href="{{ URL::route('Geor', ['subp' => 19, 'multi' => $statistical->id])}}"><button type="button" class="btn btn-default" aria-label="Left Align">
                     <span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span>
                     </button></a>&nbsp&nbsp&nbsp
                     {!! Form::label('MDF', 'Gross Pay') !!}
                  </div>
                  <div class="pull-right">
                     {!! Form::checkbox('idAvailable[]',1,false, array('id'=>'weight_19', 'class' => 'check_weight')) !!}
                     {!! Form::label('available', 'Available') !!}
                     {!! Form::hidden('weight_19_hidden', '', array('class' => 'form-control', 'id' => 'weight_19_hidden')) !!}
                  </div>
               </div>
            </div>
         </div>

         <br>

         <div id="weight_19_div">
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group">
                     {!! Form::label('value', 'Value') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     <div class="input-group {{$errors->has('ID1') ? 'has-error' : ''}}">
                        @if (Session::has('ID1'))
                        {!! Form::text('id1',Session::get('ID1')->valor, ['placeholder' => 'ft', 'class' =>'form-control pull-right value_edit', 'id' => 'ID1']) !!}
                        @else
                        {!! Form::text('id1',null, ['placeholder' => 'ft', 'class' =>'form-control pull-right value_edit', 'id' => 'ID1']) !!}
                        @endif
                        <span class="input-group-addon" id="basic-addon2">ft</span>
                     </div>
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('dateID1') ? 'has-error' : ''}}">
                     {!! Form::label('date', 'Monitoring Date') !!}
                     @if (Session::has('ID1'))
                     {!! Form::text('date_id1', Session::get('ID1')->fecha, ['placeholder' => 'dd/mm/yyyy', 'class' =>'form-control value_edit jquery-datepicker', 'id' => 'dateID1']); !!}
                     @else
                     {!! Form::text('date_id1', null, ['placeholder' => 'dd/mm/yyyy', 'class' =>'form-control value_edit jquery-datepicker', 'id' => 'dateID1']); !!}
                     @endif
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('ID1comment') ? 'has-error' : ''}}">
                     {!! Form::label('comment', 'Comment') !!}
                     @if (Session::has('ID1'))
                     {!! Form::text('comment_id1',Session::get('ID1')->comentario, ['class' =>'form-control validate', 'id' => 'ID1comment']) !!}    
                     @else 
                     {!! Form::text('comment_id1',null, ['class' =>'form-control validate', 'id' => 'ID1comment']) !!}
                     @endif
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p10_id1') ? 'has-error' : ''}}">
                     {!! Form::label('p10', 'p10') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p10_id1',null, ['class' =>'form-control validate', 'id' => 'p10_19']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p90_id1') ? 'has-error' : ''}}">
                     {!! Form::label('p90', 'p90') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p90_id1',null, ['class' =>'form-control validate', 'id' => 'p90_19']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('id_gross_pay') ? 'has-error' : ''}}">
                     {!! Form::label('Weight', 'Weight') !!}
                     {!! Form::text('id_gross_pay',$statistical->subparameters->id_gross_pay ? $statistical->subparameters->id_gross_pay : 0.25, ['class' =>'form-control weight_count', 'id' => 'weight_19_value']) !!} 
                  </div>
               </div>
            </div>
         </div>

         <hr>

         <div class="row">
            <div class="col-md-12">
               <div class="form-inline" role="form">
                  <div class="form-group">
                     <a target="_blank" href="{{ URL::route('histo', ['subp' => 20, 'statistical' => $statistical])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="{{asset('images/historics.png')}}" width="25" height="25"/>
                     </button></a>
                     <a target="_blank" href="{{ URL::route('freq', ['subp' => 20, 'statistical' => $statistical])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="{{asset('images/est.png')}}" width="25" height="25"/>
                     </button></a>
                     <button type="button" class="btn btn-default" aria-label="Left Align" id="popover20">
                     <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>
                     </button>
                     <a target="_blank" href="{{ URL::route('Geor', ['subp' => 20, 'multi' => $statistical->id])}}"><button type="button" class="btn btn-default" aria-label="Left Align">
                     <span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span>
                     </button></a>&nbsp&nbsp&nbsp
                     {!! Form::label('PDF', 'Total polymer pumped during Hydraulic Fracturing') !!}
                  </div>
                  <div class="pull-right">
                     {!! Form::checkbox('idAvailable[]',2,false, array('id'=>'weight_20', 'class' => 'check_weight')) !!}
                     {!! Form::label('available', 'Available') !!}
                     {!! Form::hidden('weight_20_hidden', '', array('class' => 'form-control', 'id' => 'weight_20_hidden')) !!}
                  </div>
               </div>
            </div>
         </div>

         <br>

         <div id="weight_20_div">
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group">
                     {!! Form::label('value', 'Value') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     <div class="input-group {{$errors->has('ID2') ? 'has-error' : ''}}">
                        @if (Session::has('ID2'))
                        {!! Form::text('id2',Session::get('ID2')->valor, ['placeholder' => 'lbs', 'class' =>'form-control pull-right value_edit', 'id' => 'ID2']) !!}  
                        @else
                        {!! Form::text('id2',null, ['placeholder' => 'lbs', 'class' =>'form-control pull-right value_edit', 'id' => 'ID2']) !!} 
                        @endif 
                        <span class="input-group-addon" id="basic-addon2">lbs</span>
                     </div>
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('dateID2') ? 'has-error' : ''}}">
                     {!! Form::label('date', 'Monitoring Date') !!}
                     @if (Session::has('ID2'))
                     {!! Form::text('date_id2', Session::get('ID2')->fecha, ['placeholder' => 'dd/mm/yyyy', 'class' =>'form-control value_edit jquery-datepicker', 'id' => 'dateID2']); !!}
                     @else
                     {!! Form::text('date_id2', null, ['placeholder' => 'dd/mm/yyyy', 'class' =>'form-control value_edit jquery-datepicker', 'id' => 'dateID2']); !!}
                     @endif
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('ID2comment') ? 'has-error' : ''}}">
                     {!! Form::label('comment', 'Comment') !!}
                     @if (Session::has('ID2'))
                     {!! Form::text('comment_id2',Session::get('ID2')->comentario, ['class' =>'form-control validate', 'id' => 'ID2comment']) !!}    
                     @else 
                     {!! Form::text('comment_id2',null, ['class' =>'form-control validate', 'id' => 'ID2comment']) !!}
                     @endif
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p10_id2') ? 'has-error' : ''}}">
                     {!! Form::label('p10', 'p10') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p10_id2',null, ['class' =>'form-control validate', 'id' => 'p10_20']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p90_id2') ? 'has-error' : ''}}">
                     {!! Form::label('p90', 'p90') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p90_id2',null, ['class' =>'form-control validate', 'id' => 'p90_20']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('id_polymer_damage_factor') ? 'has-error' : ''}}">
                     {!! Form::label('Weight', 'Weight') !!}
                     {!! Form::text('id_polymer_damage_factor',$statistical->subparameters->id_polymer_damage_factor ? $statistical->subparameters->id_polymer_damage_factor : 0.25, ['class' =>'form-control weight_count', 'id' => 'weight_20_value']) !!}  
                  </div>
               </div>
            </div>
         </div>

         <hr>

         <div class="row">
            <div class="col-md-12">
               <div class="form-inline" role="form">
                  <div class="form-group">
                     <a target="_blank" href="{{ URL::route('histo', ['subp' => 21, 'statistical' => $statistical])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="{{asset('images/historics.png')}}" width="25" height="25"/>
                     </button></a>
                     <a target="_blank" href="{{ URL::route('freq', ['subp' => 21, 'statistical' => $statistical])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="{{asset('images/est.png')}}" width="25" height="25"/>
                     </button></a>
                     <button type="button" class="btn btn-default" aria-label="Left Align" id="popover21">
                     <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>
                     </button>
                     <a target="_blank" href="{{ URL::route('Geor', ['subp' => 21, 'multi' => $statistical->id])}}"><button type="button" class="btn btn-default" aria-label="Left Align">
                     <span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span>
                     </button></a>&nbsp&nbsp&nbsp
                     {!! Form::label('IFF', 'Total volume of water based fluids pumped into the well') !!}
                  </div>
                  <div class="pull-right">
                     {!! Form::checkbox('idAvailable[]',3,false, array('id'=>'weight_21', 'class' => 'check_weight')) !!}
                     {!! Form::label('available', 'Available') !!}
                     {!! Form::hidden('weight_21_hidden', '', array('class' => 'form-control', 'id' => 'weight_21_hidden')) !!}
                  </div>
               </div>
            </div>
         </div>

         <br>

         <div id="weight_21_div">
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group">
                     {!! Form::label('value', 'Value') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     <div class="input-group {{$errors->has('ID3') ? 'has-error' : ''}}">
                        @if (Session::has('ID3'))
                        {!! Form::text('id3',Session::get('ID3')->valor, ['placeholder' => 'bbl', 'class' =>'form-control pull-right value_edit', 'id' => 'ID3']) !!}    
                        @else
                        {!! Form::text('id3',null, ['placeholder' => 'bbl', 'class' =>'form-control pull-right value_edit', 'id' => 'ID3']) !!}    
                        @endif
                        <span class="input-group-addon" id="basic-addon2">bbl</span>
                     </div>
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('dateID3') ? 'has-error' : ''}}">
                     {!! Form::label('date', 'Monitoring Date') !!}
                     @if (Session::has('ID3'))
                     {!! Form::text('date_id3', Session::get('ID3')->fecha, ['placeholder' => 'dd/mm/yyyy', 'class' =>'form-control value_edit jquery-datepicker', 'id' => 'dateID3']); !!}
                     @else
                     {!! Form::text('date_id3', null, ['placeholder' => 'dd/mm/yyyy', 'class' =>'form-control value_edit jquery-datepicker', 'id' => 'dateID3']); !!}
                     @endif
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('ID3comment') ? 'has-error' : ''}}">
                     {!! Form::label('comment', 'Comment') !!}
                     @if (Session::has('ID3'))
                     {!! Form::text('comment_id3',Session::get('ID3')->comentario, ['class' =>'form-control validate', 'id' => 'ID3comment']) !!}    
                     @else 
                     {!! Form::text('comment_id3',null, ['class' =>'form-control validate', 'id' => 'ID3comment']) !!}
                     @endif
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p10_id3') ? 'has-error' : ''}}">
                     {!! Form::label('p10', 'p10') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p10_id3',null, ['class' =>'form-control validate', 'id' => 'p10_21']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p90_id3') ? 'has-error' : ''}}">
                     {!! Form::label('p90', 'p90') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p90_id3',null, ['class' =>'form-control validate', 'id' => 'p90_21']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('id_total_volume_water') ? 'has-error' : ''}}">
                     {!! Form::label('Weight', 'Weight') !!}
                     {!! Form::text('id_total_volume_water',$statistical->subparameters->id_total_volume_water ? $statistical->subparameters->id_total_volume_water : 0.25, ['class' =>'form-control weight_count', 'id' => 'weight_21_value']) !!}
                  </div>
               </div>
            </div>
         </div>

         <hr>

         <div class="row">
            <div class="col-md-12">
               <div class="form-inline" role="form">
                  <div class="form-group">
                     <a target="_blank" href="{{ URL::route('histo', ['subp' => 22, 'statistical' => $statistical])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="{{asset('images/historics.png')}}" width="25" height="25"/>
                     </button></a>
                     <a target="_blank" href="{{ URL::route('freq', ['subp' => 22, 'statistical' => $statistical])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="{{asset('images/est.png')}}" width="25" height="25"/>
                     </button></a>
                     <button type="button" class="btn btn-default" aria-label="Left Align" id="popover22">
                     <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>
                     </button>
                     <a target="_blank" href="{{ URL::route('Geor', ['subp' => 22, 'multi' => $statistical->id])}}"><button type="button" class="btn btn-default" aria-label="Left Align">
                     <span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span>
                     </button></a>&nbsp&nbsp&nbsp
                     {!! Form::label('ML', 'Mud Losses') !!}
                  </div>
                  <div class="pull-right">
                     {!! Form::checkbox('idAvailable[]',4,false, array('id'=>'weight_22', 'class' => 'check_weight')) !!}
                     {!! Form::label('available', 'Available') !!}
                     {!! Form::hidden('weight_22_hidden', '', array('class' => 'form-control', 'id' => 'weight_22_hidden')) !!}
                  </div>
               </div>
            </div>
         </div>

         <br>

         <div id="weight_22_div">
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group">
                     {!! Form::label('value', 'Value') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     <div class="input-group {{$errors->has('ID4') ? 'has-error' : ''}}">
                        @if (Session::has('ID4'))
                        {!! Form::text('id4',Session::get('ID4')->valor, ['placeholder' => 'bbl', 'class' =>'form-control pull-right value_edit', 'id' => 'ID4']) !!}
                        @else
                        {!! Form::text('id4',null, ['placeholder' => 'bbl', 'class' =>'form-control pull-right value_edit', 'id' => 'ID4']) !!}
                        @endif
                        <span class="input-group-addon" id="basic-addon2">bbl</span>
                     </div>
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('dateID4') ? 'has-error' : ''}}">
                     {!! Form::label('date', 'Monitoring Date') !!}
                     @if (Session::has('ID4'))
                     {!! Form::text('date_id4', Session::get('ID4')->fecha, ['placeholder' => 'dd/mm/yyyy', 'class' =>'form-control value_edit jquery-datepicker', 'id' => 'dateID4']); !!}
                     @else
                     {!! Form::text('date_id4', null, ['placeholder' => 'dd/mm/yyyy', 'class' =>'form-control value_edit jquery-datepicker', 'id' => 'dateID4']); !!}
                     @endif
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('ID4comment') ? 'has-error' : ''}}">
                     {!! Form::label('comment', 'Comment') !!}
                     @if(Session::has('ID4'))
                     {!! Form::text('comment_id4',Session::get('ID4')->comentario, ['class' =>'form-control validate', 'id' => 'ID4comment']) !!}    
                     @else 
                     {!! Form::text('comment_id4',null, ['class' =>'form-control validate', 'id' => 'ID4comment']) !!}
                     @endif
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p10_id4') ? 'has-error' : ''}}">
                     {!! Form::label('p10', 'p10') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p10_id4',null, ['class' =>'form-control validate', 'id' => 'p10_22']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p90_id4') ? 'has-error' : ''}}">
                     {!! Form::label('p90', 'p90') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p90_id4',null, ['class' =>'form-control validate', 'id' => 'p90_22']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('id_mud_damage_factor') ? 'has-error' : ''}}">
                     {!! Form::label('Weight', 'Weight') !!}
                     {!! Form::text('id_mud_damage_factor',$statistical->subparameters->id_mud_damage_factor ? $statistical->subparameters->id_mud_damage_factor : 0.25, ['class' =>'form-control weight_count', 'id' => 'weight_22_value']) !!}  
                  </div>
               </div>
            </div>
         </div>
         <hr>
      </div>
   </div>
</div>