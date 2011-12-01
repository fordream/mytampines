<?php
/*
 * @component AlphaUserPoints
 * @copyright Copyright (C) 2008 Bernard Gilly
 * @license : GNU/GPL
 * @Website : http://www.alphaplug.com
 */

defined( '_JEXEC' ) or die( 'Restricted access' );

JToolBarHelper::title(   JText::_( 'AUP_COUPON_CODES' ), 'addedit' );
JToolBarHelper::custom( 'cpanel', 'default.png', 'default.png', JText::_('AUP_CPANEL'), false );
JToolBarHelper::editList( 'editcoupon' );
JToolBarHelper::addNew( 'editcoupon' );
JToolBarHelper::custom( 'deletecoupon', 'delete.png', 'delete.png', JText::_('AUP_DELETE') );
$bar = & JToolBar::getInstance('toolbar');
$bar->appendButton( 'Popup', 'upload', JText::_('AUP_GENERATOR'), 'index3.php?option=com_alphauserpoints&task=coupongenerator', 560, 380 );
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
					<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($this->couponcodes); ?>);" />
				</th>
				<th width="20%" class="title">
					<?php echo JText::_('AUP_CODE'); ?>
				</th>
				<th width="10%" class="title" nowrap="nowrap">
					<?php echo JText::_( 'AUP_POINTS' ); ?>
				</th>
				<th width="15%" class="title" nowrap="nowrap">
					<?php echo JText::_( 'AUP_EXPIRE' ); ?>
				</th>
				<th class="title" >
					<?php echo JText::_('AUP_DESCRIPTION'); ?>
				</th>
				<th width="5%" class="title" >
					<?php echo JText::_('AUP_PUBLIC'); ?>
				</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="12">
					<?php echo $this->pagination->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
		<tbody>
		<?php		
			$db =& JFactory::getDBO();
				
			$k = 0;
			
			$img = '<img src="images/tick.png" border="0" alt="'. JText::_('AUP_PUBLIC') .'" /></a>';			
			
			for ($i=0, $n=count( $this->couponcodes ); $i < $n; $i++)
			{
				$row 	=& $this->couponcodes[$i];
				
				$link 	= 'index.php?option=com_alphauserpoints&amp;task=editcoupon&amp;cid[]='. $row->id. '';
				
				$db =& JFactory::getDBO();		

				$nullDate 		= $db->getNullDate();			
				
				
				// check if the coupon is already awarded		
				if ( $row->public ) {
					$where =  "d.keyreference LIKE '".strtoupper($row->couponcode)."##%'";
				} else $where = "d.keyreference='".strtoupper($row->couponcode)."'";
				
				$query = "SELECT d.* FROM #__alpha_userpoints_details AS d, #__alpha_userpoints_rules AS r WHERE $where AND r.id=d.rule AND r.plugin_function='sysplgaup_couponpointscodes'";
				$db->setQuery( $query );
				$resultCoupons = $db->loadObjectList();
				
			?>
			<tr class="<?php echo "row$k"; ?>">
				<td>
					<?php echo $i+1+$this->pagination->limitstart;?>
				</td>
				<td align="center">
					<?php 
						echo JHTML::_('grid.id', $i, $row->id );						
					?>
				</td>
				<td>					
						<?php
						if ( $resultCoupons ) {
						 	echo "<b><s>" . JText::_( strtoupper($row->couponcode)) . "</s></b><br />";
							// show list user(s)
							echo '<span class="small">' . JText::_( 'AUP_AWARDED' ) . ': ';
							foreach ( $resultCoupons as $awardedcoupon ) {
								echo '<br />&nbsp;&nbsp;-&nbsp;' . $awardedcoupon->referreid . '&nbsp;('.JHTML::_('date',  $awardedcoupon->insert_date,  JText::_('DATE_FORMAT_LC2') ).')';										
							}
							echo '</span>';
						} else {
							echo '<a href="'.$link.'">';
							echo JText::_( strtoupper($row->couponcode) );
							echo '</a>';
						}
						?>					
				</td>
				<td>
					<div align="center">
					<?php echo JText::_( $row->points ); ?>
					</div>
				</td>
				<td>
					<div align="center">
					<?php 
					if ( $row->expires == $nullDate ) {
						echo '-';
					} else {
						echo JHTML::_('date',  $row->expires,  JText::_('DATE_FORMAT_LC') );
					}
					?>
					</div>
				</td>
				<td>		
					<?php echo JText::_( $row->description ); ?>							
				</td>
				<td>
					<div align="center">
					<?php					
					$public = ( $row->public ) ? $img : '';
					echo $public; 					
					?>
					</div>						
				</td>
			</tr>
			<?php
				$k = 1 - $k;
				}
			?>
		</tbody>
	</table>
	<input type="hidden" name="option" value="com_alphauserpoints" />
	<input type="hidden" name="task" value="couponcodes" />
	<input type="hidden" name="table" value="alpha_userpoints_coupons" />
	<input type="hidden" name="redirect" value="couponcodes" />
	<input type="hidden" name="boxchecked" value="0" />
</form>