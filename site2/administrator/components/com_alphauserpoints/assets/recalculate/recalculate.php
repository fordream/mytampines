<?php
/*
 * @component AlphaUserPoints
 * @copyright Copyright (C) 2008-2009 Bernard Gilly
 * @license : GNU/GPL
 * @Website : http://www.alphaplug.com
 */

define( '_JEXEC', 1 );

if (stristr( $_SERVER['SERVER_SOFTWARE'], 'win32' )) {
	define( 'JPATH_BASE', realpath(dirname(__FILE__).'\..\..\..\..' ));
} else define( 'JPATH_BASE', realpath(dirname(__FILE__).'/../../../..' ));

define( 'DS', DIRECTORY_SEPARATOR );

require_once ( JPATH_BASE.DS.'includes'.DS.'defines.php' );
require_once ( JPATH_BASE.DS.'includes'.DS.'framework.php' );
$app =& JFactory::getApplication('administrator');
$app->initialise();

jimport( 'joomla.plugin.plugin' );
JPlugin::loadLanguage( 'com_alphauserpoints' );

$base = JURI::base();
$base = str_replace("components/com_alphauserpoints/assets/recalculate/", "", $base);

// Time execution max (seconds)
$tempsExecMax = 2;
// Time delay between each step (milliseconds)
$tempsRepos = 100;
	
function Recalc( $start, $tempsExec )
{
	// check time execute
	list($usec, $sec) = explode(' ', microtime());
	$start_time=(float)$usec + (float)$sec;
	$new_time = $start_time;

	$db			= & JFactory::getDBO();
	$jnow		= & JFactory::getDate();
	$now		= $jnow->toMySQL();	
	
	// get params definitions
	$params =& JComponentHelper::getParams( 'com_alphauserpoints' );		

	$prefixNewReferreid = strtoupper($params->get('prefix_selfregister'));
	//$prefixNewReferreid = "AUPRS-";
	
	if ( $start ) {
		$i = $start;	
	} else $i = 0;
	
	$query = "SELECT referreid FROM #__alpha_userpoints WHERE referreid!='GUEST'";
	$db->setQuery($query);
	$users = $db->loadObjectList();
	
	$numusers = count($users);

	if ( $users ) {
	
		require_once (JPATH_SITE.DS.'components'.DS.'com_alphauserpoints'.DS.'helper.php');
		
		for ($i, $n=$numusers; $i < $n; $i++) {			
				
			if ($new_time - $start_time < $tempsExec){
			
				$user = $users[$i];				
				
				// real sum for each user
				$query = "SELECT SUM(points) FROM #__alpha_userpoints_details WHERE `referreid`='" . $user->referreid . "' AND `approved`='1' AND (`expire_date`>'$now' OR `expire_date`='0000-00-00 00:00:00')";
				$db->setQuery($query);
				$newtotal = $db->loadResult();

				$query = "UPDATE #__alpha_userpoints SET `points`='" . intval($newtotal) . "', `last_update`='$now' WHERE `referreid`='" . $user->referreid . "'";
				$db->setQuery( $query );
				
				if ( !$db->query() ) {
					$error = 'ERROR : update not complete';
					echo '<script language="Javascript">
					<!--
					parent.document.location.replace("'.$base.'index.php?option=com_alphauserpoints&task=cpanel&recalculate=$error");							
					// -->
					</script>';
					exit();
				}
				
				// update Ranks / Medals if necessary
				AlphaUserPointsHelper::checkRankMedal ( $user->referreid );
				
			} else { // time ?
			
				break;
			
			}
			
			list($usec, $sec) = explode(" ", microtime());
			$new_time=(float)$usec + (float)$sec;				
		
		} // for $i=

		if ($i==$numusers) {
			$start=-1;
		} else {
			$start=$i;
		}
	
		return $start;
		
	}  // if users
				
}
// Manage autorun page
header("Expires: Mon, 1 Dec 2003 01:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<title>Recalculation Users</title>
	<meta http-equiv="CONTENT-TYPE" content="text/html; charset=iso-8859-1"/>
	<meta http-equiv="CONTENT-LANGUAGE" content="FR"/>
	<meta http-equiv="Cache-Control" content="no-cache/"/>
	<meta http-equiv="Pragma" content="no-cache"/>
	<meta http-equiv="Expires" content="-1"/>
</head>
<body style="font-family: Verdana; font-size: 12px">
	<div>
		<p>			
			<?php
				$run = 0;
				if (isset($_GET['run'])) $run = $_GET['run'];
				$start = 0;
				if (isset($_GET['start'])) $start = $_GET['start'];
				// Display running
				if ($run == 1)
				{
					if ($start>0 ) {			
						echo " " . $start . " " .  strtolower(JText::_( 'AUP_USERS' )) . " ...";		
					} elseif ( $start==-1 ) {					
					    echo JText::_( 'AUP_PLEASE_WAIT' ); 				
					} else echo " " . JText::_( 'AUP_PLEASE_WAIT' );
					 
				}
				// call script
				$start = Recalc( $start, $tempsExecMax );
				// if end
				if (($run==1) and ($start == -1))
				{					
					echo '<script language="Javascript">
					<!--
					parent.document.location.replace("'.$base.'index.php?option=com_alphauserpoints&task=cpanel&recalculate=end");
					// -->
					</script>';
					exit();
				}
			?>
		</p>
		<?php
			if ($run == 1)
			{
				// If not end...
				if ($start > -1)
				{
					// Reload script
					echo ("<script language=\"JavaScript\"
						type=\"text/javascript\">window.setTimeout('location.href=\"".$_SERVER["PHP_SELF"]."?start=$start&run=1\";',500+$tempsRepos);
						</script>\n");
				}
			}
		?>
</div>	
</body>