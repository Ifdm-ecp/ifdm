
<script src="https://code.highcharts.com/maps/modules/map.js"></script>
<script src="https://rawgithub.com/paulo-raca/highcharts-contour/master/highcharts-contour.js"></script>
<script src="https://rawgithub.com/ironwallaby/delaunay/master/delaunay.js"></script>
<script src="https://code.highcharts.com/modules/annotations.js"></script>

<script src="https://code.highcharts.com/modules/series-label.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>

<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>

<script type="text/javascript">

  /*  global fracture/bhfp index*/

  @if(is_null($geomechanical_diagnosis->status_wr) || !$geomechanical_diagnosis->status_wr)
  let fracture_select_value;
  let max_radius = {!!json_encode($max_radius)!!};
  let max_depth = {!!json_encode($max_depth)!!};
  let min_depth = {!!json_encode($min_depth)!!};
  let fracture_permeability = {!!json_encode($fracture_permeability)!!};
  {{-- let fracture_width = {!!json_encode($fracture_width)!!}; --}}
  let normal_stress = {!!json_encode($normal_stress)!!};
  let depths = {!!json_encode($depths)!!};
  let fracture_permeability_given_radius = {!!json_encode($fracture_permeability_given_radius)!!};
  let fracture_width_given_radius = {!!json_encode($fracture_width_given_radius)!!};
  let fracture_permeability_given_theta = {!!json_encode($fracture_permeability_given_theta)!!};
  let fracture_width_given_theta = {!!json_encode($fracture_width_given_theta)!!};
  let thetas = {!!json_encode($thetas)!!};
  let radii = {!!json_encode($radii)!!};
  {{-- let average_permeability_only_fractures = {!!json_encode($average_permeability_only_fractures)!!}; --}}
  let fracture_lines = {!!json_encode($fracture_lines)!!};
  let well_radius = parseFloat({!!json_encode($well_radius)!!});
  let Sr = {!! json_encode($Sr_graph) !!};
  let St = {!! json_encode($St_graph) !!};
  let Sz = {!! json_encode($Sz_graph) !!};
  let max_azimuth_index = {!! json_encode($azimuth_maximum_horizontal_stress_index) !!};
  let fracture_permeability_vs_stress_given_theta = {!! json_encode($fracture_permeability_vs_stress_given_theta) !!};
  let max_azimuth_line = JSON.parse({!! json_encode($max_azimuth_line) !!});

  window.onload = function() {

    var fractures = {!!json_encode($fracture_depth_array)!!};
    var p_pressures = {!!json_encode($complete_pore_pressures)!!};

    let pore_pressure = [];
    p_pressures.forEach((array) => {
      pore_pressure.push(JSON.parse(array));
    });

    var index_fracture = 0;
    var index_interval = 0;

    for (var j = 0; j < fractures.length; j++) {
      $("#fracture_select").append('<option value="'+index_fracture+'"> <b>Fracture Depth:</b> '+fractures[j]+' [ft]</option>');
      index_fracture++;
    }

    index_fracture = 0;
    for (var j = 0; j < fractures.length; j++) {
      $("#fracture_depth_select").append('<option value="'+index_fracture+'"> '+(index_fracture+1)+' </option>');
      index_fracture++;
    }

    $("#fracture_select").selectpicker('refresh');
    $('#fracture_select').selectpicker('val', '');

    $("#interval_select").selectpicker('refresh');
    $('#interval_select').selectpicker('val', '');

    $("#fracture_depth_select").selectpicker('refresh');
    $('#fracture_depth_select').selectpicker('val', '');

    create_line_chart("pore_pressure_chart",pore_pressure, "Pore Pressure [psi]");

    /* Initial graph fracture lines*/
    depth = $("#depth_range").val();
    let fracture_lines_query = fracture_lines[depth - min_depth];

    if (fracture_lines_query.length == 0) {
      $("#fracture_spatial_distribution_chart").html("<h2> No fractures at this depth </h2>");
    } else {
      let series = [];
      fracture_lines_query.forEach((element) => {
        series.push({
          name: element[1],
          data: element[0]
        });
      });
      series.push(max_azimuth_line);
      fractures_lines_chart("fracture_spatial_distribution_chart", series, max_radius, "Fractures", well_radius);
    }

    /* Initial graph effective stresses*/
    depth = $("#depth_range_effective_stresses").val();
    select_angle = $("#thetas_effective_stresses").val();
    /* select_bhfp = $("#bhfp_effective_stresses").val();*/
    let aux1= [];
    let aux2= [];
    let aux3= [];
    for (let i = 0; i < radii.length; i++) {
      aux1.push([radii[i], Sr[depth-min_depth][select_angle][i]])
      aux2.push([radii[i], St[depth-min_depth][select_angle][i]])
      aux3.push([radii[i], Sz[depth-min_depth][select_angle][i]])
    }
    let series = [];
    series.push( {
      name: "Sr",
      data: aux1
    });

    series.push({
      name: "St",
      data: aux2
    });

    series.push({
      name: "Sz",
      data: aux3
    });

    seriesPlot("effective_stresses_chart", series, "Effective Stresses [psi]", "Radius [ft]");
  }

  $("#depth_range_effective_stresses").change(function() {
    graph_stresses();
  });

  $("#thetas_effective_stresses").change(function() {
    graph_stresses();
  });

  $("#bhfp_effective_stresses").change(function() {
    graph_stresses();
  });

  function graph_stresses() {
    depth = $("#depth_range_effective_stresses").val();
    $("#depth_value_effective_stresses").html( ":   " + depth + " ft");
    select_angle = $("#thetas_effective_stresses").val();
    /* select_bhfp = $("#bhfp_effective_stresses").val();*/
    let aux1= [];
    let aux2= [];
    let aux3= [];
    for (let i = 0; i < radii.length; i++) {
      aux1.push([radii[i], Sr[depth-min_depth][select_angle][i]])
      aux2.push([radii[i], St[depth-min_depth][select_angle][i]])
      aux3.push([radii[i], Sz[depth-min_depth][select_angle][i]])
    }

    let series = [];
    series.push({
      name: "Sr",
      data: aux1
    });

    series.push({
      name: "St",
      data: aux2
    });

    series.push({
      name: "Sz",
      data: aux3
    });

    seriesPlot("effective_stresses_chart", series, "Effective Stresses [psi]", "Radius [ft]");
  }

  $("#depth_range").change(function() {
    depth = $("#depth_range").val();
    $("#depth_value").html( ":   " + depth + " ft");
    let fracture_lines_query = fracture_lines[depth - min_depth];

    if (fracture_lines_query.length == 0) {
      $("#fracture_spatial_distribution_chart").html("<h2> No fractures at this depth </h2>");
    } else {
      let series = [];
      fracture_lines_query.forEach((element) => {
        series.push({
          name: element[1],
          data: element[0]
        });
      });
      series.push(max_azimuth_line);
      fractures_lines_chart("fracture_spatial_distribution_chart", series, max_radius, "Fractures", well_radius);
    }
  });

  $("#fracture_select").change(function(e) {
    fracture_select_value = $("#fracture_select").val();
    $("#kfracture_polar_chart").html("");
    /* $("#wfracture_polar_chart").html("");*/
    $("#permeability_radius_graph").html("");
    $("#width_radius_graph").html("");
    $("#permeability_theta_graph").html("");
    $("#width_theta_graph").html("");

    create_polar_chart("kfracture_polar_chart",fracture_permeability[fracture_select_value], max_radius, "Fracture Permeability [mD]", well_radius);
    /* create_polar_chart("wfracture_polar_chart",fracture_width[fracture_select_value], max_radius, "Fracture Width [µm]", well_radius);*/
    create_polar_chart("normal_stress_polar_chart",normal_stress[fracture_select_value], max_radius, "Fracture Effective Normal Stress [psi]", well_radius);
    $("#2d_graphs_by_fracture").show();
  });

  $("#fracture_depth_select").change(function(e) {
    fracture_depth_select_value = $("#fracture_depth_select").val();
    $("#fracture_depth_chart").html("");

    create_polar_chart("fracture_depth_chart",depths[fracture_depth_select_value], max_radius, "Fracture Depth [ft]", well_radius);
    $("#2d_graphs_by_fracture").show();
  });

  $("#permeability_radius_select").change(function(e) {
    var permeability_radius_select_values = $("#permeability_radius_select").val();
    let series = [];
    permeability_radius_select_values.forEach((element) => {
      series.push({
        name: String(thetas[element]) + "°",
        data: fracture_permeability_given_theta[fracture_select_value][element]
      });
    });
    seriesPlot("permeability_radius_graph", series, "Fracture Permeability [mD]", "Radius [ft]");
  });

  $("#width_radius_select").change(function(e){
    var width_radius_select_values = $("#width_radius_select").val();
    let series = [];
    width_radius_select_values.forEach((element) => {
      series.push({
        name: String(thetas[element]) + "°",
        data: fracture_width_given_theta[fracture_select_value][element]
      });
    });
    seriesPlot("width_radius_graph", series, "Fracture Width [µm]", "Radius [ft]");
  });

  $("#normal_stress_radius_select").change(function(e){
    var normal_stress_radius_select_values = $("#normal_stress_radius_select").val();
    let series = [];
    normal_stress_radius_select_values.forEach((element) => {
      series.push({
        name: String(thetas[element]) + "°",
        data: fracture_permeability_vs_stress_given_theta[fracture_select_value][element]
      });
    });
    seriesPlot("permeability_normal_stress_graph", series, "Fracture Permeability [mD]", "Effective Normal Stress [psi]");
  });

  $("#permeability_theta_select").change(function(e){
    var permeability_theta_select_values = $("#permeability_theta_select").val();
    let series = [];
    permeability_theta_select_values.forEach((element) => {
      series.push({
        name: String(radii[element]) + "ft",
        data: fracture_permeability_given_radius[fracture_select_value][element]
      });
    });
    seriesPlot("permeability_theta_graph", series, "Fracture Permeability [mD]", "Angle [°]");
  });

  $("#width_theta_select").change(function(e){
    var width_theta_select_values = $("#width_theta_select").val();
    let series = [];
    width_theta_select_values.forEach((element) => {
      series.push({
        name: String(radii[element]) + "ft",
        data: fracture_width_given_radius[fracture_select_value][element]
      });
    });
    seriesPlot("width_theta_graph", series, "Fracture Width [µm]", "Angle [°]");
  });
  function fractures_lines_chart(div, data, axis_value, title, well_radius) {

    let circleX = 0;
    let circleY = 0;


    function addCircle(chart, radius) {

      var pixelX = chart.xAxis[0].toPixels(circleX);
      var pixelY = chart.yAxis[0].toPixels(circleY);
      var pixelR = chart.xAxis[0].toPixels(radius) - chart.xAxis[0].toPixels(0);        

      this.circle = chart.renderer.circle(pixelX, pixelY, pixelR).attr({
        fill: '#FFFFFF',
        stroke: 'black',
        'stroke-width': 1
      });
      this.circle.add();
      this.circle.toFront();
    }

    function addCircleMax(chart, radius) {

      var pixelX = chart.xAxis[0].toPixels(circleX);
      var pixelY = chart.yAxis[0].toPixels(circleY);
      var pixelR = chart.xAxis[0].toPixels(radius) - chart.xAxis[0].toPixels(0);        

      this.circle = chart.renderer.circle(pixelX, pixelY, pixelR).attr({
        fill: '#FFFFFF',
        stroke: 'black',
        'stroke-width': 1
      });
      this.circle.add();
    }

    Highcharts.chart(div, {
      chart: {
        type: "line", 
        height: (10 / 10 * 100) + '%',
        zoomType: "xy",
        events: {
          load: function(){
            /*addCircleMax(this, max_radius);*/
            addCircle(this, well_radius); 

          },
          redraw: function(){
            /*addCircleMax(this, max_radius);*/
            addCircle(this, well_radius);

          }
        }
      },
      tooltip: {
        enabled: false
      },
      plotOptions: {
        series: {
          marker: {
            enabled: false
          },
          label: {
            enabled: false
          }
        }
      },
      title: {
        text: title,
      },
      xAxis: {
        min: -axis_value-2,
        max: axis_value+2,
        title: {
          text: '[ft]'
        }
      },
      yAxis: {
        min: -axis_value,
        max: axis_value,
        title: {
          text: '[ft]'
        }
      },
      annotations: [{
        labels: [{
          point: {
            xAxis: 0,
            yAxis: 0,
            x: 0,
            y: axis_value
          },
          text: '90°'
        }, {
          point: {
            xAxis: 0,
            yAxis: 0,
            x: axis_value,
            y: 0
          },
          text: '0°',
        },{
          point: {
            xAxis: 0,
            yAxis: 0,
            x: -axis_value,
            y: 0
          },
          text: '180°',
        },{
          point: {
            xAxis: 0,
            yAxis: 0,
            x: 0,
            y: -axis_value
          },
          text: '270°',
        }],
        labelOptions: {
          backgroundColor: 'white',
          borderColor: '#000'
        }
      }],
      series: data
    });
  }

  function create_polar_chart(div, data, axis_value, title, well_radius) {
    axis_value++;
    /* var data_xx = data_aux; */

    var circleX = 0;
    var circleY = 0;
    var circleR = well_radius;

    function addCircle(chart){

      var pixelX = chart.xAxis[0].toPixels(circleX);
      var pixelY = chart.yAxis[0].toPixels(circleY);
      var pixelR = chart.xAxis[0].toPixels(circleR) - chart.xAxis[0].toPixels(circleX);        

      this.circle = chart.renderer.circle(pixelX, pixelY, pixelR).attr({
        fill: 'white',
        stroke: 'black',
        'stroke-width': 1
      });
      this.circle.add();
      this.circle.toFront();    
    }

    Highcharts.chart(div, {
      chart: {
        height: (10 / 10 * 100) + '%',
        zoomType: "xy",
        events: {
          load: function(){
            addCircle(this); 
          },
          redraw: function(){
            addCircle(this);
          }
        }
      },
      title: {
        text: title,
      },
      tooltip: {
        pointFormat: 'X: <b>{point.x:.1f}</b><br/>Y: <b>{point.y:.1f}</b><br/>Value: <b>{point.value:.2f}</b><br/>',
      },
      xAxis: {
        min: -axis_value,
        max: axis_value,
        title: {
          text: '[ft]'
        }
      },
      yAxis: {
        min: -axis_value,
        max: axis_value,
        title: {
          text: '[ft]'
        }
      },
      colorAxis: {
        stops: [
        [0, '#e7ef00'],
        [0.2, '#37ef00'],
        [0.6, '#00f0e0'],
        [0.8, '#00bbef'],
        [1, '#002fef']
        ],
      },
      series: [
      {
        type: 'contour',
        data: data
      },
      {
        type: 'line',
        data: max_azimuth_line.data,
        name: max_azimuth_line.name,
        dashStyle: 'shortdot',
        zIndex: 2,
        marker: {
          enabled: false
        },
        label: {
          enabled: false
        },
        enableMouseTracking: false
      }
      ],
      annotations: [{
        labels: [{
          point: {
            xAxis: 0,
            yAxis: 0,
            x: 0,
            y: axis_value
          },
          text: '90°'
        }, {
          point: {
            xAxis: 0,
            yAxis: 0,
            x: axis_value,
            y: 0
          },
          text: '0°',
        },{
          point: {
            xAxis: 0,
            yAxis: 0,
            x: -axis_value,
            y: 0
          },
          text: '180°',
        },{
          point: {
            xAxis: 0,
            yAxis: 0,
            x: 0,
            y: -axis_value
          },
          text: '270°',
        }],
        labelOptions: {
          borderRadius: 0,
          backgroundColor: 'white',
          borderWidth: 0,
          borderColor: '#AAA'
        }
      }]
    });
  }

  function create_line_chart(div, data, title) {

    Highcharts.chart(div, {

      chart: {
        type: 'line',
        zoomType: 'x',
        inverted: false
      },
      title: {
        text: title
      },
      xAxis: {
        title: {
          text: 'Radius [ft]'
        }
      },
      yAxis: {
        title: {
          text: title
        },
        plotLines: [{
          value: 0,
          width: 1,
          color: '#808080'
        }]
      },
      plotOptions: {
        series: {
          marker: {
            enabled: false
          },
          label: {
            enabled: false
          }
        }
      },
      series: data

    });
  }

  function seriesPlot(div, data, title, xAxis) {

    Highcharts.chart(div, {

      chart: {
        type: 'line',
        zoomType: 'xy',
        inverted: false
      },
      title: {
        text: title,
        x: -20 /*center*/
      },
      xAxis: {
        title: {
          text: xAxis
        }
      },
      yAxis: {
        title: {
          text: title
        },
        plotLines: [{
          value: 0,
          width: 1,
          color: '#808080'
        }]
      },
      plotOptions: {
        series: {
          marker: {
            enabled: false
          },
          label: {
            enabled: false
          }
        }
      },
      legend: {
        layout: 'vertical',
        align: 'right',
        verticalAlign: 'middle',
        borderWidth: 0
      },
      series: data
    });
  }
  @endif
</script>
