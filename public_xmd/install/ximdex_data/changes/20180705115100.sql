ALTER TABLE `Actions` CHANGE `IdNodeType` `IdNodeType` INT(12) UNSIGNED NULL DEFAULT NULL;
UPDATE `Actions` SET `IdNodeType` = NULL WHERE `Actions`.`IdAction` = 6000;
DELETE FROM `Actions` WHERE `IdNodeType` NOT IN (SELECT `IdNodeType` FROM NodeTypes);
ALTER TABLE `Actions` ENGINE = InnoDB;
ALTER TABLE `Actions` CHANGE `IdNodeType` `IdNodeType` INT(12) UNSIGNED NULL DEFAULT NULL;
UPDATE `Actions` SET `IdNodeType` = NULL WHERE `Actions`.`IdAction` = 6000;
DELETE FROM Actions
WHERE IdNodeType NOT IN (
    SELECT IdNodeType FROM `NodeTypes`
);
ALTER TABLE `Actions` ADD CONSTRAINT `Actions_NodeTypes` FOREIGN KEY (`IdNodeType`) 
    REFERENCES `NodeTypes`(`IdNodeType`) ON DELETE RESTRICT ON UPDATE RESTRICT;
