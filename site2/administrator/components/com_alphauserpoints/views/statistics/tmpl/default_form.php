<?php
/*
 * @component AlphaUserPoints
 * @copyright Copyright (C) 2008-2010 Bernard Gilly
 * @license : GNU/GPL
 * @Website : http://www.alphaplug.com
 */

defined( '_JEXEC' ) or die( 'Restricted access' );

$row = $this->row;
$listrank = $this->listrank;
$medalsexist = $this->medalsexist;

JToolBarHelper::title(   JText::_('AUP_USERS_POINTS') . ': ' . $row->name, 'user' );
JToolBarHelper::custom( 'cpanel', 'default.png', 'default.png', JText::_('AUP_CPANEL'), false );
JToolBarHelper::save( 'saveuser' );
JToolBarHelper::cancel( 'canceluser' );
JToolBarHelper::help( 'screen.alphauserpoints', true );

JRequest::setVar( 'hidemainmenu', 1 );

?>
<form action="index.php?option=com_alphauserpoints" method="post" name="adminForm">
	<fieldset class="adminform">
		<legend><?php echo JText::_( 'AUP_DETAILS' ); ?></legend>
		<table class="admintable">
		<tbody>
		<tr>
			<td class="key">
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'AUP_NAME' ); ?>::<?php echo JText::_('AUP_NAME'); ?>">
					<?php echo JText::_( 'AUP_NAME' ); ?>:
				</span>
			</td>
			<td>
				<?php 
					echo "<font color='green'>" . JText::_($row->name) . "</font>"; 
				?>
			</td>
		</tr>
		<tr>
			<td class="key">
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'AUP_USERNAME' ); ?>::<?php echo JText::_('AUP_USERNAME'); ?>">
					<?php echo JText::_( 'AUP_USERNAME' ); ?>:
				</span>
			</td>
			<td>
				<?php 
					echo "<font color='green'>" . JText::_($row->username) . "</font>"; 
				?>
			</td>
		</tr>
		<tr>
			<td class="key">
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'AUP_REFERREID' ); ?>::<?php echo JText::_('AUP_REFERREID'); ?>">
					<?php echo JText::_( 'AUP_REFERREID' ); ?>:
				</span>
			</td>
			<td>
				<?php 
					echo "<font color='green'>" . JText::_($row->referreid) . "</font>"; 
				?>
			</td>
		</tr>
		<tr>
			<td class="key">
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'AUP_POINTS' ); ?>::<?php echo JText::_('AUP_POINTS'); ?>">
					<?php echo JText::_( 'AUP_POINTS' ); ?>:
				</span>
			</td>
			<td>
				<input class="inputbox" type="text" name="points" id="points" size="20" maxlength="255" value="<?php echo $row->points; ?>"  readonly="readonly"/>
			</td>
		</tr>
		<tr>
			<td class="key">
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'AUP_MAXPOINTS' ); ?>::<?php echo JText::_('AUP_MAXPOINTS'); ?>">
					<?php echo JText::_( 'AUP_MAXPOINTS' ); ?>:
				</span>
			</td>
			<td>
				<input class="inputbox" type="text" name="max_points" id="max_points" size="20" maxlength="255" value="<?php echo $row->max_points; ?>" />
			</td>
		</tr>
		<tr>
			<td class="key">
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'AUP_RANK' ); ?>::<?php echo JText::_('AUP_LEVEL/RANK'); ?>">
					<?php echo JText::_( 'AUP_LEVEL/RANK' ); ?>:
				</span>
			</td>
			<td>
				<?php echo $listrank; ?>
			</td>
		</tr>
		<?php 
			if ( $row->leveldate != '0000-00-00' ) {
		?>
		<tr>
			<td class="key">
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'AUP_DATE' ); ?>::<?php echo JText::_('AUP_DATE'); ?>">
					<?php echo JText::_( 'AUP_DATE' ); ?>:
				</span>
			</td>
			<td>
			<?php
				echo JHTML::_('date',  $row->leveldate,  JText::_('DATE_FORMAT_LC') );
			?>
			</td>
		<?php } ?>
		</tr>
		</tbody>
		</table>
	</fieldset>
	<input type="hidden" name="option" value="com_alphauserpoints" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="id" value="<?php echo $row->id; ?>" />
	<input type="hidden" name="userid" value="<?php echo $row->userid; ?>" />
	<input type="hidden" name="referreid" value="<?php echo $row->referreid; ?>" />
	<input type="hidden" name="referraluser" value="<?php echo $row->referraluser; ?>" />	
	<input type="hidden" name="oldrank" value="<?php echo $row->levelrank; ?>" />	
	<input type="hidden" name="upnid" value="<?php echo $row->upnid; ?>" />
	<input type="hidden" name="referrees" value="<?php echo $row->referrees; ?>" />	
	<input type="hidden" name="blocked" value="<?php echo $row->blocked; ?>" />
	<input type="hidden" name="birthdate" value="<?php echo $row->birthdate; ?>" />
	<input type="hidden" name="avatar" value="<?php echo $row->avatar; ?>" />
	<input type="hidden" name="gender" value="<?php echo $row->gender; ?>" />
	<input type="hidden" name="aboutme" value="<?php echo $row->aboutme; ?>" />
	<input type="hidden" name="website" value="<?php echo $row->website; ?>" />
	<input type="hidden" name="phonehome" value="<?php echo $row->phonehome; ?>" />
	<input type="hidden" name="phonemobile" value="<?php echo $row->phonemobile; ?>" />
	<input type="hidden" name="address" value="<?php echo $row->address; ?>" />
	<input type="hidden" name="zipcode" value="<?php echo $row->zipcode; ?>" />
	<input type="hidden" name="city" value="<?php echo $row->city; ?>" />
	<input type="hidden" name="country" value="<?php echo $row->country; ?>" />
	<input type="hidden" name="education" value="<?php echo $row->education; ?>" />
	<input type="hidden" name="graduationyear" value="<?php echo $row->graduationyear; ?>" />
	<input type="hidden" name="job" value="<?php echo $row->job; ?>" />
	<input type="hidden" name="facebook" value="<?php echo $row->facebook; ?>" />
	<input type="hidden" name="twitter" value="<?php echo $row->twitter; ?>" />
	<input type="hidden" name="icq" value="<?php echo $row->icq; ?>" />
	<input type="hidden" name="aim" value="<?php echo $row->aim; ?>" />
	<input type="hidden" name="yim" value="<?php echo $row->yim; ?>" />
	<input type="hidden" name="msn" value="<?php echo $row->msn; ?>" />
	<input type="hidden" name="skype" value="<?php echo $row->skype; ?>" />
	<input type="hidden" name="gtalk" value="<?php echo $row->gtalk; ?>" />
	<input type="hidden" name="xfire" value="<?php echo $row->xfire; ?>" />		
	<input type="hidden" name="profileviews" value="<?php echo $row->profileviews; ?>" />	
	<input type="hidden" name="redirect" value="statistics" />
	<input type="hidden" name="boxchecked" value="0" />
</form>
<?php
if ( $medalsexist ) {
?>
	<?php
	if ( $this->medalslistuser ) {
	?>
	<fieldset class="adminform">
		<legend><?php echo JText::_( 'AUP_MEDALS' ); ?></legend>
		<table class="admintable" width="100%">
		<tbody>
		<?php foreach ( $this->medalslistuser as $medaluser ) { ?>
		<tr>
			<td class="key">
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'AUP_NAME' ); ?>::<?php echo JText::_('AUP_NAME'); ?>">
					<?php echo $medaluser->medaldate; ?>
				</span>
			</td>
			<td>
				<?php 
					$linkdelete = '&nbsp;&nbsp;(<a href="index.php?option=com_alphauserpoints&amp;task=removemedaluser&amp;cid='.$medaluser->id.'&amp;rid='.$row->id.'">'.JText::_( 'AUP_DELETE' ).'</a>)';
					echo $medaluser->rank . " - " . $medaluser->reason . $linkdelete ;
				?>
			</td>
		</tr>
		<?php } ?>
		</tbody>
		</table>
	</fieldset>
	<?php } ?>
		<form action="index.php?option=com_alphauserpoints" method="post" name="adminForm2">
			<fieldset class="adminform">
				<legend><?php echo JText::_( 'AUP_AWARDED_NEW_MEDAL' ); ?></legend>
				<table class="admintable" width="100%">
				<tbody>
				<tr>
					<td class="key"><?php echo JText::_( 'AUP_MEDAL' ); ?>
					</td>
					<td>
					  <?php echo $this->listmedals; ?>
				  </td>
				</tr>
				<tr>
					<td class="key"><?php echo JText::_( 'AUP_DESCRIPTION' ); ?>
					</td>
					<td>
					  <input type="text" id="reason" name="reason" class="inputbox" value="" size="80" /> (<?php echo JText::_( 'AUP_OPTIONAL' ). ' - ' . JText::_( 'AUP_DESCRIPTION_MEDAL_BY_DEFAULT' ) ; ?>)
				  </td>
				</tr>		
				<tr>
					<td class="key">&nbsp;
					</td>
					<td>
					  <input type="submit" name="Submit" class="button" value="<?php echo  JText::_('AUP_AWARDED_NEW_MEDAL'); ?>" />
				  </td>
				</tr>		
				</tbody>
				</table>
			</fieldset>
			<input type="hidden" name="option" value="com_alphauserpoints" />
			<input type="hidden" name="task" value="awardedmedal" />
			<input type="hidden" name="rid" value="<?php echo $row->id; ?>" />
			<input type="hidden" name="userid" value="<?php echo $row->userid; ?>" />
			<input type="hidden" name="referreid" value="<?php echo $row->referreid; ?>" />
			<input type="hidden" name="referraluser" value="<?php echo $row->referraluser; ?>" />	
			<input type="hidden" name="redirect" value="edituser" />
			<input type="hidden" name="cid[]" value="<?php echo $row->id; ?>" />
			<input type="hidden" name="boxchecked" value="0" />
		</form>
<?php } ?>