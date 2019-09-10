<!DOCTYPE html>
<html>
	<div style="display:none;">
		<div id="text_general_advisor">
			This module calculates the permeability of single fractures and the effective permeability of the entire system. It uses the Bartin-Bandish model for a fracture coupled with the kirsch equations.
		</div>

		<div id="advisor_well_azimuth"><br>
			When the well is not vertical it has an azimuth according to its dip. This value can be pbtained from the well survey.
		</div>

		<div id="advisor_well_dip"><br>
			The dip of the well. This value can be obtained from the well survey.
		</div>

		<div id="advisor_well_radius"><br>
			The wellbore cross section is assumed to be a circle and has a specific radius called the wellbore radius. It can be estimated by the production casing internal diameter, or the bit size.
		</div>

		<div id="advisor_max_analysis_radius"><br>
			This is the maximum radius, measured from the wellbore center, that will be studied by the module. The analysis is performed between the well radius and the max analysis radius.
		</div>

		<div id="advisor_analysis_interval"><br>
			This is the length of the vertical interval that will be studied.
		</div>

		<div id="advisor_reservoir_pressure"><br>
			The current reservoir pressure. It can be measured from wells tests. Another stimation can be obtained with the equation for steady-state radial flow [1][2]
		</div>
		<div id="advisor_ref_reservoir_pressure">
			[1] Petrowiki. Reservoir Pressure and Tmperature. Avaliable on: <a href="https://petrowiki.org/Reservoir_pressure_and_temperature#cite_note-r1-1" target="_blank">https://petrowiki.org/Reservoir_pressure_and_temperature#cite_note-r1-1</a><br><br>

			[2] Petrowiki. Well Test. Avaliable on: <a href="https://petrowiki.org/Well_test" target="_blank">https://petrowiki.org/Well_test</a>
		</div>

		<div id="advisor_matrix_permeability"><br>
			The permeability of the medium without presence of fractures. It can be measured from a flooding test in laboratory.
		</div>

		<div id="advisor_code_table_well_bottom_pressure_table"><br>
			This is the pressure measured In the bottom of the well when it is flowing. This value can be calculated with the wellhead pressure and pressure losses obtained from correlations [3]. The analysis can be performed with different well bottom pressures. There will be results for each value of the table.
		</div>
		<div id="advisor_ref_code_table_well_bottom_pressure_table">
			[3] Brown, K.E. (1980). The Technology of Artificial Lift Methods.
		</div>

		<div id="advisor_poisson_ratio"><br>
			This parameter is the ratio of the lateral strain ε_x  and axial strain ε_y that result from an applied axial stress, and is expressed by equation (1). For reservoir rocks, Poisson’s ratio is typically 0.15 – 0.25 [4][5][6]<br><br>
			<div align="center" class="row">
				<img src="{!! asset('images/advisor/geomechanical module/poisson_ratio.png') !!}"/>
			</div>
		</div>
		<div id="advisor_ref_poisson_ratio">
			[4] Crain's Petrophysical Handbook. Avaliabe on: <a href="https://www.spec2000.net/10-elastic.htm" target="_blank">https://www.spec2000.net/10-elastic.htm</a><br><br>

			[5] Aadnoy, B., & Looyeh, R. (2011). Petroleum rock mechanics: drilling operations and well design. Gulf Professional Publishing. <br><br>

			[6] Fjar, E., Holt, R. M., Raaen, A. M., Risnes, R., & Horsrud, P. (2008). Petroleum related rock mechanics (Vol. 53). Elsevier.
		</div>

		<div id="advisor_biot_coefficient"><br>
			This coefficient is a correction parameter for the effective stress equation, and refers to the cability  of matrix rock to withstand the effect of the confining pressure. This parameter varies between 0 and 1. When Biot coefficient is close to 1, the pore pressure counteract most of the confining pressure. While the Biot coefficient is close to 0, the matrix rock support most of the confining pressure. [4]
		</div>
		<div id="advisor_ref_biot_coefficient">
			[4] Crain's Petrophysical Handbook. Avaliabe on: <a href="https://www.spec2000.net/10-elastic.htm" target="_blank">https://www.spec2000.net/10-elastic.htm</a>
		</div>

		<div id="advisor_azimuth_maximum_horizontal_stress"><br>
			Direction of the maximum horizontal stress. In a normal situation, the maximum horizontal stress form a 90 º angle with the minimum horizontal stress.
		</div>

		<div id="advisor_minimum_horizontal_stress_gradient"><br>
			Stress imposed on formation by the presence of the adjacent rock materials to restrict lateral movement caused by overburden stress. The Minimum horizontal stress could be read from leak-off test [5][7]
		</div>
		<div id="advisor_ref_minimum_horizontal_stress_gradient">
			[5] Aadnoy, B., & Looyeh, R. (2011). Petroleum rock mechanics: drilling operations and well design. Gulf Professional Publishing. <br><br>

			[7] Engineering Geology for Underground Rocks - Suping Peng, Jincai Zhang
		</div>

		<div id="advisor_vertical_stress_gradient"><br>
			The stress produced by the combined weight of the rocks and formation fluids overlaying a depth of interest. This is determined from density and neutron logs [5][8]
		</div>
		<div id="advisor_ref_vertical_stress_gradient">
			[5] Aadnoy, B., & Looyeh, R. (2011). Petroleum rock mechanics: drilling operations and well design. Gulf Professional Publishing. <br><br>

			[8] Maximum Horizontal Stress and Well bore Stability While Drilling: Modeling and Case Study - S. Li and C. Purdy
		</div>

		<div id="advisor_maximum_horizontal_stress_gradient"><br>
			Stress imposed on formation by the presence of the adjacent rock materials to restrict lateral movement caused by overburden stress. The maximum horizontal stress can be calculated from image logs [5][8]
		</div>
		<div id="advisor_ref_maximum_horizontal_stress_gradient">
			[5] Aadnoy, B., & Looyeh, R. (2011). Petroleum rock mechanics: drilling operations and well design. Gulf Professional Publishing. <br><br>

			[8] Maximum Horizontal Stress and Well bore Stability While Drilling: Modeling and Case Study - S. Li and C. Purdy
		</div>

		<div id="advisor_initial_fracture_width"><br>
			The distance between the fracture walls. The width of the opening may depend (in reservoir conditions) on depth, pore pressure and type of rock. [9] 
		</div>
		<div id="advisor_ref_initial_fracture_width">
			[9] Van Golf-Racht, T. D. (1982). Fundamentals of fractured reservoir engineering (Vol. 12). Elsevier.
		</div>

		<div id="advisor_initial_fracture_toughness"><br>
			The normal stiffness is defined as the ratio of the normal stress and the normal displacement. This parameter is obtain from laboratory test. The normal stiffness is strongly connected to the contact area of the fracture surfaces [11]. 
		</div>
		<div id="advisor_ref_initial_fracture_toughness">
			[11] Bandis, S.C., Lumsden, A. C., & Barton, N. R. (1983). Fundamentals of rock joint deformation. International Journal of Rock Mechanics and Mining Sciences & Geomechanics Abstracts (Vol. 20, No. 6, pp. 249-268). Pergamon
		</div>

		<div id="advisor_fracture_closure_permeability"><br>
			Fracture permeability at the initial stress state. This parameter is obtained experimentally from the variation of the fracture permeability with normal effective stress [10].
		</div>
		<div id="advisor_ref_fracture_closure_permeability">
			[10] Computer Modeling Group. (2016). STARS User Guide. Computer Modeling Group
		</div>

		<div id="advisor_residual_fracture_closure_permeability"><br>
			Fracture permeability obtained after increasing the stress state. This parameter is obtained experimentally from the variation of the fracture permeability with normal effective stress [10].
		</div>
		<div id="advisor_ref_residual_fracture_closure_permeability">
			[10] Computer Modeling Group. (2016). STARS User Guide. Computer Modeling Group
		</div>

		<div id="advisor_code_table_fractures_table"><br>
			This table is filled with information obtained from a UBI log for each fracture.
		</div>
	</div>
</html>

