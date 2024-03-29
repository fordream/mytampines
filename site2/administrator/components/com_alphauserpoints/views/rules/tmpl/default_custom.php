<?php
/*
 * @component AlphaUserPoints
 * @copyright Copyright (C) 2008-2010 Bernard Gilly
 * @license : GNU/GPL
 * @Website : http://www.alphaplug.com
 */

defined( '_JEXEC' ) or die( 'Restricted access' );

?>
<form action="index.php" method="post" name="adminForm" autocomplete="off">
		<fieldset>
			<div style="float: right">
				<button type="submit" onclick="window.parent.document.getElementById('sbox-window').close();window.top.location='index.php?option=com_alphauserpoints&task=showdetails&cid=<?php echo $this->cid; ?>&name=<?php echo $this->name; ?>';window.location.reload(true);history.go(0);window.location.reload(true);">
					<?php echo JText::_( 'Save' );?></button>
				<button type="button" onclick="window.parent.document.getElementById('sbox-window').close();">
					<?php echo JText::_( 'Cancel' );?></button>
			</div>
			<div class="configuration" >
				<?php echo JText::_( 'AUP_CUSTOM_POINTS' ); ?>
			</div>
		</fieldset>
<br /><br />
	<fieldset>
		<legend><?php echo JText::_( 'AUP_CUSTOM_POINTS' ); ?></legend>
		<table class="admintable">
		<tbody>
		<tr>
			<td class="key">
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'AUP_POINTS' ); ?>::<?php echo JText::_('AUP_CUSTOM_POINTS_DESCRIPTION'); ?>">
					<?php echo JText::_( 'AUP_POINTS' ); ?>:
				</span>
			</td>
			<td>				
				<input class="inputbox" type="text" name="points" id="points" size="20" maxlength="255" value="" />
			</td>
		</tr>
		<tr>
			<td class="key">
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'AUP_DESCRIPTION' ); ?>::<?php echo JText::_('AUP_DESCRIPTION'); ?>">
					<?php echo JText::_( 'AUP_DESCRIPTION' ); ?>:
				</span>
			</td>
			<td>
				<input class="inputbox" type="text" name="reason" id="reason" size="80" maxlength="255" value="" />
			</td>
		</tr>
		</tbody>
		</table>
	</fieldset>
	<input type="hidden" name="option" value="com_alphauserpoints" />
	<input type="hidden" name="task" value="savecustompoints" />
	<input type="hidden" name="cid" value="<?php echo $this->cid; ?>" />
	<input type="hidden" name="name" value="<?php echo $this->name; ?>" />
</form><br /><br />