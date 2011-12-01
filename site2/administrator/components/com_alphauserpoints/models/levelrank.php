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

class alphauserpointsModelLevelrank extends Jmodel {

	function __construct(){
		parent::__construct();
	}
	
	function _load_levelrank() {
		$app = JFactory::getApplication();
		
		$db			    =& JFactory::getDBO();
				
		$total 			= 0;		
		
		$filter_order		= $app->getUserStateFromRequest( 'com_alphauserpoints.filter_order_rank',					'filter_order_rank',		'ordering',	'cmd' );
		$filter_order_Dir	= $app->getUserStateFromRequest( 'com_alphauserpoints.filter_order_Dir_rank',				'filter_order_Dir_rank',	'ASC',	   'word' );
		
		// Get the pagination request variables
		$limit = $app->getUserStateFromRequest('com_alphauserpoints.limit', 'limit', $app->getCfg('list_limit'), 'int');
		$limitstart = JRequest::getVar('limitstart', 0, '', 'int');
		// In case limit has been changed, adjust limitstart accordingly
		$limitstart = ( $limit != 0 ? (floor( $limitstart / $limit ) * $limit) : 0);		
		
		if ($filter_order) {
			$orderby 	= ' ORDER BY typerank ASC,'. $filter_order .' '. $filter_order_Dir .', levelpoints DESC';
		} else {
			$orderby = ' ORDER BY typerank ASC, ordering ASC, levelpoints DESC';
		}

		$query = "SELECT *, '' AS numrank, '' AS nummedals FROM #__alpha_userpoints_levelrank "
				. $orderby ;
		$total = @$this->_getListCount($query);
		$results = $this->_getList($query, $limitstart, $limit);
		
		for ($i=0, $n=count( $results ); $i < $n; $i++)			
		{
			$row   = $results[$i];
			$query = "SELECT COUNT(*) FROM #__alpha_userpoints "
					. "\nWHERE levelrank='".$row->id."'";
			$db->setQuery($query);
			$row->numrank = $db->loadResult();
			
			$query = "SELECT COUNT(*) FROM #__alpha_userpoints_medals "
					. "\nWHERE medal='".$row->id."'";
			$db->setQuery($query);
			$row->nummedals = $db->loadResult();
			
			if ( $row->ruleid )
			{
				$query = "SELECT rule_name FROM #__alpha_userpoints_rules "
						. "\nWHERE id='".$row->ruleid."'";
				$db->setQuery($query);
				$row->rulename = JText::_( $db->loadResult() );
			} else $row->rulename = "- " . JText::_( 'AUP_ALL' ) . " -";
		
		}
		
		// table ordering
		$lists['order_Dir_rank'] = $filter_order_Dir;
		$lists['order_rank']		= $filter_order;
		
		// choice folder for upload icons or large image medals/ranks
		$options[] = JHTML::_('select.option','icons', JText::_( 'AUP_ICON' ) );
		$options[] = JHTML::_('select.option','large',  JText::_( 'AUP_IMAGE' ) );
		$lists['folder'] = JHTML::_('select.radiolist', $options, 'folder', 'class="inputbox" size="1"', 'value', 'text', 'icons' );
		
		return array($results, $total, $limit, $limitstart, $lists);
	
	}
	
	function _edit_levelrank() {
	
		$db     =& JFactory::getDBO();

		$cid 	= JRequest::getVar('cid', array(0));
		$option = JRequest::getVar('option');
		
		if (!is_array( $cid )) {
			$cid = array(0);
		}

		$row =& JTable::getInstance('levelrank');
		$row->load( $cid[0] );
		
		$lists = array();
		
		$oplistType[] = JHTML::_('select.option', '0', JText::_( 'AUP_RANK' ) );	
		$oplistType[] = JHTML::_('select.option', '1', JText::_( 'AUP_MEDAL' ) );	
		$lists['typerank'] = JHTML::_('select.genericlist', $oplistType, 'typerank', 'class="inputbox" size="1"', 'value', 'text', $row->typerank );
		
		$query = "SELECT id, rule_name FROM #__alpha_userpoints_rules WHERE published='1'";
		$db->setQuery( $query );
		$results = $db->loadObjectList();
		
		$oplistRules[] = JHTML::_('select.option',  '0', '- '. JText::_( 'AUP_ALL' ) .' -' );
		foreach ( $results as $result ) {			
			$oplistRules[] = JHTML::_('select.option', $result->id, JText::_( $result->rule_name ) );			
		}
		$lists['rules'] = JHTML::_('select.genericlist', $oplistRules, 'ruleid', 'class="inputbox" size="1"', 'value', 'text', $row->ruleid );
		
		// choice folder for upload icons or large image medals/ranks
		$options[] = JHTML::_('select.option','icons',  JText::_( 'AUP_ICON' ) );
		$options[] = JHTML::_('select.option','large',  JText::_( 'AUP_IMAGE' ) );
		$lists['folder'] = JHTML::_('select.radiolist', $options, 'folder', 'class="inputbox" size="1"', 'value', 'text', 'icons' );
				
		return array($row, $lists);
	
	}
	
	
	function _delete_levelrank() {
		$app = JFactory::getApplication();

		// initialize variables
		$db			=& JFactory::getDBO();
		$cid		= JRequest::getVar('cid', array(), 'post', 'array');
		$msgType	= '';
		
		JArrayHelper::toInteger($cid);
		
		if (count($cid)) {		

			$query = "DELETE FROM #__alpha_userpoints_levelrank"
					. "\n WHERE (`id` = " . implode(' OR `id` = ', $cid) . ")"
					;
			$db->setQuery($query);
			
			if (!$db->query()) {
				$msg = $db->getErrorMsg();
				$msgType = 'error';
			} else $msg = JText::_('AUP_SUCCESSFULLYDELETED');
			
			// remove user medals
			$query = "DELETE FROM #__alpha_userpoints_medals"
					. "\n WHERE (`medal` = " . implode(' OR `id` = ', $cid) . ")"
					;
			$db->setQuery($query);
			$db->query();
			
			// change level/rank user if necessary
			$query = "UPDATE #__alpha_userpoints SET levelrank='0'"
					. "\n WHERE (`levelrank` = " . implode(' OR `levelrank` = ', $cid) . ")"
					;
			$db->setQuery($query);
			$db->query();			

		}

		$app->redirect('index.php?option=com_alphauserpoints&task=levelrank', $msg, $msgType);
		
	}
	
	function _save_levelrank() {
		$app = JFactory::getApplication();

		// initialize variables
		$db =& JFactory::getDBO();
		$post	= JRequest::get( 'post' );
		$row =& JTable::getInstance('levelrank');
		
		if (!$row->bind( $post )) {
			echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
			exit();
		}
		
		if (!$row->store()) {
			echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
			exit();
		}

		$msg = JText::_( 'AUP_DETAILSSAVED' );
		//$app->redirect( 'index.php?option=com_alphauserpoints&task=levelrank', $msg );
		
		// launch recalculation for all users
		$app->redirect( 'index.php?option=com_alphauserpoints&task=recalculate', $msg );
	}
	
	
	function _load_detailrank() {
		$app = JFactory::getApplication();
		
		$db			    =& JFactory::getDBO();				
		$total 			= 0;
		
		// Get the pagination request variables
		$limit = $app->getUserStateFromRequest('com_alphauserpoints.limit', 'limit', $app->getCfg('list_limit'), 'int');
		$limitstart = JRequest::getVar('limitstart', 0, '', 'int');
		// In case limit has been changed, adjust limitstart accordingly
		$limitstart = ( $limit != 0 ? (floor( $limitstart / $limit ) * $limit) : 0);
		
		$cid  		= JRequest::getVar('cid', 0, '', 'int');
		$typerank 	= JRequest::getVar('typerank', 0, '', 'int');
		
		switch ( $typerank ) 
		{
			case '1' :	
				$query = "SELECT m.medaldate AS dateawarded, m.reason , u.name, u.username, lv.id AS cid, lv.typerank, lv.icon, lv.rank FROM #__alpha_userpoints_medals AS m"
						. "\n LEFT JOIN #__alpha_userpoints_levelrank AS lv ON m.medal=lv.id"
						. "\n LEFT JOIN #__alpha_userpoints AS aup ON m.rid=aup.id"
						. "\n LEFT JOIN #__users AS u ON aup.userid=u.id"
						. "\n WHERE m.medal='".$cid."'"
						. "\n ORDER BY m.medaldate DESC";
				$total = @$this->_getListCount($query);
				$results = $this->_getList($query, $limitstart, $limit);
				break;				
				
			default  :			
				$query = "SELECT aup.leveldate AS dateawarded, '' AS reason, u.name, u.username, lv.id AS cid, lv.typerank, lv.icon, lv.rank FROM #__alpha_userpoints AS aup"	
						. "\n LEFT JOIN #__alpha_userpoints_levelrank AS lv ON aup.levelrank=lv.id"
						. "\n LEFT JOIN #__users AS u ON aup.userid=u.id"
						. "\n WHERE aup.levelrank='".$cid."'"
						. "\nORDER BY aup.leveldate DESC";
				$total = @$this->_getListCount($query);
				$results = $this->_getList($query, $limitstart, $limit);

		}
		
		return array($results, $total, $limit, $limitstart);
	
	}
	
	function orderItem($item, $movement)	
	{	
		$db			=& JFactory::getDBO();
		$error = "";
		
		$row = & JTable::getInstance('levelrank');		
		$row->load( $item );

		if (!$row->move( $movement, 'typerank = ' . $row->typerank  )) {
			$error = $row->getError();
			if ( $error ) {
				JError::raiseNotice(0, $error );
				return false;
			}
		}

		return true;
	}

	
	function setOrder($items)
	{
		
		$db			=& JFactory::getDBO();
		$total		= count( $items );
		$row = & JTable::getInstance('levelrank');		
		$groupings	= array();

		$order		= JRequest::getVar( 'order', array(), 'post', 'array' );
		JArrayHelper::toInteger($order);

		// update ordering values
		for( $i=0; $i < $total; $i++ ) {
			$row->load( $items[$i] );
			// track parents
			$groupings[] = $row->typerank;
			if ($row->ordering != $order[$i]) {
				$row->ordering = $order[$i];
				if (!$row->store()) {
					$error = $row->getError();
					JError::raiseNotice(0, $error );

					return false;
				}
			} 
		} 

		// execute updateOrder for each typerank group
		$groupings = array_unique( $groupings );
		foreach ($groupings as $group){
			$row->reorder('typerank = '.(int) $group );
		}

		return true;
	}

	
}
?>