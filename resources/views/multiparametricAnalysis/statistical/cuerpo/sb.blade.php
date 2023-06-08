<div class="tab-pane active" id="SB">
   <div class="panel panel-default">
      <div class="panel-heading">
         <h4 id="ms-title">Statistical Database</h4>
      </div>

      <div class="panel-body">
         <div id="Statistical" class="panel-collapse collapse in">
            <div class="row">
               <div class="col-md-4">
                  <label>
                     {!! Form::checkbox('statistical', 'Colombia', null, array('id'=>'statistical', 'name'=>'statistical')) !!}
                     {!! Form::label('sta', 'Colombia') !!}
                  </label>
               </div>
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('basin_statistical') ? 'has-error' : ''}}">
                     {!! Form::label('basinA', 'Basin') !!}
                     {!! Form::select('basin_statistical', $cuencas->lists('nombre','id'), null, array('placeholder' => '', 'class'=>'form-control selectpicker show-tick', 'data-live-search'=>'true', 'data-style'=>'btn-default', 'id'=>'selectBasin')) !!}
                  </div>
               </div>
               
               <div class="col-md-4">
                  <div class="form-group {{$errors->has('field_statistical') ? 'has-error' : ''}}">
                     {!! Form::label('fieldA', 'Field') !!}
                     {!! Form::select('field_statistical[]', array(), null, array('placeholder' => '', 'class'=>'form-control selectpicker show-tick', 'data-live-search'=>'true', 'data-style'=>'btn-default', 'id'=>'field', 'multiple'=>'multiple')) !!}
                  </div>
               </div>

               <div class="col-xs-12">
                  <hr>
                  <button class="btn btn-warning" id="calculate1" onclick="runCalculate();">Calculate</button>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>

