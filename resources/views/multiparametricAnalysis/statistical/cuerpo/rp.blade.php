<div class="tab-pane" id="RP">
	<div class="panel panel-default">
      <div class="panel-heading">
         <h4>Relative Permeability</h4>
      </div>

      <div class="panel-body">
         <div class="row">
            <div class="col-md-12">
               <div class="form-inline" role="form">
                  <div class="form-group pull-left">
                     <a target="_blank" href="{{ URL::route('histo', ['subp' => 15, 'statistical' => $statistical])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="{{asset('images/historics.png')}}" width="25" height="24"/>
                     </button></a>
                     <a target="_blank" href="{{ URL::route('freq', ['subp' => 15, 'statistical' => $statistical])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="{{asset('images/est.png')}}" width="25" height="24"/>
                     </button></a>
                     <button type="button" class="btn btn-default" aria-label="Left Align" id="popover15">
                     <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>
                     </button>
                     <a target="_blank" href="{{ URL::route('Geor', ['subp' => 15, 'multi' => $statistical->id])}}"><button type="button" class="btn btn-default" aria-label="Left Align">
                     <span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span>
                     </button></a>&nbsp&nbsp&nbsp
                     {!! Form::label('', 'Number Of Days Below Saturation Pressure') !!}
                  </div>
                  <div class="pull-right">
                     <input type="checkbox" id="RP1_checkbox" name="RP1_checkbox" checked>
                     {!! Form::label('available', 'Available') !!}
                     {!! Form::hidden('weight_rp_1_hidden', '', array('class' => 'form-control', 'id' => 'weight_rp_1_hidden')) !!}
                  </div>
               </div>
            </div>
         </div>

         <br>

         <div id="NumberOfDaysBelowSaturationPressure2"></div>

         <hr>

         <div class="row">
            <div class="col-md-12">
               <div class="form-inline" role="form">
                  <div class="form-group">
                     <a target="_blank" href="{{ URL::route('histo', ['subp' => 16, 'statistical' => $statistical])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="{{asset('images/historics.png')}}" width="25" height="24"/>
                     </button></a>
                     <a target="_blank" href="{{ URL::route('freq', ['subp' => 16, 'statistical' => $statistical])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="{{asset('images/est.png')}}" width="25" height="24"/>
                     </button></a>
                     <button type="button" class="btn btn-default" aria-label="Left Align" id="popover16">
                     <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>
                     </button>
                     <a target="_blank" href="{{ URL::route('Geor', ['subp' => 16, 'multi' => $statistical->id])}}"><button type="button" class="btn btn-default" aria-label="Left Align">
                     <span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span>
                     </button></a>&nbsp&nbsp&nbsp
                     {!! Form::label('', 'Difference between current reservoir pressure and saturation pressure') !!}
                  </div>
                  <div class="pull-right">
                     <input type="checkbox" id="RP2_checkbox" name="RP2_checkbox" checked>
                     {!! Form::label('available', 'Available') !!}
                     {!! Form::hidden('weight_rp_2_hidden', '', array('class' => 'form-control', 'id' => 'weight_rp_2_hidden')) !!}
                  </div>
               </div>
            </div>
         </div>

         <br>

         <div id="Differencebetweencurrentreservoirpressureandsaturationpressure"></div>

         <hr>

         <div class="row">
            <div class="col-md-12">
               <div class="form-inline" role="form">
                  <div class="form-group">
                     <a target="_blank" href="{{ URL::route('histo', ['subp' => 17, 'statistical' => $statistical])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="{{asset('images/historics.png')}}" width="25" height="24"/>
                     </button></a>
                     <a target="_blank" href="{{ URL::route('freq', ['subp' => 17, 'statistical' => $statistical])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="{{asset('images/est.png')}}" width="25" height="24"/>
                     </button></a>
                     <button type="button" class="btn btn-default" aria-label="Left Align" id="popover17">
                     <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>
                     </button>
                     <a target="_blank" href="{{ URL::route('Geor', ['subp' => 17, 'multi' => $statistical->id])}}"><button type="button" class="btn btn-default" aria-label="Left Align">
                     <span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span>
                     </button></a>&nbsp&nbsp&nbsp
                     {!! Form::label('', 'Cumulative Water Produced') !!}
                  </div>
                  <div class="pull-right">
                     <input type="checkbox" id="RP3_checkbox" name="RP3_checkbox" checked>
                     {!! Form::label('available', 'Available') !!}
                     {!! Form::hidden('weight_rp_3_hidden', '', array('class' => 'form-control', 'id' => 'weight_rp_3_hidden')) !!}
                  </div>
               </div>
            </div>
         </div>

         <br>

         <div id="CumulativeWaterProduced"></div>

         <hr>

         <div class="row">
            <div class="col-md-12">
               <div class="form-inline" role="form">
                  <div class="form-group">
                     <a target="_blank" href="{{ URL::route('histo', ['subp' => 18, 'statistical' => $statistical])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="{{asset('images/historics.png')}}" width="25" height="24"/>
                     </button></a>
                     <a target="_blank" href="{{ URL::route('freq', ['subp' => 18, 'statistical' => $statistical])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="{{asset('images/est.png')}}" width="25" height="24"/>
                     </button></a>
                     <button type="button" class="btn btn-default" aria-label="Left Align" id="popover18">
                     <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>
                     </button>
                     <a target="_blank" href="{{ URL::route('Geor', ['subp' => 18, 'multi' => $statistical->id])}}"><button type="button" class="btn btn-default" aria-label="Left Align">
                     <span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span>
                     </button></a>&nbsp&nbsp&nbsp
                     {!! Form::label('', 'Pore Size Diameter Approximation By Katz And Thompson Correlation (d = 1/√(ϕ * k))') !!}
                  </div>
                  <div class="pull-right">
                     <input type="checkbox" id="RP4_checkbox" name="RP4_checkbox" checked>
                     {!! Form::label('available', 'Available') !!}
                     {!! Form::hidden('weight_rp_4_hidden', '', array('class' => 'form-control', 'id' => 'weight_rp_4_hidden')) !!}
                  </div>
               </div>
            </div>
         </div>

         <br>

         <div id="PoreSizeDiameterApproximationByKatzAndThompsonCorrelation"></div>

         <hr>

         <div class="row">
            <div class="col-md-12">
               <div class="form-inline" role="form">
                  <div class="form-group">
                     <a target="_blank" href="{{ URL::route('histo', ['subp' => 31, 'statistical' => $statistical])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="{{asset('images/historics.png')}}" width="25" height="24"/>
                     </button></a>
                     <a target="_blank" href="{{ URL::route('freq', ['subp' => 31, 'statistical' => $statistical])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="{{asset('images/est.png')}}" width="25" height="24"/>
                     </button></a>
                     <button type="button" class="btn btn-default" aria-label="Left Align" id="popover31">
                     <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>
                     </button>
                     <a target="_blank" href="{{ URL::route('Geor', ['subp' => 31, 'multi' => $statistical->id])}}"><button type="button" class="btn btn-default" aria-label="Left Align">
                     <span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span>
                     </button></a>&nbsp&nbsp&nbsp
                     {!! Form::label('', 'Velocity parameter estimated as the inverse of the critical radius: 1 / Critical Radius') !!}
                  </div>
                  <div class="pull-right">
                     <input type="checkbox" id="RP5_checkbox" name="RP5_checkbox" checked>
                     {!! Form::label('available', 'Available') !!}
                     {!! Form::hidden('weight_rp_5_hidden', '', array('class' => 'form-control', 'id' => 'weight_rp_5_hidden')) !!}
                  </div>
               </div>
            </div>
         </div>

         <br>

         <div id="Velocityparameterestimatedastheinverseofthecriticalradius"></div>
      </div>
   </div>
</div>