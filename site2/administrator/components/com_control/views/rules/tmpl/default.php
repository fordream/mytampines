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
<form action="<?php echo JRoute::_('index.php?option='.$this->state->option.'&view=rules');?>" method="post" name="adminForm">
	<fieldset class="filter">
		<div class="left">
			<label for="search"><?php echo JText::_('Control_Filter_Search'); ?>:</label>
			<input type="text" name="search" id="search" value="<?php echo $this->state->search; ?>" size="60" title="<?php echo JText::_('Control_Search_in_note'); ?>" />
			<button type="submit"><?php echo JText::_('Control_Button_Go'); ?></button>
			<button type="button" onclick="document.getElementById('search').value='';this.form.submit();"><?php echo JText::_('Control_Button_Clear'); ?></button>
		</div>
	</fieldset>
	<table class="adminlist">
		<thead>
			<tr>
				<th width="20">
					<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($this->items);?>)" />
				</th>
				<th class="left">
					<?php echo JHTML::_('grid.sort', 'Control_Heading_Note', 'a.note', $this->state->orderDirn, $this->state->orderCol); ?>
				</th>
				<th nowrap="nowrap" align="center">
					<?php echo JText::_('Control_Heading_User_Groups'); ?>
				</th>
				<th nowrap="nowrap" align="center">
					<?php echo JText::_('Control_Heading_Permissions'); ?>
				</th>
				<th nowrap="nowrap" align="center">
					<?php echo JText::_('Control_Heading_Applies_to_Items'); ?>
				</th>
				<th width="5%">
					<?php echo JHTML::_('grid.sort', 'Control_Heading_Allowed', 'a.allow', $this->state->orderDirn, $this->state->orderCol); ?>
				</th>
				<th nowrap="nowrap" width="5%">
					<?php echo JHTML::_('grid.sort', 'Control_Heading_Enabled', 'a.enabled', $this->state->orderDirn, $this->state->orderCol); ?>
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
			$i = -1;
			foreach ($this->items as $item) : ?>
			<tr class="row<?php echo ++$i % 2; ?>">
				<td style="text-align:center">
					<span title="<?php echo $item->id;?>"><?php echo JHTML::_('grid.id', $i, $item->id); ?></span>
				</td>
				<td>
					<a href="<?php echo JRoute::_('index.php?option='.$this->state->option.'&task=rule.edit&id='.$item->id);?>">
						<?php echo $item->note; ?></a>
					<br /><small>Type <?php echo $item->acl_type;?></small>
				</td>
				<td align="left" valign="top">
					<div class="scroll" style="height: 75px;">
				<?php
					if (isset($item->aros)) :
						foreach ($item->aros as $section => $aros) : ?>
							<strong><?php echo $section;?></strong>
							<?php if (count($aros)) : ?>
								<ol>
									<?php foreach ($aros as $name) : ?>
									<li>
										<?php echo $name; ?>
									</li>
									<?php endforeach; ?>
								</ol>
							<?php endif;
						endforeach;
					endif;

					if (isset($item->aroGroups) && count($item->aroGroups)) : ?>
						<ol>
							<?php foreach ($item->aroGroups as $name) : ?>
							<li>
								<?php echo $name; ?>
							</li>
							<?php endforeach; ?>
						</ol>
					<?php
					endif;
				?>
					</div>
				</td>
				<td align="left" valign="top">
				<?php if (isset($item->acos)) : ?>
					<div class="scroll" style="height: 75px;">
					<?php foreach ($item->acos as $section => $acos) : ?>
							<?php if (count($acos)) : ?>
								<ol>
									<?php foreach ($acos as $name) : ?>
									<li>
										<?php echo $name; ?>
									</li>
									<?php endforeach; ?>
								</ol>
							<?php endif;
						endforeach; ?>
					</div>
				<?php endif; ?>
				</td>

				<td align="left" valign="top">
				<?php if (isset($item->axos)) : ?>
					<div class="scroll" style="height: 75px;">
					<?php foreach ($item->axos as $section => $axos) : ?>
							<?php if ($n = count($axos)) : ?>
								<ol>
									<?php foreach ($axos as $name) : ?>
									<li>
										<?php echo $name; ?>
									</li>
									<?php endforeach; ?>
								</ol>
							<?php endif;
						endforeach; ?>
					</div>
					<?php
					endif;

					if (isset($item->axoGroups) && count($item->axoGroups)) : ?>
						<strong><?php echo JText::_('JX Item Groups');?></strong>
						<ol>
							<?php foreach ($item->axoGroups as $name) : ?>
							<li>
								<?php echo $name; ?>
							</li>
							<?php endforeach; ?>
						</ol>
					<?php
					endif;
				?>
				</td>
				<td align="center">
					<?php echo JHTML::_('jxgrid.allowed', $item->allow, $i); ?>
				</td>
				<td align="center">
					<?php echo JHTML::_('jxgrid.enabled', $item->enabled, $i); ?>
				</td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>

	<input type="hidden" name="task" value="rule.display" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="filter_order" value="<?php echo $this->state->get('orderCol'); ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->state->get('orderDirn'); ?>" />
	<?php echo JHTML::_('form.token'); ?>
</form>
