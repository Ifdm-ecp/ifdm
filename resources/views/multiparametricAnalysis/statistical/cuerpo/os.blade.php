<div class="tab-pane" id="OS">
	<div class="panel panel-default">
      <div class="panel-heading">
         <div class="form-inline" role="form">
            <div class="col-md-8">
               <h4 id="ms-title">Organic Scales</h4>
            </div>
            <div class="text-right">
               <input type="checkbox" id="checkbox_general_OS" name="checkbox_general_OS" checked data-toggle="toggle" \>
            </div>
         </div>
      </div>

      <div class="panel-body">
         <div class="row">
            <div class="col-md-12">
               <div class="form-inline" role="form">
                  <div class="form-group">
                     <a target="_blank" href="{{ URL::route('histo', ['subp' => 11, 'statistical' => $statistical])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="{{asset('images/historics.png')}}" width="25" height="24"/>
                     </button></a>
                     <a target="_blank" href="{{ URL::route('freq', ['subp' => 11, 'statistical' => $statistical])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="{{asset('images/est.png')}}" width="25" height="24"/>
                     </button></a>
                     <button type="button" class="btn btn-default" aria-label="Left Align" id="popover11">
                     <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>
                     </button>
                     <a target="_blank" href="{{ URL::route('Geor', ['subp' => 11, 'multi' => $statistical->id])}}"><button type="button" class="btn btn-default" aria-label="Left Align">
                     <span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span>
                     </button></a>&nbsp&nbsp&nbsp
                     {!! Form::label('', 'CII Factor: Colloidal Instability Index') !!}
                  </div>
                  <div class="pull-right">
                     <input type="checkbox" id="OS1_checkbox" name="OS1_checkbox" checked>
                     {!! Form::label('available', 'Available') !!}
                     {!! Form::hidden('weight_os_1_hidden', '', array('class' => 'form-control', 'id' => 'weight_os_1_hidden')) !!}
                  </div>
               </div>
            </div>
         </div>

         <br>

         <div id="CIIFactorColloidalInstabilityIndex"></div>

         <hr>

         <div class="row">
            <div class="col-md-12">
               <div class="form-inline" role="form">
                  <div class="form-group">
                     <a target="_blank" href="{{ URL::route('histo', ['subp' => 30, 'statistical' => $statistical])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="{{asset('images/historics.png')}}" width="25" height="24"/>
                     </button></a>
                     <a target="_blank" href="{{ URL::route('freq', ['subp' => 30, 'statistical' => $statistical])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="{{asset('images/est.png')}}" width="25" height="24"/>
                     </button></a>
                     <button type="button" class="btn btn-default" aria-label="Left Align" id="popover30">
                     <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>
                     </button>
                     <a target="_blank" href="{{ URL::route('Geor', ['subp' => 30, 'multi' => $statistical->id])}}"><button type="button" class="btn btn-default" aria-label="Left Align">
                     <span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span>
                     </button></a>&nbsp&nbsp&nbsp
                     {!! Form::label('', 'Volume of HCl pumped into the formation') !!}
                  </div>
                  <div class="pull-right">
                     <input type="checkbox" id="OS2_checkbox" name="OS2_checkbox" checked>
                     {!! Form::label('available', 'Available') !!}
                     {!! Form::hidden('weight_os_2_hidden', '', array('class' => 'form-control', 'id' => 'weight_os_2_hidden')) !!}
                  </div>
               </div>
            </div>
         </div>

         <br>

         <div id="VolumeofHClpumpedintotheformation"></div>

         <hr>

         <div class="row">
            <div class="col-md-12">
               <div class="form-inline" role="form">
                  <div class="form-group">
                     <a target="_blank" href="{{ URL::route('histo', ['subp' => 12, 'statistical' => $statistical])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="{{asset('images/historics.png')}}" width="25" height="24"/>
                     </button></a>
                     <a target="_blank" href="{{ URL::route('freq', ['subp' => 12, 'statistical' => $statistical])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="{{asset('images/est.png')}}" width="25" height="24"/>
                     </button></a>
                     <button type="button" class="btn btn-default" aria-label="Left Align" id="popover12">
                     <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>
                     </button>
                     <a target="_blank" href="{{ URL::route('Geor', ['subp' => 12, 'multi' => $statistical->id])}}"><button type="button" class="btn btn-default" aria-label="Left Align">
                     <span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span>
                     </button></a>&nbsp&nbsp&nbsp
                     {!! Form::label('', 'Cumulative Gas Produced') !!}
                  </div>
                  <div class="pull-right">
                     <input type="checkbox" id="OS3_checkbox" name="OS3_checkbox" checked>
                     {!! Form::label('available', 'Available') !!}
                     {!! Form::hidden('weight_os_3_hidden', '', array('class' => 'form-control', 'id' => 'weight_os_3_hidden')) !!}
                  </div>
               </div>
            </div>
         </div>

         <br>

         <div id="CumulativeGasProduced"></div>

         <hr>

         <div class="row">
            <div class="col-md-12">
               <div class="form-inline" role="form">
                  <div class="form-group">
                     <a target="_blank" href="{{ URL::route('histo', ['subp' => 13, 'statistical' => $statistical])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="{{asset('images/historics.png')}}" width="25" height="24"/>
                     </button></a>
                     <a target="_blank" href="{{ URL::route('freq', ['subp' => 13, 'statistical' => $statistical])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="{{asset('images/est.png')}}" width="25" height="24"/>
                     </button></a>
                     <button type="button" class="btn btn-default" aria-label="Left Align" id="popover13">
                     <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>
                     </button>
                     <a target="_blank" href="{{ URL::route('Geor', ['subp' => 13, 'multi' => $statistical->id])}}"><button type="button" class="btn btn-default" aria-label="Left Align">
                     <span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span>
                     </button></a>&nbsp&nbsp&nbsp
                     {!! Form::label('', 'Number Of Days Below Saturation Pressure') !!}
                  </div>
                  <div class="pull-right">
                     <input type="checkbox" id="OS4_checkbox" name="OS4_checkbox" checked>
                     {!! Form::label('available', 'Available') !!}
                     {!! Form::hidden('weight_os_4_hidden', '', array('class' => 'form-control', 'id' => 'weight_os_4_hidden')) !!}
                  </div>
               </div>
            </div>
         </div>

         <br>

         <div id="NumberOfDaysBelowSaturationPressure"></div>

         <hr>

         <div class="row">
            <div class="col-md-12">
               <div class="form-inline" role="form">
                  <div class="form-group">
                     <a target="_blank" href="{{ URL::route('histo', ['subp' => 14, 'statistical' => $statistical])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="{{asset('images/historics.png')}}" width="25" height="24"/>
                     </button></a>
                     <a target="_blank" href="{{ URL::route('freq', ['subp' => 14, 'statistical' => $statistical])}}"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align">
                     <img src="{{asset('images/est.png')}}" width="25" height="24"/>
                     </button></a>
                     <button type="button" class="btn btn-default" aria-label="Left Align" id="popover14">
                     <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>
                     </button>
                     <a target="_blank" href="{{ URL::route('Geor', ['subp' => 14, 'multi' => $statistical->id])}}"><button type="button" class="btn btn-default" aria-label="Left Align">
                     <span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span>
                     </button></a>&nbsp&nbsp&nbsp
                     {!! Form::label('', 'De Boer Criteria') !!}
                  </div>
                  <div class="pull-right">
                     <input type="checkbox" id="OS5_checkbox" name="OS5_checkbox" checked>
                     {!! Form::label('available', 'Available') !!}
                     {!! Form::hidden('weight_os_5_hidden', '', array('class' => 'form-control', 'id' => 'weight_os_5_hidden')) !!}
                  </div>
               </div>
            </div>
         </div>

         <br>

         <div id="DeBoerCriteria"></div>
      </div>
   </div>
</div>