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

class Hub2ViewDashboard extends JView
{
    /**
     * Set the toolbar icons
     */
    function setToolbar() {
        JToolBarHelper::title( 'Hub2: Dashboard', 'logo' );
        JToolBarHelper::preferences('com_hub2', '500');
    }

    /**
     * Display the view
     *
     * @access  public
     */
    function display($tpl = null) {
        $lang = JFactory::getLanguage();
        $lang->load('com_hub2_dashboard', JPATH_ADMINISTRATOR);
        //echo "<pre>";
        //print_r($lang->_paths); //_paths, _strings
        $this->setToolbar();
        parent::display($tpl);
    }
}