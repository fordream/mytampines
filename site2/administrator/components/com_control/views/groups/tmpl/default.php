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
<form action="<?php echo JRoute::_('index.php?option=com_control&view=groups&model=group');?>" method="post" name="adminForm">
	<fieldset class="filter clearfix">
		<div class="left">
			<label for="search"><?php echo JText::_('JX Search'); ?>:</label>
			<input type="text" name="search" id="search" value="<?php echo $this->state->search; ?>" size="60" title="<?php echo JText::_('JX Search in name'); ?>" />
			<button type="submit"><?php echo JText::_('JX Search Go'); ?></button>
			<button type="button" onclick="document.getElementById('search').value='';this.form.submit();"><?php echo JText::_('JX Search Clear'); ?></button>
		</div>
		<div class="right">
			<ol>
				<li>
					<label for="group_type">
						<?php echo JText::_('JX Filter Group Type'); ?>
					</label>
					<select name="group_type" id="group_type" class="inputbox" onchange="this.form.submit()">
					<?php
						echo JHTML::_('select.options', $this->groupTypes, 'value', 'text', $this->state->group_type);
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
				<th class="left">
					<?php echo JText::_('JX Col Group Name'); ?>
				</th>
				<th>
					<?php echo JText::_('JX Col Group Value'); ?>
				</th>
				<th width="5%">
					<?php echo JText::_('JX Col Users In Group'); ?>
				</th>
				<th nowrap="nowrap" width="5%">
					<?php echo JText::_('JX Col Group L-R'); ?>
				</th>
				<th nowrap="nowrap" width="5%" align="center">
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
				<td style="padding-left:<?php echo intval($item->level*15)+4; ?>px">
					<a href="<?php echo JRoute::_('index.php?option=com_control&view=group&model=group&task=edit&cid[]='.$item->id);?>">
						<?php echo $item->name; ?></a>
					(lvl <?php echo $item->level; ?>)
				</td>
				<td>
					<?php echo $item->value; ?>
				</td>
				<td align="center">
					<?php echo $item->object_count ? $item->object_count : ''; ?>
				</td>
				<td align="center">
					<?php echo $item->lft.'-'.$item->rgt; ?>
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
