<?php
/*
 * @component AlphaUserPoints
 * @copyright Copyright (C) 2008-2010 Bernard Gilly
 * @license : GNU/GPL
 * @Website : http://www.alphaplug.com
 */

// no direct access
defined('_JEXEC') or die('Restricted access');


if(!defined("_ALPHAUSERPOINTS_WIDTH_POPUP_CONFIG")) {
   DEFINE( "_ALPHAUSERPOINTS_WIDTH_POPUP_CONFIG", "580" );
}

if(!defined("_ALPHAUSERPOINTS_HEIGHT_POPUP_CONFIG")) {
   DEFINE( "_ALPHAUSERPOINTS_HEIGHT_POPUP_CONFIG", "480" );
}

/*
function aup_Jimport ( $lib_path ) {
	$path  = JPATH_ROOT . DS . 'libraries' . DS . str_replace( '.', DS, $lib_path ) . '.php';
	include_once ( $path );
}
*/
$document	= & JFactory::getDocument();
$style = '.icon-32-export { background-image: url(../administrator/components/com_alphauserpoints/assets/images/icon-32-export.png); }';
$document->addStyleDeclaration( $style );

function aup_CopySite ($align='center') {
	// Get Copyright for Backend
	$copyStart = 2008; 
	$copyNow = date('Y');  
	if ($copyStart == $copyNow) { 
		$copySite = $copyStart;
	} else {
		$copySite = $copyStart." - ".$copyNow ;
	}
	$_copyright =  "<br /><div align=\"$align\"><span class=\"small\"><b>AlphaUserPoints</b> &copy; $copySite"
					. " - Bernard Gilly - <a href=\"http://www.alphaplug.com\" target=\"_blank\">www.alphaplug.com</a><br />"
					. "AlphaUserPoints is Free Software released under the <a href=\"http://www.gnu.org/licenses/gpl-2.0.html\" target=\"_blank\">GNU/GPL License</a></span></div>";
	echo $_copyright;
}


function aup_createIconPanel( $link, $image, $text, $javascript='', $class='' ) {
	
	$image = JURI::base(true)."/components/com_alphauserpoints/assets/images/" . $image;
	?>
	<div style="float:left;">
		<div class="icon">
			<a <?php echo $class; ?> href="<?php echo $link; ?>" <?php echo $javascript; ?>>
				<img src="<?php echo $image; ?>" alt="<?php echo $text; ?>" align="top" border="0" />
				<span><?php echo $text; ?></span>
			</a>
		</div>
	</div>
	<?php
}

function nicetime($date, $offset=1)
{
	$config =& JFactory::getConfig();
	$tzoffset = $config->getValue('config.offset');
	
	if(empty($date)) {
		return "No date provided";
	}
	
	$datetimestamp = strtotime($date);
	if ( $offset ) {
		$date = date('Y-m-d H:i:s', $datetimestamp + ($tzoffset * 60 * 60));
	} else {
		$date = date('Y-m-d H:i:s', $datetimestamp);
	}
   
	$period          = array(JText::_( 'AUP_SECOND' ), JText::_( 'AUP_MINUTE' ), JText::_( 'AUP_HOUR' ), JText::_( 'AUP_DAY' ), JText::_( 'AUP_WEEK' ), JText::_( 'AUP_MONTH' ), JText::_( 'AUP_YEAR' ), JText::_( 'AUP_DECADE' ));
	$periods         = array(JText::_( 'AUP_SECONDS' ), JText::_( 'AUP_MINUTES' ), JText::_( 'AUP_HOURS' ), JText::_( 'AUP_DAYS' ), JText::_( 'AUP_WEEKS' ), JText::_( 'AUP_MONTHS' ), JText::_( 'AUP_YEARS' ), JText::_( 'AUP_DECADES' ));
	
	$lengths         = array("60","60","24","7","4.35","12","10");
   
	//$now             = time();
	$now = strtotime(gmdate('Y-m-d H:i:s')) + ($tzoffset * 60 * 60);
	$unix_date       = strtotime($date);
   
	   // check validity of date
	if(empty($unix_date)) {   
		return "Bad date";
	}

	// is it future date or past date
	if($now > $unix_date) {  
		$difference     = $now - $unix_date;
		$tense         = JText::_( 'AUP_AGO' );
	   
	} else {
		$difference     = $unix_date - $now;
		$tense         = JText::_( 'AUP_FROM_NOW' );
	}
   
	for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
		$difference /= $lengths[$j];
	}
   
	$difference = round($difference);
   
	if($difference != 1) {
		//return "$difference $periods[$j] {$tense}";
		$nicetime = $difference . " " . $periods[$j];
		return sprintf($tense, $nicetime);			
	} else {
		//return "$difference $period[$j] {$tense}";
		$nicetime = $difference . " " . $period[$j];
		return sprintf($tense, $nicetime);		
	}

}

	function _get_average_age_community() {

		$db =& JFactory::getDBO();	
		
		$avarage_age	= 0;
		
		$query = "SELECT AVG((FLOOR(( TO_DAYS(NOW()) - TO_DAYS(birthdate))/365))) FROM #__alpha_userpoints WHERE birthdate!='0000-00-00' AND blocked='0'";
		$db->setQuery( $query );
		$avarage_age = round($db->loadResult());
		
		return $avarage_age;
	}

	function getIdPluginFunction( $rule_name )
	{
		$db	   =& JFactory::getDBO();
		$query = "SELECT `id` FROM #__alpha_userpoints_rules WHERE `plugin_function`='$rule_name'";
		$db->setQuery( $query );
		$plugin_id = $db->loadResult();
		return	$plugin_id;	
	}
	
?>