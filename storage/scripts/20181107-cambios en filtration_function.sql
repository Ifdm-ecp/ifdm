#se le agregan 6 campos a la tabla `d_filtration_function` (ph, lplt_filtrate, hpht_filtrate, plastic_viscosity, yield_point, gel_strength)

ALTER TABLE `d_filtration_function`
ADD `ph` DECIMAL NULL DEFAULT NULL AFTER `method`, 
ADD `lplt_filtrate` DOUBLE(21,10) NULL DEFAULT NULL AFTER `ph`, 
ADD `hpht_filtrate` DOUBLE(21,10) NULL DEFAULT NULL AFTER `lplt_filtrate`,
ADD `plastic_viscosity` DOUBLE(21,10) NOT NULL AFTER `hpht_filtrate`, 
ADD `yield_point` DOUBLE(21,10) NOT NULL AFTER `plastic_viscosity`, 
ADD `gel_strength` DOUBLE(21,10) NULL DEFAULT NULL AFTER `yield_point`;

#se crea una carpeta en la vista con el nombre de filtrationFunction alli se dejaran 
#los dos formularios create y edit y una carpeta form donde se almacenaran los campos de los formularios


#se crea una nueva tabla llmada mud_composicion relacionada de 1 a muchos con  `d_filtration_function`
CREATE TABLE `ifdm`.`mud_composicion` ( 
`id` INT NOT NULL AUTO_INCREMENT , 
`d_filtration_function_id` INT NOT NULL , 
`component` DOUBLE(10,2) NOT NULL , 
`concentraction` DOUBLE(10,2) NOT NULL , 
PRIMARY KEY (`id`), INDEX (`d_filtration_function_id`)) ENGINE = InnoDB;

#se agrega la relacion de la llave foranea
ALTER TABLE `mud_composicion` ADD CONSTRAINT `d_filtration_function_id` FOREIGN KEY (`d_filtration_function_id`) REFERENCES `d_filtration_function`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;