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
require_once( JPATH_ADMINISTRATOR . DS ."components". DS ."com_enmasse".DS."helpers". DS ."DatetimeWrapper.class.php");
require_once( JPATH_ADMINISTRATOR . DS ."components". DS ."com_enmasse".DS."helpers". DS ."EnmasseHelper.class.php");
class EnmasseViewReport extends JView
{
	function display($tpl = null)
	{
		TOOLBAR_enmasse::_SMENU();
		JToolBarHelper::title( JText::_( 'T_DEAL_REPORT' ), 'article.png' );
			
		$task = JRequest::getVar('task');
		
		$dealList = JModel::getInstance('deal','enmasseModel')->listConfirmed();
		$this->assignRef('dealList',$dealList);
		
		$filter = JRequest::getVar('filter');
		$this->assignRef('filter',$filter);
		
		$dealId = $filter['deal_id'];
		
		if(!empty($dealId))
		{
			$deal = JModel::getInstance('deal','enmasseModel')->getById($dealId);
			$this->assignRef('deal',$deal);	
			
			$orderItemList = JModel::getInstance('orderItem','enmasseModel')->listByPdtIdAndStatus($dealId, "Delivered");
			
			for($count =0; $count < count($orderItemList); $count++)
			{
				$orderItemList[$count]->invtyList 	= JModel::getInstance('invty','enmasseModel')->listByOrderItemId($orderItemList[$count]->id);
				$orderItemList[$count]->order 		= JModel::getInstance('order','enmasseModel')->getById($orderItemList[$count]->order_id);
			}
			$this->assignRef('orderItemList',$orderItemList);
		}	
		else
		{
			$orderItemList = array();
			$this->assignRef('orderItemList', $orderItemList);
		}	
		/// load pagination
			$pagination =JModel::getInstance('orderItem','enmasseModel')->getPagination();	
			$this->assignRef('pagination', $pagination);	
		parent::display($tpl);

	}

}
?>