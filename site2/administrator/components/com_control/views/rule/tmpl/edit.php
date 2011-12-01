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

jimport('joomla.html.pane');
$pane =& JPane::getInstance('tabs');

$fieldsMain		= $this->form->getFields('jxform');
?>
<script language="javascript" type="text/javascript">
<!--
	function submitbutton(task)
	{
		var form = document.adminForm;
		if (task == 'rule.cancel' || document.formvalidator.isValid(document.adminForm)) {
			submitform(task);
		}
	}
-->
</script>
<?php echo $this->form->getHead(); ?>
	<fieldset>
		<?php if ($this->item->id) : ?>
		<legend><?php echo JText::sprintf('Control_Rule_Number', $this->item->id); ?></legend>
		<?php endif; ?>

		<table class="adminform">
			<tbody>
				<tr>
					<td width="33%">
						<?php echo $fieldsMain['note']->label; ?><br />
						<?php echo $fieldsMain['note']->field; ?>
					</td>
					<td width="33%">
						<?php echo $fieldsMain['allow']->label; ?><br />
						<?php echo $fieldsMain['allow']->field; ?>
					</td>
					<td width="33%">
						<?php echo $fieldsMain['section_value']->label; ?><br />
						<input type="text" name="jxform[section_value]" value="<?php echo $fieldsMain['section_value']->value; ?>" class="readonly" readonly="readonly" />

					</td>
				</tr>
				<tr>
					<td>
						<?php echo $fieldsMain['return_value']->label; ?><br />
						<?php echo $fieldsMain['return_value']->field; ?>
					</td>
					<td>
						<?php echo $fieldsMain['enabled']->label; ?><br />
						<?php echo $fieldsMain['enabled']->field; ?>
					</td>
					<td>
						<?php echo $fieldsMain['updated_date']->label; ?><br />
						<?php echo $fieldsMain['updated_date']->field; ?>
					</td>
				</tr>
			</tbody>
		</table>

<?php echo $pane->startPane('item-edit');
	  echo $pane->startPanel(JText::_('Control_Tab_Permissions'), 'editor-page'); ?>
		<table width="100%">
			<tbody>
				<tr valign="top">
					<td valign="top" width="25%">
						<fieldset>
							<legend><?php echo JText::_('Control_Fieldset_User_Groups');?></legend>
							<?php echo $this->loadTemplate('arogroups'); ?>
						</fieldset>
					</td>
					<td valign="top" width="25%">
						<fieldset>
							<legend class="hasTip" title="Permissions::Select the permissions that this group will be allowed, or not allowed to do.">
							<?php echo JText::_('Control_Fieldset_Actions') ?>
							</legend>
							<?php echo $this->loadTemplate('acos'); ?>
						</fieldset>
					</td>
					<?php if ($this->item->acl_type == 2) : ?>
					<td valign="top">
						<fieldset>
							<legend class="hasTip" title="Items::These are the items that are associated with the permission">
							<?php echo JText::_('Control_Fieldset_Assets') ?>
							</legend>
							<?php echo $this->loadTemplate('axos'); ?>
						</fieldset>
					</td>
					<td valign="top">
						<?php if ($this->item->acl_type == 3) : ?>
						<fieldset>
							<legend class="hasTip" title="Item Groups::These are the item groups that are associated with the permission">
							<?php echo JText::_('Control_Fieldset_Asset_Groups') ?>
							</legend>
							<?php echo $this->loadTemplate('axogroups'); ?>
						</fieldset>
						<?php endif; ?>
					</td>
					<?php endif; ?>
			</tbody>
		</table>
<?php echo $pane->endPanel();
	  echo $pane->endPane(); ?>
		<div class="clr"></div>
		<div style="display:none;"><?php echo $fieldsMain['id']->field; ?></div>

		<?php echo $fieldsMain['acl_type']->field; ?>
		<input type="hidden" name="object_type" value="<?php echo $this->state->object_type;?>" />
		<input type="hidden" name="option" value="<?php echo $this->state->option;?>" />
		<input type="hidden" name="task" value="" />
	</fieldset>
<?php echo $this->form->getFoot(); ?>
