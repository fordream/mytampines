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

class TableDeal extends JTable
{
	var $id = null;
	var $name = null;
	var $slug_name = null;
	var $short_desc = null;
	var $description = null;
	var $min_needed_qty = null;
	var $max_buy_qty = null;
	var $max_coupon_qty = null;
	var $max_qty = null;
	var $cur_sold_qty = null;
	var $origin_price = null;
	var $price = null;
	var $start_at = null;
	var $end_at = null;
	var $merchant_id = null;
	var $sales_person_id = null;
	var $highlight = null;
	var $pic_dir = null;
	var $terms = null;
	var $status = null;
	var $published = null;
	var $position = null;
	var $created_at = null;
	var $updated_at = null;
	var $is_side_deal = null; //Amol

	function __construct(&$db)
	{
		parent::__construct( '#__enmasse_deal', 'id', $db );
	}

}
?>