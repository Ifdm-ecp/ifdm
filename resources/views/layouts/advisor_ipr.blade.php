<!DOCTYPE html>
<html>
	<div style="display:none;">
		<div id="text_general_advisor">
			This module calculates the well performance with the concept of IPR (Inflow performance relationship) and the skin factor. This relationship of flow rates against bottom hole pressure take into account petrophysical properties of the reservoir, fluids properties and basic configuration of the well.
		</div>

		<div id="advisor_fluido">
			<br>
			This is the type of fluid present in the reservoir. A general description of each fluid can be found in Ahmed work [1].
		</div>
		<div id="advisor_well_type">
			<br>
			This is the type of well present in the reservoir.
		</div>
		<div id="advisor_ref_fluido">
			[1] Ahmed, T. (2001). Reservoir Engineering Handbook
		</div>

		<div id="advisor_tipo_roca">
			<br>
			This module supports three kind of rock: Consolidated (well cemented rock), Unconsolidated (poorly cemented) and Microfractured
		</div>
		
		<div id="advisor_radio_pozo">
			<br>
			The wellbore cross section is assumed to be a circle and has a specific radius called the wellbore radius. It can be estimated by the production casing internal diameter, or the bit size.
		</div>

		<div id="advisor_presion_yacimiento">
			<br>
			The current reservoir pressure. It can be measured from wells tests. Another stimation can be obtained with the equation for steady-state radial flow [2][3]
		</div>
		<div id="advisor_ref_presion_yacimiento">
			[2] Petrowiki. Reservoir Pressure and Tmperature. Avaliable on: <a href="https://petrowiki.org/Reservoir_pressure_and_temperature#cite_note-r1-1" target="_blank">https://petrowiki.org/Reservoir_pressure_and_temperature#cite_note-r1-1</a><br><br>

			[3] Petrowiki. Well Test. Avaliable on: <a href="https://petrowiki.org/Well_test" target="_blank">https://petrowiki.org/Well_test</a> 
		</div>

		<div id="advisor_tasa_flujo">
			<br>
			The current oil rate of the well.
		</div>

		<div id="advisor_presion_fondo">
			<br>
			This is the pressure measured In the bottom of the well when it is flowing. This value can be calculated with the wellhead pressure and pressure losses obtained from correlations [4].
		</div>
		<div id="advisor_ref_presion_fondo">
			[4] Brown, K.E. (1980). The Technology of Artificial Lift Methods
		</div>

		<div id="advisor_bsw">
			<br>
			This is the basic sediment and water content of the production stream. It is measured from a liquid sample as a fraction.
		</div>

		<div id="advisor_presion_fondo">
			<br>
			This is the pressure measured In the bottom of the well when it is flowing. This value can be calculated with the wellhead pressure and pressure losses obtained from correlations [4].
		</div>
		<div id="advisor_ref_presion_fondo">
			[4] Brown, K.E. (1980). The Technology of Artificial Lift Methods
		</div>

		<div id="advisor_presion_inicial">
			<br>
			The force exerted by fluids in a formation at the beginning of the production history [5]. 
		</div>
		<div id="advisor_ref_presion_inicial">
			[5] Rodrigues, C. (2011). A Dictionary for the Petroleum Industry, 55(16), 3202.
		</div>

		<div id="advisor_permeabilidad_abs_ini">
			<br>
			Permeability of the rock measured at the beginning of the production history.
		</div>

		<div id="advisor_espesor_reservorio">
			<br>
			The length of the production zone. It can be calculated from well logs.
		</div>

		<div id="advisor_modulo_permeabilidad">
			<br>
			This term helps to measure the permeability reduction due to depletion in stress-sensitive reservoirs. There are three ways to calculated its value:<br><br>

			1. Analysis of a pressure test in the transient period as explained in Lopez et al. work [6]<br><br>
			2. Discrete measure in a coreflood test where a depletion process is simulated [7]<br><br>
			3. Using correlations. This module provides a correlation based on Santamaria work [8]. This can be used selecting the option “calculate permeability Module”.

		</div>
		<div id="advisor_ref_modulo_permeabilidad">
			[6] Lopez et al. (2014). Study of Formation Skin Caused by Changes in Pore Pressure Using a Coupled Simulator <br><br>

			[7] Santamaria, O. (2015). El Efecto de la Tasa de Flujo y los Esfuerzos en la Pérdida de Productividad de Yacimientos Petrolíferos <br><br>

 			[8] Santamaría, O. Lopera, S. (2017). Metología Para Evaluación del Daño de Formación Debido a Cambios en el Esfuerzo Efectivo a Partir del Concepto de Unidad Hidráulica de Flujo <br>
		</div> 

		<div id="advisor_permeabilidad">
			<br>
			The permeability of the medium. It can be measured from a test in laboratory.
		</div>

		<div id="advisor_porosidad">
			<br>
			The porosity of the medium. This value is calculated in laboratory tests or from well logs [9][10].
		</div>
		<div id="advisor_ref_porosidad">
			[9] Lopera, S. (2009). Análisis petrofísicos básicos y especiales. <br><br>

			[10] Bassiouni, Z. (1994). Theory, Measurement and Interpretation of Well Logs
		</div>


		<div id="advisor_presion_saturacion">
			<br>
			Saturation pressure of the fluid. Measured in a CVD or CCE test [1]. Measured in a CVD or CCE test [1]
		</div>
		<div id="advisor_ref_presion_saturacion">
			[1] Ahmed, T. (2001). Reservoir Engineering Handbook
		</div>

		<div id="advisor_code_table_excel_table">
			<br>
			Volumetric factors and viscosities of the fluids at different pressures. Measured in a CVD or CCE test [1]. Measured in a CVD or CCE test [1]
		</div>
		<div id="advisor_ref_code_table_excel_table">[1] Ahmed, T. (2001). Reservoir Engineering Handbook</div>
	</div>
</html>

