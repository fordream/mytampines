<?php
/**
 * @version		$Id: edit_acos.php 1175 2009-06-04 00:15:16Z eddieajau $
 * @package		JXtended.Control
 * @copyright	Copyright (C) 2008 - 2009 JXtended LLC. All rights reserved.
 * @license		GNU General Public License
 * @link		http://jxtended.com
 */

// no direct access
defined('_JEXEC') or die;
?>
<ul class="checklist scroll" style="height:280px">
	<?php foreach ($this->acos as $item) : ?>
	<?php if ($item->acl_type == $this->item->acl_type) : ?>
	<li>
		<?php $eid = 'aco_'.$item->section_value.'_'.$item->value; ?>
		<input type="checkbox" name="aco_array[<?php echo $item->section_value;?>][]" value="<?php echo $item->value;?>" id="<?php echo $eid;?>"
			<?php echo aclObjectChecked($this->acl['aco'], $item->section_value, $item->value); ?> />
		<label for="<?php echo $eid;?>">
			<?php echo $item->name; ?>
		</label>
	</li>
	<?php endif; ?>
	<?php endforeach; ?>
</ul>
