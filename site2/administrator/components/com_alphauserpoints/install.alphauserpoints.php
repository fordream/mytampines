<?php
/*
 * @component AlphaUserPoints
 * @copyright Copyright (C) 2008-2011 Bernard Gilly
 * @license : GNU/GPL
 * @Website : http://www.alphaplug.com
 */

defined('_JEXEC') or die('Restricted access');
defined('JPATH_BASE') or die();

$app = JFactory::getApplication();

$error = 0;

$cache = & JFactory::getCache();
$cache->clean( null, 'com_alphauserpoints' );

$db	=& JFactory::getDBO(); 
$uninstall_status = array();
class Status {
	var $STATUS_FAIL = 'Failed';
	var $STATUS_SUCCESS = 'Success';
	var $infomsg = array();
	var $errmsg = array();
	var $status;
}

jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.file');	

// include version
require_once (JPATH_ADMINISTRATOR.DS.'components'.DS.'com_alphauserpoints'.DS.'assets'.DS.'includes'.DS.'version.php');
require_once (JPATH_ADMINISTRATOR.DS.'components'.DS.'com_alphauserpoints'.DS.'assets'.DS.'includes'.DS.'functions.php');

/************************************************************************
 *
 *                              START INSTALL
 *
 *************************************************************************/
$install = "";

// Install mod_aupadmin : Insert button on general control panel of Joomla
$module_installer = new JInstaller;
if( @$module_installer->install(dirname(__FILE__).DS.'install'.DS.'mod_aupadmin') )
{
	// Enable mod_aupadmin
	$query = "UPDATE #__modules SET published=1, position='icon' WHERE module='mod_aupadmin'";
	$db->setQuery( $query );
	$db->query();	
	// Unlink mod_aupadmin
	@unlink( dirname(__FILE__).DS.'install'.DS.'mod_aupadmin'.DS.'mod_aupadmin.php' );
	@unlink( dirname(__FILE__).DS.'install'.DS.'mod_aupadmin'.DS.'mod_aupadmin.xml' );
	$install .= '<img src="images/tick.png" alt="" /> Installing module <b>mod_aupadmin</b> (backend)<br/>';
} else $error++;


// Install plugins
$plugin_installer = new JInstaller;
$file_origin = JPATH_ADMINISTRATOR.DS.'components'.DS.'com_alphauserpoints'.DS.'install'.DS.'plugins'.DS.'alphauserpoints';
if( $plugin_installer->install($file_origin) ) {
	// publish plugin
	$query = "UPDATE #__plugins SET published='1' WHERE element='alphauserpoints'";
	$db->setQuery( $query );
	$db->query();
	// Unlink 
	@unlink( dirname(__FILE__).DS.'install'.DS.'plugins'.DS.'alphauserpoints'.DS.'alphauserpoints.php' );
	@unlink( dirname(__FILE__).DS.'install'.DS.'plugins'.DS.'alphauserpoints'.DS.'alphauserpoints.xml' );
	$install .= '<img src="images/tick.png" alt="" /> Installing AlphaUserPoints <b>System</b> Plugin <br/>';
}  else $error++;

$plugin_installer = new JInstaller;
$file_origin = JPATH_ADMINISTRATOR.DS.'components'.DS.'com_alphauserpoints'.DS.'install'.DS.'plugins'.DS.'sysplgaup_content';
if($plugin_installer->install($file_origin)) {
	// publish plugin
	$query = "UPDATE #__plugins SET published='1' WHERE element='sysplgaup_content'";
	$db->setQuery( $query );
	$db->query();
	// Unlink 
	@unlink( dirname(__FILE__).DS.'install'.DS.'plugins'.DS.'sysplgaup_content'.DS.'sysplgaup_content.php' );
	@unlink( dirname(__FILE__).DS.'install'.DS.'plugins'.DS.'sysplgaup_content'.DS.'sysplgaup_content.xml' );
	$install .= '<img src="images/tick.png" alt="" /> Installing AlphaUserPoints standart <b>Content</b> Plugin <br/>';
} else $error++;

$plugin_installer = new JInstaller;
$file_origin = JPATH_ADMINISTRATOR.DS.'components'.DS.'com_alphauserpoints'.DS.'install'.DS.'plugins'.DS.'sysplgaup_newregistered';
if($plugin_installer->install($file_origin)) {
	// publish plugin
	$query = "UPDATE #__plugins SET published='1' WHERE element='sysplgaup_newregistered'";
	$db->setQuery( $query );
	$db->query();
	@unlink( dirname(__FILE__).DS.'install'.DS.'plugins'.DS.'sysplgaup_newregistered'.DS.'sysplgaup_newregistered.php' );
	@unlink( dirname(__FILE__).DS.'install'.DS.'plugins'.DS.'sysplgaup_newregistered'.DS.'sysplgaup_newregistered.xml' );
	$install .= '<img src="images/tick.png" alt="" /> Installing AlphaUserPoints Registering <b>User</b> Plugin <br/>';
} else $error++;

$plugin_installer = new JInstaller;
$file_origin = JPATH_ADMINISTRATOR.DS.'components'.DS.'com_alphauserpoints'.DS.'install'.DS.'plugins'.DS.'sysplgaup_raffle';
if($plugin_installer->install($file_origin)) {
	// publish plugin
	$query = "UPDATE #__plugins SET published='1' WHERE element='sysplgaup_raffle'";
	$db->setQuery( $query );
	$db->query();
	@unlink( dirname(__FILE__).DS.'install'.DS.'plugins'.DS.'sysplgaup_raffle'.DS.'sysplgaup_raffle.php' );
	@unlink( dirname(__FILE__).DS.'install'.DS.'plugins'.DS.'sysplgaup_raffle'.DS.'sysplgaup_raffle.xml' );
	$install .= '<img src="images/tick.png"> Installing AlphaUserPoints Raffle <b>Content</b> Plugin <br/>';
} else $error++;

$plugin_installer = new JInstaller;
$file_origin = JPATH_ADMINISTRATOR.DS.'components'.DS.'com_alphauserpoints'.DS.'install'.DS.'plugins'.DS.'sysplgaup_reader2author';
if($plugin_installer->install($file_origin)) {
	// publish plugin
	$query = "UPDATE #__plugins SET published='1' WHERE element='sysplgaup_reader2author'";
	$db->setQuery( $query );
	$db->query();
	@unlink( dirname(__FILE__).DS.'install'.DS.'plugins'.DS.'sysplgaup_reader2author'.DS.'sysplgaup_reader2author.php' );
	@unlink( dirname(__FILE__).DS.'install'.DS.'plugins'.DS.'sysplgaup_reader2author'.DS.'sysplgaup_reader2author.xml' );
	$install .= '<img src="images/tick.png" alt="" /> Installing AlphaUserPoints Reader to Author <b>Content</b> Plugin <br/>';
} else $error++;


$plugin_installer = new JInstaller;
$file_origin = JPATH_ADMINISTRATOR.DS.'components'.DS.'com_alphauserpoints'.DS.'install'.DS.'plugins'.DS.'plg_editors-xtd_raffle';
if($plugin_installer->install($file_origin)) {
	// publish plugin
	$query = "UPDATE #__plugins SET published='1' WHERE element='raffle'";
	$db->setQuery( $query );
	$db->query();
	//@unlink( dirname(__FILE__).DS.'install'.DS.'plugins'.DS.'plg_editors-xtd_raffle'.DS.'raffle.php' );
	//@unlink( dirname(__FILE__).DS.'install'.DS.'plugins'.DS.'plg_editors-xtd_raffle'.DS.'raffle.xml' );
	$install .= '<img src="images/tick.png" alt="" /> Installing AlphaUserPoints Raffle Editor Button <b>Editor</b> Plugin <br/>';
} else $error++;

if ( $error ) {
	$app->redirect( 'index.php?option=com_alphauserpoints','NOTICE: AlphaUserPoints plugins are not successfully installed. Make sure that the plugins directory is writeable' );
} else {
	
	// Modify the admin icons
	$query = "SELECT id FROM #__components WHERE `name`='AlphaUserPoints'";
	$db->setQuery( $query );
	$id = $db->loadResult();

	//add new admin menu images
	$query = "UPDATE #__components SET `name`='AlphaUserPoints', admin_menu_img = '../administrator/components/com_alphauserpoints/assets/images/referral_icon.png' WHERE id='$id'";
	$db->setQuery( $query );
	$db->query();
	
	$install .=  '<img src="images/tick.png" alt="" /> Icon menu updated <br/>';	
	
	// New install -> insert rules and Guest user.
	$query = "SELECT id FROM #__alpha_userpoints WHERE `userid`='0' AND `referreid`='GUEST'";
	$db->setQuery( $query );
	$result = $db->loadResult();
	if ( !$result ) {
		// This GUEST user is used by AUP system, don't remove!
		$query = "INSERT INTO #__alpha_userpoints (`id`, `userid`, `referreid`, `points`, `max_points`, `last_update`, `referraluser`, `referrees`, `blocked`, `levelrank`) VALUES ('', '0', 'GUEST', '0', '0', '0000-00-00 00:00:00', '', '0', '0', '0');";
		$db->setQuery( $query );
		$db->query();
	}
	
	$query = "SELECT count(*) FROM #__alpha_userpoints_rules";
	$db->setQuery( $query );
	$result = $db->loadResult();
	if ( !$result ) {		
		// fresh install -> insert default rules
		$query = "INSERT INTO #__alpha_userpoints_rules (`id`, `rule_name`, `rule_description`, `rule_plugin`, `plugin_function`, `access`, `component`, `calltask`, `taskid`, `points`, `percentage`, `rule_expire`, `sections`, `categories`, `content_items`, `exclude_items`, `published`, `system`, `duplicate`, `blockcopy`, `autoapproved`, `fixedpoints`, `category`) VALUES
			('', 'AUP_NEWUSER', 'AUP_NEWUSERDESCRIPTION', 'AUP_SYSTEM', 'sysplgaup_newregistered', '1', '', '', '', 0, 0, '0000-00-00 00:00:00', '', '', '', '', 1, 1, 0, 1, 1, 1, 'us'),
			('', 'AUP_DAILYLOGIN', 'AUP_DAILYLOGINDESCRIPTION', 'AUP_SYSTEM', 'sysplgaup_dailylogin', '1', '', '', '', 0, 0, '0000-00-00 00:00:00', '', '', '', '', 0, 1, 0, 1, 1, 1, 'us'),
			('', 'AUP_INVITE', 'AUP_INVITE_A_USER', 'AUP_SYSTEM', 'sysplgaup_invite', '1', '', '', '', 0, 0, '0000-00-00 00:00:00', '', '', '', '', 0, 1, 0, 1, 1, 1, 're'),
			('', 'AUP_INVITESUCCES', 'AUP_INVITE_A_USERSUCCESS', 'AUP_SYSTEM', 'sysplgaup_invitewithsuccess', '1', '', '', '', 0, 0, '0000-00-00 00:00:00', '', '', '', '', 0, 1, 0, 1, 1, 1, 're'),
			('', 'AUP_SUBMITANARTICLE', 'AUP_SUBMITANARTICLEDESCRIPTION', 'AUP_SYSTEM', 'sysplgaup_submitarticle', '2', '', '', '', 0, 0, '0000-00-00 00:00:00', '', '', '', '', 0, 1, 0, 1, 0, 1, 'ar'),
			('', 'AUP_SUBMITAWEBLINK', 'AUP_SUBMITAWEBLINKDESCRIPTION', 'AUP_SYSTEM', 'sysplgaup_submitweblink', '2', '', '', '', 0, 0, '0000-00-00 00:00:00', '', '', '', '', 0, 1, 0, 1, 0, 1, 'li'),
			('', 'AUP_INVITETOREADARTICLE', 'AUP_INVITETOREADARTICLEDESCRIPTION', 'AUP_SYSTEM', 'sysplgaup_recommend', '1', '', '', '', 0, 0, '0000-00-00 00:00:00', '', '', '', '', 0, 1, 0, 1, 1, 1, 're'),
			('', 'AUP_READTOAUTHOR', 'AUP_READTOAUTHORDESCRIPTION', 'AUP_SYSTEM', 'sysplgaup_reader2author', '0', '', '', '', 0, 0, '0000-00-00 00:00:00', '', '', '', '', 0, 1, 0, 1, 1, 1, 'ar'),
			('', 'AUP_READ_ARTICLE', 'AUP_READ_ARTICLE_DESCRIPTION', 'AUP_SYSTEM', 'sysplgaup_readarticle', '1', '', '', '', 0, 0, '0000-00-00 00:00:00', '', '', '', '', 0, 1, 0, 1, 1, 1, 'ar'),
			('', 'AUP_VOTE_ARTICLE', 'AUP_VOTE_ARTICLE_DESCRIPTION', 'AUP_SYSTEM', 'sysplgaup_votearticle', '1', '', '', '', 0, 0, '0000-00-00 00:00:00', '', '', '', '', 0, 1, 0, 1, 1, 1, 'ot'),
			('', 'AUP_CLICK_BANNER', 'AUP_CLICK_BANNER_DESCRIPTION', 'AUP_SYSTEM', 'sysplgaup_clickbanner', '1', '', '', '', 0, 0, '0000-00-00 00:00:00', '', '', '', '', 0, 1, 0, 1, 1, 1, 'ot'),
			('', 'AUP_ANSWERINGAPOLL', 'AUP_ANSWERINGAPOLLDESCRIPTION', 'AUP_SYSTEM', 'sysplgaup_answeringpoll', '1', '', '', '', 0, 0, '0000-00-00 00:00:00', '', '', '', '', 0, 1, 0, 1, 1, 1, 'po'),
			('', 'AUP_USER2USERPOINTS', 'AUP_USER2USERPOINTSDESCRIPTION', 'AUP_SYSTEM', 'sysplgaup_user2userpoints', '1', '', '', '', 0, 0, '0000-00-00 00:00:00', '', '', '', '', 0, 1, 0, 1, 1, 0, 'co'),
			('', 'AUP_REFERRALPOINTS', 'AUP_REFERRALPOINTSDESCRIPTION', 'AUP_SYSTEM', 'sysplgaup_referralpoints', '1', '', '', '', 0, 1, '0000-00-00 00:00:00', '', '', '', '', 0, 1, 0, 1, 1, 1, 'co'),
			('', 'AUP_BONUSPOINTS', 'AUP_BONUSPOINTSDESCRIPTION', 'AUP_SYSTEM', 'sysplgaup_bonuspoints', '1', '', '', '', 0, 0, '0000-00-00 00:00:00', '', '', '', '', 0, 1, 0, 1, 1, 1, 'ot'),
			('', 'AUP_BECOME_AUTHOR', 'AUP_BECOME_AUTHOR_DESCRIPTION', 'AUP_SYSTEM', 'sysplgaup_becomeauthor', '1', '', '', '', 0, 0, '0000-00-00 00:00:00', '', '', '', '', 0, 1, 0, 1, 1, 1, 'us'),
			('', 'AUP_BECOME_EDITOR', 'AUP_BECOME_EDITOR_DESCRIPTION', 'AUP_SYSTEM', 'sysplgaup_becomeeditor', '1', '', '', '', 0, 0, '0000-00-00 00:00:00', '', '', '', '', 0, 1, 0, 1, 1, 1, 'us'),
			('', 'AUP_BECOME_PUBLISHER', 'AUP_BECOME_PUBLISHER_DESCRIPTION', 'AUP_SYSTEM', 'sysplgaup_becomepublisher', '1', '', '', '', 0, 0, '0000-00-00 00:00:00', '', '', '', '', 0, 1, 0, 1, 1, 1, 'us'),
			('', 'AUP_EXCLUDESPECIFICUSERS', 'AUP_EXCLUDESPECIFICUSERSDESCRIPTION', 'AUP_SYSTEM', 'sysplgaup_excludeusers', '1', '', '', '', 0, 0, '0000-00-00 00:00:00', '', '', '', '', 0, 1, 0, 1, 1, 0, 'sy'),
			('', 'AUP_EMAILNOTIFICATION', 'AUP_EMAILNOTIFICATIONDESCRIPTION', 'AUP_SYSTEM', 'sysplgaup_emailnotification', '1', '', '', '', 0, 0, '0000-00-00 00:00:00', '', '', '', '', 0, 1, 0, 1, 1, 0, 'sy'),
			('', 'AUP_WINNERNOTIFICATION', 'AUP_WINNERNOTIFICATIONDESCRIPTION', 'AUP_SYSTEM', 'sysplgaup_winnernotification', '1', '', '', '', 0, 0, '0000-00-00 00:00:00', '', '', '', '', 0, 1, 0, 1, 1, 0, 'sy'),
			('', 'AUP_BUYPOINTSWITHPAYPAL', 'AUP_BUYPOINTSWITHPAYPALDESCRIPTION', 'AUP_SYSTEM', 'sysplgaup_buypointswithpaypal', '1', '', '', '', 0, 0, '0000-00-00 00:00:00', '', '', '', '', 0, 1, 0, 1, 1, 0, 'pu'),
			('', 'AUP_COUPON_POINTS_CODES', 'AUP_COUPON_POINTS_CODES_DESCRIPTION', 'AUP_SYSTEM', 'sysplgaup_couponpointscodes', '1', '', '', '', 0, 0, '0000-00-00 00:00:00', '', '', '', '', 0, 1, 0, 1, 1, 0, 'cd'),			
			('', 'AUP_RAFFLE', 'AUP_RAFFLE_DESCRIPTION', 'AUP_SYSTEM', 'sysplgaup_raffle', '1', '', '', '', 0, 0, '0000-00-00 00:00:00', '', '', '', '', 0, 1, 0, 1, 1, 0, 'ot'),
			('', 'AUP_CUSTOM', 'AUP_CUSTOM_DESCRIPTION', 'AUP_SYSTEM', 'sysplgaup_custom', '1', '', '', '', 0, 0, '0000-00-00 00:00:00', '', '', '', '', 0, 1, 0, 1, 1, 0, 'ot'),
			('', 'AUP_HAPPYBIRTHDAY', 'AUP_HAPPYBIRTHDAY_DESCRIPTION', 'AUP_SYSTEM', 'sysplgaup_happybirthday', '1', '', '', '', 0, 0, '0000-00-00 00:00:00', '', '', '', '', 0, 1, 0, 1, 1, 1, 'us'),
			('', 'AUP_CONTENT', 'AUP_CONTENT_DESCRIPTION', 'AUP_SYSTEM', 'sysplgaup_content', '1', '', '', '', 0, 0, '0000-00-00 00:00:00', '', '', '', '', 0, 1, 0, 1, 1, 0, 'ar'),
			('', 'AUP_UPLOADAVATAR', 'AUP_UPLOADAVATAR_DESCRIPTION', 'AUP_SYSTEM', 'sysplgaup_uploadavatar', '1', '', '', '', 0, 0, '0000-00-00 00:00:00', '', '', '', '', 0, 1, 0, 1, 1, 1, 'us'),
			('', 'AUP_PROFILECOMPLETE', 'AUP_PROFILECOMPLETE_DESCRIPTION', 'AUP_SYSTEM', 'sysplgaup_profilecomplete', '1', '', '', '', 0, 0, '0000-00-00 00:00:00', '', '', '', '', 0, 1, 0, 1, 1, 1, 'us'),
			('', 'AUP_INACTIVE_USER', 'AUP_INACTIVE_USER_DESCRIPTION', 'AUP_SYSTEM', 'sysplgaup_inactiveuser', '1', '', '', '', 0, 0, '0000-00-00 00:00:00', '', '', '', '', 0, 1, 0, 1, 1, 1, 'us'),			
			('', 'AUP_KU_NEW_TOPIC', 'AUP_KU_NEW_TOPIC_DESCRIPTION', 'AUP_KUNENA_FORUM', 'plgaup_newtopic_kunena', '1', '', '', '', 0, 0, '0000-00-00 00:00:00', '', '', '', '', 0, 0, 0, 0, 1, 1, 'fo'),
			('', 'AUP_KU_REPLY_TOPIC', 'AUP_KU_REPLY_DESCRIPTION', 'AUP_KUNENA_FORUM', 'plgaup_reply_kunena', '1', '', '', '', 0, 0, '0000-00-00 00:00:00', '', '', '', '', 0, 0, 0, 0, 1, 1, 'fo'),			
			('', 'AUP_KU_NEW_TOPIC', 'AUP_KU_NEW_TOPIC_DESCRIPTION', 'AUP_KUNENA_FORUM', 'plgaup_kunena_topic_create', '1', '', '', '', 0, 0, '0000-00-00 00:00:00', '', '', '', '', 0, 0, 0, 0, 1, 1, 'fo'),
			('', 'AUP_KU_REPLY_TOPIC', 'AUP_KU_REPLY_DESCRIPTION', 'AUP_KUNENA_FORUM', 'plgaup_kunena_topic_reply', '1', '', '', '', 0, 0, '0000-00-00 00:00:00', '', '', '', '', 0, 0, 0, 0, 1, 1, 'fo'),
			('', 'AUP_KU_THANKYOU', 'AUP_KU_THANKYOU_DESCRIPTION', 'AUP_KUNENA_FORUM', 'plgaup_kunena_message_thankyou', '1', '', '', '', 0, 0, '0000-00-00 00:00:00', '', '', '', '', 0, 0, 0, 0, 1, 1, 'fo'),
			('', 'AUP_KU_DELETE_POST', 'AUP_KU_DELETE_POST_DESCRIPTION', 'AUP_KUNENA_FORUM', 'plgaup_kunena_message_delete', '1', '', '', '', 0, 0, '0000-00-00 00:00:00', '', '', '', '', 0, 0, 0, 0, 1, 1, 'fo'),			
			('', 'AUP_CONTENTAUTHOR', 'AUP_CONTENTAUTHOR_DESCRIPTION', 'AUP_SYSTEM', 'sysplgaup_contentauthor', '1', '', '', '', 0, 0, '0000-00-00 00:00:00', '', '', '', '', 0, 1, 0, 1, 1, 0, 'ar'),
			('', 'AUP_COMBINED_ACTIVITIES', 'AUP_COMBINE_ACTIVITIES_DESCRIPTION', 'AUP_SYSTEM', 'sysplgaup_archive', '1', '', '', '', 0, 0, '0000-00-00 00:00:00', '', '', '', '', 1, 1, 0, 1, 1, 0, 'sy');";
			// 34 rules
		$db->setQuery( $query );
		if ( $db->query() ) {
			$install .=  '<img src="images/tick.png" alt="" /> Default rules installed<br/>';
		}

	}	
	
	// Upgrades
	$messageUpgrade = "";
	
	// Upgrade version 0.9.14 -> 1.0.0 RC
	// ----------------------------------
	$query = "SELECT id FROM #__alpha_userpoints_rules WHERE rule_name='AUP_BUYPOINTSWITHPAYPAL' AND plugin_function='sysplgaup_buypointswithpaypal'";
	$db->setQuery( $query );
	$result = $db->loadResult();
	
	if ( !$result ) {
		$query = "INSERT INTO #__alpha_userpoints_rules (`id`, `rule_name`, `rule_description`, `rule_plugin`, `plugin_function`, `access`, `component`, `calltask`, `taskid`, `points`, `percentage`, `rule_expire`, `sections`, `categories`, `content_items`, `exclude_items`, `published`, `system`, `duplicate`, `blockcopy`, `autoapproved`, `fixedpoints`) VALUES
		('', 'AUP_BUYPOINTSWITHPAYPAL', 'AUP_BUYPOINTSWITHPAYPALDESCRIPTION', 'AUP_SYSTEM', 'sysplgaup_buypointswithpaypal', '1', '', '', '', 0, 0, '0000-00-00 00:00:00', '', '', '', '', 0, 1, 0, 1, 1, 0);";
		$db->setQuery( $query );
		if ( $db->query() ) {
			$messageUpgrade = "Upgrade to version 1.0.0 RC completed.";			
		} else $messageUpgrade = "Upgrade to version 1.0.0 RC failed ! Uninstall and install the new package.";		
		
	}
	
	// Upgrade version 1.0.0 RC -> 1.0.0 RC1
	// -------------------------------------
	$query = "SELECT id FROM #__alpha_userpoints_rules WHERE rule_name='AUP_COUPON_POINTS_CODES' AND plugin_function='sysplgaup_couponpointscodes'";
	$db->setQuery( $query );
	$result = $db->loadResult();
	
	if ( !$result ) {
		$query = "CREATE TABLE IF NOT EXISTS #__alpha_userpoints_coupons (
				  `id` int(11) NOT NULL auto_increment,
				  `description` varchar(255) NOT NULL default '',
				  `couponcode` varchar(20) NOT NULL default '',  
				  `points` int(11) NOT NULL default '0',
				  `expires` datetime NOT NULL default '0000-00-00 00:00:00',
				  PRIMARY KEY  (`id`)
				) ENGINE=MyISAM;";
		$db->setQuery( $query );
		
		if ( $db->query() ) {			
			$query = "INSERT INTO #__alpha_userpoints_rules (`id`, `rule_name`, `rule_description`, `rule_plugin`, `plugin_function`, `access`, `component`, `calltask`, `taskid`, `points`, `percentage`, `rule_expire`, `sections`, `categories`, `content_items`, `exclude_items`, `published`, `system`, `duplicate`, `blockcopy`, `autoapproved`, `fixedpoints`) VALUES
			('', 'AUP_COUPON_POINTS_CODES', 'AUP_COUPON_POINTS_CODES_DESCRIPTION', 'AUP_SYSTEM', 'sysplgaup_couponpointscodes', '1', '', '', '', 0, 0, '0000-00-00 00:00:00', '', '', '', '', 0, 1, 0, 1, 1, 0);";
			$db->setQuery( $query );
			if ( $db->query() ) {
				$messageUpgrade = "Upgrade to version 1.0.0 RC1 completed.";			
			} else {
				$messageUpgrade = "Upgrade to version 1.0.0 RC1 failed ! Uninstall and install the new package.";		
			}
		} else {
			$messageUpgrade = "Upgrade to version 1.0.0 RC1 failed ! Uninstall and install the new package.";		
		}				
	}
	
	// Upgrade version 1.0.0 RC1 -> version 1.0.0 stable
	// -------------------------------------------------
	$query = "SELECT id FROM #__alpha_userpoints_rules WHERE rule_name='AUP_VOTE_ARTICLE' AND plugin_function='sysplgaup_votearticle'";
	$db->setQuery( $query );
	$result = $db->loadResult();
	
	if ( !$result ) {
		$query = "INSERT INTO #__alpha_userpoints_rules (`id`, `rule_name`, `rule_description`, `rule_plugin`, `plugin_function`, `access`, `component`, `calltask`, `taskid`, `points`, `percentage`, `rule_expire`, `sections`, `categories`, `content_items`, `exclude_items`, `published`, `system`, `duplicate`, `blockcopy`, `autoapproved`, `fixedpoints`) VALUES "
		. "('', 'AUP_READ_ARTICLE', 'AUP_READ_ARTICLE_DESCRIPTION', 'AUP_SYSTEM', 'sysplgaup_readarticle', '1', '', '', '', 0, 0, '0000-00-00 00:00:00', '', '', '', '', 0, 1, 0, 1, 1, 1),"
		. "('', 'AUP_VOTE_ARTICLE', 'AUP_VOTE_ARTICLE_DESCRIPTION', 'AUP_SYSTEM', 'sysplgaup_votearticle', '1', '', '', '', 0, 0, '0000-00-00 00:00:00', '', '', '', '', 0, 1, 0, 1, 1, 1),"
		. "('', 'AUP_CLICK_BANNER', 'AUP_CLICK_BANNER_DESCRIPTION', 'AUP_SYSTEM', 'sysplgaup_clickbanner', '1', '', '', '', 0, 0, '0000-00-00 00:00:00', '', '', '', '', 0, 1, 0, 1, 1, 1);";
		$db->setQuery( $query );			
		$db->query();						
	}
	
	$test = "SELECT `public` FROM #__alpha_userpoints_coupons";
	$db->setQuery( $test );
	if ( !$db->query() ) {		
		$query = "ALTER TABLE #__alpha_userpoints_coupons ADD `public` TINYINT( 1 ) NOT NULL DEFAULT '1'";
		$db->setQuery( $query );
		if ( $db->query() ) {
			$messageUpgrade = "Upgrade to version 1.0.0 stable completed.";	
		} else $messageUpgrade = "Upgrade to version 1.0.0 stable failed ! Uninstall and install the new package.";
	}		
	
	// Upgrade version 1.0.0 stable -> version 1.1.0
	// ---------------------------------------------
	// Add rule for daily login
	$query = "SELECT id FROM #__alpha_userpoints_rules WHERE plugin_function='sysplgaup_raffle'";
	$db->setQuery( $query );
	$result = $db->loadResult();
	
	if ( !$result ) {
		$query = "INSERT INTO #__alpha_userpoints_rules (`id`, `rule_name`, `rule_description`, `rule_plugin`, `plugin_function`, `access`, `component`, `calltask`, `taskid`, `points`, `percentage`, `rule_expire`, `sections`, `categories`, `content_items`, `exclude_items`, `published`, `system`, `duplicate`, `blockcopy`, `autoapproved`, `fixedpoints`) VALUES "
		. "('', 'AUP_DAILYLOGIN', 'AUP_DAILYLOGINDESCRIPTION', 'AUP_SYSTEM', 'sysplgaup_dailylogin', '1', '', '', '', 0, 0, '0000-00-00 00:00:00', '', '', '', '', 0, 1, 0, 1, 1, 1),"
		. "('', 'AUP_RAFFLE', 'AUP_RAFFLE_DESCRIPTION', 'AUP_SYSTEM', 'sysplgaup_raffle', '1', '', '', '', 0, 0, '0000-00-00 00:00:00', '', '', '', '', 0, 1, 0, 1, 1, 0);";
		$db->setQuery( $query );			
		$db->query();
	}
	
	// add new field for feature
	$query = "SELECT `upnid` FROM #__alpha_userpoints";
	$db->setQuery( $query );
	if ( !$db->query() ) {		
		$query = "ALTER TABLE #__alpha_userpoints ADD `upnid` VARCHAR( 25 ) NOT NULL DEFAULT ''";
		$db->setQuery( $query );
		if ( $db->query() ) {
			$messageUpgrade = "Upgrade to version 1.1.0 completed.";	
		} else $messageUpgrade = "Upgrade to version 1.1.0 failed ! Uninstall, delete all tables related AlphaUserPoints and re-install the full package.";
	}
	
	// add new tables for raffle	
	$query = "CREATE TABLE IF NOT EXISTS #__alpha_userpoints_raffle (
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
		  PRIMARY KEY  (`id`)
		) ENGINE=MyISAM;";
	$db->setQuery( $query );
	$db->query();
	
	$query = "CREATE TABLE IF NOT EXISTS #__alpha_userpoints_raffle_inscriptions (
		  `id` int(11) NOT NULL auto_increment,
		  `raffleid` int(11) NOT NULL default '0',
		  `userid` int(11) NOT NULL default '0',
		  PRIMARY KEY  (`id`)
		) ENGINE=MyISAM;";
	$db->setQuery( $query );
	$db->query();
	
	
	// Upgrade version 1.2.0 stable -> version 1.3.0
	// ---------------------------------------------
	// add new field for user level
	$query = "SELECT `levelrank` FROM #__alpha_userpoints";
	$db->setQuery( $query );
	if ( !$db->query() ) {		
		$query = "ALTER TABLE #__alpha_userpoints "
				. "\n ADD `birthdate` date NOT NULL default '0000-00-00', "
				. "\n ADD `avatar` varchar(255) NOT NULL default '', "
				. "\n ADD `levelrank` INT( 11 ) NOT NULL DEFAULT '0', "
				. "\n ADD `leveldate` date NOT NULL default '0000-00-00', "
				. "\n ADD INDEX(referreid)";
		$db->setQuery( $query );
		if ( $db->query() ) {
			$messageUpgrade = "Upgrade to version 1.3.0 completed.";
		} else $messageUpgrade = "Upgrade to version 1.3.0 failed ! Uninstall, delete all tables related AlphaUserPoints and re-install the full package.";
		
		// Add index on referreid field
		$query = "ALTER TABLE #__alpha_userpoints_details ADD INDEX(referreid)";
		$db->setQuery( $query );
		$db->query();		
		
		$query = "CREATE TABLE IF NOT EXISTS #__alpha_userpoints_levelrank (
		  `id` int(11) NOT NULL auto_increment,
		  `rank` varchar(50) NOT NULL default '',  
		  `description` varchar(255) NOT NULL default '',
		  `levelpoints` int(11) NOT NULL default '0',
		  `typerank` tinyint(1) NOT NULL default '0',
		  `icon` varchar(255) NOT NULL default '',
		  `image` varchar(255) NOT NULL default '',
		  `gid` int(11) NOT NULL default '0',
		  PRIMARY KEY  (`id`)
		) ENGINE=MyISAM;";
		$db->setQuery( $query );
		$db->query();
		
		$query = "CREATE TABLE IF NOT EXISTS #__alpha_userpoints_medals (
		  `id` int(11) NOT NULL auto_increment,
		  `rid` int(11) NOT NULL default '0',
		  `medal` int(11) NOT NULL default '0',
		  `medaldate` date NOT NULL default '0000-00-00',
		  `reason` varchar(255) NOT NULL default '',
		  PRIMARY KEY  (`id`)
		) ENGINE=MyISAM;";
		$db->setQuery( $query );
		$db->query();		
		
	}
	
	$query = "SELECT COUNT(*) FROM #__alpha_userpoints_levelrank";
	$db->setQuery( $query );
	$nblevelrank = $db->loadResult();
	
	if ( !$nblevelrank ) {
	
		// insert sample ranks and medals
		$query = "INSERT INTO `#__alpha_userpoints_levelrank` (`id`, `rank`, `description`, `levelpoints`, `typerank`, `icon`, `image`, `gid`) VALUES
				('', 'Gold member', 'Gold member', 10000, 0, 'icon_gold.gif', 'gold.gif', 0),
				('', 'Silver member', 'Silver member', 6000, 0, 'icon_silver.gif', 'silver.gif', 0),
				('', 'Bronze member', 'Bronze member', 3000, 0, 'icon_bronze.gif', 'bronze.gif', 0),			
				('', 'Honor Medal 2009', 'Honor Medal 2009 for best activities on the site', 1000, 1, 'award_star_gold.gif', 'award_big_gold.png', 0);";
		$db->setQuery( $query );
		$db->query();
		
		$install .=  '<img src="images/tick.png" alt="" /> Sample ranks/medals installed<br/>';
		
	}
	
	// Upgrade version 1.3.1 stable -> version 1.3.2
	// ---------------------------------------------
	// add new custom points rule
	$query = "SELECT id FROM #__alpha_userpoints_rules WHERE rule_name='AUP_CUSTOM' AND plugin_function='sysplgaup_custom'";
	$db->setQuery( $query );
	$result = $db->loadResult();
	
	if ( !$result ) {
		$query = "INSERT INTO #__alpha_userpoints_rules (`id`, `rule_name`, `rule_description`, `rule_plugin`, `plugin_function`, `access`, `component`, `calltask`, `taskid`, `points`, `percentage`, `rule_expire`, `sections`, `categories`, `content_items`, `exclude_items`, `published`, `system`, `duplicate`, `blockcopy`, `autoapproved`, `fixedpoints`) VALUES
		('', 'AUP_CUSTOM', 'AUP_CUSTOM_DESCRIPTION', 'AUP_SYSTEM', 'sysplgaup_custom', '1', '', '', '', 0, 0, '0000-00-00 00:00:00', '', '', '', '', 0, 1, 0, 1, 1, 0);";
		$db->setQuery( $query );
		if ( $db->query() ) {
			$messageUpgrade = "Upgrade to version 1.3.2 completed.";			
		} else $messageUpgrade = "Upgrade to version 1.3.2 failed ! Uninstall and install the new package.";		
	}	
	
	// Upgrade version 1.3.2 stable -> version 1.3.3 beta
	// --------------------------------------------------
	// add new fields for medals and level rank to reliable at a rule and ordering
	$query = "SELECT `ruleid` FROM #__alpha_userpoints_levelrank";
	$db->setQuery( $query );
	if ( !$db->query() ) {		
		$query = "ALTER TABLE #__alpha_userpoints_levelrank "
				. "\n ADD `ruleid` INT( 11 ) NOT NULL DEFAULT '0', "
				. "\n ADD `ordering` INT( 11 ) NOT NULL DEFAULT '0'";
		$db->setQuery( $query );
		if ( $db->query() ) {
			$messageUpgrade = "Upgrade to version 1.3.3 beta completed.";	
		} else $messageUpgrade = "Upgrade to version 1.3.3 beta failed ! Uninstall, delete all tables related to AlphaUserPoints and re-install the full package.";
	}
	
	// Upgrade version 1.3.3 beta -> version 1.4.0
	// -------------------------------------------
	// add new rule "Happy birthday"
	$query = "SELECT id FROM #__alpha_userpoints_rules WHERE rule_name='AUP_HAPPYBIRTHDAY' AND plugin_function='sysplgaup_happybirthday'";
	$db->setQuery( $query );
	$result = $db->loadResult();
	
	if ( !$result ) {
		$query = "INSERT INTO #__alpha_userpoints_rules (`id`, `rule_name`, `rule_description`, `rule_plugin`, `plugin_function`, `access`, `component`, `calltask`, `taskid`, `points`, `percentage`, `rule_expire`, `sections`, `categories`, `content_items`, `exclude_items`, `published`, `system`, `duplicate`, `blockcopy`, `autoapproved`, `fixedpoints`) VALUES
		('', 'AUP_HAPPYBIRTHDAY', 'AUP_HAPPYBIRTHDAY_DESCRIPTION', 'AUP_SYSTEM', 'sysplgaup_happybirthday', '1', '', '', '', 0, 0, '0000-00-00 00:00:00', '', '', '', '', 0, 1, 0, 1, 1, 1);";
		$db->setQuery( $query );
		if ( $db->query() ) {
			$messageUpgrade = "Upgrade to version 1.4.0 completed.";			
		} else $messageUpgrade = "Upgrade to version 1.4.0 failed ! Uninstall and install the new package.";
	}
	

	// Upgrade version 1.4.0 -> version 1.5.0
	// -------------------------------------------
	// add new rule "Content"
	$query = "SELECT id FROM #__alpha_userpoints_rules WHERE rule_name='AUP_CONTENT' AND plugin_function='sysplgaup_content'";
	$db->setQuery( $query );
	$result = $db->loadResult();
	
	if ( !$result ) {
		$query = "INSERT INTO #__alpha_userpoints_rules (`id`, `rule_name`, `rule_description`, `rule_plugin`, `plugin_function`, `access`, `component`, `calltask`, `taskid`, `points`, `percentage`, `rule_expire`, `sections`, `categories`, `content_items`, `exclude_items`, `published`, `system`, `duplicate`, `blockcopy`, `autoapproved`, `fixedpoints`) VALUES
		('', 'AUP_CONTENT', 'AUP_CONTENT_DESCRIPTION', 'AUP_SYSTEM', 'sysplgaup_content', '1', '', '', '', 0, 0, '0000-00-00 00:00:00', '', '', '', '', 0, 1, 0, 1, 1, 0);";
		$db->setQuery( $query );
		if ( $db->query() ) {
			$messageUpgrade = "Upgrade to version 1.5.0 completed.";			
		} else $messageUpgrade = "Upgrade to version 1.5.0 failed ! Uninstall and install the new package.";
	}
	
	
	// Upgrade version 1.5.1 -> version 1.5.2
	// -------------------------------------------
	// add new field catgeory for rules
	$testcat = "SELECT `category` FROM #__alpha_userpoints_rules";
	$db->setQuery( $testcat );
	if ( !$db->query() ) {		
		$query = "ALTER TABLE #__alpha_userpoints_rules ADD `category` VARCHAR( 2 ) NOT NULL DEFAULT ''";
		$db->setQuery( $query );
		$db->query();
		
		// modify lenght field for better compliance with lenght username of Joomla (150 joomla + 10 for prefixe AUP)
		$query =  "ALTER TABLE #__alpha_userpoints CHANGE `referreid` `referreid` VARCHAR( 160 )";
		$db->setQuery( $query );
		$db->query();
		
		$query =  "ALTER TABLE #__alpha_userpoints CHANGE `referraluser` `referraluser` VARCHAR( 160 )";
		$db->setQuery( $query );
		$db->query();
		
		$query =  "ALTER TABLE #__alpha_userpoints_details CHANGE `referreid` `referreid` VARCHAR( 160 )";
		$db->setQuery( $query );
		$db->query();
		
		$query =  "ALTER TABLE #__alpha_userpoints_details CHANGE `keyreference` `keyreference` VARCHAR( 255 )";
		$db->setQuery( $query );
		$db->query();
		
		$query =  "ALTER TABLE #__alpha_userpoints_requests CHANGE `referreid` `referreid` VARCHAR( 160 )";
		$db->setQuery( $query );
		$db->query();
				
	}
	
	// add new fields for profile
	$testgender = "SELECT `gender` FROM #__alpha_userpoints";
	$db->setQuery( $testgender );
	if ( !$db->query() ) {		
		$query = "ALTER TABLE #__alpha_userpoints"
				 . "\n ADD `gender` TINYINT( 1 ) NOT NULL DEFAULT '0',"
				 . "\n ADD `aboutme` VARCHAR( 250 ) NOT NULL DEFAULT '',"
				 . "\n ADD `website` VARCHAR( 150 ) NOT NULL DEFAULT '',"
				 . "\n ADD `phonehome` VARCHAR( 30 ) NOT NULL DEFAULT '',"
				 . "\n ADD `phonemobile` VARCHAR( 30 ) NOT NULL DEFAULT '',"
				 . "\n ADD `address` VARCHAR( 150 ) NOT NULL DEFAULT '',"
				 . "\n ADD `zipcode` VARCHAR( 10 ) NOT NULL DEFAULT '',"
				 . "\n ADD `city` VARCHAR( 50 ) NOT NULL DEFAULT '',"
				 . "\n ADD `country` VARCHAR( 30 ) NOT NULL DEFAULT '',"
				 . "\n ADD `education` VARCHAR( 30 ) NOT NULL DEFAULT '',"
				 . "\n ADD `graduationyear` CHAR( 4 ) NOT NULL DEFAULT '',"
				 . "\n ADD `job` VARCHAR( 50 ) NOT NULL DEFAULT '',"
				 . "\n ADD `facebook` VARCHAR( 150 ) NOT NULL DEFAULT '',"
				 . "\n ADD `twitter` VARCHAR( 150 ) NOT NULL DEFAULT '',"
				 . "\n ADD `icq` VARCHAR( 50 ) NOT NULL DEFAULT '',"
				 . "\n ADD `aim` VARCHAR( 50 ) NOT NULL DEFAULT '',"
				 . "\n ADD `yim` VARCHAR( 50 ) NOT NULL DEFAULT '',"
				 . "\n ADD `msn` VARCHAR( 50 ) NOT NULL DEFAULT '',"
				 . "\n ADD `skype` VARCHAR( 50 ) NOT NULL DEFAULT '',"
				 . "\n ADD `gtalk` VARCHAR( 50 ) NOT NULL DEFAULT '',"
				 . "\n ADD `xfire` VARCHAR( 50 ) NOT NULL DEFAULT '',"
				 . "\n ADD `profileviews` INT( 11 ) NOT NULL DEFAULT '0'"
				 ;
		$db->setQuery( $query );
		$db->query();
	}
	// add new rules system for profile
	$query = "SELECT id FROM #__alpha_userpoints_rules WHERE rule_name='AUP_UPLOADAVATAR' AND plugin_function='sysplgaup_uploadavatar'";
	$db->setQuery( $query );
	$result = $db->loadResult();
		
	if ( !$result ) {
		$query = "INSERT INTO #__alpha_userpoints_rules (`id`, `rule_name`, `rule_description`, `rule_plugin`, `plugin_function`, `access`, `component`, `calltask`, `taskid`, `points`, `percentage`, `rule_expire`, `sections`, `categories`, `content_items`, `exclude_items`, `published`, `system`, `duplicate`, `blockcopy`, `autoapproved`, `fixedpoints`, `category`) VALUES
				('', 'AUP_UPLOADAVATAR', 'AUP_UPLOADAVATAR_DESCRIPTION', 'AUP_SYSTEM', 'sysplgaup_uploadavatar', '1', '', '', '', 0, 0, '0000-00-00 00:00:00', '', '', '', '', 0, 1, 0, 1, 1, 1, 'us'),
				('', 'AUP_PROFILECOMPLETE', 'AUP_PROFILECOMPLETE_DESCRIPTION', 'AUP_SYSTEM', 'sysplgaup_profilecomplete', '1', '', '', '', 0, 0, '0000-00-00 00:00:00', '', '', '', '', 0, 1, 0, 1, 1, 1, 'us'),
				('', 'AUP_INACTIVE_USER', 'AUP_INACTIVE_USER_DESCRIPTION', 'AUP_SYSTEM', 'sysplgaup_inactiveuser', '1', '', '', '', 0, 0, '0000-00-00 00:00:00', '', '', '', '', 0, 1, 0, 1, 1, 1, 'us');"
			;
		$db->setQuery( $query );
		if ( $db->query() ) {
			$messageUpgrade = "Upgrade to version 1.5.2 stable completed.";	
		} else $messageUpgrade = "Upgrade to version 1.5.2 stable failed ! Uninstall and install the new package.";
	}
	
	// Upgrade version 1.5.2 -> version 1.5.3
	// --------------------------------------
	// add add pre-installed rules for Kunena Forum
	$query = "SELECT id FROM #__alpha_userpoints_rules WHERE plugin_function='plgaup_newtopic_kunena'";
	$db->setQuery( $query );
	$result = $db->loadResult();
	if ( !$result ) {
		$query = "INSERT INTO #__alpha_userpoints_rules (`id`, `rule_name`, `rule_description`, `rule_plugin`, `plugin_function`, `access`, `component`, `calltask`, `taskid`, `points`, `percentage`, `rule_expire`, `sections`, `categories`, `content_items`, `exclude_items`, `published`, `system`, `duplicate`, `blockcopy`, `autoapproved`, `fixedpoints`, `category`) VALUES
		('', 'AUP_KU_NEW_TOPIC', 'AUP_KU_NEW_TOPIC_DESCRIPTION', 'AUP_KUNENA_FORUM', 'plgaup_newtopic_kunena', '1', '', '', '', 0, 0, '0000-00-00 00:00:00', '', '', '', '', 0, 0, 0, 0, 1, 1, 'fo');";
		$db->setQuery( $query );
		if ( $db->query() ) {
			$messageUpgrade = "Upgrade to version 1.5.3 stable completed.";	
		} else $messageUpgrade = "Upgrade to version 1.5.3 stable failed ! Uninstall and install the new package.";				
	}
	$query = "SELECT id FROM #__alpha_userpoints_rules WHERE plugin_function='plgaup_reply_kunena'";
	$db->setQuery( $query );
	$result = $db->loadResult();	
	if ( !$result ) {
		$query = "INSERT INTO #__alpha_userpoints_rules (`id`, `rule_name`, `rule_description`, `rule_plugin`, `plugin_function`, `access`, `component`, `calltask`, `taskid`, `points`, `percentage`, `rule_expire`, `sections`, `categories`, `content_items`, `exclude_items`, `published`, `system`, `duplicate`, `blockcopy`, `autoapproved`, `fixedpoints`, `category`) VALUES
		('', 'AUP_KU_REPLY_TOPIC', 'AUP_KU_REPLY_DESCRIPTION', 'AUP_KUNENA_FORUM', 'plgaup_reply_kunena', '1', '', '', '', 0, 0, '0000-00-00 00:00:00', '', '', '', '', 0, 0, 0, 0, 1, 1, 'fo');";
		$db->setQuery( $query );
		if ( $db->query() ) {
			$messageUpgrade = "Upgrade to version 1.5.3 stable completed.";	
		} else $messageUpgrade = "Upgrade to version 1.5.3 stable failed ! Uninstall and install the new package.";				
	}	
	
	
	// Upgrade 1.5.5 to 1.5.6
	// Add fields for raffle to enter a path for download file
	$testDownloadLink = "SELECT link2download1 FROM #__alpha_userpoints_raffle";

	$db->setQuery( $testDownloadLink );
	if ( !$db->query() ) {
		$query = "ALTER TABLE #__alpha_userpoints_raffle"
				 . "\n ADD link2download1 VARCHAR( 255 ) NOT NULL DEFAULT '', "
				 . "\n ADD link2download2 VARCHAR( 255 ) NOT NULL DEFAULT '', "
				 . "\n ADD link2download3 VARCHAR( 255 ) NOT NULL DEFAULT '', "				 
				 . "\n ADD `multipleentries` tinyint( 1 ) NOT NULL default '0' "
				 ;
		$db->setQuery( $query );
		if ( $db->query() ) {
			$messageUpgrade = "Upgrade to version 1.5.6 stable completed. Please, check new params in configuration.";	
		} else $messageUpgrade = "Upgrade to version 1.5.6 stable failed ! Uninstall and install a fresh package.";				
	} 
	
	
	// =======================================================================================================================
	// add unique key on userid
	// $query = "ALTER TABLE `#__alpha_userpoints` ADD UNIQUE (`userid`)";
	// $db->setQuery( $query );
	// $db->query();
	// =======================================================================================================================
	
	
	$query = "SELECT id FROM #__alpha_userpoints_rules WHERE plugin_function='sysplgaup_archive'";
	$db->setQuery( $query );
	$result = $db->loadResult();
	if ( !$result ) {
		// add rule for archive (system)
		$query = "INSERT INTO #__alpha_userpoints_rules (`id`, `rule_name`, `rule_description`, `rule_plugin`, `plugin_function`, `access`, `component`, `calltask`, `taskid`, `points`, `percentage`, `rule_expire`, `sections`, `categories`, `content_items`, `exclude_items`, `published`, `system`, `duplicate`, `blockcopy`, `autoapproved`, `fixedpoints`, `category`) VALUES
		('', 'AUP_COMBINED_ACTIVITIES', 'AUP_COMBINE_ACTIVITIES_DESCRIPTION', 'AUP_SYSTEM', 'sysplgaup_archive', '1', '', '', '', 0, 0, '0000-00-00 00:00:00', '', '', '', '', 1, 1, 0, 1, 1, 0, 'sy');";
		$db->setQuery( $query );
		if ( $db->query() ) {
			$messageUpgrade = "Upgrade to version 1.5.6 stable completed.";	
		} else $messageUpgrade = "Upgrade to version 1.5.6 stable failed ! Uninstall and install the new package.";				
	}
	
	$query = "SELECT id FROM #__alpha_userpoints_rules WHERE plugin_function='sysplgaup_contentauthor'";
	$db->setQuery( $query );
	$result = $db->loadResult();
	if ( !$result ) {
		// add rule to donate points to the author to read article with custom points per article
		$query = "INSERT INTO #__alpha_userpoints_rules (`id`, `rule_name`, `rule_description`, `rule_plugin`, `plugin_function`, `access`, `component`, `calltask`, `taskid`, `points`, `percentage`, `rule_expire`, `sections`, `categories`, `content_items`, `exclude_items`, `published`, `system`, `duplicate`, `blockcopy`, `autoapproved`, `fixedpoints`, `category`) VALUES
		('', 'AUP_CONTENTAUTHOR', 'AUP_CONTENTAUTHOR_DESCRIPTION', 'AUP_SYSTEM', 'sysplgaup_contentauthor', '1', '', '', '', 0, 0, '0000-00-00 00:00:00', '', '', '', '', 0, 1, 0, 1, 1, 0, 'ar');";
		$db->setQuery( $query );
		if ( $db->query() ) {
			$messageUpgrade = "Upgrade to version 1.5.6 stable completed.";	
		} else $messageUpgrade = "Upgrade to version 1.5.6 stable failed ! Uninstall and install the new package.";				
	}  
	
	
	// Upgrade version 1.5.11 -> version 1.5.12
	// ----------------------------------------
	// add pre-installed rule Create topic for Kunena Forum (new nommage)
	$query = "SELECT id FROM #__alpha_userpoints_rules WHERE plugin_function='plgaup_kunena_topic_create'";
	$db->setQuery( $query );
	$result = $db->loadResult();
	if ( !$result ) {
		$query = "INSERT INTO #__alpha_userpoints_rules (`id`, `rule_name`, `rule_description`, `rule_plugin`, `plugin_function`, `access`, `component`, `calltask`, `taskid`, `points`, `percentage`, `rule_expire`, `sections`, `categories`, `content_items`, `exclude_items`, `published`, `system`, `duplicate`, `blockcopy`, `autoapproved`, `fixedpoints`, `category`) VALUES
		('', 'AUP_KU_NEW_TOPIC', 'AUP_KU_NEW_TOPIC_DESCRIPTION', 'AUP_KUNENA_FORUM', 'plgaup_kunena_topic_create', '1', '', '', '', 0, 0, '0000-00-00 00:00:00', '', '', '', '', 0, 0, 0, 0, 1, 1, 'fo');";
		$db->setQuery( $query );
		if ( $db->query() ) {
					
			// migration datas old rules for Kunena to the newest
			$query = "SELECT * FROM #__alpha_userpoints_rules WHERE plugin_function='plgaup_newtopic_kunena' AND published='1' LIMIT 1";
			$db->setQuery( $query );
			$newtopic = $db->loadObject();
			if ( $newtopic ) {
				$query = "UPDATE #__alpha_userpoints_rules SET `access`='".$newtopic->access."', `component`='".$newtopic->component."', `points`='".$newtopic->points."', `rule_expire`='".$newtopic->rule_expire."', `published`='1', "
				. "`system`='".$newtopic->system."', `duplicate`='".$newtopic->duplicate."', `blockcopy`='".$newtopic->blockcopy."', `autoapproved`='".$newtopic->autoapproved."', `fixedpoints`='".$newtopic->fixedpoints."', `category`='".$newtopic->category."' "
				. "WHERE `plugin_function`='plgaup_kunena_topic_create'";
				$db->setQuery( $query );
				$db->query();
			}			
			
			$messageUpgrade = "Upgrade to version 1.5.12 stable completed.";
			
		} else $messageUpgrade = "Upgrade to version 1.5.12 stable failed ! Uninstall and install the new package.";
	}
	// add pre-installed rule Reply topic for Kunena Forum (new nommage)
	$query = "SELECT id FROM #__alpha_userpoints_rules WHERE plugin_function='plgaup_kunena_topic_reply'";
	$db->setQuery( $query );
	$result = $db->loadResult();
	if ( !$result ) {
		$query = "INSERT INTO #__alpha_userpoints_rules (`id`, `rule_name`, `rule_description`, `rule_plugin`, `plugin_function`, `access`, `component`, `calltask`, `taskid`, `points`, `percentage`, `rule_expire`, `sections`, `categories`, `content_items`, `exclude_items`, `published`, `system`, `duplicate`, `blockcopy`, `autoapproved`, `fixedpoints`, `category`) VALUES
		('', 'AUP_KU_REPLY_TOPIC', 'AUP_KU_REPLY_DESCRIPTION', 'AUP_KUNENA_FORUM', 'plgaup_kunena_topic_reply', '1', '', '', '', 0, 0, '0000-00-00 00:00:00', '', '', '', '', 0, 0, 0, 0, 1, 1, 'fo');";
		$db->setQuery( $query );
		if ( $db->query() ) {
		
			// migration datas old rules for Kunena
			$query = "SELECT * FROM #__alpha_userpoints_rules WHERE plugin_function='plgaup_reply_kunena' AND published='1' LIMIT 1";
			$db->setQuery( $query );
			$reply = $db->loadObject();
			if ( $reply ) {
				$query = "UPDATE #__alpha_userpoints_rules SET `access`='".$newtopic->access."', `component`='".$newtopic->component."', `points`='".$newtopic->points."', `rule_expire`='".$newtopic->rule_expire."', `published`='1', "
				. "`system`='".$newtopic->system."', `duplicate`='".$newtopic->duplicate."', `blockcopy`='".$newtopic->blockcopy."', `autoapproved`='".$newtopic->autoapproved."', `fixedpoints`='".$newtopic->fixedpoints."', `category`='".$newtopic->category."' "
				. "WHERE `plugin_function`='plgaup_kunena_topic_reply'";
				$db->setQuery( $query );
				$db->query();
			}
		
			$messageUpgrade = "Upgrade to version 1.5.12 stable completed.";	
			
		} else $messageUpgrade = "Upgrade to version 1.5.12 stable failed ! Uninstall and install the new package.";
	}
	
	// --------------------------------------
	// add pre-installed rule Thank You for Kunena Forum
	$query = "SELECT id FROM #__alpha_userpoints_rules WHERE plugin_function='plgaup_kunena_message_thankyou'";
	$db->setQuery( $query );
	$result = $db->loadResult();
	if ( !$result ) {
		$query = "INSERT INTO #__alpha_userpoints_rules (`id`, `rule_name`, `rule_description`, `rule_plugin`, `plugin_function`, `access`, `component`, `calltask`, `taskid`, `points`, `percentage`, `rule_expire`, `sections`, `categories`, `content_items`, `exclude_items`, `published`, `system`, `duplicate`, `blockcopy`, `autoapproved`, `fixedpoints`, `category`) VALUES
		('', 'AUP_KU_THANKYOU', 'AUP_KU_THANKYOU_DESCRIPTION', 'AUP_KUNENA_FORUM', 'plgaup_kunena_message_thankyou', '1', '', '', '', 0, 0, '0000-00-00 00:00:00', '', '', '', '', 0, 0, 0, 0, 1, 1, 'fo');";
		$db->setQuery( $query );
		if ( $db->query() ) {
			$messageUpgrade = "Upgrade to version 1.5.12 stable completed.";	
		} else $messageUpgrade = "Upgrade to version 1.5.12 stable failed ! Uninstall and install the new package.";
	}
	
	// add pre-installed rule Delete Post for Kunena Forum
	$query = "SELECT id FROM #__alpha_userpoints_rules WHERE plugin_function='plgaup_kunena_message_delete'";
	$db->setQuery( $query );
	$result = $db->loadResult();
	if ( !$result ) {
		$query = "INSERT INTO #__alpha_userpoints_rules (`id`, `rule_name`, `rule_description`, `rule_plugin`, `plugin_function`, `access`, `component`, `calltask`, `taskid`, `points`, `percentage`, `rule_expire`, `sections`, `categories`, `content_items`, `exclude_items`, `published`, `system`, `duplicate`, `blockcopy`, `autoapproved`, `fixedpoints`, `category`) VALUES
		('', 'AUP_KU_DELETE_POST', 'AUP_KU_DELETE_POST_DESCRIPTION', 'AUP_KUNENA_FORUM', 'plgaup_kunena_message_delete', '1', '', '', '', 0, 0, '0000-00-00 00:00:00', '', '', '', '', 0, 0, 0, 0, 1, 1, 'fo');";
		$db->setQuery( $query );
		if ( $db->query() ) {
			$messageUpgrade = "Upgrade to version 1.5.12 stable completed.";	
		} else $messageUpgrade = "Upgrade to version 1.5.12 stable failed ! Uninstall and install the new package.";
	}
	
	// update table version
	$query = "INSERT INTO #__alpha_userpoints_version (`version`) VALUES ('1.5.13')";
	$db->setQuery( $query );
	$db->query();
	$messageUpgrade = "Upgrade to version 1.5.13 stable completed.";
	// end message upgrade
	if ( $messageUpgrade ) $install .= '<br /><div style="color: #c00; background: #EFE7B8; border-top: 3px solid #F0DC7E; border-bottom: 3px solid #F0DC7E;"><img src="templates/system/imagesnotice-note.png" alt="" />&nbsp;&nbsp;' . $messageUpgrade.'</div><br />';
	
?>
<h1>AlphaUserPoints Installation <?php echo _ALPHAUSERPOINTS_NUM_VERSION ; ?></h1>
<table width="100%" border="0">
<tr>
  <td><img src="components/com_alphauserpoints/assets/images/aup_logo.png" alt="" /></td>
</tr>
<tr>
	<td>
	<code>
	<?php echo $install; ?>
	</code><br />	
	<?php aup_CopySite ('left'); ?><br />
	</td>
</tr>
<tr>
  <td style="border:1px solid #999999;color:green;font-weight:bold; background-color:#EFEFEF;"><img src="images/tick.png" alt="" />&nbsp;&nbsp;Installation finished.&nbsp;</td>
</tr>
</table>
<?php } ?>