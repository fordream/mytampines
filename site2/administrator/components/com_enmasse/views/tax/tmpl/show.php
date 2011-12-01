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
 
$rows = $this -> taxList;
$option = 'com_enmasse';
?>
<form action="index.php" method="post" name="adminForm">
<table class="adminlist">
	<thead>
		<tr>
			<th width="5"><input type="checkbox" name="toggle" value=""
				onclick="checkAll(<?php echo count( $rows ); ?>);" /></th>
			<th width="10%">Name</th>
			<th width="10%">Rate</th>
			<th width="5" nowrap="nowrap">Published</th>
			<th >Created At</th>
			<th >Updated At</th>
		</tr>
	</thead>
	<?php
	//                 jimport('joomla.filter.output');
	for ($i=0; $i < count( $rows ); $i++)
	{
		$k = $i % 2;
		$row = &$rows[$i];
		$checked = JHTML::_('grid.id', $i, $row->id );
		$published = JHTML::_('grid.published', $row, $i );
		$link =  JRoute::_('index.php?option=' . $option .'&controller=tax'.'&task=edit&cid[]='. $row->id) ;
		?>
	<tr class="<?php echo "row$k"; ?>">
		<td><?php echo $checked; ?></td>
		<td><a href="<?php echo $link?>"><?php echo $row->name; ?></a></td>
		<td align="center"><?php echo $row->tax_rate; echo ' %'; ?></td>
		<td align="center"><?php echo $published;?></td>
		<td><?php echo DatetimeWrapper::getDisplayDatetime($row->created_at); ?></td>
		<td><?php echo DatetimeWrapper::getDisplayDatetime($row->updated_at); ?></td>
	</tr>
	<?php
	} ?>
</table>
<input type="hidden" name="option" value="<?php echo $option;?>" />
<input type="hidden" name="controller" value="tax" />
<input type="hidden" name="task" value="" />

<input type="hidden" name="boxchecked" value="0" />
</form>
