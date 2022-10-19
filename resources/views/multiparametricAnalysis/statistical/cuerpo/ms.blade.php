<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
<div class="tab-pane" id="MS">
	<div class="panel panel-default">
      <div class="panel-heading">
         <div class="form-inline" role="form">
            <div class="col-md-8">
               <h4 id="ms-title">Mineral Scales</h4>
            </div>
            <div class="text-right">
               <input type="checkbox" id="checkbox_general_MS" name="checkbox_general_MS" checked data-toggle="toggle" onclick="myFunction()">
            </div>
         </div>
      </div>

       <div class="panel-body">
         <div class="row">
            <div class="col-md-12">
               <div class="form-inline" role="form">
                  <div class="form-group">
                     <a target="_blank" href="{{ URL::route('histo', ['subp' => 1, 'statistical' => $statistical])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="{{asset('images/historics.png')}}" width="25" height="24"/>
                     </button></a>
                     <a target="_blank" href="{{ URL::route('freq', ['subp' => 1, 'statistical' => $statistical])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="{{asset('images/est.png')}}" width="25" height="24"/>
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
                     <input type="checkbox" id="MS1_checkbox" name="MS1_checkbox" checked>
                     {!! Form::label('available', 'Available') !!}
                     {!! Form::hidden('weight_ms_1_hidden', '', array('class' => 'form-control', 'id' => 'weight_ms_1_hidden')) !!}
                  </div>
               </div>
            </div>
         </div>

         <br>

         <div id="ScaleIndexOfCaCO3" class="ScaleIndexOfCaCO3"></div>

         <hr>

         <div class="row">
            <div class="col-md-12">
               <div class="form-inline" role="form">
                  <div class="form-group">
                     <a target="_blank" href="{{ URL::route('histo', ['subp' => 2, 'statistical' => $statistical])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="{{asset('images/historics.png')}}" width="25" height="24"/>
                     </button></a>
                     <a target="_blank" href="{{ URL::route('freq', ['subp' => 2, 'statistical' => $statistical])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="{{asset('images/est.png')}}" width="25" height="24"/>
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
                     <input type="checkbox" id="MS2_checkbox" name="MS2_checkbox" checked>
                     {!! Form::label('available', 'Available') !!}
                     {!! Form::hidden('weight_ms_2_hidden', '', array('class' => 'form-control', 'id' => 'weight_ms_2_hidden')) !!}
                  </div>
               </div>
            </div>
         </div>

         <br>  

         <div id="ScaleIndexOfBaSO4" class="ScaleIndexOfBaSO4"></div>

         <hr>

         <div class="row">
            <div class="col-md-12">
               <div class="form-inline" role="form">
                  <div class="form-group">
                     <a target="_blank" href="{{ URL::route('histo', ['subp' => 3, 'statistical' => $statistical])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="{{asset('images/historics.png')}}" width="25" height="24"/>
                     </button></a>
                     <a target="_blank" href="{{ URL::route('freq', ['subp' => 3, 'statistical' => $statistical])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="{{asset('images/est.png')}}" width="25" height="24"/>
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
                     <input type="checkbox" id="MS3_checkbox" name="MS3_checkbox" checked>
                     {!! Form::label('available', 'Available') !!}
                     {!! Form::hidden('weight_ms_3_hidden', '', array('class' => 'form-control', 'id' => 'weight_ms_3_hidden')) !!}
                  </div>
               </div>
            </div>
         </div>

         <br>

         <div id="ScaleIndexOfIronScales" class="ScaleIndexOfIronScales"></div>

         <hr>

         <div class="row">
            <div class="col-md-12">
               <div class="form-inline" role="form">
                  <div class="form-group">
                     <a target="_blank" href="{{ URL::route('histo', ['subp' => 4, 'statistical' => $statistical])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="{{asset('images/historics.png')}}" width="25" height="24"/>
                     </button></a>
                     <a target="_blank" href="{{ URL::route('freq', ['subp' => 4, 'statistical' => $statistical])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="{{asset('images/est.png')}}" width="25" height="24"/>
                     </button></a>
                     <button type="button" class="btn btn-default" aria-label="Left Align" id="popover4">
                     <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>
                     </button>
                     <a target="_blank" href="{{ URL::route('Geor', ['subp' => 4, 'multi' => $statistical->id])}}"><button type="button" class="btn btn-default" aria-label="Left Align">
                     <span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span>
                     </button></a>&nbsp&nbsp&nbsp
                     {!! Form::label('', 'Backflow [Ca]') !!}
                  </div>
                  <div class="pull-right">
                     <input type="checkbox" id="MS4_checkbox" name="MS4_checkbox" checked>
                     {!! Form::label('available', 'Available') !!}
                     {!! Form::hidden('weight_ms_4_hidden', '', array('class' => 'form-control', 'id' => 'weight_ms_4_hidden')) !!}
                  </div>
               </div>
            </div>
         </div>

         <br> 

         <div id="BackflowCa" class="BackflowCa"></div>

         <hr>

         <div class="row">
            <div class="col-md-12">
               <div class="form-inline" role="form">
                  <div class="form-group">
                     <a target="_blank" href="{{ URL::route('histo', ['subp' => 5, 'statistical' => $statistical])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="{{asset('images/historics.png')}}" width="25" height="24"/>
                     </button></a>
                     <a target="_blank" href="{{ URL::route('freq', ['subp' => 5, 'statistical' => $statistical])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="{{asset('images/est.png')}}" width="25" height="24"/>
                     </button></a>
                     <button type="button" class="btn btn-default" aria-label="Left Align" id="popover5">
                     <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>
                     </button>
                     <a target="_blank" href="{{ URL::route('Geor', ['subp' => 5, 'multi' => $statistical->id])}}"><button type="button" class="btn btn-default" aria-label="Left Align">
                     <span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span>
                     </button></a>&nbsp&nbsp&nbsp
                     {!! Form::label('', 'Backflow [Ba]') !!}
                  </div>
                  <div class="pull-right">
                     <input type="checkbox" id="MS5_checkbox" name="MS5_checkbox" checked>
                     {!! Form::label('available', 'Available') !!}
                     {!! Form::hidden('weight_ms_5_hidden', '', array('class' => 'form-control', 'id' => 'weight_ms_5_hidden')) !!}
                  </div>
               </div>
            </div>
         </div>

         <br>  

         <div id="BackflowBa" class="BackflowBa"></div>
      </div>
   </div>
</div>