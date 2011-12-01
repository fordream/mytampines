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

$rows = $this->emailTemplateList;
$option = 'com_enmasse';
?>
<div>
<form action="index.php" method="post" name="adminForm">
<table class="adminlist">
	<thead>
		<tr>
			<th width="5"><input type="checkbox" name="toggle" value=""
				onclick="checkAll(<?php echo count( $rows ); ?>);" /></th>
			<th></th>
			<th><?php echo JText::_('EMAIL_SUBJECT');?></th>
			<th><?php echo JText::_('EMAIL_AVAILABLE_ATTRIBUTES');?></th>
		</tr>
	</thead>
	<?php
	for ($i=0; $i < count( $rows ); $i++)
	{
		$k = $i % 2;
		
		$row = &$rows[$i];
		$checked = JHTML::_('grid.id', $i, $row->id );
		$link =  JRoute::_('index.php?option=' . $option .'&controller=emailTemplate'.'&task=edit&cid[]='. $row->id) ;
	?>
	<tr class="<?php echo "row$k"; ?>">
		<td><?php echo $checked; ?></td>
		<td><a href="<?php echo $link?>"><?php echo $row->slug_name; ?></a></td>
		<td><a href="<?php echo $link?>"><?php echo $row->subject; ?></a></td>
		<td><?php echo $row->avail_attribute; ?></td>
	</tr>
	<?php
	} 
	?>
</table>
<input type="hidden" name="option" value="<?php echo $option;?>" />
<input type="hidden" name="controller" value="emailTemplate" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
</form>
</div>
