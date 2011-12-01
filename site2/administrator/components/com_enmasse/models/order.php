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
require_once( JPATH_ADMINISTRATOR . DS ."components". DS ."com_enmasse".DS."helpers". DS ."DatetimeWrapper.class.php");
require_once( JPATH_ADMINISTRATOR . DS ."components". DS ."com_enmasse". DS ."helpers". DS ."EnmasseHelper.class.php");

class EnmasseModelOrder extends JModel
{
      var $_total = null;
	  var $_pagination = null;
	  function __construct()
	  {
	        parent::__construct();
	 
	        global $mainframe, $option;
            
            $version = new JVersion;
            $joomla = $version->getShortVersion();
            if(substr($joomla,0,3) == '1.6'){
        	   $mainframe = JFactory::getApplication();
            }
	 
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
	  	$dealName = '';
	  		
	  	$filter = $mainframe->getUserStateFromRequest( $option.'filter', 'filter', '', 'array' );
	  	if($filter != null)
	  	{
	  		if (isset($filter['status']) && $filter['status']!=null)
	  		$status = $filter['status'];
	  		if (isset($filter['deal_name']) && $filter['deal_name']!=null )
	  		$dealName = $filter['deal_name'];
	  	}
	  	// Load total records
	  	if (empty($this->_total)) {
	  		$query =  "	SELECT
						o.*,
						oi.description as deal_name,
						oi.qty as qty
					FROM 
						#__enmasse_order AS o,
						#__enmasse_order_item AS oi 
					WHERE 
						o.id = oi.order_id ";
	  		if(!empty($status))
	  			$query .="	AND o.status like '$status'";
	  		if(!empty($dealName) && $dealName !='')
	  		{
	  			$dealName = EnmasseHelper::escapeSqlLikeSpecialChar(JFactory::getDbo()->getEscaped($dealName));
	  			$query .="	AND oi.description like '%$dealName%'";
	  		}
	  		
	  			
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
	
	
	function search($status,$dealName, $orderBy=null, $orderType=null)
	{
		$crit = array();
		$db =& JFactory::getDBO();
		$query = "	SELECT 
						o.*,
						oi.description as deal_name,
						oi.qty as qty
					FROM 
						#__enmasse_order AS o,
						#__enmasse_order_item AS oi 
					WHERE 
						o.id = oi.order_id ";
		if(!empty($status))
			$query .="	AND o.status like '$status'";
		if(!empty($dealName))
		{
			$dealName = EnmasseHelper::escapeSqlLikeSpecialChar($db->getEscaped($dealName));
			$query .="	AND oi.description like '%$dealName%'";
		}
		if(!empty($orderBy))
			$query .="	ORDER BY $orderBy ";
		if(!empty($orderBy) && !empty($orderType))
			$query .="	$orderType ";		

		$db->setQuery( $query );
		$rows = $this->_getList($query, $this->getState('limitstart'), $this->getState('limit'));

		if ($this->_db->getErrorNum()) {
			JError::raiseError( 500, $this->_db->stderr() );
			return false;
		}
		
		return $rows;
	}

	function getById($id)
	{
		$order_row =& JTable::getInstance('order', 'Table');
		$order_row->load($id);
		return $order_row;
	}

	function updateStatus($id,$value)
	{
		$db =& JFactory::getDBO();
		$query = 'UPDATE #__enmasse_order SET status ="'.$value.'", updated_at = "'. DatetimeWrapper::getDatetimeOfNow() .'" where id ='.$id;
		$db->setQuery( $query );
		$db->query();
		
		if ($this->_db->getErrorNum()) {
			JError::raiseError( 500, $this->_db->stderr() );
			return false;
		}
		return true;
	}
	/* AMol start*/
	function updateAmountRefunded($id,$value)
	{
		$db =& JFactory::getDBO();
		$query = 'UPDATE #__enmasse_order SET refunded_amount ='.$value.', status="Refunded", updated_at = "'. DatetimeWrapper::getDatetimeOfNow() .'" where id ='.$id;
		$db->setQuery( $query );
		$db->query();
		
		if ($this->_db->getErrorNum()) {
			JError::raiseError( 500, $this->_db->stderr() );
			return false;
		}
		return true;
	}
	/* AMol End*/
	
	function store($data)
	{
		$row =& JTable::getInstance('order', 'Table');
		if (!$row->bind($data))
		{
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
}
?>