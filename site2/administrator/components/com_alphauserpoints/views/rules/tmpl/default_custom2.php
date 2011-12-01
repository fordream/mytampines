<?php
/*
 * @component AlphaUserPoints
 * @copyright Copyright (C) 2008-2010 Bernard Gilly
 * @license : GNU/GPL
 * @Website : http://www.alphaplug.com
 */

defined( '_JEXEC' ) or die( 'Restricted access' );

JToolBarHelper::title(   JText::_( 'AUP_CUSTOM_POINTS' ), 'searchtext' );
JToolBarHelper::custom( 'cpanel', 'default.png', 'default.png', JText::_('AUP_CPANEL'), false );
JToolBarHelper::back( 'Back' );
JToolBarHelper::save( 'savecustomrulepoints' );
JToolBarHelper::help( 'screen.alphauserpoints', true );
?>
<form action="index.php" method="post" name="adminForm" autocomplete="off">
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
</form><br /><br />