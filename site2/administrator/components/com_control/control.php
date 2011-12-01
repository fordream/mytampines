<?php
/**
 * @version		$Id: control.php 1178 2009-06-04 01:39:04Z eddieajau $
 * @package		JXtended.Control
 * @copyright	Copyright (C) 2008 - 2009 JXtended LLC. All rights reserved.
 * @license		GNU General Public License
 * @link		http://jxtended.com
 */

// no direct access
defined('_JEXEC') or die;

// PHP 5 check
if (version_compare(PHP_VERSION, '5.0.0', '<')) {
	JError::raiseWarning(500, JText::_('JX_Use_PHP5'));
}
// Check for the JXtended Libraries.
else if (!file_exists(JPATH_SITE.'/plugins/system/jxtended.php'))
{
	JError::raiseWarning(500, JText::sprintf('JX_PLUGIN_MISSING', $redirect, JUtility::getToken()));
	JHTML::script('setup.js', 'administrator/components/com_contentmanager/media/js/');
}
else if (!function_exists('jximport'))
{
	// Attempt to enable the libraries.
	jimport('joomla.application.component.model');
	JModel::addIncludePath(JPATH_COMPONENT.'/models');
	$setup	= &JModel::getInstance('Setup', 'ControlModel');
	$return	= $setup->enableLibraries();

	if ($return === false)
	{
		// We couldn't enable the libraries plugin so throw a warning.
		JError::raiseWarning(500, JText::sprintf('JX_PLUGIN_DISABLED', $redirect, JUtility::getToken()));
		JHTML::script('setup.js', 'administrator/components/com_control/media/js/');
	}
	else
	{
		// Reload the page because we might need the libraries which weren't loaded on this page.
		JFactory::getApplication()->redirect('index.php?option=com_control');
		return true;
	}
}
elseif (version_compare(JX_LIBRARIES,'1.0.10', '<'))
{
	JError::raiseWarning(500, JText::sprintf('JX_LIBRARIES_OUTDATED', '1.0.10'));
}
else
{
	// Check version
	require_once JPATH_COMPONENT.DS.'version.php';

	$version = new ControlVersion;
	$version->showUpgrades();

	// Include dependancies
	jximport('jxtended.application.component.controller');

	$lang = JFactory::getLanguage();
	$lang->load('override-com_control');

	$controller	= JxController::getInstance('Control');
	$controller->execute(JRequest::getCmd('task'));
	$controller->redirect();

	// Display the copyright notice and version
	$version->showFooter();
}