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
                     <img src="{{asset('images/historics.png')}}" width="25" height="25"/>
                     </button></a>
                     <a target="_blank" href="{{ URL::route('freq', ['subp' => 23, 'statistical' => $statistical])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="{{asset('images/est.png')}}" width="25" height="25"/>
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
                     {!! Form::checkbox('gdAvailable[]',1,false, array('id'=>'weight_23', 'class' => 'check_weight')) !!}
                     {!! Form::label('available', 'Available') !!}
                     {!! Form::hidden('weight_23_hidden', '', array('class' => 'form-control', 'id' => 'weight_23_hidden')) !!}
                  </div>
               </div>
            </div>
         </div>

         <br>

         <div id="weight_23_div">
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group">
                     {!! Form::label('value', 'Value') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     <div class="input-group {{$errors->has('GD1') ? 'has-error' : ''}}">
                        @if (Session::has('GD1'))
                        {!! Form::text('GD1',Session::get('GD1')->valor, ['placeholder' => 'fraction', 'class' =>'form-control pull-right value_edit', 'id' => 'GD1']) !!}
                        @else
                        {!! Form::text('GD1',null, ['placeholder' => 'fraction', 'class' =>'form-control pull-right value_edit', 'id' => 'GD1']) !!}
                        @endif
                        <span class="input-group-addon" id="basic-addon2">fraction</span>
                     </div>
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('dateGD1') ? 'has-error' : ''}}">
                     {!! Form::label('date', 'Monitoring Date') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     @if (Session::has('GD1'))
                     {!! Form::text('dateGD1', Session::get('GD1')->fecha, ['placeholder' => 'dd/mm/yyyy', 'class' =>'form-control value_edit jquery-datepicker', 'id' => 'dateGD1']); !!}
                     @else
                     {!! Form::text('dateGD1', null, ['placeholder' => 'dd/mm/yyyy', 'class' =>'form-control value_edit jquery-datepicker', 'id' => 'dateGD1']); !!}
                     @endif
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('GD1comment') ? 'has-error' : ''}}">
                     {!! Form::label('comment', 'Comment') !!}
                     @if (Session::has('GD1'))
                     {!! Form::text('GD1comment',Session::get('GD1')->comentario, ['class' =>'form-control validate', 'id' => 'GD1comment']) !!}
                     @else 
                     {!! Form::text('GD1comment',null, ['class' =>'form-control validate', 'id' => 'GD1comment']) !!}
                     @endif
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p10_GD1') ? 'has-error' : ''}}">
                     {!! Form::label('p10', 'p10') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p10_GD1',null, ['class' =>'form-control validate', 'id' => 'p10_23']) !!}
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p90_GD1') ? 'has-error' : ''}}">
                     {!! Form::label('p90', 'p90') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p90_GD1',null, ['class' =>'form-control validate', 'id' => 'p90_23']) !!}
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('gd_fraction_netpay') ? 'has-error' : ''}}">
                     {!! Form::label('Weight', 'Weight') !!}
                     {!! Form::text('gd_fraction_netpay',$statistical->subparameters->gd_fraction_netpay ? $statistical->subparameters->gd_fraction_netpay : 0.25, ['class' =>'form-control weight_count', 'id' => 'weight_23_value']) !!} 
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
                     <img src="{{asset('images/historics.png')}}" width="25" height="25"/>
                     </button></a>
                     <a target="_blank" href="{{ URL::route('freq', ['subp' => 24, 'statistical' => $statistical])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="{{asset('images/est.png')}}" width="25" height="25"/>
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
                     {!! Form::checkbox('gdAvailable[]',2,false, array('id'=>'weight_24', 'class' => 'check_weight')) !!}
                     {!! Form::label('available', 'Available') !!}
                     {!! Form::hidden('weight_24_hidden', '', array('class' => 'form-control', 'id' => 'weight_24_hidden')) !!}
                  </div>
               </div>
            </div>
         </div>

         <br>

         <div id="weight_24_div">
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group">
                     {!! Form::label('value', 'Value') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     <div class="input-group {{$errors->has('GD2') ? 'has-error' : ''}}">
                        @if (Session::has('GD2'))
                        {!! Form::text('GD2',Session::get('GD2')->valor, ['placeholder' => 'psi', 'class' =>'form-control pull-right value_edit', 'id' => 'GD2']) !!}
                        @else
                        {!! Form::text('GD2',null, ['placeholder' => 'psi', 'class' =>'form-control pull-right value_edit', 'id' => 'GD2']) !!}
                        @endif
                        <span class="input-group-addon" id="basic-addon2">psi</span>
                     </div>
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('dateGD2') ? 'has-error' : ''}}">
                     {!! Form::label('date', 'Monitoring Date') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     @if (Session::has('GD2'))
                     {!! Form::text('dateGD2', Session::get('GD2')->fecha, ['placeholder' => 'dd/mm/yyyy', 'class' =>'form-control value_edit jquery-datepicker', 'id' => 'dateGD2']); !!}
                     @else
                     {!! Form::text('dateGD2', null, ['placeholder' => 'dd/mm/yyyy', 'class' =>'form-control value_edit jquery-datepicker', 'id' => 'dateGD2']); !!}
                     @endif
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('GD2comment') ? 'has-error' : ''}}">
                     {!! Form::label('comment', 'Comment') !!}
                     @if (Session::has('GD2'))
                     {!! Form::text('GD1comment',Session::get('GD2')->comentario, ['class' =>'form-control validate', 'id' => 'GD2comment']) !!}
                     @else 
                     {!! Form::text('GD1comment',null, ['class' =>'form-control validate', 'id' => 'GD2comment']) !!}
                     @endif
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p10_GD2') ? 'has-error' : ''}}">
                     {!! Form::label('p10', 'p10') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p10_GD2',null, ['class' =>'form-control validate', 'id' => 'p10_24']) !!}
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p90_GD2') ? 'has-error' : ''}}">
                     {!! Form::label('p90', 'p90') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p90_GD2',null, ['class' =>'form-control validate', 'id' => 'p90_24']) !!}
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('gd_drawdown') ? 'has-error' : ''}}">
                     {!! Form::label('Weight', 'Weight') !!}
                     {!! Form::text('gd_drawdown',$statistical->subparameters->gd_drawdown ? $statistical->subparameters->gd_drawdown : 0.25, ['class' =>'form-control weight_count', 'id' => 'weight_24_value']) !!}
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
                     <img src="{{asset('images/historics.png')}}" width="25" height="25"/>
                     </button></a>
                     <a target="_blank" href="{{ URL::route('freq', ['subp' => 25, 'statistical' => $statistical])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="{{asset('images/est.png')}}" width="25" height="25"/>
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
                     {!! Form::checkbox('gdAvailable[]',3,false, array('id'=>'weight_25', 'class' => 'check_weight')) !!}
                     {!! Form::label('available', 'Available') !!}
                     {!! Form::hidden('weight_25_hidden', '', array('class' => 'form-control', 'id' => 'weight_25_hidden')) !!}
                  </div>
               </div>
            </div>
         </div>

         <br>

         <div id="weight_25_div">
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group">
                     {!! Form::label('value', 'Value') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     <div class="input-group {{$errors->has('GD3') ? 'has-error' : ''}}">
                        @if (Session::has('GD3'))
                        {!! Form::text('GD3',Session::get('GD3')->valor, ['placeholder' => '-', 'class' =>'form-control pull-right value_edit', 'id' => 'GD3']) !!}
                        @else
                        {!! Form::text('GD3',null, ['placeholder' => '-', 'class' =>'form-control pull-right value_edit', 'id' => 'GD3']) !!}
                        @endif
                        <span class="input-group-addon" id="basic-addon2">-</span>
                     </div>
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('dateGD3') ? 'has-error' : ''}}">
                     {!! Form::label('date', 'Monitoring Date') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     @if (Session::has('GD3'))
                     {!! Form::text('dateGD3', Session::get('GD3')->fecha, ['placeholder' => 'dd/mm/yyyy', 'class' =>'form-control value_edit jquery-datepicker', 'id' => 'dateGD3']); !!}
                     @else
                     {!! Form::text('dateGD3', null, ['placeholder' => 'dd/mm/yyyy', 'class' =>'form-control value_edit jquery-datepicker', 'id' => 'dateGD3']); !!}
                     @endif
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('GD3comment') ? 'has-error' : ''}}">
                     {!! Form::label('comment', 'Comment') !!}
                     @if (Session::has('GD3'))
                     {!! Form::text('GD3comment',Session::get('GD3')->comentario, ['class' =>'form-control validate', 'id' => 'GD3comment']) !!}
                     @else 
                     {!! Form::text('GD3comment',null, ['class' =>'form-control validate', 'id' => 'GD3comment']) !!}
                     @endif
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p10_GD3') ? 'has-error' : ''}}">
                     {!! Form::label('p10', 'p10') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p10_GD3',null, ['class' =>'form-control validate', 'id' => 'p10_25']) !!}
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p90_GD3') ? 'has-error' : ''}}">
                     {!! Form::label('p90', 'p90') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p90_GD3',null, ['class' =>'form-control validate', 'id' => 'p90_25']) !!}
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('gd_ratio_kh_fracture') ? 'has-error' : ''}}">
                     {!! Form::label('Weight', 'Weight') !!}
                     {!! Form::text('gd_ratio_kh_fracture',$statistical->subparameters->gd_ratio_kh_fracture ? $statistical->subparameters->gd_ratio_kh_fracture : 0.25, ['class' =>'form-control weight_count', 'id' => 'weight_25_value']) !!}
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
                     <img src="{{asset('images/historics.png')}}" width="25" height="25"/>
                     </button></a>
                     <a target="_blank" href="{{ URL::route('freq', ['subp' => 26, 'statistical' => $statistical])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="{{asset('images/est.png')}}" width="25" height="25"/>
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
                     {!! Form::checkbox('gdAvailable[]',4,false, array('id'=>'weight_26', 'class' => 'check_weight')) !!}
                     {!! Form::label('available', 'Available') !!}
                     {!! Form::hidden('weight_26_hidden', '', array('class' => 'form-control', 'id' => 'weight_26_hidden')) !!}
                  </div>
               </div>
            </div>
         </div>
         <br>
         <div id="weight_26_div">
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group">
                     {!! Form::label('value', 'Value') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     <div class="input-group {{$errors->has('GD4') ? 'has-error' : ''}}">
                        @if (Session::has('GD4'))
                        {!! Form::text('GD4',Session::get('GD4')->valor, ['placeholder' => '-', 'class' =>'form-control pull-right value_edit', 'id' => 'GD4']) !!}
                        @else
                        {!! Form::text('GD4',null, ['placeholder' => '-', 'class' =>'form-control pull-right value_edit', 'id' => 'GD4']) !!}
                        @endif
                        <span class="input-group-addon" id="basic-addon2">-</span>
                     </div>
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('dateGD4') ? 'has-error' : ''}}">
                     {!! Form::label('date', 'Monitoring Date') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     @if (Session::has('GD4'))
                     {!! Form::text('dateGD4', Session::get('GD4')->fecha, ['placeholder' => 'dd/mm/yyyy', 'class' =>'form-control value_edit jquery-datepicker', 'id' => 'dateGD4']); !!}
                     @else
                     {!! Form::text('dateGD4', null, ['placeholder' => 'dd/mm/yyyy', 'class' =>'form-control value_edit jquery-datepicker', 'id' => 'dateGD4']); !!}
                     @endif
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('GD4comment') ? 'has-error' : ''}}">
                     {!! Form::label('comment', 'Comment') !!}
                     @if (Session::has('GD4'))
                     {!! Form::text('GD4comment',Session::get('GD4')->comentario, ['class' =>'form-control validate', 'id' => 'GD4comment']) !!}
                     @else 
                     {!! Form::text('GD4comment',null, ['class' =>'form-control validate', 'id' => 'GD4comment']) !!}
                     @endif
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p10_GD4') ? 'has-error' : ''}}">
                     {!! Form::label('p10', 'p10') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p10_GD4',null, ['class' =>'form-control validate', 'id' => 'p10_26']) !!}
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('p90_GD4') ? 'has-error' : ''}}">
                     {!! Form::label('p90', 'p90') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('p90_GD4',null, ['class' =>'form-control validate', 'id' => 'p90_26']) !!}
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('gd_geomechanical_damage_fraction') ? 'has-error' : ''}}">
                     {!! Form::label('Weight', 'Weight') !!}
                     {!! Form::text('gd_geomechanical_damage_fraction',$statistical->subparameters->gd_geomechanical_damage_fraction ? $statistical->subparameters->gd_geomechanical_damage_fraction : 0.25, ['class' =>'form-control weight_count', 'id' => 'weight_26_value']) !!}
                  </div>
               </div>
            </div>
         </div>
         <hr>
      </div>
   </div>
</div>
