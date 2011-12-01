<?php
/*
 * @component AlphaUserPoints
 * @copyright Copyright (C) 2008-2010 Bernard Gilly
 * @license : GNU/GPL
 * @Website : http://www.alphaplug.com
 */

defined( '_JEXEC' ) or die( 'Restricted access' );

JRequest::setVar( 'hidemainmenu', 1 );
?>
<form action="index.php?option=com_alphauserpoints" method="post" name="adminForm">
	<fieldset class="adminform">
		<legend><?php echo JText::_( 'AUP_COMBINE_ACTIVITIES_DESCRIPTION' ); ?></legend>
		<table class="admintable">
		<tbody>		
		<tr>
			<td class="key">
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'AUP_COMBINE_ACTIVITIES' ); ?>::<?php echo JText::_('AUP_COMBINE_ACTIVITIES_DESCRIPTION'); ?>">
					<?php echo JText::_( 'AUP_COMBINE_ACTIVITIES' ); ?>:
				</span>
			</td>
		  <td>
			<?php echo JHTML::_('calendar', '', 'datestart', 'datestart', '%Y-%m-%d', array('class'=>'inputbox', 'size'=>'20',  'maxlength'=>'19')); ?>
			</td>
		</tr>
		<tr>
			<td class="key">&nbsp;
			</td>
		  <td>
			<input type="submit" name="Submit" value="<?php echo JText::_( 'AUP_COMBINE_ACTIVITIES' ); ?>">
			</td>
		</tr>		
		</tbody>
		</table>
	</fieldset>
	<input type="hidden" name="option" value="com_alphauserpoints" />
	<input type="hidden" name="task" value="processarchive" />
	<input type="hidden" name="boxchecked" value="0" />
</form>
