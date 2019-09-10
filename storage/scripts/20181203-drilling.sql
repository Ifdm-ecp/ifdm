ALTER TABLE `drilling` ADD `d_mud_density` DOUBLE NOT NULL AFTER `c_equivalent_circulating_density`;

ALTER TABLE `drilling` ADD `d_equivalent_circulating_density` DOUBLE NOT NULL AFTER `d_mud_density`;

ALTER TABLE `drilling`  ADD `filtration_function_id` INT NOT NULL  AFTER `b_factor`;

ALTER TABLE `drilling` ADD `a_factor` DOUBLE NOT NULL AFTER `filtration_function_id`;

ALTER TABLE `drilling` ADD `b_factor` DOUBLE NOT NULL AFTER `a_factor`;

ALTER TABLE `drilling` ADD `cementingAvailable` ENUM('0','1') AFTER `b_factor`;


ALTER TABLE `drilling` ADD CONSTRAINT `filtration_function_fk_1` FOREIGN KEY (`filtration_function_id`) REFERENCES `d_filtration_function`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `drilling` CHANGE `updated_at` `updated_at` DATE NULL, CHANGE `created_at` `created_at` DATE NULL, CHANGE `d_mud_density` `d_mud_density` DOUBLE NULL, CHANGE `a_factor` `a_factor` DOUBLE NULL, CHANGE `b_factor` `b_factor` DOUBLE NULL, CHANGE `filtration_function_id` `filtration_function_id` INT(11) NULL;
