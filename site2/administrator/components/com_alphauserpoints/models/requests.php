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

class alphauserpointsModelRequests extends Jmodel {

	function __construct(){
		parent::__construct();
	}
	
	
	function _load_currentrequestschangelevel() {
	
		$db =& JFactory::getDBO();
		
		$query = "SELECT * FROM #__alpha_userpoints_requests WHERE `checkedadmin`='0' ORDER BY requestdate ASC";
		$total  = @$this->_getListCount($query);
		$result = $this->_getList($query, 0, 10);
		
		return array($result, $total);
	}
	

	function _rejectlevel() {
		
		$db			    =& JFactory::getDBO();
		
		$id = JRequest::getVar( 'id', '0', 'get', 'int' );		
		if (!$id) return;
		
		$row =& JTable::getInstance('userspointsrequests');
		$row->load( intval($id) );
		
		$row->checkedadmin = 1;
		$db->updateObject( '#__alpha_userpoints_requests', $row, 'id' );
		
	}
	
	function _acceptlevel() {
		$app = JFactory::getApplication();
		
		$db			    =& JFactory::getDBO();
		$acl			=& JFactory::getACL();
		
		$id = JRequest::getVar( 'id', '0', 'get', 'int' );		
		if (!$id) return;
		
		$row =& JTable::getInstance('userspointsrequests');
		$row->load( intval($id) );
		
		$row->checkedadmin = 1;
		$row->response = 1;
		$db->updateObject( '#__alpha_userpoints_requests', $row, 'id' );
		
 		// Create a new JUser object
		$user = new JUser(JRequest::getVar( 'uid', 0, 'get', 'int'));

		$assignlevel= JRequest::getVar( 'assignlevel', '0', 'get', 'int' );
			
		if ( $assignlevel ) $post['gid'] = $assignlevel;
		
		if (!$user->bind($post))
		{
			$app->enqueueMessage(JText::_('CANNOT SAVE THE USER INFORMATION'), 'message');
			$app->enqueueMessage($user->getError(), 'error');
			return;
		}
		
		if (!$user->save()) {
			$app->enqueueMessage(JText::_('CANNOT SAVE THE USER INFORMATION'), 'message');
			$app->enqueueMessage($user->getError(), 'error');
			return;
		}
		
		$app->enqueueMessage( JText::_('AUP_REQUESTACCEPTED' ) .  " : " . JText::_('AUP_LEVELMODIFIEDSUCCESSFULLY' ) );
		
	}
	
	function _rulechangelevelactivate()
	{
		$db	 	=& JFactory::getDBO();
		
		$query 	= "SELECT COUNT(id) FROM #__alpha_userpoints_rules WHERE (plugin_function='sysplgaup_becomeauthor' OR plugin_function='sysplgaup_becomeeditor' OR plugin_function='sysplgaup_becomepublisher') AND published='1'";
		$db->setQuery( $query );
		$result = $db->loadResult();
		
		return $result;
	}
	
}
?>