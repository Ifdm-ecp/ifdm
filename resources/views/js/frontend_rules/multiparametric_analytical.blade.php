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
        {rule: "min", min: 0}
      ]
    },
    {
      column: "Absolute Permeability",
      rules: [
        {rule: "required"},
        {rule: "numeric"},
        {rule: "min", min: 0}
      ]
    },
    {
      column: "Porosity",
      rules: [
        {rule: "required"},
        {rule: "numeric"},
        {rule: "min", min: 0}
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
        {rule: "min", min: 0}
      ]
    },
    {
      column: "Oil Volumetric Factor",
      rules: [
        {rule: "required"},
        {rule: "numeric"},
        {rule: "min", min: 0}
      ]
    },
    {
      column: "Gas Viscosity",
      rules: [
        {rule: "required"},
        {rule: "numeric"},
        {rule: "min", min: 0}
      ]
    },
    {
      column: "Gas Volumetric Factor",
      rules: [
        {rule: "required"},
        {rule: "numeric"},
        {rule: "min", min: 0}
      ]
    }
  ];
  
  production_data_tab_ruleset = [
    {
      column: "Well Radius",
      rules: [
        {rule: "required"},
        {rule: "numeric"},
        {rule: "min", min: 0}
      ]
    },
    {
      column: "Drainage Radius",
      rules: [
        {rule: "required"},
        {rule: "numeric"},
        {rule: "min", min: 0}
      ]
    },
    {
      column: "Reservoir Pressure",
      rules: [
        {rule: "required"},
        {rule: "numeric"},
        {rule: "min", min: 0}
      ]
    },
    {
      column: "Oil Rate",
      rules: [
        {rule: "required"},
        {rule: "numeric"},
        {rule: "min", min: 0}
      ]
    },
    {
      column: "Gas Rate",
      rules: [
        {rule: "required"},
        {rule: "numeric"},
        {rule: "min", min: 0}
      ]
    },
    {
      column: "BHP",
      rules: [
        {rule: "required"},
        {rule: "numeric"},
        {rule: "min", min: 0}
      ]
    }
  ];
  
  multiparametric_analysis_tab_ruleset = [
    {
      column: "Critical Radius Derived From Maximum Critical Velocity, Vc",
      rules: [
        {rule: "required"},
        {rule: "numeric"},
        {rule: "min", min: 0}
      ]
    },
    {
      column: "Total Volume Of Water Based Fluids Pumped Into The Well",
      rules: [
        {rule: "required"},
        {rule: "numeric"},
        {rule: "min", min: 0}
      ]
    },
    {
      column: "Saturation Pressure",
      rules: [
        {rule: "required"},
        {rule: "numeric"},
        {rule: "min", min: 0}
      ]
    },
    {
      column: "Critical Pressure Mineral Scales",
      rules: [
        {rule: "required"},
        {rule: "numeric"},
        {rule: "min", min: 0}
      ]
    },
    {
      column: "Critical Pressure Organic Scales",
      rules: [
        {rule: "required"},
        {rule: "numeric"},
        {rule: "min", min: 0}
      ]
    },
    {
      column: "Critical Pressure Geomechanical Damage - Drawdown",
      rules: [
        {rule: "required"},
        {rule: "numeric"},
        {rule: "min", min: 0}
      ]
    },
    {
      column: "K Damaged And K Base Ratio Mineral Scales",
      rules: [
        {rule: "required"},
        {rule: "numeric"},
        {rule: "min", min: 0}
      ]
    },
    {
      column: "K Damaged And K Base Ratio Organic Scales",
      rules: [
        {rule: "required"},
        {rule: "numeric"},
        {rule: "min", min: 0}
      ]
    },
    {
      column: "K Damaged And K Base Ratio Geomechanical Damage",
      rules: [
        {rule: "required"},
        {rule: "numeric"},
        {rule: "min", min: 0}
      ]
    },
    {
      column: "K Damaged And K Base Ratio Fines Blockage",
      rules: [
        {rule: "required"},
        {rule: "numeric"},
        {rule: "min", min: 0}
      ]
    },
    {
      column: "K Damaged And K Base Ratio Relative Permeability",
      rules: [
        {rule: "required"},
        {rule: "numeric"},
        {rule: "min", min: 0}
      ]
    },
    {
      column: "K Damaged And K Base Ratio Induced Damage",
      rules: [
        {rule: "required"},
        {rule: "numeric"},
        {rule: "min", min: 0}
      ]
    }
  ];
</script>