<?php
/**
 * @version		$Id: default.php 1175 2009-06-04 00:15:16Z eddieajau $
 * @package		JXtended.Control
 * @copyright	Copyright (C) 2008 - 2009 JXtended LLC. All rights reserved.
 * @license		GNU General Public License
 * @link		http://jxtended.com
 */

// no direct access
defined('_JEXEC') or die;

JHTML::_('behavior.tooltip');
?>
<form action="<?php echo JRoute::_('index.php?option=com_control&view=sections&model=section');?>" method="post" name="adminForm">
	<fieldset class="filter clearfix">
		<ol class="left">
			<li>
				<label for="search"><?php echo JText::_('JX Search'); ?>:</label>
				<input type="text" name="search" id="search" value="<?php echo $this->state->search; ?>" size="60" title="<?php echo JText::_('JX Search Sections'); ?>" />
			</li>
			<li>
				<button type="submit"><?php echo JText::_('JX Search Go'); ?></button>
				<button type="button" onclick="document.getElementById('search').value='';this.form.submit();"><?php echo JText::_('JX Search Clear'); ?></button>
			</li>
		</ol>
		<ol class="right">
			<li>
				<label for="section_type">
					<?php echo JText::_('Type'); ?>
				</label>
				<select name="section_type" id="section_type" class="inputbox" onchange="this.form.submit()">
				<?php
					echo JHTML::_('select.options', $this->sectionTypes, 'value', 'text', $this->state->section_type);
				?>
				</select>
			</li>
		</ol>
	</fieldset>

	<table class="adminlist">
		<thead>
			<tr>
				<th width="20">
					<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($this->items);?>)" />
				</th>
				<th class="left" width="40%">
					<?php echo JHTML::_('grid.sort', 'JX Col Section Name', 'a.name', $this->state->orderDirn, $this->state->orderCol); ?>
				</th>
				<th nowrap="nowrap">
					<?php echo JHTML::_('grid.sort', 'JX Col Section Value', 'a.value', $this->state->orderDirn, $this->state->orderCol); ?>
				</th>
				<th nowrap="nowrap" width="5%">
					<?php echo JHTML::_('grid.sort', 'JX Col Order', 'a.order_value', $this->state->orderDirn, $this->state->orderCol); ?>
				</th>
				<th nowrap="nowrap" width="5%">
					<?php echo JHTML::_('grid.sort', 'JX Col Hidden', 'a.hidden', $this->state->orderDirn, $this->state->orderCol); ?>
				</th>
				<th nowrap="nowrap" width="1%" align="center">
					<?php echo JText::_('JX ID'); ?>
				</th>
				<th width="30%">
					&nbsp;
				</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="15">
					<?php echo $this->pagination->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
		<tbody>
		<?php
			$i = 0;
			foreach ($this->items as $item) : ?>
			<tr class="row<?php echo $i % 2; ?>">
				<td style="text-align:center">
					<?php echo JHTML::_('grid.id', $i++, $item->id); ?>
				</td>
				<td>
					<a href="<?php echo JRoute::_('index.php?option=com_control&view=section&model=section&section_type='.$this->state->section_type.'&task=edit&cid[]='.$item->id);?>">
						<?php echo $item->name; ?></a>
				</td>
				<td align="center">
					<?php echo $item->value; ?>
				</td>
				<td class="order">
					<?php echo $item->order_value; ?>
				</td>
				<td align="center">
					<?php echo $item->hidden; ?>
				</td>
				<td align="center">
					<?php echo $item->id; ?>
				</td>
				<td>
					&nbsp;
				</td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>

	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="filter_order" value="<?php echo $this->state->get('orderCol'); ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->state->get('orderDirn'); ?>" />
	<?php echo JHTML::_('form.token'); ?>
</form>
