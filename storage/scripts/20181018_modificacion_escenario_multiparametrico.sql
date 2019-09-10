/*
controladores

App/Http/Controllers/MultiparametricAnalysis/StatisticalController.php
											/AnalyticalController.php

----------------------------------------------------------
Models

App/Models/MultiparametricAnalysis/Statistical.php
						   /Analytical.php

---------------------------------------------------------

Requests

App/Http/Request/MultiparametricAnalysis/StatisticalRequest.php
											/AnalyticalRequest.php

---------------------------------------------------------

vistas

Resource/Views/multiparametricAnalysis/statistical/index.blade.php
												  /create.blade.php
									  /analytical/index.blade.php
									  			 /create.blade.php

-----------------------------------------------------------
Rutas

Route::Resource('statistical', 'MultiparametricAnalysis/StatisticalController');
Route::Resource('analytical', 'MultiparametricAnalysis/AnalyticalController');

----------------------------------------------------------
se crea un nuevo campo en la tabla escenarios*/

ALTER TABLE `escenarios` ADD `multiparametricType` VARCHAR(200) NULL AFTER `asphaltene_type`;


/* se crea 2 bases de datos 

1 multiparametric_analysis_statistical
*/

CREATE TABLE `multiparametric_analysis_statistical` 
( 
	`id` INT NOT NULL AUTO_INCREMENT , 
	`statistical` TEXT NULL , 
	`basin_statistical` INT NULL , 
	`field_statistical` TEXT NULL , 
	`ms1` DOUBLE NULL DEFAULT '0', 
	`ms2` DOUBLE NULL DEFAULT '0', 
	`ms3` DOUBLE NULL DEFAULT '0', 
	`ms4` DOUBLE NULL DEFAULT '0', 
	`ms5` DOUBLE NULL DEFAULT '0', 
	`fb1` DOUBLE NULL DEFAULT '0', 
	`fb2` DOUBLE NULL DEFAULT '0', 
	`fb3` DOUBLE NULL DEFAULT '0', 
	`fb4` DOUBLE NULL DEFAULT '0', 
	`fb5` DOUBLE NULL DEFAULT '0', 
	`os1` DOUBLE NULL DEFAULT '0', 
	`os2` DOUBLE NULL DEFAULT '0', 
	`os3` DOUBLE NULL DEFAULT '0', 
	`os4` DOUBLE NULL DEFAULT '0', 
	`rp1` DOUBLE NULL DEFAULT '0', 
	`rp2` DOUBLE NULL DEFAULT '0', 
	`rp3` DOUBLE NULL DEFAULT '0', 
	`rp4` DOUBLE NULL DEFAULT '0', 
	`id1` DOUBLE NULL DEFAULT '0', 
	`id2` DOUBLE NULL DEFAULT '0', 
	`id3` DOUBLE NULL DEFAULT '0', 
	`id4` DOUBLE NULL DEFAULT '0', 
	`gd1` DOUBLE NULL DEFAULT '0', 
	`gd2` DOUBLE NULL DEFAULT '0', 
	`gd3` DOUBLE NULL DEFAULT '0', 
	`gd4` DOUBLE NULL DEFAULT '0', 
	`kd` DOUBLE NULL DEFAULT '0', 
	`date_ms1` DATE NULL , 
	`date_ms2` DATE NULL , 
	`date_ms3` DATE NULL , 
	`date_ms4` DATE NULL , 
	`date_ms5` DATE NULL , 
	`comment_ms1` TEXT NULL , 
	`comment_ms2` TEXT NULL , 
	`comment_ms3` TEXT NULL , 
	`comment_ms4` TEXT NULL , 
	`comment_ms5` TEXT NULL , 
	`date_fb1` DATE NULL , 
	`date_fb2` DATE NULL , 
	`date_fb3` DATE NULL , 
	`date_fb4` DATE NULL , 
	`date_fb5` DATE NULL , 
	`comment_fb1` TEXT NULL , 
	`comment_fb2` TEXT NULL , 
	`comment_fb3` TEXT NULL , 
	`comment_fb4` TEXT NULL , 
	`comment_fb5` TEXT NULL , 
	`date_os1` DATE NULL , 
	`date_os2` DATE NULL , 
	`date_os3` DATE NULL , 
	`date_os4` DATE NULL , 
	`comment_os1` TEXT NULL , 
	`comment_os2` TEXT NULL , 
	`comment_os3` TEXT NULL , 
	`comment_os4` TEXT NULL , 
	`date_rp1` DATE NULL , 
	`date_rp2` DATE NULL , 
	`date_rp3` DATE NULL , 
	`date_rp4` DATE NULL , 
	`comment_rp1` TEXT NULL , 
	`comment_rp2` TEXT NULL , 
	`comment_rp3` TEXT NULL , 
	`comment_rp4` TEXT NULL , 
	`date_id1` DATE NULL , 
	`date_id2` DATE NULL , 
	`date_id3` DATE NULL , 
	`date_id4` DATE NULL , 
	`comment_id1` TEXT NULL , 
	`comment_id2` TEXT NULL , 
	`comment_id3` TEXT NULL , 
	`comment_id4` TEXT NULL , 
	`date_gd1` DATE NULL , 
	`date_gd2` DATE NULL , 
	`date_gd3` DATE NULL , 
	`date_gd4` DATE NULL , 
	`comment_gd1` TEXT NULL , 
	`comment_gd2` TEXT NULL , 
	`comment_gd3` TEXT NULL , 
	`comment_gd4` TEXT NULL ,
	`p10_ms1` DOUBLE NULL DEFAULT '0', 
	`p10_ms2` DOUBLE NULL DEFAULT '0', 
	`p10_ms3` DOUBLE NULL DEFAULT '0', 
	`p10_ms4` DOUBLE NULL DEFAULT '0', 
	`p10_ms5` DOUBLE NULL DEFAULT '0', 
	`p10_fb1` DOUBLE NULL DEFAULT '0', 
	`p10_fb2` DOUBLE NULL DEFAULT '0', 
	`p10_fb3` DOUBLE NULL DEFAULT '0', 
	`p10_fb4` DOUBLE NULL DEFAULT '0', 
	`p10_fb5` DOUBLE NULL DEFAULT '0', 
	`p10_os1` DOUBLE NULL DEFAULT '0', 
	`p10_os2` DOUBLE NULL DEFAULT '0', 
	`p10_os3` DOUBLE NULL DEFAULT '0', 
	`p10_os4` DOUBLE NULL DEFAULT '0', 
	`p10_rp1` DOUBLE NULL DEFAULT '0', 
	`p10_rp2` DOUBLE NULL DEFAULT '0', 
	`p10_rp3` DOUBLE NULL DEFAULT '0', 
	`p10_rp4` DOUBLE NULL DEFAULT '0', 
	`p10_id1` DOUBLE NULL DEFAULT '0', 
	`p10_id2` DOUBLE NULL DEFAULT '0', 
	`p10_id3` DOUBLE NULL DEFAULT '0', 
	`p10_id4` DOUBLE NULL DEFAULT '0', 
	`p10_gd1` DOUBLE NULL DEFAULT '0', 
	`p10_gd2` DOUBLE NULL DEFAULT '0', 
	`p10_gd3` DOUBLE NULL DEFAULT '0', 
	`p10_gd4` DOUBLE NULL DEFAULT '0',
	`p90_ms1` DOUBLE NULL DEFAULT '0', 
	`p90_ms2` DOUBLE NULL DEFAULT '0', 
	`p90_ms3` DOUBLE NULL DEFAULT '0', 
	`p90_ms4` DOUBLE NULL DEFAULT '0', 
	`p90_ms5` DOUBLE NULL DEFAULT '0', 
	`p90_fb1` DOUBLE NULL DEFAULT '0', 
	`p90_fb2` DOUBLE NULL DEFAULT '0', 
	`p90_fb3` DOUBLE NULL DEFAULT '0', 
	`p90_fb4` DOUBLE NULL DEFAULT '0', 
	`p90_fb5` DOUBLE NULL DEFAULT '0', 
	`p90_os1` DOUBLE NULL DEFAULT '0', 
	`p90_os2` DOUBLE NULL DEFAULT '0', 
	`p90_os3` DOUBLE NULL DEFAULT '0', 
	`p90_os4` DOUBLE NULL DEFAULT '0', 
	`p90_rp1` DOUBLE NULL DEFAULT '0', 
	`p90_rp2` DOUBLE NULL DEFAULT '0', 
	`p90_rp3` DOUBLE NULL DEFAULT '0', 
	`p90_rp4` DOUBLE NULL DEFAULT '0', 
	`p90_id1` DOUBLE NULL DEFAULT '0', 
	`p90_id2` DOUBLE NULL DEFAULT '0', 
	`p90_id3` DOUBLE NULL DEFAULT '0', 
	`p90_id4` DOUBLE NULL DEFAULT '0', 
	`p90_gd1` DOUBLE NULL DEFAULT '0', 
	`p90_gd2` DOUBLE NULL DEFAULT '0', 
	`p90_gd3` DOUBLE NULL DEFAULT '0', 
	`p90_gd4` DOUBLE NULL DEFAULT '0', 
	`msAvailable` VARCHAR(200) NULL DEFAULT '1,2,3,4,5', 
	`fbAvailable` VARCHAR(200) NULL DEFAULT '1,2,3,4,5', 
	`osAvailable` VARCHAR(200) NULL DEFAULT '1,2,3,4', 
	`rpAvailable` VARCHAR(200) NULL DEFAULT '1,2,3,4', 
	`idAvailable` VARCHAR(200) NULL DEFAULT '1,2,3,4', 
	`gdAvailable` VARCHAR(200) NULL DEFAULT '1,2,3,4', 
	`escenario_id` INT NOT NULL ,
	    PRIMARY KEY (`id`), 
	    INDEX (`escenario_id`),
	    FOREIGN KEY (`escenario_id`) REFERENCES escenarios(`id`)) ENGINE = InnoDB;

/*CONVERTIR  multiparametric_id en foreign key*/
	ALTER TABLE `subparameters_weight`
	ADD COLUMN `multiparametric_id` INT NOT NULL,
	ADD INDEX (`multiparametric_id`),
	ADD  FOREIGN KEY (`multiparametric_id`) REFERENCES multiparametric_analysis_statistical(`id`);

/*
	1 multiparametric_analysis_analytical
*/

CREATE TABLE `multiparametric_analysis_analytical` 
( 
	`id` INT NOT NULL AUTO_INCREMENT , 
	`escenario_id` INT NOT NULL ,
	`netpay` DOUBLE NULL DEFAULT '0', 
	`absolute_permeability` DOUBLE NULL DEFAULT '0', 
	`fluid_type` TEXT NULL ,  
	`viscosity` DOUBLE NULL DEFAULT '0', 
	`bhp` DOUBLE NULL DEFAULT '0', 
	`porosity` DOUBLE NULL DEFAULT '0', 
	`volumetric_factor` DOUBLE NULL DEFAULT '0', 
	`well_radius` DOUBLE NULL DEFAULT '0', 
	`drainage_radius` DOUBLE NULL DEFAULT '0', 
	`reservoir_pressure` DOUBLE NULL DEFAULT '0', 
	`fluid_rate` DOUBLE NULL DEFAULT '0', 
	`critical_radius` DOUBLE NULL DEFAULT '0', 
	`total_volumen` DOUBLE NULL DEFAULT '0', 
	`saturation_presure` DOUBLE NULL DEFAULT '0', 
	`mineral_scale_cp` DOUBLE NULL DEFAULT '0', 
	`organic_scale_cp` DOUBLE NULL DEFAULT '0', 
	`geomechanical_damage_cp` DOUBLE NULL DEFAULT '0', 
	`mineral_scale_kd` DOUBLE NULL DEFAULT '0', 
	`organic_scale_kd` DOUBLE NULL DEFAULT '0', 
	`geomechanical_damage_kd` DOUBLE NULL DEFAULT '0', 
	`fines_blockage_kd` DOUBLE NULL DEFAULT '0', 
	`relative_permeability_kd` DOUBLE NULL DEFAULT '0', 
	`induced_damage_kd` DOUBLE NULL DEFAULT '0', 
		PRIMARY KEY (`id`), 
	    INDEX (`escenario_id`),
	    FOREIGN KEY (`escenario_id`) REFERENCES escenarios(`id`)) ENGINE = InnoDB;