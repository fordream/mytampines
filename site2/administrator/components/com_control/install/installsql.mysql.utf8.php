-- <?php /** $Id: installsql.mysql.utf8.php 1175 2009-06-04 00:15:16Z eddieajau $ */ defined('_JEXEC') or die() ?>;

--
-- Table structure for table `#__jxtended`
--

CREATE TABLE IF NOT EXISTS `#__jxtended` (
  `id` int(10) NOT NULL auto_increment,
  `extension` varchar(100) NOT NULL COMMENT 'The extension',
  `version` varchar(16) NOT NULL COMMENT 'Version number',
  `installed_date` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP COMMENT 'Date-time modified or installed',
  `log` mediumtext,
  PRIMARY KEY  USING BTREE (`id`),
  KEY `idx_extension` (`extension`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='JXtended product version history';

--
-- Table structure for table `#__core_acl_acl`
--

CREATE TABLE IF NOT EXISTS `#__core_acl_acl` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `section_value` varchar(230) NOT NULL default 'system',
  `allow` int(1) unsigned NOT NULL default '0',
  `enabled` int(1) unsigned NOT NULL default '0',
  `return_value` varchar(250) default NULL,
  `note` varchar(250) default NULL,
  `updated_date` int(10) unsigned NOT NULL default '0',
  `acl_type` int(1) unsigned NOT NULL default '0',
  `name` VARCHAR(100) NOT NULL DEFAULT '',
  PRIMARY KEY  (`id`),
  KEY `#__core_acl_enabled_acl` (`enabled`),
  KEY `#__core_acl_section_value_acl` (`section_value`),
  KEY `#__core_acl_updated_date_acl` (`updated_date`),
  INDEX `#__core_acl_name` (`name`),
  INDEX `#__core_acl_type` (`acl_type`)
) ENGINE=MyISAM CHARACTER SET `utf8`;

-- --------------------------------------------------------

--
-- Table structure for table `#__core_acl_acl_sections`
--


CREATE TABLE IF NOT EXISTS `#__core_acl_acl_sections` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `value` varchar(100) NOT NULL default '',
  `order_value` int(11) NOT NULL default '0',
  `name` varchar(230) NOT NULL default '',
  `hidden` int(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `#__core_acl_value_acl_sections` (`value`),
  KEY `#__core_acl_hidden_acl_sections` (`hidden`)
) ENGINE=MyISAM CHARACTER SET `utf8`;

INSERT IGNORE INTO `#__core_acl_acl_sections` VALUES (1, 'system', 1, 'System', 0);
INSERT IGNORE INTO `#__core_acl_acl_sections` VALUES (2, 'user', 2, 'User', 0);

-- --------------------------------------------------------


--
-- Table structure for table `#__core_acl_aco`
--

CREATE TABLE IF NOT EXISTS `#__core_acl_aco` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `section_value` varchar(100) NOT NULL default '0',
  `value` varchar(100) NOT NULL default '',
  `order_value` int(11) NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  `hidden` int(1) unsigned NOT NULL default '0',
  `acl_type` int(1) unsigned NOT NULL default '0',
  `note` mediumtext,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `#__core_acl_section_value_aco` (`section_value`,`value`),
  KEY `core_acl_hidden_aco` (`hidden`),
  KEY `core_acl_acl_type_section` (`acl_type`,`section_value`),
  INDEX `#__core_acl_acl_type` (`acl_type`)
) ENGINE=MyISAM CHARACTER SET `utf8`;

-- --------------------------------------------------------

--
-- Table structure for table `#__core_acl_aco_map`
--

CREATE TABLE IF NOT EXISTS `#__core_acl_aco_map` (
  `acl_id` int(10) unsigned NOT NULL default '0',
  `section_value` varchar(100) NOT NULL default '0',
  `value` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`acl_id`,`section_value`,`value`)
) ENGINE=MyISAM CHARACTER SET `utf8`;

-- --------------------------------------------------------

--
-- Table structure for table `#__core_acl_aco_sections`
--

CREATE TABLE IF NOT EXISTS `#__core_acl_aco_sections` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `value` varchar(100) NOT NULL default '',
  `order_value` int(11) NOT NULL default '0',
  `name` varchar(230) NOT NULL default '',
  `hidden` int(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `#__core_acl_value_aco_sections` (`value`),
  KEY `#__core_acl_hidden_aco_sections` (`hidden`)
) ENGINE=MyISAM CHARACTER SET `utf8`;

-- --------------------------------------------------------

--
-- Table structure for table `#__core_acl_aro_map`
--

CREATE TABLE IF NOT EXISTS `#__core_acl_aro_map` (
  `acl_id` int(10) unsigned NOT NULL default '0',
  `section_value` varchar(100) NOT NULL default '0',
  `value` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`acl_id`,`section_value`,`value`)
) ENGINE=MyISAM CHARACTER SET `utf8`;

CREATE TABLE IF NOT EXISTS  `#__core_acl_aro_groups_map` (
  `acl_id` int(11) NOT NULL default '0',
  `group_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`acl_id`,`group_id`)
) ENGINE=MyISAM CHARACTER SET `utf8`;

-- --------------------------------------------------------

--
-- Table structure for table `#__core_acl_axo`
--

CREATE TABLE IF NOT EXISTS `#__core_acl_axo` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `section_value` varchar(100) NOT NULL default '0',
  `value` int(10) NOT NULL,
  `order_value` int(11) NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  `hidden` int(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `#__core_acl_section_value_value_axo` (`section_value`,`value`),
  KEY `#__core_acl_hidden_axo` (`hidden`)
) ENGINE=MyISAM CHARACTER SET `utf8`;

-- --------------------------------------------------------

--
-- Table structure for table `#__core_acl_axo_groups`
--

CREATE TABLE IF NOT EXISTS `#__core_acl_axo_groups` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `parent_id` int(10) unsigned NOT NULL default '0',
  `lft` int(10) unsigned NOT NULL default '0',
  `rgt` int(10) unsigned NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  `value` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`,`value`),
  INDEX `#__core_acl_value_axo_groups` (`value`),
  KEY `#__core_acl_parent_id_axo_groups` (`parent_id`),
  KEY `#__core_acl_lft_rgt_axo_groups` (`lft`,`rgt`)
) ENGINE=MyISAM CHARACTER SET `utf8`;

INSERT IGNORE INTO `#__core_acl_axo_groups` VALUES (1, 0, 1, 8, 'ROOT', 'root');
INSERT IGNORE INTO `#__core_acl_axo_groups` VALUES (2, 1, 2, 3, 'Public', '0');
INSERT IGNORE INTO `#__core_acl_axo_groups` VALUES (3, 1, 4, 5, 'Registered', '1');
INSERT IGNORE INTO `#__core_acl_axo_groups` VALUES (4, 1, 6, 7, 'Special', '2');

-- --------------------------------------------------------

--
-- Table structure for table `#__core_acl_axo_groups_map`
--

CREATE TABLE IF NOT EXISTS `#__core_acl_axo_groups_map` (
  `acl_id` int(10) unsigned NOT NULL default '0',
  `group_id` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`acl_id`,`group_id`)
) ENGINE=MyISAM CHARACTER SET `utf8`;

-- --------------------------------------------------------

--
-- Table structure for table `#__core_acl_axo_map`
--

CREATE TABLE IF NOT EXISTS `#__core_acl_axo_map` (
  `acl_id` int(10) unsigned NOT NULL default '0',
  `section_value` varchar(100) NOT NULL default '0',
  `value` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`acl_id`,`section_value`,`value`)
) ENGINE=MyISAM CHARACTER SET `utf8`;

-- --------------------------------------------------------

--
-- Table structure for table `#__core_acl_axo_sections`
--

CREATE TABLE IF NOT EXISTS `#__core_acl_axo_sections` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `value` varchar(100) NOT NULL default '',
  `order_value` int(11) NOT NULL default '0',
  `name` varchar(230) NOT NULL default '',
  `hidden` int(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `#__core_acl_value_axo_sections` (`value`),
  KEY `#__core_acl_hidden_axo_sections` (`hidden`)
) ENGINE=MyISAM CHARACTER SET `utf8`;

-- --------------------------------------------------------

--
-- Table structure for table `#__core_acl_groups_aro_map`
--

#CREATE TABLE IF NOT EXISTS `#__core_acl_groups_aro_map` (
#  `group_id` int(11) NOT NULL default '0',
#  `section_value` varchar(240) NOT NULL default '',
#  `aro_id` int(11) NOT NULL default '0',
#  UNIQUE KEY `group_id_aro_id_groups_aro_map` (`group_id`,`section_value`,`aro_id`)
#) ENGINE=MyISAM CHARACTER SET `utf8`;

-- --------------------------------------------------------

--
-- Table structure for table `#__core_acl_groups_axo_map`
--

CREATE TABLE IF NOT EXISTS `#__core_acl_groups_axo_map` (
  `group_id` int(10) unsigned NOT NULL default '0',
  `axo_id` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`group_id`,`axo_id`),
  KEY `#__core_acl_axo_id` (`axo_id`),
  INDEX `group_id_axo_id_groups_axo_map` USING BTREE(`axo_id`, `group_id`),
  INDEX `aro_id_group_id_groups_axo_map` USING BTREE(`group_id`, `axo_id`)
) ENGINE=MyISAM CHARACTER SET `utf8`;

-- --------------------------------------------------------

--
-- Table structure for table `#__core_acl_phpgacl`
--

CREATE TABLE IF NOT EXISTS `#__core_acl_phpgacl` (
  `name` varchar(230) NOT NULL default '',
  `value` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`name`)
) ENGINE=MyISAM CHARACTER SET `utf8`;

INSERT IGNORE INTO `#__core_acl_phpgacl` (`name`, `value`) VALUES ('version', '3.3.7');
INSERT IGNORE INTO `#__core_acl_phpgacl` (`name`, `value`) VALUES ('schema_version', '2.1');
