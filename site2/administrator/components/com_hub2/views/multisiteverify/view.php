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

class Hub2ViewMultisiteVerify extends JView {

    /**
     * Set the toolbar icons
     */
    function setToolbar() {
        $task = JRequest::getVar('task');
        if ($task == 'verifySchemaOnSites') {
            JToolBarHelper::title( JText::_('Hub2: Multisite Verification - Schema'), 'logo' );
            JToolBarHelper::preferences('com_hub2', '500');
        } else if($task == 'verifyComponentsModules' or $task == 'runVerifyCoponentsModules' ) {
            JToolBarHelper::title( JText::_('Hub2: Multisite Verification -
              Compare Modules and Components'), 'logo' );
            JToolBarHelper::custom('backtodashboard','back.png','back_f2.png',
              'Back To Dashboard',false);
            JToolBarHelper::custom('runVerifyCoponentsModules','new.png',
              'new_f2.png','Compare',false );
        } else if($task == 'pushToSubsites' or $task == 'pushConfig' ) {
            JToolBarHelper::title( JText::_('Hub2: Copy configuration from template'), 'logo' );
            JToolBarHelper::custom('backtodashboard','back.png','back_f2.png',
              'Back To Dashboard',false);
            JToolBarHelper::custom('pushConfig','new.png',
              'new_f2.png','Copy config',false );
        } else {
            JToolBarHelper::title( JText::_('Hub2: Multisite Verification - Configuration'),
            'logo' );
            JToolBarHelper::preferences('com_hub2', '500');
        }
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
