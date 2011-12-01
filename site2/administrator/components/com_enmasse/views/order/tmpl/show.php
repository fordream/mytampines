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

$rows = $this -> orderList;
$option = 'com_enmasse';
//echo $this->sDealStatus;
require_once( JPATH_ADMINISTRATOR . DS ."components". DS ."com_enmasse".DS."helpers". DS ."EnmasseHelper.class.php");



$filter = $this->filter;
$emptyJOpt = JHTML::_('select.option', '', JText::_('') );

$statusJOptList = array();
array_push($statusJOptList, $emptyJOpt);
foreach ($this->statusList as $key=>$name)
{
	$var = JHTML::_('select.option', $key, JText::_('ORDER_'.strtoupper($key)) );
	array_push($statusJOptList, $var);
}

/* AMol Started */
	$filterOk =  false;
	if($filter['status'] == "Refund") {
		$filterOk =  true;
	}

	$filterArchive =  false;
	if($filter['status'] == "PaypalArchive") {
		$filterArchive =  true;
	}

	jimport('joomla.utilities.date');
	$dateNow = new JDate(JHTML::_('date', date(), JText::_('%Y-%m-%d %H:%M:%S')));
		$currTime = $dateNow->toUnix();

		$payPalConfig = JModel::getInstance('paygty','enmasseModel')->getById(2);

		$payPalConfigObj = json_decode($payPalConfig->attribute_config);
		$payPalExpTime = $payPalConfigObj->pending_payment_life * 60 * 60;

/* AMol Ended */
?>
<form action="index.php">
<table>
	<tr>
		<td>
			<div style="float: left; margin-right: 10px;">
			<b><?php echo JText::_('ORDER_STATUS')?>: </b>
			<?php echo JHTML::_('select.genericList', $statusJOptList, 'filter[status]', null , 'value', 'text', $filter['status']);?>
			</div>

			<div style="float: left; margin-right: 10px;">
			<b><?php echo JText::_('ORDER_DEAL_NAME')?>: </b>
			<input type="text" name="filter[deal_name]" value="<?php echo $filter['deal_name']; ?>" />
			</div>

			<input type="submit" value="<?php echo JText::_('ORDER_SEARCH')?>" />
			<input type="button" value="<?php echo JText::_('ORDER_RESET')?>" onClick="location.href='index.php?option=com_enmasse&controller=order'" />
		</td>
	</tr>
</table>
<input type="hidden" name="controller" value="order" />
<input type="hidden" name="option" value="com_enmasse" />
</form>

<form action="index.php" method="post" name="adminForm">
<table class="adminlist">
	<thead>
		<tr>
			<th class="title" width='5px'><?php echo JText::_('ORDER_ID')?></th>
			<th width='80px'><?php echo JText::_('ORDER_TOTAL_PAID')?> </th>
			<th><?php echo JText::_('ORDER_DEAL_NAME')?></th>
			<th><?php echo JText::_('ORDER_QUANTITY')?></th>
			<th><?php echo JText::_('ORDER_BUYER_DETAIL')?></th>
			<th><?php echo JText::_('ORDER_PAYMENT_DETAIL')?></th>
			<th width="5" nowrap="nowrap"><?php echo JText::_('ORDER_STATUS')?></th>
			<th><?php echo JText::_('ORDER_COMMENT')?></th>
			<th><?php echo JText::_('CREATED_AT')?></th>
			<th><?php echo JText::_('UPDATED_AT')?></th>
		</tr>
	</thead>
	<?php
	//print_r(count($rows));
	for ($i=0; $i < count( $rows ); $i++)
	{
		$k = $i % 2;		
		$row = &$rows[$i];
		//echo $row->refunded_amount."-".$row->status."<br />";
		if($row->refunded_amount == 0 && $row->status == "Refunded" && !$filterOk) continue;
		if($row->refunded_amount != 0 && $row->status == "Refunded" && $filterOk) continue;
		
			$dateNow = new JDate($row->updated_at);
			$expTimeSpan = $currTime - $dateNow->toUnix();
			$toDisp = ($expTimeSpan < $payPalExpTime)?false:true; 
		  /* 
		    if($row->status != 'Unpaid' && $row->pay_gty_id != 2 && !$filterArchive) continue;		
		    if($filterArchive==true && $toDisp==false){				
				continue;
			}
		*/	
			
			 if($filterArchive){
			            if($row->status != 'Unpaid' && $row->pay_gty_id != 2 ) continue;
						
						if($toDisp == false) continue;							
			 }
				
		$link =  JRoute::_('index.php?option=' . $option .'&controller=order'.'&task=edit&orderId='. $row->id.'&kstatus='.$filter['status'] ) ;
		 $refundText = JTEXT::_('ORDER_'.strtoupper($row->status));
		
		/* Amol*/
			if($filterOk) {
			   //$refundText="";			   
				$RefundTypePaypal = json_decode($row->pay_detail);
					if ($RefundTypePaypal || !$row->refunded_amount) {
										
						if ($RefundTypePaypal) {
							if($RefundTypePaypal->txn_id && $RefundTypePaypal->payment_status == "Completed") {								
								$refundText="Pending<br />[Paypal]";
							}
						} else {
							$refundText="Pending<br />[Manual]";
						}					
					}
			}
		/*Amol End*/	
	?>
	<tr class="<?php echo "row$k"; ?>">
		<td align="center">
			<a href="<?php echo $link?>"><?php echo EnmasseHelper::displayOrderDisplayId($row->id) ; ?></a>
		</td>
		<td align="right">
			<?php echo EnmasseHelper::displayCurrency($row->total_buyer_paid); ?>
		</td>
		<td>
			<?php echo $row->deal_name;?>
		</td>
		<td>
			<?php echo $row->qty;?>
		</td>
		<td>
			<?php echo EnmasseHelper::displayBuyer( json_decode($row->buyer_detail) ); ?>
		</td>
		<td nowrap="true">
			<?php echo EnmasseHelper::displayJson( $row->pay_detail ); ?>
		</td>
		<td align="center">
			<?php echo $refundText; //echo JTEXT::_('ORDER_'.strtoupper($row->status)); ?>
		</td>
		<td align="left">
			<?php echo $row->description; ?>
		</td>
		<td align="center"><?php echo DatetimeWrapper::getDisplayDatetime($row->created_at); ?></td>
		<td align="center"><?php echo DatetimeWrapper::getDisplayDatetime($row->updated_at); ?></td>
	</tr>
	<?php
	} 
	?>
	<tfoot>
    <tr>
      <td colspan="10"><?php echo $this->pagination->getListFooter(); ?></td>
    </tr>
  </tfoot>
</table>
<input type="hidden" name="option" value="<?php echo $option;?>" />
<input type="hidden" name="controller" value="order" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="filter[status]" value="<?php echo $filter['status']; ?>" />
<input type="hidden" name="filter[deal_name]" value="<?php echo $filter['deal_name']; ?>" />
</form>
