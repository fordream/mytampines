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
class ControlViewTest2D extends JView
{
	/**
	 * Display the view
	 *
	 * @access	public
	 */
	function display($tpl = null)
	{
		$state		= $this->get('State');
		$items		= $this->get('2d');
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
		$this->assignRef('pagination', $pagination);

		// Set the toolbar
		JToolBarHelper::title(JText::_('JX Control: 2D Test'), 'logo');

		parent::display($tpl);
	}
}