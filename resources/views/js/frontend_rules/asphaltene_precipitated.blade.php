<script type="text/javascript">
/* Asphaltene precipitated rulesets
  * This is a set of rules for each table and/or section of the form
  * Each element in the array corresponds to a rule assigned to the column
  * So element 0 has the rules for the column 0 of the table
*/
components_select_ruleset = [
  {
    column: "Components",
    rules: [
      {rule: "requiredselect"},
      {
        rule: "selectionmultiple",
        selections: ["N2", "CO2", "H2S", "C1", "C2", "C3", "IC4", "NC4", "IC5", "NC5", "NC6", "NC7", "NC8", "NC9", "NC10", "NC11", "NC12", "NC13", "NC14", "NC15", "NC16", "NC17", "NC18", "NC19", "NC20", "NC21", "NC22", "NC23", "NC24", "FC6", "FC7", "FC8", "FC9", "FC10", "FC11", "FC12", "FC13", "FC14", "FC15", "FC16", "FC17", "FC18", "FC19", "FC20", "FC21", "FC22", "FC23", "FC24", "FC25", "FC26", "FC27", "FC28", "FC29", "FC30", "FC31", "FC32", "FC33", "FC34", "FC35", "FC36", "FC37", "FC38", "FC39", "FC40", "FC41", "FC42", "FC43", "FC44", "FC45", "SO2", "H2", "Plus +"]
      }
    ]
  }
];

components_table_ruleset = [
  {
    column: "Components",
    rules: [
      {rule: "required"},
      {
        rule: "selection",
        selections: ["N2", "CO2", "H2S", "C1", "C2", "C3", "IC4", "NC4", "IC5", "NC5", "NC6", "NC7", "NC8", "NC9", "NC10", "NC11", "NC12", "NC13", "NC14", "NC15", "NC16", "NC17", "NC18", "NC19", "NC20", "NC21", "NC22", "NC23", "NC24", "FC6", "FC7", "FC8", "FC9", "FC10", "FC11", "FC12", "FC13", "FC14", "FC15", "FC16", "FC17", "FC18", "FC19", "FC20", "FC21", "FC22", "FC23", "FC24", "FC25", "FC26", "FC27", "FC28", "FC29", "FC30", "FC31", "FC32", "FC33", "FC34", "FC35", "FC36", "FC37", "FC38", "FC39", "FC40", "FC41", "FC42", "FC43", "FC44", "FC45", "SO2", "H2", "Plus +"]
      }
    ]
  },
  {
    column: "Zi [0 - 1]",
    rules: [
      {rule: "required"},
      {rule: "numeric"},
      {rule: "range", min: 0, max: 1}
    ]
  },
  {
    column: "MW [lb]",
    rules: [
      {rule: "required"},
      {rule: "numeric"},
      {rule: "range", min: 0, max: 2000}
    ]
  },
  {
    column: "Pc [psi]",
    rules: [
      {rule: "required"},
      {rule: "numeric"},
      {rule: "range", min: 0, max: 10000}
    ]
  },
  {
    column: "Tc [F]",
    rules: [
      {rule: "required"},
      {rule: "numeric"},
      {rule: "range", min: -500, max: 10000}
    ]
  },
  {
    column: "W",
    rules: [
      {rule: "required"},
      {rule: "numeric"},
      {rule: "range", min: -2, max: 2}
    ]
  },
  {
    column: "Shift",
    rules: [
      {rule: "required"},
      {rule: "numeric"},
      {rule: "range", min: -5, max: 10000}
    ]
  },
  {
    column: "SG",
    rules: [
      {rule: "required"},
      {rule: "numeric"},
      {rule: "range", min: 0, max: 2}
    ]
  },
  {
    column: "Tb [R]",
    rules: [
      {rule: "required"},
      {rule: "numeric"},
      {rule: "range", min: 0, max: 5000}
    ]
  },
  {
    column: "Vc [ft3/lbmol]",
    rules: [
      {rule: "required"},
      {rule: "numeric"},
      {rule: "range", min: 0, max: 1000}
    ]
  }
];

plus_plus_data_ruleset = [
  {
    column: "Plus Fraction Molecular Weight (MW)",
    rules: [
      {rule: "required"},
      {rule: "numeric"},
      {rule: "range", min: 100, max: 2000}
    ]
  },
  {
    column: "Plus Fraction Specific Gravity",
    rules: [
      {rule: "required"},
      {rule: "numeric"},
      {rule: "range", min: 0.5, max: 2}
    ]
  },
  {
    column: "Plus Fraction Boiling Temperature",
    rules: [
      {rule: "required"},
      {rule: "numeric"},
      {rule: "range", min: 100, max: 2000}
    ]
  },
  {
    column: "Sample Molecular Weight",
    rules: [
      {rule: "required"},
      {rule: "numeric"},
      {rule: "minw", minw: "0"}
    ]
  },
  {
    column: "Correlation",
    rules: [
      {rule: "requiredselect"},
      {
        rule: "selection",
        selections: ["Twu", "Lee-Kesler", "Kavett", "Pedersen", "Riazzi Daubert"]
      }
    ]
  }
];

binary_interaction_coefficients_table_ruleset = [
  {
    column: "Components",
    rules: [
      {rule: "requiredselect"},
      {
        rule: "selectionmultiple",
        selections: ["N2", "CO2", "H2S", "C1", "C2", "C3", "IC4", "NC4", "IC5", "NC5", "NC6", "NC7", "NC8", "NC9", "NC10", "NC11", "NC12", "NC13", "NC14", "NC15", "NC16", "NC17", "NC18", "NC19", "NC20", "NC21", "NC22", "NC23", "NC24", "FC6", "FC7", "FC8", "FC9", "FC10", "FC11", "FC12", "FC13", "FC14", "FC15", "FC16", "FC17", "FC18", "FC19", "FC20", "FC21", "FC22", "FC23", "FC24", "FC25", "FC26", "FC27", "FC28", "FC29", "FC30", "FC31", "FC32", "FC33", "FC34", "FC35", "FC36", "FC37", "FC38", "FC39", "FC40", "FC41", "FC42", "FC43", "FC44", "FC45", "SO2", "H2", "Plus +"]
      }
    ]
  }
];

bubble_point_table_ruleset = [
  {
    column: "Temperature (Bubble curve) [°F]",
    rules: [
      {rule: "required"},
      {rule: "numeric"},
      {rule: "minw", minw: "0"}
    ]
  },
  {
    column: "Bubble pressure [psi]",
    rules: [
      {rule: "required"},
      {rule: "numeric"},
      {rule: "minw", minw: "0"}
    ]
  }
];

saturation_data_ruleset = [
  {
    column: "Critical Temperature (Envolope phase)",
    rules: [
      {rule: "required"},
      {rule: "numeric"},
      {rule: "minw", minw: "0"}
    ]
  },
  {
    column: "Bubble pressure [psi]",
    rules: [
      {rule: "required"},
      {rule: "numeric"},
      {rule: "minw", minw: "0"}
    ]
  },
  {
    column: "Density at Reservoir Pressure",
    rules: [
      {rule: "required"},
      {rule: "numeric"},
      {rule: "range", min: 0.5, max: 2}
    ]
  },
  {
    column: "Density at Bubble Pressure",
    rules: [
      {rule: "required"},
      {rule: "numeric"},
      {rule: "range", min: 0.5, max: 2}
    ]
  },
  {
    column: "Density at Atmospheric Pressure",
    rules: [
      {rule: "required"},
      {rule: "numeric"},
      {rule: "range", min: 0.5, max: 2}
    ]
  },
  {
    column: "Reservoir Temperature",
    rules: [
      {rule: "required"},
      {rule: "numeric"},
      {rule: "minw", minw: "0"}
    ]
  },
  {
    column: "Current Reservoir Pressure",
    rules: [
      {rule: "required"},
      {rule: "numeric"},
      {rule: "minw", minw: "0"}
    ]
  },
  {
    column: "Fluid API Gravity",
    rules: [
      {rule: "required"},
      {rule: "numeric"},
      {rule: "range", min: 5, max: 70}
    ]
  }
];

asphaltenes_tab_ruleset = [
  {
    column: "Initial Temperature",
    rules: [
      {rule: "required"},
      {rule: "numeric"},
      {rule: "range", min: 400, max: 600}
    ]
  },
  {
    column: "Number Of Temperatures",
    rules: [
      {rule: "required"},
      {rule: "numeric"},
      {rule: "range", min: 5, max: 50}
    ]
  },
  {
    column: "Temperature Delta",
    rules: [
      {rule: "required"},
      {rule: "numeric"},
      {rule: "range", min: 20, max: 100}
    ]
  },
  {
    column: "Asphaltene Particle Diameter",
    rules: [
      {rule: "required"},
      {rule: "numeric"},
      {rule: "range", min: 1, max: 20}
    ]
  },
  {
    column: "Asphaltene Molecular Weight",
    rules: [
      {rule: "required"},
      {rule: "numeric"},
      {rule: "rangew", minw: 0, maxw: 2000}
    ]
  },
  {
    column: "Asphaltene Apparent Density",
    rules: [
      {rule: "required"},
      {rule: "numeric"},
      {rule: "range", min: 0.5, max: 2}
    ]
  },
  {
    column: "Saturate",
    rules: [
      {rule: "required"},
      {rule: "numeric"},
      {rule: "range", min: 0, max: 100}
    ]
  },
  {
    column: "Aromatic",
    rules: [
      {rule: "required"},
      {rule: "numeric"},
      {rule: "range", min: 0, max: 100}
    ]
  },
  {
    column: "Resine",
    rules: [
      {rule: "required"},
      {rule: "numeric"},
      {rule: "range", min: 0, max: 100}
    ]
  },
  {
    column: "Asphaltene",
    rules: [
      {rule: "required"},
      {rule: "numeric"},
      {rule: "range", min: 0, max: 100}
    ]
  },
  {
    column: "Hydrogen Carbon Ratio",
    rules: [
      {rule: "required"},
      {rule: "numeric"}
    ]
  },
  {
    column: "Oxygen Carbon Ratio",
    rules: [
      {rule: "required"},
      {rule: "numeric"}
    ]
  },
  {
    column: "Nitrogen Carbon Ratio",
    rules: [
      {rule: "required"},
      {rule: "numeric"}
    ]
  },
  {
    column: "Sulphure Carbon Ratio",
    rules: [
      {rule: "required"},
      {rule: "numeric"}
    ]
  },
  {
    column: "FA Aromaticity",
    rules: [
      {rule: "required"},
      {rule: "numeric"}
    ]
  },
  {
    column: "VC Molar Volume",
    rules: [
      {rule: "required"},
      {rule: "numeric"}
    ]
  }
];
</script>