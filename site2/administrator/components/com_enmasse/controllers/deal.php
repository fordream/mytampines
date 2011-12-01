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
class EnmasseControllerDeal extends JController
{
	function display()
	{
		JRequest::setVar('view', 'deal');
		JRequest::setVar('layout', 'show');
		parent::display();
	}

	function edit()
	{
		JRequest::setVar('view', 'deal');
		JRequest::setVar('layout', 'edit');
		parent::display();
	}

	function add()
	{
		JRequest::setVar('view', 'deal');
		JRequest::setVar('layout', 'edit');
		parent::display();
	}

	function save()
	{
		$data = JRequest::get( 'post' );

        //$data['pdt_cat_id'] = JRequest::getVar( 'pdt_cat_id', '', 'post' );
        //print_r($data['pdt_cat_id']);die;

        $data['name'] = trim($data['name']);
		$data['slug_name'] 		= EnmasseHelper::seoUrl($data['name']);
		$data['description'] 	= JRequest::getVar( 'description', '', 'post', 'string', JREQUEST_ALLOWRAW );
		$data['highlight'] 		= JRequest::getVar( 'highlight', '', 'post', 'string', JREQUEST_ALLOWRAW );
		$data['terms'] 			= JRequest::getVar( 'terms', '', 'post', 'string', JREQUEST_ALLOWRAW );

        if($data['slug_name']=='_' || $data['slug_name'] =='')
        {
           $now = str_replace(":"," ",DatetimeWrapper::getDatetimeOfNow());
           $data['slug_name'] = EnmasseHelper::seoUrl($now);
        }

		$model = JModel::getInstance('deal','enmasseModel');

		//---------------------------------------------------------------
		// if edit deal
		if($data['id']!=0)
		{
			//---get deal data
			$deal = JModel::getInstance('deal','enmasseModel')->getById($data['id']);

			// get sold coupon qty for deal
			$soldCouponList = JModel::getInstance('invty','enmasseModel')->getSoldCouponByPdtId($deal->id);

		    //if from unlimited to limited
			if($deal->max_coupon_qty < 0  )
			{
				if($data['max_coupon_qty'] > 0)
				{
				    if($data['max_coupon_qty'] <= count($numOfSoldCoupon) )
					{
						$msg = JText::_('MSG_CURRENT_SOLD_GRATER_THAN_MODIFIED_COUPON');
						JFactory::getApplication()->redirect('index.php?option=com_enmasse&controller=deal&task=edit&cid='.$data['id'],$msg);
					}
					else
					{
						$numOfAddCoupon  = $data['max_coupon_qty']- count($numOfSoldCoupon);
					}
				}
			}
			else
			{   //---------------- if change from limited to unlimited
				if($data['max_coupon_qty'] < 0 )
				 	$unlimit = true;
				 //-------------------------change the coupon qty to less
				else if($data['max_coupon_qty'] < $deal->max_coupon_qty)
				{
					//---------------------- if new coupon qty <= the sold coupon qty
					if( $data['max_coupon_qty'] <= count($numOfSoldCoupon) )
					{
						$msg = JText::_('MSG_CURRENT_SOLD_GRATER_THAN_MODIFIED_COUPON');
						JFactory::getApplication()->redirect('index.php?option=com_enmasse&controller=deal&task=edit&cid='.$data['id'],$msg);
					}
					else
					{
						$numOfRemoveCoupon = $deal->max_coupon_qty -  $data['max_coupon_qty'];
					}
				} //------------------ if change to coupon qty to greater qty
				else if($data['max_coupon_qty'] > $deal->max_coupon_qty)
					$numOfAddCoupon = $data['max_coupon_qty'] - $deal->max_coupon_qty;
			}

		}

			//------------------------
		//gemerate integration class
		 $integrateFileName = EnmasseHelper::getSubscriptionClassFromSetting().'.class.php';
		 $integrationClass = EnmasseHelper::getSubscriptionClassFromSetting();
		 require_once (JPATH_SITE . DS ."components". DS ."com_enmasse". DS ."helpers". DS ."subscription". DS .$integrationClass. DS.$integrateFileName);
		 $integrationObject = new $integrationClass();

		// store data
		$row = $model->store($data);

		if ($row->success)
		{
//			if($data['id'] == 0)
				$integrationObject->integration($row,'newDeal');
			//--------------------------------------
			// store location and category
			JModel::getInstance('dealCategory','enmasseModel')->store($row->id,$data['pdt_cat_id']);
			JModel::getInstance('dealLocation','enmasseModel')->store($row->id,$data['location_id']);

			// if is new deal and limited the coupon then create coupon in invty
			if($data['id']==0 && $row->max_coupon_qty > 0)
			{
				for($i=0 ; $i < $row->max_coupon_qty; $i++)
				{

					$name = $i+1;
					JModel::getInstance('invty','enmasseModel')->generateCouponFreeStatus($row->id,$name,'Free');
				}

			}
			else if($data['id']!=0)
			{
				if(!empty($numOfRemoveCoupon))
				{
					$freeCouponList = JModel::getInstance('invty','enmasseModel')->getCouponFreeByPdtID($data['id']);
					// removed the coupons from invty
					for($i=0; $i < $numOfRemoveCoupon ; $i++)
					{
						JModel::getInstance('invty','enmasseModel')->removeById($freeCouponList[$i]->id);
					}
				}
				else if(!empty($numOfAddCoupon))
				{
					// add more coupon to invty
					for($i=0; $i < $numOfAddCoupon ; $i++)
					{
						$name = $i+1;
						JModel::getInstance('invty','enmasseModel')->generateCouponFreeStatus($data['id'],$name,'Free');
					}
				}
				else if($unlimit)
				{
					//remove all free coupon
					JModel::getInstance('invty','enmasseModel')->removeCouponByPdtIdAndStatus($data['id'],'Free');

				}

			}
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
	}

	function control()
	{
		$this->setRedirect('index.php?option=com_enmasse');
	}

	function remove()
	{
		$cids = JRequest::getVar( 'cid', array(0), 'post', 'array' );

		$model = JModel::getInstance('deal','enmasseModel');

		if($model->deleteList($cids))
		{
			$this->refreshOrder();
			$msg = JText::_('DELETE_SUCCESS_MSG');
			$this->setRedirect('index.php?option=com_enmasse&controller='.JRequest::getVar('controller'), $msg );
		}
		else
		{
			$msg = JText::_('DELETE_ERROR_MSG') .": " . $model->getError();
			$this->setRedirect('index.php?option=com_enmasse&controller='.JRequest::getVar('controller'), $msg, 'error');
		}
	}

	function publish()
	{
		EnmasseHelper::changePublishState(1,'enmasse_deal','deal','deal');
	}
	function unpublish()
	{
		EnmasseHelper::changePublishState(0,'enmasse_deal','deal','deal');
	}

	function approveDeal()
	{
		$db =& JFactory::getDBO();
		$option ='com_enmasse';
		$cid = JRequest::getVar( 'cid', array(), '', 'array' );
		$application = JFactory::getApplication();

		if($cid == null || count($cid)==0)
		{
			$application ->redirect('index.php?option='.$option.'&controller=deal', JText::_('CHOOSE_DEAL_TO_APPROVE'),'error');
		}

		for ($i=0; $i < count($cid); $i++)
		{
			$status = JModel::getInstance('deal','enmasseModel')->getStatus($cid[$i]);
			if($status != "Pending")
			{
				$msg = JText::_('CHOOSE_PENDING_DEAL_TO_CONFIRM');
				$application ->redirect('index.php?option=' . $option.'&controller=deal', $msg,'error');
			}
		}

		for ($i=0; $i < count($cid); $i++)
			$status = JModel::getInstance('deal','enmasseModel')->updateStatus($cid[$i], "On Sales");

		$application->redirect('index.php?option='.$option.'&controller=deal', JText::_('APPROVE_DEAL_SUCCESS_MSG'));
	}

	function voidDeal()
	{
		$db =& JFactory::getDBO();
		$option = 'com_enmasse';
		$cid = JRequest::getVar( 'cid', array(), '', 'array' );
		$appication = JFactory::getApplication();

		if($cid == null)
			$appication->redirect('index.php?option=' . $option.'&controller=deal', JText::_('CHOOSE_DEAL_TO_VOID'),'error');

		for ($i=0; $i < count($cid); $i++)
		{
			$status = JModel::getInstance('deal','enmasseModel')->getStatus($cid[$i]);

			if($status == "Voided")
				$appication ->redirect('index.php?option=' . $option.'&controller=deal', JText::_('CANNOT_VOID_VOIDED_DEAL_MSG'),'error');
			elseif($status == "Confirmed")
				$appication ->redirect('index.php?option=' . $option.'&controller=deal', JText::_('CANNOT_VOID_CONFIRMED_DEAL'),'error');
			elseif($status == "Pending")
				$appication ->redirect('index.php?option=' . $option.'&controller=deal', JText::_('CANNOT_VOID_PENDING_DEAL'),'error');

		}


		for ($i=0; $i < count($cid); $i++)
		{
			$dealId = $cid[$i];

			$orderItemList = JModel::getInstance('orderItem','enmasseModel')->listByPdtIdAndStatus($dealId, "Paid");

			foreach($orderItemList as $orderItem)
			{
				EnmasseHelper::orderItemRefunded($orderItem->id);
				sleep(1);
			}
			/// update status
			JModel::getInstance('deal','enmasseModel')->updateStatus($dealId , 'Voided');
			//Integration with point system, refund point automatically if users paid with point
			if(EnmasseHelper::isPointSystemEnabled())
			{
				//generate integration class
				$integrationClass = EnmasseHelper::getPointSystemClassFromSetting();
				$integrateFileName = $integrationClass.'.class.php';
				require_once (JPATH_SITE . DS ."components". DS ."com_enmasse". DS ."helpers". DS ."pointsystem". DS .$integrationClass. DS.$integrateFileName);
				$buyerList = EnmasseHelper::getBuyerRefundVoidDeal($dealId);
				$integrationObject = new $integrationClass();
				foreach($buyerList as $buyer)
				{
					$integrationObject->integration($buyer['buyer_id'],'refunddeal',$buyer['point_used_to_pay']);
				}
			}
		}
		$this->unpublish();
		$appication->redirect('index.php?option='.$option.'&controller=deal', JText::_('VOID_DEAL_SUCCESS_MSG'));
	}


	function confirmDeal()
	{
		$db =& JFactory::getDBO();
		$option ='com_enmasse';
		$cid = JRequest::getVar( 'cid', array(), '', 'array' );
		$name = "";
		$appication = JFactory::getApplication();

		if($cid == null)
			$appication->redirect('index.php?option='.$option.'&controller=deal', JText::_('CHOOSE_DEAL_TO_CONFIRM'),'error');

		for ($i=0; $i < count($cid); $i++)
		{
			$status = JModel::getInstance('deal','enmasseModel')->getStatus($cid[$i]);

			if($status == "Confirmed")
				$appication ->redirect('index.php?option=' . $option.'&controller=deal', JText::_('CANNOT_CONFIM_DEAL_CONFIRMED'),'error');
			elseif($status == "Voided")
				$appication ->redirect('index.php?option=' . $option.'&controller=deal', JText::_('CANNOT_CONFIM_DEAL_VOIDED'),'error');
			elseif($status == "Pending")
				$appication ->redirect('index.php?option=' . $option.'&controller=deal', JText::_('CANNOT_CONFIM_DEAL_PENDING'),'error');

			$dealModel = JModel::getInstance('deal','enmasseModel')->getById($cid[$i]); //Amol
				//Amol
			if($dealModel->cur_sold_qty < $dealModel->min_needed_qty)
				$appication ->redirect('index.php?option=' . $option.'&controller=deal', JText::_('Minimum Needed Quantity to Open the Deal Not Met!'),'error');

		}

			//------------------------
			//generate integration class
			$isPointSystemEnabled = EnmasseHelper::isPointSystemEnabled();
			if($isPointSystemEnabled==true)
			{
				$integrationClass = EnmasseHelper::getPointSystemClassFromSetting();
				$integrateFileName = $integrationClass.'.class.php';
				require_once (JPATH_SITE . DS ."components". DS ."com_enmasse". DS ."helpers". DS ."pointsystem". DS .$integrationClass. DS.$integrateFileName);
			}

		for ($i=0 ; $i < count($cid); $i++)
		{
			$dealId = $cid[$i];

			$orderItemList = JModel::getInstance('orderItem','enmasseModel')->listByPdtIdAndStatus($dealId, "Paid");

			foreach($orderItemList as $orderItem)
			{
				EnmasseHelper::orderItemDelivered($orderItem->id); //Amol Rem


						$buyerId = EnmasseHelper::getUserIdByOrderId($orderItem->id);
						$referralId = EnmasseHelper::getReferralIdByOrderId($orderItem->id);
					if($referralId) 	//Amol
					{
						//------------------------
						//generate integration class
						if($isPointSystemEnabled==true)
						{
								$query = "	SELECT
												count(*)
											FROM
												#__enmasse_points
											WHERE
												bid = $buyerId AND
												rid = $referralId ";



								$db->setQuery( $query );
								$rowCount = $db->loadResult();



							if($rowCount < 1) {

										$integrationObject = new $integrationClass(); //amol 1
										$integrationObject->integration($buyerId,'confirmdeal'); //amol 2
										$integrationObject->integration($referralId,'referralbonus');


									$query = "INSERT INTO `#__enmasse_points` (`bid`, `rid`, `oid`) VALUES ($buyerId, $referralId, $orderItem->id)";
									// error_log($query);
									$db->setQuery( $query );
									$db->query();
							}

						}
					}


				sleep(1);
			}
			JModel::getInstance('deal','enmasseModel')->updateStatus($dealId, 'Confirmed');

			/*if($isPointSystemEnabled==true) //Refund point to who paid with point but without paying by cash
			{
				$buyerList = EnmasseHelper::getBuyerRefundConfirmDeal($dealId);
				$integrationObject = new $integrationClass();
				foreach($buyerList as $buyer)
				{
					$integrationObject->integration($buyer['buyer_id'],'refunddeal',$buyer['point_used_to_pay']);
					error_log("5");
				}
			}*/
		}
		$appication-> redirect('index.php?option=' . $option.'&controller=deal', JText::_('CONFIRM_DEAL_SUCCESS_MSG'));
	}

//Amol Copy Deal
	function copyDeal()
	{
		$db =& JFactory::getDBO();
		$option ='com_enmasse';
		$cid = JRequest::getVar( 'cid', array(), '', 'array' );
		$application = JFactory::getApplication();

		if($cid == null || count($cid)==0)
		{
			$application ->redirect('index.php?option='.$option.'&controller=deal', JText::_('Choose deal to copy'),'error');
		}
		$isSuccess=true;
		$msg = "Copied Successfully";
		for ($i=0; $i < count($cid); $i++)
		{
			$dealModel = JModel::getInstance('deal','enmasseModel')->getById($cid[$i]); //Amol

						$slugVal =count(JModel::getInstance('deal','enmasseModel')->getListPosition());
						if(!$slugVal) $slugVal = 0;
						$slugVal+=1;
						$slugName = trim($dealModel->slug_name."_".$slugVal);


			$row = new stdClass();
				$row->id = null;
				$row->name = "Copy of ".$dealModel->name." $slugVal";

				//$row->slug_name =  trim("copy_of_".$dealModel->slug_name);
				$row->slug_name = $slugName;

				$row->short_desc = $dealModel->short_desc;
				$row->highlight = $dealModel->highlight;
				$row->pic_dir = $dealModel->pic_dir;
				$row->terms = $dealModel->terms;
				$row->description =	$dealModel->description;
				$row->origin_price = $dealModel->origin_price;
				$row->price = $dealModel->price;
				$row->min_needed_qty = $dealModel->min_needed_qty;
				$row->max_buy_qty = $dealModel->max_buy_qty;
				$row->max_coupon_qty = $dealModel->max_coupon_qty;
				$row->max_qty = $dealModel->max_qty;
				$row->cur_sold_qty = 0;
				$row->start_at = $dealModel->start_at;
				$row->end_at = $dealModel->end_at;
				$row->merchant_id = $dealModel->merchant_id;
				$row->sales_person_id = $dealModel->sales_person_id;
				$row->status = "Pending";
				$row->published = 0;
				$row->position = null;
				$row->created_at = $dealModel->created_at;
				$row->updated_at = $dealModel->updated_at;
				$row->is_side_deal = $dealModel->is_side_deal;

			$newRow = JModel::getInstance('deal','enmasseModel')->store($row);

			$isSuccess = $newRow->success;
				if(!$isSuccess) {
					$msg = "Error Copying :: ". $dealModel->name;
				}
		}

		JFactory::getApplication()->redirect("index.php?option=com_enmasse&controller=deal", $msg);

	}

	function refreshOrder($by=null)
	{
		JModel::getInstance('deal','enmasseModel')->refreshOrder($by=null);
	}
	function upPosition()
	{
		EnmasseControllerDeal::changePosition('com_enmasse', true);
	}
	function downPosition()
	{
		EnmasseControllerDeal::changePosition('com_enmasse', false);
	}
	function changePosition($option, $up)
	{
		// get current item
		$id = JRequest::getVar('id');
		$cur = JModel::getInstance('deal','enmasseModel')->getCurrentPosition($id);

		// get other item
		if ($up)
			$temp = $cur->position - 1;
		else
			$temp = $cur->position + 1;
		$other = JModel::getInstance('deal','enmasseModel')->getNextPosition($temp);

		// change position
		if ($up)
		{
			$cur->position --;
			$other->position ++;
		}
		else
		{
			$cur->position ++;
			$other->position --;
		}

		JModel::getInstance('deal','enmasseModel')->store($cur);
		if ($other->id)
			JModel::getInstance('deal','enmasseModel')->store($other);

		// redirect
		$msg = JText::_( "ORDER_POSITION_UPDATED");
		JFactory::getApplication()->redirect("index.php?option=com_enmasse&controller=deal", $msg);

	}

	function updatePosition()
	{
		$id = JRequest::getVar('id');
		$updatePosition = JRequest::getVar('updatePosition');
		$listPosition = JModel::getInstance('deal','enmasseModel')->getListPosition($id);
		$cur = JModel::getInstance('deal','enmasseModel')->getCurrentPosition($id);
		$other = JModel::getInstance('deal','enmasseModel')->getOtherPosition($id);

		if($updatePosition > $cur->position)
		{
			for($i=0; $i<count($other);$i++)
			{
				if($other[$i]->position <= $updatePosition && $other[$i]->position > $cur->position)
				{
					$other[$i]->position -=1;
					JModel::getInstance('deal','enmasseModel')->store($other[$i]);
				}
			}
		}
		if ($updatePosition < $cur->position)
		{
			for($z=0; $z<count($other);$z++)
			{
			if($other[$z]->position >= $updatePosition && $other[$z]->position < $cur->position)
				{
					$other[$z]->position +=1;
					JModel::getInstance('deal','enmasseModel')->store($other[$z]);
				}
			}
		}
		$cur->position = $updatePosition;
		JModel::getInstance('deal','enmasseModel')->store($cur);

		// redirect
		$msg = JText::_( "ORDER_POSITION_UPDATED");
		JFactory::getApplication()->redirect("index.php?option=com_enmasse&controller=deal", $msg);
	}

    function checkDuplicatedDeal()
	{

		$dealName = addslashes(JRequest::getVar("dealName"));
		$dealObj = JModel::getInstance('deal','enmasseModel')->getDealByName($dealName);
		if($dealObj != null)
		 echo 'true';
		else
		 echo 'false';
		die;

	}
}
?>