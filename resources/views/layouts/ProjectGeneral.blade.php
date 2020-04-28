@extends('layouts.general')

@section('title', 'Project management')

@section('sidebar')
<?php if (!isset($_SESSION)) { session_start(); } ?>

<div id="loading_icon" style="display: none;z-index: 9999999 !important;">
    <img src="{!! asset('images/loading.gif') !!}" style="z-index: 9999999 !important;" class="loading">
</div>
<div id="loading" style="display: none;"></div>
<div class="row">
    <div class="col-sm-12">
        <h2>Projects</h2>
        <div id="treeview-selectable"></div>
    </div>
</div>

<script src="{!! asset('bower_components/bootstrap-treeview/src/js/bootstrap-treeview.js') !!}"></script>

<script type="text/javascript">
    $(function () {
        var url = window.location;
        var id = <?php echo \Auth::User()->id; ?>;
        var office = <?php echo \Auth::User()->office; ?>;
        $('ul.nav a[href="' + url + '"]').parent().addClass('active');

        $('ul.nav a').filter(function () {
            return this.href == url;
        }).parent().addClass('active').parent().parent().addClass('active');

        var usuario = id;
        var datos = [];
        var compañias = [];
        var escenarios = [];
        var proyectos;

        var i = 0;
        var j = 0;
        var k = 0;

        var shared_scenarios = [];
        var scenarios = [];

        $.get("{!! url('getSharedScenarios') !!}", {}, function (data) {
            $.each(data, function (index, value) {
                var str = value.nombre;
                var str2 = value.id;

                str = str.replace(/\s/g, "");
                str = str.toLowerCase();

                if (value.tipo == "IPR") {

                    /* Aquí envíen las variables como las necesitan recibir, recuerden pasarse a request para no tener inconvenientes después. */
                    /*  En este condicional debería enviar de una vez a la curva si el escenario está completo, si no, se va a editar normal */

                    if (value.completo == 1) {
                        var as = "{{ url('IPR/result',array('id' => "xx")) }}";
                        var color = "#000000";
                    } else {
                        var as = "{{ URL::route('IPR.edit',array('id' => "xx")) }}";
                        var color = "#ff1b00";
                    }

                    as = as.replace("xx", value.id);
                    scenarios.push({
                        text: "[IPR]  " + value.nombre,
                        href: as,
                        color: color,
                        tags: ['0']
                    });

                } else if (value.tipo == "Dissagregation") {

                    /* Aquí envíen las variables como las necesitan recibir, recuerden pasarse a request para no tener inconvenientes después. */
                    /*  En este condicional debería enviar de una vez a la curva si el escenario está completo, si no, se va a editar normal */

                    if (value.completo == 1) {
                        var as = "{{ url('Desagregacion/xx') }}";
                        var color = "#000000";
                    } else {
                        var as = "{{ URL::route('Desagregacion.show',array('id' => "xx")) }}";
                        var color = "#ff1b00";
                    }

                    as = as.replace("xx", value.id);
                    scenarios.push({
                        text: "[SDA]  " + value.nombre,
                        href: as,
                        color: color,
                        tags: ['0']
                    });

                } else if (value.tipo == "Multiparametric") {

                    if (value.completo == 1) {
                        var as = "{{ URL::route('multiparametrico',array('id' => "xx")) }}";
                        as = as.replace("xx", value.id);
                        var color = "#000000";
                    } else {
                        var as = "{{ URL::route('ScenaryC.edit',"xxxxxx") }}";
                        as = as.replace("xxxxxx", value.id);
                        var color = "#ff1b00";
                    }

                    scenarios.push({
                        text: "[MP]  " + value.nombre,
                        href: as,
                        color: color,
                        tags: ['0']
                    });

                } else if (value.tipo == "Drilling") {

                    if (value.completo == 1) {
                        var as = "{{ URL::route('drilling.result',array('id' => "xx")) }}";
                        as = as.replace("xx", value.id);
                        var color = "#000000";
                    } else {
                        var as = "{{ URL::route('drilling.edit',"xxxxxx") }}";
                        as = as.replace("xxxxxx", value.id);
                        var color = "#ff1b00";
                    }

                    scenarios.push({
                        text: "[D]  " + value.nombre,
                        href: as,
                        color: color,
                        tags: ['0']
                    });

                } else if (value.tipo == "Asphaltene precipitation") {

                    if (value.completo == 1) {
                        var as = "{{ URL::route('ScenaryC.edit',"xxxxxx") }}";
                        as = as.replace("xxxxxx", value.id);
                        var color = "#000000";
                    } else {
                        var as = "{{ URL::route('ScenaryC.edit',"xxxxxx") }}";
                        as = as.replace("xxxxxx", value.id);
                        var color = "#ff1b00";
                    }

                    if(value.asphaltene_type == "Asphaltene stability analysis") {
                        esc.push({
                            text: "[A_S]  " + value.nombre,
                            href: as,
                            color: color,
                            tags: ['0']
                        });
                    } else if(value.asphaltene_type == "Precipitated asphaltene analysis") {
                        esc.push({
                            text: "[A_P]  " + value.nombre,
                            href: as,
                            color: color,
                            tags: ['0']
                        });
                    } else if(value.asphaltene_type == "Asphaltene diagnosis") {
                        esc.push({
                            text: "[A_D]  " + value.nombre,
                            href: as,
                            color: color,
                            tags: ['0']
                        });
                    }

                } else if (value.tipo == "Swelling and fines migration") {

                    if (value.completo == 1) {
                        var as = "{{ URL::route('finesMigrationDiagnosis.show_results',"xxxxxx") }}";
                        as = as.replace("xxxxxx", value.id);
                        var color = "#000000";
                    } else {
                        var as = "{{ URL::route('ScenaryC.edit',"xxxxxx") }}";
                        as = as.replace("xxxxxx", value.id);
                        var color = "#ff1b00";
                    }

                    scenarios.push({
                        text: "[F]  " + value.nombre,
                        href: as,
                        color: color,
                        tags: ['0']
                    });

                } else if (value.tipo == "Geomechanics") {

                    if (value.completo == 1) {
                        var as = "{{ URL::to('geomechanical_diagnosis/' . "xx" . '/edit') }}";
                        as = as.replace("xx", value.id);
                        var color = "#000000";
                    } else {
                        var as = "{{ route('geomechanical_diagnosis.results_from_tree', "xx") }}";
                        as = as.replace("xx", value.id);
                        var color = "#ff1b00";
                    }

                    scenarios.push({
                        text: "[G]  " + value.nombre,
                        href: as,
                        color: color,
                        tags: ['0']
                    });

                } else if(value.tipo == "Fines Treatment Selection") {

                    if(value.completo==1) {
                        var as = "{{ route('fts.show', "xx") }}";
                        as = as.replace("xx",value.id);
                        var color = "#000000";
                    } else {
                        var as =  "{{ route('fts.edit', "xx") }}";
                        as = as.replace("xx",value.id);
                        var color = "#ff1b00";
                    }
                    esc.push({
                        text: "[FTS]  "+value.nombre,
                        href: as ,
                        color:color,
                        tags: ['0']
                    }); 

                }  else if(value.tipo == "Fines remediation") {

                    if(value.completo==1) {
                        var as = "{{ url('finesremediation/results', "xx") }}";
                        as = as.replace("xx",value.id);
                        var color = "#000000";
                    } else {
                        var as =  "{{ url('finesremediation/edit', "xx") }}";
                        as = as.replace("xx",value.id);
                        var color = "#ff1b00";
                    }
                    esc.push({
                        text: "[FR]  "+value.nombre,
                        href: as ,
                        color:color,
                        tags: ['0']
                    }); 

                }  else if(value.tipo == "Asphaltene remediation") {

                    if(value.completo==1) {
                        var as = "{{ url('asphalteneremediation/results', "xx") }}";
                        as = as.replace("xx",value.id);
                        var color = "#000000";
                    } else {
                        var as =  "{{ url('asphalteneremediation/edit', "xx") }}";
                        as = as.replace("xx",value.id);
                        var color = "#ff1b00";
                    }
                    esc.push({
                        text: "[AR]  "+value.nombre,
                        href: as ,
                        color:color,
                        tags: ['0']
                    }); 

                } else if (value.tipo == "Dissagregation") {
                    if (value.completo == 1) {
                        var as = "{{ URL::route('desagregacion.show_results',"xxxxxx") }}";
                        as = as.replace("xxxxxx", value.id);
                        var color = "#000000";
                    } else {
                        var as = "{{ URL::route('ScenaryC.edit',"xxxxxx") }}";
                        as = as.replace("xxxxxx", value.id);
                        var color = "#ff1b00";
                    }

                    scenarios.push({
                        text: "[DA]  " + value.nombre,
                        href: as,
                        color: color,
                        tags: ['0']
                    });
                }

            }); 
});

$.get("{!! url('arbol') !!}",
    {usuario: usuario},
    function (data) {
        $.each(data.Compañias, function (index, value) {
            compañias.push(value);
        });

        $.each(data.Escenarios, function (index, value) {
            var esc = [];
            $.each(value, function (index, value) {

                var str = value.nombre;
                var str2 = value.id;
                var res = value.res_;

                var nnombre = value.nombre;
                if (nnombre.length > 17) {
                    var nnombre_subs = value.nombre.substring(0, 17) + '...';
                } else {
                    var nnombre_subs = value.nombre;
                }

                str = str.replace(/\s/g, "");
                str = str.toLowerCase();

                if (value.tipo == "IPR") {

                    /* Aquí envíen las variables como las necesitan recibir, recuerden pasarse a request para no tener inconvenientes después. */
                    /* En este condicional debería enviar de una vez a la curva si el escenario está completo, si no, se va a editar normal */

                    if (value.completo == 1) {

                        var as = "{{ url('IPR/result',array('id' => "xx")) }}";
                        var color = "#000000";

                        as = as.replace("xx", value.id);
                        esc.push({
                            text: "[IPR] <span title='"+nnombre+"'>" + nnombre_subs + "</span>",
                            href: as,
                            color: color,
                            tags: ['0']
                        });

                    } else {


                        var as = "{{ url('IPR/edit',array('id' => "xx")) }}";
                        var color = "#ff1b00";

                        if (res == "1") {
                            var as = "{{ URL::route('ScenaryC.edit',"xx") }}";
                        }

                        as = as.replace("xx", value.id);
                        esc.push({
                            text: "[IPR] <span title='"+nnombre+"'>" + nnombre_subs + "</span>",
                            href: as,
                            color: color,
                            tags: ['0']
                        });
                    }

                } else if (value.tipo == "Dissagregation") {

                    /* Aquí envíen las variables como las necesitan recibir, recuerden pasarse a request para no tener inconvenientes después. */
                    /* En este condicional debería enviar de una vez a la curva si el escenario está completo, si no, se va a editar normal */

                    if (value.completo == 1) {

                        var as = "{{ url('Desagregacion/show', array('id' => "xx")) }}";
                        var color = "#000000";

                        as = as.replace("xx", value.id);
                        esc.push({
                            text: "[SDA] <span title='"+nnombre+"'>" + nnombre_subs + "</span>",
                            href: as,
                            color: color,
                            tags: ['0']
                        });

                        /*
                        var as = "{{ url('Desagregacion/xx/edit') }}";
                        var color = "#000000";

                        as = as.replace("xx", value.id);
                        esc.push({
                            text: "[SDA] <span title='"+nnombre+"'>" + nnombre_subs + "</span>",
                            href: as,
                            color: color,
                            tags: ['0']
                        });
                        */

                    } else {


                        var as = "{{ url('Desagregacion/edit',array('id' => "xx")) }}";
                        var color = "#ff1b00";

                        if (res == "1") {
                            var as = "{{ URL::route('ScenaryC.edit',"xx") }}";
                        }

                        as = as.replace("xx", value.id);
                        esc.push({
                            text: "[SDA] <span title='"+nnombre+"'>" + nnombre_subs + "</span>",
                            href: as,
                            color: color,
                            tags: ['0']
                        });
                    }

                } else if (value.tipo == "Multiparametric") {

                    if (value.completo == 1) {

                        var as = '';
                        if(value.multiparametricType == 'statistical') {
                            var titulo = "[MPS]  ";
                            var as = "{{ URL::route('statistical.show',array('id' => "xx")) }}";
                        } else if(value.multiparametricType == 'analytical') {
                            var titulo = "[MPA]  ";
                            var as = "{{ URL::route('analytical.show',array('id' => "xx")) }}";
                        } else if(value.multiparametricType == 'completeMultiparametric'){
                            var titulo = "[MPC]  ";
                            var as = "{{ URL::route('completeMultiparametric.show',array('id' => "xx")) }}";
                        }
                        as = as.replace("xx", value.id);
                        var color = "#000000";

                    } else {

                        var as = "{{ URL::route('ScenaryC.edit',"xxxxxx") }}";
                        as = as.replace("xxxxxx", value.id);
                        var color = "#ff1b00";
                        if(value.multiparametricType == 'statistical') {
                            var titulo = "[MPS] ";
                        } else if(value.multiparametricType == 'analytical') {
                            var titulo = "[MPA] ";
                        } else if(value.multiparametricType == 'completeMultiparametric') {
                            var titulo = "[MPC] ";
                        }

                    }

                    esc.push({
                        text:  titulo + "<span title='"+nnombre+"'>" + nnombre_subs + "</span>",
                        href: as,
                        color: color,
                        tags: ['0']
                    });

                } else if (value.tipo == "Drilling") {

                    if (value.completo == 1) {
                        var as = "{{ URL::route('drilling.result',array('id' => "xxxxxx")) }}";
                        var color = "#000000";
                    } else {
                        var as = "{{ URL::route('drilling.edit',"xxxxxx") }}";
                        var color = "#ff1b00";
                    }

                    as = as.replace("xxxxxx", value.id);
                    esc.push({
                        text: "[D] <span title='"+nnombre+"'>" + nnombre_subs + "</span>",
                        href: as,
                        color: color,
                        tags: ['0']
                    });

                } else if (value.tipo == "Asphaltene precipitation") {

                    var as = "{{ URL::route('ScenaryC.edit',"xxxxxx") }}";
                    var color = "#000000";

                    as = as.replace("xxxxxx", value.id);

                    var nodes_child = [];
                    $.each(value.extra_,function(index,val){
                        nodes_child.push({
                            text: val.nombre + " <span title='"+nnombre+"'>" + nnombre_subs + "</span>",
                            href: val.route,
                            color: val.status_wr == 0 ? "#000000" : "#ff1b00",
                            tags: ['0'],
                        });
                    });

                    if (nodes_child.length > 0) {

                        esc.push({
                            text: "<span title='"+nnombre+"'>" + nnombre_subs + "</span>",
                            color: value.completo == 1 ? "#000000" : "#ff1b00",
                            tags: ['0'],
                            nodes: nodes_child
                        });
                    } else {
                        esc.push({
                            text: "<span title='"+nnombre+"'>" + nnombre_subs + "</span>",
                            href: as,
                            color: color,
                            tags: ['0'],
                        });
                    }


                } else if (value.tipo == "Swelling and fines migration") {

                    if (value.completo == 1) {
                        var as = "{{ URL::route('finesMigrationDiagnosis.show_results',"xxxxxx") }}";
                        var color = "#000000";
                    } else {
                        var as = "{{ URL::route('finesMigrationDiagnosis.edit',"xxxxxx") }}";
                        var color = "#ff1b00";
                    }

                    as = as.replace("xxxxxx", value.id);
                    esc.push({
                        text: "[F] <span title='"+nnombre+"'>" + nnombre_subs + "</span>",
                        href: as,
                        color: color,
                        tags: ['0']
                    });

                } else if (value.tipo == "Geomechanics") {

                    if (value.completo == 1) {
                        var as = "{{ URL::to('geomechanical_diagnosis/' . "xx" . '/edit') }}";
                        var color = "#000000";

                    } else {

                        var as = "{{ route('geomechanical_diagnosis.results_from_tree', "xx") }}";
                        var color = "#ff1b00";

                        if (res == 1) {
                            as = "{{ URL::route('ScenaryC.edit',"xx") }}";
                        }

                    }

                    as = as.replace("xx", value.id);
                    esc.push({
                        text: "[G] <span title='"+nnombre+"'>" + nnombre_subs + "</span>",
                        href: as,
                        color: color,
                        tags: ['0']
                    });

                } else if(value.tipo == "Fines Treatment Selection") {

                    if(value.completo==1) {
                        var as = "{{ route('fts.show', "xx") }}";
                        var color = "#000000";
                    } else {

                        var as =  "{{ route('fts.edit', "xx") }}";
                        var color = "#ff1b00";

                        if (res == "1") {
                            as = "{{ URL::route('ScenaryC.edit',"xx") }}";
                        }

                    }

                    as = as.replace("xx", value.id);
                    esc.push({
                        text: "[FTS] <span title='"+nnombre+"'>" + nnombre_subs + "</span>",
                        href: as,
                        color: color,
                        tags: ['0']
                    });

                } else if(value.tipo == "Fines remediation") {

                    if(value.completo == 1) {

                        var as = "{{ url('finesremediation/results', "xx") }}";
                        var color = "#000000";

                    } else {

                        var as =  "{{ url('finesremediation/edit', "xx") }}";
                        var color = "#ff1b00";

                        if (res == 1) {
                            as = "{{ URL::route('ScenaryC.edit',"xx") }}";
                        }

                    }

                    as = as.replace("xx", value.id);
                    esc.push({
                        text: "[FR] <span title='"+nnombre+"'>" + nnombre_subs + "</span>",
                        href: as,
                        color: color,
                        tags: ['0']
                    });

                } else if(value.tipo == "Asphaltene remediation") {
                    if(value.completo==1) {
                        var as = "{{ url('asphalteneremediation/results', "xx") }}";
                        as = as.replace("xx",value.id);
                        var color = "#000000";

                        esc.push({
                            text: "[AR] <span title='"+nnombre+"'>" + nnombre_subs + "</span>",
                            href: as ,
                            color:color,
                            tags: ['0']
                        });
                    } else {

                        var as =  "{{ url('asphalteneremediation/edit', "xx") }}";
                        var color = "#ff1b00";

                        if (res == 1) {
                            as = "{{ URL::route('ScenaryC.edit',"xx") }}";
                        }

                        as = as.replace("xx", value.id);
                        esc.push({
                            text: "[AR] <span title='"+nnombre+"'>" + nnombre_subs + "</span>",
                            href: as,
                            color: color,
                            tags: ['0']
                        });
                    }

                } 

            });
escenarios[j] = esc;
j++;
});


$.each(data.ProyectosxCompanias, function (index, value) {

    var proyectos = [];

    $.each(value, function (index, value) {

        var str = value.nombre;
        var str2 = value.id;

        str = str.replace(/\s/g, "");
        str = str.toLowerCase();

        var nnombre = value.nombre;
        if (nnombre.length > 20) {
            var nnombre_subs = value.nombre.substring(0, 20) + '...';
        } else {
            var nnombre_subs = value.nombre;
        }

        var as = "{{ URL::route('ProjectC.edit',"xxxxxx") }}";
        as = as.replace("xxxxxx", value.id);

        proyectos.push({
            text: "<span title='"+nnombre+"'>" + nnombre_subs + "</span>",
            href: as,
            tags: ['0'],
            nodes: escenarios[k]
        });
        k++;
    });
    if (compañias[i] != "") {
        datos.push({
            text: compañias[i],
            tags: ['0'],
            nodes: proyectos
        });
    }
    i++;
});

if (office != 0) {
    datos.push({
        text: "Shared Scenarios",
        tags: ['0'],
        nodes: scenarios
    });
}
arbol(datos);
});

function arbol(data){
    var initSelectableTree = function() {
        return $('#treeview-selectable').treeview({ 
            data: data,
            enableLinks:true,
            multiSelect: $('#chk-select-multi').is(':checked'), 
            onNodeSelected: function(event, node) {
                $('#treeview-selectable').treeview('toggleNodeExpanded', [node.nodeId]);
            }
        });
    };

    var $selectableTree = initSelectableTree();
    $('#treeview-selectable').treeview('collapseAll', { silent: true });

}
});
</script>

<style>
#loading {
    width: 50px;
    height: 50px;
    border: 5px solid #ccc;
    border-top-color: #ff6a00;
    border-radius: 100%;
    position: fixed;
    left: 0;
    right: 0;
    top: 0;
    bottom: 0;
    margin: auto;
    animation: round 2s linear infinite;
    z-index: 9999;
}

.loading {
    position:   fixed;
    z-index:    1000;
    top:        50%;
    left:       50%;
    height:     auto;
    width:      30%;
    transform: translate(-50%, -50%);
    background: rgba( 255, 255, 255, .8 ) 100% 100% no-repeat;
}

@keyframes round {
    from {
        transform: rotate(0deg)
    }
    to {
        transform: rotate(360deg)
    }
}
</style>
@endsection
