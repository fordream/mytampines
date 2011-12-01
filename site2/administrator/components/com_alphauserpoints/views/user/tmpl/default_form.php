<?php
/*
 * @component AlphaUserPoints
 * @copyright Copyright (C) 2008-2010 Bernard Gilly
 * @license : GNU/GPL
 * @Website : http://www.alphaplug.com
 */

defined( '_JEXEC' ) or die( 'Restricted access' );

JToolBarHelper::title(   JText::_( 'AUP_ACTIVITY' ) . ': ' . $this->name, 'addedit' );
JToolBarHelper::custom( 'cpanel', 'default.png', 'default.png', JText::_('AUP_CPANEL'), false );
JToolBarHelper::save( 'saveuserdetails' );
JToolBarHelper::cancel( 'canceluserdetails' );
JToolBarHelper::help( 'screen.alphauserpoints', true );

$row = $this->row;
$rulename = $this->rulename;

JRequest::setVar( 'hidemainmenu', 1 );
?>
<form action="index.php?option=com_alphauserpoints" method="post" name="adminForm">
	<fieldset class="adminform">
		<legend><?php echo JText::_( 'AUP_DETAILS' ); ?></legend>
		<table class="admintable">
		<tbody>
		<tr>
			<td class="key">
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'AUP_REFERREID' ); ?>::<?php echo JText::_('AUP_REFERREID'); ?>">
					<?php echo JText::_( 'AUP_REFERREID' ); ?>:
				</span>
			</td>
			<td>
				<?php echo "<font color='green'>" . JText::_($row->referreid) . "</font>"; ?>
			</td>
		</tr>
		<tr>
			<td class="key">
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'AUP_RULE' ); ?>::<?php echo JText::_('AUP_RULE'); ?>">
					<?php echo JText::_( 'AUP_RULE' ); ?>:
				</span>
			</td>
			<td>
				<?php echo "<font color='green'>" . JText::_($rulename) . "</font>"; ?>
			</td>
		</tr>
		<tr>
			<td class="key">
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'AUP_DATE' ); ?>::<?php echo JText::_('AUP_DATE'); ?>">
					<?php echo JText::_( 'AUP_DATE' ); ?>:
				</span>
			</td>
			<td>
				    <?php echo JHTML::_('calendar', $row->insert_date, 'insert_date', 'insert_date', '%Y-%m-%d %H:%M:%S', array('class'=>'inputbox', 'size'=>'20',  'maxlength'=>'19')); ?>
			</td>
		</tr>

		<tr>
			<td class="key">
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'AUP_POINTS' ); ?>::<?php echo JText::_('AUP_POINTS'); ?>">
					<?php echo JText::_( 'AUP_POINTS' ); ?>:
				</span>
			</td>
			<td>
				<input class="inputbox" type="text" name="points" id="points" size="20" maxlength="255" value="<?php echo $row->points; ?>" />
			</td>
		</tr>
		<tr>
			<td class="key">
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'AUP_EXPIRE' ); ?>::<?php echo JText::_('AUP_EXPIRE'); ?>">
					<?php echo JText::_( 'AUP_EXPIRE' ); ?>:
				</span>
			</td>
			<td>
				    <?php echo JHTML::_('calendar', $row->expire_date, 'expire_date', 'expire_date', '%Y-%m-%d %H:%M:%S', array('class'=>'inputbox', 'size'=>'20',  'maxlength'=>'19')); ?>
			</td>
		</tr>
		
		<tr>
			<td class="key">
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'AUP_DATA' ); ?>::<?php echo JText::_('AUP_DATA'); ?>">
					<?php echo JText::_( 'AUP_DATA' ); ?>:
				</span>
			</td>
			<td>
				<input class="inputbox" type="text" name="datareference" id="datareference" size="80" maxlength="255" value="<?php echo $row->datareference; ?>" />
			</td>
		</tr>
		</tbody>
		</table>
	</fieldset>
	<input type="hidden" name="option" value="com_alphauserpoints" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="id" value="<?php echo $row->id; ?>" />
	<input type="hidden" name="referreid" value="<?php echo $row->referreid; ?>" />	
	<input type="hidden" name="status" value="<?php echo $row->status; ?>" />
	<input type="hidden" name="rule" value="<?php echo $row->rule; ?>" />
	<input type="hidden" name="approved" value="<?php echo $row->approved; ?>" />
	<input type="hidden" name="keyreference" value="<?php echo $row->keyreference; ?>" />	
	<input type="hidden" name="redirect" value="showdetails&amp;cid=<?php echo $row->referreid; ?>&amp;name=<?php echo $this->name; ?>" />
	<input type="hidden" name="boxchecked" value="0" />
	<?php //echo JHTML::_( 'form.token' ); ?>
</form>