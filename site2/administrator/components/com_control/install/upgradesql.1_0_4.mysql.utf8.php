-- <?php /** $Id: upgradesql.1_0_4.mysql.utf8.php 1055 2008-10-31 00:01:58Z eddieajau $ */ defined('_JEXEC') or die() ?>;

-- Flatten the AXO groups tree

UPDATE `#__core_acl_axo_groups`
 SET parent_id = 1
 WHERE parent_id > 0;

UPDATE `#__core_acl_axo_groups`
 SET lft = 2, rgt = 3
 WHERE name = 'Public';

UPDATE `#__core_acl_axo_groups`
 SET lft = 4, rgt = 5
 WHERE name = 'Registered';

UPDATE `#__core_acl_axo_groups`
 SET lft = 6, rgt = 7
 WHERE name = 'Special';

-- Insert the public user

INSERT IGNORE INTO `#__core_acl_aro` VALUES (0, 'users', '0', 0, 'Public User', 0);

INSERT IGNORE INTO `#__core_acl_groups_aro_map`
 SET group_id = 29, aro_id = (SELECT aro.id FROM #__core_acl_aro AS aro WHERE `value` = '0');
