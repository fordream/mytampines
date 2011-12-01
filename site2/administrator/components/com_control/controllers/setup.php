<?php
/**
 * @version		$Id: setup.php 1175 2009-06-04 00:15:16Z eddieajau $
 * @copyright	Copyright (C) 2008 - 2009 JXtended LLC. All rights reserved.
 * @license		GNU General Public License
 */

// no direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controller');

/**
 * The Setup Controller
 *
 * @package		JXtended.Control
 * @subpackage	com_control
 */
class ControlControllerSetup extends JController
{
	/**
	 * Method to manually install the component.
	 *
	 * @return	void
	 */
	public function install()
	{
		// Get the setup model.
		$model = &$this->getModel('Setup');

		// Attempt to run the manual install routine.
		if (!$model->install() || !$model->initAcl()) {
			$this->setMessage(JText::sprintf('JX_Setup_Install_failed', $model->getError()), 'notice');
		}
		else {
			$this->setMessage(JText::_('JX_Setup_install_success'));
		}

		// Set the redirect.
		$this->setRedirect('index.php?option=com_control');
	}

	/**
	 * Method to process any available database upgrades.
	 *
	 * @return	void
	 */
	public function upgrade()
	{
		// Check for request forgeries
		JRequest::checkToken('request') or jexit(JText::_('Invalid_Token'));

		// Get the upgrades.
		$version	= new ControlVersion();
		$upgrades	= $version->getUpgrades();

		// Get the setup model.
		$model = &$this->getModel('Setup');

		// Attempt to run the upgrade routine.
		if ($model->upgrade()) {
			$this->setMessage(JText::_('JX_Setup_database_upgrade_success'));
		}
		else {
			$this->setMessage(JText::sprintf('JX_Setup_database_upgrade_failed', $model->getError()), 'notice');
		}
		$this->setRedirect('index.php?option=com_control');
	}
}