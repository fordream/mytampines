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

require_once(JApplicationHelper::getPath('toolbar_html'));

switch($task)
{
	case 'about':
		TOOLBAR_mobilejoomla::_ABOUT();
		break;
	case 'extensions':
		TOOLBAR_mobilejoomla::_EXT();
		break;
	case 'settings':
	default:
		TOOLBAR_mobilejoomla::_DEFAULT();
		break;
}