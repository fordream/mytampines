<?php
/**
 * @version     $Id: $
 * @package     com_hub2
 * @copyright   (C) 2010 HyperLocalizer Pty Ltd. All rights reserved.
 * @license     HyperLocalizer proprietary.
 */
// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

class Hub2ViewGenerateSQL extends JView {
    function setToolbar() {
        $task = JRequest::getVar('task');
        JToolBarHelper::title( JText::_('Replicate multiple SQL'),  'logo' );
        JToolBarHelper::custom('backtodashboard','back.png','back_f2.png',
          'Back To Dashboard',false);
        JToolBarHelper::custom('generatesql','new.png', 'new_f2.png','Generate',false );
    }
    /**
     * Display the view
     *
     * @access  public
     */
    function display($tpl = null) {
        $lang = JFactory::getLanguage();
        $lang->load('com_hub2_dashboard', JPATH_ADMINISTRATOR);
        $this->setToolbar();
        parent::display($tpl);
    }
}
?>
