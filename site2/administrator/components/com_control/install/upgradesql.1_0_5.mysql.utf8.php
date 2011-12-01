-- <?php /** $Id: upgradesql.1_0_5.mysql.utf8.php 1055 2008-10-31 00:01:58Z eddieajau $ */ defined('_JEXEC') or die() ?>;

ALTER TABLE `#__core_acl_groups_aro_map` ADD INDEX aro_id_group_id_group_aro_map USING BTREE(`aro_id`, `group_id`);