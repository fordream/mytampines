<?php
/**
 * @version		$Id: control.php 1163 2009-05-22 23:15:43Z eddieajau $
 * @package		JXtended.Control
 * @copyright	Copyright (C) 2008 - 2009 JXtended LLC. All rights reserved.
 * @license		GNU General Public License
 */

// no direct access
defined('_JEXEC') or die;

/**
 * HTML behavior class
 *
 * @package		JXtended.Control
 * @subpackage	com_control
 */
class JHTMLControl
{
	/**
	 * Method to render a given parameters form.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string	$name	The name of the array for form elements.
	 * @param	string	$ini	An INI formatted string.
	 * @param	string	$file	The XML file to render.
	 * @return	string	A HTML rendered parameters form.
	 */
	function params($name, $ini, $file)
	{
		jimport('joomla.html.parameter');
		// Load and render the parameters
		$path	= JPATH_COMPONENT.DS.$file;
		$params	= new JParameter($ini, $path);
		$output	= $params->render($name);

		return $output;
	}
}
