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
require_once( JPATH_ADMINISTRATOR . DS ."components". DS ."com_enmasse".DS."helpers". DS ."EnmasseHelper.class.php");
require_once( JPATH_ADMINISTRATOR . DS ."components". DS ."com_enmasse".DS."helpers". DS ."DatetimeWrapper.class.php");
class EnmasseModelPayGty extends JModel
{
	function listAll()
	{
		$db =& JFactory::getDBO();
		$query = "SELECT * FROM #__enmasse_pay_gty";
		$db->setQuery( $query );
		$rows = $db->loadObjectList();

		if ($this->_db->getErrorNum()) {
			JError::raiseError( 500, $this->_db->stderr() );
			return false;
		}
		
		return $rows;
	}

	function countAll()
	{
		$db =& JFactory::getDBO();
		$query = "SELECT COUNT(*) FROM #__enmasse_pay_gty";
		$db->setQuery($query);
		$count = $db->loadResult();
		return $count;
	}
	  
	function getById($id)
	{
		$row =& JTable::getInstance('payGty', 'Table');
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
		if($data['class_name']!="cash" && $data['class_name']!="point")
		{
			$attribute_config = array();
			$count = 1;
			$attributes = '';
			foreach($data['attribute_name'] as $attribute_name)
			{
				if($attribute_name!="")
				{
					if($count==1)
					{
						$attributes .= $attribute_name;
					}
					else
					{
						$attributes .= "," . $attribute_name;
					}
					$attribute_config[$attribute_name] = $data['attribute_value'][$count];
					$count ++;
				}
			}
			$data['attributes'] = $attributes;
			$data['attribute_config'] = json_encode($attribute_config);
		}
		else
		{
			$data['attribute_config'] = json_encode($data['attribute_config']);
		}		
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

	function delete()
	{		
		$row =& $this->getTable();
		$cids = JRequest::getVar('cid', array(0), 'post', 'array');
		foreach($cids as $cid) {
			if (!$row->delete( $cid )) {
				$this->setError( $row->getErrorMsg() );
				return false;
			}
		}
		return true;
	}

	function storeGtyConfig($id, $attribute_config)
	{
		$obj->id = $id;
		$obj->attribute_config = json_encode($attribute_config);
		
		$row =& JTable::getInstance('payGty', 'Table');
		if (!$row->bind($obj))
		{
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		$row->updated_at = DatetimeWrapper::getDatetimeOfNow();

		if (!$row->store())
		{
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		return true;
	}

}
?>