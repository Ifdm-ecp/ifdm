<script type="text/javascript">


    /* Descripción: define sección que se incluye en el pdf de descarga del reporte*/
    function download()
    {
        //console.log("A");
        var divElements = document.getElementById('report').innerHTML;
        //Get the HTML of whole page
        var oldPage = document.body.innerHTML;

        //Reset the page's HTML with div's HTML only
        document.body.innerHTML = 
          "<html><head><title></title></head><body>" + 
          divElements + "</body>";

        //Print Page
        window.print();

        //Restore orignal HTML
        document.body.innerHTML = oldPage;
        location.reload();
    }
    /*Descripción: captura sección que se incluye en el reporte y envía los datos a la función popup-
        Parámetros:
            elem: div que contiene la información que será incluida en el reporte*/
    function PrintElem(elem)
    {
        Popup($(elem).html());
    }
    /*Descripción: restringe elementos que aparecen en el reporte
        Parámetros: 
            data: sección que será incluida en el reporte*/
    function Popup(data) 
    {
        var mywindow = window.open('', 'my div', 'height=400,width=600');
        mywindow.document.write('<html><head><title>IFDM Report</title>');
        mywindow.document.write('<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">');
        mywindow.document.write('<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">');
        mywindow.document.write('</head><body >');
        mywindow.document.write(data);
        mywindow.document.write('</body></html>');

        mywindow.document.close(); // necessary for IE >= 10
        mywindow.focus(); // necessary for IE >= 10

        mywindow.print();
        mywindow.close();

        return true;
    }


    var datos = [];
    var ultimos = [];
    var ps = [];
    var psf = [];
    var weights = [];
        
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

    /* Descripción: Captura de datos, definición de vista y asignación de datos para la construcción del reporte.  */
    (function() {

        var scenary=<?php echo \Request::get('id')?>;
        var tipo;
        var nombre;
        var descripcion;
        var fecha;

        $.get("{{url('tipo_esc')}}",
        {esc_id:scenary},
        function(data)
        {
            $.each(data, function(index,value)
            {
                tipo = value.tipo;
                nombre = value.nombre;
                descripcion = value.descripcion;
                fecha=value.fecha;
            });

            if(tipo=="Multiparametric")
            {
                $("#mp").show();
                $("#ipr_xxx").hide();
                $("#drilling").hide();

                $("#tit_rep").text("Multiparametric Analysis Report");
                $("#sub_rep").text(nombre);
                $("#des_rep").text(descripcion);
                $("#date_rep").text(fecha);
                var statistical;
                var field_statistical = [];
                $.get("{{url('mp_info')}}",
                    {esc_id:scenary},
                    function(data)
                    {
                        $.each(data,function(index,value)
                        {
                            statistical = value.statistical;
                            if(statistical==null)
                            {
                                $("#field").show();
                                field_statistical=(value.field_statistical).split(",");
                                field_statistical.forEach(function(field_id) {
                                    $.get("{!! url('statistical_field') !!}",
                                      {
                                        field_id: field_id
                                      },function(data){
                                        var  k ="";
                                        var z ="";
                                        $.each(data, function(index,value)
                                        {
                                          k = k.concat("<tr> <td>"+ value.nombre +"</td></tr>");
                                        });
                                        z = k.concat($("#s_d").html());
                                        $('#s_d').html(z);
                                      });
                                    
                                });
                            }
                            else
                            {
                                $('#s_d').text(statistical);
                            }



                            $('#top').text(value.top);
                            $('#tvd').text(value.bottom);
                            $('#netpay').text(value.netPay);
                            $('#porosity').text(value.porosity);
                            $('#abs_perm').text(value.absPermeability);
                            $('#eff_perm').text(value.kc); //Revisar

                            $('#w_r').text(value.well_radius);
                            $('#d_r').text(value.drainage_radius);
                            $('#r_p').text(value.reservoir_pressure);
                            $('#bhp').text(value.bottom_HPressure);
                            $('#oil_r').text(value.qo);
                            $('#gas_r').text(value.qg);
                            $('#wat_r').text(value.qw);

                            //$('#pr_i').text(value.);
                            //$('#date').text('2015/05/05'); //Traer datos del escenario
                            //$('#qo').text(value.qo);
                            //$('#qg').text(value.qg);
                            //$('#qw').text(value.qw);

                            $('#f_t').text(value.fluidType);
                            $('#s_p').text(value.saturation_pressure);

                            $('#o_visco').text(value.viscosityO);
                            $('#o_fvf').text(value.fvfO);
                            $('#o_rs').text(value.rs);

                            $('#g_visco').text(value.viscosityG);
                            $('#g_fvf').text(value.fvfG);
                            $('#g_rv').text(value.rv);

                            $('#w_visco').text(value.viscosityW);
                            $('#w_fvf').text(value.fvfW);



                            $('#ms').text(value.ms);
                            $('#fb').text(value.fb);
                            $('#os').text(value.os);
                            $('#rp').text(value.kr);
                            $('#idd').text(value.idd);
                            $('#gd').text(value.gd);

                            $('#ms_pc').text(value.pc_MSP);
                            $('#os_pc').text(value.pc_OSP);
                            $('#rpe_pc').text(value.pc_KrP);
                            $('#gd_pc').text(value.pc_GDP);
                            $('#crd').text(value.critical_radius);
                            $('#tvow').text(value.total_volumen);

                            $('#ms1').text(value.ms1);
                            $('#ms2').text(value.ms2);
                            $('#ms3').text(value.ms3);
                            $('#ms4').text(value.ms4);
                            $('#ms5').text(value.ms5);

                            $('#fp1').text(value.fb1);
                            $('#fp2').text(value.fb2);
                            $('#fp3').text(value.fb3);
                            $('#fp4').text(value.fb4);
                            $('#fp5').text(value.fb5);

                            $('#os1').text(value.os1);
                            $('#os2').text(value.os2);
                            $('#os3').text(value.os3);
                            $('#os4').text(value.os4);

                            $('#rp1').text(value.rp1);
                            $('#rp2').text(value.rp2);
                            $('#rp3').text(value.rp3);
                            $('#rp4').text(value.rp4);

                            $('#id1').text(value.id1);
                            $('#id2').text(value.id2);
                            $('#id3').text(value.id3);
                            $('#id4').text(value.id4);

                            $('#gd1').text(value.gd1);
                            $('#gd2').text(value.gd2);
                            $('#gd3').text(value.gd3);
                            $('#gd4').text(value.gd4);

                        });
                    });

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
                            var x = [];
                            $.each(data.Pes, function(index, value)
                            {
                                    ps.push(value);
                                    
                            });

                            $.each(data.Weights, function(index, value)
                            {
                                    weights.push(value);
                                    
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
                                    switch(i)
                                    {
                                        case 0:
                                            psfx.push(value.p10_ms1);
                                            psfx.push(value.p90_ms1);
                                            psfx.push(weights[i]);
                                            break;
                                        case 1:
                                            psfx.push(value.p10_ms2);
                                            psfx.push(value.p90_ms2);
                                            psfx.push(weights[i]);
                                            break;
                                        case 2:
                                            psfx.push(value.p10_ms3);
                                            psfx.push(value.p90_ms3);
                                            psfx.push(weights[i]);
                                            break;
                                        case 3:
                                            psfx.push(value.p10_ms4);
                                            psfx.push(value.p90_ms4);
                                            psfx.push(weights[i]);
                                            break;
                                        case 4:
                                            psfx.push(value.p10_ms5);
                                            psfx.push(value.p90_ms5);
                                            psfx.push(weights[i]);
                                            break;
                                    }
                                    //psfx.push(ps[i][0]);
                                    //psfx.push(ps[i][1]);
                                    MSP.push(psfx);
                                    
                                };

                                //console.log(MSP);
                                for (var i = 5; i < 10; i++) 
                                {
                                    
                                    var psfx = [];
                                    psfx.push(sp[i]);
                                    switch(i)
                                    {
                                        case 5:
                                            psfx.push(value.p10_fb1);
                                            psfx.push(value.p90_fb1);
                                            psfx.push(weights[i]);
                                            break;
                                        case 6:
                                            psfx.push(value.p10_fb2);
                                            psfx.push(value.p90_fb2);
                                            psfx.push(weights[i]);
                                            break;
                                        case 7:
                                            psfx.push(value.p10_fb3);
                                            psfx.push(value.p90_fb3);
                                            psfx.push(weights[i]);
                                            break;
                                        case 8:
                                            psfx.push(value.p10_fb4);
                                            psfx.push(value.p90_fb4);
                                            psfx.push(weights[i]);
                                            break;
                                        case 9:
                                            psfx.push(value.p10_fb5);
                                            psfx.push(value.p90_fb5);
                                            psfx.push(weights[i]);
                                            break;
                                    }
                                    //psfx.push(ps[i][0]);
                                    //psfx.push(ps[i][1]);
                                    FBP.push(psfx);
                                               
                                };
                                //console.log(FBP);

                                for (var i = 10; i < 14; i++) 
                                {
                                    
                                    var psfx = [];
                                    psfx.push(sp[i]);
                                    switch(i)
                                    {
                                        case 10:
                                            psfx.push(value.p10_os1);
                                            psfx.push(value.p90_os1);
                                            psfx.push(weights[i]);
                                            break;
                                        case 11:
                                            psfx.push(value.p10_os2);
                                            psfx.push(value.p90_os2);
                                            psfx.push(weights[i]);
                                            break;
                                        case 12:
                                            psfx.push(value.p10_os3);
                                            psfx.push(value.p90_os3);
                                            psfx.push(weights[i]);
                                            break;
                                        case 13:
                                            psfx.push(value.p10_os4);
                                            psfx.push(value.p90_os4);
                                            psfx.push(weights[i]);
                                            break;
                                    }
                                    //psfx.push(ps[i][0]);
                                    //psfx.push(ps[i][1]);
                                    OSP.push(psfx);
                                               
                                };
                                //console.log(OSP);

                                for (var i = 14; i < 18; i++) 
                                {
                                    
                                    var psfx = [];
                                    psfx.push(sp[i]);
                                    switch(i)
                                    {
                                        case 14:
                                            psfx.push(value.p10_rp1);
                                            psfx.push(value.p90_rp1);
                                            psfx.push(weights[i]);
                                            break;
                                        case 15:
                                            psfx.push(value.p10_rp2);
                                            psfx.push(value.p90_rp2);
                                            psfx.push(weights[i]);
                                            break;
                                        case 16:
                                            psfx.push(value.p10_rp3);
                                            psfx.push(value.p90_rp3);
                                            psfx.push(weights[i]);
                                            break;
                                        case 17:
                                            psfx.push(value.p10_rp4);
                                            psfx.push(value.p90_rp4);
                                            psfx.push(weights[i]);
                                            break;
                                    }
                                    //psfx.push(ps[i][0]);
                                    //psfx.push(ps[i][1]);
                                    KrP.push(psfx);
                                               
                                };
                                //console.log(KrP);
                                for (var i = 18; i < 22; i++) 
                                {
                                                    var psfx = [];
                                    psfx.push(sp[i]);
                                    switch(i)
                                    {
                                        case 18:
                                            psfx.push(value.p10_id1);
                                            psfx.push(value.p90_id1);
                                            psfx.push(weights[i]);
                                            break;
                                        case 19:
                                            psfx.push(value.p10_id2);
                                            psfx.push(value.p90_id2);
                                            psfx.push(weights[i]);
                                            break;
                                        case 20:
                                            psfx.push(value.p10_id3);
                                            psfx.push(value.p90_id3);
                                            psfx.push(weights[i]);
                                            break;
                                        case 21:
                                            psfx.push(value.p10_id4);
                                            psfx.push(value.p90_id4);
                                            psfx.push(weights[i]);
                                            break;
                                    }
                                    //psfx.push(ps[i][0]);
                                    //psfx.push(ps[i][1]);
                                    IDP.push(psfx); 
                                              
                                };

                                //console.log(IDP);
                                for (var i = 22; i < 26; i++) 
                                {
                                    var aux = 1;
                                    var psfx = [];
                                    psfx.push(sp[i]);    
                                    switch(i)
                                    {
                                        case 22:
                                            psfx.push(value.p10_gd1);
                                            psfx.push(value.p90_gd1);
                                            psfx.push(weights[i]);
                                            break;
                                        case 23:
                                            psfx.push(value.p10_gd2);
                                            psfx.push(value.p90_gd2);
                                            psfx.push(weights[i]);
                                            break;
                                        case 24:
                                            psfx.push(value.p10_gd3);
                                            psfx.push(value.p90_gd3);
                                            psfx.push(weights[i]);
                                            break;
                                        case 25:
                                            psfx.push(value.p10_gd4);
                                            psfx.push(value.p90_gd4);
                                            psfx.push(weights[i]);
                                            break;
                                    }
                                    //psfx.push(ps[i][0]);
                                    //psfx.push(ps[i][1]);
                                    GDP.push(psfx);
                                               
                                };
                                //console.log(GDP);


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
                VerticalDepht = parseFloat(datos[1]); // TDV - Pozos
                WellRadius = parseFloat(datos[2]); // Radius - Pozos
                ExternalRadius = parseFloat(datos[3]); // drainageRadius - Pozos

                    //PVT Data
                OILViscosity = parseFloat(datos[4]); //oilViscosity - fluidoxpozos
                GASViscosity = parseFloat(datos[5]); //gasViscosity - fluidoxpozos
                OILVolumetricFactor = parseFloat(datos[6]); //FVFO - fluiodoxpozos
                GASVolumetricFactor = parseFloat(datos[7]); //FVFG - fluidoxpozos

                    //PRODUCTION Data
                BottomHolePressure = parseFloat(datos[8]); //bhp - pozos
                TOPdepht = parseFloat(datos[9]); //TOP - formacionxpozos
                NetPay = parseFloat(datos[10]); //NetPay - formacionxpozos
                Porosity = parseFloat(datos[11]); // Porosidad - formacionxpozos
                Permeability = parseFloat(datos[12]); //FPermeabilidad - formacionxpozos 
                CondensatePerm = parseFloat(datos[13]); // Kc - formacionxpozos
                ReservoirPressure = parseFloat(datos[14]); // Presion_reservorio - formacionxpozos
                SaturationPressure = parseFloat(datos[15]); // Saturation_pressure - formacionxpozos

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
                OILRate = parseFloat(datos[26]); //oilRate - pozos - Qo 
                GASRate = parseFloat(datos[27]); //gasRate - pozos - Qg

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
                console.log("Vamos");
                data = SkinCharacterization();
                spider(data,id);
                    });

                });    
            }
            else if(tipo=="IPR")
            {
                $("#tit_rep").text("IPR Analysis Report");
                $("#sub_rep").text(nombre);
                $("#des_rep").text(descripcion);
                $("#date_rep").text(fecha);
                $("#ipr_xxx").show();
                $("#mp").hide();
                $("#drilling").hide();
                var tasa_flujo;
                var presion_fondo;
                $.get("{{url('ipr_info')}}",
                    {esc_id:scenary},
                    function(data)
                    {
                        $.each(data,function(index,value)
                        {
                            var id_ipr = value.id;

                            if(value.fluido == 1)
                            {
                                tasa_flujo=value.tasa_flujo;
                                presion_fondo=value.presion_fondo;
                                $(".oil_data").show();
                                $(".gas_data").hide();
                                $(".condensate_gas_data").hide();
                                $('#fluid_oil').text("Oil");
                                $('#well_radius').text(parseFloat(value.radio_pozo));
                                $('#reservoir_dr').text(parseFloat(value.radio_drenaje_yac));
                                $('#current_rp').text(parseFloat(value.presion_yacimiento));
                                $('#oil_rateipr').text(parseFloat(value.tasa_flujo));
                                $('#bhp_ipr').text(parseFloat(value.presion_fondo));
                                $('#bsw_ipr').text(parseFloat(value.bsw));
                                $('#initial_rp').text(parseFloat(value.presion_inicial));
                                $('#absolute_p').text(parseFloat(value.permeabilidad_abs_ini));
                                $('#net_pay').text(parseFloat(value.espesor_reservorio));

                                if(value.modulo_permeabilidad!=null)
                                {
                                    $("#use_perm").show();
                                    $('#permeability_m').text(parseFloat(value.modulo_permeabilidad));
                                }
                                else
                                {
                                    $("#cal_perm").show();
                                    $('#absolute_p_p').text(parseFloat(value.permeabilidad));
                                    $('#porosity_p').text(parseFloat(value.porosidad));
                                    if(value.tipo_roca == 1)
                                    {
                                       $('#rock_type').text("Consolidated"); 
                                    }else if(value.tipo_roca == 2)
                                    {
                                        $('#rock_type').text("Unconsolidated"); 
                                    }else if(value.tipo_roca == 3)
                                    {
                                        $('#rock_type').text("Microfractured"); 
                                    }
                                }
                                
                                if(value.end_point_kr_aceite_gas!=null)
                                {
                                    $("#cor_perm").show();
                                    $('#kro').text(parseFloat(value.end_point_kr_aceite_gas));
                                    $('#sgc').text(parseFloat(value.saturacion_gas_crit));
                                    $('#krg').text(parseFloat(value.end_point_kr_gas));
                                    $('#sorg').text(parseFloat(value.saturacion_aceite_irred_gas));
                                    $('#corey_og').text(parseFloat(value.exponente_corey_aceite_gas));
                                    $('#corey_g').text(parseFloat(value.exponente_corey_gas));

                                    $('#kro_gw').text(parseFloat(value.end_point_kr_petroleo));
                                    $('#swi').text(parseFloat(value.saturacion_agua_irred));
                                    $('#krw').text(parseFloat(value.end_point_kr_agua));
                                    $('#sor').text(parseFloat(value.saturacion_aceite_irred));
                                    $('#corey_oil').text(parseFloat(value.exponente_corey_petroleo));
                                    $('#corey_water').text(parseFloat(value.exponente_corey_agua));
                                }
                                else
                                {
                                    $("#rel_tab").show();
                                    //Water-Oil
                                    var ipr = [];
                                    $.get("{!! url('ipr_tabla_water') !!}",
                                    {
                                      id_ipr: id_ipr
                                    },function(data){
                                        var  k ="";
                                        $.each(data, function(index,value)
                                        {
                                            k = k.concat("<tr> <td>"+ parseFloat(value.lista_sw) +"</td><td>"+ parseFloat(value.lista_krw) +"</td><td>"+ parseFloat(value.lista_kro) +"</td></tr>");
                                        });
                                            $('#water_oil').html("<table class=\"table-condensed table-bordered\"><tr><th>Sw</th><th>Krw</th><th>Kro</th></tr>"+k+"</table>");
                                      });

                                    //Gas-Oil
                                    var ipr = [];
                                    $.get("{!! url('ipr_tabla_gas') !!}",
                                    {
                                      id_ipr: id_ipr
                                    },function(data)
                                    {
                                        var  k ="";
                                        $.each(data, function(index,value)
                                        {
                                            k = k.concat("<tr> <td>"+ parseFloat(value.lista_sg) +"</td><td>"+ parseFloat(value.lista_krg) +"</td><td>"+ parseFloat(value.lista_krosg) +"</td></tr>");
                                        });
                                        $('#gas_oil').html("<table class=\"table-condensed table-bordered\"><tr><th>Sg</th><th>Krg</th><th>Krog</th></tr>"+k+"</table>");
                                    });
                                }

                                $('#satu-press').text(parseFloat(value.saturation_pressure));
                                $('#wat_vis').text(parseFloat(value.viscosidad_agua));

                                $("#pvt_tab").show();
                                //PVT data selection
                                var ipr = [];
                                $.get("{!! url('ipr_tabla') !!}",
                                {
                                  id_ipr: id_ipr
                                },function(data)
                                {
                                    var  k ="";
                                    $.each(data, function(index,value)
                                    {
                                        k = k.concat("<tr> <td>"+ parseFloat(value.presion) +"</td><td>"+ parseFloat(value.viscosidad_aceite) +"</td><td>"+ parseFloat(value.factor_volumetrico_aceite) +"</td><td>"+ parseFloat(value.viscosidad_agua) +"</td></tr>");
                                    });
                                    $('#tabular_pvt').html("<table class=\"table-condensed table-bordered\"><tr><th>Pressure[psi]</th><th>Oil Viscosity [cp]</th><th>Oil Volumetric Factor[RB]</th><th>Water Viscosity [cp]</th></tr>"+k+"</table>");
                                });
                            }
                            else if(value.fluido == 2)
                            {
                                tasa_flujo=value.gas_rate_g;
                                presion_fondo=value.bhp_g;
                                $(".gas_data").show();
                                $(".oil_data").hide();
                                $(".condensate_gas_data").hide();
                                $('#fluid_gas').text("Gas");
                                $('#well_radius_gas').text(parseFloat(value.radio_pozo));
                                $('#reservoir_dr_gas').text(parseFloat(value.radio_drenaje_yac));
                                $('#current_rp_gas').text(parseFloat(value.presion_yacimiento));
                                $('#gas_rateipr').text(parseFloat(value.gas_rate_g));
                                $('#bhp_gas').text(parseFloat(value.bhp_g));
                                $('#initial_rp_gas').text(parseFloat(value.init_res_press_text_g));
                                $('#absolute_p_gas').text(parseFloat(value.abs_perm_init_text_g));
                                $('#net_pay_gas').text(parseFloat(value.net_pay_text_g));

                                if(value.permeability_module_text_g!=null)
                                {
                                    $("#use_perm_gas").show();
                                    $('#permeability_m_gas').text(parseFloat(value.permeability_module_text_g));
                                }
                                else
                                {
                                    $("#cal_perm_gas").show();
                                    $('#absolute_p_p_gas').text(parseFloat(value.abs_perm_text_g));
                                    $('#porosity_p_gas').text(parseFloat(value.porosity_text_g));
                                    if(value.rock_type == 1)
                                    {
                                        $('#rock_type_gas').text("Consolidated"); 
                                    }
                                    else if(value.rock_type == 2)
                                    {
                                        $('#rock_type_gas').text("Unconsolidated"); 
                                    }
                                    else if(value.rock_type == 3)
                                    {
                                        $('#rock_type_gas').text("Microfractured"); 
                                    }
                                }

                                $('#temperature_gas').text(parseFloat(value.temperature_text_g));

                                  if(value.campo_a1!=null)
                                  {
                                        $("#cubic_ec_gas").show();
                                        $('#p3_gas').text(parseFloat(value.c1_viscosity_fluid_g));
                                        $('#p2_gas').text(parseFloat(value.c2_viscosity_fluid_g));
                                        $('#p_gas').text(parseFloat(value.c3_viscosity_fluid_g));
                                        $('#p0_gas').text(parseFloat(value.c4_viscosity_fluid_g));
                                        $('#p3_g_gas').text(parseFloat(value.c1_compressibility_fluid_g));
                                        $('#p2_g_gas').text(parseFloat(value.c2_compressibility_fluid_g));
                                        $('#p_g_gas').text(parseFloat(value.c3_compressibility_fluid_g));
                                        $('#p0_g_gas').text(parseFloat(value.c4_compressibility_fluid_g));
                                  }
                                  else
                                  {
                                    $("#pvt_tab_gas").show();
                                    //PVT data selection
                                    var ipr = [];
                                    $.get("{!! url('ipr_tabla_pvtgas') !!}",
                                    {
                                      id_ipr: id_ipr
                                    },function(data){
                                      var  k ="";
                                      $.each(data, function(index,value)
                                      {
                                        k = k.concat("<tr> <td>"+ parseFloat(value.pressure) +"</td><td>"+ parseFloat(value.gas_viscosity) +"</td><td>"+ parseFloat(value.gas_compressibility_factor) +"</td></tr>");
                                      });
                                        $('#tabular_pvt_gas').html("<table class=\"table-condensed table-bordered\"><tr><th>Pressure[psi]</th><th>Oil Viscosity [cp]</th><th>Gas Compressibility Factor</th></tr>"+k+"</table>");
                                    });
                                  }
                            }
                            else if(value.fluido == 3)
                            {
                                tasa_flujo=value.gas_rate_c_g;
                                presion_fondo=value.bhp_c_g;
                                $(".gas_data").hide();
                                $(".oil_data").hide();
                                $(".condensate_gas_data").show();
                                $('#fluid_cg').text("Condensate Gas");
                                $('#well_radius_cg').text(parseFloat(value.radio_pozo));
                                $('#reservoir_dr_cg').text(parseFloat(value.radio_drenaje_yac));
                                $('#current_rp_cg').text(parseFloat(value.presion_yacimiento));
                                $('#gas_rate_cg').text(parseFloat(value.gas_rate_c_g));
                                $('#bhp_cg').text(parseFloat(value.bhp_c_g));
                                $('#initial_rp_cg').text(parseFloat(value.initial_pressure_c_g));
                                $('#absolute_p_cg').text(parseFloat(value.ini_abs_permeability_c_g));
                                $('#net_pay_cg').text(parseFloat(value.netpay_c_g));

                                if(value.permeability_module_c_g!=null)
                                {
                                    $("#use_perm_cg").show();
                                    $('#permeability_m_cg').text(parseFloat(value.permeability_module_c_g));
                                }
                                else
                                {
                                    $("#cal_perm_cg").show();
                                    $('#absolute_p_p_cg').text(parseFloat(value.permeability_c_g));
                                    $('#porosity_p_cg').text(parseFloat(value.porosity_c_g));
                                    if(value.rock_type_c_g == 1)
                                    {
                                       $('#rock_type_cg').text("Consolidated"); 
                                    }
                                    else if(value.rock_type_c_g == 2)
                                    {
                                        $('#rock_type_cg').text("Unconsolidated"); 
                                    }
                                    else if(value.rock_type_c_g == 3)
                                    {
                                        $('#rock_type_cg').text("Microfractured"); 
                                    }
                                }

                                $("#saturation_pressure_c_g").text(parseFloat(value.saturation_pressure_c_g));
                                $("#gor_c_g").text(parseFloat(value.gor_c_g));
                                //PVT DATA
                                $("#pvt_tab_cg").show();

                                $.get("{!! url('ipr_tabla_pvt_cg') !!}",
                                {
                                  id_ipr: id_ipr
                                },function(data){
                                  var  k ="";
                                  $.each(data, function(index,value)
                                  {
                                    k = k.concat("<tr> <td>"+ parseFloat(value.pressure) +"</td><td>"+ parseFloat(value.bo) +"</td><td>"+ parseFloat(value.vo) +"</td><td>"+ parseFloat(value.rs)+"</td><td>"+ parseFloat(value.bg)+"</td><td>"+ parseFloat(value.vg)+"</td><td>"+ parseFloat(value.og_ratio)+"</td></tr>");
                                  });
                                $('#tabular_pvt_cg').html("<table class=\"table-condensed table-bordered\"><tr><th>Pressure[psi]</th><th> Bo [RB/STB]</th><th>Uo [cP]</th><th>RS [SCF/STB]</th><th>Bg [RCF/SCF]</th><th>Ug [cP]</th><th>O-G Ratio [STB/SCF]</th></tr>"+k+"</table>");
                                });

                                //Gas-Oil
                                $.get("{!! url('ipr_tabla_gas_cg') !!}",
                                {
                                  id_ipr: id_ipr
                                },function(data){
                                  var  k ="";
                                  $.each(data, function(index,value)
                                  {
                                    k = k.concat("<tr> <td>"+ parseFloat(value.sg) +"</td><td>"+ parseFloat(value.krg) +"</td><td>"+ parseFloat(value.krog) +"</td></tr>");
                                  });
                                    $('#gas_oil_cg').html("<table class=\"table-condensed table-bordered\"><tr><th>Sg</th><th>Krg</th><th>Krog</th></tr>"+k+"</table>");
                                });

                                //Drop out 
                                $.get("{!! url('ipr_dropout_cg') !!}",
                                {
                                  id_ipr: id_ipr
                                },function(data){
                                  var  k ="";
                                  $.each(data, function(index,value)
                                  {
                                    k = k.concat("<tr> <td>"+ parseFloat(value.pressure) +"</td><td>"+ parseFloat(value.liquid_percentage) +"</td></tr>");
                                  });
                                    $('#dropout_cg').html("<table class=\"table-condensed table-bordered\"><tr><th>Pressure [psi]</th><th>Liquid Fracture []</th></tr>"+k+"</table>");
                                });
                            }

                            //Grafica IPR
                            var data_i= [];
                            var data_ = [];
                            var i=0;
                            var j=0;
                            $.get("{!! url('grafica1_ipr') !!}",
                              {
                                id_ipr: id_ipr
                              },function(data){
                                $.each(data, function(index,value3)
                                {
                                    aux = [parseFloat(value3.tasa_flujo), parseFloat(value3.presion_fondo)];
                                    data_i.push(aux);

                                });
                                $.get("{!! url('grafica2_ipr') !!}",
                              {
                                id_ipr: id_ipr
                              },function(data){
                                $.each(data, function(index,value2)
                                {
                                    aux = [parseFloat(value2.tasa_flujo), parseFloat(value2.presion_fondo)];
                                    data_.push(aux);

                                });
                                if(parseFloat(value.fluido)==1)
                                {
                                    t = "Oil";
                                }
                                else
                                {
                                    t="Gas";
                                }
                                $('#grafica_ipr').highcharts({
                                title: {
                                    text: 'Bottomhole Flowing Pressure Vs. '+t+' Rate',
                                    x: -20 //center
                                },
                                credits: {
                                    enabled: false
                                },
                                
                                legend: {
                                    layout: 'vertical',
                                    align: 'right',
                                    verticalAlign: 'middle',
                                    borderWidth: 0
                                },
                                tooltip: {
                                    headerFormat: '<b>{series.name}</b><br />',
                                    pointFormat: 'x = {point.x}, y = {point.y}'
                                },
                                xAxis: {
                                    title: {
                                        text: t+' Rate [bbl/day]'
                                    },
                                },
                                yAxis: {
                                    title: {
                                        text: 'Bottomhole Flowing Pressure [psi]'
                                    },
                                    plotLines: [{
                                        value: 0,
                                        width: 1,
                                        color: '#808080'
                                    }]
                                },

                                series: [{
                                    name: 'Current IPR',
                                    data: data_i
                                     
                                },{
                                    name: 'Production Data',
                                    data:  [[parseFloat(tasa_flujo),parseFloat(presion_fondo)]]
                                }, 
                                {
                                    name: 'Skin = 0 (ideal)',
                                    data: data_
                                }]
                            });
                            });
                            });
                            
                            ///////////////

                        });
                    });
                    //console.log("IPR");
                    /*
                    $.get("{{url('desagregacion_info')}}",
                        {esc_id:scenary},
                        function(data)
                        {
                            $.each(data,function(index,value)
                            {
                                $("#desagregacion").show();
                                $('#well_radiu').text(parseFloat(value.radio_pozo));
                                $('#reserv_draina').text(parseFloat(value.radio_drenaje_pozo));
                                $('#reserv_pre').text(parseFloat(value.presion_promedio_yacimiento));
                                $('#measur_well').text(parseFloat(value.profundidad_medida_pozo));
                                $('#thickness_p').text(parseFloat(value.espesor_canoneado));
                                $('#perf_penet').text(parseFloat(value.profundidad_penetracion_canones));
                                $('#perf_pha').text(parseFloat(value.angulo_fase_canoneo_perforado));
                                $('#perf_rad').text(parseFloat(value.radio_perforado));
                                $('#true_vd').text(parseFloat(value.profundidad_real_formacion));
                                $('#prod_form').text(parseFloat(value.espesor_formacion_productora));

                                if(value.forma_area_drenaje==1){
                                    $('#img_1').html("<img src=\"{!! asset('images/drainage-shapes/desagregacion-forma1.png') !!}\" />");
                                }else if(value.forma_area_drenaje==2){
                                    $('#img_1').html("<img src=\"{!! asset('images/drainage-shapes/desagregacion-forma2.png') !!}\" />");
                                }else if(value.forma_area_drenaje==3){
                                    $('#img_1').html("<img src=\"{!! asset('images/drainage-shapes/desagregacion-forma3.png') !!}\" />");
                                }else if(value.forma_area_drenaje==4){
                                    $('#img_1').html("<img src=\"{!! asset('images/drainage-shapes/desagregacion-forma4.png') !!}\" />");
                                }else if(value.forma_area_drenaje==5){
                                    $('#img_1').html("<img src=\"{!! asset('images/drainage-shapes/desagregacion-forma5.png') !!}\" />");
                                }else if(value.forma_area_drenaje==6){
                                    $('#img_1').html("<img src=\"{!! asset('images/drainage-shapes/desagregacion-forma6.png') !!}\" />");
                                }else if(value.forma_area_drenaje==7){
                                    $('#img_1').html("<img src=\"{!! asset('images/drainage-shapes/desagregacion-forma7.png') !!}\" />");
                                }else if(value.forma_area_drenaje==8){
                                    $('#img_1').html("<img src=\"{!! asset('images/drainage-shapes/desagregacion-forma8.png') !!}\" />");
                                }else if(value.forma_area_drenaje==9){
                                    $('#img_1').html("<img src=\"{!! asset('images/drainage-shapes/desagregacion-forma9.png') !!}\" />");
                                }else if(value.forma_area_drenaje==10){
                                    $('#img_1').html("<img src=\"{!! asset('images/drainage-shapes/desagregacion-forma10.png') !!}\" />");
                                }else if(value.forma_area_drenaje==11){
                                    $('#img_1').html("<img src=\"{!! asset('images/drainage-shapes/desagregacion-forma11.png') !!}\" />");
                                }else if(value.forma_area_drenaje==12){
                                    $('#img_1').html("<img src=\"{!! asset('images/drainage-shapes/desagregacion-forma12.png') !!}\" />");
                                }else if(value.forma_area_drenaje==13){
                                    $('#img_1').html("<img src=\"{!! asset('images/drainage-shapes/desagregacion-forma13.png') !!}\" />");
                                }else if(value.forma_area_drenaje==14){
                                    $('#img_1').html("<img src=\"{!! asset('images/drainage-shapes/desagregacion-forma14.png') !!}\" />");
                                }else if(value.forma_area_drenaje==15){
                                    $('#img_1').html("<img src=\"{!! asset('images/drainage-shapes/desagregacion-forma15.png') !!}\" />");
                                }else if(value.forma_area_drenaje==16){
                                    $('#img_1').html("<img src=\"{!! asset('images/drainage-shapes/desagregacion-forma16.png') !!}\" />");
                                }
                                

                                $('#oil_ra').text(parseFloat(value.caudal_produccion_aceite));
                                $('#bottomhole').text(parseFloat(value.presion_fondo_pozo));
                                $('#gas_ra').text(parseFloat(value.caudal_produccion_gas));

                                $('#permy').text(parseFloat(value.presion_fondo_pozo));
                                if(value.caudal_produccion_gas=="consolidada"){
                                    $('#rock_ty').text("Consolidated");
                                }else if(value.caudal_produccion_gas=="poco consolidada"){
                                    $('#rock_ty').text("Unconsolidated");
                                }else{
                                    $('#rock_ty').text("Microfractured");
                                }
                                $('#hor_ver').text(parseFloat(value.relacion_perm_horiz_vert));

                                $('#oil_vy').text(parseFloat(value.viscosidad_aceite));
                                $('#gas_vy').text(parseFloat(value.viscosidad_gas));
                                $('#specif_gg').text(parseFloat(value.gravedad_especifica_gas));
                                $('#volum_of').text(parseFloat(value.factor_volumetrico_aceite));
                                
                                $('#min_hor').text(parseFloat(value.gradiente_esfuerzo_horizontal_minimo));
                                $('#max_hor').text(parseFloat(value.gradiente_esfuerzo_horizontal_maximo));
                                $('#ver_stess').text(parseFloat(value.gradiente_esfuerzo_vertical));


                                //Hidraulic Units Data
                                  var ipr = [];
                                  var id_desagregacion=value.id;
                                  $.get("{!! url('desagregacion_tabla') !!}",
                                  {
                                    id_desagregacion: id_desagregacion
                                  },function(data){
                                    var  k ="";
                                    $.each(data, function(index,value)
                                    {
                                      k = k.concat("<tr> <td>"+ parseFloat(value.espesor) +"</td><td>"+ parseFloat(value.fzi) +"</td><td>"+ parseFloat(value.porosidad_promedio) +"</td><td>"+ parseFloat(value.permeabilidad) +"</td></tr>");
                                    });
                                      $('#hidr_units').html("<table class=\"table-condensed table-bordered\"><tr><th>Thickness Of The Hidraulic Unit [ft]</th><th>Flow Zone Index [µm]</th><th>Average Porosity Of The Hidraulic unit [%]</th><th>Average permeability of the hidraulic Unit [mD]</th></tr>"+k+"</table>");
                                  });

                                    $('#dam_in_skin').text(parseFloat(value.dano_total));

                                  $.get("{!! url('resultado_desagregacion') !!}", {
                                    id_desagregacion : id_desagregacion
                                  }, function(data)
                                  {

                                    var k = "";
                                    $.each(data, function(index,value)
                                    {
                                        k = k.concat("<tr> <td>"+ parseFloat((value.total_skin).toFixed(2)) +"</td><td>"+ parseFloat((value.mechanical_skin).toFixed(2)) +"</td><td>"+ parseFloat((value.stress_skin).toFixed(2)) +"</td><td>"+ parseFloat((value.pseudo_skin).toFixed(2)) +"</td><td>"+ parseFloat((value.rate_skin).toFixed(2)) +"</td></tr>");
                                    });
                                    $('#resultados_desagregacion').html("<table class=\"table-condensed table-bordered\"><tr><th>Total Skin</th><th>Mechanical Skin</th><th>Stress-dependent Skin</th><th>Pseudo Skin</th><th>Rate-dependent Skin</th></tr>"+k+"</table>");
                                  });
                                  var eje_x =[];
                                  var eje_y =[];
                                  $.get("{!! url('radios_desagregacion') !!}",
                                  {
                                    id_desagregacion:id_desagregacion
                                  }, function(data)
                                  {

                                    $.each(data, function(index,value)
                                    {
                                        eje_x.push(parseFloat(value.radio));
                                    });

                                    $.get("{!! url('permeabilidades_desagregacion') !!}",
                                    {
                                      id_desagregacion:id_desagregacion
                                    }, function(data)
                                    {
                                      $.each(data, function(index,value)
                                      {
                                          eje_y.push(parseFloat(value.permeabilidad));
                                      });

                                      grafica_aumentada(eje_x,eje_y);

                                    });

                                  });
                                  var skins = [];
                                  $.get("{!! url('spider_desagregacion') !!}",
                                    {id_desagregacion:id_desagregacion},
                                    function(data)
                                    {
                                        $.each(data, function(index,value)
                                        {
                                            skins.push(parseFloat(value.total_skin));
                                            skins.push(parseFloat(value.mechanical_skin));
                                            skins.push(parseFloat(value.stress_skin));
                                            skins.push(parseFloat(value.pseudo_skin));
                                            skins.push(parseFloat(value.rate_skin));
                                        });
                                        danio_desagregado(skins, {dano_total: 'Total Skin',dano_mecanico: 'Mechanical Skin',dano_por_esfuerzo: 'Stress-dependent Skin',pseudo_dano: 'Pseudo Skin',dano_por_tasa: 'Rate-dependent Skin'},'Skin By Components');
                                    });
                            });
                        }); */
            }
            else if(tipo=="Drilling")
            {
                $("#tit_rep").text("Drilling Analysis Report");
                $("#sub_rep").text(nombre);
                $("#des_rep").text(descripcion);
                $("#date_rep").text(fecha);
                $("#drilling").show();
                $("#ipr_xxx").hide();
                $("#mp").hide();

                $.get("{!! url('drilling_report') !!}",
                {esc_id:scenary},
                function(data)
                {
                    var drilling_id = data.id;

                    $.get("{!! url('d_general_data') !!}",
                    {drilling_id:drilling_id},
                    function(data)
                    {
                        var  k ="";
                          $.each(data, function(index,value)
                          {
                            k = k.concat("<tr> <td>"+ (value.interval) +"</td><td>"+ parseFloat(value.top) +"</td><td>" + parseFloat(value.bottom) +"</td><td>"+ parseFloat(value.reservoir_pressure) +"</td><td>"+ parseFloat(value.hole_diameter) +"</td><td>"+ parseFloat(value.drill_pipe_diameter) +"</td></tr>");
                          });
                            $('#general_data_drilling').html("<table class=\"table-condensed table-bordered\"><tr><th>Interval</th><th>Top [ft]</th><th>Bottom [ft]</th><th>Reservoir Pressure [psi]</th><th>Hole Diameter [in]</th><th>Drill Pipe Diameter [in]</th></tr>"+k+"</table>");
                    });


                    if(data.input_data_select == 1){
                        $('#input_data_method').text("Average");

                        $.get("{!! url('d_average_input_data') !!}",
                        {drilling_id:drilling_id},
                        function(data)
                        {
                            var  k ="";
                              $.each(data, function(index,value)
                              {
                                k = k.concat("<tr> <td>"+ (value.formation) +"</td><td>"+ parseFloat(value.porosity) +"</td><td>" + parseFloat(value.permeability) +"</td><td>"+ parseFloat(value.fracture_intensity) +"</td><td>"+ parseFloat(value.irreducible_saturation) +"</td></tr>");
                              });
                                $('#input_data_drilling').html("<table class=\"table-condensed table-bordered\"><tr><th>Formation</th><th>Porosity [-]</th><th>Permeability [mD]</th><th>Fracture Intensity [#/ft]</th><th>Irreducible Saturation [-]</th></tr>"+k+"</table>");
                        });
                    }else if (data.input_data_select == 2){
                        $('#input_data_method').text("By Intervals");
                        $.get("{!! url('d_intervals_input_data') !!}",
                        {drilling_id:drilling_id},
                        function(data)
                        {
                            var  k ="";
                              $.each(data, function(index,value)
                              {
                                k = k.concat("<tr> <td>"+ (value.interval) +"</td><td>"+ parseFloat(value.porosity) +"</td><td>" + parseFloat(value.permeability) +"</td><td>"+ parseFloat(value.fracture_intensity) +"</td><td>"+ parseFloat(value.irreducible_saturation) +"</td></tr>");
                              });
                                $('#input_data_drilling').html("<table class=\"table-condensed table-bordered\"><tr><th>Interval</th><th>Porosity [-]</th><th>Permeability [mD]</th><th>Fracture Intensity [#/ft]</th><th>Irreducible Saturation [-]</th></tr>"+k+"</table>");
                        });
                    } else if (data.input_data_select == 3){
                        $('#input_data_method').text("Profile");

                        $.get("{!! url('d_profile_input_data') !!}",
                        {drilling_id:drilling_id},
                        function(data)
                        {
                            //var  k ="";
                            var depth_arr = [];
                            var porosity_arr = [];
                            var permeability_arr = [];
                            var fracture_intensity_arr = [];
                            var irreducible_saturation_arr = [];

                            $.each(data, function(index,value)
                            {
                                /*
                                k = k.concat("<tr> <td>"+ (value.depth) +"</td><td>"+ parseFloat(value.porosity) +"</td><td>" + parseFloat(value.permeability) +"</td><td>"+ parseFloat(value.fracture_intensity) +"</td><td>"+ parseFloat(value.irreducible_saturation) +"</td></tr>");*/

                                depth_arr.push(value.depth);
                                porosity_arr.push(value.porosity);
                                permeability_arr.push(value.permeability);
                                fracture_intensity_arr.push(value.fracture_intensity);
                                irreducible_saturation_arr.push(value.irreducible_saturation);
                            });

                            /*$('#input_data_drilling').html("<table class=\"table-condensed table-bordered\"><tr><th>Depth [ft]</th><th>Porosity [-]</th><th>Permeability [mD]</th><th>Fracture Intensity [#/ft]</th><th>Irreducible Saturation [-]</th></tr>"+k+"</table>");*/

                            drilling_profile(depth_arr, porosity_arr, permeability_arr, fracture_intensity_arr, irreducible_saturation_arr);
                        });
                    }

                    $('#total_exposure_time').text(parseFloat(data.d_total_exposure_time));
                    $('#pump_rate_drilling').text(parseFloat(data.d_pump_rate));
                    $('#max_mud_density_drilling').text(parseFloat(data.d_max_mud_density));
                    $('#correction_factor_drilling').text(parseFloat(data.d_correction_factor));
                    $('#min_mud_density_drilling').text(parseFloat(data.d_min_mud_density));
                    $('#rop_drilling').text(parseFloat(data.d_rop));
                    $('#ecd_drilling').text(parseFloat(data.d_equivalent_circulating_density));


                    $('#total_exposure_time_cementing').text(parseFloat(data.c_total_exposure_time));
                    $('#pump_rate_drilling_cementing').text(parseFloat(data.c_pump_rate));
                    $('#cement_slurry_density_drilling_cementing').text(parseFloat(data.c_cement_slurry));
                    $('#correction_factor_drilling_cementing').text(parseFloat(data.c_correction_factor));
                    $('#ecd_drilling_cementing').text(parseFloat(data.c_equivalent_circulating_density));

                    $.get("{!! url('drilling_results_chart') !!}",
                    {drilling_id:drilling_id},
                    function(data)
                    {
                        var tops_arr = [];
                        var invasion_radius_drilling_arr = [];
                        var invasion_radius_cementation_arr = [];
                        $.each(data, function(index,value)
                        {
                            tops_arr.push(value.top);
                            invasion_radius_drilling_arr.push(value.d_invasion_radius);
                            invasion_radius_cementation_arr.push(value.c_invasion_radius);
                        });
                        results_driling(tops_arr, invasion_radius_drilling_arr, invasion_radius_cementation_arr);
                    });


                    $.get("{!! url('drilling_results') !!}",
                    {drilling_id:drilling_id},
                    function(data)
                    {
                        
                        $('#d_maximum_total_skin_r').text(parseFloat(data.d_maximum_calculated_skin));
                        $('#d_average_total_skin_r').text(parseFloat(data.d_average_calculated_skin));
                        $('#total_invasion_radius_drilling_r').text(parseFloat(data.d_total_invasion_radius_volume));
                        $('#maximum_invasion_radius_drilling_r').text(parseFloat(data.d_maximum_invasion_radius));
                        $('#average_invasion_radius_drilling_r').text(parseFloat(data.d_average_invasion_radius));
                        $('#c_maximum_total_skin_r').text(parseFloat(data.c_maximum_calculated_skin));
                        $('#c_average_total_skin_r').text(parseFloat(data.c_average_calculated_skin));
                        $('#total_invasion_radius_cementation_r').text(parseFloat(data.c_total_invasion_radius_volume));
                        $('#maximum_invasion_radius_cementation_r').text(parseFloat(data.c_maximum_invasion_radius));
                        $('#average_invasion_radius_cementation_r').text(parseFloat(data.c_average_invasion_radius));
                        $('#maximum_total_skin_r').text(parseFloat(data.calculated_skin_max_total));
                        $('#average_total_skin_r').text(parseFloat(data.calculated_skin_avg_total));
                        $('#maximum_total_filtration_volume_r').text(parseFloat(data.filtration_volume_max_total));
                        $('#average_total_filtration_volume_r').text(parseFloat(data.filtration_volume_avg_total));
                        $('#maximum_total_invasion_radius_r').text(parseFloat(data.total_invasion_radius_max_total));
                        $('#average_total_invasion_radius_r').text(parseFloat(data.total_invasion_radius_avg_total));
                        
                    });

                    
                });
            }

        });

/*
Gráfico drilling input data para profile
*/
function drilling_profile(depth, porosity, permeability, fracture_intensity, irreducible_saturation){
    $('#profile_g').highcharts({
        chart: {
            type: 'line',
            zoomType: 'x',
            inverted: true
        },
         title: {
             text: 'Profile Data',
             x: -20 //center
         },
         xAxis: {
          title: {
            text: 'Depth[ft]'
          },
             categories: depth
         },
         yAxis: {
             title: {
                 text: 'Profile Data'
             },
             plotLines: [{
                 value: 0,
                 width: 1,
                 color: '#808080'
             }]
         },
         tooltip: {
             valueSuffix: ''
         },
         legend: {
             layout: 'vertical',
             align: 'right',
             verticalAlign: 'middle',
             borderWidth: 0
         },
         series: [{
             name: 'Porosity [-]',
             data: porosity
         }, {
             name: 'Permeability [mD]',
             data: permeability
         }, {
             name: 'Fracture Intesity [#/ft]',
             data: fracture_intensity
         }, {
             name: 'Irreducible Saturation[-]',
             data: irreducible_saturation
         }]
     });
}

/*Gráfico drilling results*/
function results_driling(tops, invasion_radius_drilling, invasion_radius_cementation){

    $('#results_drilling').highcharts({
        chart: {
            inverted: true,
            zoomType: 'x'
        },
            title: {
            text: 'Invasion Radius',
            x: -20 //center
        },
            xAxis: {
            title: {
            text: 'Depth [ft]'
        },
            categories: tops
        },
            yAxis: {
            title: {
                text: 'Invasion Radius [ft]'
            },
            plotLines: [{
                value: 0,
                width: 1,
                color: '#808080'
            }]
        },
        tooltip: {
            valueSuffix: ''
        },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'middle',
            borderWidth: 0
        },
        series: [{
            name: 'Invasion Radius (Drilling Phase)',
            data: invasion_radius_drilling
        }, {
            name: 'Invasion Radius (Cementing Phase)',
            data: invasion_radius_cementation
        }]
    });
}

/*Descripción: recoge los datos y genera el gráfico para el reporte de escenarios de ipr que contienen análisis de desagregación
    Parámetros: 
        data: resultados del análisis de desagregación consultados desde la base de datos. 
        etiquetas: nombre de los ejes del gráfico
        nombre: nombre del escenario*/
function danio_desagregado(data, etiquetas, nombre)
{

    $('#spider_desagregacion').highcharts({
        chart: 
        {
            polar: true,
            type: 'line'
        },
        title: 
        {
            text: nombre,
            x: -80
        },
        pane: 
        {
            size: '80%'
        },
        xAxis: 
        {
            categories: [etiquetas['dano_total'],etiquetas['dano_mecanico'],etiquetas['dano_por_esfuerzo'],
            etiquetas['pseudo_dano'],etiquetas['dano_por_tasa']],
            tickmarkPlacement: 'on',
            lineWidth: 0
        },
        yAxis: 
        {
            gridLineInterpolation: 'polygon',
            lineWidth: 0,
            min: 0,
            maxTickInterval: 5
        },
        tooltip: 
        {
            shared: true,
            pointFormat: '<b>{point.y:,.0f}</b><br/>'
        },
        legend: 
        {
            align: 'right',
            verticalAlign: 'top',
            y: 70,
            layout: 'vertical'
        },
        series: 
        [{
            name: nombre,
            data: data,
            pointPlacement: 'on'
        }]
    });
}
/*Descripción: complementa a la gráfica de desagregación y muestra más detalladamente una porción de información.
    Parámetros: 
        eje_x: datos del eje x. 
        eje_y: datos del eje y
        */
function grafica_aumentada(eje_x, eje_y)
{
    // console.log(data);
    //console.log(eje_x);
    //console.log(eje_y);
    var data = new Array(eje_x.length);
    for (var i = 0; i < data.length; i++) {
        data[i] = [eje_x[i], eje_y[i]];
    }
    //console.log(data);
    $('#grafica_desagregacion').highcharts({
            credits: {
                enabled: false
            },
            xAxis: {
                min: Math.min.apply(Math, eje_x),
                max: 10,
                title: {
                  enabled: true,
                  text: 'Radius (ft)',
                  style: {
                      fontWeight: 'normal'
                  }
                }
            },
            yAxis: {
                min: Math.min.apply(Math, eje_y),
                max: Math.max.apply(Math, eje_y),
                title: {
                  enabled: true,
                  text: 'Permeability (k)',
                  style: {
                      fontWeight: 'normal'
                }
              }
            },
            title: {
                text: 'Radius (ft) vs. Permeability (k) (zoomed, ' + Math.min.apply(Math, eje_x) + ' < radius < 10)'
            },
            series: [{
                type: 'line',
                name: 'k vs. ft',
                data: data,
                marker: {
                    enabled: false
                },
                states: {
                    hover: {
                        lineWidth: 0
                    }
                },
                enableMouseTracking: false
            }]
        });
}
/*Descripción: captura los datos y despliega el gráfico spider principal del módulo multiparamétrico.
    Parámetros: 
        data: datos del resultado de la caracterización del skin. 
        mp: id del escenario multiparamétrico para la consulta de la información adicional para la construcción de los resultados
        */
function spider(data,mp)
{
    if(isNaN(data[0][0]))
    {
        data[0]=[0,0,0,0,0,0];
        alert("Check your input data, this result doesn't represent a real multiparametric analysis");
    }
    if(isNaN(data[1][0]))
    {
        data[1]=[0,0,0,0,0,0];
        alert("Check your input data, this result doesn't represent a real multiparametric analysis");
    }
    if(isNaN(data[2][0]))
    {
        data[2]=[0,0,0,0,0,0];
        alert("Check your input data, this result doesn't represent a real multiparametric analysis");
    }

    var pozo;
    var fecha;
    var camposp;
    var scnombre;
    var intervalo;

    $.get("{{'spiderinfo'}}",
    {mp: mp}, function(datas)
    {
        $.each(datas.datos,function(index,value)
        {
            pozo = value.nombre;
            fecha = value.fecha;
            camposp = value.Cnombre;
            scnombre = value.scnombre;
        });
        $.each(datas.intervalo,function(index,value)
        {
            intervalo = value.nombre;
        });
        
        $('#titulo').html('Skin Characterization Diagram');
        $('#subt').html('<b>Scenario: </b>'+scnombre+'</br><b>Producing Interval: </b>'+intervalo+' - <b>Field: </b>'+ camposp+' - <b>Well Name: </b>'+pozo +' - <b>Study Date:</b>'+fecha);


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
                size: '80%'
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
                valueDecimals: 4,
                valueSuffix: ' %'
            },

            legend: {
                align: 'right',
                verticalAlign: 'top',
                y: 70,
                layout: 'vertical'
            },

            series: [{
                name: intervalo,
                data: data[0],
                pointPlacement: 'on'
            }]
        });
        $('#container_2').highcharts({

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
                size: '80%'
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
                valueDecimals: 4,
                valueSuffix: ' %'
            },

            legend: {
                align: 'right',
                verticalAlign: 'top',
                y: 70,
                layout: 'vertical'
            },

            series: [{
                name: intervalo,
                data: data[1],
                pointPlacement: 'on'
            }]
        });
        $('#container_3').highcharts({

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
                size: '80%'
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
                valueDecimals: 4,
                valueSuffix: ' %'
            },

            legend: {
                align: 'right',
                verticalAlign: 'top',
                y: 70,
                layout: 'vertical'
            },

            series: [{
                name: intervalo,
                data: data[2],
                pointPlacement: 'on'
            }]
        });

        var data_table_final = [];

        for (var m = 0; m<data.length; m++) 
        {
          var data_table = [[data[m][0], "Mineral Scales"], [data[m][1], "Fines Blockage"], [data[m][2], "Organic Scales"], [data[m][3], "Relative Permeability"],[data[m][4], "Induced Damage"],[data[m][5], "Geomechanical Damage"]];

          for (var i = 0; i <= data_table.length; i++) 
          {
            for (var j = 0; j <= data_table.length; j++) 
            {
              if(parseFloat(data_table[j])<parseFloat(data_table[i]))
              {
                var aux = data_table[j];
                data_table[j] = data_table[i];
                data_table[i] = aux; 
              }
              
            }

          }

          data_table_final.push(data_table);
        }


        $("#statistical_body").append
        (
          '<tr>'+
            '<th>'+data_table_final[0][0][1]+'</th>'+
            '<th>'+parseFloat(data_table_final[0][0][0]).toFixed(4)+' % </th>'+
          '</tr>' +
          '<tr>'+
            '<th>'+data_table_final[0][1][1]+'</th>'+
            '<th>'+parseFloat(data_table_final[0][1][0]).toFixed(4)+' % </th>'+
          '</tr>' +
          '<tr>'+
            '<th>'+data_table_final[0][2][1]+'</th>'+
            '<th>'+parseFloat(data_table_final[0][2][0]).toFixed(4)+' % </th>'+
          '</tr>' +
          '<tr>'+
            '<th>'+data_table_final[0][3][1]+'</th>'+
            '<th>'+parseFloat(data_table_final[0][3][0]).toFixed(4)+' % </th>'+
          '</tr>' +
          '<tr>'+
            '<th>'+data_table_final[0][4][1]+'</th>'+
            '<th>'+parseFloat(data_table_final[0][4][0]).toFixed(4)+' % </th>'+
          '</tr>' +
          '<tr>'+
            '<th>'+data_table_final[0][5][1]+'</th>'+
            '<th>'+parseFloat(data_table_final[0][5][0]).toFixed(4)+' % </th>'+
          '</tr>' 
        );
        $("#analytical_body").append
        (
          '<tr>'+
            '<th>'+data_table_final[1][0][1]+'</th>'+
            '<th>'+parseFloat(data_table_final[1][0][0]).toFixed(4)+' % </th>'+
          '</tr>' +
          '<tr>'+
            '<th>'+data_table_final[1][1][1]+'</th>'+
            '<th>'+parseFloat(data_table_final[1][1][0]).toFixed(4)+' % </th>'+
          '</tr>' +
          '<tr>'+
            '<th>'+data_table_final[1][2][1]+'</th>'+
            '<th>'+parseFloat(data_table_final[1][2][0]).toFixed(4)+' % </th>'+
          '</tr>' +
          '<tr>'+
            '<th>'+data_table_final[1][3][1]+'</th>'+
            '<th>'+parseFloat(data_table_final[1][3][0]).toFixed(4)+' % </th>'+
          '</tr>' +
          '<tr>'+
            '<th>'+data_table_final[1][4][1]+'</th>'+
            '<th>'+parseFloat(data_table_final[1][4][0]).toFixed(4)+' % </th>'+
          '</tr>' +
          '<tr>'+
            '<th>'+data_table_final[1][5][1]+'</th>'+
            '<th>'+parseFloat(data_table_final[1][5][0]).toFixed(4)+' % </th>'+
          '</tr>' 
        );
        $("#total_body").append
        (
          '<tr>'+
            '<th>'+data_table_final[2][0][1]+'</th>'+
            '<th>'+parseFloat(data_table_final[2][0][0]).toFixed(4)+' % </th>'+
          '</tr>' +
          '<tr>'+
            '<th>'+data_table_final[2][1][1]+'</th>'+
            '<th>'+parseFloat(data_table_final[2][1][0]).toFixed(4)+' % </th>'+
          '</tr>' +
          '<tr>'+
            '<th>'+data_table_final[2][2][1]+'</th>'+
            '<th>'+parseFloat(data_table_final[2][2][0]).toFixed(4)+' % </th>'+
          '</tr>' +
          '<tr>'+
            '<th>'+data_table_final[2][3][1]+'</th>'+
            '<th>'+parseFloat(data_table_final[2][3][0]).toFixed(4)+' % </th>'+
          '</tr>' +
          '<tr>'+
            '<th>'+data_table_final[2][4][1]+'</th>'+
            '<th>'+parseFloat(data_table_final[2][4][0]).toFixed(4)+' % </th>'+
          '</tr>' +
          '<tr>'+
            '<th>'+data_table_final[2][5][1]+'</th>'+
            '<th>'+parseFloat(data_table_final[2][5][0]).toFixed(4)+' % </th>'+
          '</tr>' 
        );
    });
}

/*Descripción: Fachada para generar el análisis estadístico y analítico del escenario multiparamétrico. 
        */
function SkinCharacterization()
{   


  var skin = [];
  var Statistical_MSP = Statistical(MSP,"MSP");
  var Statistical_FBP = Statistical(FBP,"FBP");
  var Statistical_OSP = Statistical(OSP,"OSP");
  var Statistical_KrP = Statistical(KrP,"KrP");
  var Statistical_IDP = Statistical(IDP,"IDP");
  var Statistical_GDP = Statistical(GDP,"GDP");

  var Total_Statistical = Statistical_MSP+Statistical_FBP+Statistical_OSP+Statistical_KrP+Statistical_IDP+Statistical_GDP;

  var statistical_skin = [(Statistical_MSP/Total_Statistical)*100,(Statistical_FBP/Total_Statistical)*100,(Statistical_OSP/Total_Statistical)*100,(Statistical_KrP/Total_Statistical)*100,(Statistical_IDP/Total_Statistical)*100,(Statistical_GDP/Total_Statistical)*100];
  var Analytical_MSP = Analytical(MSP_KdKi,"MSP");
  var Analytical_FBP = Analytical(FBP_KdKi,"FBP");
  var Analytical_OSP = Analytical(OSP_KdKi,"OSP");
  var Analytical_KrP = Analytical(KrP_KdKi,"KrP");
  var Analytical_IDP = Analytical(IDP_KdKi,"IDP");
  var Analytical_GDP = Analytical(GDP_KdKi,"GDP");

  var Total_Analytical = Analytical_MSP+Analytical_FBP+Analytical_OSP+Analytical_KrP+Analytical_IDP+Analytical_GDP;
  
  var analytical_skin = [(Analytical_MSP/Total_Analytical)*100,(Analytical_FBP/Total_Analytical)*100,(Analytical_OSP/Total_Analytical)*100,(Analytical_KrP/Total_Analytical)*100,(Analytical_IDP/Total_Analytical)*100,(Analytical_GDP/Total_Analytical)*100];

  var MSP_damage = ((Statistical_MSP/Total_Statistical)+(Analytical_MSP/Total_Analytical))/2;
  var FBP_damage = ((Statistical_FBP/Total_Statistical)+(Analytical_FBP/Total_Analytical))/2;
  var OSP_damage = ((Statistical_OSP/Total_Statistical)+(Analytical_OSP/Total_Analytical))/2;
  var KrP_damage = ((Statistical_KrP/Total_Statistical)+(Analytical_KrP/Total_Analytical))/2;
  var IDP_damage = ((Statistical_IDP/Total_Statistical)+(Analytical_IDP/Total_Analytical))/2;
  var GDP_damage = ((Statistical_GDP/Total_Statistical)+(Analytical_GDP/Total_Analytical))/2;

  skin.push(Math.round(MSP_damage*10000)/100);
  skin.push(Math.round(FBP_damage*10000)/100);
  skin.push(Math.round(OSP_damage*10000)/100);
  skin.push(Math.round(KrP_damage*10000)/100);
  skin.push(Math.round(IDP_damage*10000)/100);
  skin.push(Math.round(GDP_damage*10000)/100);

  var final_skins = [statistical_skin, analytical_skin, skin];

  return final_skins;
}

/*Descripción: análisis estadístico del escenario multiparamétrico.
    Parámetros: 
        Parameter: datos estadísticos del subparámetro de estudio. p10, p90 y valores del universo de datos. 
        HIF: sigla del parámetro que define cómo se realiza el estudio estadístico. 
        */
function Statistical(Parameter,HIF)
{
      
      var average = 0.0;
      if(HIF == "MSP")
      {
          for(i=0;i<Parameter.length;i++)
          {
              var a = ValorNormalizado(Parameter[i]);
              average = average+a;

          }
          
          average = average/Parameter.length;
      }
      else if(HIF == "FBP")
      {
          for(i=0;i<Parameter.length;i++)
          {
              var a = ValorNormalizado(Parameter[i]);
              average = average+a;

          }
          
          average = average/Parameter.length;
          average = average + ValorNormalizado(Parameter[2]);
      }
      else if(HIF == "OSP")
      {
          for(i=0;i<Parameter.length;i++)
          {
              var a = ValorNormalizado(Parameter[i]);
              average = average+a;

          }
          
          average = average/Parameter.length;
          average = average = average + ValorNormalizado(Parameter[3]);
      }
      else if(HIF == "KrP")
      {
          for(i=0;i<Parameter.length;i++)
          {
              var a = ValorNormalizado(Parameter[i]);
              average = average+a;

          }
          
          average = average/Parameter.length;
          average = average = average + ValorNormalizado(Parameter[3]);
      }
      else if(HIF == "IDP")
      {
         for(i=0;i<Parameter.length;i++)
         {
             var a = ValorNormalizado(Parameter[i]);
             average = average+a;

         } 
      }
      else if(HIF == "GDP")
      {
          for(i=0;i<Parameter.length;i++)
          {
              var a = ValorNormalizado(Parameter[i]);
              average = average+a;

          }
          
          average = average/Parameter.length;
      }
      return average;
}

/*Descripción: normaliza el valor de un subparámetro con base en el universo de datos.
    Parámetros: 
        SubParam: valores del subparámetro . 
        */
function ValorNormalizado(SubParam)
{
    var a = parseFloat(SubParam[0]);
    var b = parseFloat(SubParam[1]);
    var c = parseFloat(SubParam[2]);
    var d = parseFloat(SubParam[3]);
    return ((a-b)/(c-b))*d;
}
/*Descripción: análisis analítico del escenario multiparamétrico.
    Parámetros: 
        Parameter: datos estadísticos del subparámetro de estudio. p10, p90 y valores del universo de datos. 
        HIF: sigla del parámetro que define cómo se realiza el estudio analítico. 
        */
function Analytical(KdKi,ParameterType)
{
    var DamageRadius = SkinRadius(ParameterType);
    var skin = ((1/KdKi)-1) * Math.log(DamageRadius/WellRadius); 
    return skin;
}
/*Descripción: calcula el skin según parámetro.
    Parámetros: 
        Parameter: nombre del parámetro.  
        */
function SkinRadius(Param)
{

    var SkinRadius = 0.0;
    if(Param == "MSP")
    {
        SkinRadius = PvsR_profile(Pc_MSP) + WellRadius; 
    }
    else if(Param =="FBP")
    {
        SkinRadius = CR_mcv + WellRadius; 
    }
    else if(Param == "OSP")
    {
        SkinRadius = PvsR_profile(Pc_OSP) + WellRadius; 
    }
    else if(Param == "KrP")
    {
        if(ReservoirPressure < SaturationPressure) 
        {
            SkinRadius = ExternalRadius + WellRadius; 
        }else
        {
            SkinRadius = PvsR_profile(Pc_KrP) + WellRadius;
        }
    }
    else if(Param == "IDP")
    {

        SkinRadius = Math.sqrt((TV_wbf*5.615)/ (Math.PI * NetPay * (Porosity/100))) + WellRadius;

    }
    else if(Param == "GDP")
    {
        SkinRadius = PvsR_profile_GDP(Pc_GDP) + WellRadius;
    }

    return SkinRadius;
}
/*Descripción: calcula el perfil de radio vs presión según parámetro. 
    Parámetros: 
        CriticalPressure: presión crítica según parámetro.  
        */
function PvsR_profile(CriticalPressure)
{
    
    var Radius = WellRadius; 
    var Pr = BottomHolePressure; 
    var DeltaP = Pr - CriticalPressure; 
    while(DeltaP < 0.0 && Radius < ExternalRadius)
    {
        Radius = Radius + 0.05;
        if(TypeOfWell=="Oil")
        {

            Pr = BottomHolePressure+(((141.2*OILRate*OILViscosity*OILVolumetricFactor)/(NetPay*CondensatePerm))* Math.log(Radius/WellRadius))-(0.5*(Math.pow((Radius/ExternalRadius), 2))); 

        }
        else
        {
            Pr = BottomHolePressure + (((141.2*GASRate*(1000000)*GASViscosity*GASVolumetricFactor)/(5.615*NetPay*CondensatePerm))*(Math.log(Radius/WellRadius)-(0.5*(Math.pow((Radius/ExternalRadius), 2))))); 
        }
        
        DeltaP = Pr - CriticalPressure;

        if(Radius >= ExternalRadius)
        {
            Radius = ExternalRadius - WellRadius;

        }
    }
    

    return Radius;
}
/*Descripción: calcula el perfil de radio vs presión para el parámetro GDP. 
    Parámetros: 
        CriticalPressure: presión crítica parámetro GDP.  
        */
function PvsR_profile_GDP(CriticalPressure)
{       
        
        var Radius = WellRadius; 
        if (TypeOfWell == "Oil")
        {
            var Pr_Rmax = BottomHolePressure + (((141.2*OILRate*OILViscosity*OILVolumetricFactor)/(NetPay*CondensatePerm))* Math.log(ExternalRadius/WellRadius))-(0.5*(Math.pow((ExternalRadius/ExternalRadius), 2)));
            var Pr_Rmin = BottomHolePressure + (((141.2*OILRate*OILViscosity*OILVolumetricFactor)/(NetPay*CondensatePerm))* Math.log(WellRadius/WellRadius))-(0.5*(Math.pow((WellRadius/ExternalRadius), 2)));
        }
        else
        {
            var Pr_Rmax = BottomHolePressure + (((141.2*GASRate*(1000000)*GASViscosity*GASVolumetricFactor)/(5.615*NetPay*CondensatePerm))*Math.log(ExternalRadius/WellRadius))-(0.5*(Math.pow((ExternalRadius/ExternalRadius), 2)));
            var Pr_Rmin = BottomHolePressure + (((141.2*GASRate*(1000000)*GASViscosity*GASVolumetricFactor)/(5.615*NetPay*CondensatePerm))*Math.log(WellRadius/WellRadius))-(0.5*(Math.pow((WellRadius/ExternalRadius), 2)));
        }

        var MaxDrawDown_fraction = (Pr_Rmax-Pr_Rmin) / (Pr_Rmax-Pr_Rmin);
        var CummDrawDown = 1.0 - MaxDrawDown_fraction;


        while (CummDrawDown < 0.25 && Radius < ExternalRadius)
        {
            Radius = Radius + 0.01;
            if (TypeOfWell == "Oil")
            {
                var Pr = BottomHolePressure + (((141.2*OILRate*OILViscosity*OILVolumetricFactor)/(NetPay*CondensatePerm))* Math.log(Radius/WellRadius))-(0.5*(Math.pow((Radius/ExternalRadius), 2)));
            }
            else
            {
                var Pr = BottomHolePressure + (((141.2*GASRate*(1000000)*GASViscosity*GASVolumetricFactor)/(5.615*NetPay*CondensatePerm))*Math.log(Radius/WellRadius))-(0.5*(Math.pow((Radius/ExternalRadius), 2)));
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

})();
</script>