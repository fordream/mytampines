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

defined( '_JEXEC' ) or die( 'Restricted access' );
function com_install()
{
	$db =& JFactory::getDBO();
	$query = "INSERT INTO `#__components` (`id`, `name`, `link`, `menuid`, `parent`, `admin_menu_link`, `admin_menu_alt`, `option`, `ordering`, `admin_menu_img`, `iscore`, `params`, `enabled`) 
VALUES (NULL, 'En Masse', 'option=com_enmasse', '0', '0', 'option=com_enmasse', 'enMasse', 'com_enmasse', '0', 'js/ThemeOffice/component.png', '0', '', '1');";
		$query .= " limit 1";
		$db->setQuery( $query );
		$db->query();
  ?>
  <b>Congratulations, En Masse is ready to roll!</b><br />
  
  <?php
}
?>
