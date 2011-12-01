<?php
/**
 * @version		$Id: view.html.php 1163 2009-05-22 23:15:43Z eddieajau $
 * @copyright	Copyright (C) 2008 - 2009 JXtended LLC. All rights reserved.
 * @license		GNU General Public License
 */

// no direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

// Helper functions

function aclObjectChecked(&$array, $section, $value)
{
	$values	= @$array[$section];
	return in_array($value, (array) $values) ? 'checked="checked"' : '';
}

function aclGroupChecked(&$array, $value)
{
	return in_array($value, (array) $array) ? 'checked="checked"' : '';
}

/**
 * @package		JXtended.Control
 * @subpackage	com_control
 */
class ControlViewACL extends JView
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
		$acos		= $this->get('ACOs');
		$aroGroups	= $this->get('AROGroups');
		$axos		= $this->get('AXOs');
		$axoGroups	= $this->get('AXOGroups');
		$acl		= $this->get('ACL');

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		$form->setName('adminForm');
		$form->loadObject($item);

		$this->assignRef('form', $form);
		$this->assignRef('state',		$state);
		$this->assignRef('item',		$item);
		$this->assignRef('acos',		$acos);
		$this->assignRef('aroGroups',	$aroGroups);
		$this->assignRef('axos',		$axos);
		$this->assignRef('axoGroups',	$axoGroups);
		$this->assignRef('acl',			$acl);

		$this->_setToolbar();
		parent::display($tpl);
	}

	/**
	 * Display the toolbar
	 * @access	protected
	 */
	function _setToolbar()
	{
		JToolBarHelper::title(JText::_('JX Control: Edit Rule'), 'logo');
		JToolBarHelper::custom('save2new', 'new.png', 'new_f2.png', 'Save & New', false,  false);
		JToolBarHelper::save();
		JToolBarHelper::apply();
		JToolBarHelper::cancel();
	}
}