ALTER TABLE ifdm.escenarios DROP FOREIGN KEY escenarios_ibfk_4;
ALTER TABLE ifdm.escenarios DROP INDEX formacion_id;
ALTER TABLE ifdm.escenarios MODIFY COLUMN formacion_id TEXT NOT NULL;