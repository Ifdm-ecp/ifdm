@if($datos)
	{!!Form::model($datos, ['route' => $route, 'method' => $method, 'id' => 'formFts'])!!}
@else
	{!! Form::Open(['route' => $route, 'method' => $method, 'id' => 'formFts']) !!}
@endif
		<input type="hidden" id="duplicateFrom" name="duplicateFrom" value="{{ !isset($duplicateFrom) ? '' : $duplicateFrom }}">
		<input type="hidden" name="escenario_id" value="{{ (isset($duplicateFrom) && !is_null($duplicateFrom)) ? $duplicateFrom : $scenary->id }}">
		<div class="tab-content">
		    <div id="general" class="tab-pane fade in active">
		    	<br>
		    	<div class="panel panel-default">
				  	<div class="panel-heading">Reservoir Data</div>
				  	<div class="panel-body">

				  		<div class="form-group col-md-6">	  		
				  			<label>Reservoir Temperature:</label>
				  			<div class="input-group">
						      	{!! Form::text('ty', null, ['class' => 'form-control', 'id' => 'ty']) !!}
						      	<span class="input-group-addon" id="basic-addon2">°F</span>
						    </div>
				  		</div>
				  		<div class="form-group col-md-6">	  		
				  			<label>Wettability:</label>
				  			<div class="input-group">
				  				{!!Form::select('wet', ['1' => 'Water', '2' => 'Oil', '3' => 'Mixed'], null, ['class' => 'form-control', 'id' => 'wet'])!!}
					  			<span class="input-group-addon" id="basic-addon2"></span>
						    </div>
				  			
				  		</div>

				  		<div class="form-group col-md-6">	  		
				  			<label>Permeability:</label>
				  			<div class="input-group">
				  				{!! Form::text('k', null, ['class' => 'form-control', 'id' => 'k']) !!}
						      	<span class="input-group-addon" id="basic-addon2">mD</span>
						    </div>
				  		</div>
				  		<div class="form-group col-md-6">	  		
				  			<label>Tvd:</label>
				  			<div class="input-group">
				  				{!! Form::text('tvd', null, ['class' => 'form-control', 'id' => 'tvd']) !!}
						      	<span class="input-group-addon" id="basic-addon2">ft</span>
						    </div>
				  		</div>
					</div>
				</div>
				<div class="panel panel-default">
				  	<div class="panel-heading">Fluid Data</div>
				  	<div class="panel-body">
				  		
				  		<div class="form-group col-md-6">	  		
				  			<label>Paraffin Crystallization Temperature:</label>
				  			<div class="input-group">
				  				{!! Form::text('tc', null, ['class' => 'form-control', 'id' => 'tc']) !!}
						      	<span class="input-group-addon" id="basic-addon2">°F</span>
						    </div>
				  		</div>
				  		<div class="form-group col-md-6">	  		
				  			<label>Emulsions:</label>
				  			<div class="input-group">
				  				{!!Form::select('em', ['1' => 'YES', '2' => 'NO'], null, ['class' => 'form-control', 'id' => 'em'])!!}
					  			<span class="input-group-addon" id="basic-addon2"></span>
						    </div>
				  		</div>
				  		
				  		<div class="form-group col-md-6">	  		
				  			<label>Colloidal Instability Index:</label>
				  			<div class="input-group">
				  				{!! Form::text('iic', null, ['class' => 'form-control', 'id' => 'iic']) !!}
						      	<span class="input-group-addon" id="basic-addon2">-</span>
						    </div>
				  			
				  		</div>
				  		<div class="form-group col-md-6">	  		
				  			<label>Sulficric Acid Content:</label>
				  			<div class="input-group">
				  				{!! Form::text('h2s', null, ['class' => 'form-control', 'id' => 'h2s']) !!}
						      	<span class="input-group-addon" id="basic-addon2">ppm</span>
						    </div>
				  			
				  		</div>
					</div>
				</div>
				<div class="panel panel-default">
				  	<div class="panel-heading">Water Data</div>
				  	<div class="panel-body">
				  		<div class="form-group col-md-6">	  		
				  			<label>Langelier Saturation Index:</label>
				  			<div class="input-group">
				  				{!! Form::text('isl', null, ['class' => 'form-control', 'id' => 'isl']) !!}
				  				<span class="input-group-addon" id="basic-addon2">-</span>
						    </div>
				  		</div>

				  		<div class="form-group col-md-6">	  		
				  			<label>Formation Water Salinity:</label>
				  			<div class="input-group">
				  				{!! Form::text('sal', null, ['class' => 'form-control', 'id' => 'sal']) !!}
						      	<span class="input-group-addon" id="basic-addon2">[%W/W]</span>
						    </div>
				  		</div>
					</div>
				</div>
		    </div>


		    <div id="mineral" class="tab-pane fade">
		    	<br>
		      	<div class="panel panel-default">
					  <div class="panel-heading">Quartz</div>
					  <div class="panel-body">
					  	<div class="form-group col-md-6">	
					  		<label>Quartz:</label>
				  			<div class="input-group">
				  				{!! Form::text('quartz', null, ['class' => 'form-control', 'id' => 'quartz']) !!}
						      	<span class="input-group-addon" id="basic-addon2">%</span>
						    </div>  		
					  	</div>
					  </div>
					</div>

					<div class="panel panel-default">
					  <div class="panel-heading">Feldspars</div>
					  <div class="panel-body">

					  	<div class="form-group col-md-6">	
					  		<label>Microcline:</label>
				  			<div class="input-group">
				  				{!! Form::text('microcline', null, ['class' => 'form-control', 'id' => 'microcline']) !!}
						      	<span class="input-group-addon" id="basic-addon2">%</span>
						    </div>  		
					  	</div>

					  	<div class="form-group col-md-6">	
					  		<label>Orthoclase:</label>
				  			<div class="input-group">
				  				{!! Form::text('orthoclase', null, ['class' => 'form-control', 'id' => 'orthoclase']) !!}
						      	<span class="input-group-addon" id="basic-addon2">%</span>
						    </div>  		
					  	</div>

					  	<div class="form-group col-md-6">	
					  		<label>Albite:</label>
				  			<div class="input-group">
				  				{!! Form::text('albite', null, ['class' => 'form-control', 'id' => 'albite']) !!}
						      	<span class="input-group-addon" id="basic-addon2">%</span>
						    </div>  		
					  	</div>

					  	<div class="form-group col-md-6">	
					  		<label>PlagioClase:</label>
				  			<div class="input-group">
				  				{!! Form::text('plagioClase', null, ['class' => 'form-control', 'id' => 'plagioClase']) !!}
						      	<span class="input-group-addon" id="basic-addon2">%</span>
						    </div>  		
					  	</div>
					  </div>
					</div>

					<div class="panel panel-default">
					  <div class="panel-heading">Micas</div>
					  <div class="panel-body">

					  	<div class="form-group col-md-6">	
					  		<label>Biotite:</label>
				  			<div class="input-group">
				  				{!! Form::text('biotite', null, ['class' => 'form-control', 'id' => 'biotite']) !!}
						      	<span class="input-group-addon" id="basic-addon2">%</span>
						    </div>  		
					  	</div>

					  	<div class="form-group col-md-6">	
					  		<label>Muscovite:</label>
				  			<div class="input-group">
				  				{!! Form::text('muscovite', null, ['class' => 'form-control', 'id' => 'muscovite']) !!}
						      	<span class="input-group-addon" id="basic-addon2">%</span>
						    </div>  		
					  	</div>

					  	<div class="form-group col-md-6">	
					  		<label>ChloriteM:</label>
				  			<div class="input-group">
				  				{!! Form::text('chloritem', null, ['class' => 'form-control', 'id' => 'chloritem']) !!}
						      	<span class="input-group-addon" id="basic-addon2">%</span>
						    </div>  		
					  	</div>

					  </div>
					</div>

					<div class="panel panel-default">
					  <div class="panel-heading">Clays</div>
					  <div class="panel-body">

					  	<div class="form-group col-md-6">	
					  		<label>Kaolinite:</label>
				  			<div class="input-group">
				  				{!! Form::text('kaolinite', null, ['class' => 'form-control', 'id' => 'kaolinite']) !!}
						      	<span class="input-group-addon" id="basic-addon2">%</span>
						    </div>  		
					  	</div>

					  	<div class="form-group col-md-6">	
					  		<label>Illite:</label>
				  			<div class="input-group">
				  				{!! Form::text('illite', null, ['class' => 'form-control', 'id' => 'illite']) !!}
						      	<span class="input-group-addon" id="basic-addon2">%</span>
						    </div>  		
					  	</div>

					  	<div class="form-group col-md-6">	
					  		<label>Emectite:</label>
				  			<div class="input-group">
				  				{!! Form::text('emectite', null, ['class' => 'form-control', 'id' => 'emectite']) !!}
						      	<span class="input-group-addon" id="basic-addon2">%</span>
						    </div>  		
					  	</div>

					  	<div class="form-group col-md-6">	
					  		<label>ChloriteC:</label>
				  			<div class="input-group">
				  				{!! Form::text('chloritec', null, ['class' => 'form-control', 'id' => 'chloritec']) !!}
						      	<span class="input-group-addon" id="basic-addon2">%</span>
						    </div>  		
					  	</div>

					  	<div class="form-group col-md-6">	
					  		<label>Brucite:</label>
				  			<div class="input-group">
				  				{!! Form::text('brucite', null, ['class' => 'form-control', 'id' => 'brucite']) !!}
						      	<span class="input-group-addon" id="basic-addon2">%</span>
						    </div>  		
					  	</div>

					  	<div class="form-group col-md-6">	
					  		<label>Gibbsite:</label>
				  			<div class="input-group">
				  				{!! Form::text('gibbsite', null, ['class' => 'form-control', 'id' => 'gibbsite']) !!}
						      	<span class="input-group-addon" id="basic-addon2">%</span>
						    </div>  		
					  	</div>

					  </div>
					</div>

					<div class="panel panel-default">
					  <div class="panel-heading">Carbonates</div>
					  <div class="panel-body">

					  	<div class="form-group col-md-6">	
					  		<label>Calcite:</label>
				  			<div class="input-group">
				  				{!! Form::text('calcite', null, ['class' => 'form-control', 'id' => 'calcite']) !!}
						      	<span class="input-group-addon" id="basic-addon2">%</span>
						    </div>  		
					  	</div>

					  	<div class="form-group col-md-6">	
					  		<label>Dolomite:</label>
				  			<div class="input-group">
				  				{!! Form::text('dolomite', null, ['class' => 'form-control', 'id' => 'dolomite']) !!}
						      	<span class="input-group-addon" id="basic-addon2">%</span>
						    </div>  		
					  	</div>

					  	<div class="form-group col-md-6">	
					  		<label>Ankeritec:</label>
				  			<div class="input-group">
				  				{!! Form::text('ankeritec', null, ['class' => 'form-control', 'id' => 'ankeritec']) !!}
						      	<span class="input-group-addon" id="basic-addon2">%</span>
						    </div>  		
					  	</div>

					  	<div class="form-group col-md-6">	
					  		<label>Sideritec:</label>
				  			<div class="input-group">
				  				{!! Form::text('sideritec', null, ['class' => 'form-control', 'id' => 'sideritec']) !!}
						      	<span class="input-group-addon" id="basic-addon2">%</span>
						    </div>  		
					  	</div>
					  </div>
					</div>

					<div class="panel panel-default">
					  <div class="panel-heading">Sulfates</div>
					  <div class="panel-body">

					  	<div class="form-group col-md-6">	
					  		<label>Cast:</label>
				  			<div class="input-group">
				  				{!! Form::text('cast', null, ['class' => 'form-control', 'id' => 'cast']) !!}
						      	<span class="input-group-addon" id="basic-addon2">%</span>
						    </div>  		
					  	</div>

					  	<div class="form-group col-md-6">	
					  		<label>Anhydrite:</label>
				  			<div class="input-group">
				  				{!! Form::text('anhydrite', null, ['class' => 'form-control', 'id' => 'anhydrite']) !!}
						      	<span class="input-group-addon" id="basic-addon2">%</span>
						    </div>  		
					  	</div>

					  	<div class="form-group col-md-6">	
					  		<label>Baryte:</label>
				  			<div class="input-group">
				  				{!! Form::text('baryte', null, ['class' => 'form-control', 'id' => 'baryte']) !!}
						      	<span class="input-group-addon" id="basic-addon2">%</span>
						    </div>  		
					  	</div>

					  	<div class="form-group col-md-6">	
					  		<label>Celestine:</label>
				  			<div class="input-group">
				  				{!! Form::text('celestine', null, ['class' => 'form-control', 'id' => 'celestine']) !!}
						      	<span class="input-group-addon" id="basic-addon2">%</span>
						    </div>  		
					  	</div>
					  </div>
					</div>

					<div class="panel panel-default">
					  <div class="panel-heading">Clorure</div>
					  <div class="panel-body">
					  	<div class="form-group col-md-6">	
					  		<label>Halite:</label>
				  			<div class="input-group">
				  				{!! Form::text('halite', null, ['class' => 'form-control', 'id' => 'halite']) !!}
						      	<span class="input-group-addon" id="basic-addon2">%</span>
						    </div>  		
					  	</div>
					  </div>
					</div>

					<div class="panel panel-default">
					  <div class="panel-heading">Iron Minerals</div>
					  <div class="panel-body">
					  	<div class="form-group col-md-6">	
					  		<label>Hematite:</label>
				  			<div class="input-group">
				  				{!! Form::text('hematite', null, ['class' => 'form-control', 'id' => 'hematite']) !!}
						      	<span class="input-group-addon" id="basic-addon2">%</span>
						    </div>  		
					  	</div>

					  	<div class="form-group col-md-6">	
					  		<label>Magnetite:</label>
				  			<div class="input-group">
				  				{!! Form::text('magnetite', null, ['class' => 'form-control', 'id' => 'magnetite']) !!}
						      	<span class="input-group-addon" id="basic-addon2">%</span>
						    </div>  		
					  	</div>

					  	<div class="form-group col-md-6">	
					  		<label>Pyrrhotite:</label>
				  			<div class="input-group">
				  				{!! Form::text('pyrrhotite', null, ['class' => 'form-control', 'id' => 'pyrrhotite']) !!}
						      	<span class="input-group-addon" id="basic-addon2">%</span>
						    </div>  		
					  	</div>
					  	<div class="form-group col-md-6">	
					  		<label>Pyrite:</label>
				  			<div class="input-group">
				  				{!! Form::text('pyrite', null, ['class' => 'form-control', 'id' => 'pyrite']) !!}
						      	<span class="input-group-addon" id="basic-addon2">%</span>
						    </div>  		
					  	</div>
					  	<div class="form-group col-md-6">	
					  		<label>ChloriteIM:</label>
				  			<div class="input-group">
				  				{!! Form::text('chloriteim', null, ['class' => 'form-control', 'id' => 'chloriteim']) !!}
						      	<span class="input-group-addon" id="basic-addon2">%</span>
						    </div>  		
					  	</div>
					  	
					  	<div class="form-group col-md-6">	
					  		<label>SideriteIM:</label>
				  			<div class="input-group">
				  				{!! Form::text('sideriteim', null, ['class' => 'form-control', 'id' => 'sideriteim']) !!}
						      	<span class="input-group-addon" id="basic-addon2">%</span>
						    </div>  		
					  	</div>
					  	<div class="form-group col-md-6">	
					  		<label>AnkeriteIM:</label>
				  			<div class="input-group">
				  				{!! Form::text('ankeriteim', null, ['class' => 'form-control', 'id' => 'ankeriteim']) !!}
						      	<span class="input-group-addon" id="basic-addon2">%</span>
						    </div>  		
					  	</div>
					  	<div class="form-group col-md-6">	
					  		<label>Glauconite:</label>
				  			<div class="input-group">
				  				{!! Form::text('glauconite', null, ['class' => 'form-control', 'id' => 'glauconite']) !!}
						      	<span class="input-group-addon" id="basic-addon2">%</span>
						    </div>  		
					  	</div>
					  	<div class="form-group col-md-6">	
					  		<label>Chamosite:</label>
				  			<div class="input-group">
				  				{!! Form::text('chamosite', null, ['class' => 'form-control', 'id' => 'chamosite']) !!}
						      	<span class="input-group-addon" id="basic-addon2">%</span>
						    </div>  		
					  	</div>
					  	<div class="form-group col-md-6">	
					  		<label>Troilite:</label>
				  			<div class="input-group">
				  				{!! Form::text('troilite', null, ['class' => 'form-control', 'id' => 'troilite']) !!}
						      	<span class="input-group-addon" id="basic-addon2">%</span>
						    </div>  		
					  	</div>
					  </div>
					</div>

					<div class="panel panel-default">
					  	<div class="panel-heading">Zeolites</div>
					  	<div class="panel-body">
					  		<div class="form-group col-md-6">	
						  		<label>Stilbite:</label>
					  			<div class="input-group">
					  				{!! Form::text('stilbite', null, ['class' => 'zeolites form-control', 'id' => 'stilbite']) !!}
							      	<span class="input-group-addon" id="basic-addon2">%</span>
							    </div>  		
						  	</div>
						  	<div class="form-group col-md-6">	
						  		<label>Heulandite:</label>
					  			<div class="input-group">
					  				{!! Form::text('heulandite', null, ['class' => 'zeolites form-control', 'id' => 'heulandite']) !!}
							      	<span class="input-group-addon" id="basic-addon2">%</span>
							    </div>  		
						  	</div>
						  	<div class="form-group col-md-6">	
						  		<label>Chabazite:</label>
					  			<div class="input-group">
					  				{!! Form::text('chabazite', null, ['class' => 'zeolites form-control', 'id' => 'chabazite']) !!}
							      	<span class="input-group-addon" id="basic-addon2">%</span>
							    </div>  		
						  	</div>
						  	<div class="form-group col-md-6">	
						  		<label>Natrolite:</label>
					  			<div class="input-group">
					  				{!! Form::text('natrolite', null, ['class' => 'zeolites form-control', 'id' => 'natrolite']) !!}
							      	<span class="input-group-addon" id="basic-addon2">%</span>
							    </div>  		
						  	</div>
						  	<div class="form-group col-md-6">	
						  		<label>Analcime:</label>
					  			<div class="input-group">
					  				{!! Form::text('analcime', null, ['class' => 'zeolites form-control', 'id' => 'analcime']) !!}
							      	<span class="input-group-addon" id="basic-addon2">%</span>
							    </div>  		
						  	</div>
						</div>
					</div>

					<div class="panel panel-default">
					  	<div class="panel-heading">Additional Fines</div>
					  	<div class="panel-body">
					  		<div class="form-group col-md-6">	
						  		<label>Melanterite:</label>
					  			<div class="input-group">
					  				{!! Form::text('melanterite', null, ['class' => 'form-control', 'id' => 'melanterite']) !!}
							      	<span class="input-group-addon" id="basic-addon2">%</span>
							    </div>  		
						  	</div>
						  	<div class="form-group col-md-6">	
						  		<label>Bentonite:</label>
					  			<div class="input-group">
					  				{!! Form::text('bentonite', null, ['class' => 'form-control', 'id' => 'bentonite']) !!}
							      	<span class="input-group-addon" id="basic-addon2">%</span>
							    </div>  		
						  	</div>
						  	<div class="form-group col-md-6">	
						  		<label>Other Fines:</label>
					  			<div class="input-group">
					  				{!! Form::text('finesna', null, ['class' => 'form-control', 'id' => 'finesna']) !!}
							      	<span class="input-group-addon" id="basic-addon2">%</span>
							    </div>  		
						  	</div>
						</div>
					</div>

		    </div>
		</div>

		<div class="row">
			<input type="submit" class="btn btn-success" value="Save" id="button_wr" name="button_wr">
			<input type="submit" class="btn btn-primary pull-right" value="Run" id="save" name="save">
		</div>
	</form>