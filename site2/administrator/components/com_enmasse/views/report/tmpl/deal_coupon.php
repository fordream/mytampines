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
$orderItemList = $this->orderItemList;
$emptyJOpt = JHTML::_('select.option', '', JText::_('DEAL_COUPON_SELECTION_MSG') );

$dealJOptList = array();
array_push($dealJOptList, $emptyJOpt);
foreach ($this->dealList as $item)
{
	$var = JHTML::_('select.option', $item->id, JText::_($item->name) );
	array_push($dealJOptList, $var);
}
?>

<form action="index.php">
<table>
	<tr>
		<td>
			<div style="float: left; margin-right: 10px;">
			<b>Deal: </b>
			<?php echo JHTML::_('select.genericList', $dealJOptList, 'filter[deal_id]', null , 'value', 'text', $this->filter['deal_id']);?>
			</div>

			<input type="submit" value="<?php echo JTEXT::_('REPORT_SEARCH_BUTTON');?>" />
			<input type="button" value="<?php echo JTEXT::_('REPORT_RESET_BUTTON');?>" onClick="location.href='index.php?option=com_enmasse&controller=report'" />
		</td>
	</tr>
</table>
<input type="hidden" name="controller" value="report" />
<input type="hidden" name="option" value="com_enmasse" />
</form>

<form ction="index.php" name="adminForm">
<table class="adminlist">
	<thead>
		<tr>
			<th width="5%"><?php echo JTEXT::_('REPORT_SERIAL');?></th>
			<th width="15%"><?php echo JTEXT::_('REPORT_BUYER_NAME');?></th>
			<th width="15%"><?php echo JTEXT::_('REPORT_BUYER_MAIL');?></th>
			<th width="15%"><?php echo JTEXT::_('REPORT_DELIVERY_NAME');?></th>
			<th width="15%"><?php echo JTEXT::_('REPORT_DELIVERY_MAIL');?></th>
			<th width="15%"><?php echo JTEXT::_('REPORT_ORDER_COMMENT');?></th>
			<th width="10%"><?php echo JTEXT::_('REPORT_PURCHASE_DATE');?></th>
			<th width="5%"><?php echo JTEXT::_('REPORT_COUPON_SERIAL');?></th>
			<th width="5%"><?php echo JTEXT::_('REPORT_COUPON_STATUS');?></th>
		</tr>
	</thead>
	<?php
	$count = 1;
	for($i=0; $i < count($orderItemList); $i++)
	{
		$orderItem = $orderItemList[$i];
		$buyerDetail = json_decode($orderItem->order->buyer_detail);
		$deliveryDetail = json_decode($orderItem->order->delivery_detail);

		for($j=0; $j < count($orderItem->invtyList); $j++)
		{
			$invty = $orderItem->invtyList[$j];
			?>
	<tr>
		<td><?php echo $count++; ?>
		<td><?php echo $buyerDetail->name; ?></td>
		<td><?php echo $buyerDetail->email; ?></td>
		<td><?php echo $deliveryDetail->name; ?></td>
		<td><?php echo $deliveryDetail->email; ?></td>
		<td><?php echo $orderItem->order->description; ?></td>
		<td align="center"><?php echo DatetimeWrapper::getDisplayDatetime($orderItem->created_at); ?></td>
		<td align="center"><?php echo $invty->name; ?></td>
		<td align="center"><?php echo JTEXT::_('COUPON_'.strtoupper($invty->status)); ?></td>
	</tr>
	<?php
		}
	}
	?>
	<tfoot>
    <tr>
      <td colspan="16"><?php echo $this->pagination->getListFooter(); ?></td>
    </tr>
  </tfoot>
</table>
<input type="hidden" name="option" value="com_enmasse" />
<input type="hidden" name="controller" value="report" />
<input type="hidden" name="task" value="dealList" />
<input type="hidden" name="filter[deal_id]" value="<?php echo $this->filter['deal_id'];?>" />
</form>

<br>

<form name='excelExport' method="post">
 <input type="hidden" name="option" value="com_enmasse" />
 <input type="hidden" name="controller" value="report" />
 <input type="hidden" name="task" value="generateReport" />
 <input type="hidden" name="dealId" value='<?php echo $this->filter['deal_id'];?>' />
 <input type="submit" value="<?php echo JTEXT::_('REPORT_TO_EXCEL_BUTTON');?>" />
</form>
