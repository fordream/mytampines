<?php
/**
 * Mobile Joomla!
 * http://www.mobilejoomla.com
 *
 * @version		1.0 RC3
 * @license		http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL
 * @copyright	(C) 2008-2011 MobileJoomla!
 * @date		September 2011
 */
defined('_JEXEC') or die('Restricted access');

class TOOLBAR_mobilejoomla
{
	function _DEFAULT()
	{
		JToolBarHelper::title(JText::_('COM_MJ__MOBILE_JOOMLA_SETTINGS'), 'config.php');
		JToolBarHelper::apply();
		JToolBarHelper::cancel('cancel');
	}

	function _ABOUT()
	{
		JToolBarHelper::title(JText::_('COM_MJ__ABOUT_MOBILE_JOOMLA'));
		JToolBarHelper::cancel('cancel');
	}

	function _EXT()
	{
		JToolBarHelper::title(JText::_('COM_MJ__EXTENSIONS'));
		JToolBarHelper::save();
		JToolBarHelper::cancel('cancel');
	}
}
