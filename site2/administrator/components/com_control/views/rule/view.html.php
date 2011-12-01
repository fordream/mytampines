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
class ControlViewRule extends JView
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

		if ($item->id == 0) {
			$sectionValue = $state->get('section_value');
			// The section could be an array.  First one must be the component.
			if (is_array($state->section_value)) {
				$item->section_value = JArrayHelper::getValue($sectionValue, 0);
			}
			else {
				$item->section_value = $sectionValue;
			}
			$item->acl_type = $state->get('acl_type');
		}

		$form	= &$this->get('Form');
		$form->setName('adminForm');
		$form->loadObject($item);

		$this->assignRef('state',		$state);
		$this->assignRef('item',		$item);
		$this->assignRef('form',		$form);
		$this->assignRef('acl',			$acl);
		$this->assignRef('acos',		$acos);
		$this->assignRef('aroGroups',	$aroGroups);
		if ($item->acl_type == 2) {
			$this->assignRef('axos',		$axos);
		}
		if ($item->acl_type == 3) {
			$this->assignRef('axoGroups',	$axoGroups);
		}

		// Set the toolbar
		JToolBarHelper::title(JText::_('Control_View_Rule_'.($item->id ? 'Edit' : 'Add').'_Type'.$item->acl_type), 'logo');
		JToolBarHelper::save('rule.save');
		JToolBarHelper::apply('rule.apply');
		JToolBarHelper::cancel('rule.cancel');

		parent::display($tpl);
	}
}