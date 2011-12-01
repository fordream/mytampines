<?php
/**
 * @version		$Id: config.php 1163 2009-05-22 23:15:43Z eddieajau $
 * @copyright	Copyright (C) 2008 - 2009 JXtended LLC. All rights reserved.
 * @license		GNU General Public License
 */

// no direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controller');

/**
 * The JXtended configuration controller
 *
 * @package		JXtended.Control
 * @subpackage	com_control
 */
class ControlControllerConfig extends JController
{
	/**
	 * Method to save the configuration changes.
	 *
	 * @access	public
	 * @return	void
	 * @since	1.0
	 */
	function save()
	{
		// Get the model.
		$model = &$this->getModel('Config', 'ControlModel');

		// Save the configuration.
		if (!$model->save()) {
			$message = JText::_('JX_CONFIG_SAVE_FAILED');
			$this->setRedirect('index.php?option=com_control&view=config&tmpl=component', $message, 'error');
		} else {
			$this->setRedirect('index.php?option=com_control&view=config&layout=success&tmpl=component');
		}
	}

	/**
	 * Method to cancel the configuration changes.
	 *
	 * @access	public
	 * @return	void
	 * @since	1.0
	 */
	function cancel()
	{
		$this->setRedirect('index.php?option=com_control');
	}

	/**
	 * Method to export the configuration via download.
	 *
	 * @access	public
	 * @return	void
	 * @since	1.0
	 */
	function export()
	{
		$app	= &JFactory::getApplication();
		$config = &JComponentHelper::getParams('com_control');
		$string	= $config->toString();

		header('Content-type: application/force-download');
	    header('Content-Transfer-Encoding: Binary');
	    header('Content-length: '.strlen($string));
	    header('Content-disposition: attachment; filename="jxcontrol.config.ini"');
		header('Pragma: no-cache');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Expires: 0');

	    echo $string;

		$app->close();
	}

	/**
	 * Method to import the configuration via string or upload.
	 *
	 * @access	public
	 * @return	bool	True on success, false on failure.
	 * @since	1.0
	 */
	function import()
	{
		$string = JRequest::getVar('configString', '', 'post', 'string', JREQUEST_ALLOWHTML);
		$file	= JRequest::getVar('configFile', array(), 'files', 'array');
		$return	= null;

		// Handle the possible import methods.
		if (!empty($file) && ($file['error'] == 0) && ($file['size'] > 0) && (is_readable($file['tmp_name'])))
		{
			// Handle import via uploaded file.
			$string = implode("\n", file($file['tmp_name']));
			$model	= &$this->getModel('Config');
			$return	= $model->import($string);
		}
		else
		{
			// Handle import via pasted string.
			$model	= &$this->getModel('Config');
			$return	= $model->import($string);
		}

		// Handle the response.
		if ($return === false)
		{
			$message = JText::sprintf('JX_CONFIG_IMPORT_FAILED', $model->getError());
			$this->setRedirect('index.php?option=com_control&view=config&layout=import&tmpl=component', $message, 'notice');
			return false;
		}
		else
		{
			$this->setRedirect('index.php?option=com_control&view=config&layout=close&tmpl=component');
			return true;
		}
	}
}
