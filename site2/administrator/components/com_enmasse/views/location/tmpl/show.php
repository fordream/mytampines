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
 
$rows = $this->locationList;
$option = 'com_enmasse';
?>
<div>
<form action="index.php" method="post" name="adminForm">
<table class="adminlist">
	<thead>
		<tr>
			<th width="5"><input type="checkbox" name="toggle" value=""
				onclick="checkAll(<?php echo count( $rows ); ?>);" /></th>
			<th width="200"><?php  echo JHTML::_( 'grid.sort', JText::_('NAME'), 'name', $this->order['order_dir'], $this->order['order']); ?></th>
			<th width="300"><?php echo JText::_('DESC');?></th>
			<th width="5" nowrap="nowrap"><?php echo JText::_('PUBLISHED');?></th>
			<th ><?php  echo JHTML::_( 'grid.sort', JText::_('CREATED_AT'), 'created_at', $this->order['order_dir'], $this->order['order']); ?></th>
			<th ><?php  echo JHTML::_( 'grid.sort', JText::_('UPDATED_AT'), 'updated_at', $this->order['order_dir'], $this->order['order']); ?></th>
		</tr>
	</thead>
	<?php
	for ($i=0; $i < count( $rows ); $i++)
	{
		$k = $i % 2;
		$row = &$rows[$i];
		$checked = JHTML::_('grid.id', $i, $row->id );
		$published = JHTML::_('grid.published', $row, $i );
		$link =  JRoute::_('index.php?option=' . $option .'&controller=location'.'&task=edit&cid[]='. $row->id) ;
		?>
	<tr class="<?php echo "row$k"; ?>">
		<td><?php echo $checked; ?></td>
		<td><a href="<?php echo $link?>"><?php echo $row->name; ?></a></td>
		<td><?php echo $row->description; ?></td>
		<td align="center"><?php echo $published;?></td>
		<td><?php echo DatetimeWrapper::getDisplayDatetime($row->created_at); ?></td>
		<td><?php echo DatetimeWrapper::getDisplayDatetime($row->updated_at); ?></td>
	</tr>
	<?php
	} ?>
	<tfoot>
    <tr>
      <td colspan="9"><?php echo $this->pagination->getListFooter(); ?></td>
    </tr>
  </tfoot>
</table>
<input type="hidden" name="option" value="<?php echo $option;?>" />
<input type="hidden" name="controller" value="location" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="filter_order" value="<?php echo $this->order['order']; ?>" />
<input type="hidden" name="filter_order_Dir" value="<?php echo $this->order['order_dir']; ?>" />

</form>
</div>
