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
jimport('joomla.application.component.controller');
JTable::addIncludePath('components'.DS.'com_enmasse'.DS.'tables');
require_once( JPATH_ADMINISTRATOR . DS ."components". DS ."com_enmasse".DS."helpers". DS ."EnmasseHelper.class.php");
/// load language
$language =& JFactory::getLanguage();
$base_dir = JPATH_ADMINISTRATOR.DS.'components'.DS.'com_enmasse';
$version = new JVersion;
$joomla = $version->getShortVersion();
if(substr($joomla,0,3) == '1.6'){
    $extension = 'com_enmasse16';
}else{
    $extension = 'com_enmasse';
}
if($language->load($extension, $base_dir, $language->getTag(), true) == false)
{
	 $language->load($extension, $base_dir, 'en-GB', true);
}
class EnmasseControllerReport extends JController
{
	function display()
	{
    	JRequest::setVar('view', 'report');
		JRequest::setVar('layout', 'deal_coupon');
		parent::display();
	}
	
    function dealCouponList()
    {
    	JRequest::setVar('view', 'report');
		JRequest::setVar('layout', 'deal_coupon');
		parent::display();
    }
    
	function control()
	{
		$this->setRedirect('index.php?option=com_enmasse');
	}
	
	function generateReport()
	{
		$dealId = JRequest::getVar('dealId');
		if(!empty($dealId))
		{
			$deal = JModel::getInstance('deal','enmasseModel')->getById($dealId);
			$orderItemList = JModel::getInstance('orderItem','enmasseModel')->listByPdtIdAndStatus($dealId, "Delivered");
			
		    for($count =0; $count < count($orderItemList); $count++)
			{
				$orderItemList[$count]->invtyList 	= JModel::getInstance('invty','enmasseModel')->listByOrderItemId($orderItemList[$count]->id);
				$orderItemList[$count]->order 		= JModel::getInstance('order','enmasseModel')->getById($orderItemList[$count]->order_id);
			}	
		
			$count = 1;
			$id = 0; //id of the item in itemList array
			for($i=0; $i < count($orderItemList); $i++)
			{
				$orderItem = $orderItemList[$i];
				$buyerDetail = json_decode($orderItem->order->buyer_detail);
				$deliveryDetail = json_decode($orderItem->order->delivery_detail);

				for($j=0; $j < count($orderItem->invtyList); $j++)
				{					
					 $invty = $orderItem->invtyList[$j];
					 $itemList[$id]['Serial No.'] = $count++."\t"; 
					 $itemList[$id]['Buyer Name'] = $buyerDetail->name."\t"; 
					 $itemList[$id]['Buyer Email'] = $buyerDetail->email."\t"; 
					 $itemList[$id]['Delivery Name'] = $deliveryDetail->name."\t"; 
					 $itemList[$id]['Delivery Email'] =$deliveryDetail->email."\t"; 
					 $itemList[$id]['Order Comment'] = $orderItem->order->description."\t"; 
					 $itemList[$id]['Purchase Date'] = DatetimeWrapper::getDisplayDatetime($orderItem->created_at)."\t"; 
					 $itemList[$id]['Coupon Serial'] = $invty->name."\t"; 
					 $itemList[$id]['Coupon Status'] = JTEXT::_('COUPON_'.strtoupper($invty->status))."</br>";
					 $id++;
				}				
			}
	
			$filename = "Report" . date('Ymd') . ".xls";
		    enmasseHelper::reportGenerator($itemList);
			header("Content-Disposition: attachment; filename=\"$filename\"");
	        header("Content-Type: application/vnd.ms-excel");
			exit(0);
		}
		else
		{
			$this->setRedirect('http://'.$_SERVER['SERVER_NAME'].'/administrator/index.php?option=com_enmasse&controller=report',JTEXT::_('REPORT_EMPTY_MSG'));
		}
		
	}
	 
}
?>