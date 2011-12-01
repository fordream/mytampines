<?php
/**
 * @version		$Id: view.html.php 1163 2009-05-22 23:15:43Z eddieajau $
 * @copyright	Copyright (C) 2008 - 2009 JXtended LLC. All rights reserved.
 * @license		GNU General Public License
 */

// no direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * @package		JXtended.Control
 * @subpackage	com_control
 */
class ControlViewObject extends JView
{
	/**
	 * Display the view
	 *
	 * @access	public
	 */
	function display($tpl = null)
	{
		$state		= $this->get('State');
		$item		= $this->get('Item');
		$form		= $this->get('Form');
		$groupList	= $this->get('grouplist');
		$groups		= $this->get('groups');

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		$form->setName('adminForm');
		$form->loadObject($item);

		$this->assignRef('state',		$state);
		$this->assignRef('item',		$item);
		$this->assignRef('form',		$form);
		$this->assignRef('grouplist',	$groupList);
		$this->assignRef('groups',		$groups);

		if ($state->get('object_type') == 'aro') {
			$user	= JUser::getInstance($item->value);
			$this->assign('group_id',	$user->gid);
		}
		else {
			$this->assign('group_id',	0);
		}

		parent::display($tpl);
	}
}

function aclGroupChecked(&$array, $value)
{
	return in_array($value, (array) $array) ? 'checked="checked"' : '';
}