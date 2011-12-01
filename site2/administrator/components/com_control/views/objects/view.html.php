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
class ControlViewObjects extends JView
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
		$sections	= $this->get('Sections');
		$pagination	= $this->get('Pagination');

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		$this->assignRef('state',			$state);
		$this->assignRef('items',			$items);
		$this->assignRef('sectionValues',	$sections);
		$this->assignRef('pagination',		$pagination);

		// types filter
		$options	= array();
		$options[]	= JHTML::_('select.option', 'aco', 'Permissions (ACOs)');
		$options[]	= JHTML::_('select.option', 'aro', 'Users (AROs)');
		$options[]	= JHTML::_('select.option', 'axo', 'Items (AXOs)');
		$this->assign('objectTypes', $options);

		$this->_setToolbar();
		parent::display($tpl);
	}

	/**
	 * Display the toolbar
	 * @access	public
	 */
	function _setToolbar()
	{
		JToolBarHelper::title(JText::_('JX Control: '.$this->state->object_type.' Objects'), 'logo');
		//JToolBarHelper::custom('publish', 'publish.png', 'publish_f2.png', 'Publish', true);
		//JToolBarHelper::custom('unpublish', 'unpublish.png', 'unpublish_f2.png', 'Unpublish', true);
		JToolBarHelper::custom('edit', 'edit.png', 'edit_f2.png', 'Edit', true);
		if ($this->state->get('ext_mode') == 1) {
			JToolBarHelper::custom('edit', 'new.png', 'new_f2.png', 'New', false);
			JToolBarHelper::deleteList('', 'delete');
		}
	}
}