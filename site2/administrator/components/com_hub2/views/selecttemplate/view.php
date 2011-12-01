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

class Hub2ViewSelectTemplate extends JView {

    /**
     * Set the toolbar icons
     */
    function setToolbar() {
        JToolBarHelper::title( JText::_('Hub2: Spoke Menu Management'), 'logo' );
        JToolBarHelper::custom('backtodashboard','back.png','back_f2.png',
              'Back To Dashboard',false);
    }

    /**
     * Display the view
     *
     * @access  public
     */
    function display($tpl = null) {
        $layout = JRequest::getVar('layout','');
        if ($layout == 'showiframe') {
            $this->setLayout($layout);
        } else {
            $this->setToolbar();
        }
        parent::display($tpl);
    }
}
