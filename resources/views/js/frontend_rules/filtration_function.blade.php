<script type="text/javascript">
/* Filtration Function rulesets
  * This is a set of rules for each table and/or section of the form
  * Each element in the array corresponds to a rule assigned to the column
  * So element 0 has the rules for the column 0 of the table
*/
general_filtration_function_data_ruleset = [
  {
    column: "Basin",
    rules: [
      {rule: "requiredselect"}
    ]
  },
  {
    column: "Field",
    rules: [
      {rule: "requiredselect"}
    ]
  },
  {
    column: "Formation",
    rules: [
      {rule: "requiredselect"}
    ]
  },
  {
    column: "Filtration Function Name",
    rules: [
      {rule: "required"},
      {rule: "any"}
    ]
  },
];

mud_properties_ruleset = [
  {
    column: "Density",
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
    column: "LPLT Filtrate",
    rules: [
      {rule: "numeric"},
      {rule: "range", min: 0, max: 100},
    ]
  },
  {
    column: "HPHT Filtrate",
    rules: [
      {rule: "numeric"},
      {rule: "range", min: 0, max: 100},
    ]
  },
  {
    column: "PH",
    rules: [
      {rule: "numeric"},
      {rule: "range", min: 0, max: 14},
    ]
  },
  {
    column: "Gel Strength",
    rules: [
      {rule: "numeric"},
      {rule: "range", min: 0, max: 100},
    ]
  },
];

cement_properties_ruleset = [
  {
    column: "Density",
    rules: [
      {rule: "numeric"},
      {rule: "range", min: 0, max: 50},
    ]
  },
  {
    column: "Plastic Viscosity",
    rules: [
      {rule: "numeric"},
      {rule: "range", min: 0, max: 100},
    ]
  },
  {
    column: "Yield Point",
    rules: [
      {rule: "numeric"},
      {rule: "range", min: 0, max: 100},
    ]
  },
];

drilling_fluid_formulation_ruleset = [
  {
    column: "Component",
    rules: [
      {rule: "required"},
      {rule: "any"}
    ]
  },
  {
    column: "Concentration [lb/b or gal/b]",
    rules: [
      {rule: "required"},
      {rule: "numeric"}
    ]
  }
];

kdki_values_ruleset = [
  {
    column: "Kd/Ki Completition Fluids",
    rules: [
      {rule: "numeric"},
      {rule: "range", min: 0, max: 1},
    ]
  },
  {
    column: "Kd/Ki Mud",
    rules: [
      {rule: "required"},
      {rule: "numeric"},
      {rule: "range", min: 0, max: 1},
    ]
  },
  {
    column: "Core Diameter",
    rules: [
      {rule: "required"},
      {rule: "numeric"},
      {rule: "range", min: 0, max: 50},
    ]
  },
];

filtration_function_factors_ruleset = [
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

filtration_function_lab_ruleset = [
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

laboratory_test_table_ruleset = [
  {
    column: "Time [min]",
    rules: [
      {rule: "required"},
      {rule: "numeric"},
      {rule: "range", min: 0, max: 1000},
    ]
  },
  {
    column: "Filtrate Volume [ml]",
    rules: [
      {rule: "required"},
      {rule: "numeric"},
      {rule: "range", min: 0, max: 1000},
    ]
  }
];

laboratory_test_data_ruleset = [
  {
    column: "Permeability",
    rules: [
      {rule: "required"},
      {rule: "numeric"},
      {rule: "range", min: 0, max: 10000},
    ]
  },
  {
    column: "Pob",
    rules: [
      {rule: "required"},
      {rule: "numeric"},
      {rule: "range", min: 0, max: 10000},
    ]
  }
];
</script>