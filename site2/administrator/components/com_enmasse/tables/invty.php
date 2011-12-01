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

class TableEnmasse_invty extends JTable
{
	var $id = null;
	var $order_item_id = null;
	var $pdt_id = null;
	var $name = null;
	var $deallocated_at = null;
	var $status = null;

	function __construct(&$db)
	{
		parent::__construct( '#__enmasse_invty', 'id', $db );
	}

}
?>