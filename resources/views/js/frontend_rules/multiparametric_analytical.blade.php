<script type="text/javascript">
  /* Multiparametric analytical rulesets
    * This is a set of rules for each table and/or section of the form
    * Each element in the array corresponds to a rule assigned to the column
    * So element 0 has the rules for the column 0 of the table
  */
  rock_properties_tab_ruleset = [
    {
      column: "NetPay",
      rules: [
        {rule: "required"},
        {rule: "numeric"},
        {rule: "range", min: 0, max: 10000}
      ]
    },
    {
      column: "Absolute Permeability",
      rules: [
        {rule: "required"},
        {rule: "numeric"},
        {rule: "minw", minw: 0},
        {rule: "max", max: 10000}
      ]
    },
    {
      column: "Porosity",
      rules: [
        {rule: "required"},
        {rule: "numeric"},
        {rule: "minw", minw: 0},
        {rule: "max", max: 0.49}
      ]
    },
    {
      column: "Permeability",
      rules: [
        {rule: "required"},
        {rule: "numeric"},
        {rule: "minw", minw: 0.01},
        {rule: "max", max: 10000}
      ]
    }
  ];
  
  fluid_information_tab_ruleset = [
    {
      column: "Fluid Type",
      rules: [
        {rule: "requiredselect"},
        {
          rule: "selection",
          selections: ["Oil", "Gas"]
        }
      ]
    },
    {
      column: "Oil Viscosity",
      rules: [
        {rule: "required"},
        {rule: "numeric"},
        {rule: "minw", minw: 0}
      ]
    },
    {
      column: "Oil Volumetric Factor",
      rules: [
        {rule: "required"},
        {rule: "numeric"},
        {rule: "minw", minw: 0},
        {rule: "max", max: 20}
      ]
    },
    {
      column: "Gas Viscosity",
      rules: [
        {rule: "required"},
        {rule: "numeric"},
        {rule: "minw", minw: 0}
      ]
    },
    {
      column: "Gas Volumetric Factor",
      rules: [
        {rule: "required"},
        {rule: "numeric"},
        {rule: "minw", minw: 0},
        {rule: "max", max: 20}
      ]
    }
  ];
  
  production_data_tab_ruleset = [
    {
      column: "Well Radius",
      rules: [
        {rule: "required"},
        {rule: "numeric"},
        {rule: "minw", minw: 0.1},
        {rule: "max", max: 2}
      ]
    },
    {
      column: "Drainage Radius",
      rules: [
        {rule: "required"},
        {rule: "numeric"},
        {rule: "minw", minw: 10},
        {rule: "max", max: 20000}
      ]
    },
    {
      column: "Reservoir Pressure",
      rules: [
        {rule: "required"},
        {rule: "numeric"},
        {rule: "minw", minw: 100},
      ]
    },
    {
      column: "Oil Rate",
      rules: [
        {rule: "required"},
        {rule: "numeric"},
        {rule: "minw", minw: 0}
      ]
    },
    {
      column: "Gas Rate",
      rules: [
        {rule: "required"},
        {rule: "numeric"},
        {rule: "minw", minw: 0}
      ]
    },
    {
      column: "BHP",
      rules: [
        {rule: "required"},
        {rule: "numeric"},
        {rule: "minw", minw: 0}
      ]
    }
  ];
  
  multiparametric_analysis_tab_ruleset = [
    {
      column: "Critical Radius Derived From Maximum Critical Velocity, Vc",
      rules: [
        {rule: "required"},
        {rule: "numeric"},
        {rule: "range", min: 0, max: 100}
      ]
    },
    {
      column: "Total Volume Of Water Based Fluids Pumped Into The Well",
      rules: [
        {rule: "required"},
        {rule: "numeric"},
        {rule: "range", min: 0, max: 1000000}
      ]
    },
    {
      column: "Saturation Pressure",
      rules: [
        {rule: "required"},
        {rule: "numeric"},
        {rule: "minw", minw: 0}
      ]
    },
    {
      column: "Critical Pressure Mineral Scales",
      rules: [
        {rule: "required"},
        {rule: "numeric"},
        {rule: "minw", minw: 0}
      ]
    },
    {
      column: "Critical Pressure Organic Scales",
      rules: [
        {rule: "required"},
        {rule: "numeric"},
        {rule: "minw", minw: 0}
      ]
    },
    {
      column: "Critical Pressure Geomechanical Damage - Drawdown",
      rules: [
        {rule: "required"},
        {rule: "numeric"},
        {rule: "minw", minw: 0}
      ]
    },
    {
      column: "K Damaged And K Base Ratio Mineral Scales",
      rules: [
        {rule: "required"},
        {rule: "numeric"},
        {rule: "range", min: 0, max: 1}
      ]
    },
    {
      column: "K Damaged And K Base Ratio Organic Scales",
      rules: [
        {rule: "required"},
        {rule: "numeric"},
        {rule: "range", min: 0, max: 1}
      ]
    },
    {
      column: "K Damaged And K Base Ratio Geomechanical Damage",
      rules: [
        {rule: "required"},
        {rule: "numeric"},
        {rule: "range", min: 0, max: 1}
      ]
    },
    {
      column: "K Damaged And K Base Ratio Fines Blockage",
      rules: [
        {rule: "required"},
        {rule: "numeric"},
        {rule: "range", min: 0, max: 1}
      ]
    },
    {
      column: "K Damaged And K Base Ratio Relative Permeability",
      rules: [
        {rule: "required"},
        {rule: "numeric"},
        {rule: "range", min: 0, max: 1}
      ]
    },
    {
      column: "K Damaged And K Base Ratio Induced Damage",
      rules: [
        {rule: "required"},
        {rule: "numeric"},
        {rule: "range", min: 0, max: 1}
      ]
    }
  ];
</script>