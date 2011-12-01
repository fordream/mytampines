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
<form action="<?php echo JRoute::_('index.php?option=com_control&view=objects&model=object');?>" method="post" name="adminForm">
	<fieldset class="filter">
		<div class="left">
			<label for="search"><?php echo JText::_('JX Search'); ?>:</label>
			<input type="text" name="search" id="search" value="<?php echo $this->state->search; ?>" size="60" title="<?php echo JText::_('JX Search in name'); ?>" />
			<button type="submit"><?php echo JText::_('JX Search Go'); ?></button>
			<button type="button" onclick="document.getElementById('search').value='';this.form.submit();"><?php echo JText::_('JX Search Clear'); ?></button>
		</div>
		<div class="right">
			<ol>
				<li>
					<label for="object_type">
						<?php echo JText::_('JX Filter Type'); ?>
					</label>
					<select name="object_type" id="object_type" class="inputbox" onchange="this.form.submit()">
					<?php
						echo JHTML::_('select.options', $this->objectTypes, 'value', 'text', $this->state->object_type);
					?>
					</select>
				</li>
				<li>
					<label for="object_type">
						<?php echo JText::_('JX Filter Section'); ?>
					</label>
					<select name="section_value" id="section_value" class="inputbox" onchange="this.form.submit()">
						<option value=""><?php echo JText::_('JX All');?></option>
					<?php
						echo JHTML::_('select.options', $this->sectionValues, 'value', 'text', $this->state->section_value);
					?>
					</select>
				</li>
			</ol>
		</div>
	</fieldset>

	<table class="adminlist">
		<thead>
			<tr>
				<th width="20">
					<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($this->items);?>)" />
				</th>
				<th nowrap="nowrap" width="10%">
					<?php echo JHTML::_('grid.sort', 'JX Col Section Value', 'a.section_value', $this->state->orderDirn, $this->state->orderCol); ?>
				</th>
				<th class="left" width="40%">
					<?php echo JHTML::_('grid.sort', 'JX Col '.$this->state->object_type.' Name', 'a.name', $this->state->orderDirn, $this->state->orderCol); ?>
				</th>
				<th nowrap="nowrap">
					<?php echo JHTML::_('grid.sort', 'JX Col '.$this->state->object_type.' Value', 'a.value', $this->state->orderDirn, $this->state->orderCol); ?>
				</th>
				<?php if ($this->state->object_type == 'aro' OR $this->state->object_type == 'axo') : ?>
				<th nowrap="nowrap" width="5%">
					<?php echo JText::_('JX Col in Groups'); ?>
				</th>
				<?php endif; ?>
				<th nowrap="nowrap" width="5%">
					<?php echo JHTML::_('grid.sort', 'JX Col Order', 'a.order_value', $this->state->orderDirn, $this->state->orderCol); ?>
				</th>
				<th nowrap="nowrap" width="5%">
					<?php echo JHTML::_('grid.sort', 'JX Col Hidden', 'a.hidden', $this->state->orderDirn, $this->state->orderCol); ?>
				</th>
				<?php if ($this->state->object_type == 'aco') : ?>
				<th nowrap="nowrap" width="5%" align="center">
					<?php echo JText::_('JX Col '.$this->state->object_type.' Type'); ?>
				</th>
				<?php endif; ?>
				<th nowrap="nowrap" width="1%" align="center">
					<?php echo JText::_('JX Col ID'); ?>
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
				<td align="center">
					<?php echo $item->section_value; ?>
				</td>
				<td>
					<a href="<?php echo JRoute::_('index.php?option=com_control&view=object&model=object&object_type='.$this->state->object_type.'&task=edit&cid[]='.$item->id);?>">
						<?php echo $item->name; ?></a>
				</td>
				<td>
					<?php echo $item->value; ?>
				</td>
				<?php if ($this->state->object_type == 'aro' OR $this->state->object_type == 'axo') : ?>
				<td align="center">
					<?php echo nl2br($item->group_names); ?>
				</td>
				<?php endif; ?>
				<td class="order">
					<?php echo $this->pagination->orderUpIcon($item->id, $item->order_value); ?>
					<?php echo $item->order_value; ?>
					<?php echo $this->pagination->orderDownIcon($item->id, $item->order_value); ?>
				</td>
				<td align="center">
					<?php echo $item->hidden; ?>
				</td>
				<?php if ($this->state->object_type == 'aco') : ?>
				<td align="center">
					<?php echo JText::_($item->acl_type ? 'JX Role' : 'JX Rule'); ?>
				</td>
				<?php endif; ?>
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
