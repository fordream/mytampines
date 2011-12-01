<?php
/**
 * @version		$Id: import.php 1175 2009-06-04 00:15:16Z eddieajau $
 * @package		JXtended.Control
 * @copyright	Copyright (C) 2008 - 2009 JXtended LLC. All rights reserved.
 * @license		GNU General Public License
 * @link		http://jxtended.com
 */

// no direct access
defined('_JEXEC') or die;

JHTML::_('behavior.switcher');
JHTML::_('behavior.tooltip');
$this->document->addStyleSheet('templates/system/css/system.css');
?>

<div id="jx-config">
	<fieldset>
		<div style="float: right">
			<button type="button" onclick="submitbutton('config.import');">
				<?php echo JText::_('Import');?>
			</button>
			<button type="button" onclick="window.location = 'index.php?option=com_control&amp;task=config.export';">
				<?php echo JText::_('Export');?>
			</button>
			<button type="button" onclick="window.parent.document.getElementById('sbox-window').close();">
				<?php echo JText::_('Cancel');?>
			</button>
		</div>
		<div class="configuration" >
			<?php echo JText::_('JX Control Configuration'); ?>
		</div>
	</fieldset>

	<form action="index.php?option=com_control" method="post" name="adminForm" autocomplete="off" enctype="multipart/form-data">
		<fieldset>
			<legend><?php echo JText::_('Import'); ?></legend>

			<label for="import_file"><?php echo JText::_('Import From File'); ?></label><br />
			<input type="file" name="configFile" id="import_file" size="50" />

			<br /><br />

			<label for="import_string"><?php echo JText::_('Import From String'); ?></label><br />
			<textarea name="configString" rows="10" cols="50"></textarea>
		</fieldset>
		<input type="hidden" name="task" value="" />
		<?php echo JHTML::_('form.token'); ?>
	</form>
</div>