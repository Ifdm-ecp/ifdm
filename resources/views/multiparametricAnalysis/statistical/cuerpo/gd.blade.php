<div class="tab-pane" id="GD">
   <div class="panel panel-default">
      <div class="panel-heading">
         <div class="form-inline" role="form">
            <div class="col-md-8">
               <h4 id="ms-title">Geomechanical Damage</h4>
            </div>
            <div class="text-right">
               <input type="checkbox" id="checkbox_general_GD" name="checkbox_general_GD" checked data-toggle="toggle" \>
            </div>
         </div>
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
                     {!! Form::label('PNP', 'Fraction of Net Pay Exihibiting Natural Fractures') !!}
                  </div>
                  <div class="pull-right">
                     <input type="checkbox" id="GD1_checkbox" name="GD1_checkbox" checked>
                     {!! Form::label('available', 'Available') !!}
                     {!! Form::hidden('weight_gd_1_hidden', '', array('class' => 'form-control', 'id' => 'weight_gd_1_hidden')) !!}
                  </div>
               </div>
            </div>
         </div>

         <br>

         <div id="FractionofNetPayExihibitingNaturalFractures"></div>

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
                     <input type="checkbox" id="GD2_checkbox" name="GD2_checkbox" checked>
                     {!! Form::label('available', 'Available') !!}
                     {!! Form::hidden('weight_gd_2_hidden', '', array('class' => 'form-control', 'id' => 'weight_gd_2_hidden')) !!}
                  </div>
               </div>
            </div>
         </div>

         <br>

         <div id="reservoirpressureminusBHFP"></div>

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
                     {!! Form::label('KH', 'Ratio of KH (matrix + fracture / KH) matrix') !!}
                  </div>
                  <div class="pull-right">
                     <input type="checkbox" id="GD3_checkbox" name="GD3_checkbox" checked>
                     {!! Form::label('available', 'Available') !!}
                     {!! Form::hidden('weight_gd_3_hidden', '', array('class' => 'form-control', 'id' => 'weight_gd_3_hidden')) !!}
                  </div>
               </div>
            </div>
         </div>

         <br>

         <div id="RatioofKH"></div>

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
                     <input type="checkbox" id="GD4_checkbox" name="GD4_checkbox" checked>
                     {!! Form::label('available', 'Available') !!}
                     {!! Form::hidden('weight_gd_4_hidden', '', array('class' => 'form-control', 'id' => 'weight_gd_4_hidden')) !!}
                  </div>
               </div>
            </div>
         </div>

         <br>

         <div id="GeomechanicalDamageExpressedAsFractionOfBasePermeabilityAtBHFP"></div>
      </div>
   </div>
</div>
