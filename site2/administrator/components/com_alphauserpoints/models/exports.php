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

class alphauserpointsModelExports extends Jmodel {

	function __construct(){
		parent::__construct();
	}
	

	function _export_most_active_users() {
		
		$db =& JFactory::getDBO();	
		$query = "SELECT a.*, u.id AS iduser, u.name, u.username FROM #__alpha_userpoints AS a, #__users AS u WHERE u.id = a.userid ORDER BY a.points DESC, u.name ASC";
		$result = $this->_getList($query, 0, 50);
		
		return $result;
		
	}	
	
	function _export_emails() {
	
		$db =& JFactory::getDBO();	
		$query = "SELECT datareference FROM #__alpha_userpoints_details WHERE datareference!=''";
		$db->setQuery( $query );
		$result = $db->loadObjectList();
		
		return $result;
	}	

	
}
?>