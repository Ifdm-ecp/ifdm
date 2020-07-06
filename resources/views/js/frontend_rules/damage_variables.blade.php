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
      {rule: "numeric"}
    ]
  },
  {
    column: "Scale Index Of CaCO3 monitoring date",
    rules: [
      {rule: "date", format: !/^\d{2}\/\d{2}\/\d{4}$/, formatRead: "dd/mm/yyyy"}
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
      {rule: "numeric"}
    ]
  },
  {
    column: "Scale Index Of BaSO4 monitoring date",
    rules: [
      {rule: "date", format: !/^\d{2}\/\d{2}\/\d{4}$/, formatRead: "dd/mm/yyyy"}
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
      {rule: "numeric"}
    ]
  },
  {
    column: "Scale Index Of Iron Scales monitoring date",
    rules: [
      {rule: "date", format: !/^\d{2}\/\d{2}\/\d{4}$/, formatRead: "dd/mm/yyyy"}
    ]
  },
  {
    column: "Scale Index Of Iron Scales comment",
    rules: [
      {rule: "textmaxw", maxw: 100},
    ]
  },
  {
    column: "Backflow [Ca] (ppm) value",
    rules: [
      {rule: "numeric"}
    ]
  },
  {
    column: "Backflow [Ca] (ppm) monitoring date",
    rules: [
      {rule: "date", format: !/^\d{2}\/\d{2}\/\d{4}$/, formatRead: "dd/mm/yyyy"}
    ]
  },
  {
    column: "Backflow [Ca] (ppm) comment",
    rules: [
      {rule: "textmaxw", maxw: 100},
    ]
  },
  {
    column: "Backflow [Ba] (ppm) value",
    rules: [
      {rule: "numeric"}
    ]
  },
  {
    column: "Backflow [Ba] (ppm) monitoring date",
    rules: [
      {rule: "date", format: !/^\d{2}\/\d{2}\/\d{4}$/, formatRead: "dd/mm/yyyy"}
    ]
  },
  {
    column: "Backflow [Ba] (ppm) comment",
    rules: [
      {rule: "textmaxw", maxw: 100},
    ]
  }
];

fine_blockage_tab_ruleset = [
  {
    column: "[Al] on Produced Water (ppm) value",
    rules: [
      {rule: "numeric"}
    ]
  },
  {
    column: "[Al] on Produced Water (ppm) monitoring date",
    rules: [
      {rule: "date", format: !/^\d{2}\/\d{2}\/\d{4}$/, formatRead: "dd/mm/yyyy"}
    ]
  },
  {
    column: "[Al] on Produced Water (ppm) comment",
    rules: [
      {rule: "textmaxw", maxw: 100},
    ]
  },
  {
    column: "[Si] on produced water value",
    rules: [
      {rule: "numeric"}
    ]
  },
  {
    column: "[Si] on produced water monitoring date",
    rules: [
      {rule: "date", format: !/^\d{2}\/\d{2}\/\d{4}$/, formatRead: "dd/mm/yyyy"}
    ]
  },
  {
    column: "[Si] on produced water comment",
    rules: [
      {rule: "textmaxw", maxw: 100},
    ]
  },
  {
    column: "Critical Radius derived from maximum critical velocity, Vc (ft) value",
    rules: [
      {rule: "numeric"}
    ]
  },
  {
    column: "Critical Radius derived from maximum critical velocity, Vc (ft) monitoring date",
    rules: [
      {rule: "date", format: !/^\d{2}\/\d{2}\/\d{4}$/, formatRead: "dd/mm/yyyy"}
    ]
  },
  {
    column: "Critical Radius derived from maximum critical velocity, Vc (ft) comment",
    rules: [
      {rule: "textmaxw", maxw: 100},
    ]
  },
  {
    column: "Mineralogy Factor value",
    rules: [
      {rule: "numeric"}
    ]
  },
  {
    column: "Mineralogy Factor monitoring date",
    rules: [
      {rule: "date", format: !/^\d{2}\/\d{2}\/\d{4}$/, formatRead: "dd/mm/yyyy"}
    ]
  },
  {
    column: "Mineralogy Factor comment",
    rules: [
      {rule: "textmaxw", maxw: 100},
    ]
  },
  {
    column: "Mass of crushed proppant inside Hydraulic Fractures (lbs) value",
    rules: [
      {rule: "numeric"}
    ]
  },
  {
    column: "Mass of crushed proppant inside Hydraulic Fractures (lbs) date",
    rules: [
      {rule: "date", format: !/^\d{2}\/\d{2}\/\d{4}$/, formatRead: "dd/mm/yyyy"}
    ]
  },
  {
    column: "Mass of crushed proppant inside Hydraulic Fractures (lbs) comment",
    rules: [
      {rule: "textmaxw", maxw: 100},
    ]
  }
];

organic_scales_tab_ruleset = [
  {
    column: "CII Factor: Colloidal Instability Index value",
    rules: [
      {rule: "numeric"}
    ]
  },
  {
    column: "CII Factor: Colloidal Instability Index monitoring date",
    rules: [
      {rule: "date", format: !/^\d{2}\/\d{2}\/\d{4}$/, formatRead: "dd/mm/yyyy"}
    ]
  },
  {
    column: "CII Factor: Colloidal Instability Index comment",
    rules: [
      {rule: "textmaxw", maxw: 100},
    ]
  },
  {
    column: "Volume of HCL pumped into the formation (bbl) value",
    rules: [
      {rule: "numeric"}
    ]
  },
  {
    column: "Volume of HCL pumped into the formation (bbl) monitoring date",
    rules: [
      {rule: "date", format: !/^\d{2}\/\d{2}\/\d{4}$/, formatRead: "dd/mm/yyyy"}
    ]
  },
  {
    column: "Volume of HCL pumped into the formation (bbl) comment",
    rules: [
      {rule: "textmaxw", maxw: 100},
    ]
  },
  {
    column: "Cumulative Gas Produced value",
    rules: [
      {rule: "numeric"}
    ]
  },
  {
    column: "Cumulative Gas Produced monitoring date",
    rules: [
      {rule: "date", format: !/^\d{2}\/\d{2}\/\d{4}$/, formatRead: "dd/mm/yyyy"}
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
      {rule: "numeric"}
    ]
  },
  {
    column: "Number Of Days Below Saturation Pressure monitoring date",
    rules: [
      {rule: "date", format: !/^\d{2}\/\d{2}\/\d{4}$/, formatRead: "dd/mm/yyyy"}
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
      {rule: "date", format: !/^\d{2}\/\d{2}\/\d{4}$/, formatRead: "dd/mm/yyyy"}
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
      {rule: "numeric"}
    ]
  },
  {
    column: "Number Of Days Below Saturation Pressure monitoring date",
    rules: [
      {rule: "date", format: !/^\d{2}\/\d{2}\/\d{4}$/, formatRead: "dd/mm/yyyy"}
    ]
  },
  {
    column: "Number Of Days Below Saturation Pressure comment",
    rules: [
      {rule: "textmaxw", maxw: 100},
    ]
  },
  {
    column: "Delta Pressure From Saturation Pressure value",
    rules: [
      {rule: "numeric"}
    ]
  },
  {
    column: "Delta Pressure From Saturation Pressure monitoring date",
    rules: [
      {rule: "date", format: !/^\d{2}\/\d{2}\/\d{4}$/, formatRead: "dd/mm/yyyy"}
    ]
  },
  {
    column: "Delta Pressure From Saturation Pressure comment",
    rules: [
      {rule: "textmaxw", maxw: 100},
    ]
  },
  {
    column: "Cumulative Water Produced value",
    rules: [
      {rule: "numeric"}
    ]
  },
  {
    column: "Cumulative Water Produced monitoring date",
    rules: [
      {rule: "date", format: !/^\d{2}\/\d{2}\/\d{4}$/, formatRead: "dd/mm/yyyy"}
    ]
  },
  {
    column: "Cumulative Water Produced comment",
    rules: [
      {rule: "textmaxw", maxw: 100},
    ]
  },
  {
    column: "Pore Size Diameter Approximation By Katz And Thompson Correlation value",
    rules: [
      {rule: "numeric"}
    ]
  },
  {
    column: "Pore Size Diameter Approximation By Katz And Thompson Correlation monitoring date",
    rules: [
      {rule: "date", format: !/^\d{2}\/\d{2}\/\d{4}$/, formatRead: "dd/mm/yyyy"}
    ]
  },
  {
    column: "Pore Size Diameter Approximation By Katz And Thompson Correlation comment",
    rules: [
      {rule: "textmaxw", maxw: 100},
    ]
  }
];

induce_damage_tab_ruleset = [
  {
    column: "Gross Pay (ft) value",
    rules: [
      {rule: "numeric"}
    ]
  },
  {
    column: "Gross Pay (ft) monitoring date",
    rules: [
      {rule: "date", format: !/^\d{2}\/\d{2}\/\d{4}$/, formatRead: "dd/mm/yyyy"}
    ]
  },
  {
    column: "Gross Pay (ft) comment",
    rules: [
      {rule: "textmaxw", maxw: 100},
    ]
  },
  {
    column: "Total polymer pumped during Hydraulic Fracturing (lbs) value",
    rules: [
      {rule: "numeric"}
    ]
  },
  {
    column: "Total polymer pumped during Hydraulic Fracturing (lbs) monitoring date",
    rules: [
      {rule: "date", format: !/^\d{2}\/\d{2}\/\d{4}$/, formatRead: "dd/mm/yyyy"}
    ]
  },
  {
    column: "Total polymer pumped during Hydraulic Fracturing (lbs) comment",
    rules: [
      {rule: "textmaxw", maxw: 100},
    ]
  },
  {
    column: "Total volume of water based fluids pumped into the well (bbl) value",
    rules: [
      {rule: "numeric"}
    ]
  },
  {
    column: "Total volume of water based fluids pumped into the well (bbl) monitoring date",
    rules: [
      {rule: "date", format: !/^\d{2}\/\d{2}\/\d{4}$/, formatRead: "dd/mm/yyyy"}
    ]
  },
  {
    column: "Total volume of water based fluids pumped into the well (bbl) comment",
    rules: [
      {rule: "textmaxw", maxw: 100},
    ]
  },
  {
    column: "Mud Losses value",
    rules: [
      {rule: "numeric"}
    ]
  },
  {
    column: "Mud Losses monitoring date",
    rules: [
      {rule: "date", format: !/^\d{2}\/\d{2}\/\d{4}$/, formatRead: "dd/mm/yyyy"}
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
    column: "Percentage of Net Pay exihibiting Natural (fraction) value",
    rules: [
      {rule: "numeric"}
    ]
  },
  {
    column: "Percentage of Net Pay exihibiting Natural (fraction) monitoring date",
    rules: [
      {rule: "date", format: !/^\d{2}\/\d{2}\/\d{4}$/, formatRead: "dd/mm/yyyy"}
    ]
  },
  {
    column: "Percentage of Net Pay exihibiting Natural (fraction) comment",
    rules: [
      {rule: "textmaxw", maxw: 100},
    ]
  },
  {
    column: "Drawdown, i.e, reservoir pressure minus BHFP (psi) value",
    rules: [
      {rule: "numeric"}
    ]
  },
  {
    column: "Drawdown, i.e, reservoir pressure minus BHFP (psi) monitoring date",
    rules: [
      {rule: "date", format: !/^\d{2}\/\d{2}\/\d{4}$/, formatRead: "dd/mm/yyyy"}
    ]
  },
  {
    column: "Drawdown, i.e, reservoir pressure minus BHFP (psi) comment",
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
      {rule: "date", format: !/^\d{2}\/\d{2}\/\d{4}$/, formatRead: "dd/mm/yyyy"}
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
      {rule: "numeric"}
    ]
  },
  {
    column: "Geomechanical Damage Expressed As Fraction Of Base Permeability At BHFP monitoring date",
    rules: [
      {rule: "date", format: !/^\d{2}\/\d{2}\/\d{4}$/, formatRead: "dd/mm/yyyy"}
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