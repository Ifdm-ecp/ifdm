<script type="text/javascript">
/* Drilling rulesets
 * This is a set of rules for each table and/or section of the form
 * Each element in the array corresponds to a rule assigned to the column
 * So element 0 has the rules for the column 0 of the table
*/
general_data_select_ruleset = [
  {
    column: "Producing Interval",
    rules: [
      {rule: "requiredselect"}
    ]
  },
];

general_data_table_ruleset = [
  {
    column: "Interval",
    rules: [
      {rule: "required"},
      {rule: "any"}
    ]
  },
  {
    column: "Top [ft]",
    rules: [
      {rule: "required"},
      {rule: "numeric"},
      {rule: "range", min: 0, max: 50000},
    ]
  },
  {
    column: "Bottom [ft]",
    rules: [
      {rule: "required"},
      {rule: "numeric"},
      {rule: "range", min: 0, max: 50000},
    ]
  },
  {
    column: "Reservoir Pressure [psi]",
    rules: [
      {rule: "required"},
      {rule: "numeric"},
      {rule: "range", min: 0, max: 10000},
    ]
  },
  {
    column: "Hole Diameter [in]",
    rules: [
      {rule: "required"},
      {rule: "numeric"},
      {rule: "range", min: 0, max: 10},
    ]
  },
  {
    column: "Drill Pipe Diameter [in]",
    rules: [
      {rule: "required"},
      {rule: "numeric"},
      {rule: "range", min: 0, max: 10},
    ]
  }
];

profile_select_ruleset = [
  {
    column: "Input Data Method",
    rules: [
      {rule: "requiredselect"}
    ]
  },
];

profile_table_ruleset = [
  {
    column: "Top [ft]",
    rules: [
      {rule: "required"},
      {rule: "numeric"},
      {rule: "range", min: 0, max: 50000},
    ]
  },
  {
    column: "Bottom [ft]",
    rules: [
      {rule: "required"},
      {rule: "numeric"},
      {rule: "range", min: 0, max: 50000},
    ]
  },
  {
    column: "Porosity [-]",
    rules: [
      {rule: "required"},
      {rule: "numeric"},
      {rule: "range", min: 0, max: 1},
    ]
  },
  {
    column: "Permeability [mD]",
    rules: [
      {rule: "required"},
      {rule: "numeric"},
      {rule: "range", min: 0, max: 10000},
    ]
  },
  {
    column: "Fracture Intensity [#/ft]",
    rules: [
      {rule: "required"},
      {rule: "numeric"},
      {rule: "range", min: 0, max: 100},
    ]
  },
  {
    column: "Irreducible Saturation [-]",
    rules: [
      {rule: "required"},
      {rule: "numeric"},
      {rule: "range", min: 0, max: 1},
    ]
  }
];

filtration_function_tab_ruleset = [
  {
    column: "Filtration Function",
    rules: [
      {rule: "requiredselect"}
    ]
  },
  {
    column: "a",
    rules: [
      {rule: "required"},
      {rule: "numeric"},
      {rule: "range", min: 0, max: 50},
    ]
  },
  {
    column: "b",
    rules: [
      {rule: "required"},
      {rule: "numeric"},
      {rule: "range", min: 0, max: 50},
    ]
  }
];

drilling_data_tab_ruleset = [
  {
    column: "Total Exposure Time",
    rules: [
      {rule: "required"},
      {rule: "numeric"},
      {rule: "range", min: 0, max: 50},
    ]
  },
  {
    column: "Pump Rate",
    rules: [
      {rule: "required"},
      {rule: "numeric"},
      {rule: "range", min: 0, max: 500},
    ]
  },
  {
    column: "Mud Density",
    rules: [
      {rule: "required"},
      {rule: "numeric"},
      {rule: "range", min: 0, max: 20},
    ]
  },
  {
    column: "Plastic Viscosity",
    rules: [
      {rule: "required"},
      {rule: "numeric"},
      {rule: "range", min: 0, max: 100},
    ]
  },
  {
    column: "Yield Point",
    rules: [
      {rule: "required"},
      {rule: "numeric"},
      {rule: "range", min: 0, max: 100},
    ]
  },
  {
    column: "ROP",
    rules: [
      {rule: "required"},
      {rule: "numeric"},
      {rule: "range", min: 0, max: 500},
    ]
  },
  {
    column: "ECD (Equivalent Circulating Density)",
    rules: [
      {rule: "required"},
      {rule: "numeric"},
      {rule: "range", min: 0, max: 30},
    ]
  }
];

completion_data_tab_ruleset = [
  {
    column: "Total Exposure Time",
    rules: [
      {rule: "required"},
      {rule: "numeric"},
      {rule: "range", min: 0, max: 50},
    ]
  },
  {
    column: "Pump Rate",
    rules: [
      {rule: "required"},
      {rule: "numeric"},
      {rule: "range", min: 0, max: 500},
    ]
  },
  {
    column: "Cement Slurry Density",
    rules: [
      {rule: "required"},
      {rule: "numeric"},
      {rule: "range", min: 0, max: 50},
    ]
  },
  {
    column: "Plastic Viscosity",
    rules: [
      {rule: "required"},
      {rule: "numeric"},
      {rule: "range", min: 0, max: 100},
    ]
  },
  {
    column: "Yield Point",
    rules: [
      {rule: "required"},
      {rule: "numeric"},
      {rule: "range", min: 0, max: 100},
    ]
  },
  {
    column: "ECD (Equivalent Circulating Density)",
    rules: [
      {rule: "required"},
      {rule: "numeric"},
      {rule: "range", min: 0, max: 70},
    ]
  }
];
</script>