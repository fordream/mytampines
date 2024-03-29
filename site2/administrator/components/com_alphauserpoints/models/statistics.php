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
jimport( 'joomla.html.html');

class alphauserpointsModelStatistics extends Jmodel {

	function __construct(){
		parent::__construct();
	}
	
	function _needsync () {
	
		$db			    =& JFactory::getDBO();
		
		$this->_purge_old_users();
		
		$query = "SELECT count(*) FROM #__users";
		$db->setQuery( $query );
		$totalUSers = $db->loadResult();
		
		$query = "SELECT count(*) FROM #__alpha_userpoints";
		$db->setQuery( $query );
		$totalReferral = ( $db->loadResult() - 1 ); // remove the GUEST user in count
		
		if ( $totalUSers != $totalReferral ) {		
			return true;		
		} else return false;		
	
	}
	
	function _purge_old_users() {
	
		$db			= & JFactory::getDBO();
		// old users
		$query = "SELECT a.userid FROM #__alpha_userpoints AS a LEFT JOIN #__users AS u ON u.id = a.userid WHERE (((u.id) Is Null)) AND a.userid>0";
		$db->setQuery( $query );
		$oldUsers = $db->loadObjectList();
		
		if ( $oldUsers ) {		
			foreach ( $oldUsers as $oldUser ) {			
				// delete each old user not reliable in table #__users
				
				/*
				$query = "DELETE FROM #__alpha_userpoints WHERE userid='".$oldUser->userid."'";
				$db->setQuery($query);
				$db->query();
				*/				
				
				$query = "SELECT `id`, `referreid`, `referraluser` FROM #__alpha_userpoints WHERE `userid`='".$oldUser->userid."'";
				$db->setQuery( $query );
				$result = $db->loadObject();
				$referreid = $result->referreid;
				$referraluser = $result->referraluser;
		
				$query = "DELETE FROM #__alpha_userpoints WHERE `userid`='".$oldUser->userid."'";
				$db->setQuery( $query );
				$db->query();
				
				$query = "DELETE FROM #__alpha_userpoints_details WHERE `referreid`='".$referreid."'";
				$db->setQuery( $query );
				$db->query();
				
				$query = "DELETE FROM #__alpha_userpoints_medals WHERE `rid`='".$result->id."'";
				$db->setQuery( $query );
				$db->query();
				
				// if the user has been a referral user
				$query = "UPDATE #__alpha_userpoints SET referraluser='' WHERE referraluser='".$referreid."'";
				$db->setQuery($query);
				$db->query();
				
				// recount referrees for the referral user
				$query = "UPDATE #__alpha_userpoints SET referrees=referrees-1 WHERE referreid='".$referraluser."'";
				$db->setQuery($query);
				$db->query();
		
			}
		}
		
	}	
	
	
	function _load_users() {	

		$app = JFactory::getApplication();
		$option = JRequest::getVar('option');
		
		$db			    =& JFactory::getDBO();
		
		$filter_levelrank	= JRequest::getVar( 'filterlevelrank', 0, '', 'int' );
		$filter_order		= $app->getUserStateFromRequest( "$option.filter_order",		'filter_order',		'u.name',	'cmd' );
		$filter_order_Dir	= $app->getUserStateFromRequest( "$option.filter_order_Dir",	'filter_order_Dir',	'',			'word' );
		$search				= $app->getUserStateFromRequest( "$option.search",			'search',			'',		  'string' );
		$search				= JString::strtolower( $search );
		
		$orderby = " ORDER BY " . $filter_order . " " . $filter_order_Dir;
		
		$total 			= 0;
		
		// Get the pagination request variables
		$limit = $app->getUserStateFromRequest('com_alphauserpoints.limit', 'limit', $app->getCfg('list_limit'), 'int');
		$limitstart = JRequest::getVar('limitstart', 0, '', 'int');
		// In case limit has been changed, adjust limitstart accordingly
		$limitstart = ( $limit != 0 ? (floor( $limitstart / $limit ) * $limit) : 0);		
		
		$where = array();
		
		if ($filter_levelrank > 0) {
			$where[] = 'a.levelrank = ' . (int) $filter_levelrank;
		}
		
		if ($search) {
			$where[] = 'LOWER(u.name) LIKE '. $db->Quote( '%'.$db->getEscaped( $search, true ).'%', false );
			$where[] = 'LOWER(u.username) LIKE '. $db->Quote( '%'.$db->getEscaped( $search, true ).'%', false );
			$where[] = 'LOWER(a.referreid) LIKE '. $db->Quote( '%'.$db->getEscaped( $search, true ).'%', false );
		}
		$where 		= ( count( $where ) ? " AND (" . implode( ' OR ', $where ) .")" : "" );

		$query = "SELECT a.*, u.name, u.username FROM #__alpha_userpoints AS a, #__users AS u "
				. "WHERE u.id = a.userid " . $where . $orderby;
		$total = @$this->_getListCount($query);
		$result = $this->_getList($query, $limitstart, $limit);
		// table ordering
		$lists['order_Dir']	= $filter_order_Dir;
		$lists['order']		= $filter_order;
		// search filter
		$lists['search']    = $search;
		
		// get list level / rank
		$query = "SELECT id, rank FROM #__alpha_userpoints_levelrank WHERE typerank='0'";
		$db->setQuery( $query );
		$resultsrank = $db->loadObjectList();
		
		$javascript = 'onchange="document.adminForm.submit();"';
		if ( $resultsrank ) { 
			$oplistRank[] = JHTML::_('select.option',  '-1', '- '. JText::_( 'AUP_SELECT_A_RANK' ) .' -' );
			$oplistRank[] = JHTML::_('select.option',  '0', '- '. JText::_( 'AUP_ALL' ) .' -' );
			foreach ( $resultsrank as $resultrank ) {			
				$oplistRank[] = JHTML::_('select.option', $resultrank->id, JText::_( $resultrank->rank ) );			
			}		
			$lists['levelrank'] = JHTML::_('select.genericlist', $oplistRank, 'filterlevelrank', 'class="inputbox" size="1" '. $javascript, 'value', 'text', $filter_levelrank );
		} else $lists['levelrank'] = "";

		// Check if level/rank(s) exist
		$queryMedals = "SELECT count(*) FROM #__alpha_userpoints_levelrank WHERE typerank='0'";
		$db->setQuery( $queryMedals );
		$ranksexist = $db->loadResult();
	
		// Check if medal(s) exist
		$queryMedals = "SELECT count(*) FROM #__alpha_userpoints_levelrank WHERE typerank='1'";
		$db->setQuery( $queryMedals );
		$medalsexist = $db->loadResult();
		
		return array($result, $total, $limit, $limitstart, $lists, $ranksexist, $medalsexist);
	
	}
	
	function _edit_user() {
	
		$db     =& JFactory::getDBO();

		$cid 	= JRequest::getVar('cid', array(0));
		
		if (!is_array( $cid )) {
			$cid = array(0);
		}

		$lists = array();

		$row =& JTable::getInstance('userspoints');
		$row->load( $cid[0] );
		
		// get name and username
		$query = "SELECT name, username FROM #__users WHERE `id`='$row->userid'";
		$db->setQuery( $query );
		$result  = $db->loadObjectList();
		$row->name = $result[0]->name;
		$row->username = $result[0]->username;		
		
		// get list level / rank
		$query = "SELECT id, rank FROM #__alpha_userpoints_levelrank WHERE typerank='0'";
		$db->setQuery( $query );
		$results = $db->loadObjectList();
		
		$oplistRank[] = JHTML::_('select.option',  '0', '- '. JText::_( 'AUP_SELECT_A_RANK' ) .' -' );
		foreach ( $results as $result ) {			
			$oplistRank[] = JHTML::_('select.option', $result->id, JText::_( $result->rank ) );			
		}
		$listrank = JHTML::_('select.genericlist', $oplistRank, 'levelrank', 'class="inputbox" size="1"', 'value', 'text', $row->levelrank );
		
		// get list medals
		$queryMedals = "SELECT id, rank FROM #__alpha_userpoints_levelrank WHERE typerank='1'";
		$db->setQuery( $queryMedals );
		$results1 = $db->loadObjectList();
		$oplistRank1[] = JHTML::_('select.option',  '0', '- '. JText::_( 'AUP_SELECT_MEDAL' ) .' -' );
		foreach ( $results1 as $result1 ) {			
			$oplistRank1[] = JHTML::_('select.option', $result1->id, JText::_( $result1->rank ) );			
		}
		$listmedals = JHTML::_('select.genericlist', $oplistRank1, 'medal', 'class="inputbox" size="1"', 'value', 'text', '0' );
		
		
		// Check if medal(s) exist
		$queryMedals = "SELECT count(*) FROM #__alpha_userpoints_levelrank WHERE typerank='1'";
		$db->setQuery( $queryMedals );
		$medalsexist = $db->loadResult();
		
		$medals = "SELECT m.id, m.medaldate, m.reason, lv.rank, lv.description, lv.icon, lv.image "
				. "\nFROM #__alpha_userpoints_medals AS m, #__alpha_userpoints_levelrank AS lv "
				. "\nWHERE rid=".$row->id." AND m.medal=lv.id"
				. "\n ORDER BY m.medaldate DESC";
		$db->setQuery( $medals );
		$medalslistuser = $db->loadObjectList();
		
		return array($row, $listrank, $medalsexist, $medalslistuser, $listmedals);
	
	}
	
	function _save_user() {
		$app = JFactory::getApplication();

		// initialize variables
		$db 	=& JFactory::getDBO();
		$post	= JRequest::get( 'post' );
		$row 	=& JTable::getInstance('userspoints');
		
		// last update
		$jnow		=& JFactory::getDate();
		$now		= $jnow->toMySQL();
		
		$row->last_update = $now;
		
		if ( $post['oldrank'] != $post['levelrank'] ) $row->leveldate = $now;
		if ($post['levelrank'] == 0 ) $row->leveldate = '0000-00-00';		

		if (!$row->bind( $post )) {
			echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
			exit();
		}
		
		if (!$row->store()) {
			echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
			exit();
		}
		
		$referreid = $post['referreid'];
		$this->recalculateUser( $referreid );

		$msg = JText::_( 'AUP_GENERALSTATSSAVED' );
		$app->redirect( 'index.php?option=com_alphauserpoints&task=statistics', $msg );
	}
	
	function recalculateUser( $referrerid )
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
		AlphaUserPointsHelper::checkRankMedal ( $referrerid );
	
	}
	
	function _save_medaluser () {
	
		$app = JFactory::getApplication();
	
		// initialize variables
		$db =& JFactory::getDBO();
		$post	= JRequest::get( 'post' );
		$row =& JTable::getInstance('medals');
		
		// last update
		$jnow		=& JFactory::getDate();
		$now		= $jnow->toMySQL();
		
		$row->medaldate = $now;
		
		if ( $post['reason']=='' ) {
			$queryDescMedals = "SELECT description FROM #__alpha_userpoints_levelrank WHERE typerank='1' AND id='".$post['medal']."'";
			$db->setQuery( $queryDescMedals );
			$medaldescription = $db->loadResult();
			
			$post['reason'] = $medaldescription;
		}
		
		if (!$row->bind( $post )) {
			echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
			exit();
		}
		
		if (!$row->store()) {
			echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
			exit();
		}

		$msg = JText::_( 'AUP_GENERALSTATSSAVED' );
		$app->redirect( 'index.php?option=com_alphauserpoints&task=edituser&cid[]='.$post['rid'], $msg );
	
	}
	
	function _delete_medaluser() {
		$app = JFactory::getApplication();

		// initialize variables
		$db			=& JFactory::getDBO();
		$cid		= JRequest::getVar('cid', 0, 'get', 'int');
		$rid 		= JRequest::getVar('rid', 0, 'get', 'int');
		
		$msgType	= '';
		
		if ($cid) {		

			// remove user medals
			$query = "DELETE FROM #__alpha_userpoints_medals"
					. "\n WHERE `id`=$cid AND rid=$rid"
					;
			$db->setQuery($query);
			$db->query();
			
			if (!$db->query()) {
				$msg = $db->getErrorMsg();
				$msgType = 'error';
			} else $msg = JText::_('AUP_SUCCESSFULLYDELETED');

		}

		$app->redirect( 'index.php?option=com_alphauserpoints&task=edituser&cid[]='.$rid, $msg );
		
	}

	
	function _load_top10 () {
	
		$db =& JFactory::getDBO();
	
		$query = "SELECT a.*, u.name, u.username FROM #__alpha_userpoints AS a, #__users AS u WHERE u.id = a.userid ORDER BY a.points DESC, u.name ASC";
		$result = $this->_getList($query, 0, 10);
		
		return $result;
	
	}
	
	function _load_unapproved() {
	
		$db =& JFactory::getDBO();
		
		$query = "SELECT a.id AS cid, a.insert_date, a.referreid, a.points AS pendingapprovalpoints, r.rule_name, u.name , u.username"
			   . " FROM #__alpha_userpoints_details AS a, #__alpha_userpoints_rules AS r, #__alpha_userpoints AS aup, #__users AS u"
			   . " WHERE r.id=a.rule AND aup.referreid=a.referreid AND aup.userid=u.id AND a.approved='0' AND a.status='0'"
			   . " ORDER BY a.insert_date DESC";
		$total  = @$this->_getListCount($query);
		//$result = $this->_getList($query, 0, 10);
		$result = $this->_getList($query);
		return array($result, $total);
	}	
	
	function _pointsearned() {
	
		$date_start = JRequest::getVar( 'date_start', '', 'post', 'string' );
		$date_end   = JRequest::getVar( 'date_end', '', 'post', 'string' );
		$rule       = JRequest::getVar( 'rule', '0', 'post', 'int' );
		
		$where = "";
		if ( !$date_start || !$date_end ) {			
			$where = "";		
		} elseif ( $date_start>$date_end ) {
			$where = "";			
		} else {		
			$where .= " AND a.insert_date>='$date_start' AND a.insert_date<='$date_end'";
		}
		
		if ( $rule>0 ) $where .= " AND a.rule='$rule'";
	
		$db =& JFactory::getDBO();
		
		$query = "SELECT a.referreid, SUM(a.points) AS sumpoints, u.username AS username, u.name AS name"
			   . " FROM #__alpha_userpoints_details AS a, #__alpha_userpoints AS aup, #__users AS u"
			   . " WHERE aup.referreid=a.referreid AND aup.userid=u.id AND a.approved='1' AND a.status='1' AND a.points>=1"
			   . $where
			   . " GROUP BY a.referreid "
			   . " ORDER BY sumpoints DESC"
			   . " LIMIT 10"
			   ;

		$db->setQuery( $query );
		$result = $db->loadObjectList();

		return $result;
	
	}

	function _pointsspent() {
	
		$date_start = JRequest::getVar( 'date_start', '', 'post', 'string' );
		$date_end   = JRequest::getVar( 'date_end', '', 'post', 'string' );
		$rule       = JRequest::getVar( 'rule', '0', 'post', 'int' );
		
		$jnow		=& JFactory::getDate();
		$now		= $jnow->toMySQL();		

		
		$where = "";
		if ( !$date_start || !$date_end ) {			
			$where = "";		
		} elseif ( $date_start>$date_end ) {
			$where = "";			
		} else {		
			$where = " AND a.insert_date>='$date_start' AND a.insert_date<='$date_end'";
		}
		
		if ( $rule>0 ) $where .= " AND a.rule='$rule'";
	
		$db =& JFactory::getDBO();
		
		$query = "SELECT a.referreid, SUM(a.points) AS sumpoints, u.username AS username, u.name AS name"
			   . " FROM #__alpha_userpoints_details AS a, #__alpha_userpoints AS aup, #__users AS u"
			   . " WHERE aup.referreid=a.referreid AND aup.userid=u.id AND a.approved='1' AND a.status='1' AND a.points<0"
			   . $where
			   . " GROUP BY a.referreid "
			   . " ORDER BY sumpoints ASC"
			   . " LIMIT 10"
			   ;

		$db->setQuery( $query );
		$result = $db->loadObjectList();

		return $result;
	
	}
	
	function _getListRules($rule) {
	
		$db =& JFactory::getDBO();
		
		$query = "SELECT id, rule_name FROM #__alpha_userpoints_rules WHERE published='1'";
		$db->setQuery( $query );
		$results = $db->loadObjectList();
		
		$oplistRules[] = JHTML::_('select.option',  '0', '- '. JText::_( 'AUP_ALL' ) .' -' );
		foreach ( $results as $result ) {			
			$oplistRules[] = JHTML::_('select.option', $result->id, JText::_( $result->rule_name ) );			
		}
		$listRules = JHTML::_('select.genericlist', $oplistRules, 'rule', 'class="inputbox" size="1"', 'value', 'text', $rule );
				
		return $listRules;	
	
	}
	
	function _totalcurrentcommunitypoints() {
	
		$db =& JFactory::getDBO();
		
		$query = "SELECT SUM(a.points) AS totalpoints"
			   . " FROM #__alpha_userpoints_details AS a"
			   . " WHERE a.approved='1' AND a.status='1'"
			   ;
		$db->setQuery( $query );
		$result = $db->loadResult();

		return $result;
	
	}
	
	function _totalcommunitypointsearned() {
	
		$db =& JFactory::getDBO();
		
		$query = "SELECT SUM(a.points) AS totalpoints"
			   . " FROM #__alpha_userpoints_details AS a"
			   . " WHERE a.approved='1' AND a.status='1' AND a.points>=1"
			   ;
		$db->setQuery( $query );
		$result = $db->loadResult();

		return $result;
	
	}

	function _totalcommunitypointsspent() {
	
		$db =& JFactory::getDBO();
		
		$query = "SELECT SUM(a.points) AS totalpoints"
			   . " FROM #__alpha_userpoints_details AS a"
			   . " WHERE a.approved='1' AND a.status='1' AND a.points<0"
			   ;
		$db->setQuery( $query );
		$result = abs($db->loadResult());

		return $result;
	
	}
	
	function _get_num_users() {
	
		$db =& JFactory::getDBO();
		
		$query = "SELECT COUNT(id)"
			   . " FROM #__alpha_userpoints"
			   . " WHERE blocked='0'"
			   ;
		$db->setQuery( $query );
		$result = ($db->loadResult() - 1 ); // remove the guest user

		return $result;
	
	}
	
	function _average_points_earned_by_day() {
	
		$db =& JFactory::getDBO();
		
		$result = 0;
		
		$query = "SELECT MIN(a.insert_date) AS firstdate, MAX(a.insert_date) AS lastdate "
			   . " FROM #__alpha_userpoints_details AS a"
			   . " WHERE a.approved='1' AND a.status='1' AND a.insert_date!='0000-00-00 00:00:00'"
			   ;
		$db->setQuery( $query );
		$dates = $db->loadObject();
		
		if ( !$dates ) return $result;
		
		$numdays = floor( ( strtotime($dates->lastdate) - strtotime($dates->firstdate))/(60*60*24));
		$currentpointscommunityearned = $this->_totalcommunitypointsearned();

		$numusers = $this->_get_num_users();		
		
		if ( $numdays && $numusers ) $result = round((($currentpointscommunityearned / $numdays) / $numusers), 2 );
		
		return $result;
	
	}
	
	function _average_points_spent_by_day() {
	
		$db =& JFactory::getDBO();
		
		$result = 0;
		
		$query = "SELECT MIN(a.insert_date) AS firstdate, MAX(a.insert_date) AS lastdate "
			   . " FROM #__alpha_userpoints_details AS a"
			   . " WHERE a.approved='1' AND a.status='1' AND a.insert_date!='0000-00-00 00:00:00'"
			   ;
		$db->setQuery( $query );
		$dates = $db->loadObject();
		
		if ( !$dates ) return $result;
		
		$numdays = floor( ( strtotime($dates->lastdate) - strtotime($dates->firstdate))/(60*60*24));
		$currentpointscommunityspent = $this->_totalcommunitypointsspent();

		$numusers = $this->_get_num_users();
		
		if ( $numdays && $numusers ) $result = round((($currentpointscommunityspent / $numdays) / $numusers), 2 );
		
		return $result;
	
	}
	
	function _get_most_country( $limit ) {
	
		$db =& JFactory::getDBO();		
		
		$query = "SELECT country, COUNT(id) AS numusers"
			   . " FROM #__alpha_userpoints"
			  // . " WHERE blocked='0' AND country!='' AND userid > 0 AND referreid!='GUEST'"
			   . " WHERE blocked='0' AND userid > 0 AND referreid!='GUEST'"
			   . " GROUP BY country"
			   . " ORDER BY numusers DESC"
			   . " LIMIT $limit"
			   ;
		$db->setQuery( $query );
		$country = $db->loadObjectList();
		
		return $country;
	}
	
	function _get_ratio_gender_members() {
	
		$db =& JFactory::getDBO();		
		
		$query = "SELECT COUNT(id) AS nb, gender FROM #__alpha_userpoints WHERE userid > 0 AND referreid!='GUEST' GROUP BY gender";
		$db->setQuery( $query );
		$ratio_members = $db->loadObjectList();
		
		return $ratio_members;	
	}
	
	function _get_inactive_members() {
	
		$inactive_members = 0;
		$num_days = 0;
		
		require_once (JPATH_SITE.DS.'components'.DS.'com_alphauserpoints'.DS.'helper.php');
		
		$inactive_user_rule = AlphaUserPointsHelper::checkRuleEnabled( 'sysplgaup_inactiveuser' );
		
		if ( $inactive_user_rule && $inactive_user_rule[0]->published ){
		
			$num_days = intval($inactive_user_rule[0]->content_items);
		
			$db =& JFactory::getDBO();
			
			$query = "SELECT COUNT(id) FROM #__alpha_userpoints WHERE userid > 0 AND referreid!='GUEST' AND (TO_DAYS(NOW()) - TO_DAYS(last_update)) > ". intval($inactive_user_rule[0]->content_items);
			$db->setQuery( $query );
			$inactive_members = $db->loadResult();
			
		}
		return array($inactive_members, $num_days);
	
	}

}
?>