<?php
/**
 * @file AddCourse.sql
 *
 * @author Till Uhlig <till.uhlig@student.uni-halle.de>
 * @date 2015
 */
?>

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Table `Setting`
-- -----------------------------------------------------
<?php $tableName = 'Setting'.$pre.'_'.$object->getId(); ?>
CREATE TABLE IF NOT EXISTS `<?php echo $tableName;?>` (
  `SET_id` INT NOT NULL AUTO_INCREMENT,
  `SET_name` VARCHAR(255) NOT NULL,
  `SET_state` VARCHAR(255) NOT NULL DEFAULT '',
  `SET_type` VARCHAR(255) NOT NULL DEFAULT 'TEXT',
  `SET_category` VARCHAR(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`SET_id`),
  UNIQUE INDEX `SET_id_UNIQUE` USING BTREE (`SET_id` ASC),
  UNIQUE INDEX `SET_name_UNIQUE` USING BTREE (`SET_name` ASC))
ENGINE = InnoDB
AUTO_INCREMENT = 1;

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

call execute_if_column_not_exists('<?php echo $tableName;?>','SET_category','ALTER TABLE `<?php echo $tableName;?>` ADD COLUMN SET_category VARCHAR(255) NOT NULL DEFAULT \'\';');

ALTER IGNORE TABLE `<?php echo $tableName;?>` MODIFY COLUMN SET_state VARCHAR(255) NOT NULL DEFAULT '';
ALTER IGNORE TABLE `<?php echo $tableName;?>` MODIFY COLUMN SET_type VARCHAR(255) NOT NULL DEFAULT 'TEXT';