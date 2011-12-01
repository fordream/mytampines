<?php
/*
 * @component AlphaUserPoints
 * @copyright Copyright (C) 2008-2010 Bernard Gilly
 * @license : GNU/GPL
 * @Website : http://www.alphaplug.com
 */

defined( '_JEXEC' ) or die( 'Restricted access' );

$app = JFactory::getApplication();

$rows = $this->top10;
$rowsPA = $this->unapproved10;
$rowsCR = $this->requestslevel;
$rowsLA = $this->lastactivities;

if ( $this->needSync && $this->synch!='start') {	
	$error = JText::_('AUP_MSG_NEEDSYNCUSERS');
	JError::raiseNotice(0, $error );
}

if ($this->synch=='end') {
	$app->enqueueMessage( JText::_( 'AUP_ALLUSERSSYNC' ) );		
} elseif ($this->synch!='start' && $this->synch!='end' && $this->synch!='') {
	$app->enqueueMessage( $this->synch );		
}

if ($this->recalculate=='end') {
	$app->enqueueMessage( JText::_( 'AUP_SUCCESSFULLYRECALCULATE' ) );		
} elseif ($this->recalculate!='start' && $this->recalculate!='end' && $this->recalculate!='') {
	$app->enqueueMessage( $this->recalculate );		
}

$template	= $app->getTemplate();
?>
<script language="JavaScript" type="text/JavaScript">
<!--
function MM_reloadPage(init) {  //reloads the window if Nav4 resized
  if (init==true) with (navigator) {if ((appName=="Netscape")&&(parseInt(appVersion)==4)) {
    document.MM_pgW=innerWidth; document.MM_pgH=innerHeight; onresize=MM_reloadPage; }}
  else if (innerWidth!=document.MM_pgW || innerHeight!=document.MM_pgH) location.reload();
}
MM_reloadPage(true);
//-->
</script>
<?php
if ( $this->synch=='start' || $this->recalculate=='start' ) {	
	JRequest::setVar( 'hidemainmenu', 1 );	
	echo '<img src="'. JURI::base(true).'/components/com_alphauserpoints/assets/images/warning.png" alt="" style="vertical-align:middle;" />&nbsp;&nbsp;' . JText::_( 'AUP_NOTTOINTERRUPTPROCESS' );
 ?>
<table class="adminform">			
   <tr>
    <td width="20">
	  <div align="center">
	  <img src="<?php echo JURI::base(true).'/components/com_alphauserpoints/assets/images/working.gif'; ?>" alt="" />
	  </div>
	</td>
	<td>
	  <?php if ( $this->synch=='start' ) { ?>	
	  <iframe src="components/com_alphauserpoints/assets/synch/synch.php?start=0&run=1" width="100%" height="40" scrolling="no" marginheight="0" marginwidth="0" frameborder="0" style="vertical-align:middle;"></iframe>
	  <?php } elseif ( $this->recalculate=='start' ) { ?>
	  <iframe src="components/com_alphauserpoints/assets/recalculate/recalculate.php?start=0&run=1" width="100%" height="40" scrolling="no" marginheight="0" marginwidth="0" frameborder="0" style="vertical-align:middle;"></iframe>
	  <?php } ?>
	</td>
   </tr>
</table>
<?php
}
?>
<table class="adminform">			
	<tr>
		<td width="50%" valign="top">
			<div id="cpanel">
			<?php
					$link = 'index.php?option=com_alphauserpoints&task=configuration';
					$rel  = 'rel="{handler: \'iframe\', size: {x: '._ALPHAUSERPOINTS_WIDTH_POPUP_CONFIG.', y: '._ALPHAUSERPOINTS_HEIGHT_POPUP_CONFIG.'}}"';
					$class = 'class="modal"';
					aup_createIconPanel($link, 'icon-48-config.png', JText::_('AUP_CONFIGURATION'), $rel, $class);					
			
					$link = 'index.php?option=com_alphauserpoints&task=rules';
					aup_createIconPanel($link, 'icon-48-rules.png', JText::_('AUP_RULES'));
					
					$link = 'index.php?option=com_alphauserpoints&task=statistics';
					aup_createIconPanel($link, 'icon-48-awards.png', JText::_('AUP_USERS'));
					
					$link = 'index.php?option=com_alphauserpoints&task=activities';
					aup_createIconPanel($link, 'icon-48-activities.png', JText::_('AUP_ACTIVITY'));

					$link = 'index.php?option=com_alphauserpoints&task=plugins';
					aup_createIconPanel($link, 'icon-48-plugins.png', JText::_('AUP_PLUGINS'));
					
					$link = 'index.php?option=com_alphauserpoints&task=cpanel&synch=start';
					aup_createIconPanel($link, 'icon-48-usersync.png', JText::_('AUP_USERSYNC'));
					
					$link = 'index.php?option=com_alphauserpoints&task=recalculate';
					$javascript_recalculate_points = "onclick=\"return confirm('" . JText::_( 'AUP_CONFIRM_STARTPROCESS' ) . "')\"";	
					aup_createIconPanel($link, 'icon-48-recalculate.png', JText::_('AUP_RECALCULATE'), $javascript_recalculate_points);
					
					$link = 'index.php?option=com_alphauserpoints&task=resetpoints';
					$javascript_reset_points = "onclick=\"return confirm('" . JText::_( 'AUP_DO_YOU_WANT_RESET_ALL_POINTS' ) . "')\"";
					aup_createIconPanel($link, 'icon-48-reset.png', JText::_('AUP_RESETPOINTS'), $javascript_reset_points);
					
					$link = 'index.php?option=com_alphauserpoints&task=setmaxpoints';
					aup_createIconPanel($link, 'icon-48-maxpoints.png', JText::_('AUP_SETMAXPOINST'));
					
					$link = 'index.php?option=com_alphauserpoints&task=purge';
					aup_createIconPanel($link, 'icon-48-trash.png', JText::_('AUP_PURGEEXPIRESPOINTS'));
					
					$link = 'index.php?option=com_alphauserpoints&task=couponcodes';
					aup_createIconPanel($link, 'icon-48-coupons.png', JText::_('AUP_COUPON_CODES'));
					
					$link = 'index.php?option=com_alphauserpoints&task=raffle';
					aup_createIconPanel($link, 'icon-48-raffle.png', JText::_('AUP_RAFFLE'));
					
					$link = 'index.php?option=com_alphauserpoints&task=levelrank';
					aup_createIconPanel($link, 'icon-48-rank.png', JText::_('AUP_LEVEL/RANK'));
					
					$link = 'index.php?option=com_alphauserpoints&task=stats';
					aup_createIconPanel($link, 'icon-48-charts.png', JText::_('AUP_STATISTICS'));
					
					$link = 'index.php?option=com_alphauserpoints&task=exportactiveusers';
					aup_createIconPanel($link, 'icon-48-mostactiveusers.png', JText::_('AUP_EXPORTACTIVEUSERS'));

					$link = 'index.php?option=com_alphauserpoints&task=exportemails';
					aup_createIconPanel($link, 'icon-48-massmail.png', JText::_('AUP_EXPORTEMAILS'));
					
					$link = 'index.php?option=com_alphauserpoints&task=archiveActivities';
					aup_createIconPanel($link, 'icon-48-archive.png', JText::_('AUP_COMBINE_ACTIVITIES'));

					$link = 'index.php?option=com_alphauserpoints&task=about';
					aup_createIconPanel($link, 'icon-48-alphauserpoints.png', JText::_('AUP_ABOUT'));					
					
					$link = '#';
					if (file_exists( JPATH_COMPONENT . "/help/" . $this->tag . "/screen.how_create_plugin.html")){
						$javascript = "onclick=\"popupWindow('components/com_alphauserpoints/help/" . $this->tag . "/screen.how_create_plugin.html', 'Help', 640, 480, 1)\"";
					} else $javascript = "onclick=\"popupWindow('components/com_alphauserpoints/help/en-GB/screen.how_create_plugin.html', 'Help', 640, 480, 1)\"";
					aup_createIconPanel($link, 'icon-48-plugins_help.png', JText::_('AUP_HOWTO'), $javascript);
					
					$javascript="";
					
					$link = '#';
					if (file_exists( JPATH_COMPONENT . "/help/" . $this->tag . "/screen.alphauserpoints.html")){
						$javascript = "onclick=\"popupWindow('components/com_alphauserpoints/help/" . $this->tag . "/screen.alphauserpoints.html', 'Help', 640, 480, 1)\"";
					} else $javascript = "onclick=\"popupWindow('components/com_alphauserpoints/help/en-GB/screen.alphauserpoints.html', 'Help', 640, 480, 1)\"";
					aup_createIconPanel($link, 'icon-48-help_header.png', JText::_('HELP'), $javascript);
					$link4welcome = "<a href=\"#\" $javascript>";
					$endlinkwelcome = "</a>";

			?>
			</div>	
		</td>
		<td width="50%" valign="top">
		<?php
			echo $this->pane->startPane("content-pane");
			
			echo $this->pane->startPanel(JText::_('AUP_WELCOMETOALPHAUSERPOINTS'), "cpanel-panel-welcome" );
			echo JText::sprintf('AUP_WELCOMEPANELTEXT', $link4welcome, $endlinkwelcome);
			echo $this->pane->endPanel();	
			
			echo $this->pane->startPanel(JText::_('AUP_HIGHT_SCORE'), "cpanel-panel-usersawards" );
			
			if ( $rows ) {
		?>
		<table class="adminlist">
		<tr>
			<td class="title">
				<strong><?php echo JText::_( 'AUP_NAME' ); ?></strong>
			</td>
			<td class="title">
				<strong><?php echo JText::_( 'AUP_USERNAME' ); ?></strong>
			</td>
			<td class="title">
				<strong><?php echo JText::_( 'AUP_POINTS' ); ?></strong>
			</td>
		</tr>
		<?php		
			foreach ($rows as $row)	{			
				?>
				<tr>
					<td>					
						<?php echo htmlspecialchars($row->name, ENT_QUOTES, 'UTF-8');?>
					</td>
					<td>
						<?php echo $row->username;?>
					</td>
					<td>
						<?php echo $row->points;?>
					</td>
				</tr>
				<?php
			}
			?>
		  </table>
		
		<?php
		}
			echo $this->pane->endPanel();
			
			// Last activity
			echo $this->pane->startPanel(JText::_('AUP_LASTACTIVITY'), "cpanel-panel-latest-activities" );
			
			if ( $rowsLA ) {
			?>
		<table class="adminlist">
		<tr>
			<td class="title">
				<strong><?php echo JText::_( 'AUP_DATE' ); ?></strong>
			</td>
			<td class="title">
				<strong><?php echo JText::_( 'AUP_RULENAME' ); ?></strong>
			</td>
			<td class="title">
				<strong><?php echo JText::_( 'AUP_NAME' ); ?></strong>
			</td>
			<td class="title">
				<strong><?php echo JText::_( 'AUP_USERNAME' ); ?></strong>
			</td>
			<td class="title">
				<strong><?php echo JText::_( 'AUP_POINTS' ); ?></strong>
			</td>
		</tr>		
			<?php
			
			foreach ($rowsLA as $rowLA)	{			
				?>
				<tr>
					<td>					
						<?php echo nicetime($rowLA->insert_date); ?>
					</td>
					<td>					
						<?php echo JText::_( $rowLA->rule_name ); ?>
					</td>
					<td>					
						<?php
						 $linkLA = "<a href=\"index.php?option=com_alphauserpoints&task=showdetails&cid=".$rowLA->referreid."&name=".htmlspecialchars($rowLA->uname, ENT_QUOTES, 'UTF-8')."\">";
						 echo $linkLA . htmlspecialchars($rowLA->uname, ENT_QUOTES, 'UTF-8') . "</a>";					
						 ?>					
					</td>
					<td>
						<?php echo $rowLA->usrname;?>
					</td>
					<td>
						<?php echo $rowLA->last_points;?>
					</td>
				</tr>
				<?php
			} 
			?>
		  </table>
			<?php
			}
			
			echo $this->pane->endPanel();			
			
			// pending approval
			$nb = ( $rowsPA ) ? ' (<font color="red">'.$this->totalunapproved.'</font>)' : ' (<font color="green">0</font>)';
			
			echo $this->pane->startPanel(JText::_('AUP_PENDING_APPROVAL').$nb, "cpanel-panel-pending-approval" );
			
			if ( $rowsPA ) {
		?>
		<form action="index.php" method="post" name="adminForm">
		<table class="adminlist">
		<tr>
			<td width="3%" class="title">
				<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($rowsPA); ?>);" />
			</td>
			<td class="title">
				<strong><?php echo JText::_( 'AUP_DATE' ); ?></strong>
			</td>
			<td class="title">
				<strong><?php echo JText::_( 'AUP_RULENAME' ); ?></strong>
			</td>
			<td class="title">
				<strong><?php echo JText::_( 'AUP_NAME' ); ?></strong>
			</td>
			<td class="title">
				<strong><?php echo JText::_( 'AUP_USERNAME' ); ?></strong>
			</td>
			<td class="title">
				<strong><?php echo JText::_( 'AUP_POINTS' ); ?></strong>
			</td>
			<td class="title">&nbsp;
				
			</td>
		</tr>
		<?php		
			$k = 0;
			for ($i=0, $n=count( $rowsPA ); $i < $n; $i++)
			{
				$rowPA 	=& $rowsPA[$i];

				?>
				<tr>
					<td align="center">
						<?php echo JHTML::_('grid.id', $i, $rowPA->cid ); ?>
					</td>
					<td>					
						<?php echo $rowPA->insert_date; ?>
					</td>
					<td>					
						<?php echo JText::_( $rowPA->rule_name ); ?>
					</td>
					<td>					
						<?php
						 $linkPA = "<a href=\"index.php?option=com_alphauserpoints&task=showdetails&cid=".$rowPA->referreid."&name=".htmlspecialchars($rowPA->name, ENT_QUOTES, 'UTF-8')."\">";
						
						 echo $linkPA . htmlspecialchars($rowPA->name, ENT_QUOTES, 'UTF-8') . "</a>";
						 
						 ?>
					</td>
					<td>
						<?php echo $rowPA->username;?>
					</td>
					<td>
						<?php echo $rowPA->pendingapprovalpoints;?>
					</td>
					<td>
						<?php 
						$linkDeletePA = '<a href="index.php?option=com_alphauserpoints&task=deletependingapproval&cid='.$rowPA->cid.'">';
						echo $linkDeletePA . '<img src="'. JURI::base(true).'/components/com_alphauserpoints/assets/images/trash-16.png" alt="" style="vertical-align:middle;" /></a>';
						?>
					</td>
				</tr>
				<?php
			} 
			?>
		  </table>
		  <table class="adminlist">
		  	<tr>
		  		<td>
		    		<input type="submit" name="Submit" value="<?php echo JText::_( 'AUP_APPROVE' ); ?>">
		  		</td>
		  	</tr>		  
		  </table>
			<input type="hidden" name="option" value="com_alphauserpoints" />
			<input type="hidden" name="task" value="approve" />
			<input type="hidden" name="table" value="alpha_userpoints_details" />
			<input type="hidden" name="redirect" value="cpanel" />
			<input type="hidden" name="boxchecked" value="0" />
		</form>		
		<?php
		} else echo "<p>" . JText::_( 'AUP_NO_PENDING_APPROVAL' ) . "</p>";
			
			
			echo $this->pane->endPanel();
			
		//if any request level rule activate
		if ( $this->rulechangelevelactivate>0 )  { 
			
			$nc = ( $rowsCR ) ? ' (<font color="red">'.$this->totalrequestslevel.'</font>)' : ' (<font color="green">0</font>)';
			
			echo $this->pane->startPanel(JText::_('AUP_CURRENTREQUESTSFORCHANGELEVEL').$nc, "cpanel-panel-pending-requests" );
			
			if ( $rowsCR ) {
		?>
		<table class="adminlist">
		<tr>
			<td class="title">
				<strong><?php echo JText::_( 'AUP_DATE' ); ?></strong>
			</td>
			<td class="title">
				<strong><?php echo JText::_( 'AUP_NAME' ); ?></strong>
			</td>
			<td class="title">
				<strong><?php echo JText::_( 'AUP_USERNAME' ); ?></strong>
			</td>
			<td class="title">
				<strong><?php echo JText::_( 'AUP_USERLEVEL' ); ?></strong>
			</td>
			<td class="title">
				<strong><?php echo JText::_( 'AUP_RESPONSE' ); ?></strong>
			</td>
		</tr>
		<?php		
			foreach ($rowsCR as $rowCR)	{			
				?>
				<tr>
					<td>					
						<?php echo $rowCR->requestdate; ?>
					</td>
					<td>					
						<?php echo htmlspecialchars($rowCR->name, ENT_QUOTES, 'UTF-8'); ?>
					</td>
					<td>					
						<?php echo JText::_( $rowCR->username ); ?>
					</td>
					<td>
						<?php						
						 switch ( $rowCR->levelrequest ){						 
						 	case "19" : // group Author
								echo JText::_( 'AUP_REGISTERED' ).' &raquo; '.JText::_( 'AUP_AUTHOR' );
								break;
							case "20" : // group editor
								echo JText::_( 'AUP_AUTHOR' ).' &raquo; '.JText::_( 'AUP_EDITOR' );
								break; 
							case "21": // group publisher
								echo JText::_( 'AUP_EDITOR' ).' &raquo; '.JText::_( 'AUP_PUBLISHER' );
								break;					 
						 }						 
						 ?>
					</td>
					<td>
					<?php
					 $linkCRaccept = "<a href=\"index.php?option=com_alphauserpoints&task=changeuserlevel&id=".$rowCR->id."&uid=".$rowCR->userid."&assignlevel=".$rowCR->levelrequest."\">";
					 $linkCRreject = "<a href=\"index.php?option=com_alphauserpoints&task=rejectrequest&id=".$rowCR->id."\">";
					 
					 echo $linkCRaccept . JText::_( 'AUP_ACCEPT' ) . "</a>&nbsp;-&nbsp;";
					 echo $linkCRreject . JText::_( 'AUP_REJECT' ) . "</a>";
					 ?>
					</td>
				</tr>
				<?php
			} 
			?>
	    </table>
		<?php
		} else echo "<p>" . JText::_( 'AUP_NOREQUEST' ) . "</p>";
			
			echo $this->pane->endPanel();
			
	} // end if any request level rule activate
			
			echo $this->pane->endPane();
			
			
	if ( $this->params->get('showUpdateCheck', 1)){
		if($this->check['connect'] == 0) {
		?>
			<table class="adminlist">
			<thead>
				<tr>
					<th colspan="2">
					<?php echo JText::_( 'AUP_UPDATE_CHECK' ); ?>
					</th>
				</tr>
			</thead>
			<tbody>
			<tr>
			<td colspan="2">
				<?php
		  			echo '<b><font color="red">'.JText::_( 'AUP_CONNECTION_FAILED' ).'</font></b>';
		  		?>
			</td>
			</tr>
			</tbody>
			</table>
				<?php
				} elseif ($this->check['enabled'] == 1) {
				?>
			<table class="adminlist">
			<thead>
				<tr>
					<th colspan="2">
					<?php echo JText::_( 'AUP_UPDATE_CHECK' ); ?>
					</th>
				</tr>
			</thead>
			<tbody>
			<tr>
			<td width="33%">
				<?php
		  			if ($this->check['current'] == 0 ) {
						echo JHTML::_('image', 'administrator/templates/'. $template .'/images/header/icon-48-checkin.png', NULL, 'width=32');
		  			} elseif( $this->check['current'] == -1 ) {
		  				echo JHTML::_('image', 'administrator/templates/'. $template .'/images/header/icon-48-info.png', NULL, 'width=32');
		  			} else {
		  				echo JHTML::_('image', 'administrator/templates/'. $template .'/images/header/icon-48-info.png', NULL, 'width=32');
		  			}
		  		?>
			</td>
			<td>
				<?php
		  			if ($this->check['current'] == 0) {
		  				echo '<strong><font color="green">'.JText::_( 'AUP_LATEST_VERSION_INSTALLED' ).'</font></strong>';
		  			} elseif( $this->check['current'] == -1 ) {
		  				echo '<b><font color="red">'.JText::_( 'AUP_OLD_VERSION' ).'</font></b>';
		  			} else {
		  				echo '<b><font color="orange">'.JText::_( 'AUP_NEWER_VERSION' ).'</font></b>';
		  			}
		  		?>
			</td>
		</tr>
		<tr>
			<td width="33%">
				<?php echo JText::_( 'AUP_LATEST_VERSION' ).':'; ?>
			</td>
			<td>
				<?php echo $this->check['version']; ?>
			</td>
		</tr>
		<tr>
			<td width="33%">
				<?php echo JText::_( 'AUP_INSTALLED_VERSION' ).':'; ?>
			</td>
			<td>
				<?php echo $this->check['current_version']; ?>
			</td>
		</tr>
		<tr>
		  	<td width="33%">
		  		<?php echo JText::_( 'AUP_RELEASED_DATE' ).':'; ?>
		  	</td>
		  	<td>
		  		<?php echo $this->check['released']; ?>
		  	</td>
		</tr>
		</tbody>
		</table>
		<?php 
		  } 
		} // end if check enabled
		?>
			<table class="adminlist">
			<thead>
				<tr>
					<th colspan="2">
					<?php echo JText::_( 'AUP_DONATE' ); ?>
					</th>
				</tr>
			</thead>
			<tbody>
			<tr>
			<td width="33%">
				<?php echo JText::_( 'AUP_IF_YOU_LIKE_ALPHAUSERPOINTS' ); ?>
			</td>
		  	<td>
			<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
			<input type="hidden" name="cmd" value="_s-xclick" />
			<input type="image" src="<?php echo JURI::base(true).'/components/com_alphauserpoints/assets/images/btn_donateCC_LG.gif'; ?>" name="submit" alt="PayPal - The safer, easier way to pay online!" style="border:0"/>
			<img alt="" border="0" src="https://www.paypal.com/fr_FR/i/scr/pixel.gif" width="1" height="1">
			<input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----MIIHbwYJKoZIhvcNAQcEoIIHYDCCB1wCAQExggEwMIIBLAIBADCBlDCBjjELMAkGA1UEBhMCVVMxCzAJBgNVBAgTAkNBMRYwFAYDVQQHEw1Nb3VudGFpbiBWaWV3MRQwEgYDVQQKEwtQYXlQYWwgSW5jLjETMBEGA1UECxQKbGl2ZV9jZXJ0czERMA8GA1UEAxQIbGl2ZV9hcGkxHDAaBgkqhkiG9w0BCQEWDXJlQHBheXBhbC5jb20CAQAwDQYJKoZIhvcNAQEBBQAEgYADqCLXg9sx7ssktkKZlwqe3CfVkCdZwb/sp48uBlDB13YwA2PzeXAD8nP50SWJN49wS9mBkoZt6fq52OmczzlbCbkq0k9aw1UqJqW3PdnJNIwmNe/GzCpL+6CxMKmZM6gTyhIAkICZ3CdXOhYdRAAzxJsUBKo3YUNoGAbe6RFW3TELMAkGBSsOAwIaBQAwgewGCSqGSIb3DQEHATAUBggqhkiG9w0DBwQI4H17XQyPt+eAgcgfluvZoiB+q7GXBXxsGGBDePN1mZ9hqMLro1FTPVkqi9k0H1odpFMUBayd8PXOQt6ltxau/fOhj56uV2ipsWLZkwZVPmT9qtfWC7+hOL8iCmRYZKscyqq6WZD1EkG9Lfy4FfyewGSc7RPCG2ZvRPQ+ywM+38WBd+fsz7GGD01qpYRWuvu5qh9ojJCJt2nM3VpX1pjly8OY6Drbn+KADIg2dg2O5jbIGcu4/pm62jmkzjNtK57ppLKMpWgP1c/YdFNf21blh5a7cKCCA4cwggODMIIC7KADAgECAgEAMA0GCSqGSIb3DQEBBQUAMIGOMQswCQYDVQQGEwJVUzELMAkGA1UECBMCQ0ExFjAUBgNVBAcTDU1vdW50YWluIFZpZXcxFDASBgNVBAoTC1BheVBhbCBJbmMuMRMwEQYDVQQLFApsaXZlX2NlcnRzMREwDwYDVQQDFAhsaXZlX2FwaTEcMBoGCSqGSIb3DQEJARYNcmVAcGF5cGFsLmNvbTAeFw0wNDAyMTMxMDEzMTVaFw0zNTAyMTMxMDEzMTVaMIGOMQswCQYDVQQGEwJVUzELMAkGA1UECBMCQ0ExFjAUBgNVBAcTDU1vdW50YWluIFZpZXcxFDASBgNVBAoTC1BheVBhbCBJbmMuMRMwEQYDVQQLFApsaXZlX2NlcnRzMREwDwYDVQQDFAhsaXZlX2FwaTEcMBoGCSqGSIb3DQEJARYNcmVAcGF5cGFsLmNvbTCBnzANBgkqhkiG9w0BAQEFAAOBjQAwgYkCgYEAwUdO3fxEzEtcnI7ZKZL412XvZPugoni7i7D7prCe0AtaHTc97CYgm7NsAtJyxNLixmhLV8pyIEaiHXWAh8fPKW+R017+EmXrr9EaquPmsVvTywAAE1PMNOKqo2kl4Gxiz9zZqIajOm1fZGWcGS0f5JQ2kBqNbvbg2/Za+GJ/qwUCAwEAAaOB7jCB6zAdBgNVHQ4EFgQUlp98u8ZvF71ZP1LXChvsENZklGswgbsGA1UdIwSBszCBsIAUlp98u8ZvF71ZP1LXChvsENZklGuhgZSkgZEwgY4xCzAJBgNVBAYTAlVTMQswCQYDVQQIEwJDQTEWMBQGA1UEBxMNTW91bnRhaW4gVmlldzEUMBIGA1UEChMLUGF5UGFsIEluYy4xEzARBgNVBAsUCmxpdmVfY2VydHMxETAPBgNVBAMUCGxpdmVfYXBpMRwwGgYJKoZIhvcNAQkBFg1yZUBwYXlwYWwuY29tggEAMAwGA1UdEwQFMAMBAf8wDQYJKoZIhvcNAQEFBQADgYEAgV86VpqAWuXvX6Oro4qJ1tYVIT5DgWpE692Ag422H7yRIr/9j/iKG4Thia/Oflx4TdL+IFJBAyPK9v6zZNZtBgPBynXb048hsP16l2vi0k5Q2JKiPDsEfBhGI+HnxLXEaUWAcVfCsQFvd2A1sxRr67ip5y2wwBelUecP3AjJ+YcxggGaMIIBlgIBATCBlDCBjjELMAkGA1UEBhMCVVMxCzAJBgNVBAgTAkNBMRYwFAYDVQQHEw1Nb3VudGFpbiBWaWV3MRQwEgYDVQQKEwtQYXlQYWwgSW5jLjETMBEGA1UECxQKbGl2ZV9jZXJ0czERMA8GA1UEAxQIbGl2ZV9hcGkxHDAaBgkqhkiG9w0BCQEWDXJlQHBheXBhbC5jb20CAQAwCQYFKw4DAhoFAKBdMBgGCSqGSIb3DQEJAzELBgkqhkiG9w0BBwEwHAYJKoZIhvcNAQkFMQ8XDTA4MDgxNjE0NDQzNVowIwYJKoZIhvcNAQkEMRYEFMt0et/WHZWbb5Sb5UmEfz0AyAEUMA0GCSqGSIb3DQEBAQUABIGAbi5iPYuw2TDIu1DexHF7z6kS/XTfHCZR3GNOpdA785r/Koj//D6/d+d0aq4E9ibaDPrl6JC1rLn75THVp7SQQgDBOrHTvERVJuiyFV9LPVxf3HVE0CwM+9FkPINxm1pmJz/NS6PNVt0P4qGddVQOQRlcFAV2CdmBLdjNdLhWMHY=-----END PKCS7-----
			" />
			</form>
		  	</td>
			</tr>
			</tbody>
			</table>
		</td>			
	</tr>
</table>
<div align="center"><b><font color="green"><?php echo JText::_( 'AUP_TOTAL_COMMUNITY_POINTS' ) . ": " . $this->communitypoints; ?></font></b></div>