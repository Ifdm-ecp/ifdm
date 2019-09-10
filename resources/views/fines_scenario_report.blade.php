@extends('layouts.ProjectGeneral2')

@section('title', 'Scenario Report')

@section('content')
<div class="col-md-12" id="report">
    <div class="row">
        <div class="col-md-12">
            <h1 align="center">Fines Migration Report</h1>
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

    <div class="panel panel-default">
        <div class="panel-heading"><b>General Data</b></div>
        <div class="panel-body">
            <div class="panel panel-default">
                <div class="panel-heading"><b>Well Properties</b></div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Drainage Radius [ft]</th>
                                    <th>Net Pay [ft]</th>    
                                    <th>Well Radius [ft]</th>
                                    <th>Perforation Radius [inch]</th> 
                                    <th>Numbers Of Perforations [-]</th>       
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{!! $fines_d_diagnosis->drainage_radius !!}</td>
                                    <td>{!! $fines_d_diagnosis->formation_height !!}</td>
                                    <td>{!! $fines_d_diagnosis->well_radius !!}</td>
                                    <td>{!! $fines_d_diagnosis->perforation_radius !!}</td>
                                    <td>{!! $fines_d_diagnosis->number_of_perforations !!}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading"><b>Formation Properties</b></div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Compressibility [1/psi]</th>
                                    <th>Initial Porosity [Fraction]</th>    
                                    <th>Initial Permeability [mD]</th>     
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{!! $fines_d_diagnosis->compressibility !!}</td>
                                    <td>{!! $fines_d_diagnosis->initial_porosity !!}</td>
                                    <td>{!! $fines_d_diagnosis->initial_permeability !!}</td>
                                </tr>
                            </tbody>
                        </table><br><br>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Average Pore Diameter [μm]</th> 
                                    <th>Initial Pressure [psi]</th>     
                                    <th>Initial Saturation [Fraction]</th>       
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{!! $fines_d_diagnosis->average_pore_diameter !!}</td>
                                    <td>{!! $fines_d_diagnosis->initial_pressure !!}</td>
                                    <td>{!! $fines_d_diagnosis->initial_saturation !!}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading"><b>Fines Properties</b></div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Type of Suspension Flux</th>
                                    <th>Fine Density [μm]</th>    
                                    <th>Fine Diameter [g/cc]</th>     
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{!! $fines_d_diagnosis->type_of_suspension_flux !!}</td>
                                    <td>{!! $fines_d_diagnosis->fine_density !!}</td>
                                    <td>{!! $fines_d_diagnosis->fine_diameter !!}</td>
                                </tr>
                            </tbody>
                        </table><br><br>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Initial Deposited Fines Concentration [g/cc]</th> 
                                    <th>Critical Rate [cc/min]</th>     
                                    <th>Initial Fines Concentration In Fluid [ppm]</th>       
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{!! $fines_d_diagnosis->initial_deposited_fines_concentration !!}</td>
                                    <td>{!! $fines_d_diagnosis->critical_rate !!}</td>
                                    <td>{!! $fines_d_diagnosis->initial_fines_concentration_in_fluid !!}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading"><b>Deposited Fines</b></div>
                <div class="panel-body">
                     <div class="panel panel-default">
                        <div class="panel-heading"><b>Core Data</b></div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Length [cm]</th>
                                            <th>Diameter [cm]</th>    
                                            <th>Porosity [Fraction]</th>     
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>{!! $fines_d_diagnosis->length !!}</td>
                                            <td>{!! $fines_d_diagnosis->diameter !!}</td>
                                            <td>{!! $fines_d_diagnosis->porosity !!}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading"><b>Clay Data</b></div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Illite [%]</th>
                                            <th>Kaolinite [%]</th>    
                                            <th>Chlorite [%]</th> 
                                            <th>Emectite [%]</th>
                                            <th>Total Amount of Clays [%]</th>    
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>{!! $fines_d_diagnosis->illite !!}</td>
                                            <td>{!! $fines_d_diagnosis->kaolinite !!}</td>
                                            <td>{!! $fines_d_diagnosis->chlorite !!}</td>
                                            <td>{!! $fines_d_diagnosis->emectite !!}</td>
                                            <td>{!! $fines_d_diagnosis->total_amount_of_clays !!}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading"><b>Mineral Data</b></div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Quartz [%]</th>
                                            <th>Feldspar [%]</th>    
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>{!! $fines_d_diagnosis->quartz !!}</td>
                                            <td>{!! $fines_d_diagnosis->feldspar !!}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading"><b>PVT Data</b></div>
        <div class="panel-body">
            <div class="table-responsive">
                @if($fines_d_diagnosis->type_of_suspension_flux == "water")
                    <table class="table table-striped">
                        <thead >
                            <tr>
                                <th>Pressure [psi]</th>
                                <th>Water Volumetric Factor [bbl/BN]</th>     
                                <th>Water Viscosity [cP]</th>
                                <th>Water Density [g/cc]</th>       
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($fines_d_pvt as $pvt)
                                <tr>
                                    <td>{!! $pvt->pressure !!}</td>
                                    <td>{!! $pvt->volumetric_water_factor !!}</td>
                                    <td>{!! $pvt->water_viscosity !!}</td>
                                    <td>{!! $pvt->water_density !!}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @elseif($fines_d_diagnosis->type_of_suspension_flux == "oil")
                    <table class="table table-striped">
                        <thead >
                            <tr>
                                <th>Pressure [psi]</th>
                                <th>Oil Density [g/cc]</th>     
                                <th>Oil Viscosity [cP]</th>
                                <th>Oil Volumetric Factor [bbl/BN]</th>       
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($fines_d_pvt as $pvt)
                                <tr>
                                    <td>{!! $pvt->pressure !!}</td>
                                    <td>{!! $pvt->oil_density !!}</td>
                                    <td>{!! $pvt->oil_viscosity !!}</td>
                                    <td>{!! $pvt->volumetric_oil_factor !!}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading"><b>Phenomenological Constants</b></div>
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead >
                        <tr>
                            <th>Flow [cc/min]</th>
                            <th>K1</th>     
                            <th>K2</th>
                            <th>DP/DL [atm/cm]</th>    
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($fines_d_phenomenological_constants as $phenomenological_constants)
                            <tr>
                                <td>{!! $phenomenological_constants->flow !!}</td>
                                <td>{!! $phenomenological_constants->k1 !!}</td>
                                <td>{!! $phenomenological_constants->k2 !!}</td>
                                <td>{!! $phenomenological_constants->dp_dl !!}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table><br>
                <table class="table table-striped">
                    <thead >
                        <tr>    
                            <th>K3</th> 
                            <th>K4</th> 
                            <th>K5</th>  
                            <th>DP/DL [atm/cm]</th>  
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($fines_d_phenomenological_constants as $phenomenological_constants)
                            <tr>
                                <td>{!! $phenomenological_constants->k3 !!}</td>
                                <td>{!! $phenomenological_constants->k4 !!}</td>
                                <td>{!! $phenomenological_constants->k5 !!}</td>
                                <td>{!! $phenomenological_constants->dp_dl2 !!}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table><br>
                <table class="table table-striped">
                    <thead >
                        <tr>
                            <th>SIGMA</th>   
                            <th>K6</th> 
                            <th>2AB</th> 
                            <th>AB</th> 
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($fines_d_phenomenological_constants as $phenomenological_constants)
                            <tr>
                                <td>{!! $phenomenological_constants->sigma !!}</td>
                                <td>{!! $phenomenological_constants->k6 !!}</td>
                                <td>{!! $phenomenological_constants->ab_2 !!}</td>
                                <td>{!! $phenomenological_constants->ab !!}</td>
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
                            <th>BWPD [bbl/d]</th>       
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($fines_d_historical_data as $historical_data)
                            <tr>
                                <td>{!! $historical_data->date !!}</td>
                                <td>{!! $historical_data->bopd !!}</td>
                                <td>{!! $historical_data->bwpd !!}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

<br><br>

<h2>Fines Migration Results</h2><br>

    <div class="panel panel-default">
        <div class="panel-heading"><b>Fines Results</b></div>
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
                <div class="panel panel-default">
                    <div class="panel-heading"><b>Porosity</b></div>
                    <div class="panel-body">
                        <div id="porosity_chart"></div>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading"><b>Permeability</b></div>
                        <div class="panel-body">
                            <div id="permeability_chart"></div>
                        </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading"><b>Initial Fines Concentration</b></div>
                    <div class="panel-body">
                        <div id="co_chart"></div>
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
@include('js/fines_scenario_report')
@include('js/results_fines_migration_diagnosis')
@endsection