<?php
/**
 * @version		$Id: sections.php 1163 2009-05-22 23:15:43Z eddieajau $
 * @copyright	Copyright (C) 2008 - 2009 JXtended LLC. All rights reserved.
 * @license		GNU General Public License
 */

// no direct access
defined('_JEXEC') or die;

jimport('joomla.html.html');

/**
 * List form field type object
 *
 * @package		JXtended.Control
 * @subpackage	com_control
 */
class JXFieldTypeList_Sections extends JXFieldTypeList
{
	function _getOptions(&$node)
	{
		$type	= $this->_parent->getValue('type');
		$model		= JModel::getInstance('Section', 'ControlModel');
		$vars		= array(
			'select'		=> 'a.value, a.name AS text',
			'section_type'	=> $this->_parent->getValue('type'),
			'order by'	=> 'a.order_value, a.name',
		);
		$options	= $model->getList($vars);
		//array_unshift($options, JHTML::_('select.option', 0, 'Not Applicable'));
		return $options;
	}
}