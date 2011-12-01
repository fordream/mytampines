<?php
/*------------------------------------------------------------------------
# En Masse - Social Buying Extension 2010
# ------------------------------------------------------------------------
# By Matamko.com
# Copyright (C) 2010 Matamko.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.matamko.com
# Technical Support:  Visit our forum at www.matamko.com
-------------------------------------------------------------------------*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();
jimport( 'joomla.application.component.model' );

class EnmasseModelTax extends JModel
{

	function listAll()
	{
		$db =& JFactory::getDBO();
		$query = "SELECT * FROM #__enmasse_tax";
		$db->setQuery( $query );
		$rows = $db->loadObjectList();
		
		if ($this->_db->getErrorNum()) {
			JError::raiseError( 500, $this->_db->stderr() );
			return false;
		}
		return $rows;
	}
	
	function listAllPublished()
	{
		$db =& JFactory::getDBO();
		$query = ' SELECT name as text FROM #__enmasse_tax WHERE published = 1';
		$db->setQuery($query);
		$tax= $db->loadObjectList();
		
		if ($this->_db->getErrorNum()) {
			JError::raiseError( 500, $this->_db->stderr() );
			return false;
		}
		return $tax;

	}

	function getById($id)
	{
		$row =& JTable::getInstance('tax', 'Table');
		$row->load($id);
		
		if ($this->_db->getErrorNum()) {
			JError::raiseError( 500, $this->_db->stderr() );
			return false;
		}
		return $row;
	}

	function store($data)
	{
		$row =& $this->getTable();

		if (!$row->bind($data)) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		
		if($row->id <= 0)
			$row ->created_at = DatetimeWrapper::getDatetimeOfNow();
		$row ->updated_at = DatetimeWrapper::getDatetimeOfNow();

		if (!$row->check()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		if (!$row->store()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		return true;
	}

	function deleteList($cids)
	{
		$row =& $this->getTable();
		foreach($cids as $cid) {
			if (!$row->delete( $cid )) {
				$this->setError( $row->getErrorMsg() );
				return false;
			}
		}
		return true;
	}
}
?>