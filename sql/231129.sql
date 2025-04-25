ALTER TABLE `karyawan` ADD `date_joined` DATE NULL DEFAULT NULL;
ALTER TABLE `process` ADD `min_skill` INT NOT NULL DEFAULT '0' AFTER `name`;