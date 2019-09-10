@extends('layouts.ProjectGeneral2')

@section('title', 'Scenario Report')

@section('content')
<div class="col-md-12" id="report">
    <div class="row">
        <div class="col-md-12">
            <h1 align="center"><div id="tit_rep"></div></h1>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-md-12">
            <h4><b>Scenario: </b><div id="sub_rep"></div></h4>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <h4><b>Description: </b><div id="des_rep"></div></h4>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <h4><b>Date: </b><div id="date_rep"></div></h4>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-md-12">
            <h2>Input Data</h2>
        </div>
    </div>


    <div class="col-md-12"  id="mp">
        <div class="row">
            <div class="col-md-12">

                <h3><b>Statistical Database</b></h3>
                  <table class="table-condensed table-bordered">
                    <thead>
                      <tr>
                        <th>Statistical Database <div style="display:none;" id="field">(Field)</div></th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td><div id="s_d"></div></td>
                      </tr>
                    </tbody>
                  </table>
                </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-md-12">

                    <h3><b>Rock Properties</b></h3>
                  <table class="table-condensed table-bordered">
                    <thead>
                      <tr>
                        <th>Top [ft]</th>
                        <th>TVD [ft]</th>
                        <th>NetPay [ft]</th>
                        <th>Porosity [%]</th>
                        <th>Abosulte Permeability [mD]</th>
                        <th>Effective Permeability of Continuous Phase [mD]</th>          
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td><div id="top"></div></td>
                        <td><div id="tvd"></div></td>
                        <td><div id="netpay"></div></td>
                        <td><div id="porosity"></div></td>
                        <td><div id="abs_perm"></div></td>
                        <td><div id="eff_perm"></div></td>
                      </tr>
                    </tbody>
                  </table>
                </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-md-12">

                    <h3><b>Production Data</b></h3>
                  <table class="table-condensed table-bordered">
                    <thead>
                      <tr>
                        <th>Well Radius [ft]</th>
                        <th>Drainage Radius [ft]</th>
                        <th>Reservoir Pressure [psia]</th>
                        <th>BHP [psia]</th>
                        <th>Oil Rate [STB/D]</th>
                        <th>Gas Rate [MMSCF/D]</th> 
                        <th>Water Rate [STB/D]</th>         
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td><div id="w_r"></div></td>
                        <td><div id="d_r"></div></td>
                        <td><div id="r_p"></div></td>
                        <td><div id="bhp"></div></td>
                        <td><div id="oil_r"></div></td>
                        <td><div id="gas_r"></div></td>
                        <td><div id="wat_r"></div></td>
                      </tr>
                    </tbody>
                  </table>
                  <!--
                  <h5>Production Test (PLT)</h5>
                  <table class="table-condensed table-bordered">
                      <thead>
                          <tr>
                              <th>Producing Interval</th>
                              <th>Date</th>
                              <th>Qo</th>
                              <th>Qg</th>
                              <th>Qw</th>
                          </tr>
                      </thead>
                      <tbody>
                          <tr>
                              <td><div id="pr_i"></div></td>
                              <td><div id="date"></div></td>
                              <td><div id="qo"></div></td>
                              <td><div id="qg"></div></td>
                              <td><div id="qw"></div></td>
                          </tr>
                      </tbody>
                  </table>-->
                </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-md-12">

                    <h3><b>Fluid Information</b></h3>
                    <table class="table-condensed table-bordered">
                        <thead>
                            <tr>
                                <th>Fluid Type</th>
                                <th>Saturation Pressure [psia]</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><div id="f_t"></div></td>
                                <td><div id="s_p"></div></td>
                            </tr>
                        </tbody>
                    </table>
                    <h5>Oil Properties</h5>
                    <table class="table-condensed table-bordered">
                        <thead>
                            <tr>
                                <th>Viscosity [cP]</th>
                                <th>FVF [RB/STB]</th>
                                <th>RS [SCF/STB]</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><div id="o_visco"></div></td>
                                <td><div id="o_fvf"></div></td>
                                <td><div id="o_rs"></div></td>
                            </tr>
                        </tbody>
                    </table>
                    <h5>Gas Properties</h5>
                    <table class="table-condensed table-bordered">
                        <thead>
                            <tr>
                                <th>Viscosity [cP]</th>
                                <th>FVF [RCF/SCF]</th>
                                <th>RV [STB/SCF]</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><div id="g_visco"></div></td>
                                <td><div id="g_fvf"></div></td>
                                <td><div id="g_rv"></div></td>
                            </tr>
                        </tbody>
                    </table>
                    <h5>Water Properties</h5>
                    <table class="table-condensed table-bordered">
                        <thead>
                            <tr>
                                <th>Viscosity [cP]</th>
                                <th>FVF [RB/STB]</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><div id="w_visco"></div></td>
                                <td><div id="w_fvf"></div></td>
                            </tr>
                        </tbody>
                    </table>

            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-md-12">

                    <h3><b>Multiparametric Analysis</b></h3>
                    <h5>Critical Pressure By Damage Parameters</h5>
                        <table class="table-condensed table-bordered">
                            <thead>
                                <tr>
                                    <th>Mineral Scales [psia]</th>
                                    <th>Organic Scales [psia]</th>
                                    <th>Relative Permeability Effects [psia]</th>
                                    <th>Geomechanical Damage [psia]</th>
                                    <th>Critical Radius Derived From Maximum Critical Velocity, Vc [ft]</th>
                                    <th>Total Volumen Of Water Based Fluids Pumped Into The Well [bbl]</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><div id="ms_pc"></div></td>
                                    <td><div id="os_pc"></div></td>
                                    <td><div id="rpe_pc"></div></td>
                                    <td><div id="gd_pc"></div></td>
                                    <td><div id="crd"></div></td>
                                    <td><div id="tvow"></div></td>
                                </tr>
                            </tbody>
                        </table>
                        <h5>K Damaged And K Base Ratio (Kd/Kb) By Damage Parameter</h5>
                        <table class="table-condensed table-bordered">
                            <thead>
                                <th>Mineral Scales</th>
                                <th>Fines Blockage</th>
                                <th>Organic Scales</th>
                                <th>Relative Permeability</th>
                                <th>Induced Damage</th>
                                <th>Geomechanical Damage</th>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><div id="ms"></div></td>
                                    <td><div id="fb"></div></td>
                                    <td><div id="os"></div></td>
                                    <td><div id="rp"></div></td>
                                    <td><div id="idd"></div></td>
                                    <td><div id="gd"></div></td>
                                </tr>
                            </tbody>
                        </table>

            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-md-12">

                    <h3><b>Mineral Scales Data</b></h3>
                    <table class="table-condensed table-bordered">
                        <thead>
                            <tr>
                                <th>Scale Index Of CaCO3 [-]</th>
                                <th>Scale index of BaSO4 [-]</th>
                                <th>Scale Index Of Iron Scales [-]</th>
                                <th>Calcium Concentration On Backflow Samples [ppm]</th>
                                <th>Barium Concentration On Backflow Samples [ppm]</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><div id="ms1"></div></td>
                                <td><div id="ms2"></div></td>
                                <td><div id="ms3"></div></td>
                                <td><div id="ms4"></div></td>
                                <td><div id="ms5"></div></td>
                            </tr>
                        </tbody>
                    </table>

            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-md-12">

                    <h3><b>Fine Blockage Data</b></h3>
                    <table class="table-condensed table-bordered">
                        <thead>
                            <tr>
                                <th>Aluminum Concentration On Produced Water [ppm]</th>
                                <th>Silicon Concentration On Produced Water [ppm]</th>
                                <th>Critical Radius Factor Rc [ft]</th>
                                <th>Mineralogic Factor [-]</th>
                                <th>Crushed Proppant Factor [lbs]</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><div id="fp1"></div></td>
                                <td><div id="fp2"></div></td>
                                <td><div id="fp3"></div></td>
                                <td><div id="fp4"></div></td>
                                <td><div id="fp5"></div></td>
                            </tr>
                        </tbody>
                    </table>

            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-md-12">

                    <h3><b>Organic Scales Data</b></h3>
                    <table class="table-condensed table-bordered">
                        <thead>
                            <tr>
                                <th>CII Factor: Colloidal Instability Index [-]</th>
                                <th>Compositional Factor: Cumulative Gas Produced [mMMSCF]</th>
                                <th>Pressure Factor: Number Of Days Below Saturation Pressure [Days]</th>
                                <th>High Impact Factor: De Boer Criteria [-]</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><div id="os1"></div></td>
                                <td><div id="os2"></div></td>
                                <td><div id="os3"></div></td>
                                <td><div id="os4"></div></td>
                            </tr>
                        </tbody>
                    </table>

            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-md-12">

                    <h3><b>Relative Permeability Data</b></h3>
                    <table class="table-condensed table-bordered">
                        <thead>
                            <tr>
                                <th>Number Of Days Below Saturation Pressure [days]</th>
                                <th>Delta Pressure From Saturation Pressure [psi]</th>
                                <th>Water Intrusion: Cumulative Water Produced [MMbbl]</th>
                                <th> High Impact Factor:Pore Size Diameter Approximation By Katz And Thompson Correlation [-]</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><div id="rp1"></div></td>
                                <td><div id="rp2"></div></td>
                                <td><div id="rp3"></div></td>
                                <td><div id="rp4"></div></td>
                            </tr>
                        </tbody>
                    </table>

            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-md-12">

                    <h3><b>Induced Damage Data</b></h3>
                    <table class="table-condensed table-bordered">
                        <thead>
                            <tr>
                                <th>Invasion Radius [ft]</th>
                                <th>Polymer Damage Factor [-]</th>
                                <th>Induced Skin [-]</th>
                                <th>Mud Damage Factor: Mud Losses [bbl]</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><div id="id1"></div></td>
                                <td><div id="id2"></div></td>
                                <td><div id="id3"></div></td>
                                <td><div id="id4"></div></td>
                            </tr>
                        </tbody>
                    </table>

            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-md-12">

                    <h3><b>Geomechanical Damge Data</b></h3>
                    <table class="table-condensed table-bordered">
                        <thead>
                            <tr>
                                <th>Fraction Of NetPay Exhibiting Natural Fractures [-]</th>
                                <th>Drawdown [psi]</th>
                                <th>Ratio Of KH + Fracture / KH [-]</th>
                                <th>Geomechanical Damage Expressed As Fraction Of Base Permeability At BHFP [-]</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><div id="gd1"></div></td>
                                <td><div id="gd2"></div></td>
                                <td><div id="gd3"></div></td>
                                <td><div id="gd4"></div></td>
                            </tr>
                        </tbody>
                    </table>

            </div>
        </div>
        <hr>
        <!-- gráficos -->
        <div class="row"  align="center"><h3><div id="subt"></div></h3></div>
        <hr>
        <h2 align="center">Statistical Skin Characterization</h2>
        <div class="row">
          <div class="col-md-12">
            <div class="row" id="container"></div>
          </div>
        </div>
        <div class="row">
            <div class="col-md-12">
              <p> </p>            
              <p> </p>            
              <p> </p>            
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th>Damage Mechanism</th>
                    <th>Skin [%]</th>
                  </tr>
                </thead>
                <tbody id="statistical_body">
                </tbody>
              </table>
            </div>
        </div>
        <hr>
        <h2 align="center">Analytical Skin Characterization</h2>
        <div class="row">
          <div class="col-md-12">
            <div class="row" id="container_2"></div>
          </div>
        </div>
        <div class="row">
            <div class="col-md-12">
              <p> </p>            
              <p> </p>            
              <p> </p>            
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th>Damage Mechanism</th>
                    <th>Skin [%]</th>
                  </tr>
                </thead>
                <tbody id="analytical_body">
                </tbody>
              </table>
            </div>
        </div>
        <hr>
        <h2 align="center">Average Skin Characterization</h2>
        <div class="row">
          <div class="col-md-12">
            <div class="row" id="container_3"></div>
          </div>
        </div>
        <div class="row">
            <div class="col-md-12">
              <p> </p>            
              <p> </p>            
              <p> </p>            
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th>Damage Mechanism</th>
                    <th>Skin [%]</th>
                  </tr>
                </thead>
                <tbody id="total_body">

                </tbody>
              </table>
            </div>
        </div>         
    </div>       <!-- gráficos -->
    <div class="col-md-12"  id="ipr_xxx">
        <div class="oil_data">
            <div class="row">
                <div class="col-md-12">
                    <h3><b>Well Data</b></h3>
                    <table class="table-condensed table-bordered">
                        <thead>
                          <tr>
                            <th>Fluid</th>
                            <th>Well Radius [ft]</th>
                            <th>Reservoir Drainage Radius [ft]</th>
                            <th>Reservoir Pressure [psi]</th>         
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td><div id="fluid_oil"></div></td>
                            <td><div id="well_radius"></div></td>
                            <td><div id="reservoir_dr"></div></td>
                            <td><div id="current_rp"></div></td>
                          </tr>
                        </tbody>
                      </table>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-12">
                    <h3><b>Production data</b></h3>
                    <table class="table-condensed table-bordered">
                        <thead>
                          <tr>
                            <th>Oil Rate [bbl/day]</th>
                            <th>BHP[psi]</th>
                            <th>BSW[-]</th>        
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td><div id="oil_rateipr"></div></td>
                            <td><div id="bhp_ipr"></div></td>
                            <td><div id="bsw_ipr"></div></td>
                          </tr>
                        </tbody>
                      </table>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-12">
                    <h3><b>Rock Properties</b></h3>
                    <div class="row">
                        <div class="col-md-12">
                            <h5><b>Basic Petrophysics</b></h5>
                            <table class="table-condensed table-bordered">
                                <thead>
                                  <tr>
                                    <th>Initial Reservoir Pressure [psi]</th>
                                    <th>Absolute Permeability At Initial Reservoir Pressure [md]</th>
                                    <th>Net Pay [ft]</th>       
                                  </tr>
                                </thead>
                                <tbody>
                                  <tr>
                                    <td><div id="initial_rp"></div></td>
                                    <td><div id="absolute_p"></div></td>
                                    <td><div id="net_pay"></div></td>
                                  </tr>
                                </tbody>
                              </table>
                        </div>
                    </div><br>
                    <div class="row" id="use_perm" style="display:none;">
                        <div class="col-md-12">
                            <h5><li><b>Use Permeability Module</b></li></h5>
                            <table class="table-condensed table-bordered">
                                <thead>
                                  <tr>
                                    <th>Permeability Module [1/psi]</th>       
                                  </tr>
                                </thead>
                                <tbody>
                                  <tr>
                                    <td><div id="permeability_m"></div></td>
                                  </tr>
                                </tbody>
                              </table>
                        </div>
                    </div>
                    <div class="row" id="cal_perm" style="display:none;">
                        <div class="col-md-12">
                            <h5><li><b>Calculate Permeability Module</b></li></h5>
                            <table class="table-condensed table-bordered">
                                <thead>
                                  <tr>
                                    <th>Absolute Permeability [md]</th>       
                                    <th>Porosity [-]</th>  
                                    <th>Rock Type</th>  
                                  </tr>
                                </thead>
                                <tbody>
                                  <tr>
                                    <td><div id="absolute_p_p"></div></td>
                                    <td><div id="porosity_p"></div></td>
                                    <td><div id="rock_type"></div></td>
                                  </tr>
                                </tbody>
                              </table>
                        </div>
                    </div>
                </div>
            </div>
            <br><br>
            <div class="row">
                <div class="col-md-12">
                    <h5><b>Relative Permeability Data Selection</b></h5>
                    <div class="row" id="rel_tab" style="display:none;">
                        <div class="col-md-12">
                            <h5><li><b>Tabular</b></li></h5>
                            <br><h5><b>Water-Oil</b></h5>
                            <div id="water_oil"></div>
                            <h5><b>Gas-Oil</b></h5>
                            <div id= "gas_oil"></div>
                        </div>
                    </div>
                    <div class="row" id="cor_perm" style="display:none;">
                        <div class="col-md-12">
                            <h5><li><b>Corey's Model</b></li></h5>
                            <div class="row">
                                <div class="col-md-12">
                                    <h5><b>Gas/Oil End-point Parameters</b></h5>
                                    <table class="table-condensed table-bordered">
                                        <thead>
                                          <tr>
                                            <th>Kro (Sgc) [-]</th>       
                                            <th>Sgc [-]</th>  
                                            <th>Krg (Sorg) [-]</th> 
                                            <th>Sorg [-]</th>       
                                            <th>Corey Exponent Oil/Gas [-]</th>  
                                            <th>Corey Exponent Gas [-]</th>   
                                          </tr>
                                        </thead>
                                        <tbody>
                                          <tr>
                                            <td><div id="kro"></div></td>
                                            <td><div id="sgc"></div></td>
                                            <td><div id="krg"></div></td>
                                            <td><div id="sorg"></div></td>
                                            <td><div id="corey_og"></div></td>
                                            <td><div id="corey_g"></div></td>
                                          </tr>
                                        </tbody>
                                      </table>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <h5><b>Oil/Water End-point Parameters</b></h5>
                                    <table class="table-condensed table-bordered">
                                        <thead>
                                          <tr>
                                            <th>Kro (Swi) [-]</th>       
                                            <th>Swi [-]</th>  
                                            <th>Krw (Sor) [-]</th>  
                                            <th>Sor [-]</th>       
                                            <th>Corey Exponent Oil [-]</th>  
                                            <th>Corey Exponent Water [-]</th>  
                                          </tr>
                                        </thead>
                                        <tbody>
                                          <tr>
                                            <td><div id="kro_gw"></div></td>
                                            <td><div id="swi"></div></td>
                                            <td><div id="krw"></div></td>
                                            <td><div id="sor"></div></td>
                                            <td><div id="corey_oil"></div></td>
                                            <td><div id="corey_water"></div></td>
                                          </tr>
                                        </tbody>
                                      </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-12">
                    <h3><b>Fluid properties</b></h3>
                    <table class="table-condensed table-bordered">
                        <thead>
                          <tr>
                            <th>Saturation Pressure [psi]</th>      
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td><div id="satu-press"></div></td>
                          </tr>
                        </tbody>
                    </table>
                </div>
                    <div class="col-md-12">
                        </br></br><h5><b>PVT Data Selection</b></h5>
                        <div id="pvt_tab" style="display:none;">
                            <h5><li><b>Tabular</b></li></h5>
                            <div id="tabular_pvt"></div>
                        </div>
                            <div class="col-md-12">
                            <div id="cubic_ec" style="display:none;">
                            <h5><li><b>Cubic Equations</b></li></h5>
                            <div class="row">
                                <table class="table-condensed table-bordered">
                                    <thead>
                                      <tr>
                                        <th>Water Viscosity [cp]</th>  
                                      </tr>
                                    </thead>
                                    <tbody>
                                      <tr>
                                        <td><div id="wat_vis"></div></td>
                                      </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="row">
                                <h5><b>Oil Viscosity Coefficients</b></h5>
                                <table class="table-condensed table-bordered">
                                    <thead>
                                      <tr>
                                        <th>P3</th> 
                                        <th>P2</th>
                                        <th>P</th>
                                        <th></th>   
                                      </tr>
                                    </thead>
                                    <tbody>
                                      <tr>
                                        <td><div id="p3"></div></td>
                                        <td><div id="p2"></div></td>
                                        <td><div id="p"></div></td>
                                        <td><div id="p0"></div></td>
                                      </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="row">
                                <h5><b>Oil Volumetric Factor Coefficients</b></h5>
                                <table class="table-condensed table-bordered">
                                    <thead>
                                      <tr>
                                        <th>P3</th> 
                                        <th>P2</th>
                                        <th>P</th>
                                        <th></th>   
                                      </tr>
                                    </thead>
                                    <tbody>
                                      <tr>
                                        <td><div id="p3_o"></div></td>
                                        <td><div id="p2_o"></div></td>
                                        <td><div id="p_o"></div></td>
                                        <td><div id="p0_o"></div></td>
                                      </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        </div>
                    </div>
                    <div class="col-md-12" style="display:none;">
                        </br></br><h5><b>Shrinkage Curve</b></h5>
                        <h5><b>Liquid Phase Fraction vs. Pressure</b></h5>
                        <div id="shrinkage"></div>
                    </div>
            </div>
            <hr>
        </div>
        <div class="gas_data">
            <div class="row">
                <div class="col-md-12">
                    <h3><b>Well Data</b></h3>
                    <table class="table-condensed table-bordered">
                        <thead>
                          <tr>
                            <th>Fluid</th>
                            <th>Well Radius [ft]</th>
                            <th>Reservoir Drainage Radius [ft]</th>
                            <th>Reservoir Pressure [psi]</th>         
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td><div id="fluid_gas"></div></td>
                            <td><div id="well_radius_gas"></div></td>
                            <td><div id="reservoir_dr_gas"></div></td>
                            <td><div id="current_rp_gas"></div></td>
                          </tr>
                        </tbody>
                      </table>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-12">
                    <h3><b>Production Data</b></h3>
                    <table class="table-condensed table-bordered">
                        <thead>
                          <tr>
                            <th>Gas Rate [MMscf/day]</th>
                            <th>BHP [psi]</th>       
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td><div id="gas_rateipr"></div></td>
                            <td><div id="bhp_gas"></div></td>
                          </tr>
                        </tbody>
                      </table>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-12">
                    <h3><b>Rock Properties </b></h3>
                    <div class="row">
                        <div class="col-md-12">
                            <h5><b>Basic Petrophysics</b></h5>
                            <table class="table-condensed table-bordered">
                                <thead>
                                  <tr>
                                    <th>Initial Reservoir Pressure [psi]</th>
                                    <th>Absolute Permeability At Initial Reservoir Pressure [md]</th>
                                    <th>Net Pay [ft]</th>       
                                  </tr>
                                </thead>
                                <tbody>
                                  <tr>
                                    <td><div id="initial_rp_gas"></div></td>
                                    <td><div id="absolute_p_gas"></div></td>
                                    <td><div id="net_pay_gas"></div></td>
                                  </tr>
                                </tbody>
                              </table>
                        </div>
                    </div><br>
                    <div class="row" id="use_perm_gas" style="display:none;">
                        <div class="col-md-12">
                            <h5><li><b>Use Permeability Module</b></li></h5>
                            <table class="table-condensed table-bordered">
                                <thead>
                                  <tr>
                                    <th>Permeability Module [1/psi]</th>       
                                  </tr>
                                </thead>
                                <tbody>
                                  <tr>
                                    <td><div id="permeability_m_gas"></div></td>
                                  </tr>
                                </tbody>
                              </table>
                        </div>
                    </div>
                    <div class="row" id="cal_perm_gas" style="display:none;">
                        <div class="col-md-12">
                            <h5><li><b>Calculate Permeability Module</b></li></h5>
                            <table class="table-condensed table-bordered">
                                <thead>
                                  <tr>
                                    <th>Absolute Permeability [md]</th>       
                                    <th>Porosity [-]</th>  
                                    <th>Rock Type</th>  
                                  </tr>
                                </thead>
                                <tbody>
                                  <tr>
                                    <td><div id="absolute_p_p_gas"></div></td>
                                    <td><div id="porosity_p_gas"></div></td>
                                    <td><div id="rock_type_gas"></div></td>
                                  </tr>
                                </tbody>
                              </table>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-12">
                    <h3><b>Fluid Properties </b></h3>
                </div>
                    <div class="col-md-12">
                        </br></br><h5><b>PVT Data Selection</b></h5>
                        <table class="table-condensed table-bordered">
                            <thead>
                              <tr>
                                <th>Temperature [F]</th>      
                              </tr>
                            </thead>
                            <tbody>
                              <tr>
                                <td><div id="temperature_gas"></div></td>
                              </tr>
                            </tbody>
                        </table>
                        <div id="pvt_tab_gas" style="display:none;">
                            <h5><li><b>Tabular</b></li></h5>
                            <div id="tabular_pvt_gas"></div>
                        </div>
                            <div class="col-md-12">
                            <div id="cubic_ec_gas" style="display:none;">
                            <h5><li><b>Cubic Equations</b></li></h5>
                            <div class="row">
                                <h5><b>Gas Viscosity Coefficients</b></h5>
                                <table class="table-condensed table-bordered">
                                    <thead>
                                      <tr>
                                        <th>P3</th> 
                                        <th>P2</th>
                                        <th>P</th>
                                        <th></th>   
                                      </tr>
                                    </thead>
                                    <tbody>
                                      <tr>
                                        <td><div id="p3_gas"></div></td>
                                        <td><div id="p2_gas"></div></td>
                                        <td><div id="p_gas"></div></td>
                                        <td><div id="p0_gas"></div></td>
                                      </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="row">
                                <h5><b>Gas Compressibility Coefficients</b></h5>
                                <table class="table-condensed table-bordered">
                                    <thead>
                                      <tr>
                                        <th>P3</th> 
                                        <th>P2</th>
                                        <th>P</th>
                                        <th></th>   
                                      </tr>
                                    </thead>
                                    <tbody>
                                      <tr>
                                        <td><div id="p3_g_gas"></div></td>
                                        <td><div id="p2_g_gas"></div></td>
                                        <td><div id="p_g_gas"></div></td>
                                        <td><div id="p0_g_gas"></div></td>
                                      </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        </div>
                    </div>
            </div>
        </div>
        <div class="condensate_gas_data">
            <div class="row">
                <div class="col-md-12">
                    <h3><b>Well Data</b></h3>
                    <table class="table-condensed table-bordered">
                        <thead>
                          <tr>
                            <th>Fluid</th>
                            <th>Well Radius [ft]</th>
                            <th>Reservoir Drainage Radius [ft]</th>
                            <th>Reservoir Pressure [psi]</th>         
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td><div id="fluid_cg"></div></td>
                            <td><div id="well_radius_cg"></div></td>
                            <td><div id="reservoir_dr_cg"></div></td>
                            <td><div id="current_rp_cg"></div></td>
                          </tr>
                        </tbody>
                      </table>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-12">
                    <h3><b>Production Data</b></h3>
                    <table class="table-condensed table-bordered">
                        <thead>
                          <tr>
                            <th>Gas Rate [MMscf/day]</th>
                            <th>BHP [psi]</th>       
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td><div id="gas_rate_cg"></div></td>
                            <td><div id="bhp_cg"></div></td>
                          </tr>
                        </tbody>
                      </table>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-12">
                    <h3><b>Rock Properties </b></h3>
                    <div class="row">
                        <div class="col-md-12">
                            <h5><b>Basic Petrophysics</b></h5>
                            <table class="table-condensed table-bordered">
                                <thead>
                                  <tr>
                                    <th>Initial Reservoir Pressure [psi]</th>
                                    <th>Absolute Permeability At Initial Reservoir Pressure [md]</th>
                                    <th>Net Pay [ft]</th>       
                                  </tr>
                                </thead>
                                <tbody>
                                  <tr>
                                    <td><div id="initial_rp_cg"></div></td>
                                    <td><div id="absolute_p_cg"></div></td>
                                    <td><div id="net_pay_cg"></div></td>
                                  </tr>
                                </tbody>
                              </table>
                        </div>
                    </div><br>
                    <div class="row" id="use_perm_cg" style="display:none;">
                        <div class="col-md-12">
                            <h5><li><b>Use Permeability Module</b></li></h5>
                            <table class="table-condensed table-bordered">
                                <thead>
                                  <tr>
                                    <th>Permeability Module [psi]</th>       
                                  </tr>
                                </thead>
                                <tbody>
                                  <tr>
                                    <td><div id="permeability_m_cg"></div></td>
                                  </tr>
                                </tbody>
                              </table>
                        </div>
                    </div>
                    <div class="row" id="cal_perm_cg" style="display:none;">
                        <div class="col-md-12">
                            <h5><li><b>Calculate Permeability Module</b></li></h5>
                            <table class="table-condensed table-bordered">
                                <thead>
                                  <tr>
                                    <th>Absolute Permeability [md]</th>       
                                    <th>Porosity [-]</th>  
                                    <th>Rock Type</th>  
                                  </tr>
                                </thead>
                                <tbody>
                                  <tr>
                                    <td><div id="absolute_p_p_cg"></div></td>
                                    <td><div id="porosity_p_cg"></div></td>
                                    <td><div id="rock_type_cg"></div></td>
                                  </tr>
                                </tbody>
                              </table>
                        </div>
                    </div>
                    <div class="row" id="rel_tab_cg">
                        <div class="col-md-12">
                            <h5><b>Gas-Oil Data</b></h5>
                            <div id= "gas_oil_cg"></div>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-12">
                    <h3><b>Fluid Properties </b></h3>
                </div>
                <div class="col-md-12">
                    <table class="table-condensed table-bordered">
                        <thead>
                          <tr>
                            <th>Saturation Pressure [psi]</th>       
                            <th>GOR [psi]</th>  
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td><div id="saturation_pressure_c_g"></div></td>
                            <td><div id="gor_c_g"></div></td>
                          </tr>
                        </tbody>
                    </table>
                </div>
                    <div class="col-md-12">
                        </br></br><h5><b>PVT Data</b></h5>
                        <div id="pvt_tab_cg">
                            <div id="tabular_pvt_cg"></div>
                        </div>
                    </div>
            </div>
            <div class="row">
                    <div class="col-md-12">
                        </br></br><h5><b>Drop Out Curve</b></h5>
                        <div id="pvt_tab_cg">
                            <h5><li><b>Tabular</b></li></h5>
                            <div id="dropout_cg"></div>
                        </div>
                    </div>
            </div>
        </div>
        <hr>
        <br><div id="grafica_ipr"></div>

        <div id="desagregacion" style="display:none;">
            <hr>
            <div class="row">
                <div class="col-md-12">
                    <h2 align="center">Disaggregation</h2>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <h3><b>Well Data</b></h3>
                    <table class="table-condensed table-bordered">
                        <thead>
                          <tr>
                            <th>Well Radius [ft]</th>
                            <th>Reservoir Drainage Radius [ft]</th>
                            <th>Reservoir Pressure [psi]</th>
                            <th>Measured Well Depth [ft]</th>     
                            <th>Thickness Perforating [ft]</th>
                            <th>Perforation Penetration Depth [ft]</th>
                            <th>Perforating Phase Angle</th>
                            <th>Perforating Radius [in]</th>
                            <th>True Vertical Depth [ft]</th>
                            <th>Production Formation Thickness [ft]</th>    
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td><div id="well_radiu"></div></td>
                            <td><div id="reserv_draina"></div></td>
                            <td><div id="reserv_pre"></div></td>
                            <td><div id="measur_well"></div></td>
                            <td><div id="thickness_p"></div></td>
                            <td><div id="perf_penet"></div></td>
                            <td><div id="perf_pha"></div></td>
                            <td><div id="perf_rad"></div></td>
                            <td><div id="true_vd"></div></td>
                            <td><div id="prod_form"></div></td>
                          </tr>
                        </tbody>
                      </table><br>
                      <div class="row">
                        <div class="col-md-12">
                            <h5><b>Drainage Area Shape</b></h5>
                            <div id="img_1" name="img_1"></div>
                        </div>
                      </div>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-12">
                    <h3><b>Production Data</b></h3>
                    <table class="table-condensed table-bordered">
                        <thead>
                          <tr>
                            <th>Oil Rate [bbls/day]</th>
                            <th>Bottomhole Flowing Pressure [psi]</th>
                            <th>Gas Rate [MMscf/day]</th>   
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td><div id="oil_ra"></div></td>
                            <td><div id="bottomhole"></div></td>
                            <td><div id="gas_ra"></div></td>
                          </tr>
                        </tbody>
                      </table>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-12">
                    <h3><b>Rock Properties</b></h3>
                    <table class="table-condensed table-bordered">
                        <thead>
                          <tr>
                            <th>Permeability [md]</th>
                            <th>Rock Type</th>
                            <th>Horizontal - Vertical Permeability Ratio [-]</th>   
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td><div id="permy"></div></td>
                            <td><div id="rock_ty"></div></td>
                            <td><div id="hor_ver"></div></td>
                          </tr>
                        </tbody>
                      </table>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-12">
                    <h3><b>Fluid Properties</b></h3>
                    <table class="table-condensed table-bordered">
                        <thead>
                          <tr>
                            <th>Oil Viscosity [cp]</th>
                            <th>Gas Viscosity [cp]</th>
                            <th>Specific Gas Gravity [-]</th>   
                            <th>Volumetric Oil Factor [-]</th> 
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td><div id="oil_vy"></div></td>
                            <td><div id="gas_vy"></div></td>
                            <td><div id="specif_gg"></div></td>
                            <td><div id="volum_of"></div></td>
                          </tr>
                        </tbody>
                      </table>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-12">
                    <h3><b>Stress Gradients</b></h3>
                    <table class="table-condensed table-bordered">
                        <thead>
                          <tr>
                            <th>Minimum Horizontal Stress Gradient [psi/ft]</th>
                            <th>Maximum Horizontal Stress Gradient [psi/ft]</th>
                            <th>Vertical Stress Gradient [psi/ft]</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td><div id="min_hor"></div></td>
                            <td><div id="max_hor"></div></td>
                            <td><div id="ver_stess"></div></td>
                          </tr>
                        </tbody>
                      </table>
                </div>
            </div>
            
            <div class="row"><hr>
                <div class="col-md-12">
                    <h3><b>Hidraulic Units Data</b></h3>
                    <div id="hidr_units"></div>
                </div>
            </div>
            <hr>
           
            <div class="row" id="dama_inp">
                <div class="col-md-12">
                    <h3><b>Damage</b></h3>
                    <table class="table-condensed table-bordered">
                        <thead>
                          <tr>
                            <th>Skin [-]</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td><div id="dam_in_skin"></div></td>
                          </tr>
                        </tbody>
                      </table>
                </div><hr>
            </div>
            <hr>
            <div class="row" id="resultados_desagregacion">
                
            </div>
            <hr>
            <br><div id="spider_desagregacion"></div>
            <hr>
            <br><div id="grafica_desagregacion"></div>
        </div>
    </div>

    <div class="col-md-12"  id="drilling">
        <div class="row">
            <div class="col-md-12">
                <h3><b>General Data</b></h3>
                <div id="general_data_drilling"></div>
            </div>
        </div>
        <hr>

        <br>
        <div class="row">
            <div class="col-md-12">
                <h3><b>Input Data</b></h3>
                <h4><b><div id="input_data_method"></div></b></h4>
                <div id="input_data_drilling"></div>
                <br>
                <div id="profile_g"></div>
            </div>
        </div>
        <hr>

        <br>
        <div class="row">
            <div class="col-md-12">
                <h3><b>Drilling Data</b></h3>
                <table class="table-condensed table-bordered">
                    <thead>
                      <tr>
                        <th>Total Exposure Time [d]</th>
                        <th>Pump Rate [gpm]</th>
                        <th>Max Mud Density [lb/gal]</th>
                        <th>Correction Factor [lb/gal]</th>
                        <th>Min Mud Density [-]</th>
                        <th>ROP [ft/ho]</th>
                        <th>ECD (Equivalent Circulating Density) [gpm]</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td><div id="total_exposure_time"></div></td>
                        <td><div id="pump_rate_drilling"></div></td>
                        <td><div id="max_mud_density_drilling"></div></td>
                        <td><div id="correction_factor_drilling"></div></td>
                        <td><div id="min_mud_density_drilling"></div></td>
                        <td><div id="rop_drilling"></div></td>
                        <td><div id="ecd_drilling"></div></td>
                      </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <hr>

        <br>
        <div class="row">
            <div class="col-md-12">
                <h3><b>Cementing Data</b></h3>
                <table class="table-condensed table-bordered">
                    <thead>
                      <tr>
                        <th>Total Exposure Time [d]</th>
                        <th>Pump Rate [gpm]</th>
                        <th>Cement Slurry Density [lb/gal]</th>
                        <th>Correction Factor [-]</th>
                        <th>ECD (Equivalent Circulating Density) [gpm]</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td><div id="total_exposure_time_cementing"></div></td>
                        <td><div id="pump_rate_drilling_cementing"></div></td>
                        <td><div id="cement_slurry_density_drilling_cementing"></div></td>
                        <td><div id="correction_factor_drilling_cementing"></div></td>
                        <td><div id="ecd_drilling_cementing"></div></td>
                      </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <hr>

        <br>
        <div class="row">
            <div class="col-md-12">
                <h2><b>Results</b></h2>
                <br>
                <div id="results_drilling"></div>
            </div>
        </div>
        <hr>
                <div class="row">
            <div class="col-md-12">
                  <h3>Drilling Phase</h3>
                  <table class="table table-bordered">
                    <thead>
                      <tr>
                        <th>Maximum Calculated Skin [-]</th>
                        <th>Average Calculated Skin [-]</th>
                        <th>Total Invasion Volume (bbl)</th>
                        <th>Maximum Invasion Radius (ft)</th>
                        <th>Average Invasion Radius (ft)</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td><div id = "d_maximum_total_skin_r"></div></td>
                        <td><div id = "d_average_total_skin_r"></div></td>
                        <td><div id = "total_invasion_radius_drilling_r"></div></td>
                        <td><div id = "maximum_invasion_radius_drilling_r"></div></td>
                        <td><div id = "average_invasion_radius_drilling_r"></div></td>
                      </tr>
                    </tbody>
                  </table>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-12">
                <h3>Cementing Phase</h3>
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th>Maximum Calculated Skin [-]</th>
                      <th>Average Calculated Skin [-]</th>
                      <th>Total Invasion Volume (bbl)</th>
                      <th>Maximum Invasion Radius (ft)</th>
                      <th>Average Invasion Radius (ft)</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                    <td><div id = "c_maximum_total_skin_r"></div></td>
                    <td><div id = "c_average_total_skin_r"></div></td>
                    <td><div id = "total_invasion_radius_cementation_r"></div></td>
                    <td><div id = "maximum_invasion_radius_cementation_r"></div></td>
                    <td><div id = "average_invasion_radius_cementation_r"></div></td>
                    </tr>
                  </tbody>
                </table>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-12">
                <h3>Total Skin</h3>
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th>Calculated Skin - Maximum Total [-]</th>
                      <th>Calculated Skin - Average Total [-]</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                    <td><div id = "maximum_total_skin_r"></div></td>
                    <td><div id = "average_total_skin_r"></div></td>
                    </tr>
                  </tbody>
                </table>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <h3>Total Filtration Volume</h3>
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th>Filtration Volume - Maximum Total [bbl]</th>
                      <th>Filtration Volume - Average Total [bbl]</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                    <td><div id = "maximum_total_filtration_volume_r"></div></td>
                    <td><div id = "average_total_filtration_volume_r"></div></td>
                    </tr>
                  </tbody>
                </table>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <h3>Total Invasion Radius</h3>
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th>Total Invasion Radius - Maximum Total [ft]</th>
                      <th>Total Invasion Radius - Average Total [ft]</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                    <td><div id = "maximum_total_invasion_radius_r"></div></td>
                    <td><div id = "average_total_invasion_radius_r"></div></td>
                    </tr>
                  </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
<div>
    <p class="pull-right">            
        <button id="download" onclick="download();" class="btn btn-primary">Print Report</button>
    </p>
</div>


@endsection


@section('Scripts')
@include('js/scenario_report')
@endsection