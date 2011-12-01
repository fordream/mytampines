CREATE TABLE IF NOT EXISTS #__alpha_userpoints (
  `id` int(11) NOT NULL auto_increment,
  `userid` int(11) NOT NULL default '0',
  `referreid` varchar(160) NOT NULL default '',
  `upnid` varchar(25) NOT NULL default '',
  `points` int(11) NOT NULL default '0',
  `max_points` int(11) NOT NULL default '0',
  `last_update` datetime NOT NULL default '0000-00-00 00:00:00',
  `referraluser` varchar(160) NOT NULL default '',
  `referrees` int(11) NOT NULL default '0',
  `blocked` tinyint(1) NOT NULL default '0',
  `birthdate` date NOT NULL default '0000-00-00',
  `avatar` varchar(255) NOT NULL default '',
  `levelrank` int(11) NOT NULL default '0',
  `leveldate` date NOT NULL default '0000-00-00',
  `gender` tinyint(1) NOT NULL default '0',
  `aboutme` varchar(250) NOT NULL default '',
  `website` varchar(150) NOT NULL default '',
  `phonehome` varchar(30) NOT NULL default '',
  `phonemobile` varchar(30) NOT NULL default '',
  `address` varchar(150) NOT NULL default '',
  `zipcode` varchar(10) NOT NULL default '',
  `city` varchar(50) NOT NULL default '',
  `country` varchar(30) NOT NULL default '',
  `education` varchar(30) NOT NULL default '',
  `graduationyear` char(4) NOT NULL default '',
  `job` VARCHAR( 50 ) NOT NULL DEFAULT '',
  `facebook` varchar(150) NOT NULL default '',
  `twitter` varchar(150) NOT NULL default '',
  `icq` varchar(50) NOT NULL default '',
  `aim` varchar(50) NOT NULL default '',
  `yim` varchar(50) NOT NULL default '',
  `msn` varchar(50) NOT NULL default '',
  `skype` varchar(50) NOT NULL default '',
  `gtalk` varchar(50) NOT NULL default '',
  `xfire` varchar(50) NOT NULL default '',
  `profileviews` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  INDEX (referreid),
  UNIQUE KEY `userid` (`userid`)
) ENGINE=MyISAM;

CREATE TABLE IF NOT EXISTS #__alpha_userpoints_details (
  `id` int(11) NOT NULL auto_increment,
  `referreid` varchar(160) NOT NULL default '',
  `points` int(11) NOT NULL default '0',
  `insert_date` datetime NOT NULL default '0000-00-00 00:00:00',
  `expire_date` datetime NOT NULL default '0000-00-00 00:00:00',
  `status` tinyint(1) NOT NULL default '0',
  `rule` int(11) NOT NULL default '0',
  `approved` tinyint(1) NOT NULL default '1',
  `keyreference` varchar(255) NOT NULL default '',
  `datareference` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`),
  INDEX (referreid)
) ENGINE=MyISAM;

CREATE TABLE IF NOT EXISTS #__alpha_userpoints_details_archive (
  `id` int(11) NOT NULL default '0',
  `referreid` varchar(160) NOT NULL default '',
  `points` int(11) NOT NULL default '0',
  `insert_date` datetime NOT NULL default '0000-00-00 00:00:00',
  `expire_date` datetime NOT NULL default '0000-00-00 00:00:00',
  `status` tinyint(1) NOT NULL default '0',
  `rule` int(11) NOT NULL default '0',
  `approved` tinyint(1) NOT NULL default '1',
  `keyreference` varchar(255) NOT NULL default '',
  `datareference` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`),
  INDEX (referreid)
) ENGINE=MyISAM;

CREATE TABLE IF NOT EXISTS #__alpha_userpoints_rules (
  `id` int(11) NOT NULL auto_increment,
  `rule_name` varchar(30) NOT NULL default '',
  `rule_description` varchar(255) NOT NULL default '',
  `rule_plugin` varchar(30) NOT NULL default '', 
  `plugin_function` varchar(50) NOT NULL default '', 
  `access` tinyint(1) NOT NULL default '1',
  `component` varchar(50) NOT NULL default '',
  `calltask` varchar(50) NOT NULL default '',
  `taskid` varchar(50) NOT NULL default '',
  `points` int(11) NOT NULL default '0',
  `percentage` tinyint(1) NOT NULL default '0',
  `rule_expire` datetime NOT NULL default '0000-00-00 00:00:00',
  `sections` text NOT NULL default '',
  `categories` text NOT NULL default '',
  `content_items` text NOT NULL default '',
  `exclude_items` text NOT NULL default '',
  `published` tinyint(1) NOT NULL default '0',
  `system` tinyint(1) NOT NULL default '0',
  `duplicate` tinyint(1) NOT NULL default '0',
  `blockcopy` tinyint(1) NOT NULL default '0',
  `autoapproved` tinyint(1) NOT NULL default '1',
  `fixedpoints` tinyint(1) NOT NULL default '1',
  `category` varchar(2) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM;

CREATE TABLE IF NOT EXISTS #__alpha_userpoints_requests (
  `id` int(11) NOT NULL auto_increment,
  `userid` int(11) NOT NULL default '0',
  `referreid` varchar(160) NOT NULL default '',
  `name` varchar(50) NOT NULL default '',
  `username` varchar(50) NOT NULL default '',  
  `levelrequest` int(11) NOT NULL default '0',
  `checked` tinyint(1) NOT NULL default '0',
  `checkedadmin` tinyint(1) NOT NULL default '0',
  `response` tinyint(1) NOT NULL default '0',
  `requestdate` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM;

CREATE TABLE IF NOT EXISTS #__alpha_userpoints_coupons (
  `id` int(11) NOT NULL auto_increment,
  `description` varchar(255) NOT NULL default '',
  `couponcode` varchar(20) NOT NULL default '',  
  `points` int(11) NOT NULL default '0',
  `expires` datetime NOT NULL default '0000-00-00 00:00:00',
  `public` tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM;

CREATE TABLE IF NOT EXISTS #__alpha_userpoints_raffle (
  `id` int(11) NOT NULL auto_increment,
  `description` varchar(255) NOT NULL default '',
  `inscription` tinyint(1) NOT NULL default '0',
  `rafflesystem` tinyint(1) NOT NULL default '0',
  `numwinner` tinyint(1) NOT NULL default '1',
  `couponcodeid1` int(11) NOT NULL default '0',
  `couponcodeid2` int(11) NOT NULL default '0',
  `couponcodeid3` int(11) NOT NULL default '0',
  `sendcouponbyemail` tinyint(1) NOT NULL default '0',
  `pointstoparticipate` int(11) NOT NULL default '0',
  `removepointstoparticipate` tinyint(1) NOT NULL default '0',
  `pointstoearn1` int(11) NOT NULL default '0',
  `pointstoearn2` int(11) NOT NULL default '0',
  `pointstoearn3` int(11) NOT NULL default '0',
  `raffledate` datetime NOT NULL default '0000-00-00 00:00:00',
  `winner1` int(11) NOT NULL default '0',
  `winner2` int(11) NOT NULL default '0',
  `winner3` int(11) NOT NULL default '0',
  `published` tinyint(1) NOT NULL default '1',
  `link2download1` varchar(255) NOT NULL default '',
  `link2download2` varchar(255) NOT NULL default '',
  `link2download3` varchar(255) NOT NULL default '',
  `multipleentries` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM;

CREATE TABLE IF NOT EXISTS #__alpha_userpoints_raffle_inscriptions (
  `id` int(11) NOT NULL auto_increment,
  `raffleid` int(11) NOT NULL default '0',
  `userid` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM;

CREATE TABLE IF NOT EXISTS #__alpha_userpoints_levelrank (
  `id` int(11) NOT NULL auto_increment,
  `rank` varchar(50) NOT NULL default '',  
  `description` varchar(255) NOT NULL default '',
  `levelpoints` int(11) NOT NULL default '0',
  `typerank` tinyint(1) NOT NULL default '0',
  `icon` varchar(255) NOT NULL default '',
  `image` varchar(255) NOT NULL default '',
  `gid` int(11) NOT NULL default '0',
  `ruleid` int(11) NOT NULL default '0',
  `ordering` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM;

CREATE TABLE IF NOT EXISTS #__alpha_userpoints_medals (
  `id` int(11) NOT NULL auto_increment,
  `rid` int(11) NOT NULL default '0',
  `medal` int(11) NOT NULL default '0',
  `medaldate` date NOT NULL default '0000-00-00',
  `reason` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM;

CREATE TABLE IF NOT EXISTS #__alpha_userpoints_version (
  `version` varchar(8) NOT NULL default '1.5.13'
) ENGINE=MyISAM;