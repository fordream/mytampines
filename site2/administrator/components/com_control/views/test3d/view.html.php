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
class ControlViewTest3D extends JView
{
	/**
	 * Display the view
	 *
	 * @access	public
	 */
	function display($tpl = null)
	{
		$state	= $this->get('State');
		$go		= true;
		if (!$state->get('search')) {
			$go = false;
			JError::raiseNotice(500, JText::_('JX Notice You must filter on a user for performance reasons'));
		}
		if (!$state->get('section_value')) {
			$go = false;
			JError::raiseNotice(500, JText::_('JX Notice You must filter on a section for performance reasons'));
		}
		if (!$state->get('axo_section_value')) {
			$go = false;
			JError::raiseNotice(500, JText::_('JX Notice You must filter on a section for performance reasons'));
		}
		else if (strpos($state->get('axo_section_value'), $state->get('section_value')) !== 0) {
			$go = false;
			JError::raiseNotice(500, JText::_('JX Notice You must filter on a similar sections for performance reasons'));
		}

		if ($go) {
			$items = $this->get('3d');
		}
		else {
			$items = array();
		}
		$sections		= $this->get('Sections');
		$axoSections	= $this->get('AxoSections');
		$pagination		= $this->get('Pagination');

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		$this->assignRef('state',			$state);
		$this->assignRef('items',			$items);
		$this->assignRef('sectionValues',	$sections);
		$this->assignRef('axoSectionValues',$axoSections);
		$this->assignRef('pagination',		$pagination);

		// Set the toolbar
		JToolBarHelper::title(JText::_('JX Control: 3D Test'), 'logo');

		parent::display($tpl);
	}
}