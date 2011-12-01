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
<form action="index.php" method="post" name="adminForm">

<table class="adminlist">
	<thead>
		<tr>
			<th rowspan="2" width="5%">
				&nbsp;
			</th>
			<th colspan="2">
				<?php echo JText::_('JX Col ACO'); ?>
			</th>
			<th colspan="2">
				<?php echo JText::_('JX Col ARO'); ?>
			</th>
			<th colspan="2">
				<?php echo JText::_('JX Col AXO'); ?>
			</th>
			<th rowspan="2">
				&nbsp;
			</th>
		</tr>
		<tr>
			<th>
				<?php echo JText::_('JX Col Section'); ?>
			</th>
			<th>
				<?php echo JText::_('JX Col Value'); ?>
			</th>
			<th>
				<?php echo JText::_('JX Col Section'); ?>
			</th>
			<th>
				<?php echo JText::_('JX Col Value'); ?>
			</th>
			<th>
				<?php echo JText::_('JX Col Section'); ?>
			</th>
			<th>
				<?php echo JText::_('JX Col Value'); ?>
			</th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<td colspan="99">
				<input type="Submit" value="<?php echo JText::_('JX Submit'); ?>" />
			</td>
		</tr>
	</tfoot>
	<tbody>
		<tr>
			<td>
				acl_query (
			</td>
			<td>
				<input type="text" value="<?php echo $this->state->aco_section_value; ?>" name="aco_section_value" id="aco_section_value" />
			</td>
			<td>
				<input type="text" value="<?php echo $this->state->aco_value; ?>" name="aco_value" id="aco_value" />
			</td>
			<td>
				<input type="text" value="<?php echo $this->state->aro_section_value; ?>" name="aro_section_value" id="aro_section_value" />
			</td>
			<td>
				<input type="text" value="<?php echo $this->state->aro_value; ?>" name="aro_value" id="aro_value" />
			</td>
			<td>
				<input type="text" value="<?php echo $this->state->axo_section_value; ?>" name="axo_section_value" id="axo_section_value" />
			</td>
			<td>
				<input type="text" value="<?php echo $this->state->axo_value; ?>" name="axo_value" id="axo_value" />
			</td>

			<td width="1%">
				)
			</td>
		</tr>
	</tbody>
</table>

<br />

<table class="adminlist">
	<thead>
		<tr>
			<th rowspan="2" width="5%">
				<?php echo JText::_('JX Col ACL ID'); ?>
			</th>
			<th colspan="2">
				<?php echo JText::_('JX Col ACO'); ?>
			</th>
			<th colspan="2">
				<?php echo JText::_('JX Col ARO'); ?>
			</th>
			<th colspan="2">
				<?php echo JText::_('JX Col AXO'); ?>
			</th>
			<th colspan="2">
				<?php echo JText::_('JX Col ACL'); ?>
			</th>
		</tr>
		<tr>
			<th width="13%">
				<?php echo JText::_('JX Col Section'); ?>
			</th>
			<th width="13%">
				<?php echo JText::_('JX Col Value'); ?>
			</th>
			<th width="13%">
				<?php echo JText::_('JX Col Section'); ?>
			</th>
			<th width="13%">
				<?php echo JText::_('JX Col Value'); ?>
			</th>
			<th width="13%">
				<?php echo JText::_('JX Col Section'); ?>
			</th>
			<th width="13%">
				<?php echo JText::_('JX Col Value'); ?>
			</th>
			<th width="5%">
				<?php echo JText::_('JX Col Result'); ?>
			</th>
			<th width="10%">
				<?php echo JText::_('JX Col Updated Date'); ?>
			</th>
		</tr>
	</thead>
	<tbody>
	<?php if (count($this->items) == 0) : ?>
		<tr>
			<td colspan="99" align="center" class="acl-allow0">
				<?php echo JText::_('JX no Match Desc'); ?>
			</td>
		</tr>
	<?php endif; ?>
	<?php
		$i = 0;
		foreach ($this->items as $item) : ?>
		<tr class="">
			<td align="center">
				<?php echo $item->id; ?>
			</td>
			<td align="center">
				<?php echo $item->aco_section_value; ?>
			</td>
			<td align="center">
				<?php echo $item->aco_value; ?>
			</td>
			<td align="center">
				<?php echo $item->aro_section_value; ?>
			</td>
			<td align="center">
				<?php echo $item->aro_value; ?>
			</td>
			<td align="center">
				<?php echo $item->axo_section_value; ?>
			</td>
			<td align="center">
				<?php echo $item->axo_value; ?>
			</td>
			<td class="acl-allow<?php echo (int) $item->allow; ?>">
				<?php echo JText::_($item->allow ? 'JX Allow' : 'JX Deny'); ?>
			</td>
			<td align="center">
				<?php echo date('d-M-y H:m:i', $item->updated_date); ?>
			</td>
		</tr>
	<?php
		$i++;
		endforeach; ?>
	</tbody>
</table>

<input type="hidden" name="option" value="com_control" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="view" value="debug" />
<input type="hidden" name="model" value="test" />

</form>
