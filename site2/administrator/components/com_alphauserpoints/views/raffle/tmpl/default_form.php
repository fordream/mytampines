<?php
/*
 * @component AlphaUserPoints
 * @copyright Copyright (C) 2008-2010 Bernard Gilly
 * @license : GNU/GPL
 * @Website : http://www.alphaplug.com
 */

defined( '_JEXEC' ) or die( 'Restricted access' );

JToolBarHelper::title(   JText::_( 'AUP_RAFFLE' ), 'addedit' );
JToolBarHelper::custom( 'cpanel', 'default.png', 'default.png', JText::_('AUP_CPANEL'), false );
JToolBarHelper::save( 'saveraffle' );
JToolBarHelper::cancel( 'cancelraffle' );
JToolBarHelper::help( 'screen.alphauserpoints', true );

$row = $this->row;

JRequest::setVar( 'hidemainmenu', 1 );
?>
<script language="javascript" type="text/javascript">
function submitbutton(pressbutton, section) {
	var form = document.adminForm;
	
	if (pressbutton == 'cancelraffle') {
		submitform( pressbutton );
		return;
	}

	if ( form.description.value == "" ) {
		alert("<?php echo JText::_( 'AUP_YOU_MUST_ENTER_A_DESCRIPTION', true ); ?>");
	} else if ( form.pointstoearn1.value == "" &&  form.rafflesystem.value == '0' ){	
		alert("<?php echo JText::_( 'AUP_YOU_MUST_ENTER_POINTS', true ); ?>");
	} else {		
		submitform(pressbutton);
	}
}

function mxctoggleSystemRaffle(currcmt) {

	var prevcmt1;
	var prevcmt2;
	
	if (currcmt=='rafflesystemexpand0') {
		prevcmt1='rafflesystemexpand1';
		prevcmt2='rafflesystemexpand2';
	} else if (currcmt=='rafflesystemexpand1') {
		prevcmt1='rafflesystemexpand0';
		prevcmt2='rafflesystemexpand2';
	} else if (currcmt=='rafflesystemexpand2') {
		prevcmt1='rafflesystemexpand0';
		prevcmt2='rafflesystemexpand1';
	}		
	
	if (document.getElementById) {
		thisSystemRaffle = document.getElementById(currcmt).style;		
		thatSystemRaffle1 = document.getElementById(prevcmt1).style;	
		thatSystemRaffle2 = document.getElementById(prevcmt2).style;			
		thisSystemRaffle.display = "block";
		thatSystemRaffle1.display = "none";		
		thatSystemRaffle2.display = "none";		
		return false;
	}else {
		return true;
	}

}
</script>
<form action="index.php?option=com_alphauserpoints" method="post" name="adminForm">
	<fieldset class="adminform">
		<legend><?php echo JText::_( 'AUP_DETAILS' ); ?></legend>
		<table class="admintable">
		<tbody>
		<tr>
			<td class="key">
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'AUP_DESCRIPTION' ); ?>::<?php echo JText::_('AUP_DESCRIPTION'); ?>">
					<?php echo JText::_( 'AUP_DESCRIPTION' ); ?>:
				</span>
			</td>
			<td>
				<input class="inputbox" type="text" name="description" id="description" size="80" maxlength="255" value="<?php echo $row->description; ?>" />
			</td>
		</tr>
		<tr>
			<td class="key">
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'AUP_REGISTRATION' ); ?>::<?php echo JText::_('AUP_REGISTRATION_DESCRIPTION'); ?>">
					<?php echo JText::_( 'AUP_REGISTRATION' ); ?>:
				</span>
			</td>
			<td>
				<?php echo $this->lists['inscription']; ?>
			</td>
		</tr>
		<tr>
		  <td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_( 'AUP_MULTIPLE_ENTRIES' ); ?>::<?php echo JText::_('AUP_MULTIPLE_ENTRIES_DESCRIPTION'); ?>"><?php echo JText::_( 'AUP_MULTIPLE_ENTRIES' ); ?></span></td>
		  <td>
		 	<?php echo $this->lists['multipleentries']; ?>
		  </td>
		  </tr>
		<tr>
			<td class="key">
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'AUP_POINTS_TO_PARTICIPATE' ); ?>::<?php echo JText::_('AUP_POINTS_TO_PARTICIPATE_DESCRIPTION'); ?>">
					<?php echo JText::_( 'AUP_POINTS_TO_PARTICIPATE' ); ?>:
				</span>
			</td>
			<td>
				<input class="inputbox" type="text" name="pointstoparticipate" id="pointstoparticipate" size="20" maxlength="50" value="<?php echo $row->pointstoparticipate; ?>" />
			</td>
		</tr>
		<tr>
			<td class="key">
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'AUP_REMOVE_POINTS_TO_PARTICIPATE' ); ?>::<?php echo JText::_('AUP_REMOVE_POINTS_TO_PARTICIPATE_DESCRIPTION'); ?>">
					<?php echo JText::_( 'AUP_REMOVE_POINTS_TO_PARTICIPATE' ); ?>:
				</span>
			</td>
			<td>
				<?php echo $this->lists['removepointstoparticipate']; ?>
			</td>
		</tr>
		<tr>
			<td class="key">
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'AUP_NUM_OF_WINNER' ); ?>::<?php echo JText::_('AUP_NUM_OF_WINNER'); ?>">
					<?php echo JText::_( 'AUP_NUM_OF_WINNER' ); ?>:
				</span>
			</td>
			<td>			
				<?php echo $this->lists['numwinner']; ?>
			</td>
		</tr>
		<tr>
			<td class="key">
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'AUP_RAFFLE_SYSTEM' ); ?>::<?php echo JText::_('AUP_RAFFLE_SYSTEM_DESCRIPTION'); ?>">
					<?php echo JText::_( 'AUP_RAFFLE_SYSTEM' ); ?>:
				</span>
			</td>
			<td>
				<?php echo $this->lists['rafflesystem']; ?>
			</td>
		</tr>
	</table>
	
	<table class="admintable" id="rafflesystemexpand0" <?php if ($row->rafflesystem) echo "style=\"display:none\""; ?>>
		<tr>
			<td class="key">
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'AUP_POINTS_TO_WINNER_1' ); ?>::<?php echo JText::_('AUP_POINTS_TO_WINNER'); ?>">
					<?php echo JText::_( 'AUP_POINTS_TO_WINNER_1' ); ?>:
				</span>
			</td>
			<td>
				<input class="inputbox" type="text" name="pointstoearn1" id="pointstoearn1" size="20" maxlength="50" value="<?php echo $row->pointstoearn1; ?>" />
			</td>
		</tr>		
		<tr>
			<td class="key">
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'AUP_POINTS_TO_WINNER_2' ); ?>::<?php echo JText::_('AUP_POINTS_TO_WINNER'); ?>">
					<?php echo JText::_( 'AUP_POINTS_TO_WINNER_2' ); ?>:
				</span>
			</td>
			<td>
				<input class="inputbox" type="text" name="pointstoearn2" id="pointstoearn2" size="20" maxlength="50" value="<?php echo $row->pointstoearn2; ?>" />
			</td>
		</tr>		
		<tr>
			<td class="key">
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'AUP_POINTS_TO_WINNER_3' ); ?>::<?php echo JText::_('AUP_POINTS_TO_WINNER'); ?>">
					<?php echo JText::_( 'AUP_POINTS_TO_WINNER_3' ); ?>:
				</span>
			</td>
			<td>
				<input class="inputbox" type="text" name="pointstoearn3" id="pointstoearn3" size="20" maxlength="50" value="<?php echo $row->pointstoearn3; ?>" />
			</td>
		</tr>
	</table>
	
	<table class="admintable" id="rafflesystemexpand1" <?php if ( $row->rafflesystem==0 || $row->rafflesystem==2 ) echo "style=\"display:none\""; ?>>
		<tr>
			<td class="key">
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'AUP_COUPON_CODE_1' ); ?>::<?php echo JText::_('AUP_SELECT_A_COUPON_CODE_FOR_THIS_RANK'); ?>">
					<?php echo JText::_( 'AUP_COUPON_CODE_1' ); ?>:
				</span>
			</td>
			<td>
				<?php echo $this->lists['couponcodeid1']; ?>
			</td>
		</tr>
		<tr>
			<td class="key">
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'AUP_COUPON_CODE_2' ); ?>::<?php echo JText::_('AUP_SELECT_A_COUPON_CODE_FOR_THIS_RANK'); ?>">
					<?php echo JText::_( 'AUP_COUPON_CODE_2' ); ?>:
				</span>
			</td>
			<td>
				<?php echo $this->lists['couponcodeid2']; ?>
			</td>
		</tr>
		<tr>
			<td class="key">
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'AUP_COUPON_CODE_3' ); ?>::<?php echo JText::_('AUP_SELECT_A_COUPON_CODE_FOR_THIS_RANK'); ?>">
					<?php echo JText::_( 'AUP_COUPON_CODE_3' ); ?>:
				</span>
			</td>
			<td>
				<?php echo $this->lists['couponcodeid3']; ?>
			</td>
		</tr>
		<tr>
			<td class="key">
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'AUP_SEND_COUPON_CODE_BY_EMAIL' ); ?>::<?php echo JText::_('AUP_SEND_COUPON_CODE_BY_EMAIL'); ?>">
					<?php echo JText::_( 'AUP_SEND_COUPON_CODE_BY_EMAIL' ); ?>:
				</span>
			</td>
			<td>
				<?php echo $this->lists['sendcouponbyemail']; ?>
			</td>
		</tr>	
	</table>
	
	<table class="admintable" id="rafflesystemexpand2" <?php if ($row->rafflesystem==0 || $row->rafflesystem==1) echo "style=\"display:none\""; ?>>
		<tr>
			<td class="key">
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'AUP_MAIL_FILE_1' ); ?>::<?php echo JText::_('AUP_ENTER_A_LINK_TO_DOWNLOAD_A_FILE_FOR_THIS_RANK'); ?>">
					<?php echo JText::_( 'AUP_MAIL_FILE_1' ); ?>:
				</span>
			</td>
			<td>
				<input class="inputbox" type="text" name="link2download1" id="link2download1" size="80" maxlength="255" value="<?php echo $row->link2download1; ?>" />
			</td>
		</tr>
		<tr>
			<td class="key">
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'AUP_MAIL_FILE_2' ); ?>::<?php echo JText::_('AUP_ENTER_A_LINK_TO_DOWNLOAD_A_FILE_FOR_THIS_RANK'); ?>">
					<?php echo JText::_( 'AUP_MAIL_FILE_2' ); ?>:
				</span>
			</td>
			<td>
				<input class="inputbox" type="text" name="link2download2" id="link2download2" size="80" maxlength="255" value="<?php echo $row->link2download2; ?>" />
			</td>
		</tr>
		<tr>
			<td class="key">
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'AUP_MAIL_FILE_3' ); ?>::<?php echo JText::_('AUP_ENTER_A_LINK_TO_DOWNLOAD_A_FILE_FOR_THIS_RANK'); ?>">
					<?php echo JText::_( 'AUP_MAIL_FILE_3' ); ?>:
				</span>
			</td>
			<td>
				<input class="inputbox" type="text" name="link2download3" id="link2download3" size="80" maxlength="255" value="<?php echo $row->link2download3; ?>" />
			</td>
		</tr>
	</table>
		
		<table class="admintable">
		<tr>
			<td class="key">
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'AUP_RAFFLE_DATE' ); ?>::<?php echo JText::_('AUP_RAFFLE_DATE'); ?>">
					<?php echo JText::_( 'AUP_RAFFLE_DATE' ); ?>:
				</span>
			</td>
			<td>
				    <?php echo JHTML::_('calendar', $row->raffledate, 'raffledate', 'raffledate', '%Y-%m-%d %H:%M:%S', array('class'=>'inputbox', 'size'=>'20',  'maxlength'=>'19')); ?>
			</td>
		</tr>
		<tr>
			<td class="key">
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'AUP_PUBLISHED' ); ?>::<?php echo JText::_('AUP_PUBLISHED'); ?>">
					<?php echo JText::_( 'AUP_PUBLISHED' ); ?>:
				</span>
			</td>
			<td>			
				<?php echo $this->lists['published']; ?>
			</td>
		</tr>
		</tbody>
		</table>
	</fieldset>
	<input type="hidden" name="option" value="com_alphauserpoints" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="id" value="<?php echo $row->id; ?>" />
	<input type="hidden" name="redirect" value="raffle" />
	<input type="hidden" name="boxchecked" value="0" />
</form>