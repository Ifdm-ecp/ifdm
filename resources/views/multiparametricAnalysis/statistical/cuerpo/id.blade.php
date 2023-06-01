<div class="tab-pane" id="ID">
	<div class="panel panel-default">
      <div class="panel-heading">
         <div class="form-inline" role="form">
            <div class="col-md-8">
               <h4 id="ms-title">Induced Damage</h4>
            </div>
            <div class="text-right">
               <input type="checkbox" id="checkbox_general_ID" name="checkbox_general_ID" checked data-toggle="toggle" >
            </div>
         </div>
      </div>

      <div class="panel-body">
         <div class="row">
            <div class="col-md-12">
               <div class="form-inline" role="form">
                  <div class="form-group">
                     <a target="_blank" href="{{ URL::route('histo', ['subp' => 19, 'statistical' => $statistical])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="{{asset('images/historics.png')}}" width="25" height="24"/>
                     </button></a>
                     <a target="_blank" href="{{ URL::route('freq', ['subp' => 19, 'statistical' => $statistical])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="{{asset('images/est.png')}}" width="25" height="24"/>
                     </button></a>
                     <button type="button" class="btn btn-default" aria-label="Left Align" id="popover19">
                     <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>
                     </button>
                     <a target="_blank" href="{{ URL::route('Geor', ['subp' => 19, 'multi' => $statistical->id])}}"><button type="button" class="btn btn-default" aria-label="Left Align">
                     <span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span>
                     </button></a>&nbsp&nbsp&nbsp
                     {!! Form::label('MDF', 'Gross Pay [ft]') !!}
                  </div>
                  <div class="pull-right">
                     <input type="checkbox" id="ID1_checkbox" name="ID1_checkbox" checked>
                     {!! Form::label('available', 'Available') !!}
                     {!! Form::hidden('weight_id_1_hidden', '', array('class' => 'form-control', 'id' => 'weight_id_1_hidden')) !!}
                  </div>
               </div>
            </div>
         </div>

         <br>

         <div id="GrossPay"></div>

         <hr>

         <div class="row">
            <div class="col-md-12">
               <div class="form-inline" role="form">
                  <div class="form-group">
                     <a target="_blank" href="{{ URL::route('histo', ['subp' => 20, 'statistical' => $statistical])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="{{asset('images/historics.png')}}" width="25" height="24"/>
                     </button></a>
                     <a target="_blank" href="{{ URL::route('freq', ['subp' => 20, 'statistical' => $statistical])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="{{asset('images/est.png')}}" width="25" height="24"/>
                     </button></a>
                     <button type="button" class="btn btn-default" aria-label="Left Align" id="popover20">
                     <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>
                     </button>
                     <a target="_blank" href="{{ URL::route('Geor', ['subp' => 20, 'multi' => $statistical->id])}}"><button type="button" class="btn btn-default" aria-label="Left Align">
                     <span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span>
                     </button></a>&nbsp&nbsp&nbsp
                     {!! Form::label('PDF', 'Total polymer pumped during Hydraulic Fracturing [lbs]') !!}
                  </div>
                  <div class="pull-right">
                     <input type="checkbox" id="ID2_checkbox" name="ID2_checkbox" checked>
                     {!! Form::label('available', 'Available') !!}
                     {!! Form::hidden('weight_id_2_hidden', '', array('class' => 'form-control', 'id' => 'weight_id_2_hidden')) !!}
                  </div>
               </div>
            </div>
         </div>

         <br>

         <div id="TotalpolymerpumpedduringHydraulicFracturing"></div>

         <hr>

         <div class="row">
            <div class="col-md-12">
               <div class="form-inline" role="form">
                  <div class="form-group">
                     <a target="_blank" href="{{ URL::route('histo', ['subp' => 21, 'statistical' => $statistical])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="{{asset('images/historics.png')}}" width="25" height="24"/>
                     </button></a>
                     <a target="_blank" href="{{ URL::route('freq', ['subp' => 21, 'statistical' => $statistical])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="{{asset('images/est.png')}}" width="25" height="24"/>
                     </button></a>
                     <button type="button" class="btn btn-default" aria-label="Left Align" id="popover21">
                     <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>
                     </button>
                     <a target="_blank" href="{{ URL::route('Geor', ['subp' => 21, 'multi' => $statistical->id])}}"><button type="button" class="btn btn-default" aria-label="Left Align">
                     <span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span>
                     </button></a>&nbsp&nbsp&nbsp
                     {!! Form::label('IFF', 'Total volume of water based fluids pumped into the well [bbl]') !!}
                  </div>
                  <div class="pull-right">
                     <input type="checkbox" id="ID3_checkbox" name="ID3_checkbox" checked>
                     {!! Form::label('available', 'Available') !!}
                     {!! Form::hidden('weight_id_3_hidden', '', array('class' => 'form-control', 'id' => 'weight_id_3_hidden')) !!}
                  </div>
               </div>
            </div>
         </div>

         <br>

         <div id="Totalvolumeofwaterbasedfluidspumpedintothewell"></div>

         <hr>

         <div class="row">
            <div class="col-md-12">
               <div class="form-inline" role="form">
                  <div class="form-group">
                     <a target="_blank" href="{{ URL::route('histo', ['subp' => 22, 'statistical' => $statistical])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="{{asset('images/historics.png')}}" width="25" height="24"/>
                     </button></a>
                     <a target="_blank" href="{{ URL::route('freq', ['subp' => 22, 'statistical' => $statistical])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="{{asset('images/est.png')}}" width="25" height="24"/>
                     </button></a>
                     <button type="button" class="btn btn-default" aria-label="Left Align" id="popover22">
                     <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>
                     </button>
                     <a target="_blank" href="{{ URL::route('Geor', ['subp' => 22, 'multi' => $statistical->id])}}"><button type="button" class="btn btn-default" aria-label="Left Align">
                     <span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span>
                     </button></a>&nbsp&nbsp&nbsp
                     {!! Form::label('ML', 'Mud Losses [bbl]') !!}
                  </div>
                  <div class="pull-right">
                     <input type="checkbox" id="ID4_checkbox" name="ID4_checkbox" checked>
                     {!! Form::label('available', 'Available') !!}
                     {!! Form::hidden('weight_id_4_hidden', '', array('class' => 'form-control', 'id' => 'weight_id_4_hidden')) !!}
                  </div>
               </div>
            </div>
         </div>

         <br>

         <div id="MudLosses"></div>
      </div>
   </div>
</div>