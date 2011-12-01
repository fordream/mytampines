<?php
/**
 * @version		$Id: edit_axos.php 1175 2009-06-04 00:15:16Z eddieajau $
 * @package		JXtended.Control
 * @copyright	Copyright (C) 2008 - 2009 JXtended LLC. All rights reserved.
 * @license		GNU General Public License
 * @link		http://jxtended.com
 */

// no direct access
defined('_JEXEC') or die;
?>
<ul class="checklist scroll" style="height:280px">
	<?php foreach ($this->axos as $item) : ?>
	<li>
		<?php $eid = 'aco_'.$item->section_value.'_'.$item->value; ?>
		<input type="checkbox" name="axo_array[<?php echo $item->section_value;?>][]" value="<?php echo $item->value;?>" id="<?php echo $eid;?>"
			<?php echo aclObjectChecked($this->acl['axo'], $item->section_value, $item->value); ?> />
		<label for="<?php echo $eid;?>">
			<?php echo $item->section_name; ?> -
			<?php echo $item->name; ?>
		</label>
	</li>
	<?php endforeach; ?>
</ul>
