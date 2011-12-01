<?php
/**
 * @version		$Id: easy.php 1175 2009-06-04 00:15:16Z eddieajau $
 * @package		JXtended.Control
 * @copyright	Copyright (C) 2008 - 2009 JXtended LLC. All rights reserved.
 * @license		GNU General Public License
 * @link		http://jxtended.com
 */

// no direct access
defined('_JEXEC') or die;

JHTML::_('behavior.tooltip');
if ($groups = $this->get('GroupList')) :
	$baseLevel = $groups[0]->level;
	foreach ($groups as $i => $option) :
		$groups[$i]->name = str_pad($option->name, strlen($option->name) + 2*($option->level-$baseLevel), '- ', STR_PAD_LEFT);
	endforeach;
endif;
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
					<label for="filter_group_id">
						<?php echo JText::_('JX Filter Group'); ?>
					</label>
					<select name="filter_group_id" id="filter_group_id" class="inputbox" onchange="this.form.submit()">
						<option value=""></option>
						<?php echo JHTML::_('select.options', $groups, 'id', 'name', $this->state->get('group_id')); ?>
					</select>
				</li>
			</ol>
		</div>
		<input type="hidden" name="object_type" value="ARO" />
		<input type="hidden" name="section_value" value="users" />
	</fieldset>

	<table class="adminlist">
		<thead>
			<tr>
				<th width="20">
					<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($this->items);?>)" />
				</th>
				<th class="left" width="40%">
					<?php echo JHTML::_('grid.sort', 'JX Col '.$this->state->object_type.' Name', 'a.name', $this->state->orderDirn, $this->state->orderCol); ?>
				</th>
				<th nowrap="nowrap">
					<?php echo JHTML::_('grid.sort', 'JX Col In Groups', 'a.order_value', $this->state->orderDirn, $this->state->orderCol); ?>
				</th>
				<th nowrap="nowrap" width="5%">
					<?php echo JHTML::_('grid.sort', 'JX Col User ID', 'a.value', $this->state->orderDirn, $this->state->orderCol); ?>
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
					<a href="<?php echo JRoute::_('index.php?option=com_control&view=object&model=object&object_type='.$this->state->object_type.'&task=edit&cid[]='.$item->id);?>">
						<?php echo $item->name; ?></a>
				</td>
				<td align="left">
					<?php echo nl2br($item->group_names); ?>
				</td>
				<td align="center">
					<?php echo $item->value; ?>
				</td>
				<td>
					&nbsp;
				</td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>

	<fieldset>
		<legend><?php echo JText::_('JX Batch Update'); ?></legend>

		<label for="object_type">
			<?php echo JText::_('JX Batch Group'); ?>
		</label>
		<select name="batch[group_id]" id="batch_group_id" class="inputbox">
			<option value=""></option>
			<?php echo JHTML::_('select.options', $groups, 'id', 'name'); ?>
		</select>
		<input type="radio" name="batch[group_logic]" id="batch_group_logic_add" value="add" checked="checked" />
		<label for="batch_group_logic_add">
			<?php echo JText::_('JX Batch Add To Group'); ?>
		</label>
		<input type="radio" name="batch[group_logic]" id="batch_group_logic_del" value="del" />
		<label for="batch_group_logic_del">
			<?php echo JText::_('JX Batch Delete From Group'); ?>
		</label>
		<input type="radio" name="batch[group_logic]" id="batch_group_logic_set" value="set" />
		<label for="batch_group_logic_set">
			<?php echo JText::_('JX Batch Set To Group'); ?>
		</label>

		<button type="button" onclick="submitbutton('object.batch');"><?php echo JText::_('JX Batch Do It'); ?></button>

	</fieldset>


	<input type="hidden" name="task" value="" />

	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="filter_order" value="<?php echo $this->state->get('orderCol'); ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->state->get('orderDirn'); ?>" />
	<?php echo JHTML::_('form.token'); ?>
</form>
