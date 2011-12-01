<?php
/*
 * @component AlphaUserPoints
 * @copyright Copyright (C) 2008-2010 Bernard Gilly
 * @license : GNU/GPL
 * @Website : http://www.alphaplug.com
 */

defined( '_JEXEC' ) or die( 'Restricted access' );
?>
<table class="noshow">
	<tr>
		<td width="100%" valign="top">
		<form enctype="multipart/form-data" action="index2.php" method="post" name="filename">
		<table class="adminheading">
		<tr>
			<th class="install"><?php echo JText::_('AUP_INSTALL_NEW_PLUGIN_RULE');?></th>
		</tr>
		</table>
		<table class="adminform">
		<tr>
			<th><?php echo JText::_('AUP_UPLOADXMLFILE');?></th>
		</tr>
		<tr>
			<td align="left"><?php echo JText::_('AUP_FILENAME');?>:
			<input class="text_area" name="userfile" type="file" size="70"/>
			<input class="button" type="submit" value="<?php echo JText::_('AUP_UPLOADANDINSTALL');?>" />
			</td>
		</tr>
		</table>

		<input type="hidden" name="task" value="uploadfile"/>
		<input type="hidden" name="option" value="com_alphauserpoints"/>
		</form>
		</td>
	</tr>
</table>

