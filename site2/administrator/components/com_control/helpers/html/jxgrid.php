<?php
/**
 * @version		$Id: jxgrid.php 1163 2009-05-22 23:15:43Z eddieajau $
 * @copyright	Copyright (C) 2008 - 2009 JXtended LLC. All rights reserved.
 * @license		GNU General Public License
 */

/**
 * HTML Grid Helper
 *
 * @package		JXtended.Control
 * @subpackage	com_control
 */
class JHTMLJxGrid
{
	function enabled($value, $i)
	{
		$images	= array(0 => 'images/publish_x.png', 1 => 'images/tick.png');
		$alts	= array(0 => 'Disabled', 1 => 'Enabled');
		$img 	= JArrayHelper::getValue($images, $value, $images[0]);
		$task 	= $value == 1 ? 'rule.disable' : 'rule.enable';
		$alt 	= JArrayHelper::getValue($alts, $value, $images[0]);
		$action = JText::_('JX Click to toggle setting');

		$href = '
		<a href="javascript:void(0);" onclick="return listItemTask(\'cb'. $i .'\',\''. $task .'\')" title="'. $action .'">
		<img src="'. $img .'" border="0" alt="'. $alt .'" /></a>'
		;

		return $href;
	}

	function allowed($value, $i)
	{
		$images	= array(0 => 'images/publish_x.png', 1 => 'images/tick.png');
		$alts	= array(0 => 'Denied', 1 => 'Allowed');
		$img 	= JArrayHelper::getValue($images, $value, $images[0]);
		$task 	= $value == 1 ? 'rule.deny' : 'rule.allow';
		$alt 	= JArrayHelper::getValue($alts, $value, $images[0]);
		$action = JText::_('JX Click to toggle setting');

		$href = '
		<a href="javascript:void(0);" onclick="return listItemTask(\'cb'. $i .'\',\''. $task .'\')" title="'. $action .'">
		<img src="'. $img .'" border="0" alt="'. $alt .'" /></a>'
		;

		return $href;
	}

}