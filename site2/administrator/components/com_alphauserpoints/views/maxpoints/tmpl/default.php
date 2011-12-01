<?php
/*
 * @component AlphaUserPoints
 * @copyright Copyright (C) 2008-2010 Bernard Gilly
 * @license : GNU/GPL
 * @Website : http://www.alphaplug.com
 */

defined( '_JEXEC' ) or die( 'Restricted access' );

JToolBarHelper::title(   JText::_( 'AUP_SETMAXPOINST' ), 'cpanel' );
JToolBarHelper::custom( 'cpanel', 'default.png', 'default.png', JText::_('AUP_CPANEL'), false );
JToolBarHelper::save( 'savemaxpoints' );
JToolBarHelper::help( 'screen.alphauserpoints', true );

JRequest::setVar( 'hidemainmenu', 1 );
?>
<form action="index.php?option=com_alphauserpoints" method="post" name="adminForm">
	<fieldset class="adminform">
		<legend><?php echo JText::_( 'AUP_MAXPOINTSPERUSER' ); ?></legend>
		<table class="admintable">
		<tbody>
		<tr>
			<td class="key">
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'AUP_MAXPOINTS' ); ?>::<?php echo JText::_('AUP_MAXPOINTS'); ?>">
					<?php echo JText::_( 'AUP_MAXPOINTS' ); ?>:
				</span>
			</td>
			<td>
				<input class="inputbox" type="text" name="setpointsperuser" id="setpointsperuser" size="20" maxlength="30" value="<?php echo $this->setpoints; ?>" />
			</td>
		</tr>
		<tr>
			<td class="key">&nbsp;
			</td>
			<td>
				<?php echo JText::_('AUP_ZEROORBLANKFORUNLIMITED'); ?>
			</td>
		</tr>
		
		
		
		</tbody>
		</table>
	</fieldset>
	<input type="hidden" name="option" value="com_alphauserpoints" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="redirect" value="rules" />
	<input type="hidden" name="boxchecked" value="0" />
</form>



