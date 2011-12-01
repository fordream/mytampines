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

jimport( 'joomla.application.component.model' );
require_once( JPATH_ADMINISTRATOR . DS ."components". DS ."com_enmasse". DS ."helpers". DS ."EnmasseHelper.class.php");

class EnmasseModelOrderItem extends JModel
{
	 var $_total = null;
	  var $_pagination = null;
	  function __construct()
	  {
	        parent::__construct();
	 
	        global $mainframe, $option;
	 
	        // define values need to pagination
	        $limit = $mainframe->getUserStateFromRequest('global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
	        $limitstart = JRequest::getVar('limitstart', 0, '', 'int');
	        $limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
	        $this->setState('limit', $limit);
	        $this->setState('limitstart', $limitstart);
	        
	  }
	 
	  
	   function getTotal()
	   {
	   	 global $mainframe, $option;
	   	 $status = '';
	   	 $pdtId = '';
	   	 $filter = $mainframe->getUserStateFromRequest( $option.'filter', 'filter', '', 'array' );
	   	 if($filter != null && count($filter)!=0)
	   	 {
	   	 	
		    $status = 'Delivered';
		   	if(isset($filter['deal_id']) && $filter['deal_id']!=null)
		   	 	$pdtId = $filter['deal_id'];
	   	 }
	        // Load total records
	        if($pdtId == '')
	        {$this->_total=0;}
	        else if (empty($this->_total)) {
	            $query =  "	SELECT
						* 
					FROM 
						#__enmasse_order_item 
					WHERE";
				if($status!=null)						
					$query .= "	status = '".$status."' AND";
				$query .= "		pdt_id = $pdtId";
			
	            $this->_total = $this->_getListCount($query);    
	        }
	        return $this->_total;
	  }
	   function getPagination()
	  {
	        //create pagination
	        if (empty($this->_pagination)) {
	            jimport('joomla.html.pagination');
	            $this->_pagination = new JPagination($this->getTotal(), $this->getState('limitstart'), $this->getState('limit') );
	        }
	        return $this->_pagination;
	  }
	
	function getById($id)
	{
		$db =& JFactory::getDBO();
		$query = "	SELECT
						* 
					FROM 
						#__enmasse_order_item 
					WHERE
	              		id = $id
	              ";
		$db->setQuery( $query );
		$orderItem = $db->loadObject();

		if ($this->_db->getErrorNum()) {
			JError::raiseError( 500, $this->_db->stderr() );
			return false;
		}
		return $orderItem;
	}
	
	function listByOrderId($orderId)
	{
		$db =& JFactory::getDBO();
		$query = "	SELECT
						* 
					FROM 
						#__enmasse_order_item 
					WHERE
	              		order_id = $orderId
	              ";
		$db->setQuery( $query );
		$orderItemList = $db->loadObjectList();

		if ($this->_db->getErrorNum()) {
			JError::raiseError( 500, $this->_db->stderr() );
			return false;
		}
		return $orderItemList;
	}

	function listByPdtIdAndStatus($pdtId, $status=null)
	{
		$db =& JFactory::getDBO();
		$query = "	SELECT
						* 
					FROM 
						#__enmasse_order_item 
					WHERE";
		if($status!=null)						
			$query .= "	status = '".$status."' AND";
		$query .= "		pdt_id = $pdtId";
		$db->setQuery( $query );
		$orderItemList = $this->_getList($query, $this->getState('limitstart'), $this->getState('limit'));
		
		if ($this->_db->getErrorNum()) {
			JError::raiseError( 500, $this->_db->stderr() );
			return false;
		}
		return $orderItemList;
	}
	
	function updateStatus($id,$value)
	{
		$db =& JFactory::getDBO();
		$query = '	UPDATE 
						#__enmasse_order_item 
					SET 
						status ="'.$value.'", 
						updated_at = "'. DatetimeWrapper::getDatetimeOfNow() .'" 
					WHERE 
						id ='.$id;
		$db->setQuery( $query );
		$db-> query();
		
		if ($this->_db->getErrorNum()) {
			JError::raiseError( 500, $this->_db->stderr() );
			return false;
		}
		return true;
	}
}
?>