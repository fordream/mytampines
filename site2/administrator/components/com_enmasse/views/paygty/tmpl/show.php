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

$rows = $this->payGtyList;
$option = 'com_enmasse';
?>
<form action="index.php" method="post" name="adminForm">
<table class="adminlist">
	<thead>
		<tr>
			<th width="5"><input type="checkbox" name="toggle" value=""
				onclick="checkAll(<?php echo count( $rows ); ?>);" /></th>
			<th width="300"><?php echo JText::_('PAY_NAME');?></th>
			<th width="5" nowrap="nowrap"><?php echo JText::_('PUBLISHED');?></th>
			<th width="400" nowrap="nowrap"><?php echo JText::_('GATEWAY_SETTING');?></th>
			<th><?php echo JText::_('UPDATED_AT');?></th>
		</tr>
	</thead>
	<?php 
		for ($i=0; $i < count( $rows ); $i++) 
		{
			$k = $i % 2;
			$row = &$rows[$i];
			$checked = JHTML::_('grid.id', $i, $row->id );
			$published = JHTML::_('grid.published', $row, $i );
			$link =  JRoute::_('index.php?option=' . $option .'&controller=payGty'.'&task=edit&cid[]='. $row->id) ;
	?>
	<tr class="<?php echo "row$k"; ?>">
		<td><?php echo $checked; ?></td>
		<td align="center">
			<a href="<?php echo $link?>"><?php echo $row->name; ?></a>
		</td>
		<td align="center"><?php echo $published;?></td>
		<td align="left"><?php echo EnmasseHelper::displayJson($row->attribute_config); ?>
		</td>
		<td><?php echo DatetimeWrapper::getDisplayDatetime($row->updated_at); ?></td>
	</tr>
	<?php
    	} 
	?>
</table>

<input type="hidden" name="option" value="<?php echo $option;?>" />
<input type="hidden" name="controller" value="payGty" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" /></form>