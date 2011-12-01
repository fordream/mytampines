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

class EnmasseModelCoupon extends JModel
{

	function getCouponBgUrl()
	{
		$db =& JFactory::getDBO();
		$query = "SELECT coupon_bg_url FROM #__enmasse_setting LIMIT 1";
		$db->setQuery( $query );
		$row = $db->loadObject();

		if ($this->_db->getErrorNum()) {
			JError::raiseError( 500, $this->_db->stderr() );
			return false;
		}
		
		return $row->coupon_bg_url;
	}

	function updateCouponBgUrl($url)
	{
		$db =& JFactory::getDBO();
		$query = "UPDATE #__enmasse_setting SET coupon_bg_url = '".$url."'";
		$db -> setQuery($query);
		$db -> query();
		
		if ($this->_db->getErrorNum()) {
			JError::raiseError( 500, $this->_db->stderr() );
			return false;
		}
		
        return true;
	}

}
?>