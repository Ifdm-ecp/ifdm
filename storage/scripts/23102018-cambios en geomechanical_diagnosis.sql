ALTER TABLE `geomechanical_diagnosis` ADD `top` DOUBLE NOT NULL AFTER `residual_fracture_closure_permeability`, ADD `netpay` DOUBLE NOT NULL AFTER `top`, ADD `viscosity` DOUBLE NOT NULL AFTER `netpay`, ADD `volumetric_factor` DOUBLE NOT NULL AFTER `viscosity`, ADD `rate` DOUBLE NOT NULL AFTER `volumetric_factor`;
ALTER TABLE `pvt` ADD `saturation_pressure` DECIMAL NOT NULL AFTER `id`;
ALTER TABLE `pvt_formacion_x_pozos` ADD `saturation_pressure` DECIMAL NOT NULL AFTER `formacionxpozos_id`;
ALTER TABLE `pvt_globals` ADD `saturation_pressure` DECIMAL NOT NULL AFTER `formacion_id`;
