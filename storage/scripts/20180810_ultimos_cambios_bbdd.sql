-se cambio en la tabla pvt la columna campo_id por formacion-id
	ALTER TABLE `pvt` CHANGE `campo_id` `formacion_id` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;

-se borro las columnas date e intervalo de la tabla plt
	ALTER TABLE 'plt' drop 'date';
	ALTER TABLE 'plt' drop 'intervalo';

-se agrego las columnas top y bottom en la tabla plt 
	ALTER TABLE `plt` ADD `top` FLOAT NOT NULL AFTER `pqw`;
	ALTER TABLE `plt` ADD `bottom` FLOAT NOT NULL AFTER `top`;

-se borra la columna bhp de la tabla pozos
	ALTER TABLE 'pozos' drop 'bhp';