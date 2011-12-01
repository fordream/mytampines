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

class TableOrder extends JTable
{
	var $id = null;
	var $description = null;
	var $status = null;
	var $total_buyer_paid = null;
	var $session_id = null;
	var $buyer_id = null;
	var $buyer_detail = null;
	var $pay_gty_id = null;
	var $pay_detail = null;
	var $delivery_gty_id = null;
	var $delivery_detail = null;
	var $created_at = null;
	var $updated_at = null;

	function __construct(&$db)
	{
		parent::__construct( '#__enmasse_order', 'id', $db );
	}

}
?>