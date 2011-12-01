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

class TableSetting extends JTable
{
	var $id=null;
	var $company_name = null;
	var $address1= null;
	var $address2 = null;
	var $city = null;
	var $state = null;
	var $country = null;
	var $postal_code= null;
	var $tax = null;
	var $tax_number1 = null;
	var $tax_number2 = null;
	var $logo_url= null;
	var $contact_number = null;
	var $contact_fax = null;
	var $customer_support_email = null;
	var $default_currency = null;
	var $currency_prefix = null;
	var $currency_postfix = null;
	var $currency_decimal = null;
	var $currency_separator = null;
	var $currency_decimal_separator = null;
	var $image_height = null;
	var $image_width = null;
	var $article_id = null;
	var $theme = null;
	var $coupon_bg_url = null;
	var $minute_release_invty = null;
	var $created_at = null;
	var $updated_at = null;
    var $subscription_class = null;
    var $point_system_class = null;
	function __construct(&$db)
	{
		parent::__construct( '#__enmasse_setting', 'id', $db );
	}

}
?>