<div class="tab-pane" id="GD">
   <div class="panel panel-default">
      <div class="panel-heading">
         <h4>Geomechanical Damage</h4>
      </div>

      <div class="panel-body">
         <div class="row">
            <div class="col-md-12">
               <div class="form-inline" role="form">
                  <div class="form-group">
                     <a target="_blank" href="{{ URL::route('histo', ['subp' => 23, 'statistical' => $statistical])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="{{asset('images/historics.png')}}" width="25" height="24"/>
                     </button></a>
                     <a target="_blank" href="{{ URL::route('freq', ['subp' => 23, 'statistical' => $statistical])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="{{asset('images/est.png')}}" width="25" height="24"/>
                     </button></a>
                     <button type="button" class="btn btn-default" aria-label="Left Align" id="popover23">
                     <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>
                     </button>
                     <a target="_blank" href="{{ URL::route('Geor', ['subp' => 23, 'multi' => $statistical->id])}}"><button type="button" class="btn btn-default" aria-label="Left Align">
                     <span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span>
                     </button></a>&nbsp&nbsp&nbsp
                     {!! Form::label('PNP', 'Percentage of Net Pay exihibiting Natural') !!}
                  </div>
                  <div class="pull-right">
                     {!! Form::checkbox('gdAvailable[]',1,false, array('id'=>'weight_gd_1', 'class' => 'check_weight')) !!}
                     {!! Form::label('available', 'Available') !!}
                     {!! Form::hidden('weight_gd_1_hidden', '', array('class' => 'form-control', 'id' => 'weight_gd_1_hidden')) !!}
                  </div>
               </div>
            </div>
         </div>

         <br>

         <div id="weight_gd_1_div">
            <div class="row">
               <div class="col-xs-12 col-md-4">
                  <div class="form-group">
                     {!! Form::label('stored', 'Stored previously') !!}
                     {!! Form::select('selectStoredGD1', array(), null, array('placeholder' => '', 'class' => 'form-control selectpicker show-tick ms-subparameter-picker select-stored-23', 'data-style' => 'btn-default', 'id' => 'selectStoredGD1')) !!}
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group">
                     {!! Form::label('value', 'Value') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     <div class="input-group {{$errors->has('GD1') ? 'has-error' : ''}}">
                        {!! Form::text('GD1', $statistical->gd1, ['placeholder' => 'fraction', 'class' =>'form-control pull-right value_edit', 'id' => 'GD1']) !!}
                        <span class="input-group-addon" id="basic-addon2">fraction</span>
                     </div>
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('dateGD1') ? 'has-error' : ''}}">
                     {!! Form::label('date', 'Monitoring Date') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('dateGD1', $statistical->date_gd1, ['placeholder' => 'dd/mm/yyyy', 'class' =>'form-control value_edit jquery-datepicker', 'id' => 'dateGD1']); !!}
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('GD1comment') ? 'has-error' : ''}}">
                     {!! Form::label('comment', 'Comment') !!}
                     {!! Form::text('GD1comment', $statistical->comment_gd1, ['class' =>'form-control validate', 'id' => 'GD1comment']) !!}
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p10_GD1') ? 'has-error' : ''}}">
                     {!! Form::label('p10', 'p10') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p10_GD1', $statistical->p10_gd1, ['class' =>'form-control validate', 'id' => 'p10_23']) !!}
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p90_GD1') ? 'has-error' : ''}}">
                     {!! Form::label('p90', 'p90') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p90_GD1', $statistical->p90_gd1, ['class' =>'form-control validate', 'id' => 'p90_23']) !!}
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('gd_fraction_netpay') ? 'has-error' : ''}}">
                     {!! Form::label('Weight', 'Weight') !!}
                     {!! Form::text('gd_fraction_netpay', $statistical->subparameters->gd_fraction_netpay ? $statistical->subparameters->gd_fraction_netpay : 0.25, ['class' =>'form-control weight_gd_count', 'id' => 'weight_gd_1_value']) !!} 
                  </div>
               </div>
            </div>
         </div>

         <hr>

         <div class="row">
            <div class="col-md-12">
               <div class="form-inline" role="form">
                  <div class="form-group">
                     <a target="_blank" href="{{ URL::route('histo', ['subp' => 24, 'statistical' => $statistical])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="{{asset('images/historics.png')}}" width="25" height="24"/>
                     </button></a>
                     <a target="_blank" href="{{ URL::route('freq', ['subp' => 24, 'statistical' => $statistical])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="{{asset('images/est.png')}}" width="25" height="24"/>
                     </button></a>
                     <button type="button" class="btn btn-default" aria-label="Left Align" id="popover24">
                     <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>
                     </button>
                     <a target="_blank" href="{{ URL::route('Geor', ['subp' => 24, 'multi' => $statistical->id])}}"><button type="button" class="btn btn-default" aria-label="Left Align">
                     <span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span>
                     </button></a>&nbsp&nbsp&nbsp
                     {!! Form::label('BHFP', 'Drawdown, i.e, reservoir pressure minus BHFP') !!}
                  </div>
                  <div class="pull-right">
                     {!! Form::checkbox('gdAvailable[]',2,false, array('id'=>'weight_gd_2', 'class' => 'check_weight')) !!}
                     {!! Form::label('available', 'Available') !!}
                     {!! Form::hidden('weight_gd_2_hidden', '', array('class' => 'form-control', 'id' => 'weight_gd_2_hidden')) !!}
                  </div>
               </div>
            </div>
         </div>

         <br>

         <div id="weight_gd_2_div">
            <div class="row">
               <div class="col-xs-12 col-md-4">
                  <div class="form-group">
                     {!! Form::label('stored', 'Stored previously') !!}
                     {!! Form::select('selectStoredGD2', array(), null, array('placeholder' => '', 'class' => 'form-control selectpicker show-tick ms-subparameter-picker select-stored-24', 'data-style' => 'btn-default', 'id' => 'selectStoredGD2')) !!}
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group">
                     {!! Form::label('value', 'Value') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     <div class="input-group {{$errors->has('GD2') ? 'has-error' : ''}}">
                        {!! Form::text('GD2', $statistical->gd2, ['placeholder' => 'psi', 'class' =>'form-control pull-right value_edit', 'id' => 'GD2']) !!}
                        <span class="input-group-addon" id="basic-addon2">psi</span>
                     </div>
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('dateGD2') ? 'has-error' : ''}}">
                     {!! Form::label('date', 'Monitoring Date') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('dateGD2', $statistical->date_gd2, ['placeholder' => 'dd/mm/yyyy', 'class' =>'form-control value_edit jquery-datepicker', 'id' => 'dateGD2']); !!}
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('GD2comment') ? 'has-error' : ''}}">
                     {!! Form::label('comment', 'Comment') !!}
                     {!! Form::text('GD1comment', $statistical->comment_gd2, ['class' =>'form-control validate', 'id' => 'GD2comment']) !!}
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p10_GD2') ? 'has-error' : ''}}">
                     {!! Form::label('p10', 'p10') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p10_GD2', $statistical->p10_gd2, ['class' =>'form-control validate', 'id' => 'p10_24']) !!}
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p90_GD2') ? 'has-error' : ''}}">
                     {!! Form::label('p90', 'p90') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p90_GD2', $statistical->p90_gd2, ['class' =>'form-control validate', 'id' => 'p90_24']) !!}
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('gd_drawdown') ? 'has-error' : ''}}">
                     {!! Form::label('Weight', 'Weight') !!}
                     {!! Form::text('gd_drawdown', $statistical->subparameters->gd_drawdown ? $statistical->subparameters->gd_drawdown : 0.25, ['class' =>'form-control weight_gd_count', 'id' => 'weight_gd_2_value']) !!}
                  </div>
               </div>
            </div>
         </div>

         <hr>

         <div class="row">
            <div class="col-md-12">
               <div class="form-inline" role="form">
                  <div class="form-group">
                     <a target="_blank" href="{{ URL::route('histo', ['subp' => 25, 'statistical' => $statistical])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="{{asset('images/historics.png')}}" width="25" height="24"/>
                     </button></a>
                     <a target="_blank" href="{{ URL::route('freq', ['subp' => 25, 'statistical' => $statistical])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="{{asset('images/est.png')}}" width="25" height="24"/>
                     </button></a>
                     <button type="button" class="btn btn-default" aria-label="Left Align" id="popover25">
                     <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>
                     </button>
                     <a target="_blank" href="{{ URL::route('Geor', ['subp' => 25, 'multi' => $statistical->id])}}"><button type="button" class="btn btn-default" aria-label="Left Align">
                     <span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span>
                     </button></a>&nbsp&nbsp&nbsp
                     {!! Form::label('KH', 'Ratio of KH)matrix + fracture / KH)matrix') !!}
                  </div>
                  <div class="pull-right">
                     {!! Form::checkbox('gdAvailable[]',3,false, array('id'=>'weight_gd_3', 'class' => 'check_weight')) !!}
                     {!! Form::label('available', 'Available') !!}
                     {!! Form::hidden('weight_gd_3_hidden', '', array('class' => 'form-control', 'id' => 'weight_gd_3_hidden')) !!}
                  </div>
               </div>
            </div>
         </div>

         <br>

         <div id="weight_gd_3_div">
            <div class="row">
               <div class="col-xs-12 col-md-4">
                  <div class="form-group">
                     {!! Form::label('stored', 'Stored previously') !!}
                     {!! Form::select('selectStoredGD3', array(), null, array('placeholder' => '', 'class' => 'form-control selectpicker show-tick ms-subparameter-picker select-stored-25', 'data-style' => 'btn-default', 'id' => 'selectStoredGD3')) !!}
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group">
                     {!! Form::label('value', 'Value') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     <div class="input-group {{$errors->has('GD3') ? 'has-error' : ''}}">
                        {!! Form::text('GD3', $statistical->gd3, ['placeholder' => '-', 'class' =>'form-control pull-right value_edit', 'id' => 'GD3']) !!}
                        <span class="input-group-addon" id="basic-addon2">-</span>
                     </div>
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('dateGD3') ? 'has-error' : ''}}">
                     {!! Form::label('date', 'Monitoring Date') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('dateGD3', $statistical->date_gd3, ['placeholder' => 'dd/mm/yyyy', 'class' =>'form-control value_edit jquery-datepicker', 'id' => 'dateGD3']); !!}
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('GD3comment') ? 'has-error' : ''}}">
                     {!! Form::label('comment', 'Comment') !!}
                     {!! Form::text('GD3comment', $statistical->comment_gd3, ['class' =>'form-control validate', 'id' => 'GD3comment']) !!}
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p10_GD3') ? 'has-error' : ''}}">
                     {!! Form::label('p10', 'p10') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p10_GD3', $statistical->p10_gd3, ['class' =>'form-control validate', 'id' => 'p10_25']) !!}
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p90_GD3') ? 'has-error' : ''}}">
                     {!! Form::label('p90', 'p90') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p90_GD3', $statistical->p90_gd3, ['class' =>'form-control validate', 'id' => 'p90_25']) !!}
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('gd_ratio_kh_fracture') ? 'has-error' : ''}}">
                     {!! Form::label('Weight', 'Weight') !!}
                     {!! Form::text('gd_ratio_kh_fracture', $statistical->subparameters->gd_ratio_kh_fracture ? $statistical->subparameters->gd_ratio_kh_fracture : 0.25, ['class' =>'form-control weight_gd_count', 'id' => 'weight_gd_3_value']) !!}
                  </div>
               </div>
            </div>
         </div>

         <hr>

         <div class="row">
            <div class="col-md-12">
               <div class="form-inline" role="form">
                  <div class="form-group">
                     <a target="_blank" href="{{ URL::route('histo', ['subp' => 26, 'statistical' => $statistical])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="{{asset('images/historics.png')}}" width="25" height="24"/>
                     </button></a>
                     <a target="_blank" href="{{ URL::route('freq', ['subp' => 26, 'statistical' => $statistical])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="{{asset('images/est.png')}}" width="25" height="24"/>
                     </button></a>
                     <button type="button" class="btn btn-default" aria-label="Left Align" id="popover26">
                     <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>
                     </button>
                     <a target="_blank" href="{{ URL::route('Geor', ['subp' => 26, 'multi' => $statistical->id])}}"><button type="button" class="btn btn-default" aria-label="Left Align">
                     <span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span>
                     </button></a>&nbsp&nbsp&nbsp
                     {!! Form::label('KH', 'Geomechanical Damage Expressed As Fraction Of Base Permeability At BHFP') !!}
                  </div>
                  <div class="pull-right">
                     {!! Form::checkbox('gdAvailable[]',4,false, array('id'=>'weight_gd_4', 'class' => 'check_weight')) !!}
                     {!! Form::label('available', 'Available') !!}
                     {!! Form::hidden('weight_gd_4_hidden', '', array('class' => 'form-control', 'id' => 'weight_gd_4_hidden')) !!}
                  </div>
               </div>
            </div>
         </div>
         <br>
         <div id="weight_gd_4_div">
            <div class="row">
               <div class="col-xs-12 col-md-4">
                  <div class="form-group">
                     {!! Form::label('stored', 'Stored previously') !!}
                     {!! Form::select('selectStoredGD4', array(), null, array('placeholder' => '', 'class' => 'form-control selectpicker show-tick ms-subparameter-picker select-stored-26', 'data-style' => 'btn-default', 'id' => 'selectStoredGD4')) !!}
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group">
                     {!! Form::label('value', 'Value') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     <div class="input-group {{$errors->has('GD4') ? 'has-error' : ''}}">
                        {!! Form::text('GD4', $statistical->gd4, ['placeholder' => '-', 'class' =>'form-control pull-right value_edit', 'id' => 'GD4']) !!}
                        <span class="input-group-addon" id="basic-addon2">-</span>
                     </div>
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('dateGD4') ? 'has-error' : ''}}">
                     {!! Form::label('date', 'Monitoring Date') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('dateGD4', $statistical->date_gd4, ['placeholder' => 'dd/mm/yyyy', 'class' =>'form-control value_edit jquery-datepicker', 'id' => 'dateGD4']); !!}
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('GD4comment') ? 'has-error' : ''}}">
                     {!! Form::label('comment', 'Comment') !!}
                     {!! Form::text('GD4comment', $statistical->comment_gd4, ['class' =>'form-control validate', 'id' => 'GD4comment']) !!}
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p10_GD4') ? 'has-error' : ''}}">
                     {!! Form::label('p10', 'p10') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p10_GD4', $statistical->p10_gd4, ['class' =>'form-control validate', 'id' => 'p10_26']) !!}
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p90_GD4') ? 'has-error' : ''}}">
                     {!! Form::label('p90', 'p90') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p90_GD4', $statistical->p90_gd4, ['class' =>'form-control validate', 'id' => 'p90_26']) !!}
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('gd_geomechanical_damage_fraction') ? 'has-error' : ''}}">
                     {!! Form::label('Weight', 'Weight') !!}
                     {!! Form::text('gd_geomechanical_damage_fraction', $statistical->subparameters->gd_geomechanical_damage_fraction ? $statistical->subparameters->gd_geomechanical_damage_fraction : 0.25, ['class' =>'form-control weight_gd_count', 'id' => 'weight_gd_4_value']) !!}
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
