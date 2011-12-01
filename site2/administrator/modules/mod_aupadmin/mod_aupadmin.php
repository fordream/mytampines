<?php
/*
 * @component AlphaUserPoints
 * @copyright Copyright (C) 2008-2010 Bernard Gilly
 * @license : GNU/GPL
 * @Website : http://www.alphaplug.com
 */

defined('_JEXEC') or die('Restricted Access');

$db =& JFactory::getDBO();

$lang =& JFactory::getLanguage();

JPlugin::loadLanguage( 'com_alphauserpoints' );
$label = JText::_('AUP_USERS_POINTS');
$label2 = "";

// Load custom CSS
$document =& JFactory::getDocument();
$document->addStyleSheet(JURI::base().'components/com_alphauserpoints/assets/css/mod_aupadmin.css');

// check if unapproved item
$query = "SELECT COUNT(*) FROM #__alpha_userpoints_details"
	   . " WHERE approved='0' AND status='0'"
	   ;
$db->setQuery( $query );
$result = $db->loadResult();
if ( $result ) $label2 = '<span class="small">' . JText::_('AUP_PENDING_APPROVAL') . ' <font color="red">('.$result.')</font></span>';
$image = ($result)? "icon-48-alphauserpoints-warning.png" : "icon-48-alphauserpoints.png";
?>
<div class="aupcpanel">
<div style="float:<?php echo ($lang->isRTL()) ? 'right' : 'left'; ?>;">
	<div class="icon">
		<a href="index.php?option=com_alphauserpoints&view=cpanel">
			<img src="components/com_alphauserpoints/assets/images/<?php echo $image ?>" />
			<span><?php echo $label; ?></span><div><?php echo $label2; ?></div>
		</a>
	</div>
</div>
</div>