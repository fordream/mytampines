<?php
/*
 * @component AlphaUserPoints
 * @copyright Copyright (C) 2008-2010 Bernard Gilly
 * @license : GNU/GPL
 * @Website : http://www.alphaplug.com
 */

defined( '_JEXEC' ) or die( 'Restricted access' );

JToolBarHelper::title(   JText::_( 'AUP_AWARDED' ), 'addedit' );
JToolBarHelper::custom( 'cpanel', 'default.png', 'default.png', JText::_('AUP_CPANEL'), false );
JToolBarHelper::back();
JToolBarHelper::help( 'screen.alphauserpoints', true );

$columns = 7;
?>
<form action="index.php?option=com_alphauserpoints" method="post" name="adminForm">
	<table class="adminlist" cellpadding="1">
		<thead>
			<tr>
				<th width="2%" class="title">
					<?php echo JText::_( 'NUM' ); ?>
				</th>
				<th width="5%" class="title">&nbsp;
					
				</th>
				<th width="12%" class="title">
					<?php echo JText::_('AUP_NAME'); ?>
				</th>
				<th width="10%" class="title" nowrap="nowrap">
					<?php echo JText::_( 'AUP_USERNAME' ); ?>
				</th>
				<th width="10%" class="title" nowrap="nowrap">
					<?php echo JText::_('AUP_DATE'); ?>
				</th>
				<th width="50%" class="title" nowrap="nowrap">
					<?php echo JText::_( 'AUP_REASON_FOR_AWARD' ); ?>
				</th>
				<th class="title" nowrap="nowrap">&nbsp;
										
				</th>		
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="<?php echo $columns ; ?>">
					<?php echo $this->pagination->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
		<tbody>
		<?php
			$k = 0;
			
			for ($i=0, $n=count( $this->detailrank ); $i < $n; $i++)			
			{
				$row 	=& $this->detailrank[$i];
				
				if ($row->icon ) {
					$pathicon = JURI::root() . 'components/com_alphauserpoints/assets/images/awards/icons/';
					$icone = '<img src="'.$pathicon . $row->icon.'" width="16" height="16" border="0" alt="" />';
				} else $icone = '';
				
			?>
			<tr class="<?php echo "row$k"; ?>">
				<td>
					<?php echo $i+1+$this->pagination->limitstart;?>
				</td>
				<td>
					<div align="center">
					<?php echo $icone; ?>
					</div>
				</td>
				<td>					
					<?php echo JText::_( $row->name ); ?>
				</td>
				<td>
					<?php echo JText::_( $row->username ); ?>
				</td>
				<td>
					<div align="center">
					<?php 
					echo JHTML::_('date',  $row->dateawarded,  JText::_('DATE_FORMAT_LC') );
					?>
					</div>
				</td>
				<td>					
					<?php 
					echo JText::_( $row->rank );
					if ( $row->reason ) echo ' - ' . JText::_( $row->reason ); 
					?>
				</td>
				<td>&nbsp;
				</td>
			</tr>
			<?php
				$k = 1 - $k;
				}
			?>
		</tbody>
	</table>
	<input type="hidden" name="option" value="com_alphauserpoints" />
	<input type="hidden" name="task" value="detailrank" />
	<input type="hidden" name="cid" value="<?php echo $row->cid ; ?>" />
	<input type="hidden" name="typerank" value="<?php echo $row->typerank ; ?>" />	
	<input type="hidden" name="table" value="" />
	<input type="hidden" name="redirect" value="detailrank" />
	<input type="hidden" name="boxchecked" value="0" />
</form>