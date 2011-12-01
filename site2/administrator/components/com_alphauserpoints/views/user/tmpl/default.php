<?php
/*
 * @component AlphaUserPoints
 * @copyright Copyright (C) 2008-2010 Bernard Gilly
 * @license : GNU/GPL
 * @Website : http://www.alphaplug.com
 */

defined( '_JEXEC' ) or die( 'Restricted access' );

JToolBarHelper::title(   JText::_( 'AUP_ACTIVITY' ) . ': ' . $this->name , 'searchtext' );
JToolBarHelper::custom( 'cpanel', 'default.png', 'default.png', JText::_('AUP_CPANEL'), false );
JToolBarHelper::back( 'Back' );
JToolBarHelper::editList( 'edituserdetails' );
JToolBarHelper::custom( 'deleteuserdetails', 'trash.png', 'delete.png', JText::_('AUP_DELETE') );
JToolBarHelper::custom( 'deleteuserallactivities', 'delete.png', 'delete.png', JText::_('AUP_DELETE_ALL'), false );
JToolBarHelper::custom( 'exportallactivitiesuser', 'export.png', 'export.png', JText::_('AUP_EXPORT_ACTIVITIES'), false );
$bar = & JToolBar::getInstance('toolbar');
$bar->appendButton( 'Popup', 'apply', JText::_('AUP_CUSTOM_POINTS'), 'index3.php?option=com_alphauserpoints&task=applycustom&cid='.$this->cid.'&name='.$this->name, 500, 280 );
JToolBarHelper::help( 'screen.alphauserpoints', true );
?>
<form action="index.php" method="post" name="adminForm">
	<table class="adminlist" cellpadding="1">
		<thead>
			<tr>
				<th width="2%" class="title">
					<?php echo JText::_( 'NUM' ); ?>
				</th>
				<th width="3%" class="title">
					<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($this->userDetails); ?>);" />
				</th>
				<th width="15%" class="title" nowrap="nowrap">
					<?php echo JText::_( 'AUP_DATE' ); ?>
				</th>
				<th width="20%" class="title">
					<?php echo JText::_('AUP_RULE'); ?>
				</th>
				<th width="5%" class="title" >
					<?php echo JText::_('AUP_POINTS'); ?>
				</th>
				<th width="15%" class="title">
					<?php echo JText::_( 'AUP_EXPIRE' ); ?>
				</th>
				<th width="5%" class="title" >
					<?php echo JText::_('AUP_APPROVED'); ?>
				</th>
				<th class="title">
					<?php echo JText::_( 'AUP_DATA' ); ?>
				</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="8">
					<?php echo $this->pagination->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
		<tbody>
		<?php
			$k = 0;
			for ($i=0, $n=count( $this->userDetails ); $i < $n; $i++)
			{
				$row 	=& $this->userDetails[$i];
				
				$link 	= 'index.php?option=com_alphauserpoints&amp;task=edituserdetails&amp;cid[]='. $row->id. '&amp;name=' . $this->name . '';
	
				$db =& JFactory::getDBO();
				
				$prefix = "";

				$nullDate 		= $db->getNullDate();
				
				$imgA 	 = $row->approved ? 'publish_g.png' :'publish_r.png';
				$taskA 	 = $row->approved ? 'unapprove' : 'approve';
				$altA	 = $row->approved ? JText::_( 'AUP_APPROVE' ) : JText::_( 'AUP_NOTAPPROVE' );
				$actionA = $row->approved ? JText::_( 'AUP_NOTAPPROVE' ) : JText::_( 'AUP_APPROVE' );
		
				$approved = '<a href="javascript:void(0);" onclick="return listItemTask(\'cb'. $i .'\',\''. $prefix.$taskA .'\')" title="'. $actionA .'">
				<img src="images/'. $imgA .'" border="0" alt="'. $altA .'" /></a>'
				;
				
				if ( $row->status ) {
					// already approved !
					$approved = "<img src=\"images/publish_g.png\" border=\"0\" title=\"".JText::_('AUP_ALREADY_APPROVED')."\" alt=\"".JText::_('AUP_ALREADY_APPROVED')."\" /></a>";
				}
				
			?>
			<tr class="<?php echo "row$k"; ?>">
				<td>
					<?php echo $i+1+$this->pagination->limitstart;?>
				</td>
				<td align="center">
					<?php echo JHTML::_('grid.id', $i, $row->id ); ?>
				</td>
				<td>
				<a href="<?php echo $link; ?>">
					<?php 
					if ( $row->insert_date == $nullDate ) {
						echo '-';
					} else {
						echo JHTML::_('date',  $row->insert_date,  JText::_('DATE_FORMAT_LC2') );
					}
					?>
				</a>
				</td>				
				<td>
					<?php echo JText::_( $row->rule_name ); ?>					
				</td>
				<td align="center">
					<?php echo JText::_( $row->points ); ?>
				</td>
				<td align="center">
					<?php 
					if ( $row->expire_date == $nullDate ) {
						echo '-';
					} else {
						echo JHTML::_('date',  $row->expire_date,  JText::_('DATE_FORMAT_LC2') );
					}
					?>
				</td>
				<td align="center">
					<?php echo $approved; ?>
				</td>
				<td>				
					<?php echo $row->datareference; ?>
				</td>
			</tr>
			<?php
				$k = 1 - $k;
				}
			?>
		</tbody>
	</table>
	<input type="hidden" name="option" value="com_alphauserpoints" />
	<input type="hidden" name="task" value="showdetails" />
	<input type="hidden" name="c2id" value="<?php echo $row->referreid; ?>" />
	<input type="hidden" name="name" value="<?php echo $this->name; ?>" />
	<input type="hidden" name="table" value="alpha_userpoints_details" />
	<input type="hidden" name="redirect" value="showdetails&amp;cid=<?php echo $row->referreid; ?>&amp;name=<?php echo $this->name; ?>" />
	<input type="hidden" name="boxchecked" value="0" />
</form>