<div class="tab-pane" id="PD">
	<div class="col-md-6">
      <div class="form-group">
         <label for="well_radius">Well Radius</label><label class="red">*</label>
         <div class="input-group ">
            {!! Form::text('well_radius',$escenario->pozo->radius, ['class' =>'form-control', 'placeholder' => 'ft']) !!}
            <span class="input-group-addon" id="basic-addon2">ft</span>
         </div>
      </div>
   </div>
   <div class="col-md-6">
      <div class="form-group">
         <label for="drainage_radius">Drainage Radius</label><label class="red">*</label>
         <div class="input-group ">
            {!! Form::text('drainage_radius',$escenario->pozo->drainage_radius, ['class' =>'form-control', 'placeholder' => 'ft']) !!}
            <span class="input-group-addon" id="basic-addon2">ft</span>
         </div>
      </div>
   </div>

   <div class="col-md-6">
      <div class="form-group">
         <label for="reservoir_pressure">Reservoir Pressure</label><label class="red">*</label>
         <div class="input-group ">
            {!! Form::text('reservoir_pressure',$escenario->formacionxpozo->presion_reservorio, ['class' =>'form-control', 'placeholder' => 'psia']) !!}
            <span class="input-group-addon" id="basic-addon2">psia</span>
         </div>
      </div>
   </div>
   <div class="col-md-6" id="input_oil">
      <div class="form-group">
         <label for="oil_rate">Oil Rate</label><label class="red">*</label>
         <div class="input-group ">
            {!! Form::text('fluid_rate',$escenario->pozo->oil_rate, ['class' =>'form-control', 'placeholder' => 'SBT/D']) !!}
            <span class="input-group-addon" id="basic-addon2">SBT/D</span>
         </div>
      </div>
   </div>
   <div class="col-md-6" id="input_gas">
      <div class="form-group">
         <label for="gas_rate">Gas Rate</label><label class="red">*</label>
         <div class="input-group ">
            {!! Form::text('fluid_rate',$escenario->pozo->gas_rate, ['class' =>'form-control', 'placeholder' => 'MMSCF/D']) !!}
            <span class="input-group-addon" id="basic-addon2">MMSCF/D</span>
         </div>
      </div>
   </div>
</div>