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

class TableCouponElement extends JTable
{
	var $id = null;
	var $name = null;
	var $x = null;
	var $y = null;
	var $font_size = null;
	var $width = null;
	var $height = null;

	function __construct(&$db)
	{
		parent::__construct( '#__enmasse_coupon_element', 'id', $db );
	}

}
?>