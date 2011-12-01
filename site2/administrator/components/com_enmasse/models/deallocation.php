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
jimport( 'joomla.html.pagination' );
class EnmasseModelDealLocation extends JModel
{
    function getLocationByDealId($id)
	{
		$db 	=& JFactory::getDBO();
		$query = ' SELECT 
		              location_id
		           FROM 
		              #__enmasse_deal_location
		           WHERE 
		             deal_id = '.$id;
		$db->setQuery($query);
		$location = $db->loadResultArray();
		
		if ($this->_db->getErrorNum()) {
			JError::raiseError( 500, $this->_db->stderr() );
			return false;
		}
		return $location;
	}
   function getDealByLocationId($id)
	{
		$db 	=& JFactory::getDBO();
		$query = ' SELECT 
		              deal_id
		           FROM 
		              #__enmasse_deal_location
		           WHERE 
		             location_id = '.$id;
		$db->setQuery($query);
		$dealIdArr = $db->loadResultArray();
		
		if ($this->_db->getErrorNum()) {
			JError::raiseError( 500, $this->_db->stderr() );
			return false;
		}
		return $dealIdArr;
	}
	
	function store($dealId,$locationIdArr)
	{
		$currentLocations = EnmasseModelDealLocation::getLocationByDealId($dealId);
		
		if(count($currentLocations) == 0)
		{
			for($i=0 ; $i<count($locationIdArr); $i++)
			{
				EnmasseModelDealLocation::save($dealId,$locationIdArr[$i]);
			}
		}
		else
		{
			for($z=0 ; $z < count($currentLocations); $z++)
			{
				EnmasseModelDealLocation::delete($dealId,$currentLocations[$z]);
			}
		   for($i=0 ; $i<count($locationIdArr); $i++)
			{
				EnmasseModelDealLocation::save($dealId,$locationIdArr[$i]);
			}
			
			
		}
		
	}
	
	function delete($dealId,$locationId)
	{
		$db 	=& JFactory::getDBO();
		$query = " DELETE FROM
		              #__enmasse_deal_location
		           WHERE 
		            deal_id=$dealId AND location_id=$locationId ";
		$db->setQuery($query);
		$db->query();
	}
	
	function save($dealId,$locationId)
	{
		$db 	=& JFactory::getDBO();
		$query = " INSERT INTO 
		              #__enmasse_deal_location
		           VALUES 
		            ('null',$dealId,$locationId) ";
		$db->setQuery($query);
		$db->query();
	}
	function removeByLocation($locationId)
	{
		$db 	=& JFactory::getDBO();
		$query = " DELETE FROM
		              #__enmasse_deal_location
		           WHERE 
		             location_id=$locationId ";
		$db->setQuery($query);
		$db->query();
	}
	
}
?>