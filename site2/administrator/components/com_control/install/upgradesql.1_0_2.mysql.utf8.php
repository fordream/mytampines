-- <?php /** $Id: upgradesql.1_0_2.mysql.utf8.php 1055 2008-10-31 00:01:58Z eddieajau $ */ defined('_JEXEC') or die() ?>;

CREATE TABLE IF NOT EXISTS `#__jxcontrol` (
  `version` varchar(16) NOT NULL COMMENT 'Version number',
  `installed_date` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP COMMENT 'Date-time installed',
  `log` mediumtext,
  PRIMARY KEY  (`version`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Version history';

ALTER TABLE `#__core_acl_aco`
 ADD COLUMN `allow_axos` INT(1) UNSIGNED NOT NULL DEFAULT 0 AFTER `hidden`;

ALTER TABLE `#__core_acl_aco`
 ADD COLUMN `note` MEDIUMTEXT AFTER `allow_axos`;

ALTER TABLE `#__core_acl_aco`
 ADD INDEX #__core_acl_axo_section(`allow_axos`, `section_value`);
