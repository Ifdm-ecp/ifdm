<div class="tab-pane" id="MA">
	<div class="panel panel-default">
		<div class="panel-heading">
			 <h4> Damage Variables </h4>
		</div>
		<div class="panel-body">
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
					    <label for="critical_radius" ><small>Critical Radius Derived From Maximum Critical Velocity ,  Vc</small></label><label class="red">*</label>
					    <div class="input-group ">
					    	 {!! Form::text('critical_radius',null, ['class' =>'form-control', 'placeholder' => 'ft']) !!}
					        <span class="input-group-addon" id="basic-addon2">ft</span>
					    </div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
					    <label for="total_volumen"><small>Total Volumen Of Water Based Fluids Pumped Into The Well</small></label><label class="red">*</label>
					    <div class="input-group ">
					    	{!! Form::text('total_volumen',null, ['class' =>'form-control', 'placeholder' => 'bbl']) !!}
					        <span class="input-group-addon" id="basic-addon2">bbl</span>
					    </div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
					    <label for="saturation_presure">Saturation Pressure</label><label class="red">*</label>
					    <div class="input-group ">
					    	{!! Form::text('saturation_presure',null, ['class' =>'form-control', 'placeholder' => 'psia']) !!}
					       	<span class="input-group-addon" id="basic-addon2">psia</span>
					    </div>
					</div>
				</div>
			</div>
			<hr>

			<div class="row" style="margin: 10px;">
				<div class="panel panel-default">
					<div class="panel-heading">
						 <h4><a data-parent="#accordion" data-toggle="collapse" href="#CP" class="collapsed"><span class="chevron_toggleable glyphicon pull-right glyphicon-chevron-down"></span></a> Critical Pressure By Damage Parameters</h4>
					</div>
					<div class="panel-body">
						<div id="CP" class="panel-collapse collapse" aria-expanded="false">
						    <div class="row">
						       	<div class="col-md-6">
						          	<div class="form-group ">
						             	<label for="mineral_scale_cp">Mineral Scales</label><label for="*" class="red">*</label>
						             	<div class="input-group">
						             		{!! Form::text('mineral_scale_cp',null, ['class' =>'form-control', 'placeholder' => 'psia']) !!}
						                	<span class="input-group-addon" id="basic-addon2">psia</span>
						             	</div>
						          	</div>
						       	</div>
						       	<div class="col-md-6">
						          	<div class="form-group ">
						             	<label for="organic_scale_cp">Organic Scales</label><label for="*" class="red">*</label>
						             	<div class="input-group">
						             		{!! Form::text('organic_scale_cp',null, ['class' =>'form-control', 'placeholder' => 'psia']) !!}
						                	<span class="input-group-addon" id="basic-addon2">psia</span>
						             	</div>
						          	</div>
						       	</div>
						    </div>
						    <div class="row">
						       	<div class="col-md-6">
						          	<div class="form-group ">
						             	<label for="geomechanical_damage_cp">Geomechanical Damage - DrawDown</label><label for="*" class="red">*</label>
							            <div class="input-group">
							            	{!! Form::text('geomechanical_damage_cp',null, ['class' =>'form-control', 'placeholder' => 'psia']) !!}
							                <span class="input-group-addon" id="basic-addon2">psia</span>
							             </div>
						          	</div>
						       	</div>
						    </div>
						</div>
					</div>
				</div>
			</div>
			<hr>

			<div class="row" style="margin: 10px;">
				<div class="panel panel-default">
					<div class="panel-heading">
						 <h4><a data-parent="#accordion" data-toggle="collapse" href="#KD" class="collapsed"><span class="chevron_toggleable glyphicon pull-right glyphicon-chevron-down"></span></a>K Damaged And K Base Ratio (Kd/Kb) By Damage Parameter</h4>
					</div>
					<div class="panel-body">
						<div id="KD" class="panel-collapse collapse" aria-expanded="false">
						    <div class="row">
						       	<div class="col-md-6">
						          	<div class="form-group ">
						             	<label for="mineral_scale_kd">Mineral Scales</label><label for="*" class="red">*</label>
						             	<div class="input-group">
						             		{!! Form::text('mineral_scale_kd',null, ['class' =>'form-control', 'placeholder' => 'psia']) !!}
						                	<span class="input-group-addon" id="basic-addon2">psia</span>
						             	</div>
						          	</div>
						       	</div>
						       	<div class="col-md-6">
						          	<div class="form-group ">
						             	<label for="organic_scale_kd">Organic Scales</label><label for="*" class="red">*</label>
						             	<div class="input-group">
						             		{!! Form::text('organic_scale_kd',null, ['class' =>'form-control', 'placeholder' => 'psia']) !!}
						                	<span class="input-group-addon" id="basic-addon2">psia</span>
						             	</div>
						          	</div>
						       	</div>
						    </div>
						    <div class="row">
						       	<div class="col-md-6">
						          	<div class="form-group ">
						             	<label for="geomechanical_damage_kd">Geomechanical Damage</label><label for="*" class="red">*</label>
							            <div class="input-group">
							            	{!! Form::text('geomechanical_damage_kd',null, ['class' =>'form-control', 'placeholder' => 'psia']) !!}
							                <span class="input-group-addon" id="basic-addon2">psia</span>
							             </div>
						          	</div>
						       	</div>
						       	<div class="col-md-6">
						          	<div class="form-group ">
						             	<label for="fines_blockage">Fines Blockage</label><label for="*" class="red">*</label>
							            <div class="input-group">
							            	{!! Form::text('fines_blockage_kd',null, ['class' =>'form-control', 'placeholder' => 'psia']) !!}
							                <span class="input-group-addon" id="basic-addon2">psia</span>
							             </div>
						          	</div>
						       	</div>
						    </div>

							<div class="row">
						       	<div class="col-md-6">
						          	<div class="form-group ">
						             	<label for="relative_permeability">Relative Permeability</label><label for="*" class="red">*</label>
							            <div class="input-group">
							            	{!! Form::text('relative_permeability_kd',null, ['class' =>'form-control', 'placeholder' => 'psia']) !!}
							                <span class="input-group-addon" id="basic-addon2">psia</span>
							             </div>
						          	</div>
						       	</div>
						       	<div class="col-md-6">
						          	<div class="form-group ">
						             	<label for="induced_damage">Induced Damage</label><label for="*" class="red">*</label>
							            <div class="input-group">
							            	{!! Form::text('induced_damage_kd',null, ['class' =>'form-control', 'placeholder' => 'psia']) !!}
							                <span class="input-group-addon" id="basic-addon2">psia</span>
							             </div>
						          	</div>
						       	</div>
							</div>

						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>