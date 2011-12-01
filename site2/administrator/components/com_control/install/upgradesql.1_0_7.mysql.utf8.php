-- <?php /** $Id: upgradesql.1_0_7.mysql.utf8.php 1175 2009-06-04 00:15:16Z eddieajau $ */ defined('_JEXEC') or die() ?>;

UPDATE `#__core_acl_aco`
 SET allow_axos = allow_axos + 1;

ALTER TABLE `#__core_acl_aco`
 CHANGE COLUMN `allow_axos` `acl_type` INT(1) UNSIGNED NOT NULL DEFAULT 1;

ALTER TABLE `#__core_acl_aco`
 ADD INDEX `#__core_acl_acl_type_section` (`acl_type`, `section_value`);

ALTER TABLE `#__core_acl_acl`
 ADD COLUMN `acl_type` INT(1) UNSIGNED NOT NULL DEFAULT 1;

ALTER TABLE `#__core_acl_acl`
 ADD INDEX `#__core_acl_acl_type` (`acl_type`);

UPDATE `#__core_acl_axo`
 SET value = '-1' WHERE value = 'root';

ALTER TABLE `#__core_acl_axo`
 MODIFY COLUMN `value` INTEGER(10) NOT NULL;

ALTER TABLE `#__core_acl_axo_groups`
 DROP INDEX `#__core_acl_value_axo_groups`,
 ADD INDEX `#__core_acl_value_axo_groups` (`value`);

ALTER TABLE `#__core_acl_aro_groups`
 ADD COLUMN `section_id` INTEGER(10) NOT NULL DEFAULT 0 AFTER `value`,
 ADD INDEX `#__core_acl_section_id` (`section_id`);

ALTER TABLE `#__core_acl_axo_groups`
 ADD COLUMN `section_id` INTEGER(10) NOT NULL DEFAULT 0 AFTER `value`,
 ADD INDEX `#__core_acl_section_id` (`section_id`);

ALTER TABLE `#__core_acl_acl`
 ADD COLUMN `name` VARCHAR(100) NOT NULL DEFAULT '' AFTER `acl_type`,
 ADD INDEX `#__core_acl_name` (`name`);
