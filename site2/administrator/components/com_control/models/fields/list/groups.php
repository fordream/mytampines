<?php
/**
 * @version		$Id: groups.php 1163 2009-05-22 23:15:43Z eddieajau $
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
class JXFieldTypeList_Groups extends JXFieldTypeList
{
	function _getOptions(&$node)
	{
		$type	= strtolower($this->_parent->getValue('type'));
		$model	= JModel::getInstance('Group', 'ControlModel');

		$model->setState('tree',		'1');
		//$model->setState('parent_id',	$node->attributes('parent_id'));
		if ($type == 'aro') {
			// @todo look this up correctly
			$model->setState('parent_id',	28);
		}
		$model->setState('select',		'a.id AS value, a.name AS text');
		$model->setState('group_type',	$type);
		$model->setState('order by',	'a.lft');
		$options = $model->getItems(false);
		//array_unshift($options, JHTML::_('select.option', 0, 'Not Applicable'));

		if (count($options) == 1) {
			array_unshift($options, JHTML::_('select.option', 0, 'None'));
		}
		else {
			foreach ($options as $i => $option) {
				$options[$i]->text = str_pad($option->text, strlen($option->text) + 2*$option->level, '- ', STR_PAD_LEFT);
			}
		}

		return $options;
	}
}