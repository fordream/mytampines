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

?>
<div style="">
<table>
	<tr>
		<td align="center" valign="top">
		<?php $link = JRoute::_("index.php?option=com_enmasse&controller=setting&cid=1");
		echo "&nbsp;<a href=\"$link\"><a href=\"$link\"><img src=\"components/com_enmasse/images/setting.png\" /></a>"; ?>
		<br/>
		<b><?php echo JText::_('S_SETTING');?></b>
		</td>

		<td align="center" valign="top">
		<?php $link = JRoute::_("index.php?option=com_enmasse&controller=category");
		echo "&nbsp;<a href=\"$link\"><img src=\"components/com_enmasse/images/category_management.png\" /></a>"; ?>
		<br/>
		<b><?php echo JText::_('S_CATEGORY');?></b>
		</td>

		<td align="center" valign="top">
		<?php $link = JRoute::_("index.php?option=com_enmasse&controller=location");
		echo "&nbsp;<a href=\"$link\"><img src=\"components/com_enmasse/images/locations.png\" /></a>"; ?>
		<br/>
		<b><?php echo JText::_('S_LOCATION');?></b>
		</td>

		<td align="center" valign="top">
		<?php $link = JRoute::_("index.php?option=com_enmasse&controller=payGty");
		echo "&nbsp;<a href=\"$link\"><img src=\"components/com_enmasse/images/payment_gateways.png\" /></a>"; ?>
		<br/>
		<b><?php echo JText::_('S_PAY_GATEWAY');?></b>
		</td>
		
		<td align="center" valign="top">
		<?php $link = JRoute::_("index.php?option=com_enmasse&controller=coupon");
		echo "&nbsp;<a href=\"$link\"><img src=\"components/com_enmasse/images/coupon_editor.png\" /></a>"; ?>
		<br/>
		<b><?php echo JText::_('S_COUPON_EDITOR');?></b>
		</td>
		
		<td align="center" valign="top">
		<?php $link = JRoute::_("index.php?option=com_enmasse&controller=emailTemplate");
		echo "&nbsp;<a href=\"$link\"><img src=\"components/com_enmasse/images/email_template.png\" /></a>"; ?>
		<br/>
		<b><?php echo JText::_('S_EMAIL_TEMPLATE');?></b>
		</td>
	</tr>
<tr><td colspan="6" height="10"></td></tr>
	<tr>
		<td align="center" valign="top">
		<?php $link = JRoute::_("index.php?option=com_enmasse&controller=salesPerson");
		echo "&nbsp;<a href=\"$link\"><img src=\"components/com_enmasse/images/salesperson.png\" /></a>"; ?>
		<br/>
		<b><?php echo JText::_('S_SALE_PERSON');?></b>
		</td>

		<td align="center" valign="top">
		<?php $link = JRoute::_("index.php?option=com_enmasse&controller=merchant");
		echo "&nbsp;<a href=\"$link\"><img src=\"components/com_enmasse/images/merchant_management.png\" /></a>"; ?>
		<br/>
		<b><?php echo JText::_('S_MERCHANT');?></b>
		</td>

		<td align="center" valign="top">
		<?php $link = JRoute::_("index.php?option=com_enmasse&controller=deal");
		echo "&nbsp;<a href=\"$link\"><img src=\"components/com_enmasse/images/deal_management.png\" /></a>"; ?>
		<br/>
		<b><?php echo JText::_('S_DEAL');?></b>
		</td>

		<td align="center" valign="top">
		<?php $link = JRoute::_("index.php?option=com_enmasse&controller=order");
		echo "&nbsp;<a href=\"$link\"><img src=\"components/com_enmasse/images/order_management.png\" /></a>"; ?>
		<br/>
		<b><?php echo JText::_('S_ORDER');?></b>
		</td>

		<td align="center" valign="top">
		<?php $link = JRoute::_("index.php?option=com_enmasse&controller=report");
		echo "&nbsp;<a href=\"$link\"><img src=\"components/com_enmasse/images/reports.png\" /></a>"; ?>
		<br/>
		<b><?php echo JText::_('S_REPORT');?></b>
		</td>
		
		<td align="center" valign="top">
		<?php $link = JRoute::_("index.php?option=com_enmasse&controller=help");
		echo "&nbsp;<a href=\"$link\"><img src=\"components/com_enmasse/images/help.png\" /></a>"; ?>
		<br/>
		<b><?php echo JText::_('S_HELP');?></b>
		</td>

	</tr>
</table>
</div>

