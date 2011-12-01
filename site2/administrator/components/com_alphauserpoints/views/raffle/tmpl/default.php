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
JToolBarHelper::publishList();
JToolBarHelper::unpublishList();
//JToolBarHelper::customX( 'copyrule', 'copy.png', 'copy.png', JText::_('AUP_COPY') ); // for future version, not available now
JToolBarHelper::editList( 'editraffle' );
JToolBarHelper::addNew( 'editraffle' );
JToolBarHelper::custom( 'deleteraffle', 'delete.png', 'delete.png', JText::_('AUP_DELETE') );
JToolBarHelper::help( 'screen.alphauserpoints', true );

?>
<form action="index.php?option=com_alphauserpoints" method="post" name="adminForm">

	<table class="adminlist" cellpadding="1">
		<thead>
			<tr>
				<th width="2%" class="title">
					<?php echo JText::_( 'NUM' ); ?>
				</th>
				<th width="3%" class="title">
					<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($this->raffle); ?>);" />
				</th>
				<th width="2%" class="title" >
					<?php echo JText::_('AUP_ID'); ?>
				</th>
				<th class="title" >
					<?php echo JText::_('AUP_DESCRIPTION'); ?>
				</th>
				<th width="10%" class="title" nowrap="nowrap">
					<?php echo JText::_( 'AUP_REGISTRATION' ); ?>
				</th>
				<th width="10%" class="title" nowrap="nowrap">
					<?php echo JText::_( 'AUP_POINTS_TO_PARTICIPATE' ); ?>
				</th>
				<th width="8%" class="title" nowrap="nowrap">
					<?php echo JText::_( 'AUP_REGISTERED' ); ?>
				</th>
				<th width="10%" class="title" nowrap="nowrap">
					<?php echo JText::_( 'AUP_NUM_OF_WINNER' ); ?>
				</th>
				<th width="10%" class="title">
					<?php echo JText::_( 'AUP_RAFFLE_SYSTEM' ); ?>
				</th>
				<th width="10%" class="title">
					<?php echo JText::_( 'AUP_RAFFLE_DATE' ); ?>
				</th>
				<th class="title" nowrap="nowrap">
					<?php echo JText::_( 'AUP_WINNERS' ); ?>
				</th>
				<th width="5%" class="title" nowrap="nowrap">
					<?php echo JText::_( 'AUP_PUBLISHED' ); ?>
				</th>
				<th width="10%" class="title" nowrap="nowrap">
					<?php echo JText::_( 'AUP_RAFFLE' ); ?>
				</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="13">
					<?php echo $this->pagination->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
		<tbody>
		<?php
			$k = 0;
			for ($i=0, $n=count( $this->raffle ); $i < $n; $i++)
			{
				$row 	=& $this->raffle[$i];
			
				$prefix = "";

				$img 	= $row->published ? 'publish_x.png' : 'tick.png';
				$link 	= 'index.php?option=com_alphauserpoints&amp;task=editraffle&amp;cid[]='. $row->id. '';
				$link2  = 'index.php?option=com_alphauserpoints&amp;task=makeraffle&amp;cid[]='. $row->id. '';
		
				$published 	= JHTML::_('grid.published', $row, $i );
				
				$imgA 	 = $row->inscription ? 'publish_g.png' :'publish_r.png';
				$taskA 	 = $row->inscription ? 'unregistration' : 'registration';
				$altA	 = $row->inscription ? JText::_( 'AUP_REGISTRATION' ) : JText::_( 'AUP_REGISTRATION' );
				$actionA = $row->inscription ? JText::_( 'AUP_REGISTRATION' ) : JText::_( 'AUP_REGISTRATION' );
		
				$registration = '<a href="javascript:void(0);" onclick="return listItemTask(\'cb'. $i .'\',\''. $prefix.$taskA .'\')" title="'. $actionA .'">
				<img src="images/'. $imgA .'" border="0" alt="'. $altA .'" /></a>'
				;				
				
				$db =& JFactory::getDBO();		

				$nullDate 		= $db->getNullDate();				
				
			?>
			<tr class="<?php echo "row$k"; ?>">
				<td>
					<?php echo $i+1+$this->pagination->limitstart;?>
				</td>
				<td align="center">
					<?php echo JHTML::_('grid.id', $i, $row->id ); ?>
				</td>
				<td align="center">
					<b><?php echo $row->id; ?></b>		
				</td>
				<td>
					<a href="<?php echo $link; ?>">
						<?php echo JText::_( $row->description ); ?>
					</a>
				</td>				
				<td align="center">
					<?php echo $registration; ?>				
				</td>
				<td align="center">
					<?php echo $row->pointstoparticipate; ?>
				</td>
				<td align="center">
					<?php 
					if ( !$row->inscription ) {
						echo "-"; 
					} else {
						if ( $row->numregistered>=1 ) {
							echo "<a href=\"index.php?option=com_alphauserpoints&amp;task=exportListUsersRaffle&amp;id=".$row->id."\">".$row->numregistered."</a>";
						} else echo $row->numregistered;
					}
					?>
				</td>				
				<td align="center">
					<?php echo $row->numwinner; ?>
				</td>
				<td align="center">
					<?php 					
					//$rafflesystem = ( $row->rafflesystem ) ? JText::_( 'AUP_COUPON_CODES' ) : JText::_( 'AUP_POINTS' ) ;
					switch ( $row->rafflesystem ) {
						case '2':						
							$rafflesystem =  JText::_( 'AUP_EMAIL_WITH_A_LINK_TO_DOWNLOAD' );
							break;
						case '1':						
							$rafflesystem =  JText::_( 'AUP_COUPON_CODES' );
							break;
						case '0':
						default:		
							$rafflesystem =  JText::_( 'AUP_POINTS' );					
					}					
					echo $rafflesystem; 					
					?>
				</td>
				<td align="center">
					<?php 
					if ( $row->raffledate == $nullDate ) {
						echo '-';
					} else {
						echo JHTML::_('date',  $row->raffledate,  JText::_('DATE_FORMAT_LC2'), 0 ) . '<br />' . nicetime($row->raffledate, 0);
					}
					?>
				</td>
				<td align="center">
					<?php			
					if ( $row->winner1 ) {					
						$db			    =& JFactory::getDBO();
						
						$query = "SELECT u.*, aup.referreid FROM #__users AS u, #__alpha_userpoints AS aup"
								. "\n WHERE u.id = $row->winner1 AND u.id = aup.userid";
								;
						$db->setQuery($query);
						$result = $db->loadObjectList();
						if ( $result ) {
							foreach ( $result as $winner ) {
								$linkuser1 = "index.php?option=com_alphauserpoints&task=showdetails&cid=$winner->referreid&name=$winner->name";
								echo "<a href=\"$linkuser1\">";
								echo $winner->name;	
								echo "</a>";				
							}
						}
						
						$query = "SELECT u.*, aup.referreid FROM #__users AS u, #__alpha_userpoints AS aup"
								. "\n WHERE u.id = $row->winner2 AND u.id = aup.userid";
								;
						$db->setQuery($query);
						$result2 = $db->loadObjectList();
						if ( $result2 ) {
							foreach ( $result2 as $winner ) {
								$linkuser2 = "index.php?option=com_alphauserpoints&task=showdetails&cid=$winner->referreid&name=$winner->name";
								echo "<a href=\"$linkuser2\">";
								echo ", " . $winner->name;
								echo "</a>";			
							}
						}
						
						$query = "SELECT u.*, aup.referreid FROM #__users AS u, #__alpha_userpoints AS aup"
								. "\n WHERE u.id = $row->winner3 AND u.id = aup.userid";
								;
						$db->setQuery($query);
						$result3 = $db->loadObjectList();
						if ( $result3 ) {
							foreach ( $result3 as $winner ) {
								$linkuser3 = "index.php?option=com_alphauserpoints&task=showdetails&cid=$winner->referreid&name=$winner->name";
								echo "<a href=\"$linkuser3\">";
								echo ", " . $winner->name;
								echo "</a>";
							}
						}
									
					} else echo "<i>". JText::_('AUP_PENDING') . "</i>";
					?>
				</td>
				<td align="center">
					<?php echo $published; ?>
				</td>
				<td align="center">
				<?php if ( !$row->winner1 ) { ?>
					<a href="<?php echo $link2; ?>">
						<?php echo JText::_( 'AUP_MAKE_THE_RAFFLE_NOW' ); ?>
					</a>
				<?php } else echo JText::_( 'AUP_THIS_RAFFLE_HAS_BEEN_PROCEEDED' ); ?>
				</td>
			</tr>
			<?php
				$k = 1 - $k;
				}
			?>
		</tbody>
	</table>
	<input type="hidden" name="option" value="com_alphauserpoints" />
	<input type="hidden" name="task" value="raffle" />
	<input type="hidden" name="table" value="alpha_userpoints_raffle" />
	<input type="hidden" name="redirect" value="raffle" />
	<input type="hidden" name="boxchecked" value="0" />
	<?php //echo JHTML::_( 'form.token' ); ?>
</form>