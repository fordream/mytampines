<?php
/**
 * @version		$Id: view.html.php 1163 2009-05-22 23:15:43Z eddieajau $
 * @copyright	Copyright (C) 2008 - 2009 JXtended LLC. All rights reserved.
 * @license		GNU General Public License
 */

// no direct access
defined('_JEXEC') or die;

// import library dependencies
jimport('joomla.application.component.view');

/**
 * The HTML JXtended Control configuration view
 *
 * @package		JXtended.Control
 * @subpackage	com_control
 */
class ControlViewConfig extends JView
{
	/**
	 * Method to display the view.
	 *
	 * @access	public
	 * @param	string	$tpl	A template file to load.
	 * @return	mixed	JError object on failure, void on success.
	 * @throws	object	JError
	 * @since	1.0
	 */
	function display($tpl = null)
	{
		$user		= &JFactory::getUser();
		$state		= $this->get('State');
		$params		= &$state->get('params');

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		JHTML::addIncludePath(JPATH_COMPONENT.DS.'helpers'.DS.'html');

		// Push out the view data.
		$this->assignRef('state',	$state);
		$this->assignRef('params',	$params);

		parent::display($tpl);
	}
}