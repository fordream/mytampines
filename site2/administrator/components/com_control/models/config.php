<?php
/**
 * @version		$Id: config.php 1163 2009-05-22 23:15:43Z eddieajau $
 * @copyright	Copyright (C) 2008 - 2009 JXtended LLC. All rights reserved.
 * @license		GNU General Public License
 */

// no direct access
defined('_JEXEC') or die;

// import library dependencies
jimport('joomla.application.component.model');

/**
 * Configuration model class for Control.
 *
 * @package		JXtended.Control
 * @subpackage	com_control
 */
class ControlModelConfig extends JModel
{
	/**
	 * Flag to indicate model state initialization.
	 *
	 * @access	protected
	 * @var		boolean
	 */
	var $__state_set		= null;

	/**
	 * Overridden method to get model state variables.
	 *
	 * @access	public
	 * @param	string	$property	Optional parameter name.
	 * @return	object	The property where specified, the state object where omitted.
	 * @since	1.0
	 */
	function getState($property = null)
	{
		// if the model state is uninitialized lets set some values we will need from the request.
		if (!$this->__state_set)
		{
			// Load the parameters.
			$this->setState('params', JComponentHelper::getParams('com_control'));

			$this->__state_set = true;
		}

		return parent::getState($property);
	}

	/**
	 * Method to save the component configuration
	 *
	 * @access	public
	 * @return	boolean	True on success
	 * @since	1.0
	 */
	function save()
	{
		// Initialize variables.
		$table			= &JTable::getInstance('component');
		$params 		= JRequest::getVar('params', array(), 'post', 'array');
		$row			= array();
		$row['option']	= 'com_control';
		$row['params']	= $params;

		// Load the component data for the component
		if (!$table->loadByOption('com_control')) {
			$this->setError($table->getError());
			return false;
		}

		// Bind the new values
		$table->bind($row);

		// Check the row.
		if (!$table->check()) {
			$this->setError($table->getError());
			return false;
		}

		// Store the row.
		if (!$table->store()) {
			$this->setError($table->getError());
			return false;
		}

		return true;
	}
}