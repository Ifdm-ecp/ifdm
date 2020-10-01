<script type="text/javascript">
/* Damage variables rulesets
 * This is a set of rules for each table and/or section of the form
 * Each element in the array corresponds to a rule assigned to the column
 * So element 0 has the rules for the column 0 of the table
*/
well_ruleset = [
  {
    column: "Well",
    rules: [
      {rule: "requiredselect"}
    ]
  }
];

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
  }
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
  }
];
</script>