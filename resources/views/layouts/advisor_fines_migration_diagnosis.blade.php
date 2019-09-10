<!DOCTYPE html>
<html>
	<div style="display:none;">
		<div id="text_general_advisor">
			The module allows modeling, forecasting and scaling at the field level and the impact on production due to the phenomenon of fine migration and swelling.
		</div>

		<div id="advisor_drainage_radius"><br>
			<b>Drainage radius (rdre): </b> The area of a reservoir in which a single well serves as a point for drainage of reservoir fluids. [1] It can be stimated through drawdown tests.
		</div>
		<div id="advisor_ref_drainage_radius"><br>
			[1] Rodrigues, C. (2011). A Dictionary for the Petroleum Industry, 55(16), 3202.
		</div>

		<div id="advisor_formation_height"><br>
			<b>Formation Height (Hf): </b> Net Pay is that part of the reservoir thickness which contributes to oil recovery. All available measurements performed on reservoir samples and in wells, such as core analysis and well logs, are extensively used in evaluating the reservoir net thickness. [2]
		</div>
		<div id="advisor_ref_formation_height"><br>
			[2] Ahmed, T. H. (2010). Reservoir Engineering Handbook (Fourth Edition). Gulf Professional Publishing. <a href="https://doi.org/10.1016/B978-1-4160-5009-4.50004-2" target="_blank">https://doi.org/10.1016/B978-1-4160-5009-4.50004-2</a>
		</div>

		<div id="advisor_well_radius"><br>
			<b>Well radius (rw): </b> The wellbore section is assumed to be a circle and has a specific radius called the wellbore radius. It can be estimated by the production casing internal diameter, or the bit size.
		</div>

		<div id="advisor_perforation_radius"><br>
			<b>Perforations radius (Rp): </b>  Obtained from completion design.
		</div>

		<div id="advisor_number_of_perforations"><br>
			<b>Numbers of perforations (tpp): </b>  Communication tunnel created from the casing or liner to the interior of the formation. Obtained from completion design.
		</div>

		<div id="advisor_compressibility"><br>
			<b>Compressibility (CR): </b>  Geological data, rock property measured during exploration or perforation of the reservoir. Rock compressibility is the change of volume of the rock in response to a pressure gradient. Hall's correlation is a way to calculate it:<br> <br>  <center>   C = (1.87*(10^-6)*(Phi^(-0.415))</center> <br> where C is rock compressibility, and Phi is the formation porosity.
		</div>

		<div id="advisor_initial_porosity"><br>
			<b>Initial Porosity (PHIO): </b>  The porosity of a rock is a measure of the storage capacity (pore volume) that is capable of holding fluids. It is the ratio of the pore volume to the total volume (bulk volume). [2] Core analysis and well logs, are extensively used in evaluating the initial porosity.
		</div>
		<div id="advisor_ref_initial_porosity"><br>
			[2] Ahmed, T. H. (2010). Reservoir Engineering Handbook (Fourth Edition). Gulf Professional Publishing. <a href="https://doi.org/10.1016/B978-1-4160-5009-4.50004-2" target="_blank">https://doi.org/10.1016/B978-1-4160-5009-4.50004-2</a>
		</div>

		<div id="advisor_porosity_limit_constant"><br>
			<b>Constant for the porosity limit: </b>  Constant to calculate the maximum porosity decrease. It can be obtained knowing the final permeability from multi-rate tests. Also It can be estimated with simulation by the "Yacimientos de hidrocarburos" group from the "Universidad Nacional sede Medellin"
		</div>

		<div id="advisor_initial_permeability"><br>
			<b>Initial permeability (Ko): </b>  Permeability is a property of the porous medium that measures the capacity and ability of the formation to transmit fluids. [2] Core analysis and well logs, are extensively used in evaluating the initial permeability.
		</div>
		<div id="advisor_ref_initial_permeability"><br>
			[2] Ahmed, T. H. (2010). Reservoir Engineering Handbook (Fourth Edition). Gulf Professional Publishing. <a href="https://doi.org/10.1016/B978-1-4160-5009-4.50004-2" target="_blank">https://doi.org/10.1016/B978-1-4160-5009-4.50004-2</a>
		</div>

		<div id="advisor_average_pore_diameter"><br>
			<b>Average pore diamater (dporo): </b>  Average width of the rock conducts that allow the fluid to move. Rock property measured during exploration or perforation of the reservoir, through core analysis.
		</div>

		<div id="advisor_initial_pressure"><br>
			<b>Initial pressure (pinit): </b>  The force exerted by fluids in a formation at the beginning of the production history. [1]
		</div>
		<div id="advisor_ref_initial_pressure"><br>
			[1] Rodrigues, C. (2011). A Dictionary for the Petroleum Industry, 55(16), 3202.
		</div>
		
		<div id="advisor_initial_saturation"><br>
			<b>Initial saturation (swi): </b>  The fraction of water in a given pore space. [1]
		</div>
		<div id="advisor_ref_initial_saturation"><br>
			[1] Rodrigues, C. (2011). A Dictionary for the Petroleum Industry, 55(16), 3202.
		</div>

		<div id="advisor_type_of_suspension_flux"><br>
			<b>Type of suspension flux (Finos Mov): </b>  Fluid in which the fines are suspended.
		</div>

		<div id="advisor_fine_density"><br>
			<b>Fine density (rhop): </b>  Lab test of the fines. It can be calculated by "Fenomenos de superficie" or "Fluidos de yacimiento" groups from the "Universidad Nacional sede Medellin". 
		</div>

		<div id="advisor_fine_diameter"><br>
			<b>Fine diameter (dpart): </b>  It can be found by experimental test of dynamic light scattering (DLS). It can be calculated by "Fenomenos de superficie" or "Fluidos de yacimiento" groups from the "Universidad Nacional sede Medellin". 
		</div>

		<div id="advisor_initial_deposited_fines_concentration"><br>
			<b>Initial deposited fines concentration in the fluid (sigma): </b>  It is assumed that only 2% of the mass of fines is deposited. It can be calculated with information from a core of the deposit and its mineralogy by "Fenomenos de superficie" or "Fluidos de yacimiento" groups from the "Universidad Nacional sede Medellin". 
		</div>

		<div id="advisor_critical_rate"><br>
			<b>Critical rate (tcri): </b>  Calculation of critical rate to laboratory conditions:<br><br>
			The critical velocity is the rate at which particles with a weak adhesion to the porous surface can be separated by the force of shearing or dragging the fluid, can be measured in the laboratory by means of a displacement test and scaled to field conditions . The test is based on the measurement of the permeability of the sample at different injection rates. The speed or flow rate where the permeability decreases by 10% of its initial value is considered the critical rate. Laboratory assembly:<br>
			<div align="center">
				<img src="{!! asset('images/advisor/fines module/critical_rate.png') !!}"/>
			</div>
			<br><br>
			Scaling of critical rate to reservoir conditions:<br>
			<div align="center" class="row">
				<img src="{!! asset('images/advisor/fines module/critical_rate1.png') !!}"/>
				<img src="{!! asset('images/advisor/fines module/critical_rate2.png') !!}"/>
			</div><br><br>
			It is assumed that:<br>
			<div align="center">
				<img src="{!! asset('images/advisor/fines module/critical_rate3.png') !!}"/>
			</div><br><br>
			Thus,<br>
			<div align="center">
				<img src="{!! asset('images/advisor/fines module/critical_rate4.png') !!}"/>
			</div><br><br>
			The critical rate to reservoir conditions for openhole is:<br>
			<div align="center" class="row">
				<div class="col-md-6">
					<img src="{!! asset('images/advisor/fines module/critical_rate5.png') !!}"/>
				</div>
				<div class="col-md-6">
					<img src="{!! asset('images/advisor/fines module/critical_rate6.png') !!}"/>
				</div>
			</div><br><br>
			The critical rate to reservoir conditions for cemented well is:<br>
			<div class="row">
				<div class="col-md-6">
					For rw = 3 ''<br>
					<div align="center">
						<img src="{!! asset('images/advisor/fines module/critical_rate7.png') !!}"/>
					</div><br><br>
					For rw = 6 ''<br>
					<div align="center">
						<img src="{!! asset('images/advisor/fines module/critical_rate8.png') !!}"/>
					</div>
				</div>
				<div class="col-md-6">
					<div align="center">
						<img src="{!! asset('images/advisor/fines module/critical_rate9.png') !!}"/>
					</div>
				</div>
			</div>
		</div>

		<div id="advisor_length"><br>
			<b>Length (L): </b>  Lab information. <br>
			<div align="center">
				<img src="{!! asset('images/advisor/fines module/d_l.png') !!}"/>
			</div>
		</div>

		<div id="advisor_diameter"><br>
			<b>Diameter (D): </b>  Lab information. <br>
			<div align="center">
				<img src="{!! asset('images/advisor/fines module/d_l.png') !!}"/>
			</div>
		</div>

		<div id="advisor_porosity"><br>
			<b>Porosity (P): </b>  Is a measure of the storage capacity (pore volume) that is capable of holding fluids. It is the ratio of the pore volume to the total volume (bulk volume). [2] 
		</div>
		<div id="advisor_ref_porosity"><br>
			[2] Ahmed, T. H. (2010). Reservoir Engineering Handbook (Fourth Edition). Gulf Professional Publishing. <a href="https://doi.org/10.1016/B978-1-4160-5009-4.50004-2" target="_blank">https://doi.org/10.1016/B978-1-4160-5009-4.50004-2</a>
		</div>

		<div id="advisor_clay_quartz"><br>
			<b>Quartz (cuarzo): </b>  It can be obtained from the study of thin sections or more sophisticated studies such as Density or Gamma Ray registration. Geological data.
		</div>

		<div id="advisor_illite"><br>
			<b>Illite (ilita): </b>  It can be obtained from the study of thin sections or more sophisticated studies such as Density or Gamma Ray registration. Geological data.
		</div>

		<div id="advisor_kaolinite"><br>
			<b>Kaolinite (kaolinita): </b>  It can be obtained from the study of thin sections or more sophisticated studies such as Density or Gamma Ray registration. Geological data.
		</div>

		<div id="advisor_chlorite"><br>
			<b>Chlorite (clorita): </b>  It can be obtained from the study of thin sections or more sophisticated studies such as Density or Gamma Ray registration. Geological data.
		</div>

		<div id="advisor_emectite"><br>
			<b>Emectite (esmectita): </b>  It can be obtained from the study of thin sections or more sophisticated studies such as Density or Gamma Ray registration. Geological data.
		</div>

		<div id="advisor_total_amount_of_clays"><br>
			<b>Total amount of clays (tarcilla): </b>  It can be obtained from the study of thin sections or more sophisticated studies such as Density or Gamma Ray registration. Geological data.
		</div>

		<div id="advisor_mineral_quartz"><br>
			<b>Quartz (cuarzo): </b>  It can be obtained from the study of thin sections or more sophisticated studies such as Density or Gamma Ray registration. Geological data.
		</div>

		<div id="advisor_feldspar"><br>
			<b>Feldspar (feldespato): </b>  It can be obtained from the study of thin sections or more sophisticated studies such as Density or Gamma Ray registration. Geological data.
		</div>

		<div id="advisor_clay"><br>
			<b>Clay (arcilla): </b>  It can be obtained from the study of thin sections or more sophisticated studies such as Density or Gamma Ray registration. Geological data.
		</div>

		<div id="advisor_code_table_historical_data_table"><br>
			Daily oil and water flows are obtained from the reservoir register.
		</div>

		<div id="advisor_code_table_phenomenological_constants_table"><br>
			<table class="table table-bordered">
		    	<tbody>
				    <tr>
				      	<th rowspan="3" scope="rowgroup">Parameters for deposit</th>
				      	<th scope="row">K1</th>
				      	<td>Phenomenological constant for particle retention</td>
				      	<td>It can be taken from the phenomenological constants simulator or database</td>
				    </tr>
				    <tr>
				      	<th scope="row">K2</th>
				      	<td>Phenomenological constant for particle entrainment</td>
				      	<td>It can be taken from the phenomenological constants simulator or database</td>
				    </tr>
				    <tr>
				      	<th scope="row">DP/DL</th>
				      	<td>Pressure gradient</td>
				      	<td>It can be taken from the phenomenological constants simulator or database</td>
				    </tr>
				</tbody>
		  	</table><br><br>

		  	<table class="table table-bordered">
		    	<tbody>
				    <tr>
				      	<th rowspan="5" scope="rowgroup">Parameters for generation</th>
				      	<th scope="row">K3</th>
				      	<td>Phenomenological constant for release of swelling particles</td>
				      	<td>It can be taken from the phenomenological constants simulator or database</td>
				    </tr>
				    <tr>
				      	<th scope="row">K4</th>
				      	<td>Phenomenological constant for fines movement</td>
				      	<td>It can be taken from the phenomenological constants simulator or database</td>
				    </tr>
				    <tr>
				      	<th scope="row">K5</th>
				      	<td>Phenomenological constant for erosion of surface fines</td>
				      	<td>It can be taken from the phenomenological constants simulator or database</td>
				    </tr>
				    <tr>
				      	<th scope="row">DP/DL</th>
				      	<td>Critical pressure gradient</td>
				      	<td>It can be taken from the phenomenological constants simulator or database</td>
				    </tr>
				    <tr>
				      	<th scope="row">SIGMA</th>
				      	<td>Initial deposited fines concentration</td>
				      	<td>It can be taken from the phenomenological constants simulator or database</td>
				    </tr>
				</tbody>
		  	</table><br><br>
		  	<table class="table table-bordered">
		    	<tbody>
				    <tr>
				      	<th rowspan="3" scope="rowgroup">Parameters for swelling</th>
				      	<th scope="row">K6</th>
				      	<td>Phenomenological constant, Ratio permeability- swelling </td>
				      	<td>It can be taken from the phenomenological constants simulator or database. Or calculate by:<br><br>  <center><img src="{!! asset('images/advisor/fines module/k6.png') !!}"/></center><br><b>Kt:</b> Value permeability inferior limit (Higher damage by swelling)<br>
						<b>Ko:</b> Initial permeability</td>
				    </tr>
				    <tr>
				      	<th scope="row">2AB</th>
				      	<td>Phenomenological constant for swelling <br><br>
							<b>A:</b> Rate constant <br>
							<b>B:</b> Phenomenological constant for liquid absorption</td>
				      	<td>It can be taken from the phenomenological constants simulator or database</td>
				    </tr>
				    <tr>
				      	<th scope="row">AB</th>
				      	<td>Phenomenological constant for swelling <br><br>
							<b>A:</b> Rate constant <br>
							<b>B:</b> Phenomenological constant for liquid absorption</td>
				      	<td>It can be taken from the phenomenological constants simulator or database</td>
				    </tr>
				</tbody>
		  	</table>
		</div>

		<div id="advisor_code_table_pvt_table"><br>
			Density (the mass or weight of a substance per unit volume), viscosity (measure of the resistance of a fluid to flow, it is commonly expressed in terms of the time required for a specific volume of the liquid to flow through a capillary tube of a specific size at a given temperature) and volume factor (relate the volume of oil or water, as measured at determinated conditions, to the volume of oil or water as measured at standard conditions) at specific pressures and reservoir temperature, obtained from differential liberation tests. [1]
		</div>
		<div id="advisor_ref_code_table_pvt_table"><br>
			[1] Rodrigues, C. (2011). A Dictionary for the Petroleum Industry, 55(16), 3202.
		</div>
	</div>
</html>