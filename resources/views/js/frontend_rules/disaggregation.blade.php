<script type="text/javascript">
/* Disaggregation rulesets
    * This is a set of rules for each table and/or section of the form
    * Each element in the array corresponds to a rule assigned to the column
    * So element 0 has the rules for the column 0 of the table
*/
well_data_ruleset = [
  {
    column: "Well Radius",
    rules: [
      {rule: "required"},
      {rule: "numeric"},
      {rule: "range", min: 0, max: 5}
    ]
  },
  {
    column: "Reservoir Pressure",
    rules: [
      {rule: "required"},
      {rule: "numeric"},
      {rule: "range", min: 0, max: 10000}
    ]
  },
  {
    column: "Measured Well Depth",
    rules: [
      {rule: "required"},
      {rule: "numeric"},
      {rule: "range", min: 0, max: 30000}
    ]
  },
  {
    column: "True Vertical Depth",
    rules: [
      {rule: "required"},
      {rule: "numeric"},
      {rule: "range", min: 0, max: 30000}
    ]
  },
  {
    column: "Formation Thickness",
    rules: [
      {rule: "required"},
      {rule: "numeric"},
      {rule: "range", min: 0, max: 1000}
    ]
  },
  {
    column: "Perforated Thickness",
    rules: [
      {rule: "required"},
      {rule: "numeric"},
      {rule: "range", min: 0, max: 1000}
    ]
  },
  {
    column: "Well Completitions",
    rules: [
      {rule: "requiredselect"},
      {
        rule: "selection",
        selections: ["1", "2", "3"]
      }
    ]
  },
  {
    column: "Perforation Penetration Depth",
    rules: [
      {rule: "required"},
      {rule: "numeric"},
      {rule: "range", min: 0, max: 50}
    ]
  },
  {
    column: "Perforating Phase Angle",
    rules: [
      {rule: "requiredselect"},
      {
        rule: "selection",
        selections: ["0", "45", "60", "90", "120", "180", "360"]
      }
    ]
  },
  {
    column: "Perforating Radius",
    rules: [
      {rule: "required"},
      {rule: "numeric"},
      {rule: "range", min: 0, max: 10}
    ]
  },
  {
    column: "Production Formation Thickness",
    rules: [
      {rule: "required"},
      {rule: "numeric"},
      {rule: "range", min: 0, max: 1000}
    ]
  },
  {
    column: "Horizontal - Vertical Permeability Ratio",
    rules: [
      {rule: "required"},
      {rule: "numeric"},
      {rule: "range", min: 0, max: 100}
    ]
  },
  {
    column: "Drainage Area Shape",
    rules: [
      {rule: "requiredselect"},
      {
        rule: "selection",
        selections: ["1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12", "13", "14", "15", "16"]
      }
    ]
  },
];

production_data_ruleset = [
  {
    column: "Fluid of Interest",
    rules: [
      {rule: "requiredselect"},
      {
        rule: "selection",
        selections: ["1", "2", "3"]
      }
    ]
  },
  {
    column: "Oil Rate",
    rules: [
      {rule: "required"},
      {rule: "numeric"},
      {rule: "range", min: 0, max: 10000}
    ]
  },
  {
    column: "Bottomhole Flowing Pressure",
    rules: [
      {rule: "required"},
      {rule: "numeric"},
      {rule: "range", min: 0, max: 10000}
    ]
  },
  {
    column: "Oil Viscosity",
    rules: [
      {rule: "required"},
      {rule: "numeric"},
      {rule: "range", min: 0, max: 100000}
    ]
  },
  {
    column: "Oil Volumetric Factor",
    rules: [
      {rule: "required"},
      {rule: "numeric"},
      {rule: "range", min: 0, max: 10}
    ]
  },
  {
    column: "Gas Rate",
    rules: [
      {rule: "required"},
      {rule: "numeric"},
      {rule: "range", min: 0, max: 10000}
    ]
  },
  {
    column: "Bottomhole Flowing Pressure",
    rules: [
      {rule: "required"},
      {rule: "numeric"},
      {rule: "range", min: 0, max: 10000}
    ]
  },
  {
    column: "Gas Viscosity",
    rules: [
      {rule: "required"},
      {rule: "numeric"},
      {rule: "range", min: 0, max: 100000}
    ]
  },
  {
    column: "Gas Volumetric Factor",
    rules: [
      {rule: "required"},
      {rule: "numeric"},
      {rule: "range", min: 0, max: 10}
    ]
  },
  {
    column: "Water Rate",
    rules: [
      {rule: "required"},
      {rule: "numeric"},
      {rule: "range", min: 0, max: 10000}
    ]
  },
  {
    column: "Bottomhole Flowing Pressure",
    rules: [
      {rule: "required"},
      {rule: "numeric"},
      {rule: "range", min: 0, max: 10000}
    ]
  },
  {
    column: "Water Viscosity",
    rules: [
      {rule: "required"},
      {rule: "numeric"},
      {rule: "range", min: 0, max: 100000}
    ]
  },
  {
    column: "Water Volumetric Factor",
    rules: [
      {rule: "required"},
      {rule: "numeric"},
      {rule: "range", min: 0, max: 10}
    ]
  },
];

damage_ruleset = [
  {
    column: "Skin",
    rules: [
      {rule: "required"},
      {rule: "numeric"},
      {rule: "range", min: 0, max: 1000}
    ]
  }
];

basic_petrophysics_ruleset = [
  {
    column: "Permeability",
    rules: [
      {rule: "required"},
      {rule: "numeric"},
      {rule: "range", min: 0, max: 1000000}
    ]
  },
  {
    column: "Rock Type",
    rules: [
      {rule: "requiredselect"},
      {
        rule: "selection",
        selections: ["poco consolidada", "consolidada", "microfracturada"]
      }
    ]
  },
  {
    column: "Porosity",
    rules: [
      {rule: "required"},
      {rule: "numeric"},
      {rule: "range", min: 0, max: 1}
    ]
  }
];

hydraulic_units_data_table_ruleset = [
  {
    column: "Thickness [ft]",
    rules: [
      {rule: "required"},
      {rule: "numeric"},
      {rule: "range", min: 0, max: 1000}
    ]
  },
  {
    column: "Average Porosity [0-1]",
    rules: [
      {rule: "required"},
      {rule: "numeric"},
      {rule: "range", min: 0, max: 1}
    ]
  },
  {
    column: "Average Permeability [mD]",
    rules: [
      {rule: "required"},
      {rule: "numeric"},
      {rule: "range", min: 0, max: 1000000}
    ]
  }
];
</script>