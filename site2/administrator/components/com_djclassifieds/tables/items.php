<?php
/**
* @version		1.1
* @package		DJ Classifieds
* @subpackage	DJ Classifieds Component
* @copyright	Copyright (C) 2010 Blue Constant Media LTD, All rights reserved.
* @license		http://www.gnu.org/licenses GNU/GPL
* @autor url    http://design-joomla.eu
* @autor email  contact@design-joomla.eu
* @Developer    Lukasz Ciastek - lukasz.ciastek@design-joomla.eu
* 
* 
* DJ Classifieds is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
*
* DJ Classifieds is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with DJ Classifieds. If not, see <http://www.gnu.org/licenses/>.
* 
*/
defined('_JEXEC') or die('Restricted access');



class TableItems extends JTable

{

	var $id = null;
	
	var $user_id = null;

	var $name = null;

	var $cat_id = null;
	
	var $description = null;
	
	var $intro_desc = null;
	
	var $image_url = null;
	
	var $date_start = null;
	
	var $date_exp = null;
	
	var $display = null;
	
	var $paypal_token = null;
	
	var $payed = null;
	
	var $pay_type = null;
	
	var $special = null;
	
	var $notify = null;
	
	var $price = null;
	
	var $contact = null;

	var $ordering = 0;
	
	var $published = 0;
	
	

	function __construct(&$db)

	{

		parent::__construct( '#__djcf_items', 'id', $db);

	}

}

?>