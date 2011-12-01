<?php
/*------------------------------------------------------------------------
# En Masse - Social Buying Extension 2010
# ------------------------------------------------------------------------
# By Matamko.com
# Copyright (C) 2010 Matamko.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.matamko.com
# Technical Support:  Visit our forum at www.matamko.com
-------------------------------------------------------------------------*/


defined( '_JEXEC' ) or die( 'Restricted access' );
jimport( 'joomla.application.component.view');
require_once( JPATH_ADMINISTRATOR . DS ."components". DS ."com_enmasse". DS ."toolbar.enmasse.html.php");
 
class EnmasseViewEmailTemplate extends JView
{
    function display($tpl = null)
    { 
        
        $task = JRequest::getWord('task');
        if($task == 'edit'|| $task == 'add' )
        {
        	$cid = JRequest::getVar( 'cid', array(0), '', 'array' );
            
        	TOOLBAR_enmasse::_EMAILTEMPLATE_NEW();
            
	        $emailTemplate 	= JModel::getInstance('emailTemplate','enmasseModel')->getById($cid[0]);
	         
	        $this->assignRef( 'emailTemplate', $emailTemplate );	
        }
        else
        {
	        TOOLBAR_enmasse::_SMENU();
        	TOOLBAR_enmasse::_EMAILTEMPLATE();
        	
	        $emailTemplateList 	= JModel::getInstance('emailTemplate','enmasseModel')->listAll();
	         
	        $this->assignRef( 'emailTemplateList', $emailTemplateList );
        }
        parent::display($tpl);
    }

}
?>