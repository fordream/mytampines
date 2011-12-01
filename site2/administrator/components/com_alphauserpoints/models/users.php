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

class alphauserpointsModelUsers extends Jmodel {

	function __construct(){
		parent::__construct();
	}
	
	function _setmaxpoints() {
	
		$db			= & JFactory::getDBO();
		$maxpoints	= JRequest::getVar( 'setpointsperuser', 0, 'post', 'int' );

		$query = "UPDATE #__alpha_userpoints SET `max_points`='$maxpoints'";
		$db->setQuery($query);
		$db->query();
		
		return $maxpoints;
		
	}
	
	function _resetpoints() {
		
		$db	= & JFactory::getDBO();
				
		$jnow		=& JFactory::getDate();		
		$now		= $jnow->toMySQL();
			
		// main query
		$query = "UPDATE #__alpha_userpoints SET `points`='0', `last_update`='$now'";
		$db->setQuery( $query );
		$db->query();
		
		// main query
		$query = "DELETE FROM #__alpha_userpoints_details";
		$db->setQuery( $query );
		$db->query();
		
		return true;
		
	}
	
	function _recalculate_points () {
		// prepare recalculation
		$db	= & JFactory::getDBO();
		
		$jnow		=& JFactory::getDate();		
		$now		= $jnow->toMySQL();		
		
		// delete old points after expire date before recount 
		$query = "DELETE FROM #__alpha_userpoints_details WHERE `expire_date`!='0000-00-00 00:00:00' AND `expire_date`<='$now'";
		$db->setQuery( $query );
		$db->query();		
	
	}
	
	function _purge_expires () {
	
		$db	= & JFactory::getDBO();
		
		$jnow		=& JFactory::getDate();		
		$now		= $jnow->toMySQL();		
	
		// main query
		$query = "DELETE FROM #__alpha_userpoints_details WHERE `expire_date`!='0000-00-00 00:00:00' AND `expire_date`<='$now'";
		$db->setQuery( $query );
		$db->query();
		
		return true;
		
	}
	
	function _last_Activities() {

		$db			      =& JFactory::getDBO();
		
		$nullDate	= $db->getNullDate();
		$date =& JFactory::getDate();
		$now  = $date->toMySQL();
		
		$count		      = 10;
		
		// exclude specific users of this list
		$excludeuser = array();		
		$excludeusers = "";
		$query = "SELECT exclude_items FROM #__alpha_userpoints_rules WHERE `plugin_function`='sysplgaup_excludeusers' AND `published`='1'";
		$db->setQuery( $query );
		$result  = $db->loadResult();		
		if ( $result ) {		
			$excludeuser = explode( ",", $result);
			for ($i=0, $n=count($excludeuser); $i < $n; $i++) {		
				$excludeusers .= " AND aup.referreid!='" . trim($excludeuser[$i]) . "'";
			}
		}		
				
		$query = "SELECT a.insert_date, a.referreid, a.points AS last_points, u.username AS usrname, u.name AS uname, aup.userid AS userID, r.rule_name"
			   . " FROM #__alpha_userpoints_details AS a, #__alpha_userpoints AS aup, #__users AS u, #__alpha_userpoints_rules AS r"
			   . " WHERE aup.referreid=a.referreid AND aup.userid=u.id AND a.approved='1'"
			   . "  AND a.rule=r.id"
			   . "  AND (a.expire_date>='$now' OR a.expire_date='0000-00-00 00:00:00')"
			   . $excludeusers
			   . " ORDER BY a.insert_date DESC"		
		 	   ;
		$db->setQuery($query, 0, $count);
		$rows = $db->loadObjectList();
	
		return $rows;
	
	}
	
	
	function _load_activities() {
		
		$app = JFactory::getApplication();
		
		$db			    =& JFactory::getDBO();
		
		$nullDate	= $db->getNullDate();
		$date =& JFactory::getDate();
		$now  = $date->toMySQL();
		
		$total 			= 0;
		
		//$filter_state = $app->getUserStateFromRequest( 'com_alphaquotation'.'.filter_state', 'filter_state', '', 'word' );
		$filter_order		= $app->getUserStateFromRequest( "com_alphauserpoints.filter_order",		'filter_order',		'a.insert_date',	'cmd' );
		$filter_order_Dir	= $app->getUserStateFromRequest( "com_alphauserpoints.filter_order_Dir",	'filter_order_Dir',	'desc',			'word' );
		$search				= $app->getUserStateFromRequest( "com_alphauserpoints.search",			'search',			'',		  'string' );
		$filter_state = JRequest::getVar('filter_state', '', '', 'string');
		
		// Get the pagination request variables
		$limit = $app->getUserStateFromRequest('com_alphauserpoints.limit', 'limit', $app->getCfg('list_limit'), 'int');
		$limitstart = JRequest::getVar('limitstart', 0, '', 'int');
		// In case limit has been changed, adjust limitstart accordingly
		$limitstart = ( $limit != 0 ? (floor( $limitstart / $limit ) * $limit) : 0);
		
		if ( $filter_state!='' ) {
			$filter = " AND a.approved='".$filter_state."' ";						
		} else {
			$filter = " AND (a.approved='1' OR a.approved='0') ";		
		}
		
		$total 			= 0;
		
		$where = array();
		
		if ($search) {
			$where[] = 'LOWER(u.name) LIKE '. $db->Quote( '%'.$db->getEscaped( $search, true ).'%', false );
			$where[] = 'LOWER(u.username) LIKE '. $db->Quote( '%'.$db->getEscaped( $search, true ).'%', false );
			$where[] = 'LOWER(r.rule_name) LIKE '. $db->Quote( '%'.$db->getEscaped( $search, true ).'%', false );
			$where[] = 'LOWER(a.insert_date) LIKE '. $db->Quote( '%'.$db->getEscaped( $search, true ).'%', false );
		}
		$where 		= ( count( $where ) ? " AND (" . implode( ' OR ', $where ) .")" : "" );
		
		$orderby = " ORDER BY " . $filter_order . " " . $filter_order_Dir;
		
		$query = "SELECT a.id, a.insert_date, a.referreid, a.points AS last_points, a.approved, u.username AS usrname, u.name AS uname, aup.userid AS userID, r.rule_name"
			   . " FROM #__alpha_userpoints_details AS a, #__alpha_userpoints AS aup, #__users AS u, #__alpha_userpoints_rules AS r"
			   . " WHERE aup.referreid=a.referreid AND aup.userid=u.id"
			   . " AND a.rule=r.id"
			   . " AND (a.expire_date>='$now' OR a.expire_date='0000-00-00 00:00:00')"
			   . $filter . $where 
			   .$orderby
			   ;
				
		$total = @$this->_getListCount($query);
		$result = $this->_getList($query, $limitstart, $limit);
		
		$lists = array();		
		$options[] = JHTML::_('select.option', '', JText::_( 'AUP_ALL' ) );
		$options[] = JHTML::_('select.option', '1', JText::_( 'AUP_APPROVED' ) );
		$options[] = JHTML::_('select.option', '0', JText::_( 'AUP_UNAPPROVED' ) );
		$lists['filter_state'] = JHTML::_('select.genericlist', $options, 'filter_state', 'class="inputbox" size="1" onchange="document.adminForm.submit();"' ,'value', 'text', $filter_state );		

		// table ordering
		$lists['order_Dir']	= $filter_order_Dir;
		$lists['order']		= $filter_order;
		// search filter
		$lists['search']    = $search;

		return array($result, $total, $limit, $limitstart, $lists);
	
	}

}
?>