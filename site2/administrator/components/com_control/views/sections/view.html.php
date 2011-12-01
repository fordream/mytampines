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
class ControlViewSections extends JView
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

		$this->assignRef('state',		$state);
		$this->assignRef('items',		$items);
		$this->assignRef('pagination',	$pagination);

		// types filter
		$options	= array();
		$options[]	= JHTML::_('select.option', 'acl', 'for Rules (ACLs)');
		$options[]	= JHTML::_('select.option', 'aco', 'for Permissions (ACOs)');
		$options[]	= JHTML::_('select.option', 'aro', 'for Users (AROs)');
		$options[]	= JHTML::_('select.option', 'axo', 'for Items (AXOs)');
		$this->assign('sectionTypes', $options);

		$this->_setToolbar();
		parent::display($tpl);
	}

	/**
	 * Display the toolbar
	 * @access	protected
	 */
	function _setToolbar()
	{
		$state	= $this->get('State');
		$type	= $state->get('section_type');

		JToolBarHelper::title(JText::_('JX Control: Sections'), 'logo');
		//JToolBarHelper::custom('publish', 'publish.png', 'publish_f2.png', 'Publish', true);
		//JToolBarHelper::custom('unpublish', 'unpublish.png', 'unpublish_f2.png', 'Unpublish', true);
		JToolBarHelper::custom('edit', 'edit.png', 'edit_f2.png', 'Edit', true);
		JToolBarHelper::custom('edit', 'new.png', 'new_f2.png', 'New', false);
		if ($type != 'aro') {
			JToolBarHelper::deleteList('', 'delete');
		}
	}
}