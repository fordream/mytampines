<?php
/*
 * @component AlphaUserPoints
 * @copyright Copyright (C) 2008-2010 Bernard Gilly
 * @license : GNU/GPL
 * @Website : http://www.alphaplug.com
 */

defined( '_JEXEC' ) or die( 'Restricted access' );

JToolBarHelper::title(   JText::_( 'AUP_RULES' ), 'addedit' );
JToolBarHelper::custom( 'cpanel', 'default.png', 'default.png', JText::_('AUP_CPANEL'), false );
JToolBarHelper::publishList();
JToolBarHelper::unpublishList();
//JToolBarHelper::customX( 'copyrule', 'copy.png', 'copy.png', JText::_('AUP_COPY') ); // for future version, not available now
JToolBarHelper::editList( 'editrule' );
JToolBarHelper::addNew( 'plugins' );
JToolBarHelper::custom( 'deleterule', 'delete.png', 'delete.png', JText::_('AUP_DELETE') );
JToolBarHelper::help( 'screen.alphauserpoints', true );

?>
<form action="index.php?option=com_alphauserpoints" method="post" name="adminForm">
	<table>
		<tr>
			<td align="left" width="100%">&nbsp;
			
			</td>
			<td nowrap="nowrap">
				<?php
				echo JText::_('AUP_CATEGORY') . " " . $this->lists['filter_category'];
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
					<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($this->rules); ?>);" />
				</th>
				<th width="3%" class="title" nowrap="nowrap">&nbsp;
					
				</th>
				<th width="3%" class="title" nowrap="nowrap">&nbsp;
					
				</th>
				<th width="12%" class="title">
					<?php echo JText::_('AUP_RULENAME'); ?>
				</th>
				<th class="title" >
					<?php echo JText::_('AUP_DESCRIPTION'); ?>
				</th>
				<th width="6%" class="title" nowrap="nowrap">
					<?php echo JText::_( 'AUP_PLUGIN' ); ?>
				</th>
				<th width="10%" class="title" nowrap="nowrap">
					<?php echo JText::_( 'AUP_USERLEVEL' ); ?>
				</th>
				<th width="4%" class="title">
					<?php echo JText::_( 'AUP_POINTS' ); ?>
				</th>
				<th width="10%" class="title">
					<?php echo JText::_( 'AUP_EXPIRE' ); ?>
				</th>
				<th width="5%" class="title" nowrap="nowrap">
					<?php echo JText::_( 'AUP_PUBLISHED' ); ?>
				</th>
				<th width="5%" class="title" nowrap="nowrap">
					<?php echo JText::_( 'AUP_AUTOAPPROVED' ); ?>
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
			$k = 0;
			for ($i=0, $n=count( $this->rules ); $i < $n; $i++)
			{
				$row 	=& $this->rules[$i];
				
				$access = JHTML::_('grid.access', $row, $i );
				
				$prefix = "";

				$img 	= $row->published ? 'publish_x.png' : 'tick.png';
				$link 	= 'index.php?option=com_alphauserpoints&amp;task=editrule&amp;cid[]='. $row->id. '';
				
				$locked = $row->blockcopy ? '<img src="'.JURI::base(true).'/components/com_alphauserpoints/assets/images/locked.png" alt="" />' : '';
				
				$published 	= JHTML::_('grid.published', $row, $i );
				
				$imgA 	 = $row->autoapproved ? 'publish_g.png' :'publish_r.png';
				$taskA 	 = $row->autoapproved ? 'unautoapprove' : 'autoapprove';
				$altA	 = $row->autoapproved ? JText::_( 'AUP_AUTOAPPROVED' ) : JText::_( 'AUP_NOTAUTOAPPROVED' );
				$actionA = $row->autoapproved ? JText::_( 'AUP_UNAUTOAPPROVEITEM' ) : JText::_( 'AUP_AUTOAPPROVEITEM' );
		
				$autoapproved = '<a href="javascript:void(0);" onclick="return listItemTask(\'cb'. $i .'\',\''. $prefix.$taskA .'\')" title="'. $actionA .'">
				<img src="images/'. $imgA .'" border="0" alt="'. $altA .'" /></a>'
				;				
				
				$db =& JFactory::getDBO();

				$nullDate 		= $db->getNullDate();
				
				$category		= $row->category ? '<img src="../components/com_alphauserpoints/assets/images/categories/'.$row->category.'.gif" alt="" />' : '';
				
			?>
			<tr class="<?php echo "row$k"; ?>">
				<td>
					<?php echo $i+1+$this->pagination->limitstart;?>
				</td>
				<td align="center">
					<?php echo JHTML::_('grid.id', $i, $row->id ); ?>
				</td>
				<td align="center">
					<?php echo $locked; ?>
				</td>
				<td align="center">
					<?php echo $category; ?>
				</td>	
				<td>
					<a href="<?php echo $link; ?>">
						<?php echo JText::_( $row->rule_name ); ?>
					</a>
				</td>
				<td>
					<?php echo JText::_( $row->rule_description ); ?>
				</td>
				<td>
					<?php echo JText::_( $row->rule_plugin ); ?>
				</td>
				<td>									
					<?php
					
					switch ( $row->plugin_function ) {
					
						case 'sysplgaup_newregistered':
						case 'sysplgaup_referralpoints':						
						case 'sysplgaup_bonuspoints':
						case 'sysplgaup_excludeusers':
						case 'sysplgaup_emailnotification':
						case 'sysplgaup_winnernotification':
						case 'sysplgaup_archive':						
						//case 'sysplgaup_invitewithsuccess':
							// this rules can't be modified
							echo "<font color=\"grey\">".JText::_('Registered').", ".JText::_('Special')."</font>";						
							break;							
						case 'sysplgaup_becomeauthor':
						case 'sysplgaup_becomeeditor':
						case 'sysplgaup_becomepublisher':
							// this rules can't be modified
							echo "<font color=\"grey\">".JText::_('Registered')."</font>";						
							break;
						default:
							echo $access;		
					}					
					?>					
				</td>
				<td align="center">
					<?php
					switch ( $row->fixedpoints ) {
					
						case '0':
							echo  "-";
							break;
						case '1':
							echo $row->points; 
							if ( $row->percentage ) echo "%";					
					}
					
					?>
				</td>
				<td align="center">
					<?php 
					if ( $row->rule_expire == $nullDate ) {
						echo '-';
					} else {
						echo JHTML::_('date',  $row->rule_expire,  JText::_('DATE_FORMAT_LC') );
					}
					?>
				</td>
				<td align="center">
					<?php echo $published; ?>
				</td>
				<td align="center">
					<?php
					switch ( $row->plugin_function ) {
						case 'sysplgaup_excludeusers':
						case 'sysplgaup_emailnotification':
						case 'sysplgaup_winnernotification':
						case 'sysplgaup_becomeauthor':
						case 'sysplgaup_becomeeditor':
						case 'sysplgaup_becomepublisher':
						case 'sysplgaup_archive':		
							echo  "<img src=\"images/publish_y.png\" border=\"0\" title=\"".JText::_('AUP_NOT_AVAILABLE')."\" alt=\"".JText::_('AUP_NOT_AVAILABLE')."\" /></a>";
							break;						
						default:
							echo $autoapproved;						
					}					 
					?>
				</td>
			</tr>
			<?php
				$k = 1 - $k;
				}
			?>
		</tbody>
	</table>
	<input type="hidden" name="option" value="com_alphauserpoints" />
	<input type="hidden" name="task" value="rules" />
	<input type="hidden" name="system" value="<?php echo $row->system; ?>" />
	<input type="hidden" name="table" value="alpha_userpoints_rules" />
	<input type="hidden" name="redirect" value="rules" />
	<input type="hidden" name="boxchecked" value="0" />
</form>