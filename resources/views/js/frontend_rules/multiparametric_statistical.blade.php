<script type="text/javascript">
  /* Multiparametric statistical rulesets
   * This is a set of rules for each table and/or section of the form
   * Each element in the array corresponds to a rule assigned to the column
   * So element 0 has the rules for the column 0 of the table
  */
  mineral_scales_tab_ruleset = [
    {
      column: "Scale Index Of CaCO3 value",
      rules: [
        {rule: "numeric"},
        {rule: "min", min: 0}
      ]
    },
    {
      column: "Scale Index Of CaCO3 monitoring date",
      rules: [
        {rule: "date", format: "DD/MM/YYYY", formatRead: "dd/mm/yyyy"}
      ]
    },
    {
      column: "Scale Index Of CaCO3 comment",
      rules: [
        {rule: "textmaxw", maxw: 100},
      ]
    },
    {
      column: "Scale Index Of CaCO3 p10",
      rules: [
        {rule: "numeric"},
        {rule: "min", min: 0}
      ]
    },
    {
      column: "Scale Index Of CaCO3 p90",
      rules: [
        {rule: "numeric"},
        {rule: "min", min: 0},
        {rule: "differentnumber", otherValue: $('#p10_1'), otherField: "p10"}
      ]
    },
    {
      column: "Scale Index Of CaCO3 weight",
      rules: [
        {rule: "numeric"}
      ]
    },
    {
      column: "Scale Index Of BaSO4 value",
      rules: [
        {rule: "numeric"},
        {rule: "min", min: 0}
      ]
    },
    {
      column: "Scale Index Of BaSO4 monitoring date",
      rules: [
        {rule: "date", format: "DD/MM/YYYY", formatRead: "dd/mm/yyyy"}
      ]
    },
    {
      column: "Scale Index Of BaSO4 comment",
      rules: [
        {rule: "textmaxw", maxw: 100},
      ]
    },
    {
      column: "Scale Index Of BaSO4 p10",
      rules: [
        {rule: "numeric"},
        {rule: "min", min: 0}
      ]
    },
    {
      column: "Scale Index Of BaSO4 p90",
      rules: [
        {rule: "numeric"},
        {rule: "min", min: 0},
        {rule: "differentnumber", otherValue: $('#p10_2'), otherField: "p10"}
      ]
    },
    {
      column: "Scale Index Of BaSO4 weight",
      rules: [
        {rule: "numeric"}
      ]
    },
    {
      column: "Scale Index Of Iron Scales value",
      rules: [
        {rule: "numeric"},
        {rule: "min", min: 0}
      ]
    },
    {
      column: "Scale Index Of Iron Scales monitoring date",
      rules: [
        {rule: "date", format: "DD/MM/YYYY", formatRead: "dd/mm/yyyy"}
      ]
    },
    {
      column: "Scale Index Of Iron Scales comment",
      rules: [
        {rule: "textmaxw", maxw: 100},
      ]
    },
    {
      column: "Scale Index Of Iron Scales p10",
      rules: [
        {rule: "numeric"},
        {rule: "min", min: 0}
      ]
    },
    {
      column: "Scale Index Of Iron Scales p90",
      rules: [
        {rule: "numeric"},
        {rule: "min", min: 0},
        {rule: "differentnumber", otherValue: $('#p10_3'), otherField: "p10"}
      ]
    },
    {
      column: "Scale Index Of Iron Scales weight",
      rules: [
        {rule: "numeric"}
      ]
    },
    {
      column: "Backflow [Ca] value",
      rules: [
        {rule: "numeric"},
        {rule: "range", min: 0, max: 1000000}
      ]
    },
    {
      column: "Backflow [Ca] monitoring date",
      rules: [
        {rule: "date", format: "DD/MM/YYYY", formatRead: "dd/mm/yyyy"}
      ]
    },
    {
      column: "Backflow [Ca] comment",
      rules: [
        {rule: "textmaxw", maxw: 100},
      ]
    },
    {
      column: "Backflow [Ca] p10",
      rules: [
        {rule: "numeric"},
        {rule: "min", min: 0}
      ]
    },
    {
      column: "Backflow [Ca] p90",
      rules: [
        {rule: "numeric"},
        {rule: "min", min: 0},
        {rule: "differentnumber", otherValue: $('#p10_4'), otherField: "p10"}
      ]
    },
    {
      column: "Backflow [Ca] weight",
      rules: [
        {rule: "numeric"}
      ]
    },
    {
      column: "Backflow [Ba] value",
      rules: [
        {rule: "numeric"},
        {rule: "range", min: 0, max: 1000000}
      ]
    },
    {
      column: "Backflow [Ba] monitoring date",
      rules: [
        {rule: "date", format: "DD/MM/YYYY", formatRead: "dd/mm/yyyy"}
      ]
    },
    {
      column: "Backflow [Ba] comment",
      rules: [
        {rule: "textmaxw", maxw: 100},
      ]
    },
    {
      column: "Backflow [Ba] p10",
      rules: [
        {rule: "numeric"},
        {rule: "min", min: 0}
      ]
    },
    {
      column: "Backflow [Ba] p90",
      rules: [
        {rule: "numeric"},
        {rule: "min", min: 0},
        {rule: "differentnumber", otherValue: $('#p10_5'), otherField: "p10"}
      ]
    },
    {
      column: "Backflow [Ba] weight",
      rules: [
        {rule: "numeric"}
      ]
    }
  ];
  
  fine_blockage_tab_ruleset = [
    {
      column: "[Al] on Produced Water value",
      rules: [
        {rule: "numeric"},
        {rule: "range", min: 0, max: 1000000}
      ]
    },
    {
      column: "[Al] on Produced Water monitoring date",
      rules: [
        {rule: "date", format: "DD/MM/YYYY", formatRead: "dd/mm/yyyy"}
      ]
    },
    {
      column: "[Al] on Produced Water comment",
      rules: [
        {rule: "textmaxw", maxw: 100},
      ]
    },
    {
      column: "[Al] on Produced Water p10",
      rules: [
        {rule: "numeric"},
        {rule: "min", min: 0}
      ]
    },
    {
      column: "[Al] on Produced Water p90",
      rules: [
        {rule: "numeric"},
        {rule: "min", min: 0},
        {rule: "differentnumber", otherValue: $('#p10_6'), otherField: "p10"}
      ]
    },
    {
      column: "[Al] on Produced Water weight",
      rules: [
        {rule: "numeric"}
      ]
    },
    {
      column: "[Si] on produced water value",
      rules: [
        {rule: "numeric"},
        {rule: "range", min: 0, max: 1000000}
      ]
    },
    {
      column: "[Si] on produced water monitoring date",
      rules: [
        {rule: "date", format: "DD/MM/YYYY", formatRead: "dd/mm/yyyy"}
      ]
    },
    {
      column: "[Si] on produced water comment",
      rules: [
        {rule: "textmaxw", maxw: 100},
      ]
    },
    {
      column: "[Si] on produced water p10",
      rules: [
        {rule: "numeric"},
        {rule: "min", min: 0}
      ]
    },
    {
      column: "[Si] on produced water p90",
      rules: [
        {rule: "numeric"},
        {rule: "min", min: 0},
        {rule: "differentnumber", otherValue: $('#p10_7'), otherField: "p10"}
      ]
    },
    {
      column: "[Si] on produced water weight",
      rules: [
        {rule: "numeric"}
      ]
    },
    {
      column: "Critical Radius derived from maximum critical velocity, Vc value",
      rules: [
        {rule: "numeric"},
        {rule: "range", min: 0, max: 100}
      ]
    },
    {
      column: "Critical Radius derived from maximum critical velocity, Vc monitoring date",
      rules: [
        {rule: "date", format: "DD/MM/YYYY", formatRead: "dd/mm/yyyy"}
      ]
    },
    {
      column: "Critical Radius derived from maximum critical velocity, Vc comment",
      rules: [
        {rule: "textmaxw", maxw: 100},
      ]
    },
    {
      column: "Critical Radius derived from maximum critical velocity, Vc p10",
      rules: [
        {rule: "numeric"},
        {rule: "min", min: 0}
      ]
    },
    {
      column: "Critical Radius derived from maximum critical velocity, Vc p90",
      rules: [
        {rule: "numeric"},
        {rule: "min", min: 0},
        {rule: "differentnumber", otherValue: $('#p10_8'), otherField: "p10"}
      ]
    },
    {
      column: "Critical Radius derived from maximum critical velocity, Vc weight",
      rules: [
        {rule: "numeric"}
      ]
    },
    {
      column: "Mineralogy Factor value",
      rules: [
        {rule: "numeric"},
        {rule: "range", min: 0, max: 1}
      ]
    },
    {
      column: "Mineralogy Factor monitoring date",
      rules: [
        {rule: "date", format: "DD/MM/YYYY", formatRead: "dd/mm/yyyy"}
      ]
    },
    {
      column: "Mineralogy Factor comment",
      rules: [
        {rule: "textmaxw", maxw: 100},
      ]
    },
    {
      column: "Mineralogy Factor p10",
      rules: [
        {rule: "numeric"},
        {rule: "min", min: 0}
      ]
    },
    {
      column: "Mineralogy Factor p90",
      rules: [
        {rule: "numeric"},
        {rule: "min", min: 0},
        {rule: "differentnumber", otherValue: $('#p10_9'), otherField: "p10"}
      ]
    },
    {
      column: "Mineralogy Factor weight",
      rules: [
        {rule: "numeric"}
      ]
    },
    {
      column: "Mass of crushed proppant inside Hydraulic Fractures value",
      rules: [
        {rule: "numeric"},
        {rule: "min", min: 0}
      ]
    },
    {
      column: "Mass of crushed proppant inside Hydraulic Fractures date",
      rules: [
        {rule: "date", format: "DD/MM/YYYY", formatRead: "dd/mm/yyyy"}
      ]
    },
    {
      column: "Mass of crushed proppant inside Hydraulic Fractures comment",
      rules: [
        {rule: "textmaxw", maxw: 100},
      ]
    },
    {
      column: "Mass of crushed proppant inside Hydraulic Fractures p10",
      rules: [
        {rule: "numeric"},
        {rule: "min", min: 0}
      ]
    },
    {
      column: "Mass of crushed proppant inside Hydraulic Fractures p90",
      rules: [
        {rule: "numeric"},
        {rule: "min", min: 0},
        {rule: "differentnumber", otherValue: $('#p10_10'), otherField: "p10"}
      ]
    },
    {
      column: "Mass of crushed proppant inside Hydraulic Fractures weight",
      rules: [
        {rule: "numeric"}
      ]
    }
  ];
  
  organic_scales_tab_ruleset = [
    {
      column: "CII Factor: Colloidal Instability Index value",
      rules: [
        {rule: "numeric"},
        {rule: "range", min: 0, max: 14}
      ]
    },
    {
      column: "CII Factor: Colloidal Instability Index monitoring date",
      rules: [
        {rule: "date", format: "DD/MM/YYYY", formatRead: "dd/mm/yyyy"}
      ]
    },
    {
      column: "CII Factor: Colloidal Instability Index comment",
      rules: [
        {rule: "textmaxw", maxw: 100},
      ]
    },
    {
      column: "CII Factor: Colloidal Instability Index p10",
      rules: [
        {rule: "numeric"},
        {rule: "min", min: 0}
      ]
    },
    {
      column: "CII Factor: Colloidal Instability Index p90",
      rules: [
        {rule: "numeric"},
        {rule: "min", min: 0},
        {rule: "differentnumber", otherValue: $('#p10_11'), otherField: "p10"}
      ]
    },
    {
      column: "CII Factor: Colloidal Instability Index weight",
      rules: [
        {rule: "numeric"}
      ]
    },
    {
      column: "Volume of HCL pumped into the formation value",
      rules: [
        {rule: "numeric"},
        {rule: "min", min: 0}
      ]
    },
    {
      column: "Volume of HCL pumped into the formation monitoring date",
      rules: [
        {rule: "date", format: "DD/MM/YYYY", formatRead: "dd/mm/yyyy"}
      ]
    },
    {
      column: "Volume of HCL pumped into the formation comment",
      rules: [
        {rule: "textmaxw", maxw: 100},
      ]
    },
    {
      column: "Volume of HCL pumped into the formation p10",
      rules: [
        {rule: "numeric"},
        {rule: "min", min: 0}
      ]
    },
    {
      column: "Volume of HCL pumped into the formation p90",
      rules: [
        {rule: "numeric"},
        {rule: "min", min: 0},
        {rule: "differentnumber", otherValue: $('#p10_30'), otherField: "p10"}
      ]
    },
    {
      column: "Volume of HCL pumped into the formation weight",
      rules: [
        {rule: "numeric"}
      ]
    },
    {
      column: "Cumulative Gas Produced value",
      rules: [
        {rule: "numeric"},
        {rule: "min", min: 0}
      ]
    },
    {
      column: "Cumulative Gas Produced monitoring date",
      rules: [
        {rule: "date", format: "DD/MM/YYYY", formatRead: "dd/mm/yyyy"}
      ]
    },
    {
      column: "Cumulative Gas Produced comment",
      rules: [
        {rule: "textmaxw", maxw: 100},
      ]
    },
    {
      column: "Cumulative Gas Produced p10",
      rules: [
        {rule: "numeric"},
        {rule: "min", min: 0}
      ]
    },
    {
      column: "Cumulative Gas Produced p90",
      rules: [
        {rule: "numeric"},
        {rule: "min", min: 0},
        {rule: "differentnumber", otherValue: $('#p10_12'), otherField: "p10"}
      ]
    },
    {
      column: "Cumulative Gas Produced weight",
      rules: [
        {rule: "numeric"}
      ]
    },
    {
      column: "Number Of Days Below Saturation Pressure value",
      rules: [
        {rule: "numeric"},
        {rule: "range", min: 0, max: 20000}
      ]
    },
    {
      column: "Number Of Days Below Saturation Pressure monitoring date",
      rules: [
        {rule: "date", format: "DD/MM/YYYY", formatRead: "dd/mm/yyyy"}
      ]
    },
    {
      column: "Number Of Days Below Saturation Pressure comment",
      rules: [
        {rule: "textmaxw", maxw: 100},
      ]
    },
    {
      column: "Number Of Days Below Saturation Pressure p10",
      rules: [
        {rule: "numeric"},
        {rule: "min", min: 0}
      ]
    },
    {
      column: "Number Of Days Below Saturation Pressure p90",
      rules: [
        {rule: "numeric"},
        {rule: "min", min: 0},
        {rule: "differentnumber", otherValue: $('#p10_13'), otherField: "p10"}
      ]
    },
    {
      column: "Number Of Days Below Saturation Pressure weight",
      rules: [
        {rule: "numeric"}
      ]
    },
    {
      column: "De Boer Criteria value",
      rules: [
        {rule: "numeric"}
      ]
    },
    {
      column: "De Boer Criteria monitoring date",
      rules: [
        {rule: "date", format: "DD/MM/YYYY", formatRead: "dd/mm/yyyy"}
      ]
    },
    {
      column: "De Boer Criteria comment",
      rules: [
        {rule: "textmaxw", maxw: 100},
      ]
    },
    {
      column: "De Boer Criteria p10",
      rules: [
        {rule: "numeric"}
      ]
    },
    {
      column: "De Boer Criteria p90",
      rules: [
        {rule: "numeric"},
        {rule: "differentnumber", otherValue: $('#p10_14'), otherField: "p10"}
      ]
    },
    {
      column: "De Boer Criteria weight",
      rules: [
        {rule: "numeric"}
      ]
    }
  ];
  
  relative_permeability_tab_ruleset = [
    {
      column: "Number Of Days Below Saturation Pressure value",
      rules: [
        {rule: "numeric"},
        {rule: "range", min: 0, max: 20000}
      ]
    },
    {
      column: "Number Of Days Below Saturation Pressure monitoring date",
      rules: [
        {rule: "date", format: "DD/MM/YYYY", formatRead: "dd/mm/yyyy"}
      ]
    },
    {
      column: "Number Of Days Below Saturation Pressure comment",
      rules: [
        {rule: "textmaxw", maxw: 100},
      ]
    },
    {
      column: "Number Of Days Below Saturation Pressure p10",
      rules: [
        {rule: "numeric"},
        {rule: "min", min: 0}
      ]
    },
    {
      column: "Number Of Days Below Saturation Pressure p90",
      rules: [
        {rule: "numeric"},
        {rule: "min", min: 0},
        {rule: "differentnumber", otherValue: $('#p10_15'), otherField: "p10"}
      ]
    },
    {
      column: "Number Of Days Below Saturation Pressure weight",
      rules: [
        {rule: "numeric"}
      ]
    },
    {
      column: "Difference between current reservoir pressure and saturation pressure value",
      rules: [
        {rule: "numeric"},
        {rule: "range", min: -15000, max: 15000}
      ]
    },
    {
      column: "Difference between current reservoir pressure and saturation pressure monitoring date",
      rules: [
        {rule: "date", format: "DD/MM/YYYY", formatRead: "dd/mm/yyyy"}
      ]
    },
    {
      column: "Difference between current reservoir pressure and saturation pressure comment",
      rules: [
        {rule: "textmaxw", maxw: 100},
      ]
    },
    {
      column: "Difference between current reservoir pressure and saturation pressure p10",
      rules: [
        {rule: "numeric"},
        {rule: "min", min: 0}
      ]
    },
    {
      column: "Difference between current reservoir pressure and saturation pressure p90",
      rules: [
        {rule: "numeric"},
        {rule: "min", min: 0},
        {rule: "differentnumber", otherValue: $('#p10_16'), otherField: "p10"}
      ]
    },
    {
      column: "Difference between current reservoir pressure and saturation pressure weight",
      rules: [
        {rule: "numeric"}
      ]
    },
    {
      column: "Cumulative Water Produced value",
      rules: [
        {rule: "numeric"},
        {rule: "minw", minw: 0}
      ]
    },
    {
      column: "Cumulative Water Produced monitoring date",
      rules: [
        {rule: "date", format: "DD/MM/YYYY", formatRead: "dd/mm/yyyy"}
      ]
    },
    {
      column: "Cumulative Water Produced comment",
      rules: [
        {rule: "textmaxw", maxw: 100},
      ]
    },
    {
      column: "Cumulative Water Produced p10",
      rules: [
        {rule: "numeric"},
        {rule: "min", min: 0}
      ]
    },
    {
      column: "Cumulative Water Produced p90",
      rules: [
        {rule: "numeric"},
        {rule: "min", min: 0},
        {rule: "differentnumber", otherValue: $('#p10_17'), otherField: "p10"}
      ]
    },
    {
      column: "Cumulative Water Produced weight",
      rules: [
        {rule: "numeric"}
      ]
    },
    {
      column: "Pore Size Diameter Approximation By Katz And Thompson Correlation (d = 1/√(ϕ * k)) value",
      rules: [
        {rule: "numeric"},
        {rule: "minw", minw: 0}
      ]
    },
    {
      column: "Pore Size Diameter Approximation By Katz And Thompson Correlation (d = 1/√(ϕ * k)) monitoring date",
      rules: [
        {rule: "date", format: "DD/MM/YYYY", formatRead: "dd/mm/yyyy"}
      ]
    },
    {
      column: "Pore Size Diameter Approximation By Katz And Thompson Correlation (d = 1/√(ϕ * k)) comment",
      rules: [
        {rule: "textmaxw", maxw: 100},
      ]
    },
    {
      column: "Pore Size Diameter Approximation By Katz And Thompson Correlation (d = 1/√(ϕ * k)) p10",
      rules: [
        {rule: "numeric"},
        {rule: "min", min: 0}
      ]
    },
    {
      column: "Pore Size Diameter Approximation By Katz And Thompson Correlation (d = 1/√(ϕ * k)) p90",
      rules: [
        {rule: "numeric"},
        {rule: "min", min: 0},
        {rule: "differentnumber", otherValue: $('#p10_18'), otherField: "p10"}
      ]
    },
    {
      column: "Pore Size Diameter Approximation By Katz And Thompson Correlation (d = 1/√(ϕ * k)) weight",
      rules: [
        {rule: "numeric"}
      ]
    },
    {
      column: "Velocity parameter estimated from maximum critical velocity value",
      rules: [
        {rule: "numeric"},
        {rule: "minw", minw: 0}
      ]
    },
    {
      column: "Velocity parameter estimated from maximum critical velocity monitoring date",
      rules: [
        {rule: "date", format: "DD/MM/YYYY", formatRead: "dd/mm/yyyy"}
      ]
    },
    {
      column: "Velocity parameter estimated from maximum critical velocity comment",
      rules: [
        {rule: "textmaxw", maxw: 100},
      ]
    },
    {
      column: "Velocity parameter estimated from maximum critical velocity p10",
      rules: [
        {rule: "numeric"},
        {rule: "min", min: 0}
      ]
    },
    {
      column: "Velocity parameter estimated from maximum critical velocity p90",
      rules: [
        {rule: "numeric"},
        {rule: "min", min: 0},
        {rule: "differentnumber", otherValue: $('#p10_31'), otherField: "p10"}
      ]
    },
    {
      column: "Velocity parameter estimated from maximum critical velocity weight",
      rules: [
        {rule: "numeric"}
      ]
    }
  ];
  
  induce_damage_tab_ruleset = [
    {
      column: "Gross Pay value",
      rules: [
        {rule: "numeric"},
        {rule: "range", min: 0, max: 10000}
      ]
    },
    {
      column: "Gross Pay monitoring date",
      rules: [
        {rule: "date", format: "DD/MM/YYYY", formatRead: "dd/mm/yyyy"}
      ]
    },
    {
      column: "Gross Pay comment",
      rules: [
        {rule: "textmaxw", maxw: 100},
      ]
    },
    {
      column: "Gross Pay p10",
      rules: [
        {rule: "numeric"},
        {rule: "min", min: 0}
      ]
    },
    {
      column: "Gross Pay p90",
      rules: [
        {rule: "numeric"},
        {rule: "min", min: 0},
        {rule: "differentnumber", otherValue: $('#p10_19'), otherField: "p10"}
      ]
    },
    {
      column: "Gross Pay weight",
      rules: [
        {rule: "numeric"}
      ]
    },
    {
      column: "Total polymer pumped during Hydraulic Fracturing value",
      rules: [
        {rule: "numeric"},
        {rule: "min", min: 0}
      ]
    },
    {
      column: "Total polymer pumped during Hydraulic Fracturing monitoring date",
      rules: [
        {rule: "date", format: "DD/MM/YYYY", formatRead: "dd/mm/yyyy"}
      ]
    },
    {
      column: "Total polymer pumped during Hydraulic Fracturing comment",
      rules: [
        {rule: "textmaxw", maxw: 100},
      ]
    },
    {
      column: "Total polymer pumped during Hydraulic Fracturing p10",
      rules: [
        {rule: "numeric"},
        {rule: "min", min: 0}
      ]
    },
    {
      column: "Total polymer pumped during Hydraulic Fracturing p90",
      rules: [
        {rule: "numeric"},
        {rule: "min", min: 0},
        {rule: "differentnumber", otherValue: $('#p10_20'), otherField: "p10"}
      ]
    },
    {
      column: "Total polymer pumped during Hydraulic Fracturing weight",
      rules: [
        {rule: "numeric"}
      ]
    },
    {
      column: "Total volume of water based fluids pumped into the well value",
      rules: [
        {rule: "numeric"},
        {rule: "range", min: 0, max: 1000000}
      ]
    },
    {
      column: "Total volume of water based fluids pumped into the well monitoring date",
      rules: [
        {rule: "date", format: "DD/MM/YYYY", formatRead: "dd/mm/yyyy"}
      ]
    },
    {
      column: "Total volume of water based fluids pumped into the well comment",
      rules: [
        {rule: "textmaxw", maxw: 100},
      ]
    },
    {
      column: "Total volume of water based fluids pumped into the well p10",
      rules: [
        {rule: "numeric"},
        {rule: "min", min: 0}
      ]
    },
    {
      column: "Total volume of water based fluids pumped into the well p90",
      rules: [
        {rule: "numeric"},
        {rule: "min", min: 0},
        {rule: "differentnumber", otherValue: $('#p10_21'), otherField: "p10"}
      ]
    },
    {
      column: "Total volume of water based fluids pumped into the well weight",
      rules: [
        {rule: "numeric"}
      ]
    },
    {
      column: "Mud Losses value",
      rules: [
        {rule: "numeric"},
        {rule: "range", min: 0, max: 10000}
      ]
    },
    {
      column: "Mud Losses monitoring date",
      rules: [
        {rule: "date", format: "DD/MM/YYYY", formatRead: "dd/mm/yyyy"}
      ]
    },
    {
      column: "Mud Losses comment",
      rules: [
        {rule: "textmaxw", maxw: 100},
      ]
    },
    {
      column: "Mud Losses p10",
      rules: [
        {rule: "numeric"},
        {rule: "min", min: 0}
      ]
    },
    {
      column: "Mud Losses p90",
      rules: [
        {rule: "numeric"},
        {rule: "min", min: 0},
        {rule: "differentnumber", otherValue: $('#p10_22'), otherField: "p10"}
      ]
    },
    {
      column: "Mud Losses weight",
      rules: [
        {rule: "numeric"}
      ]
    },
  ];
  
  geomechanical_damage_tab_ruleset = [
    {
      column: "Percentage of Net Pay exihibiting Natural value",
      rules: [
        {rule: "numeric"},
        {rule: "range", min: 0, max: 1}
      ]
    },
    {
      column: "Percentage of Net Pay exihibiting Natural monitoring date",
      rules: [
        {rule: "date", format: "DD/MM/YYYY", formatRead: "dd/mm/yyyy"}
      ]
    },
    {
      column: "Percentage of Net Pay exihibiting Natural comment",
      rules: [
        {rule: "textmaxw", maxw: 100},
      ]
    },
    {
      column: "Percentage of Net Pay exihibiting Natural p10",
      rules: [
        {rule: "numeric"},
        {rule: "min", min: 0}
      ]
    },
    {
      column: "Percentage of Net Pay exihibiting Natural p90",
      rules: [
        {rule: "numeric"},
        {rule: "min", min: 0},
        {rule: "differentnumber", otherValue: $('#p10_23'), otherField: "p10"}
      ]
    },
    {
      column: "Percentage of Net Pay exihibiting Natural weight",
      rules: [
        {rule: "numeric"}
      ]
    },
    {
      column: "Drawdown, i.e, reservoir pressure minus BHFP value",
      rules: [
        {rule: "numeric"},
        {rule: "range", min: 0, max: 10000}
      ]
    },
    {
      column: "Drawdown, i.e, reservoir pressure minus BHFP monitoring date",
      rules: [
        {rule: "date", format: "DD/MM/YYYY", formatRead: "dd/mm/yyyy"}
      ]
    },
    {
      column: "Drawdown, i.e, reservoir pressure minus BHFP comment",
      rules: [
        {rule: "textmaxw", maxw: 100},
      ]
    },
    {
      column: "Drawdown, i.e, reservoir pressure minus BHFP p10",
      rules: [
        {rule: "numeric"},
        {rule: "min", min: 0}
      ]
    },
    {
      column: "Drawdown, i.e, reservoir pressure minus BHFP p90",
      rules: [
        {rule: "numeric"},
        {rule: "min", min: 0},
        {rule: "differentnumber", otherValue: $('#p10_24'), otherField: "p10"}
      ]
    },
    {
      column: "Drawdown, i.e, reservoir pressure minus BHFP weight",
      rules: [
        {rule: "numeric"}
      ]
    },
    {
      column: "Ratio of KH)matrix + fracture / KH)matrix value",
      rules: [
        {rule: "numeric"}
      ]
    },
    {
      column: "Ratio of KH)matrix + fracture / KH)matrix monitoring date",
      rules: [
        {rule: "date", format: "DD/MM/YYYY", formatRead: "dd/mm/yyyy"}
      ]
    },
    {
      column: "Ratio of KH)matrix + fracture / KH)matrix comment",
      rules: [
        {rule: "textmaxw", maxw: 100},
      ]
    },
    {
      column: "Ratio of KH)matrix + fracture / KH)matrix p10",
      rules: [
        {rule: "numeric"}
      ]
    },
    {
      column: "Ratio of KH)matrix + fracture / KH)matrix p90",
      rules: [
        {rule: "numeric"},
        {rule: "differentnumber", otherValue: $('#p10_25'), otherField: "p10"}
      ]
    },
    {
      column: "Ratio of KH)matrix + fracture / KH)matrix weight",
      rules: [
        {rule: "numeric"}
      ]
    },
    {
      column: "Geomechanical Damage Expressed As Fraction Of Base Permeability At BHFP value",
      rules: [
        {rule: "numeric"},
        {rule: "range", min: 0, max: 1}
      ]
    },
    {
      column: "Geomechanical Damage Expressed As Fraction Of Base Permeability At BHFP monitoring date",
      rules: [
        {rule: "date", format: "DD/MM/YYYY", formatRead: "dd/mm/yyyy"}
      ]
    },
    {
      column: "Geomechanical Damage Expressed As Fraction Of Base Permeability At BHFP comment",
      rules: [
        {rule: "textmaxw", maxw: 100},
      ]
    },
    {
      column: "Geomechanical Damage Expressed As Fraction Of Base Permeability At BHFP p10",
      rules: [
        {rule: "numeric"},
        {rule: "min", min: 0}
      ]
    },
    {
      column: "Geomechanical Damage Expressed As Fraction Of Base Permeability At BHFP p90",
      rules: [
        {rule: "numeric"},
        {rule: "min", min: 0},
        {rule: "differentnumber", otherValue: $('#p10_26'), otherField: "p10"}
      ]
    },
    {
      column: "Geomechanical Damage Expressed As Fraction Of Base Permeability At BHFP weight",
      rules: [
        {rule: "numeric"}
      ]
    },
  ];
  </script>