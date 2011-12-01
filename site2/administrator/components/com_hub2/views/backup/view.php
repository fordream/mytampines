<?php
/**
 * @version		$Id: $
 * @package		com_hub2
 * @copyright	(C) 2010 HyperLocalizer Pty Ltd. All rights reserved.
 * @license		HyperLocalizer proprietary.
 */

// no direct access
defined('_JEXEC') or die('Restricted access');


jimport('joomla.application.component.view');
class Hub2ViewBackup extends JView {

    function setToolbar() {
        JToolBarHelper::title( JText::_( 'Backup/Clean Tables' ), 'logo' );
        JToolBarHelper::custom( 'edit', 'new.png', 'new_f2.png', 'Select', false );
    }

    function setEditToolbar() {
        JToolBarHelper::title( JText::_( 'Backup/Clean Tables' ), 'logo' );
        JToolBarHelper::custom( 'backupTables', 'new.png', 'new_f2.png', 'Backup', false );
        JToolBarHelper::custom( 'cleanTables', 'delete.png', 'delete_f2.png', 'Clean', false );
    }
    /**
     * Display the view
     *
     * @access  public
     */
    function display($tpl=null) {
        $jApp = &JFactory::getApplication();

        $config = &JComponentHelper::getParams( 'com_hub2' );
        $this->assignRef( 'config', $config );

        $document   = &JFactory::getDocument();
        $this->assignRef( 'document',   $document );

        $layout = $this->getLayout();
        if ($layout == 'edit') {
            $this->setEditToolbar();
            $model = $this->getModel();
            $form   = &$model->getForm();
            $form->setName( 'adminForm' );
            $this->assignRef( 'form', $form );

            $this->assignRef('errors',JRequest::getVar('errors'));
        } else {
            $this->setToolbar();
        }
        parent::display($tpl);
    }
}