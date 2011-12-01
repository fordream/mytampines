<?php
/**
 * @version		$Id: default.php 1168 2009-05-25 10:52:18Z eddieajau $
 * @package		JXtended.Control
 * @copyright	Copyright (C) 2008 - 2009 JXtended LLC. All rights reserved.
 * @license		GNU General Public License
 * @link		http://jxtended.com
 */

// no direct access
defined('_JEXEC') or die;
?>
<h1>
	<?php echo JText::_('Control_About');?>
</h1>

<table class="adminlist">
	<caption>
		<?php echo JText::_('Control_About_Version_History');?>
	</caption>
	<thead>
		<tr>
			<th>
				<?php echo JText::_('Control_About_Version');?>
			</th>
			<th>
				<?php echo JText::_('Control_About_Version_Installed'); ?>
			</th>
			<th>
				<?php echo JText::_('Control_About_Version_Log'); ?>
			</th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<td colspan="3">
				<?php echo JText::_('Control_About_Version_Footnote'); ?>
			</td>
		</tr>
	</tfoot>
	<tbody>
		<?php foreach ($this->versions as $version) : ?>
		<tr>
			<td>
				<?php echo $version->version;?>
			</td>
			<td>
				<?php echo JHTML::date($version->installed_date); ?>
			</td>
			<td>
				<?php echo nl2br($version->log); ?>
			</td>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>
<br />
