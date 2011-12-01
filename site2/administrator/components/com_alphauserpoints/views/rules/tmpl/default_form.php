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
JToolBarHelper::save( 'saverule' );
JToolBarHelper::cancel( 'cancelrule' );
JToolBarHelper::help( 'screen.alphauserpoints', true );

$row = $this->row;
$pos = strpos( $row->plugin_function, 'sysplgaup_' );
$disabled = ( $pos === false ) ? 0 : 1;
$duplicate = $row->duplicate;
$system = $row->system;

JRequest::setVar( 'hidemainmenu', 1 );
?>
<form action="index.php?option=com_alphauserpoints" method="post" name="adminForm">
	<fieldset class="adminform">
		<legend><?php echo JText::_( 'AUP_DETAILS' ); ?></legend>
		<table class="admintable">
		<tbody>
		<tr>
			<td class="key">
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'AUP_CATEGORY' ); ?>::<?php echo JText::_('AUP_CATEGORY'); ?>">
					<?php echo JText::_( 'AUP_CATEGORY' ); ?>:
				</span>
			</td>
			<td>
			<?php echo $this->lists['category']; ?>
			</td>
		</tr>
		<tr>
			<td class="key">
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'AUP_RULENAME' ); ?>::<?php echo JText::_('AUP_THISFIELDCANBEMODIFIEDINLANGUAGEFILE'); ?>">
					<?php echo JText::_( 'AUP_RULENAME' ); ?>:
				</span>
			</td>
			<td>
				<?php 
				if ( !$disabled || $duplicate )  { ?>
				<input class="inputbox" type="text" name="rule_name" id="rule_name" size="80" maxlength="255" value="<?php echo JText::_($row->rule_name); ?>" />
				<?php
				} else {
					echo "<font color='green'>" . JText::_($row->rule_name) . "</font>"; 
					?>
					<input type="hidden" name="rule_name" value="<?php echo $row->rule_name; ?>" />
					<?php
				}
				?>
			</td>
		</tr>
		<tr>
			<td class="key">
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'AUP_DESCRIPTION' ); ?>::<?php echo JText::_('AUP_THISFIELDCANBEMODIFIEDINLANGUAGEFILE'); ?>">
					<?php echo JText::_( 'AUP_DESCRIPTION' ); ?>:
				</span>
			</td>
			<td>
				<?php 
				if ( !$disabled || $duplicate )  { ?>
				<input class="inputbox" type="text" name="rule_description" id="rule_description" size="80" maxlength="255" value="<?php echo JText::_($row->rule_description); ?>" />
				<?php
				} else {
					echo "<font color='green'>" . JText::_($row->rule_description) . "</font>"; 
					?>
					<input type="hidden" name="rule_description" value="<?php echo $row->rule_description; ?>" />
					<?php
				}
				?>
			</td>
		</tr>
		<tr>
			<td class="key">
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'AUP_PLUGIN' ); ?>::<?php echo JText::_('AUP_THISFIELDCANBEMODIFIEDINLANGUAGEFILE'); ?>">
					<?php echo JText::_( 'AUP_PLUGIN' ); ?>:
				</span>
			</td>
			<td>
				<?php 
				if ( !$disabled )  { ?>
				<input class="inputbox" type="text" name="rule_plugin" id="rule_plugin" size="20" maxlength="50" value="<?php echo JText::_($row->rule_plugin); ?>" />
				<?php
				} else {
					echo "<font color='green'>" . JText::_($row->rule_plugin) . "</font>"; 
					?>
					<input type="hidden" name="rule_plugin" value="<?php echo $row->rule_plugin; ?>" />
					<?php
				}
				?>
			</td>
		</tr>
		<?php
		
		 if ( $row->plugin_function!='sysplgaup_newregistered'
		  	&& $row->plugin_function!='sysplgaup_referralpoints' 
		     && $row->plugin_function!='sysplgaup_excludeusers' 
		      && $row->plugin_function!='sysplgaup_emailnotification' 
		       && $row->plugin_function!='sysplgaup_winnernotification' 
				&& $row->plugin_function!='sysplgaup_becomeauthor' 
				 && $row->plugin_function!='sysplgaup_becomeeditor' 
				  && $row->plugin_function!='sysplgaup_becomepublisher') { 
		 
		 ?>
		<tr>
			<td class="key">
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'AUP_USERLEVEL' ); ?>::<?php echo JText::_('AUP_USERLEVEL'); ?>">
					<?php echo JText::_( 'AUP_USERLEVEL' ); ?>:
				</span>
			</td>
			<td>
				<?php echo JHTML::_('list.accesslevel',  $row); ?>
			</td>
		</tr>
		<?php 
		
		} else { 
		
		?>
		<input type="hidden" name="access" value="1" />		
		<?php	
			
		}		
		 
		?>		
		<?php
		if ( $row->fixedpoints ) {

		?>
		<tr>
			<td class="key">
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'AUP_POINTS' ); ?>::<?php echo JText::_('AUP_ATTRIB_X_POINTS_TO_THIS_RULE'); ?>">
					<?php echo JText::_( 'AUP_POINTS' ); if ($row->percentage) echo " (" . JText::_( 'AUP_PERCENTAGE' ) . ")"; ?> :
				</span>
			</td>
			<td>			
				<input class="inputbox" type="text" name="points" id="points" size="10" maxlength="30" value="<?php echo $row->points; ?>" />
				<?php if ($row->percentage) echo " <b> %</b>" ; ?>
				<?php if ($row->plugin_function=='sysplgaup_becomeauthor' || $row->plugin_function=='sysplgaup_becomeeditor' || $row->plugin_function=='sysplgaup_becomepublisher') echo  JText::_( 'AUP_NUMBEROFPOINTSNECESSARY' ); ?>								
			</td>
		</tr>
		<?php
		} else { 		
		?>		
		<input type="hidden" name="points" value="<?php echo $row->points; ?>" />
		<?php
		} 
		?>
		<?php
		if ( $row->plugin_function!='sysplgaup_newregistered'
		 && $row->plugin_function!='sysplgaup_excludeusers'
		  && $row->plugin_function!='sysplgaup_emailnotification'
		   && $row->plugin_function!='sysplgaup_winnernotification' 
		    && $row->plugin_function!='sysplgaup_becomeauthor' 
			 && $row->plugin_function!='sysplgaup_becomeeditor' 
			  && $row->plugin_function!='sysplgaup_becomepublisher') { 
		?>
		<tr>
			<td class="key">
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'AUP_EXPIRE' ); ?>::<?php echo JText::_('AUP_EXPIRE'); ?>">
					<?php echo JText::_( 'AUP_EXPIRE' ); ?>:
				</span>
			</td>
			<td>
				    <?php echo JHTML::_('calendar', $row->rule_expire, 'rule_expire', 'rule_expire', '%Y-%m-%d %H:%M:%S', array('class'=>'inputbox', 'size'=>'20',  'maxlength'=>'19')); ?>
			</td>
		</tr>
		<?php 
		} else { 
		?>
		<input type="hidden" name="rule_expire" value="0000-00-00 00:00:00" />		
		<?php 
		}
		?>
		<?php if ( $row->plugin_function=='sysplgaup_excludeusers' ) { ?>
		<tr>
			<td class="key">
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'AUP_EXCLUDEUSERID' ); ?>::<?php echo JText::_('AUP_EXCLUDEUSERIDDESCRIPTION'); ?>">
					<?php echo JText::_( 'AUP_EXCLUDEUSERID' ); ?>:
				</span>
			</td>
			<td>
			<input class="inputbox" type="text" name="exclude_items" id="exclude_items" size="100" value="<?php echo $row->exclude_items; ?>" />
			</td>
		</tr>
		<?php }?>		
		<?php if ( $row->plugin_function=='sysplgaup_winnernotification' || $row->plugin_function=='plgaup_kunena_message_thankyou' ) { ?>
		<tr>
		<?php if ( $row->plugin_function=='sysplgaup_winnernotification' ) { ?>
			<td class="key">
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'AUP_EMAILDAMINS' ); ?>::<?php echo JText::_('AUP_EMAILDAMINSNOTIFICATION'); ?>">
					<?php echo JText::_( 'AUP_EMAILDAMINS' ); ?>:
				</span>
			</td>
			<td>
			<input class="inputbox" type="text" name="content_items" id="content_items" size="100" value="<?php echo $row->content_items; ?>" />
			</td>
		<?php } elseif ( $row->plugin_function=='plgaup_kunena_message_thankyou' ) { ?>
			<td class="key">
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'AUP_KU_POINT_USER_TARGET' ); ?>::<?php echo JText::_('AUP_KU_POINT_USER_TARGET_DESCRIPTION'); ?>">
					<?php echo JText::_( 'AUP_KU_POINT_USER_TARGET' ); ?>:
				</span>
			</td>
			<td>
			<input class="inputbox" type="text" name="content_items" id="content_items" size="10" value="<?php echo $row->content_items; ?>" />
			</td>
		<?php }?>		
		</tr>
		<?php }?>		
		<?php if ( $row->plugin_function=='sysplgaup_inactiveuser' ) { ?>
		<tr>
			<td class="key">
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'AUP_INACTIVE_PERIOD' ); ?>::<?php echo JText::_('AUP_INACTIVE_PERIOD'); ?>">
					<?php echo JText::_( 'AUP_INACTIVE_PERIOD' ); ?>:
				</span>
			</td>
			<td>			
			<?php echo $this->lists['inactive_preset_period'] ; ?>
			</td>
		</tr>
		<?php }?>		
		<?php if ( $row->plugin_function!='sysplgaup_newregistered' ) { ?>
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
		<?php } else { ?>
		<input type="hidden" name="published" value="1" />		
		<?php }?>
		<?php
		switch ( $row->plugin_function ) {
			case 'sysplgaup_excludeusers':
			case 'sysplgaup_emailnotification':
			case 'sysplgaup_winnernotification':
			case 'sysplgaup_becomeauthor':
			case 'sysplgaup_becomeeditor':
			case 'sysplgaup_becomepublisher':
				echo "<input type=\"hidden\" name=\"autoapproved\" value=\"1\" />";
				break;										
			default:
				?>
			<tr>
				<td class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'AUP_AUTOAPPROVED' ); ?>::<?php echo JText::_('AUP_AUTOAPPROVED'); ?>">
						<?php echo JText::_( 'AUP_AUTOAPPROVED' ); ?>:
					</span>
				</td>
				<td>			
					<?php echo $this->lists['autoapproved']; ?><br />
					
				</td>
			</tr>
		<?php
		}					 
		?>
		</tbody>
		</table>
	</fieldset>
	<input type="hidden" name="option" value="com_alphauserpoints" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="id" value="<?php echo $row->id; ?>" />
	<input type="hidden" name="system" value="<?php echo $row->system; ?>" />
	<input type="hidden" name="plugin_function" value="<?php echo $row->plugin_function; ?>" />
	<input type="hidden" name="duplicate" value="<?php echo $row->duplicate; ?>" />
	<input type="hidden" name="blockcopy" value="<?php echo $row->blockcopy; ?>" />
	<input type="hidden" name="percentage" value="<?php echo $row->percentage; ?>" />
	<input type="hidden" name="fixedpoints" value="<?php echo $row->fixedpoints; ?>" />
	<input type="hidden" name="redirect" value="rules" />
	<input type="hidden" name="boxchecked" value="0" />
</form>