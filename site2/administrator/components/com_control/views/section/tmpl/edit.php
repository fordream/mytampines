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
		<legend><?php echo JText::_('Add/Edit Section');?></legend>

		<?php echo $this->form->renderToTable(); ?>

		<input type="hidden" name="section_type" value="<?php echo $this->state->section_type;?>" />
		<input type="hidden" name="option" value="com_control" />
		<input type="hidden" name="model" value="section" />
		<input type="hidden" name="view" value="section" />
		<input type="hidden" name="return" value="sections" />
		<input type="hidden" name="task" value="" />
	</fieldset>
<?php echo $this->form->getFoot(); ?>
<script type="text/javascript">
// Attach the onblur event to auto-create the alias
e = document.getElementById('jxform_name');
e.onblur = function(){
	title = document.getElementById('jxform_name');
	alias = document.getElementById('jxform_value');
	if (alias.value=='') {
		alias.value = title.value.replace(/[\s\-]+/g,'-').replace(/&/g,'and').replace(/[^A-Z0-9\-\_]/ig,'').toLowerCase();
	}
}
</script>
