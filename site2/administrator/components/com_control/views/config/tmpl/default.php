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

JHTML::_('behavior.switcher');
JHTML::_('behavior.tooltip');
$this->document->addStyleSheet('templates/system/css/system.css');
?>

<div id="jx-config">
	<fieldset>
		<div style="float: right">
			<button type="button" onclick="submitbutton('config.save');">
				<?php echo JText::_('Save');?>
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

	<div id="submenu-box">
		<div class="t">
			<div class="t">
				<div class="t"></div>
	 		</div>
		</div>
		<div class="m">
			<ul id="submenu">
				<li><a id="component" class="active"><?php echo JText::_('JX Control Configuration'); ?></a></li>
			</ul>
			<div class="clr"></div>
		</div>
		<div class="b">
			<div class="b">
	 			<div class="b"></div>
			</div>
		</div>
	</div>

	<form action="index.php?option=com_control" method="post" name="adminForm" autocomplete="off">
		<div id="config-document">
			<div id="page-component">
				<fieldset>
					<legend><?php echo JText::_('JX Control Configuration'); ?></legend>
					<?php echo JHTML::_('control.params', 'params', $this->params->toString(), 'models/forms/config/component.xml'); ?>
				</fieldset>
			</div>

		</div>
		<input type="hidden" name="task" value="" />
	</form>
</div>