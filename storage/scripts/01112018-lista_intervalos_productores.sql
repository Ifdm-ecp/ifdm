-- Creacion catalogo intervalos productores

CREATE TABLE `lista_intervalo_productor` ( `id` INT NOT NULL AUTO_INCREMENT , `formacion_id` INT NOT NULL , `nombre` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL , `created_at` DATE NOT NULL , `updated_at` DATE NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;
ALTER TABLE `lista_intervalo_productor` ADD CONSTRAINT `fk_formacion_id` FOREIGN KEY (`formacion_id`) REFERENCES `formaciones`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- Quitando netpay de formacion

ALTER TABLE `formaciones`
  DROP `netpay`;

