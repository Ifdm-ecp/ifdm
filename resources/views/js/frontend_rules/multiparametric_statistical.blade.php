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
        {rule: "numeric"}
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
        {rule: "numeric"}
      ]
    },
    {
      column: "Scale Index Of CaCO3 p90",
      rules: [
        {rule: "numeric"},
        {rule: "differentnumber", otherValue: $('p10_1').val(), otherField: "p10"}
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
        {rule: "numeric"}
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
        {rule: "numeric"}
      ]
    },
    {
      column: "Scale Index Of BaSO4 p90",
      rules: [
        {rule: "numeric"},
        {rule: "differentnumber", otherValue: $('p10_2').val(), otherField: "p10"}
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
        {rule: "numeric"}
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
        {rule: "numeric"}
      ]
    },
    {
      column: "Scale Index Of Iron Scales p90",
      rules: [
        {rule: "numeric"},
        {rule: "differentnumber", otherValue: $('p10_3').val(), otherField: "p10"}
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
        {rule: "numeric"}
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
        {rule: "numeric"}
      ]
    },
    {
      column: "Backflow [Ca] p90",
      rules: [
        {rule: "numeric"},
        {rule: "differentnumber", otherValue: $('p10_4').val(), otherField: "p10"}
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
        {rule: "numeric"}
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
        {rule: "numeric"}
      ]
    },
    {
      column: "Backflow [Ba] p90",
      rules: [
        {rule: "numeric"},
        {rule: "differentnumber", otherValue: $('p10_5').val(), otherField: "p10"}
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
        {rule: "numeric"}
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
        {rule: "numeric"}
      ]
    },
    {
      column: "[Al] on Produced Water p90",
      rules: [
        {rule: "numeric"},
        {rule: "differentnumber", otherValue: $('p10_6').val(), otherField: "p10"}
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
        {rule: "numeric"}
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
        {rule: "numeric"}
      ]
    },
    {
      column: "[Si] on produced water p90",
      rules: [
        {rule: "numeric"},
        {rule: "differentnumber", otherValue: $('p10_7').val(), otherField: "p10"}
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
        {rule: "numeric"}
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
        {rule: "numeric"}
      ]
    },
    {
      column: "Critical Radius derived from maximum critical velocity, Vc p90",
      rules: [
        {rule: "numeric"},
        {rule: "differentnumber", otherValue: $('p10_8').val(), otherField: "p10"}
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
        {rule: "numeric"}
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
        {rule: "numeric"}
      ]
    },
    {
      column: "Mineralogy Factor p90",
      rules: [
        {rule: "numeric"},
        {rule: "differentnumber", otherValue: $('p10_9').val(), otherField: "p10"}
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
        {rule: "numeric"}
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
        {rule: "numeric"}
      ]
    },
    {
      column: "Mass of crushed proppant inside Hydraulic Fractures p90",
      rules: [
        {rule: "numeric"},
        {rule: "differentnumber", otherValue: $('p10_10').val(), otherField: "p10"}
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
        {rule: "numeric"}
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
        {rule: "numeric"}
      ]
    },
    {
      column: "CII Factor: Colloidal Instability Index p90",
      rules: [
        {rule: "numeric"},
        {rule: "differentnumber", otherValue: $('p10_11').val(), otherField: "p10"}
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
        {rule: "numeric"}
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
        {rule: "numeric"}
      ]
    },
    {
      column: "Volume of HCL pumped into the formation p90",
      rules: [
        {rule: "numeric"},
        {rule: "differentnumber", otherValue: $('p10_30').val(), otherField: "p10"}
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
        {rule: "numeric"}
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
        {rule: "numeric"}
      ]
    },
    {
      column: "Cumulative Gas Produced p90",
      rules: [
        {rule: "numeric"},
        {rule: "differentnumber", otherValue: $('p10_12').val(), otherField: "p10"}
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
        {rule: "numeric"}
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
        {rule: "numeric"}
      ]
    },
    {
      column: "Number Of Days Below Saturation Pressure p90",
      rules: [
        {rule: "numeric"},
        {rule: "differentnumber", otherValue: $('p10_13').val(), otherField: "p10"}
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
        {rule: "differentnumber", otherValue: $('p10_14').val(), otherField: "p10"}
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
        {rule: "numeric"}
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
        {rule: "numeric"}
      ]
    },
    {
      column: "Number Of Days Below Saturation Pressure p90",
      rules: [
        {rule: "numeric"},
        {rule: "differentnumber", otherValue: $('p10_15').val(), otherField: "p10"}
      ]
    },
    {
      column: "Number Of Days Below Saturation Pressure weight",
      rules: [
        {rule: "numeric"}
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
        {rule: "date", format: "DD/MM/YYYY", formatRead: "dd/mm/yyyy"}
      ]
    },
    {
      column: "Delta Pressure From Saturation Pressure comment",
      rules: [
        {rule: "textmaxw", maxw: 100},
      ]
    },
    {
      column: "Delta Pressure From Saturation Pressure p10",
      rules: [
        {rule: "numeric"}
      ]
    },
    {
      column: "Delta Pressure From Saturation Pressure p90",
      rules: [
        {rule: "numeric"},
        {rule: "differentnumber", otherValue: $('p10_16').val(), otherField: "p10"}
      ]
    },
    {
      column: "Delta Pressure From Saturation Pressure weight",
      rules: [
        {rule: "numeric"}
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
        {rule: "numeric"}
      ]
    },
    {
      column: "Cumulative Water Produced p90",
      rules: [
        {rule: "numeric"},
        {rule: "differentnumber", otherValue: $('p10_17').val(), otherField: "p10"}
      ]
    },
    {
      column: "Cumulative Water Produced weight",
      rules: [
        {rule: "numeric"}
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
        {rule: "date", format: "DD/MM/YYYY", formatRead: "dd/mm/yyyy"}
      ]
    },
    {
      column: "Pore Size Diameter Approximation By Katz And Thompson Correlation comment",
      rules: [
        {rule: "textmaxw", maxw: 100},
      ]
    },
    {
      column: "Pore Size Diameter Approximation By Katz And Thompson Correlation p10",
      rules: [
        {rule: "numeric"}
      ]
    },
    {
      column: "Pore Size Diameter Approximation By Katz And Thompson Correlation p90",
      rules: [
        {rule: "numeric"},
        {rule: "differentnumber", otherValue: $('p10_18').val(), otherField: "p10"}
      ]
    },
    {
      column: "Pore Size Diameter Approximation By Katz And Thompson Correlation weight",
      rules: [
        {rule: "numeric"}
      ]
    },
  ];
  
  induce_damage_tab_ruleset = [
    {
      column: "Gross Pay value",
      rules: [
        {rule: "numeric"}
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
        {rule: "numeric"}
      ]
    },
    {
      column: "Gross Pay p90",
      rules: [
        {rule: "numeric"},
        {rule: "differentnumber", otherValue: $('p10_19').val(), otherField: "p10"}
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
        {rule: "numeric"}
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
        {rule: "numeric"}
      ]
    },
    {
      column: "Total polymer pumped during Hydraulic Fracturing p90",
      rules: [
        {rule: "numeric"},
        {rule: "differentnumber", otherValue: $('p10_20').val(), otherField: "p10"}
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
        {rule: "numeric"}
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
        {rule: "numeric"}
      ]
    },
    {
      column: "Total volume of water based fluids pumped into the well p90",
      rules: [
        {rule: "numeric"},
        {rule: "differentnumber", otherValue: $('p10_21').val(), otherField: "p10"}
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
        {rule: "numeric"}
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
        {rule: "numeric"}
      ]
    },
    {
      column: "Mud Losses p90",
      rules: [
        {rule: "numeric"},
        {rule: "differentnumber", otherValue: $('p10_22').val(), otherField: "p10"}
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
        {rule: "numeric"}
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
        {rule: "numeric"}
      ]
    },
    {
      column: "Percentage of Net Pay exihibiting Natural p90",
      rules: [
        {rule: "numeric"},
        {rule: "differentnumber", otherValue: $('p10_23').val(), otherField: "p10"}
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
        {rule: "numeric"}
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
        {rule: "numeric"}
      ]
    },
    {
      column: "Drawdown, i.e, reservoir pressure minus BHFP p90",
      rules: [
        {rule: "numeric"},
        {rule: "differentnumber", otherValue: $('p10_24').val(), otherField: "p10"}
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
        {rule: "differentnumber", otherValue: $('p10_25').val(), otherField: "p10"}
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
        {rule: "numeric"}
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
        {rule: "numeric"}
      ]
    },
    {
      column: "Geomechanical Damage Expressed As Fraction Of Base Permeability At BHFP p90",
      rules: [
        {rule: "numeric"},
        {rule: "differentnumber", otherValue: $('p10_26').val(), otherField: "p10"}
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