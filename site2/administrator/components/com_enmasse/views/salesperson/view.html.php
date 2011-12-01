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

class EnmasseViewSalesPerson extends JView
{
	function display($tpl = null)
	{
		$task = JRequest::getWord('task');
		
		if($task == 'edit')
		{
			TOOLBAR_enmasse::_SALESPERSON_NEW();
			
			$cid = JRequest::getVar( 'cid', array(0), '', 'array' );
			
			$salesPerson 	= JModel::getInstance('salesPerson','enmasseModel')->getById($cid[0]);
			$this->assignRef( 'salesPerson', $salesPerson );
		}
		elseif($task == 'add')
		{
			TOOLBAR_enmasse::_SALESPERSON_NEW();
		}				
		else // show
		{
			
			TOOLBAR_enmasse::_SMENU();
			$nNumberOfSales = JModel::getInstance('salesPerson','enmasseModel')->countAll();
			if($nNumberOfSales==0)
			{
				TOOLBAR_enmasse::_SALESPERSON_EMPTY();
			}
			else
			{
				TOOLBAR_enmasse::_SALESPERSON();
			}

						
			$filter = JRequest::getVar('filter');
			
			$salesPersonList = JModel::getInstance('salesPerson','enmasseModel')->search($filter['name']);
			/// load pagination
			$pagination =& $this->get('Pagination');
			$state =& $this->get( 'state' );
			// get order values
			$order['order_dir'] = $state->get( 'filter_order_dir' );
            $order['order']     = $state->get( 'filter_order' );
            
			$this->assignRef( 'filter', $filter );
			$this->assignRef( 'salesPersonList', $salesPersonList );
			$this->assignRef('pagination', $pagination);
			$this->assignRef( 'order', $order );			
		}
		parent::display($tpl);
	}

}
?>