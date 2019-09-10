@extends('layouts.general')

@section('title', 'Project management')

@section('sidebar')

    <?php
    if (!isset($_SESSION)) {
        session_start();
    }
    ?>

    <div id="loading" style="display: none;"></div>
    <div class="row">
        <div class="col-sm-12">
            <h2>Projects</h2>
            <div id="treeview-selectable"></div>
            <div id="treeview-selectable-scenarios"></div>
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

            var scenario = [];
            var proyectos;

            var i = 0;
            var j = 0;
            var k = 0;

            var index = 0;

            $.get("{!! url('getSharedScenarios') !!}", {}, function (data) {
                $.each(data, function (index, value) {

                    var str = value.nombre;
                    var str2 = value.id;

                    str = str.replace(/\s/g, "");
                    str = str.toLowerCase();

                    var as = "{{ URL::route('scenarioRep',array('id' => "xx")) }}";
                    as = as.replace("xx", value.id);
                    var color = "#000000";

                    if (value.tipo == "IPR") {
                        //Aquí envíen las variables como las necesitan recibir, recuerden pasarse a request para no tener inconvenientes después.
                        //En este condicional debería enviar de una vez a la curva si el escenario está completo, si no, se va a editar normal

                        if (value.completo == 1) {

                            scenario.push({
                                text: "[IPR]  " + value.nombre,
                                href: as,
                                color: color,
                                tags: ['0']

                            });
                        }
                    }
                    else if (value.tipo == "Multiparametric") {
                        if (value.completo == 1) {
                            scenario.push({
                                text: "[MP]  " + value.nombre,
                                href: as,
                                color: color,
                                tags: ['0']
                            });
                        }
                    }
                    else if (value.tipo == "Drilling") {
                        if (value.completo == 1) {
                            var as = "{{ URL::route('scenarioRep',array('id' => "xx")) }}";
                            as = as.replace("xx", value.id);
                            var color = "#000000";

                            scenario.push({
                                text: "[D]  " + value.nombre,
                                href: as,
                                color: color,
                                tags: ['0']
                            });
                        }
                    }

                    else if (value.tipo == "Asphaltene precipitation") {
                        if (value.estado == 1) {
                            var as = "{{ URL::route('scenario_report.show_asphaltene_report',"xxxxxx") }}";
                            as = as.replace("xxxxxx", value.id);
                            var color = "#000000";

                            scenario.push({
                                text: "[A]  " + value.nombre,
                                href: as,
                                color: color,
                                tags: ['0']
                            });
                        }
                    } else if (value.tipo == "Swelling and fines migration") {
                        if (value.completo == 1) {
                            var as = "{{ URL::route('scenario_report.show_fines_report',"xxxxxx") }}";
                            as = as.replace("xxxxxx", value.id);
                            var color = "#000000";
                            scenario.push({
                                text: "[F]  " + value.nombre,
                                href: as,
                                color: color,
                                tags: ['0']
                            });
                        }
                    } else if (value.tipo == "Asphaltene remediation") {
                        if (value.estado == 1) {
                            var as = "{{ URL::route('scenario_report.show_asphaltene_report',"xxxxxx") }}";
                            as = as.replace("xxxxxx", value.id);
                            var color = "#000000";

                            scenario.push({
                                text: "[AR]  " + value.nombre,
                                href: as,
                                color: color,
                                tags: ['0']
                            });
                        }
                    } else if (value.tipo == "Fines remediation") {
                        if (value.estado == 1) {
                            var as = "{{ URL::route('scenario_report.show_asphaltene_report',"xxxxxx") }}";
                            as = as.replace("xxxxxx", value.id);
                            var color = "#000000";

                            scenario.push({
                                text: "[FR]  " + value.nombre,
                                href: as,
                                color: color,
                                tags: ['0']
                            });
                        }
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

                            str = str.replace(/\s/g, "");
                            str = str.toLowerCase();

                            var as = "{{ URL::route('scenarioRep',array('id' => "xx")) }}";
                            as = as.replace("xx", value.id);
                            var color = "#000000";

                            if (value.tipo == "IPR") {
                                //Aquí envíen las variables como las necesitan recibir, recuerden pasarse a request para no tener inconvenientes después.
                                //En este condicional debería enviar de una vez a la curva si el escenario está completo, si no, se va a editar normal

                                if (value.completo == 1) {
                                    esc.push({
                                        text: "[IPR]  " + value.nombre,
                                        href: as,
                                        color: color,
                                        tags: ['0']
                                    });
                                }
                            }
                            else if (value.tipo == "Multiparametric") {
                                if (value.completo == 1) {
                                    esc.push({
                                        text: "[MP]  " + value.nombre,
                                        href: as,
                                        color: color,
                                        tags: ['0']
                                    });
                                }
                            }
                            else if (value.tipo == "Drilling") {
                                if (value.completo == 1) {

                                    var as = "{{ URL::route('scenarioRep',array('id' => "xx")) }}";
                                    as = as.replace("xx", value.id);
                                    var color = "#000000";

                                    esc.push({
                                        text: "[D]  " + value.nombre,
                                        href: as,
                                        color: color,
                                        tags: ['0']
                                    });
                                }
                            }

                            else if (value.tipo == "Asphaltene precipitation") {
                                if (value.estado == 1) {
                                    var as = "{{ URL::route('scenario_report.show_asphaltene_report',"xxxxxx") }}";
                                    as = as.replace("xxxxxx", value.id);
                                    var color = "#000000";

                                    esc.push({
                                        text: "[A]  " + value.nombre,
                                        href: as,
                                        color: color,
                                        tags: ['0']
                                    });
                                }
                            } else if (value.tipo == "Swelling and fines migration") {
                                if (value.completo == 1) {
                                    var as = "{{ URL::route('scenario_report.show_fines_report',"xxxxxx") }}";
                                    as = as.replace("xxxxxx", value.id);
                                    var color = "#000000";
                                    esc.push({
                                        text: "[F]  " + value.nombre,
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

                            proyectos.push({
                                text: value.nombre,
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
                            nodes: scenario
                        });
                    }
                    arbol(datos);
                });


            function arbol(data) {
                var nodo_s;
                var initSelectableTree = function () {
                    return $('#treeview-selectable').treeview({
                        data: data,
                        enableLinks: true,
                        multiSelect: $('#chk-select-multi').is(':checked'),
                        onNodeSelected: function (event, node) {
                            //document.getElementById('loading').style.display = 'block';
                            nodo_s = node.text;
                            document.getElementById("project").value = nodo_s;
                        },
                    });
                };
                var $selectableTree = initSelectableTree();
                $('#treeview-selectable').treeview('collapseAll', {silent: true});
            }

        });
    </script>
    <!--
<div class="row">
  <div class="col-xs-6 sidebar-offcanvas" id="sidebar">
    <a href="{{ URL::route('Project') }}" class="btn btn-primary  btn-block">Add project</a>
  </div>
  <div class="col-xs-6 sidebar-offcanvas" id="sidebar">
    {!! link_to('ScenaryC', 'Add scenary',array('class' => 'btn btn-primary  btn-block'))!!}
            </div>
          </div>
          </br>
-->

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
