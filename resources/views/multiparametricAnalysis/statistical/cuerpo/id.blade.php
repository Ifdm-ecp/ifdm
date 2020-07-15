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
                     {!! Form::checkbox('idAvailable[]',1,false, array('id'=>'weight_id_1', 'class' => 'check_weight')) !!}
                     {!! Form::label('available', 'Available') !!}
                     {!! Form::hidden('weight_id_1_hidden', '', array('class' => 'form-control', 'id' => 'weight_id_1_hidden')) !!}
                  </div>
               </div>
            </div>
         </div>

         <br>

         <div id="weight_id_1_div">
            <div class="row">
               <div class="col-xs-12 col-md-4">
                  <div class="form-group">
                     {!! Form::label('stored', 'Stored previously') !!}
                     {!! Form::select('selectStoredID1', array(), null, array('placeholder' => '', 'class' => 'form-control selectpicker show-tick ms-subparameter-picker select-stored-19', 'data-style' => 'btn-default', 'id' => 'selectStoredID1')) !!}
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group">
                     {!! Form::label('value', 'Value') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     <div class="input-group {{$errors->has('ID1') ? 'has-error' : ''}}">
                        {!! Form::text('ID1', $statistical->id1, ['placeholder' => 'ft', 'class' =>'form-control pull-right value_edit', 'id' => 'ID1']) !!}
                        <span class="input-group-addon" id="basic-addon2">ft</span>
                     </div>
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('dateID1') ? 'has-error' : ''}}">
                     {!! Form::label('date', 'Monitoring Date') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('dateID1', $statistical->date_id1, ['placeholder' => 'dd/mm/yyyy', 'class' =>'form-control value_edit jquery-datepicker', 'id' => 'dateID1']); !!}
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('ID1comment') ? 'has-error' : ''}}">
                     {!! Form::label('comment', 'Comment') !!}
                     {!! Form::text('ID1comment', $statistical->comment_id1, ['class' =>'form-control validate', 'id' => 'ID1comment']) !!}
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p10_ID1') ? 'has-error' : ''}}">
                     {!! Form::label('p10', 'p10') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p10_ID1', $statistical->p10_id1, ['class' =>'form-control validate', 'id' => 'p10_19']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p90_ID1') ? 'has-error' : ''}}">
                     {!! Form::label('p90', 'p90') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p90_ID1', $statistical->p90_id1, ['class' =>'form-control validate', 'id' => 'p90_19']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('id_gross_pay') ? 'has-error' : ''}}">
                     {!! Form::label('Weight', 'Weight') !!}
                     {!! Form::text('id_gross_pay', $statistical->subparameters->id_gross_pay ? $statistical->subparameters->id_gross_pay : 0.25, ['class' =>'form-control weight_id_count', 'id' => 'weight_id_1_value']) !!} 
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
                     {!! Form::checkbox('idAvailable[]',2,false, array('id'=>'weight_id_2', 'class' => 'check_weight')) !!}
                     {!! Form::label('available', 'Available') !!}
                     {!! Form::hidden('weight_id_2_hidden', '', array('class' => 'form-control', 'id' => 'weight_id_2_hidden')) !!}
                  </div>
               </div>
            </div>
         </div>

         <br>

         <div id="weight_id_2_div">
            <div class="row">
               <div class="col-xs-12 col-md-4">
                  <div class="form-group">
                     {!! Form::label('stored', 'Stored previously') !!}
                     {!! Form::select('selectStoredID2', array(), null, array('placeholder' => '', 'class' => 'form-control selectpicker show-tick ms-subparameter-picker select-stored-20', 'data-style' => 'btn-default', 'id' => 'selectStoredID2')) !!}
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group">
                     {!! Form::label('value', 'Value') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     <div class="input-group {{$errors->has('ID2') ? 'has-error' : ''}}">
                        {!! Form::text('ID2', $statistical->id2, ['placeholder' => 'lbs', 'class' =>'form-control pull-right value_edit', 'id' => 'ID2']) !!} 
                        <span class="input-group-addon" id="basic-addon2">lbs</span>
                     </div>
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('dateID2') ? 'has-error' : ''}}">
                     {!! Form::label('date', 'Monitoring Date') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('dateID2', $statistical->date_id2, ['placeholder' => 'dd/mm/yyyy', 'class' =>'form-control value_edit jquery-datepicker', 'id' => 'dateID2']); !!}
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('ID2comment') ? 'has-error' : ''}}">
                     {!! Form::label('comment', 'Comment') !!}
                     {!! Form::text('ID2comment', $statistical->comment_id2, ['class' =>'form-control validate', 'id' => 'ID2comment']) !!}
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p10_ID2') ? 'has-error' : ''}}">
                     {!! Form::label('p10', 'p10') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p10_ID2', $statistical->p10_id2, ['class' =>'form-control validate', 'id' => 'p10_20']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p90_ID2') ? 'has-error' : ''}}">
                     {!! Form::label('p90', 'p90') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p90_ID2', $statistical->p90_id2, ['class' =>'form-control validate', 'id' => 'p90_20']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('id_polymer_damage_factor') ? 'has-error' : ''}}">
                     {!! Form::label('Weight', 'Weight') !!}
                     {!! Form::text('id_polymer_damage_factor', $statistical->subparameters->id_polymer_damage_factor ? $statistical->subparameters->id_polymer_damage_factor : 0.25, ['class' =>'form-control weight_id_count', 'id' => 'weight_id_2_value']) !!}  
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
                     {!! Form::checkbox('idAvailable[]',3,false, array('id'=>'weight_id_3', 'class' => 'check_weight')) !!}
                     {!! Form::label('available', 'Available') !!}
                     {!! Form::hidden('weight_id_3_hidden', '', array('class' => 'form-control', 'id' => 'weight_id_3_hidden')) !!}
                  </div>
               </div>
            </div>
         </div>

         <br>

         <div id="weight_id_3_div">
            <div class="row">
               <div class="col-xs-12 col-md-4">
                  <div class="form-group">
                     {!! Form::label('stored', 'Stored previously') !!}
                     {!! Form::select('selectStoredID3', array(), null, array('placeholder' => '', 'class' => 'form-control selectpicker show-tick ms-subparameter-picker select-stored-21', 'data-style' => 'btn-default', 'id' => 'selectStoredID3')) !!}
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group">
                     {!! Form::label('value', 'Value') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     <div class="input-group {{$errors->has('ID3') ? 'has-error' : ''}}">
                        {!! Form::text('ID3', $statistical->id3, ['placeholder' => 'bbl', 'class' =>'form-control pull-right value_edit', 'id' => 'ID3']) !!}    
                        <span class="input-group-addon" id="basic-addon2">bbl</span>
                     </div>
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('dateID3') ? 'has-error' : ''}}">
                     {!! Form::label('date', 'Monitoring Date') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('dateID3', $statistical->date_id3, ['placeholder' => 'dd/mm/yyyy', 'class' =>'form-control value_edit jquery-datepicker', 'id' => 'dateID3']); !!}
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('ID3comment') ? 'has-error' : ''}}">
                     {!! Form::label('comment', 'Comment') !!}
                     {!! Form::text('ID3comment', $statistical->comment_id3, ['class' =>'form-control validate', 'id' => 'ID3comment']) !!}
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p10_ID3') ? 'has-error' : ''}}">
                     {!! Form::label('p10', 'p10') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p10_ID3', $statistical->p10_id3, ['class' =>'form-control validate', 'id' => 'p10_21']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p90_ID3') ? 'has-error' : ''}}">
                     {!! Form::label('p90', 'p90') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p90_ID3', $statistical->p90_id3, ['class' =>'form-control validate', 'id' => 'p90_21']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('id_total_volume_water') ? 'has-error' : ''}}">
                     {!! Form::label('Weight', 'Weight') !!}
                     {!! Form::text('id_total_volume_water', $statistical->subparameters->id_total_volume_water ? $statistical->subparameters->id_total_volume_water : 0.25, ['class' =>'form-control weight_id_count', 'id' => 'weight_id_3_value']) !!}
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
                     {!! Form::checkbox('idAvailable[]',4,false, array('id'=>'weight_id_4', 'class' => 'check_weight')) !!}
                     {!! Form::label('available', 'Available') !!}
                     {!! Form::hidden('weight_id_4_hidden', '', array('class' => 'form-control', 'id' => 'weight_id_4_hidden')) !!}
                  </div>
               </div>
            </div>
         </div>

         <br>

         <div id="weight_id_4_div">
            <div class="row">
               <div class="col-xs-12 col-md-4">
                  <div class="form-group">
                     {!! Form::label('stored', 'Stored previously') !!}
                     {!! Form::select('selectStoredID4', array(), null, array('placeholder' => '', 'class' => 'form-control selectpicker show-tick ms-subparameter-picker select-stored-22', 'data-style' => 'btn-default', 'id' => 'selectStoredID4')) !!}
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group">
                     {!! Form::label('value', 'Value') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     <div class="input-group {{$errors->has('ID4') ? 'has-error' : ''}}">
                        {!! Form::text('ID4', $statistical->id4, ['placeholder' => 'bbl', 'class' =>'form-control pull-right value_edit', 'id' => 'ID4']) !!}
                        <span class="input-group-addon" id="basic-addon2">bbl</span>
                     </div>
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('dateID4') ? 'has-error' : ''}}">
                     {!! Form::label('date', 'Monitoring Date') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('dateID4', $statistical->date_id4, ['placeholder' => 'dd/mm/yyyy', 'class' =>'form-control value_edit jquery-datepicker', 'id' => 'dateID4']); !!}
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('ID4comment') ? 'has-error' : ''}}">
                     {!! Form::label('comment', 'Comment') !!}
                     {!! Form::text('ID4comment', $statistical->comment_id4, ['class' =>'form-control validate', 'id' => 'ID4comment']) !!}
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p10_ID4') ? 'has-error' : ''}}">
                     {!! Form::label('p10', 'p10') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p10_ID4', $statistical->p10_id4, ['class' =>'form-control validate', 'id' => 'p10_22']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p90_ID4') ? 'has-error' : ''}}">
                     {!! Form::label('p90', 'p90') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p90_ID4', $statistical->p90_id4, ['class' =>'form-control validate', 'id' => 'p90_22']) !!}   
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('id_mud_damage_factor') ? 'has-error' : ''}}">
                     {!! Form::label('Weight', 'Weight') !!}
                     {!! Form::text('id_mud_damage_factor', $statistical->subparameters->id_mud_damage_factor ? $statistical->subparameters->id_mud_damage_factor : 0.25, ['class' =>'form-control weight_id_count', 'id' => 'weight_id_4_value']) !!}  
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>