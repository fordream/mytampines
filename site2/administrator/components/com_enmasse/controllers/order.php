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
class EnmasseControllerOrder extends JController
{

	function display()
	{
		JRequest::setVar('view', 'order');
		JRequest::setVar('layout', 'show');
		parent::display();
	}
	function edit()
	{
		JRequest::setVar('view', 'order');
		JRequest::setVar('layout', 'edit');
		parent::display();
	}

	function control()
	{
		$this->setRedirect('index.php?option=com_enmasse');
	}
	
	function save()
	{			
		$orderData->id 			= JRequest::getVar( 'id', '', 'post', 'text', JREQUEST_ALLOWRAW );
		$orderData->description = JRequest::getVar( 'description', '', 'post', 'text', JREQUEST_ALLOWRAW );
		$orderData->status 		= JRequest::getVar( 'status', '', 'post', 'text', JREQUEST_ALLOWRAW );
		$orderData->buyerid 		= JRequest::getVar( 'buyerid', '', 'post', 'text', JREQUEST_ALLOWRAW );
		$model = JModel::getInstance('order','enmasseModel');
		
		if ($model->store($orderData))
		{
			$orderItemList = JModel::getInstance('orderItem','enmasseModel')->listByOrderId($orderData->id);
			foreach($orderItemList as $orderItem)
				JModel::getInstance('orderItem','enmasseModel')->updateStatus($orderData->id, $orderData->status);
				
			$msg = JText::_('SAVE_SUCCESS_MSG');
			$this->setRedirect('index.php?option=com_enmasse&controller='.JRequest::getVar('controller'), $msg);
		}
		else
		{
			$msg = JText::_('SAVE_ERROR_MSG') .": " . $model->getError();
			if($data['id'] == null)
				$this->setRedirect('index.php?option=com_enmasse&controller='.JRequest::getVar('controller').'&task=add', $msg, 'error');
			else
				$this->setRedirect('index.php?option=com_enmasse&controller='.JRequest::getVar('controller').'&task=edit&cid[0]='. $data['id'], $msg, 'error');
		}
		//------------------------------
		//  send email and update total sold qty for each case of when change status of order
	    if( $orderData->status == 'Paid')
	    {
	    	EnmasseHelper::doNotify($orderData->id); 	
  	    	$msg = JTEXT::_('SAVE_SUCCESS_MSG_AND_SEND_RECEIPT');
	    }
	    else if($orderData->status == 'Refunded')
	    {
	    	/*$orderItemList = JModel::getInstance('orderItem','enmasseModel')->listByOrderId($orderData->id);
	   		foreach($orderItemList as $orderItem)
			{
				EnmasseHelper::orderItemRefunded($orderItem->id);
				sleep(1);
			}*/	
			$msg = JTEXT::_('Queued for Refund, Please see Pending Refund option');    

	    }
	    else if($orderData->status == 'Delivered')
	    {
	    	$orderItemList = JModel::getInstance('orderItem','enmasseModel')->listByOrderId($orderData->id);
	   		foreach($orderItemList as $orderItem)
			{
				EnmasseHelper::orderItemDelivered($orderItem->id);
				sleep(1);
			}
			$msg = JTEXT::_('SAVE_SUCCESS_MSG_AND_SEND_DELIVERY');
	    }	
	
	    
		$this->setRedirect('index.php?option=com_enmasse&controller=order', $msg);
	}
	
	//Amol
	function refundPaypal()
	{	
		$orderData->id 			= JRequest::getVar( 'id', '', 'post', 'text', JREQUEST_ALLOWRAW );
		$orderData->description = JRequest::getVar( 'description', '', 'post', 'text', JREQUEST_ALLOWRAW );
		$orderData->status 		= JRequest::getVar( 'status', '', 'post', 'text', JREQUEST_ALLOWRAW );
		$orderData->status2 		= JRequest::getVar( 'status2', '', 'post', 'text', JREQUEST_ALLOWRAW );
		$orderData->buyerid 		= JRequest::getVar( 'buyerid', '', 'post', 'text', JREQUEST_ALLOWRAW );
		
		$msg = JText::_('Something Wrong');		
		
		if($orderData->status == 'Refunded' && $orderData->status2 == 'Refund') {
		
			$order = JModel::getInstance('order','enmasseModel')->getById($orderData->id);
			if(count($order) && $order->buyer_id == $orderData->buyerid && $order->status == 'Refunded' ) {
			
				$RefundTypePaypal = json_decode($order->pay_detail);
					if($RefundTypePaypal && $order->refunded_amount == 0) {	
					
							if($RefundTypePaypal->txn_id && $RefundTypePaypal->payment_status == "Completed") {	
														
							    $emailContent="Refund Summary <br>";
								$emailContent.="<br>Order ID: ".$orderData->id;
								$emailContent.="<br>User ID: ".$orderData->buyerid;
								
								require_once( JPATH_COMPONENT_SITE.DS."helpers". DS ."payGty". DS ."paypal". DS ."PayGtyPaypal.class.php");								
								
								$restRes = PayGtyPaypal::refundTxn($RefundTypePaypal->txn_id, 'Full', $RefundTypePaypal->mc_currency, $RefundTypePaypal->mc_gross, '');
								 if($restRes["success"]){
										$msg = "Paypal Refund Successfull ".$restRes["msg"];
									 $amountUpdated = JModel::getInstance('order','enmasseModel')->updateAmountRefunded($orderData->id, $order->total_buyer_paid /*$restRes["netRefundAmt"]*/);
										
									$orderItemList = JModel::getInstance('orderItem','enmasseModel')->listByOrderId($orderData->id);
										foreach($orderItemList as $orderItem)
										{
											EnmasseHelper::orderItemRefunded($orderItem->id);											
										}		
										
										 $emailContent.="<br>Refund TXN ID: ".$restRes["refTxnId"];
										 $emailContent.="<br>Net Amount Refunded: ".$RefundTypePaypal->mc_gross;
										 $emailContent.="<br>Correlation Id: ".$restRes["corRelId"];
										 if(!$amountUpdated){
										    $emailContent.="<br><br>* Update Function Error :: Amount Not updated in the Database.";
										 }
										
								 } else {
								       $msg = $restRes["msg"];
									    $emailContent.="<br>Message: ".$restRes["msg"];
								 }
									
								       //Customer Care
											$db 	=& JFactory::getDBO();
											$query = "SELECT * FROM #__enmasse_setting ";
											$db->setQuery( $query );
											$setting = $db->loadObject();
											$setting->customer_support_email;
										if(!EnmasseHelper::sendMail($setting->customer_support_email, "Paypal Refund Summary",$emailContent ))
										{
                                           $msg .= "<br>Refund Summary Email Failed To customer Service";
										}										
							} 							
					} else {
						         $msg = "Manual Refund Successfull ";
								 $amountUpdated = JModel::getInstance('order','enmasseModel')->updateAmountRefunded($orderData->id, $order->total_buyer_paid);
									 if(!$amountUpdated){
										    //$emailContent.="<br><br>* Update Function Error :: Amount Not updated in the Database.";
											 $msg = "Update Function Error :: Amount Not updated in the Database.";
										 }	
									$orderItemList = JModel::getInstance('orderItem','enmasseModel')->listByOrderId($orderData->id);
										foreach($orderItemList as $orderItem)
										{
											EnmasseHelper::orderItemRefunded($orderItem->id);											
										}		
					}
				
			}
			
		}
		
		$this->setRedirect('index.php?option=com_enmasse&controller=order', $msg);
	}
	
	
	//Amol
	function paypalArchive()
	{	
		$orderData->id 			= JRequest::getVar( 'id', '', 'post', 'text', JREQUEST_ALLOWRAW );
		$orderData->description = JRequest::getVar( 'description', '', 'post', 'text', JREQUEST_ALLOWRAW );
		$orderData->status 		= JRequest::getVar( 'status', '', 'post', 'text', JREQUEST_ALLOWRAW );
		$orderData->status2 	= JRequest::getVar( 'status2', '', 'post', 'text', JREQUEST_ALLOWRAW );		
		$orderData->buyerid 		= JRequest::getVar( 'buyerid', '', 'post', 'text', JREQUEST_ALLOWRAW );
		$msg = JText::_('Something Wrong');	
		
		if($orderData->id && $orderData->status && $orderData->status2 && $orderData->buyerid ){
				
				$order = JModel::getInstance('order','enmasseModel')->getById($orderData->id);
				if($order && $order->status == "Unpaid" && $order->pay_gty_id == 2   ) {
					$db =& JFactory::getDBO();				
						$query = "	DELETE 
									FROM 
									#__enmasse_order 
									WHERE
									id = $orderData->id LIMIT 1";
						$db->setQuery( $query );
						$db->query();
						if ($db->getErrorNum()) {
							JError::raiseError( 500, $db->stderr() );
							return false;
						}
							
						$query = "	DELETE 
									FROM 
									#__enmasse_order_item 
									WHERE
									order_id = $orderData->id LIMIT 1";
						$db->setQuery( $query );
						$db->query();
						if ($db->getErrorNum()) {
							JError::raiseError( 500, $db->stderr() );
							return false;
						}
						
					$msg = JText::_('Deleted Successfully');		
				}			
				$this->setRedirect('index.php?option=com_enmasse&controller=order&filter[status]=PaypalArchive', $msg);
		}

		
	}	
	
	
	
}
?>