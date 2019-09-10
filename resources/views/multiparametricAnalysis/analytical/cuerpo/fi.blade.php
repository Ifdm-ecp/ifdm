<div class="tab-pane" id="FI">
	<div class="row">
		<div class="col-md-6">
			<label>Fluid Type</label><label class="text-danger">*</label>
			{!! Form::select('fluid_type',['Oil' => 'Oil', 'Gas' => 'Gas'], null, ['class'=>'form-control', 'id' => 'fluid_type']) !!}
		</div>
	</div>
	<hr>
	<div class="panel panel-default" id="div_oil">
      	<div class="panel-heading">
         	<h4 id="ms-title">Oil Properties </h4>
      	</div>

      	<div class="panel-body">
      		<div class="col-md-6">
			    <div class="form-group">
			        <label for="viscosity">Viscosity</label><label class="red">*</label>
			        <div class="input-group ">
			        	{!! Form::text('viscosity',null, ['class' =>'form-control', 'placeholder' => 'cP']) !!}
			            <span class="input-group-addon" id="basic-addon2">cP</span>
			        </div>
			    </div>
			</div>
			<div class="col-md-6">
			    <div class="form-group">
			        <label for="volumetric_factor">Volumetric Factor</label><label class="red">*</label>
			        <div class="input-group ">
			        	{!! Form::text('volumetric_factor',null, ['class' =>'form-control', 'placeholder' => '-']) !!}
			            <span class="input-group-addon" id="basic-addon2">-</span>
			        </div>
			    </div>
			</div>
      	</div>
   	</div>
   	<div class="panel panel-default" id="div_gas">
      	<div class="panel-heading">
         	<h4 id="ms-title">Gas Properties </h4>
      	</div>

      	<div class="panel-body">
      		<div class="col-md-6">
			    <div class="form-group">
			        <label for="viscosity">Viscosity</label><label class="red">*</label>
			        <div class="input-group ">
			        	{!! Form::text('viscosity',null, ['class' =>'form-control', 'placeholder' => 'cP']) !!}
			            <span class="input-group-addon" id="basic-addon2">cP</span>
			        </div>
			    </div>
			</div>
			<div class="col-md-6">
			    <div class="form-group">
			        <label for="volumetric_factor">Volumetric Factor</label><label class="red">*</label>
			        <div class="input-group ">
			        	{!! Form::text('volumetric_factor',null, ['class' =>'form-control', 'placeholder' => '-']) !!}
			            <span class="input-group-addon" id="basic-addon2">-</span>
			        </div>
			    </div>
			</div>
      	</div>
   	</div>
</div>