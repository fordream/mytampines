<?php
/**
 * @version		$Id: edit_groups.php 1175 2009-06-04 00:15:16Z eddieajau $
 * @package		JXtended.Control
 * @copyright	Copyright (C) 2008 - 2009 JXtended LLC. All rights reserved.
 * @license		GNU General Public License
 * @link		http://jxtended.com
 */

// no direct access
defined('_JEXEC') or die;
?>
<strong><?php echo JText::_('JX Assigned to Groups'); ?></strong>
<ul class="checklist">
	<?php foreach ($this->grouplist as $item) :
		$readOnly	= ($item->id == $this->group_id) ? 'readonly="readonly"' : '';
	?>
	<li>
		<?php $eid = 'group_'.$item->value; ?>
		<input type="checkbox" name="group_ids[]" value="<?php echo $item->id;?>" id="<?php echo $eid;?>"
			<?php echo aclGroupChecked($this->groups, $item->id); ?> <?php echo $readOnly;?> />
		<label for="<?php echo $eid;?>" style="padding-left:<?php echo intval(($item->level-2)*15)+4; ?>px;<?php echo $readOnly ? 'color:blue;' : '';?>">
			<?php echo $item->name; ?>
		</label>
		<?php if ($readOnly) : ?>
		<label>(<?php echo JText::_('JX SYSTEM USER GROUP'); ?>)</label>
		<?php endif; ?>
	</li>
	<?php endforeach; ?>
</ul>
