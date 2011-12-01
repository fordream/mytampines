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

class TableMerchant extends JTable
{
	var $id = null;
	var $name = null;
	var $user_name = null;
	var $sales_person_id = null;
	var $telephone = null;
	var $fax = null;
//	var $password = null;
	var $web_url = null;
	var $address = null;
	var $postal_code = null;
	var $logo_url = null;
	var $location_id = null;
	var $google_map_lat = null;
	var $google_map_long = null;
	var $google_map_width = null;
	var $google_map_height = null;
	var $published = null;
	var $created_at = null;
	var $updated_at = null;

	function __construct(&$db)
	{
		parent::__construct( '#__enmasse_merchant', 'id', $db );
	}

}

?>