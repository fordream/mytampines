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
<form action="<?php echo JRoute::_('index.php?option=com_control&view=acls&model=acl');?>" method="post" name="adminForm">
	<fieldset class="filter clearfix">
		<ol class="left">
			<li>
				<label for="search"><?php echo JText::_('JX Search'); ?>:</label>
				<input type="text" name="search" id="search" value="<?php echo $this->state->search; ?>" size="60" title="<?php echo JText::_('JX Search ACLs'); ?>" />
			</li>
			<li>
				<button type="submit"><?php echo JText::_('JX Search Go'); ?></button>
				<button type="button" onclick="document.getElementById('search').value='';this.form.submit();"><?php echo JText::_('JX Search Clear'); ?></button>
			</li>
		</ol>
		<ol class="right">
			<li>
				<label for="object_type">
					<?php echo JText::_('JX Filter Section'); ?>
				</label>
				<select name="section_value" id="section_value" class="inputbox" onchange="this.form.submit()">
					<option value=""><?php echo JText::_('- Select Section -');?></option>
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
				<th width="20" rowspan="3">
					<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($this->items);?>)" />
				</th>
				<th class="left" colspan="3">
					<?php echo JHTML::_('grid.sort', 'JX Col Note', 'a.note', $this->state->orderDirn, $this->state->orderCol); ?>
				</th>
				<th nowrap="nowrap" width="5%">
					<?php echo JHTML::_('grid.sort', 'JX Col Allow', 'a.allow', $this->state->orderDirn, $this->state->orderCol); ?>
				</th>
				<th nowrap="nowrap" width="5%">
					<?php echo JHTML::_('grid.sort', 'JX Col Enabled', 'a.enabled', $this->state->orderDirn, $this->state->orderCol); ?>
				</th>
			</tr>
			<tr>
				<th nowrap="nowrap" align="center" rowspan="2">
					<?php echo JText::_('JX Col ACOs'); ?>
				</th>
				<th nowrap="nowrap" align="center" rowspan="2">
					<?php echo JText::_('JX Col AROs'); ?>
				</th>
				<th nowrap="nowrap" align="center" rowspan="2">
					<?php echo JText::_('JX Col AXOs'); ?>
				</th>
				<th nowrap="nowrap" width="10%" colspan="2">
					<?php echo JHTML::_('grid.sort', 'JX Col Section', 'a.section_value', $this->state->orderDirn, $this->state->orderCol); ?>
				</th>
			</tr>
			<tr>
				<th nowrap="nowrap" width="40%" colspan="2">
					<?php echo JHTML::_('grid.sort', 'JX Col Return Value', 'a.return_value', $this->state->orderDirn, $this->state->orderCol); ?>
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
				<td rowspan="3" style="text-align:center">
					<span title="<?php echo $item->id;?>"><?php echo JHTML::_('grid.id', $i++, $item->id); ?></a>
				</td>
				<td colspan="3">
					<a href="<?php echo JRoute::_('index.php?option=com_control&view=acl&model=acl&task=edit&cid[]='.$item->id);?>">
						<?php echo $item->note; ?></a>
				</td>
				<td class="acl-allow<?php echo $item->allow; ?>">
					<?php echo $item->allow; ?>
				</td>
				<td class="acl-enabled<?php echo $item->enabled; ?>">
					<?php echo $item->enabled; ?>
				</td>
			</tr>
			<tr>
				<td align="left" valign="top" rowspan="2">
				<?php
					if (isset($item->acos)) : ?>
					<div class="scroll" style="height:75px">
					<?php foreach ($item->acos as $section => $acos) : ?>
							<strong><?php echo $section;?></strong>
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
				<td align="left" valign="top" rowspan="2">
					<div class="scroll" style="height:75px">
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
						<strong><?php echo JText::_('JX User Groups');?></strong>
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
				<td align="left" valign="top" rowspan="2">
					<div class="scroll" style="height:75px">
				<?php
					if (isset($item->axos)) :
						foreach ($item->axos as $section => $axos) : ?>
							<strong><?php echo $section;?></strong>
							<?php if (count($axos)) : ?>
								<ol>
									<?php foreach ($axos as $name) : ?>
									<li>
										<?php echo $name; ?>
									</li>
									<?php endforeach; ?>
								</ol>
							<?php endif;
						endforeach;
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
					</div>
				</td>
				<td align="center" colspan="2">
					<?php echo $item->section_value; ?>
				</td>
			</tr>
			<tr>
				<td align="center" colspan="2">
					<?php echo $item->return_value; ?>
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
