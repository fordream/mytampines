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

class EnmasseViewlocation extends JView
{
	function display($tpl = null)
	{

		$task = JRequest::getWord('task');
		if($task == 'edit')
		{
			TOOLBAR_enmasse::_LOCATION_NEW();
			$cid = JRequest::getVar( 'cid', array(0), '', 'array' );
			$row = JModel::getInstance('location','enmasseModel')->getById($cid[0]);
			$this->assignRef( 'location', $row );
		}
		elseif($task == 'add')
		{
			TOOLBAR_enmasse::_LOCATION_NEW();
		}		
		else
		{			 
			/// load pagination
			$pagination =& $this->get('Pagination');
			$state =& $this->get( 'state' );
			// get order values
			$order['order_dir'] = $state->get( 'filter_order_dir' );
            $order['order']     = $state->get( 'filter_order' );
            
			TOOLBAR_enmasse::_SMENU();
			$nNumberOfLocations = JModel::getInstance('location','enmasseModel')->countAll();
			if($nNumberOfLocations==0)
			{
				TOOLBAR_enmasse::_LOCATION_EMPTY();
			}
			else
			{
				//------------------------
				//gemerate integration class
				 $integrateFileName = EnmasseHelper::getSubscriptionClassFromSetting().'.class.php';
				 $integrationClass = EnmasseHelper::getSubscriptionClassFromSetting();
				 require_once (JPATH_SITE . DS ."components". DS ."com_enmasse". DS ."helpers". DS ."subscription". DS .$integrationClass. DS.$integrateFileName);
				 $integrationObject = new $integrationClass();
				 $integrationObject ->addMenu();				
				TOOLBAR_enmasse::_LOCATION();
			}			
			
			$locationList = JModel::getInstance('location','enmasseModel')->search();
		
			 
			$this->assignRef( 'locationList', $locationList );
			$this->assignRef('pagination', $pagination);
			$this->assignRef( 'order', $order );
			
		}
		parent::display($tpl);
	}

}
?>