@extends('layouts.ProjectGeneral2')

@section('title', 'Scenario Report')

@section('content')
<div class="col-md-12" id="report">
    <div class="row">
        <div class="col-md-12">
            <h1 align="center">Asphaltene Report</h1>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-md-12">
            <h4><b>Scenario: </b>{!! $scenary_name !!}</h4>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <h4><b>Description: </b>{!! $scenary_description !!}</h4>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <h4><b>Date: </b>{!! $scenary_date !!}</h4>
        </div>
    </div>
    <hr>

    @if($asphaltenes_d_stability_analysis)
        <div class="row">
            <div class="col-md-12">
                <h2 align="center">Asphaltene Stability Analysis</h2>
            </div>
        </div><br>

        <div class="panel panel-default">
            <div class="panel-heading"><b>Components Data</b></div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead >
                            <tr>
                                <th>Component</th>
                                <th>Zi [0-1]</th>       
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($asphaltenes_d_stability_analysis_components as $asphaltenes_d_stability_analysis_component)
                                <tr>
                                    <td>{!! $asphaltenes_d_stability_analysis_component->component !!}</td>
                                    <td>{!! $asphaltenes_d_stability_analysis_component->mole_fraction !!}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>


        <div class="panel panel-default">
            <div class="panel-heading"><b>SARA Analysis</b></div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Saturated [% Weight]</th>
                                <th>Aromatics [% Weight]</th>
                                <th>Resines [% Weight]</th>
                                <th>Asphaltenes [% Weight]</th>        
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{!! $asphaltenes_d_stability_analysis->saturated !!}</td>
                                <td>{!! $asphaltenes_d_stability_analysis->aromatics !!}</td>
                                <td>{!! $asphaltenes_d_stability_analysis->resines !!}</td>
                                <td>{!! $asphaltenes_d_stability_analysis->asphaltenes !!}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>


        <div class="panel panel-default">
            <div class="panel-heading"><b>Saturation Data</b></div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Reservoir Initial Pressure [psi]</th>
                                <th>Bubble Pressure [psi]</th>
                                <th>Density At Reservoir Temperature [g/cc]</th>
                                <th>Current Reservoir Pressure [psi]</th> 
                                <th>API Gravity [°API]</th>        
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{!! $asphaltenes_d_stability_analysis->reservoir_initial_pressure !!}</td>
                                <td>{!! $asphaltenes_d_stability_analysis->bubble_pressure !!}</td>
                                <td>{!! $asphaltenes_d_stability_analysis->density_at_reservoir_temperature !!}</td>
                                <td>{!! $asphaltenes_d_stability_analysis->current_reservoir_pressure !!}</td>
                                <td>{!! $asphaltenes_d_stability_analysis->api_gravity !!}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <br><br>
        <h2>Stability Analysis Results</h2><br>


        <div class="panel panel-default">
            <div class="panel-heading"><b>Conclusions</b></div>
            <div class="panel-body">
                <div class="panel panel-danger">
                    <div class="panel-heading"><b>Light Components And Precipitated Asphaltenes</b></div>
                    <div class="panel-body">
                        <div id = "light_analysis_problem_level"></div>
                        <div id = "light_analysis_conclusion"></div>
                        <div id = "light_analysis_probability"></div>
                    </div>
               </div>

                <div class="panel panel-warning">
                    <div class="panel-heading"><b>SARA Stability Analysis</b></div>
                    <div class="panel-body">
                        <div id = "sara_analysis_problem_level"></div>
                        <div id = "sara_analysis_conclusion"></div>
                        <div id = "sara_analysis_probability"></div>
                    </div>
                </div>

                <div class="panel panel-success">
                    <div class="panel-heading"><b>Colloidal Stability Index Analysis</b></div>
                    <div class="panel-body">
                        <div id = "colloidal_analysis_problem_level"></div>
                        <div id = "colloidal_analysis_conclusion"></div>
                        <div id = "colloidal_analysis_probability"></div>
                    </div>
                </div>

                <div class="panel panel-default">
                    <div class="panel-heading"><b>Risk Analysis</b></div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-8">
                                <p></p>
                                <div id = "precipitation_risk_light"></div>
                                <div id = "precipitation_risk_sara"></div>
                                <div id = "precipitation_risk_colloidal"></div>
                                <div class="well well-sm" id = "precipitation_risk_fluid"></div>
                            </div>
                            <div class="col-md-4">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Risk</th>
                                            <th>Level</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="success">
                                            <td>0</td>
                                            <td>None</td>
                                        </tr>
                                        <tr class="success">
                                            <td>1</td>
                                            <td>Low-low</td>
                                        </tr>
                                        <tr class="success">
                                            <td>2</td>
                                            <td>Low-high</td>
                                        </tr>
                                        <tr class="warning">
                                            <td>3</td>
                                            <td>Medium-low</td>
                                        </tr>
                                        <tr class="warning">
                                            <td>4</td>
                                            <td>Medium-high</td>
                                        </tr>
                                        <tr class="danger">
                                            <td>5</td>
                                            <td>High-low</td>
                                        </tr>
                                        <tr class="danger">
                                            <td>6</td>
                                            <td>High-high</td>
                                        </tr>
                                        <tr class="danger">
                                            <td>7</td>
                                            <td>Severe</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading"><b>Boer Stability Analysis</b></div>
            <div class="panel-body">
                <p></p>
                <div id="boer_chart"></div>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading"><b>Boer Stability Analysis</b></div>
            <div class="panel-body">
                <div class="panel panel-default">
                    <div class="panel-heading"><b>Colloidal Instability Index Analysis</b></div>
                    <div class="panel-body">
                        <div id="cii_chart"></div>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading"><b>Stankiewicz Stability Index Analysis</b></div>
                    <div class="panel-body">
                        <div id="stankiewicz_chart"></div>
                    </div>
                </div>
            </div>
        </div>
        
        <br>
        <br>
        <hr>
        <hr class="hr-height">
        <hr>
        <br>
        <br>
    @endif

    @if($asphaltenes_d_diagnosis)
        <div class="row">
            <div class="col-md-12">
                <h2 align="center">Asphaltene Diagnosis</h2>
            </div>
        </div><br>

        <div class="panel panel-default">
            <div class="panel-heading"><b>General Data</b></div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead >
                            <tr>
                                <th>Drainage Radius [ft]</th>
                                <th>Net Pay [ft]</th>  
                                <th>Wellbore Radius [ft]</th>
                                <th>Compressibility [1/psi]</th>  
                                <th>Initial Pressure [psi]</th>    
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{!! $asphaltenes_d_diagnosis->drainage_radius !!}</td>
                                <td>{!! $asphaltenes_d_diagnosis->net_pay !!}</td>
                                <td>{!! $asphaltenes_d_diagnosis->wellbore_radius !!}</td>
                                <td>{!! $asphaltenes_d_diagnosis->compressibility !!}</td>
                                <td>{!! $asphaltenes_d_diagnosis->initial_pressure !!}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <br><br>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead >
                            <tr>
                                <th>Initial Porosity [decimal]</th>  
                                <th>Initial Permeability [mD]</th>
                                <th>Average Pore Diameter [um]</th>  
                                <th>Asphaltene Particle Diameter [um]</th>
                                <th>Asphaltene Apparent Density [um]</th>       
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{!! $asphaltenes_d_diagnosis->initial_porosity !!}</td>
                                <td>{!! $asphaltenes_d_diagnosis->initial_permeability !!}</td>
                                <td>{!! $asphaltenes_d_diagnosis->average_pore_diameter !!}</td>
                                <td>{!! $asphaltenes_d_diagnosis->asphaltene_particle_diameter !!}</td>
                                <td>{!! $asphaltenes_d_diagnosis->asphaltene_apparent_density !!}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading"><b>PVT Data</b></div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead >
                            <tr>
                                <th>Pressure [psi]</th>
                                <th>Density [g/cc]</th>     
                                <th>Oil Viscosity [cp]</th>
                                <th>Oil Formation Volumetric Factor [bbl/STB]</th>       
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($asphaltenes_d_diagnosis_pvt as $pvt)
                                <tr>
                                    <td>{!! $pvt->pressure !!}</td>
                                    <td>{!! $pvt->density !!}</td>
                                    <td>{!! $pvt->oil_viscosity !!}</td>
                                    <td>{!! $pvt->oil_formation_volume_factor !!}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading"><b>Historical Data</b></div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead >
                            <tr>
                                <th>Date [YYYY-MM-DD]</th>
                                <th>BOPD [bbl/d]</th>     
                                <th>Asphaltenes [%wt]</th>     
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($asphaltenes_d_diagnosis_historical_data as $historical_data)
                                <tr>
                                    <td>{!! $historical_data->date !!}</td>
                                    <td>{!! $historical_data->bopd !!}</td>
                                    <td>{!! $historical_data->asphaltenes !!}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading"><b>Asphaltenes Data</b></div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead >
                            <tr>
                                <th>Pressure [psi]</th>
                                <th>Asphaltene Soluble Fraction [Fraction]</th>  
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($asphaltenes_d_diagnosis_soluble_asphaltenes as $diagnosis_soluble_asphaltenes)
                                <tr>
                                    <td>{!! $diagnosis_soluble_asphaltenes->pressure !!}</td>
                                    <td>{!! $diagnosis_soluble_asphaltenes->asphaltene_soluble_fraction !!}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <br><br>
        <h2>Asphaltene Diagnosis Results</h2><br>



        <div class="panel panel-default">
            <div class="panel-heading"><b>Deposited Asphaltenes Results</b></div>
            <div class="panel-body">
                <div class="col-md-12">
                    <div class="row">
                        <label>Please choose one or more dates for plotting the results</label>
                        <select class="selectpicker show-tick"  data-live-search="true" data-width="100%" data-style="btn-primary" id="date_select" multiple>
                            <option selected disabled>Dates</option>
                                @foreach ($dates_data as $date)
                                    <option value = "{!! $date !!}">{!! $date !!}</option>
                                @endforeach
                        </select>
                    </div>
                    <br>
                    <div class="row">
                        <div class="panel panel-default">
                            <div class="panel-heading"><b>Porosity</b></div>
                            <div class="panel-body">
                                <div id="porosity_chart"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="panel panel-default">
                            <div class="panel-heading"><b>Permeability</b></div>
                            <div class="panel-body">
                                <div id="permeability_chart"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="panel panel-default">
                            <div class="panel-heading"><b>Deposited Asphaltenes</b></div>
                            <div class="panel-body">
                                <div id="deposited_asphaltenes_chart"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="panel panel-default">
                            <div class="panel-heading"><b>Soluble Asphaltenes</b></div>
                            <div class="panel-body">
                                <div id="soluble_asphaltenes_chart"></div>
                            </div>
                        </div>
                    </div>
                </div>
              <br>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading"><b>Skin Results</b></div>
            <div class="panel-body">
                <div class="panel panel-default">
                    <div class="panel-heading"><b>Damage Radius</b></div>
                    <div class="panel-body">
                        <div id="damage_radius_chart"></div>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading"><b>Skin</b></div>
                    <div class="panel-body">
                        <div id="skin_chart"></div>
                    </div>
                </div>
            </div>
        </div>

        <br>
        <br>
        <hr>
        <hr class="hr-height">
        <hr>
        <br>
        <br>
    @endif


    @if($asphaltenes_d_precipitated_analysis)
        <div class="row">
            <div class="col-md-12">
                <h2 align="center">Precipitated Asphaltene Analysis</h2>
            </div>
        </div><br>

        <div class="panel panel-default">
            <div class="panel-heading"><b>Component Analysis</b></div>
            <div class="panel-body">
                <div class="panel panel-default">
                    <div class="panel-heading"><b>Components Data</b></div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead >
                                    <tr>
                                        <th>Components</th>
                                        <th>Zi [0-1]</th>  
                                        <th>MW [lb]</th>
                                        <th>Pc [psi]</th>  
                                        <th>Tc [F]</th> 
                                        <th>W</th> 
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($asphaltenes_d_precipitated_analysis_components_data as $components_data)
                                        <tr>
                                            <td>{!! $components_data->component !!}</td>
                                            <td>{!! $components_data->zi !!}</td>
                                            <td>{!! $components_data->mw !!}</td>
                                            <td>{!! $components_data->pc !!}</td>
                                            <td>{!! $components_data->tc !!}</td>
                                            <td>{!! $components_data->w !!}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <br><br>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead >
                                    <tr>
                                        <th>Components</th>
                                        <th>Shift</th>  
                                        <th>SG</th>
                                        <th>Tb [R]</th>  
                                        <th>Vc [ft3/lbmol]</th> 
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($asphaltenes_d_precipitated_analysis_components_data as $components_data)
                                        <tr>
                                            <td>{!! $components_data->component !!}</td>
                                            <td>{!! $components_data->shift !!}</td>
                                            <td>{!! $components_data->sg !!}</td>
                                            <td>{!! $components_data->tb !!}</td>
                                            <td>{!! $components_data->vc !!}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if($asphaltenes_d_precipitated_analysis->correlation)
                            <br>
                            <div class="panel panel-default">
                                <div class="panel-heading"><b>Plus Characterization</b></div>
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead >
                                                <tr>
                                                    <th>Plus Fraction Molecular Weight (MW) [lb/lbmol]</th>
                                                    <th>Plus Fraction Specific Gravity [-]</th>  
                                                    <th>Plus Fraction Boiling Temperature [R]</th>
                                                    <th>Sample Molecular Weight [lb/lbmol]</th>  
                                                    <th>Correlation</th> 
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>{!! $asphaltenes_d_precipitated_analysis->plus_fraction_molecular_weight !!}</td>
                                                    <td>{!! $asphaltenes_d_precipitated_analysis->plus_fraction_specific_gravity !!}</td>
                                                    <td>{!! $asphaltenes_d_precipitated_analysis->plus_fraction_boiling_temperature !!}</td>
                                                    <td>{!! $asphaltenes_d_precipitated_analysis->sample_molecular_weight !!}</td>
                                                    <td>{!! $asphaltenes_d_precipitated_analysis->correlation !!}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="panel panel-default">
                    <div class="panel-heading"><b>Binary Interaction Coefficients Data</b></div>
                    <div class="panel-body">
                        
                    </div>
                </div>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading"><b>Saturation Data</b></div>
            <div class="panel-body">
                <div class="panel panel-default">
                    <div class="panel-heading"><b>Bubble Point Data</b></div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead >
                                    <tr>
                                        <th>Temperature (Bubble Curve) [°F]</th>
                                        <th>Bubble Pressure [psi]</th>  
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($asphaltenes_d_precipitated_analysis_temperatures as $precipitated_analysis_temperatures)
                                        <tr>
                                            <td>{!! $precipitated_analysis_temperatures->temperature !!}</td>
                                            <td>{!! $precipitated_analysis_temperatures->bubble_pressure !!}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="panel panel-default">
                    <div class="panel-heading"><b>Saturation Data</b></div>
                    <div class="panel-body">
                        <table class="table table-striped">
                            <thead >
                                <tr>
                                    <th>Critical Temperature (Envolope phase) [°F]</th>
                                    <th>Critical Pressure (Envelope phase) [psi]</th>  
                                    <th>Density at Reservoir Pressure [g/cc]</th>
                                    <th>Density at Bubble Pressure [g/cc]</th>  
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{!! $asphaltenes_d_precipitated_analysis->critical_temperature !!}</td>
                                    <td>{!! $asphaltenes_d_precipitated_analysis->critical_pressure !!}</td>
                                    <td>{!! $asphaltenes_d_precipitated_analysis->density_at_reservoir_pressure !!}</td>
                                    <td>{!! $asphaltenes_d_precipitated_analysis->density_at_bubble_pressure !!}</td>
                                </tr>
                            </tbody>
                        </table><br><br>
                        <table class="table table-striped">
                            <thead >
                                <tr>
                                    <th>Density at Atmospheric Pressure [g/cc]</th> 
                                    <th>Reservoir Temperature [°F]</th>
                                    <th>Current Reservoir Pressure [psi]</th>  
                                    <th>Fluid API Gravity [°API]</th> 
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{!! $asphaltenes_d_precipitated_analysis->density_at_atmospheric_pressure !!}</td>
                                    <td>{!! $asphaltenes_d_precipitated_analysis->reservoir_temperature !!}</td>
                                    <td>{!! $asphaltenes_d_precipitated_analysis->current_reservoir_pressure !!}</td>
                                    <td>{!! $asphaltenes_d_precipitated_analysis->fluid_api_gravity !!}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading"><b>Asphaltenes Data</b></div>
            <div class="panel-body">
                <div class="panel panel-default">
                    <div class="panel-heading"><b>Temperature Data</b></div>
                    <div class="panel-body">
                        <table class="table table-striped">
                            <thead >
                                <tr>
                                    <th>Initial Temperature [R]</th> 
                                    <th>Number Of Temperatures [-]</th>
                                    <th>Temperature Delta [-]</th>  
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{!! $asphaltenes_d_precipitated_analysis->initial_temperature !!}</td>
                                    <td>{!! $asphaltenes_d_precipitated_analysis->number_of_temperatures !!}</td>
                                    <td>{!! $asphaltenes_d_precipitated_analysis->temperature_delta !!}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="panel panel-default">
                    <div class="panel-heading"><b>Asphaltenes Data</b></div>
                    <div class="panel-body">
                        <table class="table table-striped">
                            <thead >
                                <tr>
                                    <th>Asphaltene Particle Diameter [nm]</th> 
                                    <th>Asphaltene Molecular Weight [lb/lbm]</th>
                                    <th>Asphaltene Apparent Density [g/cc]</th>  
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{!! $asphaltenes_d_precipitated_analysis->asphaltene_particle_diameter !!}</td>
                                    <td>{!! $asphaltenes_d_precipitated_analysis->asphaltene_molecular_weight !!}</td>
                                    <td>{!! $asphaltenes_d_precipitated_analysis->asphaltene_apparent_density !!}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="panel panel-default">
                    <div class="panel-heading"><b>SARA Analysis</b></div>
                    <div class="panel-body">
                        <table class="table table-striped">
                            <thead >
                                <tr>
                                    <th>Saturate [% Weight]</th> 
                                    <th>Aromatic [% Weight]</th>
                                    <th>Resine [% Weight]</th>  
                                    <th>Asphaltene [% Weight]</th>  
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{!! $asphaltenes_d_precipitated_analysis->saturate !!}</td>
                                    <td>{!! $asphaltenes_d_precipitated_analysis->aromatic !!}</td>
                                    <td>{!! $asphaltenes_d_precipitated_analysis->resine !!}</td>
                                    <td>{!! $asphaltenes_d_precipitated_analysis->asphaltene !!}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                @if($asphaltenes_d_precipitated_analysis->include_elemental_asphaltene_analysis == true)
                    <div class="panel panel-default">
                        <div class="panel-heading"><b>Elemental Asphaltene Analysis Data</b></div>
                        <div class="panel-body">
                            <table class="table table-striped">
                                <thead >
                                    <tr>
                                        <th>Hydrogen Carbon Ratio [-]</th> 
                                        <th>Oxygen Carbon Ratio [-]</th>
                                        <th>Nitrogen Carbon Ratio [-]</th> 
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{!! $asphaltenes_d_precipitated_analysis->hydrogen_carbon_ratio !!}</td>
                                        <td>{!! $asphaltenes_d_precipitated_analysis->oxygen_carbon_ratio !!}</td>
                                        <td>{!! $asphaltenes_d_precipitated_analysis->nitrogen_carbon_ratio !!}</td>
                                    </tr>
                                </tbody>
                            </table><br><br>
                            <table class="table table-striped">
                                <thead >
                                    <tr>
                                        <th>Sulphure Carbon Ratio [-]</th>  
                                        <th>FA Aromaticity [-]</th>  
                                        <th>VC Molar Volume [-]</th>  
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{!! $asphaltenes_d_precipitated_analysis->sulphure_carbon_ratio !!}</td>
                                        <td>{!! $asphaltenes_d_precipitated_analysis->fa_aromaticity !!}</td>
                                        <td>{!! $asphaltenes_d_precipitated_analysis->vc_molar_volume !!}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            </div>
        </div>

  

        <br><br>
        <h2>Precipitated Asphaltene Analysis Results</h2><br>



        <div class="panel panel-default">
            <div class="panel-heading"><b>Saturation Results</b></div>
            <div class="panel-body">
                <div id="saturation_results_chart"></div>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading"><b>Solid Results</b></div>
            <div class="panel-body">
                <div id="solid_a_results_chart"></div>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading"><b>Onset Pressure Results</b></div>
            <div class="panel-body">
                <div class="panel panel-default">
                    <div class="panel-heading"><b>Onset Pressure Results</b></div>
                    <div class="panel-body">
                        <div id="onset_pressure_chart"></div>
                    </div>
                </div>
            
                <div class="panel panel-default">
                    <div class="panel-heading"><b>Asphaltenes Soluble Fraction</b></div>
                    <div class="panel-body">
                        <div id="asphaltenes_soluble_fraction_chart"></div>
                    </div>
                </div>
            </div>
        </div>
        
        <br>
        <br>
        <br>
    @endif


<div>
    <p class="pull-right">            
        <button id="download" onclick="download();" class="btn btn-primary">Print Report</button>
    </p>
</div>

<style type="text/css">
    .hr-height {
        height: 5px;
        background-color: black;
    }
</style>


@endsection


@section('Scripts')
@include('js/asphaltene_scenario_report')

@if($asphaltenes_d_stability_analysis)
@include('js/results_asphaltene_stability_analysis')
@endif

@if($asphaltenes_d_diagnosis)
@include('js/results_asphaltenes_diagnosis')
@endif

@if($asphaltenes_d_precipitated_analysis)
@include('js/results_precipitated_asphaltenes_analysis')
@endif()
@endsection