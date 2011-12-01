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
<form action="<?php echo JRoute::_('index.php?option=com_control&view=test2d&model=test');?>" method="post" name="adminForm">
	<fieldset class="filter clearfix">
		<ol class="left">
			<li>
				<label for="search"><?php echo JText::_('JX Search'); ?>:</label>
				<input type="text" name="search" id="search" value="<?php echo $this->state->search; ?>" size="60" title="<?php echo JText::_('JX Search test2d'); ?>" />
			</li>
			<li>
				<button type="submit"><?php echo JText::_('JX Search Go'); ?></button>
				<button type="button" onclick="document.getElementById('search').value='';this.form.submit();"><?php echo JText::_('JX Search Clear'); ?></button>
			</li>
		</ol>
		<ol class="right">
			<li>
				<label for="section_value">
					<?php echo JText::_('JX Filter'); ?>
				</label>
				<select name="section_value" id="section_value" class="inputbox" onchange="this.form.submit()">
					<option value=""><?php echo JText::_('- Select ACO Section -');?></option>
				<?php
					echo JHTML::_('select.options', $this->sectionValues, 'value', 'text', $this->state->section_value);
				?>
				</select>
			</li>
		</ol>
	</fieldset>

	<table class="adminlist">
		<thead>
			<tr>
				<th width="20">
					<?php echo JText::_('Num'); ?>
				</th>
				<th nowrap="nowrap">
					<?php echo JText::_('JX Col ACO'); ?>
				</th>
				<th nowrap="nowrap">
					<?php echo JText::_('JX Col ARO'); ?>
				</th>
				<th nowrap="nowrap" width="5%">
					<?php echo JText::_('JX Col Return Value'); ?>
				</th>
				<th nowrap="nowrap">
					<?php echo JText::_('JX Col ACL Check Code'); ?>
				</th>
				<th nowrap="nowrap" width="5%">
					<?php echo JText::_('JX Col Result'); ?>
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
			<tr class="">
				<td align="center">
					<?php echo $this->pagination->getRowOffset($i); ?>
				</td>
				<td>
					<?php if ($this->state->section_value == '') : ?>
					<?php echo $item->aco_section_name; ?><br />
					<?php endif; ?>
					<?php echo $item->aco_name; ?>
				</td>
				<td>
					<?php echo $item->aro_name; ?>
				</td>
				<td>
					<?php echo $item->return_value; ?>
				</td>
				<td>
					<?php
						$href	= 'index.php?option=com_control&view=debug' .
								'&aco_section_value='.$item->aco_section_value.'&aco_value='.$item->aco_value.
								'&aro_section_value='.$item->aro_section_value.'&aro_value='.$item->aro_value;
					?>
					<a href="<?php echo JRoute::_($href);?>" title="<?php echo JText::_('JX Debug');?>">
					acl_check('<?php echo $item->aco_section_value;?>',
					'<?php echo $item->aco_value;?>',
					'<?php echo $item->aro_section_value;?>',
					'<?php echo $item->aro_value;?>')</a>
				</td>
				<td class="acl-allow<?php echo (int) $item->allow; ?>">
					<?php echo JText::_($item->allow ? 'JX Allow' : 'JX Deny'); ?>
				</td>
			</tr>
		<?php
			$i++;
			endforeach; ?>
		</tbody>
	</table>

	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="filter_order" value="<?php echo $this->state->get('orderCol'); ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->state->get('orderDirn'); ?>" />
</form>
