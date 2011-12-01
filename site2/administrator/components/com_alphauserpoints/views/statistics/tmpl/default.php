<?php
/*
 * @component AlphaUserPoints
 * @copyright Copyright (C) 2008-2010 Bernard Gilly
 * @license : GNU/GPL
 * @Website : http://www.alphaplug.com
 */

defined( '_JEXEC' ) or die( 'Restricted access' );

JToolBarHelper::title(   JText::_('AUP_USERS'), 'user' );
JToolBarHelper::custom( 'cpanel', 'default.png', 'default.png', JText::_('AUP_CPANEL'), false );
JToolBarHelper::custom( 'applybonus', 'new.png', 'new.png', JText::_('AUP_BONUS'), true );
JToolBarHelper::custom( 'applycustomrule', 'apply.png', 'new.png', JText::_('AUP_CUSTOM_POINTS'), true );
JToolBarHelper::custom( 'exportallactivitiesallusers', 'export.png', 'export.png', JText::_('AUP_EXPORT_ACTIVITIES'), false );
JToolBarHelper::editList( 'edituser' );
JToolBarHelper::help( 'screen.alphauserpoints', true );

$numberofcolumns = 12;
?>
<form action="index.php" method="post" name="adminForm">
<table>
<tr>
	<td align="left" width="100%">
		<?php echo JText::_( 'Filter' ); ?>:
		<input type="text" name="search" id="search" value="<?php echo @$this->lists['search'];?>" class="text_area" onchange="document.adminForm.submit();" />
		<button onclick="this.form.submit();"><?php echo JText::_( 'Go' ); ?></button>
		<button onclick="document.getElementById('search').value='';this.form.submit();"><?php echo JText::_( 'Reset' ); ?></button>
	</td>
	<td align="right">
	<?php 
	if ( $this->ranksexist ) { 
		echo @$this->lists['levelrank']; 
	}
	?>
	</td>
</tr>
</table>
	<table class="adminlist" cellpadding="1">
		<thead>
			<tr>
				<th width="2%" class="title">
					<?php echo JText::_( 'NUM' ); ?>
				</th>
				<th width="3%" class="title">
					<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($this->usersStats); ?>);" />
				</th>
				<th width="3%" class="title">					
					<?php echo JHTML::_('grid.sort',   'AUP_ID', 'a.userid', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
				</th>
				<?php 
				if ( $this->ranksexist ) { 
					$numberofcolumns++;
				?>
				<th width="3%" class="title">					
					<?php echo JHTML::_('grid.sort',   'AUP_RANK', 'a.levelrank', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
				</th>
				<?php } ?>
				<?php 
				if ( $this->medalsexist ) { 
					$numberofcolumns++;
				?>
				<th width="3%" class="title">					
					<?php echo JText::_( 'AUP_MEDALS' ); ?>
				</th>
				<?php } ?>
				<th width="12%" class="title" >
					<?php echo JHTML::_('grid.sort',   'AUP_NAME', 'u.name', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
				</th>
				<th width="12%" class="title" nowrap="nowrap">
					<?php echo JHTML::_('grid.sort',   'AUP_USERNAME', 'u.username', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
				</th>
				<th width="12%" class="title" nowrap="nowrap">
                  <?php echo JText::_( 'AUP_REFERREID' ); ?> </th>
				<th width="6%" class="title">
					<?php echo JHTML::_('grid.sort',   'AUP_POINTS', 'a.points', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
				</th>
				<th width="5%" class="title">
					<?php echo JText::_( 'AUP_MAXPOINTS' ); ?>
				</th>
				<th width="18%" class="title" nowrap="nowrap">
					<?php echo JHTML::_('grid.sort',   'AUP_LASTUPDATE', 'a.last_update', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
				</th>
				<th width="10%" class="title" nowrap="nowrap">
					<?php echo JHTML::_('grid.sort',   'AUP_REFERRALUSER', 'a.referraluser', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
				</th>
				<th width="3%" class="title" nowrap="nowrap">
					<?php echo JText::_( 'AUP_REFERREES' ); ?>
				</th>
				<th class="title" nowrap="nowrap">
					<?php echo JText::_( 'AUP_ACTIVITY' ); ?>
				</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="<?php echo $numberofcolumns ?>">
					<?php echo $this->pagination->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
		<tbody>
		<?php
			$k = 0;
			
			$pathicon = JURI::root() . 'components/com_alphauserpoints/assets/images/awards/icons/';
			
			$db =& JFactory::getDBO();
			$nullDate 		= $db->getNullDate();
						
			for ($i=0, $n=count( $this->usersStats ); $i < $n; $i++)
			{
				$row 	=& $this->usersStats[$i];				
								
				$link 			= 'index.php?option=com_alphauserpoints&amp;task=edituser&amp;cid[]='. $row->id. '';
				$link_details 	= 'index.php?option=com_alphauserpoints&amp;task=showdetails&amp;cid='. $row->referreid. '&amp;name='. $row->name .'';	
				
				$queryicon = "SELECT icon FROM #__alpha_userpoints_levelrank WHERE id=".$row->levelrank;
				$db->setQuery( $queryicon );
				$icon = $db->loadResult();
				if ( $icon ) {
					$icone = '<img src="'.$pathicon . $icon .'" width="16" height="16" border="0" style="vertical-align:middle" alt="" /> ';
				} else $icone = "";
				
				$querymedals = "SELECT COUNT(*) FROM #__alpha_userpoints_medals WHERE rid=".$row->id;
				$db->setQuery( $querymedals );
				$medals = $db->loadResult();
				if ( $medals ) {
					$nummedals = $medals;
				} else $nummedals = "";
				
			?>
			<tr class="<?php echo "row$k"; ?>">
				<td align="center">
					<?php echo $i+1+$this->pagination->limitstart;?>
				</td>
				<td align="center">
					<?php echo JHTML::_('grid.id', $i, $row->id ); ?>
				</td>
				<td align="center">
					<?php echo $row->userid;?>
				</td>
				<?php 
				if ( $this->ranksexist ) { ?>
				<td align="center">					
					<?php echo $icone; ?> 
				</td>
				<?php } ?>
				<?php 
				if ( $this->medalsexist ) { ?>
				<td align="center">					
					<?php echo $nummedals; ?> 
				</td>
				<?php } ?>				
				<td>					
					<?php echo $row->name; ?> 
				</td>
				<td>
					<?php echo $row->username; ?>
				</td>
				<td>
					<a href="<?php echo $link; ?>" title="<?php echo JText::_('AUP_USER_DETAILS'); ?>">
					<?php echo $row->referreid; ?>
					</a>
				</td>
				<td align="center">				
					<?php echo $row->points; ?>
				</td>
				<td align="center">
					<?php echo $row->max_points; ?>
				</td>
				<td align="center">
					<?php 
					if ( $row->last_update == $nullDate ) {
						echo '-';
					} else {
						echo JHTML::_('date',  $row->last_update,  JText::_('DATE_FORMAT_LC2') );
					}
					?>
				</td>
				<td>				
					<?php echo $row->referraluser; ?>
				</td>
				<td align="center">				
					<?php 
					$nb_referrees = ( $row->referrees ) ? $row->referrees : "" ;
					echo $nb_referrees;					 
					?>
				</td>
				<td align="center">
					<b><a href="<?php echo $link_details; ?>" title="<?php echo JText::_('AUP_DETAILS_ALL_POINTS'); ?>">
					<?php echo JText::_('AUP_DETAILS'); ?>
					</a></b>
				</td>
			</tr>
			<?php
				$k = 1 - $k;
				}
			?>
		</tbody>
	</table>
	<input type="hidden" name="option" value="com_alphauserpoints" />
	<input type="hidden" name="task" value="statistics" />
	<input type="hidden" name="table" value="alpha_userpoints" />
	<input type="hidden" name="redirect" value="statistics" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="filter_order" value="<?php echo @$this->lists['order']; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo @$this->lists['order_Dir']; ?>" />	
</form>

