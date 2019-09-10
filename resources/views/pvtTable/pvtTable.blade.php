<div class="row">
    <div class="col-md-12">
       {!! Form::label('pvt', 'PVT Data') !!}
       <a href="#button_field_pvt" class="toggle btn btn-default btn-block" id="tooltip_pvt_field"><span class="chevron_toggleable glyphicon glyphicon-chevron-down pull-right"></span> Add PVT Data</a>
       <div id="button_field_pvt" class="well hidden">
          <div class="row">
             <button class="btn btn-success" id="btnImportarPvt" style="margin-left: 20px;">Importar</button>
          </div>
          <br>
          <div class="row">
            <div class="col-md-6">
               <div class="form-group {{$errors->has('saturation_pressure') ? 'has-error' : ''}}">
                  {!! Form::label('saturation_pressure', 'Saturation Pressure') !!}
                  <div id = "alert_top"></div>
                  <div class="input-group">
                     {!! Form::text('saturation_pressure',null, ['placeholder' => 'Psi', 'class' =>'form-control']) !!}
                     <span class="input-group-addon" id="top-addon">Psi</span>
                  </div>
               </div>
            </div>
          </div>
          <br>
          <br>
          <div id="table_field_pvt" style="overflow: scroll" class="handsontable"></div>
          <div class="row">
             <div id="pvtField"></div>
          </div>
          <div class="col-md-12">
             <button class="btn btn-primary pull-right" onclick="plot_fieldPVT()">Plot</button>
          </div>
          {!! Form::hidden('pvt_table', '', ['id' => 'pvt_table']) !!}
       </div>
    </div>
 </div>

 {{--modal import PVT Library--}}
 <div class="modal fade" id="modalImportarPvt" role="dialog" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
    <div class="modal-dialog">
       <div class="modal-content">
          <div class="modal-header">
             <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
             <h4 class="modal-title">Tree Pvt Data Globals</h4>
          </div>
          <div class="modal-body">
             <div id="jstree_demo_div"></div>
          </div>
          <div class="modal-footer">
             <button type="button" class="btn btn-default" data-dismiss="modal">Ok</button>
          </div>
       </div>
    </div>
 </div>
