@extends('layouts.ProjectGeneral2')

@section('title', 'Scenario Report')

@section('content')
<div class="col-md-12">
    <div class="row">
        <div class="col-md-12">
            <b><h1 align="center"><div id="tit_rep"></div></h1></b>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <h2><div id="sub_rep"></div></h2>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <h2>Input Data</h2>
        </div>
    </div>


    <div class="col-md-12" style="display: ;" id="mp">

        <div class="row">
            <div class="col-md-12">

                    <b><h3>Statistical Database</h3></b>
                  <table class="table-condensed">
                    <thead>
                      <tr>
                        <th>Statistical Database</th>
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

        <div class="row">
            <div class="col-md-12">

                    <b><h3>Petrophysics</h3></b>
                  <table class="table-condensed">
                    <thead>
                      <tr>
                        <th>Top</th>
                        <th>TVD</th>
                        <th>Net Pay</th>
                        <th>Porosity</th>
                        <th>Abosulte Permeability</th>
                        <th>Effective Permeability Of Continuous Phase</th>          
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

        <div class="row">
            <div class="col-md-12">

                    <b><h3>Production Data</h3></b>
                  <table class="table-condensed">
                    <thead>
                      <tr>
                        <th>Well Radius</th>
                        <th>Drainage Radius</th>
                        <th>Reservoir Pressure</th>
                        <th>BHP</th>
                        <th>Oil Rate</th>
                        <th>Gas Rate</th> 
                        <th>Water Rate</th>         
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
                  <h5>Production Test (PLT)</h5>
                  <table class="table-condensed">
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
                  </table>
                </div>
        </div>

        <div class="row">
            <div class="col-md-12">

                    <b><h3>Fluid Information At Average Reservoir Pressure</h3></b>
                    <table class="table-condensed">
                        <thead>
                            <tr>
                                <th>Fluid Type</th>
                                <th>Saturation Pressure</th>
                            </tr>
                        </thead>
                    </table>
                    <h5>Oil Properties</h5>
                    <table class="table-condensed">
                        <thead>
                            <tr>
                                <th>Viscosity</th>
                                <th>FVF</th>
                                <th>RS</th>
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
                    <table class="table-condensed">
                        <thead>
                            <tr>
                                <th>Viscosity</th>
                                <th>FVF</th>
                                <th>RS</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><div id="g_visco"></div></td>
                                <td><div id="g_fvf"></div></td>
                                <td><div id="g_rs"></div></td>
                            </tr>
                        </tbody>
                    </table>
                    <h5>Water Properties</h5>
                    <table class="table-condensed">
                        <thead>
                            <tr>
                                <th>Viscosity</th>
                                <th>FVF</th>
                                <th>RS</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><div id="w_visco"></div></td>
                                <td><div id="w_fvf"></div></td>
                                <td><div id="w_rs"></div></td>
                            </tr>
                        </tbody>
                    </table>

            </div>
        </div>

        <div class="row">
            <div class="col-md-12">

                    <b><h3>Multiparametric Analysis</h3></b>
                    <h5>Critical Pressure By Damage Parameters</h5>
                        <table class="table-condensed">
                            <thead>
                                <tr>
                                    <th>Mineral Scales</th>
                                    <th>Organic Scales</th>
                                    <th>Relative Permeability Effects</th>
                                    <th>Geomechanical Damage</th>
                                    <th>Critical Radius Derived From Maximum Critical Velocity</th>
                                    <th>Total Volumen Of Water Based Fluids Pumped Into The Well</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><div id="ms"></div></td>
                                    <td><div id="os"></div></td>
                                    <td><div id="rpe"></div></td>
                                    <td><div id="gd"></div></td>
                                    <td><div id="crd"></div></td>
                                    <td><div id="tvow"></div></td>
                                </tr>
                            </tbody>
                        </table>
                        <h5>K Damaged And K Base Ratio (Kd/Kb) By Damage Parameters</h5>
                        <table class="table-condensed">
                            <thead>
                                <th>Mineral Scales</th>
                                <th>Fines Blocakge</th>
                                <th>Organic Scales</th>
                                <th>Relative Permeability</th>
                                <th>Induced Damage</th>
                                <th>Geomechanical Damage</th>
                            </thead>
                        </table>

            </div>
        </div>

        <div class="row">
            <div class="col-md-12">

                    <b><h3>Mineral Scales Data</h3></b>
                    <table class="table-condensed">
                        <thead>
                            <tr>
                                <th>Scale Index Of CaCO3</th>
                                <th>Scale index of BaSO4</th>
                                <th>Scale Index Of Iron Scales</th>
                                <th>Calcium Concentration On Blackflow Samples</th>
                                <th>Barium Concentration On Backflow Samples</th>
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

        <div class="row">
            <div class="col-md-12">

                    <b><h3>Fine Blockage Data</h3></b>
                    <table class="table-condensed">
                        <thead>
                            <tr>
                                <th>Aluminum Concentration On Produced Water</th>
                                <th>Silicon Concentration On Produced Water</th>
                                <th>Critical Radius Factor Rc</th>
                                <th>Mineralogical Factor</th>
                                <th>Crushed Proppant Factor</th>
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

        <div class="row">
            <div class="col-md-12">

                    <b><h3>Organic Scales Data</h3></b>
                    <table class="table-condensed">
                        <thead>
                            <tr>
                                <th>Colloidal Instability Index</th>
                                <th>Compositional Factor</th>
                                <th>Pressure Factor</th>
                                <th>High Impact Factor</th>
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

        <div class="row">
            <div class="col-md-12">

                    <b><h3>Relative Permeability Data</h3></b>
                    <table class="table-condensed">
                        <thead>
                            <tr>
                                <th>Number Of Days Below Saturation Pressure</th>
                                <th>Difference between current reservoir pressure and saturation pressure</th>
                                <th>Water Intrusion</th>
                                <th>High Impact Factor</th>
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

        <div class="row">
            <div class="col-md-12">

                    <b><h3>Induced Damge Data</h3></b>
                    <table class="table-condensed">
                        <thead>
                            <tr>
                                <th>Invasion Radius</th>
                                <th>Polymer Damage Factor</th>
                                <th>Induced Skin</th>
                                <th>Mud Damage Factor</th>
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

        <div class="row">
            <div class="col-md-12">

                    <b><h3>Geomechanical Damge Data</h3></b>
                    <table class="table-condensed">
                        <thead>
                            <tr>
                                <th>Natural Fractures Index</th>
                                <th>Drawdown</th>
                                <th>Ratio Of KH + Fracture / KH</th>
                                <th>Geomechanical Damage Expressed AS Fraction Of Base Permeability At BHFP</th>
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

        <div class="col-md-12"  align="center"><h1><div id="titulo">Skin Characterization Diagram</div></h1></div>
        <div class="col-md-12"  align="center"><h3><div id="subt"></div></h3></div>
                    <hr>
        <div  class="col-md-12" id="container"></div>
            <p class="pull-right">            
                <a href="{{ URL::route('ScenaryC.edit',\Request::get('id')) }}" class="btn btn-primary" role="button">Download Report</a>
            </p>

    </div>


    <div class="col-md-12"  style="display: ;" id="ipr">
        <div id="desagregacion"></div>
    </div>

</div>



<script type="text/javascript">

(function() {

    var scenary=<?php echo \Request::get('id')?>;
    var tipo;
    $.get("{{url('tipo_esc')}}",
    {esc_id:scenary},
    function(data)
    {
        $.each(data, function(index,value)
        {
            tipo = value.tipo;
        });

        if(tipo=="Multiparametric")
        {
            $("#tit_rep").text('Multiparametric Analysis Report');
            $("#sub_rep").text('Scenario: ')
            $("#mp").show();


            var datos = [];
            var ultimos = [];
            var ps = [];
            var psf = [];


            var datos2 = ["OIL",17728,0.25,1500,0.310,0.025,1.060,0.9,1830,17090,200,4.0,3.0,0.56,5900,5800,
            0.7,0.2,0.7,0.3,0.2,0.2,2447,2447,5800,2368,560,4.65,[[0.6,0.6,8.0],[0.7,0.7,10.0],[0.05,0.05,6.0],
            [1000,500,3000],[8.0,5.2,26]], [[1.24,0.05,0.62],[31,3.0,38.5],[1.0,0.5,10],[0.6,0.3,1.0],[0.0,0.0,14000]],
            [[4.0,2.0,6.5],[8.0,8.0,176],[1500,1500,3300],[-1000,-1180,200]],[[2400,1500,3300],[1060,520,1600],[0.2,0.2,3.0],
            [8.75496,67.1,6.4]],[[638,110,1500],[240,240,2100],[1000,1000,5000],[150,10,390]],[[0.5,0.1,0.8],[4070,1200,5900],
            [2.0,1.0,10],[0.5,0.95,0.1]]];
            
            var TypeOfWell; //Tipo de fluido - Multiparamétrico
            var VerticalDepht; // TDV - Pozos
            var WellRadius; // Radius - Pozos
            var ExternalRadius; // drainageRadius - Pozos

                //PVT Data
            var OILViscosity; //oilViscosity - fluidoxpozos
            var GASViscosity; //gasViscosity - fluidoxpozos
            var OILVolumetricFactor; //FVFO - fluiodoxpozos
            var GASVolumetricFactor; //FVFG - fluidoxpozos

                //PRODUCTION Data
            var BottomHolePressure; //bhp - pozos
            var TOPdepht; //TOP - formacionxpozos
            var NetPay; //NetPay - formacionxpozos
            var Porosity; // Porosidad - formacionxpozos
            var Permeability; //FPermeabilidad - formacionxpozos 
            var CondensatePerm; // Kc - formacionxpozos
            var ReservoirPressure; // Presion_reservorio - formacionxpozos
            var SaturationPressure; // Saturation_pressure - formacionxpozos

               //#Kd/Ki
            var MSP_KdKi; //MS - formacionxpozos
            var FBP_KdKi; //FB - formacionxpozos
            var OSP_KdKi; //OS - formacionxpozos
            var KrP_KdKi; //Kr - formacionxpozos
            var IDP_KdKi; //IDD - formacionxpozos
            var GDP_KdKi; //GD - formacionxpozos

                //Presiones Criticas
            var Pc_MSP; //PcMSP - formacionxpozos
            var Pc_OSP; //PcOSP - formacionxpozos
            var Pc_KrP; //PcKrp - formacionxpozos
            var Pc_GDP; //PcGDP - formacionxpozos 

            //class intervalo productor

            //#PRODUCTION Data
            var OILRate; //oilRate - pozos - Qo 
            var GASRate; //gasRate - pozos - Qg

            //#DAMAGE Data
            var MSP = []; //Todos los msp (medición - comparados por fecha - último -), p10 y p90 de cada uno
            var FBP = []; //Todos los fbp (medición - comparados por fecha - último -), p10 y p90 de cada uno 
            var OSP = []; //Todos los osp (medición - comparados por fecha - último -), p10 y p90 de cada uno
            var KrP = []; //Todos los krp (medición - comparados por fecha - último -), p10 y p90 de cada uno 
            var IDP = []; //Todos los idp (medición - comparados por fecha - último -), p10 y p90 de cada uno 
            var GDP = []; //Todos los gdp (medición - comparados por fecha - último -), p10 y p90 de cada uno

            var CR_mcv;
            //Total volumen of water based fluids pumped into the ell(bbl)
            var TV_wbf;

            var data;

            var id;


            $.get("{{url('multiparametrico2')}}",
            {scenary : scenary},
            function(data){

                $.each(data, function(index, value){
                id=value.id;
                
              });
                

            $.get("{{url('datosM')}}",
                {mpid : id}, //ID del múltiparamétrico de estudio
                function(data){


                    var sp=[];

                    $.each(data.Pes, function(index, value)
                    {
                            ps.push(value);
                            
                    });

                    
                    

                    $.each(data.Data, function(index,value)
                    {

                        sp.push(value.ms1);
                        sp.push(value.ms2);
                        sp.push(value.ms3);
                        sp.push(value.ms4);
                        sp.push(value.ms5);
                        sp.push(value.fb1);
                        sp.push(value.fb2);
                        sp.push(value.fb3);
                        sp.push(value.fb4);
                        sp.push(value.fb5);
                        sp.push(value.os1);
                        sp.push(value.os2);
                        sp.push(value.os3);
                        sp.push(value.os4);
                        sp.push(value.rp1);
                        sp.push(value.rp2);
                        sp.push(value.rp3);
                        sp.push(value.rp4);
                        sp.push(value.id1);
                        sp.push(value.id2);
                        sp.push(value.id3);
                        sp.push(value.id4);
                        sp.push(value.gd1);
                        sp.push(value.gd2);
                        sp.push(value.gd3);
                        sp.push(value.gd4);


                        for (var i = 0; i < 5; i++) 
                    {
                       
                        var psfx = [];
                        psfx.push(sp[i]);
                        psfx.push(ps[i][0]);
                        psfx.push(ps[i][1]);
                        MSP.push(psfx);
                        
                    };

                    console.log(MSP);
                    for (var i = 5; i < 10; i++) 
                    {
                        
                        var psfx = [];
                        psfx.push(sp[i]);
                        psfx.push(ps[i][0]);
                        psfx.push(ps[i][1]);
                        FBP.push(psfx);
                                   
                    };
                    console.log(FBP);

                    for (var i = 10; i < 14; i++) 
                    {
                        
                        var psfx = [];
                        psfx.push(sp[i]);
                        psfx.push(ps[i][0]);
                        psfx.push(ps[i][1]);
                        OSP.push(psfx);
                                   
                    };
                    console.log(OSP);

                    for (var i = 14; i < 18; i++) 
                    {
                        
                        var psfx = [];
                        psfx.push(sp[i]);
                        psfx.push(ps[i][0]);
                        psfx.push(ps[i][1]);
                        KrP.push(psfx);
                                   
                    };
                    console.log(KrP);
                    for (var i = 18; i < 22; i++) 
                    {
                                        var psfx = [];
                        psfx.push(sp[i]);
                        psfx.push(ps[i][0]);
                        psfx.push(ps[i][1]);
                        IDP.push(psfx); 
                                  
                    };

                    console.log(IDP);
                    for (var i = 22; i < 26; i++) 
                    {
                        var aux = 1;
                        var psfx = [];
                        psfx.push(sp[i]);    
                        psfx.push(ps[i][0]);
                        psfx.push(ps[i][1]);
                        GDP.push(psfx);
                                   
                    };
                    console.log(GDP);


                        datos.push(value.fluidType);
                        datos.push(value.bottom); //TVD
                        datos.push(value.well_radius);
                        datos.push(value.drainage_radius);
                        datos.push(value.viscosityO);
                        datos.push(value.viscosityG);
                        datos.push(value.fvfO);
                        datos.push(value.fvfG);
                        datos.push(value.bottom_HPressure);
                        datos.push(value.top);
                        datos.push(value.netPay);
                        datos.push(value.porosity);
                        datos.push(value.absPermeability);
                        datos.push(value.kc);
                        datos.push(value.reservoir_pressure);
                        datos.push(value.saturation_pressure);
                        datos.push(value.ms);
                        datos.push(value.fb);
                        datos.push(value.os);
                        datos.push(value.kr);
                        datos.push(value.idd);
                        datos.push(value.gd);
                        datos.push(value.pc_MSP);//PCMSP
                        datos.push(value.pc_OSP);//PCOSP
                        datos.push(value.pc_KrP);//PCKRP
                        datos.push(value.pc_GDP);//PCGDP
                        datos.push(value.qo);
                        datos.push(value.qg);
                        //datos.push(datos2[28]);          
                        //datos.push(datos2[29]);  
                        //datos.push(datos2[30]);  
                        //datos.push(datos2[31]);  
                        //datos.push(datos2[32]);  
                        //datos.push(datos2[33]);  
                        datos.push(MSP);          
                        datos.push(FBP);  
                        datos.push(OSP);  
                        datos.push(KrP);  
                        datos.push(IDP);  
                        datos.push(GDP);  
                        datos.push(value.critical_radius);
                        datos.push(value.total_volumen);
                    });
            
            

            TypeOfWell = datos[0]; //Tipo de fluido - Multiparamétrico
            VerticalDepht = datos[1]; // TDV - Pozos
            WellRadius = datos[2]; // Radius - Pozos
            ExternalRadius = datos[3]; // drainageRadius - Pozos

                //PVT Data
            OILViscosity = datos[4]; //oilViscosity - fluidoxpozos
            GASViscosity = datos[5]; //gasViscosity - fluidoxpozos
            OILVolumetricFactor = datos[6]; //FVFO - fluiodoxpozos
            GASVolumetricFactor = datos[7]; //FVFG - fluidoxpozos

                //PRODUCTION Data
            BottomHolePressure = datos[8]; //bhp - pozos
            TOPdepht = datos[9]; //TOP - formacionxpozos
            NetPay = datos[10]; //NetPay - formacionxpozos
            Porosity = datos[11]; // Porosidad - formacionxpozos
            Permeability = datos[12]; //FPermeabilidad - formacionxpozos 
            CondensatePerm = datos[13]; // Kc - formacionxpozos
            ReservoirPressure = datos[14]; // Presion_reservorio - formacionxpozos
            SaturationPressure = datos[15]; // Saturation_pressure - formacionxpozos

               //#Kd/Ki
            MSP_KdKi = datos[16]; //MS - formacionxpozos
            FBP_KdKi = datos[17]; //FB - formacionxpozos
            OSP_KdKi = datos[18]; //OS - formacionxpozos
            KrP_KdKi = datos[19]; //Kr - formacionxpozos
            IDP_KdKi = datos[20]; //IDD - formacionxpozos
            GDP_KdKi = datos[21]; //GD - formacionxpozos

                //Presiones Criticas
            Pc_MSP = datos[22]; //PcMSP - formacionxpozos
            Pc_OSP = datos[23]; //PcOSP - formacionxpozos
            Pc_KrP = datos[24]; //PcKrp - formacionxpozos
            Pc_GDP = datos[25]; //PcGDP - formacionxpozos 

            //class intervalo productor

            //#PRODUCTION Data
            OILRate = datos[26]; //oilRate - pozos - Qo 
            GASRate = datos[27]; //gasRate - pozos - Qg

            //#DAMAGE Data
            MSP = datos[28]; //Todos los msp (medición - comparados por fecha - último -), p10 y p90 de cada uno
            FBP = datos[29]; //Todos los fbp (medición - comparados por fecha - último -), p10 y p90 de cada uno 
            OSP = datos[30]; //Todos los osp (medición - comparados por fecha - último -), p10 y p90 de cada uno
            KrP = datos[31]; //Todos los krp (medición - comparados por fecha - último -), p10 y p90 de cada uno 
            IDP = datos[32]; //Todos los idp (medición - comparados por fecha - último -), p10 y p90 de cada uno 
            GDP = datos[33]; //Todos los gdp (medición - comparados por fecha - último -), p10 y p90 de cada uno

            //Campos nuevos
            //Critical Radius derived from maximum critical velocity
            CR_mcv = datos[34];
            //Total volumen of water based fluids pumped into the ell(bbl)
            TV_wbf = datos[35];

            data = SkinCharacterization();

            spider(data,"MG_UZ",id);
                });

            });

        }
        else
        {
            alert(":'(");
            $("#tit_rep").text('IPR Analysis Report');
            $("#sub_rep").text('Scenario: ')
            $("#mp").hide();
        }

    });
})();

function spider(data,name,mp)
{
    alert("spider");

    var pozo;
    var fecha;
    var formacionsp;
    var camposp;

$.get("{{'spiderinfo'}}",
{mp: mp}, function(datas)
{
    $.each(datas,function(index,value)
    {
        pozo = value.nombre;
        fecha = value.fecha;
        formacionsp = value.Fnombre;
        camposp = value.Cnombre;
    });
    $('#titulo').html('Skin Characterization Diagram');
    $('#subt').html('<b>Formation: </b>'+formacionsp+' - <b>Field: </b>'+ camposp+' - <b>Well Name: </b>'+pozo +' - <b>Study Date:</b>'+fecha);
    $('#container').highcharts({

        chart: {
            polar: true,
            type: 'line'
        },

        title: {
            text: '',
            x: -80
        },
                subtitle: {
            text: '',
            x: -80
        },

        pane: {
            size: '90%'
        },

        xAxis: {
            categories: ['Mineral Scales','Fines Blockage','Organic Scales','Relative Permeability','Induced Damage','Geomechanical Damage'],
            tickmarkPlacement: 'on',
            lineWidth: 0
        },

        yAxis: {
            gridLineInterpolation: 'polygon',
            lineWidth: 0,
            min: 0
        },

        tooltip: {
            shared: true
        },

        legend: {
            align: 'right',
            verticalAlign: 'top',
            y: 70,
            layout: 'vertical'
        },

        series: [{
            name: name,
            data: data,
            pointPlacement: 'on'
        }]

    });
});
   
}

function SkinCharacterization()
{   

    var skin = [];
    var Statistical_MSP = Statistical(MSP,false);
    var Statistical_FBP = Statistical(FBP,false);
    var Statistical_OSP = Statistical(OSP,false);
    var Statistical_KrP = Statistical(KrP,false);
    var Statistical_IDP = Statistical(IDP,false);
    var Statistical_GDP = Statistical(GDP,false);
    var Total_Statistical = Statistical_MSP+Statistical_FBP+Statistical_OSP+Statistical_KrP+Statistical_IDP+Statistical_GDP;


    var Analytical_MSP = Analytical(MSP_KdKi,"MSP");
    var Analytical_FBP = Analytical(FBP_KdKi,"FBP");
    var Analytical_OSP = Analytical(OSP_KdKi,"OSP");
    var Analytical_KrP = Analytical(KrP_KdKi,"KrP");
    var Analytical_IDP = Analytical(IDP_KdKi,"IDP");
    var Analytical_GDP = Analytical(GDP_KdKi,"GDP");
    var Total_Analytical = Analytical_MSP+Analytical_FBP+Analytical_OSP+Analytical_KrP+Analytical_IDP+Analytical_GDP;



    var MSP_damage = ((Statistical_MSP/Total_Statistical)+(Analytical_MSP/Total_Analytical))/2;
    var FBP_damage = ((Statistical_FBP/Total_Statistical)+(Analytical_FBP/Total_Analytical))/2;
    var OSP_damage = ((Statistical_OSP/Total_Statistical)+(Analytical_OSP/Total_Analytical))/2;
    var KrP_damage = ((Statistical_KrP/Total_Statistical)+(Analytical_KrP/Total_Analytical))/2;
    var IDP_damage = ((Statistical_IDP/Total_Statistical)+(Analytical_IDP/Total_Analytical))/2;
    var GDP_damage = ((Statistical_GDP/Total_Statistical)+(Analytical_GDP/Total_Analytical))/2;


   // alert("MSP_damage: " + MSP_damage*100);
   // alert("FBP_damage: " + FBP_damage*100);
   // alert("OSP_damage: " + OSP_damage*100);
   // alert("KrP_damage: " + KrP_damage*100);
   // alert("IDP_damage: " + IDP_damage*100);
   // alert("GDP_damage: " + GDP_damage*100);

   skin.push(Math.round(MSP_damage*10000)/100);
   skin.push(Math.round(FBP_damage*10000)/100);
   skin.push(Math.round(OSP_damage*10000)/100);
   skin.push(Math.round(KrP_damage*10000)/100);
   skin.push(Math.round(IDP_damage*10000)/100);
   skin.push(Math.round(GDP_damage*10000)/100);


   return skin.reverse();

}

//Ok
function Statistical(Parameter,HIF)
{
    
    
    var average = 0.0;
    if(HIF)
    {
        
        for(i=0;i<Parameter.length-1;i++)
        {
            var a = ValorNormalizado(Parameter[i]);
            average = average+a;
        }

        average = average/Parameter.length-1;
        average = average * ValorNormalizado(Parameter[Parameter.length-1]);

    }else{
        
        for(i=0;i<Parameter.length;i++)
        {
            
            var a = ValorNormalizado(Parameter[i]);
            average = average+a;

        }
    
        average = average/Parameter.length;
        
    }

    
    return average;

}

//Ok
function ValorNormalizado(SubParam)
{
    return (SubParam[0]-SubParam[1])/(SubParam[2]-SubParam[1]);
}

//Ok
function Analytical(KdKi,ParameterType)
{

    var DamageRadius = SkinRadius(ParameterType);
    var skin = ((1/KdKi)-1) * Math.log(DamageRadius/WellRadius); 
    return skin;
}
//Ok
function SkinRadius(Param)
{

    var SkinRadius = 0.0;
    if(Param == "MSP")
    {
        SkinRadius = PvsR_profile(Pc_MSP) + WellRadius; 

    }else if(Param =="FBP")
    {

        SkinRadius = CR_mcv + WellRadius; 
    }else if(Param == "OSP")
    {
        SkinRadius = PvsR_profile(Pc_OSP) + WellRadius; 
    }else if(Param == "KrP")
    {
        if(ReservoirPressure < SaturationPressure) 
        {
            SkinRadius = ExternalRadius + WellRadius; 
        }else
        {
            SkinRadius = PvsR_profile(Pc_KrP) + WellRadius;
        }
    }else if(Param == "IDP")
    {

        SkinRadius = Math.sqrt(TV_wbf/ (Math.PI * NetPay * (Porosity/100))) + WellRadius;

    }else if(Param == "GDP")
    {
        SkinRadius = PvsR_profile_GDP(Pc_GDP) + WellRadius;
    }

    return SkinRadius;
}

function PvsR_profile(CriticalPressure)
{
    
    var Radius = WellRadius; 
    var Pr = BottomHolePressure; 
    var DeltaP = Pr - CriticalPressure; 

    while(DeltaP < 0.0 && Radius < ExternalRadius)
    {
        Radius = Radius + 0.01;
        if(TypeOfWell=="Oil")
        {
            Pr = BottomHolePressure+(((141.2*OILRate*OILViscosity*OILVolumetricFactor)/(NetPay*CondensatePerm))* Math.log(Radius/WellRadius))-(0.5*(Radius/Math.pow(ExternalRadius, 2))); 

        }else
        {
            Pr = BottomHolePressure + (((141.2*GASRate*(1000000)*GASViscosity*GASVolumetricFactor)/(5.615*NetPay*CondensatePerm))*Math.log(Radius/WellRadius))-(0.5*(Radius/Math.pow(ExternalRadius, 2))); 

        }

        DeltaP = Pr - CriticalPressure;

        if(Radius >= ExternalRadius)
        {
            Radius = ExternalRadius - WellRadius;

        }

        
    }

       return Radius;
}

function PvsR_profile_GDP(CriticalPressure)
{       
        
        var Radius = WellRadius; 
        if (TypeOfWell == "Oil")
        {
            var Pr_Rmax = BottomHolePressure + (((141.2*OILRate*OILViscosity*OILVolumetricFactor)/(NetPay*CondensatePerm))* Math.log(ExternalRadius/WellRadius))-(0.5*(ExternalRadius/Math.pow(ExternalRadius, 2)));
            var Pr_Rmin = BottomHolePressure + (((141.2*OILRate*OILViscosity*OILVolumetricFactor)/(NetPay*CondensatePerm))* Math.log(WellRadius/WellRadius))-(0.5*(WellRadius/Math.pow(ExternalRadius, 2)));
        }else
        {
            var Pr_Rmax = BottomHolePressure + (((141.2*GASRate*(1000000)*GASViscosity*GASVolumetricFactor)/(5.615*NetPay*CondensatePerm))*Math.log(ExternalRadius/WellRadius))-(0.5*(ExternalRadius/Math.pow(ExternalRadius, 2)));
            var Pr_Rmin = BottomHolePressure + (((141.2*GASRate*(1000000)*GASViscosity*GASVolumetricFactor)/(5.615*NetPay*CondensatePerm))*Math.log(WellRadius/WellRadius))-(0.5*(WellRadius/Math.pow(ExternalRadius, 2)));
        }

        var MaxDrawDown_fraction = (Pr_Rmax-Pr_Rmin) / (Pr_Rmax-Pr_Rmin);
        var CummDrawDown = 1.0 - MaxDrawDown_fraction;


        while (CummDrawDown < 0.25 && Radius < ExternalRadius)
        {
            Radius = Radius + 0.01;
            if (TypeOfWell == "Oil")
            {
                var Pr = BottomHolePressure + (((141.2*OILRate*OILViscosity*OILVolumetricFactor)/(NetPay*CondensatePerm))* Math.log(Radius/WellRadius))-(0.5*(Radius/Math.pow(ExternalRadius, 2)));
            }
            else
            {
                var Pr = BottomHolePressure + (((141.2*GASRate*(1000000)*GASViscosity*GASVolumetricFactor)/(5.615*NetPay*CondensatePerm))*Math.log(Radius/WellRadius))-(0.5*(Radius/Math.pow(ExternalRadius, 2)));
            }

            MaxDrawDown_fraction = (Pr_Rmax-Pr) / (Pr_Rmax-Pr_Rmin);
            CummDrawDown = 1.0 - MaxDrawDown_fraction;
        }

        if (Radius >= ExternalRadius)
        {
            Radius = ExternalRadius - WellRadius;
        }

        return Radius;
}

</script>

@endsection


@section('Scripts')
<script src="http://nextgen.pl/_/scroll/lib/jquery.min.js"></script>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/highcharts-more.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>


@endsection


