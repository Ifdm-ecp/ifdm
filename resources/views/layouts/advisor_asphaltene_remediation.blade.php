<!DOCTYPE html>
<html>
<div style="display:none;">
	<div id="text_general_advisor">
		Asphaltene remotion model determines the restauration of permeability and deposited ashpaltene concentration in-situ against damage radius from a chemical stimulation performed in the well. This improvement in the properties allow increase production and forecast it through the time.
	</div>

	<div id="advisor_initial_porosity">
		<br>
		The porosity of a rock is a measure of the storage capacity (pore volume) that is capable of holding fluids. It is the ratio of the pore volume to the total volume (bulk volume). [1] Core analysis and well logs, are extensively used in evaluating the initial porosity.
	</div>
	<div id="advisor_ref_initial_porosity">
		[1] Ahmed, T. H. (2010). Reservoir Engineering Handbook (Fourth Edition). Gulf Professional Publishing. <a href="https://doi.org/10.1016/B978-1-4160-5009-4.50004-2">https://doi.org/10.1016/B978-1-4160-5009-4.50004-2</a>
	</div>


	<div id="advisor_net_pay">
		<br>
		Net Pay is that part of the reservoir thickness which contributes to oil recovery. All available measurements performed on reservoir samples and in wells, such as core analysis and well logs, are extensively used in evaluating the reservoir net thickness. [2]
	</div>
	<div id="advisor_ref_net_pay">
		[2] Ahmed, T. H. (2010). Reservoir Engineering Handbook (Fourth Edition). Gulf Professional Publishing. <a href="https://doi.org/10.1016/B978-1-4160-5009-4.50004-2">https://doi.org/10.1016/B978-1-4160-5009-4.50004-2</a>
	</div>

	<div id="advisor_water_saturation">
		<br>
		 Water saturation is a measure of the amount of water in the porous medium, it is expressed in volume/volume. saturation data is often reported in percentage units but is always a fraction in equations. It can be calculated from effective porosity and resistivity log[4]. 
	</div>
	<div id="advisor_ref_water_saturation">
		<br>
		 [4] E.R Crain (2016). Crain’s Petrophysical Pocket Pal. Chapter 14. The Common Water Saturation Methods.  <a href="https://www.spec2000.net">https://www.spec2000.net</a> 
	</div>

	<div id="advisor_initial_permeability">
		<br>
		Permeability is a property of the porous medium that measures the capability of the formation to let flow fluids. This is the value of permeability regarding early times.  [3] Core analysis and well logs, are extensively used in evaluating the initial permeability.
	</div>
	<div id="advisor_ref_initial_permeability">
		<br>
		[3] Ahmed, T. H. (2010). Reservoir Engineering Handbook (Fourth Edition). Gulf Professional Publishing. <a href="https://doi.org/10.1016/B978-1-4160-5009-4.50004-2">https://doi.org/10.1016/B978-1-4160-5009-4.50004-2</a>
	</div>

	<div id="code_table_excel_changes_along_the_radius_input">
		<br>
		this table can be completed with Asphaltene Diagnosis Model results which brings permeability variation and deposited Asphaltene concentration along the radius. Asphaltene deposits increase where the pressure drops are higher, these particles plug the pore throat reducing permeability.
	</div>

	<div id="advisor_asphaltene_apparent_density">
		<br>
		Default value: 1,2 g/cc. It can be calculated through optical microscopy and high pressure SEM(Scanning Electron Microscope) and goes from 0.95 to 1.2 g/cc. Also, it can be estimated with simulation by the "Yacimientos de hidrocarburos" group from the "Universidad Nacional sede Medellin".
	</div>

	<div id="advisor_asphaltene_dilution_capacity">
		<br>
		 this concentration refers to how many milligrams of Asphaltene is possible diluting in a liter of chemical treatment. It can be calculated from static laboratory tests.
	</div>

	<div id="advisor_code_option_treatment">
		<br>
		The current reservoir pressure. It can be measured from wells tests. Another stimation can be obtained with the equation for steady-state radial flow [2][3]
	</div>
	
	<div id="advisor_treatment_radius">
		this is the limit radius where expects the chemical treatment remediate Asphaltene damage.
	</div>

	<div id="advisor_wellbore_radius">
		<br>
		The wellbore section is assumed to be a circle and has a specific radius called the wellbore radius. It can be estimated by the production casing internal diameter, or the bit size in the producing zone. This value can be found in the Schematic Well.
	</div>

	<div id="advisor_monthly_decline_rate">
		<br>
		the reservoir-draining reduce the flow rate along the producing time, the decline rate is an estimated measure in percentage of how much decline the well production every month. It can be calculated from well testing where the flow is measured in well head, and the commonly method used is the exponential. 
	</div>
	<div id="advisor_href_monthly_decline_rate">
		<br>
		[5] M.J Fetkovich et al (1996). Useful Concepts for Decline Curve Forecasting, Reserve Estimation, and Analysis. SPE Reservoir Engineering. <a href="http://dx.doi.org/10.2118/28628-PA">http://dx.doi.org/10.2118/28628-PA</a>
	</div>

	<div id="advisor_current_oil_production">
		<br>
		the current value of  the amount of oil flowing daily through the well in Barrels per day (bbl/d). This is taken  from Well Testing and estimated by IPR curves. 
	</div>

	<div id="advisor_current_permeability">
		<br>
		the current value of  the amount of oil flowing daily through the well in Barrels per day (bbl/d). This is taken  from Well Testing and estimated by IPR curves. 
	</div>

	<div id="advisor_asphaltene_remotion_efficiency_range_a">
		<br>
		it’s a percentage range to calculate different scenarios regarding asphaltene dilution.  100% total dilution, 0% no dilution, taking into account  dinamic laboratory tests.
	</div>
	<div id="advisor_skin_characterization">
		<br>
		it’s a percentage range to calculate different scenarios regarding asphaltene dilution.  100% total dilution, 0% no dilution, taking into account  dinamic laboratory tests.
	</div>

	<div id="advisor_skin_characterization_scale">
		<br>
		damage associated to scale deposits in the porous medium.
	</div>

	<div id="advisor_skin_characterization_asphaltene">
		<br>
		damage associated to Asphaltene deposits in the porous medium. 
	</div>

	<div id="advisor_skin_characterization_induced">
		<br>
		damage associated to mud losses during the drilling well.
	</div>

	<div id="advisor_skin_characterization_fines">
		<br>
		damage associated to fines deposited in porous medium as clay and lime due to high production rates.
	</div>

	<div id="advisor_skin_characterization_non_darcy">
		<br>
		damage associated to turbulent flow, it’s normally present in Gas Wells.  
	</div>

	<div id="advisor_skin_characterization_geomechanical">
		<br>
		damage associated to changes in stress in-situ, usually fields where the stress values are higher (high deeps) and the dropping pressure causes changes in permeability.  
	</div>

</div>
</html>

