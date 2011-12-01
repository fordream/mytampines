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
function com_uninstall()
{
    $db =& JFactory::getDBO();
	$query = "DELETE FROM #__components WHERE name='com_enmasse';";
		$db->setQuery( $query );
		$db->query();
?>
<b>
  	The En Masse are now removed from your system. But we have kept your database in case you want to reactivate the system
</b>
<p>
	We're sorry to see you go! To completely remove the software from your system, be sure to also uninstall the modules.
</p>
<?php
}
?>