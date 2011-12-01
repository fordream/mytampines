<?php
/**
 * @version		$Id: view.html.php 1168 2009-05-25 10:52:18Z eddieajau $
 * @package		JXtended.Control
 * @copyright	Copyright (C) 2008 - 2009 JXtended LLC. All rights reserved.
 * @license		http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

// no direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * @package		JXtended.Control
 */
class ControlViewAbout extends JView
{
	/**
	 * Display the view
	 *
	 * @access	public
	 */
	function display($tpl = null)
	{
		$version	= new ControlVersion;
		$versions	= $version->getVersions();

		$upgrades	= $this->get('Upgrades');

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		$this->assignRef('versions',	$versions);

		parent::display($tpl);
	}
}