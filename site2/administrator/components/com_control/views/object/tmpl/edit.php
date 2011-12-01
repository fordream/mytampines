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

$state	= $this->get('State');
$type	= strtoupper($state->get('object_type'));

if ($this->item->id) :
	JToolBarHelper::title(JText::_('Control: Edit '.$type.' Object'), 'logo');
else :
	JToolBarHelper::title(JText::_('Control: Add '.$type.' Object'), 'logo');
endif;
JToolBarHelper::save();
JToolBarHelper::apply();
JToolBarHelper::cancel();
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
		<legend><?php echo $this->item->id ? JText::sprintf('JX '.$type.' Object #%d', $this->item->id) : JText::_('JX New Object');?></legend>

		<?php if ($this->state->get('ext_mode') == 1) : ?>
			<div class="col width-60">
				<?php echo $this->form->renderToTable(); ?>
			</div>
			<div class="col width-40">
			<?php if ($this->grouplist) :
				echo $this->loadTemplate('groups');
			endif; ?>
			</div>
		<?php else : ?>
		<?php
			$fields = $this->form->getFields();
		?>
			<?php foreach ($fields as $name => $data) : ?>
				<input type="hidden" name="jxform[<?php echo $name; ?>]" value="<?php echo htmlspecialchars($data->get('value')); ?>" />
			<?php endforeach; ?>
			<h3><?php echo $fields['name']->get('value'); ?></h3>
			<?php if ($this->grouplist) :
				echo $this->loadTemplate('groups');
			endif; ?>
		<?php endif; ?>


		<input type="hidden" name="object_type" value="<?php echo $this->state->object_type;?>" />
		<input type="hidden" name="option" value="com_control" />
		<input type="hidden" name="model" value="object" />
		<input type="hidden" name="view" value="object" />
		<input type="hidden" name="return" value="objects" />
		<input type="hidden" name="task" value="" />
	</fieldset>
<?php echo $this->form->getFoot(); ?>

<script type="text/javascript">
// Attach the onblur event to auto-create the alias
if (e = document.getElementById('jxform_name')) {
	e.onblur = function() {
		title = document.getElementById('jxform_name');
		alias = document.getElementById('jxform_value');
		if (alias.value=='') {
			alias.value = title.value.replace(/ /g,'-').replace(/&/g,'and').toLowerCase();
		}
	}
}
</script>