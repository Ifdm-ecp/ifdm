<script type="text/javascript">
  /* Asphaltene stability rulesets
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
    }
  ];
  
  sara_section_ruleset = [
    {
      column: "Saturated",
      rules: [
        {rule: "required"},
        {rule: "numeric"},
        {rule: "rangew", minw: 0, maxw: 100}
      ]
    },
    {
      column: "Aromatics",
      rules: [
        {rule: "required"},
        {rule: "numeric"},
        {rule: "rangew", minw: 0, maxw: 100}
      ]
    },
    {
      column: "Resines",
      rules: [
        {rule: "required"},
        {rule: "numeric"},
        {rule: "rangew", minw: 0, maxw: 100}
      ]
    },
    {
      column: "Asphaltenes",
      rules: [
        {rule: "required"},
        {rule: "numeric"},
        {rule: "rangew", minw: 0, maxw: 100}
      ]
    }
  ];

  saturate_section_ruleset = [
    {
      column: "Reservoir Initial Pressure",
      rules: [
        {rule: "required"},
        {rule: "numeric"},
        {rule: "minw", minw: 0}
      ]
    },
    {
      column: "Bubble Pressure",
      rules: [
        {rule: "required"},
        {rule: "numeric"},
        {rule: "minw", minw: 0}
      ]
    },
    {
      column: "Density At Reservoir Temperature",
      rules: [
        {rule: "required"},
        {rule: "numeric"},
        {rule: "range", min: 0.5, max: 2}
      ]
    },
    {
      column: "API Gravity",
      rules: [
        {rule: "required"},
        {rule: "numeric"},
        {rule: "range", min: 5, max: 70}
      ]
    }
  ];
</script>