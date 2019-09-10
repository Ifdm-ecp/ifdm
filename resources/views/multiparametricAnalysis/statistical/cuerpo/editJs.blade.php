<script type="text/javascript">    

    function multiparametricoStatistical()
    {
        var count = <?php echo count($errors) ?>;
        var nps = [];
        var d;
        var p50;
        var i = 1;

        if("{{ $statistical->statistical}}" != "Colombia") 
        {
            var dataarray = "{{ $statistical->field_statistical }}";
        } else {
            var dataarray = "Todos";
        }
        $.get("{!! url('P') !!}", 
        {
            campo: dataarray,

        }, 
        function(data) 
        {
            
            $.each(data, function(index, value) 
            {
                nps = [];
                $.each(value, function(index, value) 
                {
                    d = parseFloat(value.valorchart);
                    d = Math.round(d * 100) / 100;
                    nps.push(d);
                });
                tam = 0;
                tam = nps.length;
                //console.log(tam);
                if (tam == 0) {
                    p10 = 0;
                    p50 = 0;
                    p90 = 0;
                } else {
                    p10 = nps[Math.floor(tam * 0.1)];
                    p50 = nps[Math.floor(tam * 0.5)];
                    p90 = nps[Math.floor(tam * 0.9)];
                }
                if (count < 1) {
                    $("#p10_" + i).val(p10);
                    $("#p90_" + i).val(p90);
                }

                $("#popover" + i).popover(
                {

                    placement: 'top',
                    html: 'true',
                    title: '<span class="text-info"><strong>Percentile     </strong></span>',
                    content: '<b>p10: </b>' + p10 + '<br><b>p50: </b>' + p50 + '<br><b>p90: </b>' + p90
                });

                i++;
            });
        });
    }

    function cargarCamposBBDD()
    {
        $('#basin > option[value="{{ $statistical->basin_statistical }}"]').attr('selected', 'selected');
            //Cargar valores de select anidados basados en opcion escogida
            $.get("{{url('fieldbybasin')}}", {
                basin: "{{ $statistical->basin_statistical }}"
            }, function(data) {
                $("#field").empty();

                $.each(data, function(index, value) {
                    $("#field").append('<option value="' + value.id + '">' + value.nombre + '</option>');
                });
                $("#field").selectpicker('refresh');
                var data2 = "{{ $statistical->field_statistical }}";
                var dataarray = data2.split(",");
                $('#field').selectpicker('val', dataarray);
            });

    }

        //Asignar color a input en caso de que un valor este malo
        $('.check_weight').bind('change init', function() {
            var this_name = '#' + $(this).attr('id');
            var this_value_div = '#' + $(this).attr('id') + '_div';
            if ($(this_name).prop('checked')) {
                $(this_name + '_hidden').val("false");
                $(this_value_div + " *").attr('disabled', false);
            } else {
                $(this_value_div + " *").attr('disabled', true);
                $(this_name + '_hidden').val("true");
            }
        }).trigger('init');
    
    $('#save').click(function(e){
        verificarWeight(e); 
        verificarP(e);      
    });

    $('#ocultarSave').click(function(){
        $('#save').hide();
        $('#calculate').show();
    });

     $('.ocultarCalculate').click(function(){
        $('#save').show();
        $('#calculate').hide();
    });


    function verificarWeight(e)
    {
        var arrayMS = [1,2,3,4,5];
        var arrayFB = [6,7,8,9,10];
        var arrayOS = [11,12,13,14];
        var arrayRP = [15,16,17,18];
        var arrayID = [19,20,21,22];
        var arrayGD = [23,24,25,26];

        var sumMS = 0;
        var sumFB = 0;
        var sumOS = 0;
        var sumRP = 0;
        var sumID = 0;
        var sumGD = 0;

        for(var i=0;i<4;i++){
            sumMS = sumMS + parseFloat($('#weight_'+arrayMS[i]+'_value').val());
            sumFB = sumFB + parseFloat($('#weight_'+arrayFB[i]+'_value').val());
            sumOS = sumOS + parseFloat($('#weight_'+arrayOS[i]+'_value').val());
            sumRP = sumRP + parseFloat($('#weight_'+arrayRP[i]+'_value').val());
            sumID = sumID + parseFloat($('#weight_'+arrayID[i]+'_value').val());
            sumGD = sumGD + parseFloat($('#weight_'+arrayGD[i]+'_value').val());
        }

        for(var i=4;i<5;i++){
            sumMS = sumMS + parseFloat($('#weight_'+arrayMS[i]+'_value').val());
            sumFB = sumFB + parseFloat($('#weight_'+arrayFB[i]+'_value').val());
        }     

        if(sumMS > 1){
            e.preventDefault();
            alert('the plus of the input Weight of the section Mineral Scales is older '+sumMS);
        }else if(sumFB > 1){
            e.preventDefault();
            alert('the plus of the input Weight of the section Fine Blockage is older '+sumFB);
        }else if(sumOS > 1){
            e.preventDefault();
            alert('the plus of the input Weight of the section Organic Scales is older '+sumOS);
        }else if(sumRP > 1){
            e.preventDefault();
            alert('the plus of the input Weight of the section Relative Permeability is older '+sumRP);
        }else if(sumID > 1){
            e.preventDefault();
            alert('the plus of the input Weight of the section Induced Damage is older '+sumID);
        }else if(sumGD > 1){
            e.preventDefault();
            alert('the plus of the input Weight of the section Geomechanical Damage is older '+sumGD);
        }
    }

    function verificarP(e)
    {
        //se vacia los errores anteriores
        $('.modal-body ul').html('');

        title = ['Scale Index Of CaCO3', 'Scale Index Of BaSO4', 'Scale Index Of Iron Scales', '[Ca]: Calcium Concentration On Backflow Samples', '[Ba]: Barium Concentration On Backflow Samples', '[Al]: Aluminum Concentration On Produced Water', '[Si]: Silicon Concentration On Produced Water', 'Critical Radius Factor Rc', 'Mineralogic Factor', 'Crushed Proppant Factor', 'CII Factor: Colloidal Instability Index', 'Compositional Factor: Cumulative Gas Produced', 'Pressure Factor: Number Of Days Below Saturation Pressure', 'High Impact Factor: De Boer Criteria', 'Number Of Days Below Saturation Pressure', 'Delta Pressure From Saturation Pressure', 'Water Intrusion: Cumulative Water Produced', 'High Impact Factor:Pore Size Diameter Approximation By Katz And Thompson Correlation', 'Invasion Radius', 'Polymer Damage Factor', 'Induced Skin', 'Mud Damage Factor: Mud Losses', 'Fraction Of NetPay Exhibiting Natural Fractures', 'Drawdown', 'Ratio Of KH + Fracture / KH', 'Geomechanical Damage Expressed As Fraction Of Base Permeability At BHFP'];

        for (var i=1; i<27; i++) {
            var r = parseInt(i)-1;
            var result;
            a = $("#p10_" + i).val();
            b = $("#p90_" + i).val();
            if(a == b)
            {
                e.preventDefault();
                $('.modal-body ul').append('<li>the P10 and P90 of '+title[r]+' they can not be the same.</li>');
                $('#errors').modal('show');
            }

        }
    }   

    function cargarAvailables()
    {
        var ms = {{json_encode($statistical->msAvailable)}};
        var fb = {{json_encode($statistical->fbAvailable)}};
        var os = {{json_encode($statistical->osAvailable)}};     
        var rp = {{json_encode($statistical->rpAvailable)}};     
        var id = {{json_encode($statistical->idAvailable)}};     
        var gd = {{json_encode($statistical->gdAvailable)}}; 

        $.each(ms, function( index, value ) {
            $('#weight_'+ value).prop("checked", true);
            $('#weight_'+ value + '_div *').attr('disabled', false);
        });

        $.each(fb, function( index, value ) {
            var i = parseInt(value)+5;
            $('#weight_'+ i).prop("checked", true);
            $('#weight_'+ i + '_div *').attr('disabled', false);
        });

        $.each(os, function( index, value ) {
            var i = parseInt(value)+10;
            $('#weight_'+ i).prop("checked", true);
            $('#weight_'+ i + '_div *').attr('disabled', false);
        });

        $.each(rp, function( index, value ) {
            var i = parseInt(value)+14;
            $('#weight_'+ i).prop("checked", true);
            $('#weight_'+ i + '_div *').attr('disabled', false);
        });

        $.each(id, function( index, value ) {
            var i = parseInt(value)+18;
            $('#weight_'+ i).prop("checked", true);
            $('#weight_'+ i + '_div *').attr('disabled', false);
        });

        $.each(gd, function( index, value ) {
            var i = parseInt(value)+22;
            $('#weight_'+ i).prop("checked", true);
            $('#weight_'+ i + '_div *').attr('disabled', false);
        });
    }

</script>