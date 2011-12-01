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
class ControlViewRules extends JView
{
	/**
	 * Display the view
	 *
	 * @access	public
	 */
	function display($tpl = null)
	{
		$state		= $this->get('State');
		$items		= $this->get('Items');
		$pagination	= $this->get('Pagination');

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		$this->assignRef('state', $state);
		$this->assignRef('items', $items);
		$this->assignRef('pagination', $pagination);

		$this->_setToolBar();
		parent::display($tpl);
	}

	/**
	 * Display the toolbar
	 *
	 * @access	private
	 */
	private function _setToolBar()
	{
		JToolBarHelper::title(JText::_('Control_View_Rules'), 'logo');
		JToolBarHelper::custom('rule.edit', 'edit.png', 'edit_f2.png', 'Edit', true);
		JToolBarHelper::custom('rule.edit_type1', 'new.png', 'new_f2.png', 'Control_Toolbar_New_Type1', false);
		JToolBarHelper::custom('rule.edit_type2', 'new.png', 'new_f2.png', 'Control_Toolbar_New_Type2', false);
		JToolBarHelper::deleteList('', 'rule.delete');
	}
}