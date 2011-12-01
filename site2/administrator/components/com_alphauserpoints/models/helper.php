<?php
/*
 * @component AlphaUserPoints
 * @copyright Copyright (C) 2008-2010 Bernard Gilly
 * @license : GNU/GPL
 * @Website : http://www.alphaplug.com
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.model' );

class alphauserpointsModelHelper extends Jmodel {

	function __construct()
	{
		parent::__construct();
	}
	
	function _aup_publish( $cid=null, $publish=1, $option, $table, $redirect ) 
	{
		$app = JFactory::getApplication();
		
		// initialize variables
		$db		= & JFactory::getDBO();		
		
		if (count($cid) < 1) 
		{
			$action = ( $publish == 1 )? 'publish' : 'unpublish';
			JViewContent::displayError( JText::_('Select an item to') . ' ' . JText::_($action) );
			return false;
		}
		
		$cids	= implode(',', $cid);

		$query = "UPDATE #__$table" .
		"\n SET published = $publish" .
		"\n WHERE id IN ( $cids )"
		;
		$db->setQuery( $query );

		if (!$db->query()) 
		{
			JError::raiseError( 500, $db->getErrorMsg() );
			return false;
		}		
		
		if ( $table == 'alpha_userpoints_rules' ) 
		{ 
			// check new user rule is already enabled
			$query = "UPDATE #__$table" .
			"\n SET `published`= '1'" .
			"\n WHERE `plugin_function`='sysplgaup_newregistered'"
			;
			$db->setQuery( $query );
	
			if (!$db->query()) 
			{
				JError::raiseError( 500, $db->getErrorMsg() );
				return false;
			}		
		}
				
		$redirecturl = "index.php?option=$option&task=$redirect";		
		
		$app->redirect( $redirecturl );	
	}
	
	function _aup_autoapprove( $cid=null, $autoapprove=1, $option, $table, $redirect ) 
	{
		$app = JFactory::getApplication();
		
		// initialize variables
		$db		= & JFactory::getDBO();		
		
		if (count($cid) < 1) 
		{
			$action = ( $autoapprove == 1 )? 'autoapprove' : 'unautoapprove';
			JViewContent::displayError( JText::_('Select an item to') . ' ' . JText::_($action) );
			return false;
		}
		
		$cids	= implode(',', $cid);

		$query = "UPDATE #__$table" .
		"\n SET autoapproved = $autoapprove" .
		"\n WHERE id IN ( $cids )"
		;
		$db->setQuery( $query );

		if (!$db->query()) 
		{
			JError::raiseError( 500, $db->getErrorMsg() );
			return false;
		}		
		
		// check new user rule is already enabled
		$query = "UPDATE #__$table" .
		"\n SET `autoapproved`= '1'" .
		"\n WHERE `plugin_function`='sysplgaup_newregistered'"
		;
		$db->setQuery( $query );

		if (!$db->query()) 
		{
			JError::raiseError( 500, $db->getErrorMsg() );
			return false;
		}		
				
		$redirecturl = "index.php?option=$option&task=$redirect";		
		
		$app->redirect( $redirecturl );	
	}
	
	function _aup_approve( $cid=null, $approved=1, $option, $table, $redirect ) 
	{
		$app = JFactory::getApplication();
		
		// pending approval		
		$db		= & JFactory::getDBO();
		
		if (count($cid) < 1) 
		{
			$action = ( $approved == 1 )? 'approve' : 'unapprove';
			JViewContent::displayError( JText::_('Select an item to') . ' ' . JText::_($action) );
			return false;
		}
		
		$cids	= implode(',', $cid);		

		$query = "UPDATE #__$table" .
		"\n SET approved = $approved" .
		"\n WHERE id IN ( $cids )"
		;
		$db->setQuery( $query );

		if (!$db->query())
		{
			JError::raiseError( 500, $db->getErrorMsg() );
			return false;
		}
		
		// Updade points member account and check the new status
		if ( $approved )  
		{		
			foreach ( $cid as $id ) {
				$this->updateUserAccount($id, 0);
			}
		}
		
		$redirecturl = "index.php?option=$option&task=$redirect";
		$app->redirect( $redirecturl );
		
	}
	
	function updateUserAccount($cid, $message=1) 
	{
		// cid is the ID of action with points stored in #__alpha_userpoints_details on pending approval
		$db	   =& JFactory::getDBO();
		
		$jnow		=& JFactory::getDate();
		$now		= $jnow->toMySQL();
		
		// check status			
		$query = "SELECT a.*, r.*"
			   . " FROM #__alpha_userpoints_details AS a, #__alpha_userpoints_rules AS r"
			   . " WHERE a.id='$cid' AND a.rule=r.id";
		$db->setQuery( $query );
		$details = $db->loadObjectList();
		
		if ( !$details ) return;
		
		// already approved -> exit
		if ( $details[0]->status ) return;
		
		$query = "SELECT id, referraluser FROM #__alpha_userpoints WHERE `referreid`='".$details[0]->referreid."'";
		$db->setQuery( $query );
		$aupUser = $db->loadObjectList();
		$referrerUser = $aupUser[0]->id;
		$referraluser = $aupUser[0]->referraluser;
	
		$row =& JTable::getInstance('userspoints');
		
		// update points into alpha_userpoints table
		$row->load( intval($referrerUser) );
		
		$assignpoints = $details[0]->points;
		$referrerid   = $details[0]->referreid;
		
		$newtotal = $row->points + $assignpoints ;
		
		$row->last_update	= $now;
		
		if ( $details[0]->plugin_function=='sysplgaup_invitewithsuccess' )
		{
			// update number referrees
			$row->referrees = $row->referrees+1;
		}
		
		$checkWinner = 0;				
		if ( $row->max_points >=1 && ( $newtotal > $row->max_points ) ) 
		{
			// Max total was reached !
			$newtotal = $row->max_points;
			// HERE YOU CAN ADD MORE FUNCTIONS! example call other component etc...
			if ( $this->checkRuleIsEnabled( 'sysplgaup_winnernotification' ) ) 
			{
				// get email admins in rule
				$query = "SELECT `content_items` FROM #__alpha_userpoints_rules WHERE `plugin_function`='sysplgaup_winnernotification'";
				$db->setQuery( $query );
				$emailadmins = $db->loadResult();
				$this->sendwinnernotification ( $referrerid, $assignpoints, $newtotal, $emailadmins );				
				$checkWinner = 1;
			}
		}		
								
		$row->points		= $newtotal;

		$db->updateObject( '#__alpha_userpoints', $row, 'id' );
		
		if ( $this->checkRuleIsEnabled( 'sysplgaup_emailnotification' ) && !$checkWinner ) $this->sendnotification ( $referrerid, $assignpoints, $newtotal, $details[0]->rule_name );
		
		// Assign status = 1
		$query = "UPDATE #__alpha_userpoints_details" .
		"\n SET status='1'" .
		"\n WHERE id ='$cid'"
		;
		$db->setQuery( $query );

		if (!$db->query()) 
		{
			JError::raiseError( 500, $db->getErrorMsg() );
			return false;
		}
		
		require_once (JPATH_SITE.DS.'components'.DS.'com_alphauserpoints'.DS.'helper.php');
		
		// If referral user exist		
		if ( $referraluser!='' && $details[0]->plugin_function!='sysplgaup_buypointswithpaypal' && $details[0]->plugin_function!='sysplgaup_raffle' && $details[0]->plugin_function!='sysplgaup_referralpoints' ) {
			$query = "SELECT * FROM #__alpha_userpoints_rules WHERE `plugin_function`='sysplgaup_referralpoints' AND `published`='1' AND (`rule_expire`>'$now' OR `rule_expire`='0000-00-00 00:00:00')";
			$db->setQuery( $query );
			$referralpoints = $db->loadObjectList();
			if ( $referralpoints ){			
				$referraluserpoints = round(($details[0]->points*$referralpoints[0]->points)/100) ;
				if ( $referraluserpoints>=1 ) {					
					AlphaUserPointsHelper::userpoints( 'sysplgaup_referralpoints', $referraluser, $referraluserpoints );
					$this->checkTotalAfterRecalculate( $referraluser, $details[0]->rule );
				}
			}			
		}		
		
		// recalculate for this user 
		$query = "SELECT SUM(points) FROM #__alpha_userpoints_details WHERE `referreid`='" . $details[0]->referreid . "' AND `approved`='1' AND (`expire_date`>'$now' OR `expire_date`='0000-00-00 00:00:00')";
		$db->setQuery($query);
		$newtotal = $db->loadResult();

		$query = "UPDATE #__alpha_userpoints SET `points`='" . intval($newtotal) . "', `last_update`='$now' WHERE `referreid`='" . $details[0]->referreid . "'";
		$db->setQuery( $query );
		$db->query();
		
		// update Ranks / Medals if necessary
		AlphaUserPointsHelper::checkRankMedal ( $details[0]->referreid, $details[0]->rule );
	
	}
	
	function checkRuleIsEnabled( $plugin_function='' ) 
	{
	
		if ( !$plugin_function ) return false;
	
		$jnow		=& JFactory::getDate();
		$now		= $jnow->toMySQL();
		
		$db	   =& JFactory::getDBO();		
		$query = "SELECT id FROM #__alpha_userpoints_rules WHERE `plugin_function`='$plugin_function' AND `published`='1' $accessrule AND (`rule_expire`>'$now' OR `rule_expire`='0000-00-00 00:00:00')";
		
		$db->setQuery( $query );
		$result  = $db->loadResult();
		return $result;
	
	}
	
	function sendnotification ( $referrerid, $assignpoints, $newtotal, $rule_name ) 
	{
		$app = JFactory::getApplication();
		
		if ( !$referrerid ) return;
		
		$MailFrom	= $app->getCfg('mailfrom'); 	
		$FromName	= $app->getCfg('fromname'); 
		$SiteName	= $app->getCfg('sitename');		
		
		$userinfo = $this->getUserInfo( $referrerid );		
		$email	  = $userinfo->email;
		
		if ( !$userinfo->block ) 
		{		
			if ( $assignpoints>0 ) 
			{
				$subject = JText::_('AUP_EMAILNOTIFICATION_SUBJECT');
				$message = sprintf ( JText::_('AUP_EMAILNOTIFICATION_MSG'), $SiteName, $assignpoints, $newtotal, JText::_($rule_name) );	
			} 
			elseif ( $assignpoints<0 )			
			{
				$subject = JText::_('AUP_EMAILNOTIFICATION_SUBJECT_ACCOUNT_UPDATED');
				$message = sprintf ( JText::_('AUP_EMAILNOTIFICATION_MSG_REMOVE_POINTS'), $SiteName, abs($assignpoints), $newtotal, JText::_($rule_name) );	
			}
			JUtility::sendMail( $MailFrom, $FromName, $email, $subject, $message );
		}
		
	}
	
	function sendwinnernotification ( $referrerid, $assignpoints, $newtotal, $emailadmins='' ) 
	{
		$app = JFactory::getApplication();
		
		$MailFrom	= $app->getCfg('mailfrom'); 	
		$FromName	= $app->getCfg('fromname'); 
		
		$userinfo 	= $this->getUserInfo( $referrerid );
		$name 		= $userinfo->name;
		$email	 	= $userinfo->email;

		if ( !$userinfo->block ) 
		{		
		
			// send notification to winner
			$subject = JText::_('AUP_EMAILWINNERNOTIFICATION_SUBJECT_MSG_USER');
			$message = sprintf ( JText::_('AUP_EMAILWINNERNOTIFICATION_MSG_USER'), $name, $newtotal );
			
			JUtility::sendMail( $MailFrom, $FromName, $email, $subject, $message );		
			
			// send notification to administrators...		
			if ( $emailadmins ) 
			{
				$subject = JText::_('AUP_EMAILWINNERNOTIFICATION_SUBJECT_MSG_ADMIN');
				$message = sprintf ( JText::_('AUP_EMAILWINNERNOTIFICATION_MSG_ADMIN'), $name, $newtotal );
				
				JUtility::sendMail( $MailFrom, $FromName, $emailadmins, $subject, $message );
			}
		}
	
	}
	
	function getUserInfo ( $referrerid='' ) 
	{
	
		if ( !$referrerid ) return;
	
		$db	   =& JFactory::getDBO();
		
		$query = "SELECT a.*, a.id AS rid, u.* FROM #__alpha_userpoints AS a, #__users AS u WHERE a.referreid='$referrerid' AND a.userid=u.id";
		$db->setQuery( $query );
		$userinfo = $db->loadObjectList();
	
		return @$userinfo[0];
	
	}
	
	function _bonuspoints ( $cids ) 
	{
		$app = JFactory::getApplication();
	
		// initialize variables
		$db		= & JFactory::getDBO();			
		
		$query = "SELECT `id` FROM #__alpha_userpoints_rules WHERE `plugin_function`='sysplgaup_bonuspoints'";
		$db->setQuery( $query );
		$rule_id = $db->loadResult();

		JArrayHelper::toInteger($cids);
		
		if (count($cids)) 
		{		
			require_once ( JPATH_SITE.DS.'components'.DS.'com_alphauserpoints'.DS.'helper.php' );			
			foreach( $cids as $cid ) 
			{			
				$query = "SELECT referreid FROM #__alpha_userpoints WHERE id='".$cid."'";
				$db->setQuery( $query );
				$referrerid = $db->loadResult();
				if ( $referrerid )
				{
					AlphaUserPointsHelper::userpoints ( 'sysplgaup_bonuspoints' , $referrerid, 0, '', JText::_('AUP_BONUSPOINTS') );
					$this->checkTotalAfterRecalculate( $referrerid, $rule_id );
					
				}
			}			
			$app->enqueueMessage( JText::_('AUP_RECALCULATION_MADE' ) );
		}
		$redirecturl = "index.php?option=com_alphauserpoints&task=statistics";		
		
		$app->redirect( $redirecturl );

	}
	
	function _aup_registration_raffle( $cid=null, $regitration=1, $option, $table, $redirect )
	{
		$app = JFactory::getApplication();
		
		// initialize variables
		$db		= & JFactory::getDBO();		
		
		if (count($cid) < 1) 
		{
			$action = ( $regitration == 1 )? 'regitration' : 'unregitration';
			JViewContent::displayError( JText::_('Select an item to') . ' ' . JText::_($action) );
			return false;
		}
		
		$cids	= implode(',', $cid);

		$query = "UPDATE #__$table" .
		"\n SET inscription = $regitration" .
		"\n WHERE id IN ( $cids )"
		;
		$db->setQuery( $query );

		if (!$db->query()) 
		{
			JError::raiseError( 500, $db->getErrorMsg() );
			return false;
		}		
		
		$redirecturl = "index.php?option=$option&task=$redirect";		
		
		$app->redirect( $redirecturl );	
	}

	function _customrulepoints ( $cids, $reason, $points ) 
	{
		$app = JFactory::getApplication();
	
		// initialize variables
		$db		= & JFactory::getDBO();	
		
		$query = "SELECT `id` FROM #__alpha_userpoints_rules WHERE `plugin_function`='sysplgaup_custom'";
		$db->setQuery( $query );
		$rule_id = $db->loadResult();

		JArrayHelper::toInteger($cids);
		
		if (count($cids)) 
		{		
			require_once ( JPATH_SITE.DS.'components'.DS.'com_alphauserpoints'.DS.'helper.php' );			
			foreach( $cids as $cid ) 
			{			
				$query = "SELECT referreid FROM #__alpha_userpoints WHERE id='".$cid."'";
				$db->setQuery( $query );
				$referrerid = $db->loadResult();
				if ( $referrerid )
				{					
					AlphaUserPointsHelper::userpoints ( 'sysplgaup_custom', $referrerid, 0, '', $reason, $points );
					$this->checkTotalAfterRecalculate( $referrerid, $rule_id );
					
				}
			}			
			$app->enqueueMessage( JText::_('AUP_RECALCULATION_MADE' ) );
		}
		$redirecturl = "index.php?option=com_alphauserpoints&task=statistics";		
		
		$app->redirect( $redirecturl );

	}
	
	function checkTotalAfterRecalculate( $referrerid, $rule_id=0 )
	{
		$db			=& JFactory::getDBO();
		$jnow		=& JFactory::getDate();		
		$now		= $jnow->toMySQL();
		
		require_once (JPATH_SITE.DS.'components'.DS.'com_alphauserpoints'.DS.'helper.php');		
		
		// recalculate for this user 
		$query = "SELECT SUM(points) FROM #__alpha_userpoints_details WHERE `referreid`='" . $referrerid . "' AND `approved`='1' AND (`expire_date`>'$now' OR `expire_date`='0000-00-00 00:00:00')";
		$db->setQuery($query);
		$newtotal = $db->loadResult();

		$query = "UPDATE #__alpha_userpoints SET `points`='" . intval($newtotal) . "', `last_update`='$now' WHERE `referreid`='" . $referrerid . "'";
		$db->setQuery( $query );
		$db->query();
		
		// update Ranks / Medals if necessary		
		AlphaUserPointsHelper::checkRankMedal ( $referrerid, $rule_id );
	
	}

		
}
?>