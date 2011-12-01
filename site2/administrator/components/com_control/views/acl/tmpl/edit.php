<?php
/**
 * @version		$Id: edit.php 1175 2009-06-04 00:15:16Z eddieajau $
 * @package		JXtended.Control
 * @copyright	Copyright (C) 2008 - 2009 JXtended LLC. All rights reserved.
 * @license		GNU General Public License
 * @link		http://jxtended.com
 */

// no direct access
defined('_JEXEC') or die;

JHTML::_('behavior.tooltip');
JHTML::_('behavior.formvalidation');
?>
<script language="javascript" type="text/javascript">
<!--
	function submitbutton(task)
	{
		var form = document.adminForm;
		if (task == 'cancel' || document.formvalidator.isValid(document.adminForm)) {
			submitform(task);
		}
	}
-->
</script>
<?php echo $this->form->getHead(); ?>
	<fieldset>
		<legend></legend>

		<?php echo $this->form->renderToTable(); ?>

		<table id="acl">
			<thead>
				<tr>
					<th width="25%" class="hasTip" title="Permissions::What you are allowed, or not allowed to do.">
						<?php echo JText::_('JX Assigned ACOs') ?>
					</th>
					<th width="25%" class="hasTip" title="User Groups::The user groups that are connected with the permission.  Remember child groups inherit from their parents">
						<?php echo JText::_('JX Assigned ARO Groups') ?>
					</th>
					<th width="25%" class="hasTip" title="Items::Not always used.  These are the items that are associated with the permission">
						<?php echo JText::_('JX Assigned AXOs') ?>
					</th>
					<th width="25%" class="hasTip" title="Item Groups::Not always used.  These are the item groups that are associated with the permission">
						<?php echo JText::_('JX Assigned AXO Groups') ?>
					</th>
				</tr>

			</thead>
			<tbody>
				<tr>
					<td>
						<?php echo $this->loadTemplate('acos'); ?>
					</td>
					<td>
						<?php echo $this->loadTemplate('arogroups'); ?>
					</td>
					<td>
						<?php echo $this->loadTemplate('axos'); ?>
					</td>
					<td>
						<?php echo $this->loadTemplate('axogroups'); ?>
					</td>
				</tr>
			</tbody>
		</table>

		<input type="hidden" name="object_type" value="<?php echo $this->state->object_type;?>" />
		<input type="hidden" name="option" value="com_control" />
		<input type="hidden" name="model" value="acl" />
		<input type="hidden" name="view" value="acl" />
		<input type="hidden" name="return" value="acls" />
		<input type="hidden" name="task" value="" />
	</fieldset>
<?php echo $this->form->getFoot(); ?>
