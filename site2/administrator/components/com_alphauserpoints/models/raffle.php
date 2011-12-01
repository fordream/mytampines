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

class alphauserpointsModelRaffle extends Jmodel {

	function __construct(){
		parent::__construct();
	}
	
	function _load_raffle() {
		$app = JFactory::getApplication();
		
		$db			    =& JFactory::getDBO();
		
		$total 			= 0;
		
		// Get the pagination request variables
		$limit = $app->getUserStateFromRequest('com_alphauserpoints.limit', 'limit', $app->getCfg('list_limit'), 'int');
		$limitstart = JRequest::getVar('limitstart', 0, '', 'int');
		// In case limit has been changed, adjust limitstart accordingly
		$limitstart = ( $limit != 0 ? (floor( $limitstart / $limit ) * $limit) : 0);

		$query = "SELECT r.*, COUNT(ri.id) AS numregistered FROM #__alpha_userpoints_raffle AS r"
		. " LEFT JOIN #__alpha_userpoints_raffle_inscriptions AS ri ON ri.raffleid=r.id"
		. " GROUP BY r.id"
		. " ORDER BY r.id DESC";
		$total = @$this->_getListCount($query);
		$result = $this->_getList($query, $limitstart, $limit);
		
		// message if rule disabled
		$query = "SELECT id FROM #__alpha_userpoints_rules WHERE plugin_function = 'sysplgaup_raffle' AND published = '1'";
		$db->setQuery($query);
		$alert = $db->loadResult();
		
		if ( !$alert ) $app->enqueueMessage( JText::_('AUP_THIS_RULE_IS_DISABLED'));		
		
		return array($result, $total, $limit, $limitstart);
	
	}	
	
	function _edit_raffle() {
	
		$db     =& JFactory::getDBO();

		$cid 	= JRequest::getVar('cid', array(0));
		$option = JRequest::getVar('option');
		
		if (!is_array( $cid )) {
			$cid = array(0);
		}

		$lists = array();

		$row =& JTable::getInstance('raffle');
		$row->load( $cid[0] );
		
		return $row;
	
	}	
	
	function _delete_raffle() {
		$app = JFactory::getApplication();

		// initialize variables
		$db			=& JFactory::getDBO();
		$cid		= JRequest::getVar('cid', array(), 'post', 'array');
		$msgType	= '';
		
		JArrayHelper::toInteger($cid);

		if (count($cid)) {		
			
			$query = "DELETE FROM #__alpha_userpoints_raffle"
					. "\n WHERE (`id` = " . implode(' OR `id` = ', $cid) . ")"
					;
			$db->setQuery($query);
			
			if (!$db->query()) {
				$msg = $db->getErrorMsg();
				$msgType = 'error';
			}
			
			$query = "DELETE FROM #__alpha_userpoints_raffle_inscriptions"
					. "\n WHERE (`raffleid` = " . implode(' OR `raffleid` = ', $cid) . ")"
					;
			$db->setQuery($query);
			
			if (!$db->query()) {
				$msg = $db->getErrorMsg();
				$msgType = 'error';
			}

		}

		$app->redirect('index.php?option=com_alphauserpoints&task=raffle', $msg, $msgType);
		
	}
	
	function _save_raffle() {
		$app = JFactory::getApplication();

		// initialize variables
		$db =& JFactory::getDBO();
		$post	= JRequest::get( 'post' );
		
		$row =& JTable::getInstance('raffle');
		
		switch ( $post['rafflesystem'] )
		{
			case '0':
				$post['couponcodeid1'] = '0';
				$post['couponcodeid2'] = '0';
				$post['couponcodeid3'] = '0';
				$post['link2download1'] = '';
				$post['link2download2'] = '';
				$post['link2download3'] = '';			
				break;				
			case '1':
				$post['pointstoearn1'] = '0';
				$post['pointstoearn2'] = '0';
				$post['pointstoearn3'] = '0';
				$post['link2download1'] = '';
				$post['link2download2'] = '';
				$post['link2download3'] = '';			
				break;
			case '2':
				$post['pointstoearn1'] = '0';
				$post['pointstoearn2'] = '0';
				$post['pointstoearn3'] = '0';			
				$post['couponcodeid1'] = '0';
				$post['couponcodeid2'] = '0';
				$post['couponcodeid3'] = '0';
				break;		
		}
		
		if (!$row->bind( $post )) {
			echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
			exit();
		}
		
		if (!$row->store()) {
			echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
			exit();
		}

		$msg = JText::_( 'AUP_DETAILSSAVED' );
		$app->redirect( 'index.php?option=com_alphauserpoints&task=raffle', $msg );
	}
	
	function _make_raffle_now() {
		$app = JFactory::getApplication();
		
		require_once (JPATH_SITE.DS.'components'.DS.'com_alphauserpoints'.DS.'helper.php');	
		
		$db =& JFactory::getDBO();
		
		// Proceed raffle now
		$cid 	= JRequest::getVar('cid', array(0));
		$rowRaffle = $this->_edit_raffle();
		
		$jnow		=& JFactory::getDate();
		$now		= $jnow->toMySQL();
				
		// add offset
		$config =& JFactory::getConfig();
		$tzoffset = $config->getValue('config.offset');
		$datetimestamp = strtotime($now);
		$now = date('Y-m-d H:i:s', $datetimestamp + ($tzoffset * 60 * 60));		
		
		// get params definitions
		$params =& JComponentHelper::getParams( 'com_alphauserpoints' );
		
		// Raffle date is not today ?
		if ( $rowRaffle->raffledate!='0000-00-00 00:00:00' && $now < $rowRaffle->raffledate ){
			echo "<script> alert('".JText::_( 'AUP_DATE_OF_RAFFLE_IS_NOT_TODAY' )."'); window.history.go(-1); </script>\n";
			exit();	
		}
		
		$winner1 = 0;
		$winner2 = 0;
		$winner3 = 0;
		$winner1_Referreid = 0;
		$winner2_Referreid = 0;
		$winner3_Referreid = 0;
		
		// exclude users if rule enabled
		$queryExclude = "";
		$query = "SELECT exclude_items FROM #__alpha_userpoints_rules WHERE plugin_function='sysplgaup_excludeusers' AND published='1'";
		$db->setQuery( $query );
		$resultExcludeUsers  = $db->loadResult();
		if ( $resultExcludeUsers ) {
			$uids = explode( ',', $resultExcludeUsers );
			$queryExclude =  " AND (referreid!='" . implode( "' AND referreid!='", $uids ) . "')";
		}
	
		// first raffle
		// select all users registered or only users with registration for this raffle 
		if ( $rowRaffle->inscription ) 
		{			
			$query = "SELECT id, userid as uid FROM #__alpha_userpoints_raffle_inscriptions WHERE raffleid=" . $rowRaffle->id;	
		} 
		else 
		{
			$query = "SELECT userid as uid FROM #__alpha_userpoints WHERE blocked='0'".$queryExclude;
		}		
		$db->setQuery($query);
		$listParticipants = $db->loadObjectList();
		
		if ( !$listParticipants ) 
		{ 
			echo "<script> alert('".JText::_( 'AUP_NO_PARTICIPANT' )."'); window.history.go(-1); </script>\n";
			exit();
		}	
		
		$max = count($listParticipants)-1;		
		$choice = rand(0, $max);		
		$winner1 = $listParticipants[$choice]->uid;		
		/*
		if ( $rowRaffle->inscription ) {
			$participant1 = $listParticipants[$choice]->id;
		}
		*/
		if ( $rowRaffle->numwinner>1 )
		{
			// 2th raffle without first winner
			if ( $rowRaffle->inscription )
			{			
				$query = "SELECT userid as uid FROM #__alpha_userpoints_raffle_inscriptions WHERE raffleid=" . $rowRaffle->id . " AND userid!='$winner1'";
				//$query = "SELECT userid as uid FROM #__alpha_userpoints_raffle_inscriptions WHERE raffleid=" . $rowRaffle->id . " AND id!='$participant1' AND userid!='$winner1'";
			} 
			else 
			{
				$query = "SELECT userid as uid FROM #__alpha_userpoints WHERE blocked='0' AND userid!='$winner1'".$queryExclude;
			}
			$db->setQuery($query);
			$listParticipants2 = $db->loadObjectList();
	
			$max2 = count($listParticipants2)-1;
			$choice2 = rand(0, $max2);
			$winner2 = @$listParticipants2[$choice2]->uid;
			/*
			if ( $rowRaffle->inscription ) {
				$participant2 = $listParticipants[$choice2]->id;
			}
			*/
			
		}
		
		if ( $rowRaffle->numwinner==3 )
		{
			// 3th raffle without first and second winner
			if ( $rowRaffle->inscription ) 
			{			
				$query = "SELECT userid as uid FROM #__alpha_userpoints_raffle_inscriptions WHERE raffleid=" . $rowRaffle->id . " AND userid!='$winner1' AND userid!='$winner2'";		
				//$query = "SELECT userid as uid FROM #__alpha_userpoints_raffle_inscriptions WHERE raffleid=" . $rowRaffle->id . " AND id!='$participant1' AND id!='$participant2' AND userid!='$winner1' AND userid!='$winner2'";		
			} 
			else 
			{
				$query = "SELECT userid as uid FROM #__alpha_userpoints WHERE blocked='0' AND userid!='$winner1' AND userid!='$winner2'".$queryExclude;
			}
			$db->setQuery($query);
			$listParticipants3 = $db->loadObjectList();
			
			$max3 = count($listParticipants3)-1;
			$choice3 = rand(0, $max3);
			$winner3 = @$listParticipants3[$choice3]->uid;
		}
		
		// Save winner(s)
		$row =& JTable::getInstance('raffle');
		$row->load( $rowRaffle->id );
		
		$row->winner1 = $winner1;
		$row->winner2 = $winner2;
		$row->winner3 = $winner3;
		
		if (!$row->store()) {
			JError::raiseError(500, $row->getError());
		}	
		
		// attribs points or coupon code
		if ( $winner1 ) $winner1_Referreid = AlphaUserPointsHelper::getAnyUserReferreID( intval($winner1) );	
		if ( $winner2 ) $winner2_Referreid = AlphaUserPointsHelper::getAnyUserReferreID( intval($winner2) );	
		if ( $winner3 ) $winner3_Referreid = AlphaUserPointsHelper::getAnyUserReferreID( intval($winner3) );
		
		if ( $rowRaffle->rafflesystem=='1' )
		{
			// is coupon code ...				
			
			// send notification by email		
			if ( $rowRaffle->sendcouponbyemail )
			{
				if ( $winner1 && $rowRaffle->couponcodeid1 ) {
					$this->sendnotification4couponcode ( $winner1_Referreid, $this->_get_Coupon($rowRaffle->couponcodeid1) );
				}
				if ( $winner2 && $rowRaffle->couponcodeid2 ) {
					$this->sendnotification4couponcode ( $winner2_Referreid, $this->_get_Coupon($rowRaffle->couponcodeid2) );
				}
				if ( $winner3 && $rowRaffle->couponcodeid3 ) {
					$this->sendnotification4couponcode ( $winner3_Referreid, $this->_get_Coupon($rowRaffle->couponcodeid3) );
				}
			}
			
			// uddeim notification
			if ( $params->get( 'sendMsgUddeim', 0 ) && $params->get('fromIdUddeim') )
			{					
				// Uddeim notification integration
				require_once (JPATH_SITE.DS.'components'.DS.'com_alphauserpoints'.DS.'helpers'.DS.'uddeim.api.php');
				$SiteName	= $app->getCfg('sitename');				
				
				if ( $winner1 && $rowRaffle->couponcodeid1 ) {
					$message = sprintf ( JText::_('AUP_EMAILNOTIFICATION_MSG_COUPONCODE'), $SiteName, $this->_get_Coupon($rowRaffle->couponcodeid1) );
					uddeIMAPI::sendNewMessage( intval($params->get('fromIdUddeim')), intval($winner1), $message );
					$message = "";
				}
				if ( $winner2 && $rowRaffle->couponcodeid2 ) {
					$message = sprintf ( JText::_('AUP_EMAILNOTIFICATION_MSG_COUPONCODE'), $SiteName, $this->_get_Coupon($rowRaffle->couponcodeid2) );
					uddeIMAPI::sendNewMessage( intval($params->get('fromIdUddeim')), intval($winner2), $message );
					$message = "";
				}
				if ( $winner3 && $rowRaffle->couponcodeid3 ) {
					$message = sprintf ( JText::_('AUP_EMAILNOTIFICATION_MSG_COUPONCODE'), $SiteName, $this->_get_Coupon($rowRaffle->couponcodeid3) );
					uddeIMAPI::sendNewMessage( intval($params->get('fromIdUddeim')), intval($winner3), $message );
					$message = "";
				}					
			}			
		}
		elseif ( $rowRaffle->rafflesystem=='2' ) 
		{
			// e-mail with a download link as a price
			if ( $winner1 && $rowRaffle->link2download1 ) {
				$this->sendnotificationDownload ( $winner1_Referreid, $rowRaffle->link2download1 );
			}
			if ( $winner2 && $rowRaffle->link2download2 ) {
				$this->sendnotificationDownload ( $winner2_Referreid, $rowRaffle->link2download2 );
			}
			if ( $winner3 && $rowRaffle->link2download3 ) {
				$this->sendnotificationDownload ( $winner3_Referreid, $rowRaffle->link2download3 );
			}		
		}		
		else
		{
			// is points ...
			if ( $winner1 ) {
				AlphaUserPointsHelper::newpoints ( 'sysplgaup_raffle', $winner1_Referreid, '', $rowRaffle->description, $rowRaffle->pointstoearn1 );
			}
			if ( $winner2 ) {			
				AlphaUserPointsHelper::newpoints ( 'sysplgaup_raffle', $winner2_Referreid, '', $rowRaffle->description, $rowRaffle->pointstoearn2 );
			}
			if ( $winner3 ) {							
				AlphaUserPointsHelper::newpoints ( 'sysplgaup_raffle', $winner3_Referreid, '', $rowRaffle->description, $rowRaffle->pointstoearn3 );
			}
			
		}
		
		$redirecturl = "index.php?option=com_alphauserpoints&task=raffle";
		$app->redirect( $redirecturl );
	
	}
		
	function sendnotification4couponcode ( $referrerid, $couponcode ) 
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
			$subject = JText::_('AUP_EMAILNOTIFICATION_SUBJECT_COUPONCODE');
			$message = sprintf ( JText::_('AUP_EMAILNOTIFICATION_MSG_COUPONCODE'), $SiteName, $couponcode );
			JUtility::sendMail( $MailFrom, $FromName, $email, $subject, $message );
		}
		
	}
	
	function sendnotificationDownload ( $referrerid, $linkToDownload )
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
			$subject = JText::_('AUP_EMAILNOTIFICATION_SUBJECT_MAIL_WITH_DOWNLOAD');
			$message = sprintf ( JText::_('AUP_EMAILNOTIFICATION_MSG_MAIL_WITH_DOWNLOAD'), $SiteName, $linkToDownload );
			JUtility::sendMail( $MailFrom, $FromName, $email, $subject, $message );
		}		
	}
	
	function getUserInfo ( $referrerid='' ) 
	{	
		if ( !$referrerid ) return;
	
		$db	   =& JFactory::getDBO();
		
		$query = "SELECT a.*, u.* FROM #__alpha_userpoints AS a, #__users AS u WHERE a.referreid='$referrerid' AND a.userid=u.id";
		$db->setQuery( $query );
		$userinfo = $db->loadObjectList();
	
		return @$userinfo[0];	
	}
	
	function _export_users_registration()
	{
		$raffleid	   = JRequest::getVar('id', 0, 'get', 'int');
		
		$db	   =& JFactory::getDBO();
		/*
		$query = "SELECT ari.userid AS uid, u.name, u.username " .
				 "FROM #__alpha_userpoints_raffle_inscriptions AS ari, #__users AS u " .
				 "GROUP BY ari.userid " .
				 "WHERE ari.raffleid=" . $raffleid . " AND ari.userid=u.id";
		*/
		$query = "SELECT ari.userid AS uid, u.name, u.username " .
				 "FROM #__alpha_userpoints_raffle_inscriptions AS ari " .				 
				 "LEFT JOIN #__users AS u ON ari.userid=u.id " .
				 "WHERE ari.raffleid='" . $raffleid . "' " .
				 "GROUP BY ari.userid "
				 ;				 
		$db->setQuery( $query );
		$userslist = $db->loadObjectList();
		return @$userslist;
	}
	
	function _get_Coupon( $idcoupon )
	{
		$db	   =& JFactory::getDBO();
		$query = "SELECT couponcode " .
				 "FROM #__alpha_userpoints_coupons " .
				 "WHERE id='" . $idcoupon . "'";		
		$db->setQuery( $query );
		$couponcode = $db->loadResult();
		return $couponcode;	
	}
	
}
?>