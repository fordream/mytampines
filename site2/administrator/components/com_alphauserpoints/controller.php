<?php
/*
 * @component AlphaUserPoints
 * @copyright Copyright (C) 2008-2011 Bernard Gilly
 * @license : GNU/GPL
 * @Website : http://www.alphaplug.com
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.controller' );

/**
 * @package AlphaUserPoints
 */
class alphauserpointsController extends JController
{
	/**
	 * Custom Constructor
	 */
 	function __construct()	{
		parent::__construct( );
	}	

	/**
	* Show Control Panel
	*/
	function cpanel() {
	
		$synch				= JRequest::getVar( 'synch', '', 'get', 'string' );
		$recalculate		= JRequest::getVar( 'recalculate', '', 'get', 'string' );
		
		$_params 			= &JComponentHelper::getParams( 'com_alphauserpoints' );
	
		$model          	= &$this->getModel ( 'statistics' );
		$model2         	= &$this->getModel ( 'requests' );
		$model3         	= &$this->getModel ( 'users' );
		$modelUpdate       	= &$this->getModel ( 'upgrade' );
		
		$view  				= $this->getView ( 'cpanel','html');
		
		$_top10 	 		= $model->_load_top10 ();
		$_unapproved 		= $model->_load_unapproved ();
		$_needsync   		= $model->_needsync ();		
		$_last_Activities 	= $model3->_last_Activities ();	
		$_communitypoints 	= $model->_totalcurrentcommunitypoints();
		
		$_rulechangelevel 	= $model2->_rulechangelevelactivate ();
		if ( $_rulechangelevel ) 
		{
			$_requestslevel 	= $model2->_load_currentrequestschangelevel ();		
		} else {
			$_requestslevel[0]	= null;
			$_requestslevel[1]	= 0;
		}
		
		if ( $_params->get('showUpdateCheck', 1) ) 
		{
			// cache subfolder(group) 'rssconnector', cache method: callback
			$cache= & JFactory::getCache('com_alphauserpoints');
			// save configured lifetime		 
			@$lifetime=$cache->lifetime; 
			$cache->setLifeTime(15 * 60); // 15 minutes to seconds		 
			// save cache conf		 
			$conf =& JFactory::getConfig();		 
			// check if cache is enabled in configuration		 
			$cacheactive = $conf->getValue('config.caching');		 
			$cache->setCaching(1); //enable caching		 
			// if the cache expired, the method will be called again and the result will be stored for 'lifetime' seconds
			$_check = $cache->call( array( $modelUpdate, '_getUpdate') );
			// revert configuration
			$cache->setCaching($cacheactive);		
		} else $_check		= '';
		
		
		// new feature in 1.5.2 -> added category for rule -> inform user !
		$modelUpdate->_checkCategory4rule();		
		
		$view->assign('top10', $_top10 );
		$view->assign('unapproved10', $_unapproved[0] );
		$view->assign('totalunapproved', $_unapproved[1] );
		$view->assign('needSync', $_needsync );
		$view->assign('requestslevel', $_requestslevel[0] );
		$view->assign('totalrequestslevel', $_requestslevel[1] );
		$view->assign('check' , $_check);
		$view->assign('params' , $_params);
		$view->assign('lastactivities' , $_last_Activities);
		$view->assign('synch' , $synch);
		$view->assign('recalculate' , $recalculate);
		$view->assign('rulechangelevelactivate' , $_rulechangelevel);
		$view->assign('communitypoints',  $_communitypoints );		

		$view->show();
	}
	
	function activities() {
	
		$model     	= &$this->getModel ( 'users' );
		$view       = $this->getView  ( 'activities','html' );
		
		$_activities = $model->_load_activities ();
		
		$view->assign('activities', $_activities[0] );
		$view->assign('total', $_activities[1] );
		$view->assign('limit', $_activities[2] );
		$view->assign('limitstart', $_activities[3] );
		$view->assign('lists', $_activities[4] );
	
		// Display
		$view->_displaylist();	
	}

	
	/**
	* Show About
	*/
	function about() {
		$view  = $this->getView ( 'about','html');		
		$view->show();
	}
	
	/**
	* Set max points for all users
	*/
	function setmaxpoints() {
		
		$view         = $this->getView  ( 'maxpoints','html' );	
		
		$setpoints	= JRequest::getVar( 'newmaxpoints', 0, 'get', 'int' );
		
		$view->assign('setpoints', $setpoints );
		$view->showform();
	
	}	
	
	function savemaxpoints() {
		$app = JFactory::getApplication();
		
		$model        = &$this->getModel ( 'users' );
		
		$view         = $this->getView  ( 'maxpoints','html' );		
		
		$_setmaxpoints = $model->_setmaxpoints ();
		
		$msg         = JText::_( 'AUP_NEWMAXPOINTS' ) . " " . $_setmaxpoints;
		$urlredirect = "index.php?option=com_alphauserpoints&task=setmaxpoints&newmaxpoints=$_setmaxpoints";
		$app->redirect( $urlredirect, $msg );
	
	}
		
	function resetpoints() {
		$app = JFactory::getApplication();
		
		$model       = &$this->getModel ( 'users' );
		
		$_resetpoints= $model->_resetpoints ();	
		
		$msg         = JText::_( 'AUP_SUCCESSFULLYRESETTOZERO' ) ;
		
		$urlredirect = "index.php?option=com_alphauserpoints&task=cpanel";
		$app->redirect( $urlredirect, $msg );
	
	}
	
	function recalculate() {
		$app = JFactory::getApplication();
	
		$model       = &$this->getModel ( 'users' );		
		
		$_recalculatepoints = $model->_recalculate_points ();
		
		$urlredirect = "index.php?option=com_alphauserpoints&task=cpanel&recalculate=start";
		$app->redirect( $urlredirect );

	}
	
	function purge() {
		$app = JFactory::getApplication();
		
		$model       = &$this->getModel ( 'users' );
		
		$_purgeexpirepoints	= $model->_purge_expires ();
	
		$msg         = JText::_( 'AUP_SUCCESSFULLYPURGE' ) ;
		
		$urlredirect = "index.php?option=com_alphauserpoints&task=cpanel";
		$app->redirect( $urlredirect, $msg );		

	}
	
	
	/**
	* Show Rules
	*/
	function rules() {
		
		$model        = &$this->getModel ( 'rules' );
	
		$view         = $this->getView  ( 'rules','html' );

		// load rules
		$_rules = $model->_load_rules ();
		
		$view->assign('rules', $_rules[0] );
		$view->assign('total', $_rules[1] );
		$view->assign('limit', $_rules[2] );
		$view->assign('limitstart', $_rules[3] );
		$view->assign('lists', $_rules[4] );
		
		// Display
		$view->_displaylist();		
	}

	/**
	* Edit Rules
	*/
	function editrule() {
	
		$model        = &$this->getModel ( 'rules' );
		$view         = $this->getView  ( 'rules','html' );
		
		$_row = $model->_edit_rule ();
		
		$view->assign('row', $_row );
		
		// Display
		$view->_edit_rule();				
	}	
	
	
	/**
	* Save Rule
	*/
	function saverule() {
		
		$model        = &$this->getModel ( 'rules' );
		// save rule(s)
		$model->_save_rule ();	

	}	
	
	/**
	* Delete Rules
	*/
	function deleterule() {
		
		$model        = &$this->getModel ( 'rules' );
		// delete rule(s)
		$model->_delete_rule ();	

	}	
	
	
	function cancelrule() {
		$app = JFactory::getApplication();
		
		$redirecturl = "index.php?option=com_alphauserpoints&task=rules";		
		$app->redirect( $redirecturl );	
	
	}
	
	
	function copyrule() {
	
		$model        = &$this->getModel ( 'rules' );
		// copy rule(s)
		$model->_copy_rule ();	
	
	}
	
	function statistics() {
	
		$model        = &$this->getModel ( 'statistics' );
		$view         = $this->getView  ( 'statistics','html' );
		
		$_stats = $model->_load_users ();
		
		$view->assign('usersStats', $_stats[0] );
		$view->assign('total', $_stats[1] );
		$view->assign('limit', $_stats[2] );
		$view->assign('limitstart', $_stats[3] );
		$view->assign('lists', $_stats[4] );
		$view->assign('ranksexist', $_stats[5] );			
		$view->assign('medalsexist', $_stats[6] );				
		
		// Display
		$view->_displaylist();	
	}
	
	function edituser () {
		
		$model        = &$this->getModel ( 'statistics' );
		$view         = $this->getView  ( 'statistics','html' );
		
		$_row = $model->_edit_user ();	
		
		$view->assign('row', $_row[0] );
		$view->assign('listrank', $_row[1] );
		$view->assign('medalsexist', $_row[2] );
		$view->assign('medalslistuser', $_row[3] );
		$view->assign('listmedals', $_row[4] );
		
		// Display
		$view->_edit_user();
	
	}
	
	function awardedmedal () {
		
		$model        = &$this->getModel ( 'statistics' );
		// save general medal
		$model->_save_medaluser ();	
	
	}
	
	function removemedaluser () {
		
		$model        = &$this->getModel ( 'statistics' );
		// delete user medal
		$model->_delete_medaluser ();	
	
	}
	
	
	function saveuser() {
	
		$model        = &$this->getModel ( 'statistics' );
		// save general user stats
		$model->_save_user ();	

	}	

	function canceluser(){
		$app = JFactory::getApplication();
		
		$redirecturl = "index.php?option=com_alphauserpoints&task=statistics";		
		$app->redirect( $redirecturl );		
	}
	
	function showdetails() {
	
		$_name		= JRequest::getVar( 'name', '', 'get', 'string' );
		$_cid		= JRequest::getVar( 'cid', '', 'get', 'string' );
	
		$model = &$this->getModel ( 'user' );
		$view  = $this->getView ( 'user','html');
		
		// load user details
		$userDetails = $model->_load_details_user ();
		
		$view->assign('userDetails', $userDetails[0] );
		$view->assign('total', $userDetails[1] );
		$view->assign('limit', $userDetails[2] );
		$view->assign('limitstart', $userDetails[3] );
		$view->assign('lists', $userDetails[4] );
		$view->assign('name', $_name );
		$view->assign('cid', $_cid );
		
		// Display
		$view->_displaylist();

	}
		
	function edituserdetails() {

		$_name		  = JRequest::getVar( 'name', '', 'get', 'string' );
		
		$model        = &$this->getModel ( 'user' );
		$view         = $this->getView  ( 'user','html' );
		
		$_row = $model->_edit_pointsDetails ();
		$_rule_name = $model->_get_rule_name ($_row->rule);
		
		$view->assign('row', $_row );	
		$view->assign('name', $_name );
		$view->assign('rulename', $_rule_name );
		
		// Display
		$view->_edit_pointsDetails();				
	
	}
	
	function saveuserdetails () {
		
		$model        = &$this->getModel ( 'user' );
		// save user details
		$model->_save_user_details ();
	
	}
	
	function canceluserdetails() {
		$app = JFactory::getApplication();
		
		$redirecturl = JRequest::getVar( 'redirect', '', 'post', 'string' );
		
		$redirecturl = "index.php?option=com_alphauserpoints&task=" . $redirecturl ;		
		$app->redirect( $redirecturl );	
	
	}
	

	function deleteuserdetails () {	
		
		$model        = &$this->getModel ( 'user' );
		// delete 
		$model->_delete_user_details ();
	}	
	
	function deleteuserallactivities() {
	
		$model        = &$this->getModel ( 'user' );
		// delete 
		$model->_delete_user_all_activities ();
	
	}
		
	
	
	/**
	* Show Form Install Plugins
	*/
	function plugins() {
		
		$view  = $this->getView ( 'plugins','html');
		
		$view->show();
	}
	
	/**
	* Upload Plugin
	*/
	function uploadfile() {
	
		$view  = $this->getView ( 'plugins','html');		
		
		$error = "";
		$msg = "";
	
		if (@is_uploaded_file($_FILES["userfile"]["tmp_name"])) {
			require_once (JPATH_COMPONENT_ADMINISTRATOR  .DS . 'assets' . DS.'includes'.DS.'alphauserpoints.installer.php');
			$installer = new aupInstaller();
			
			$file = $installer->install( $_FILES["userfile"] );
						
			if ( !is_array($file) ) {
				// extract data of xml file
				$this->loadPluginElements( $file );
			} elseif ( is_array($file) ){
				foreach ( $file as $_file ) {
					$this->loadPluginElements( $_file );
				}			
			} else {
				$error = JText::_('AUP_FILEUPLOAD_ERROR');
				JError::raiseWarning(0, $error );
				$view->show();
			}
			
			return $this->rules();		
			
		} else {
			$error = JText::_('AUP_FILEUPLOAD_ERROR');
			JError::raiseWarning(0, $error );
			$view->show();
		}
	
	}
	
	/**
	 * Loading of related XML files
	 *
	*/
	function loadPluginElements( $xmlFile ) {
		$app = JFactory::getApplication();
		
		$error = "";
	
		// XML library
		require_once( JPATH_SITE . "/libraries/domit/xml_domit_lite_include.php" );

		//$xmlDoc =& new DOMIT_Lite_Document();
		//$_xmlDoc = & $xmlDoc;
		$xmlDoc = new DOMIT_Lite_Document();
		$_xmlDoc = $xmlDoc;
		$_xmlDoc->resolveErrors( true );		
		if ($_xmlDoc->loadXML( JPATH_COMPONENT_ADMINISTRATOR . DS . 'assets' . DS . 'plugins' . DS . $xmlFile, false, true )) {
			$element = $_xmlDoc->documentElement;
			if ($element->getTagName() == 'alphauserpoints') {
				if ( $element->getAttribute('type')=='plugin' ) {

					$nameRule =& $element->getElementsByPath('rule', 1);
					$nameRule = trim($nameRule->getText());
					$descriptionRule =& $element->getElementsByPath('description', 1);
					$descriptionRule = trim($descriptionRule->getText());
					$componentRule =& $element->getElementsByPath('component', 1);
					$componentRule = trim($componentRule->getText());
					$pluginRule =& $element->getElementsByPath('plugin_function', 1);
					$pluginRule = trim($pluginRule->getText());
					$fixedpointsRule =& $element->getElementsByPath('fixed_points', 1);
					$fixedpointsRule = trim(strtolower($fixedpointsRule->getText()));
					$fixedpointsRule = ( $fixedpointsRule=='true' ) ? 1 : 0;
					//$categoryRule =& $element->getElementsByPath('category', '');
					//$categoryRule = strtolower(trim($categoryRule->getText()));
					$categoryRule = "";
					
					// insert in table
					if ( $nameRule!='' && $descriptionRule!='' && $componentRule!='' && $pluginRule!='') {
					
						$db	=& JFactory::getDBO();
						// check if already exist...					
						$query = "SELECT COUNT(*) FROM #__alpha_userpoints_rules WHERE `plugin_function`='$pluginRule'";
						$db->setQuery( $query );
						$resultCount = $db->loadResult();
						if ( !$resultCount ) {
							$query = "INSERT INTO #__alpha_userpoints_rules VALUES ('', '$nameRule', '$descriptionRule', '$componentRule', '$pluginRule', '1', '$componentRule', '', '', 0, 0, '0000-00-00 00:00:00', '', '', '', '', 0, 0, 0, 0, 1, $fixedpointsRule, '$categoryRule')";
							$db->setQuery( $query );
							if ( $db->query() ) {							
								$msg = JText::_('AUP_NEW_RULE_INSTALLED_SUCCESSFULLY') . ' (' . $nameRule . ')' ;
								$app->enqueueMessage( $msg );
							} else {
								$error = JText::_('This rule is not installed properly') ;
								JError::raiseNotice(0, $error );
							}
						} else {
							$error = JText::_('AUP_THISRULEALREADYEXIST');
							JError::raiseNotice(0, $error );
							return $this->plugins();						
						}
					}  else {
						$error = JText::_('AUP_XML_FILE_INVALID');
						JError::raiseWarning(0, $error );
						return $this->plugins();
					}										
				} else {
					$error = JText::_('AUP_XML_FILE_INVALID');
					JError::raiseWarning(0, $error );
					return $this->plugins();
				}
			} else {
				$error = JText::_('AUP_XML_FILE_INVALID');
				JError::raiseWarning(0, $error );
				return $this->plugins();
			}
		} 
	}
	
	
	/**
	* Export 50 most active users in CSV
	*/
	function exportactiveusers() {
	
		$model        = &$this->getModel ( 'exports' );
		$_row = $model->_export_most_active_users ();
		
		if ( $_row ) {		
			$totalRecords = 0;
			$fileName     = "mostactiveusers_" . uniqid(rand(), true) . ".csv";	
			$filepath     = JPATH_COMPONENT_ADMINISTRATOR . DS . 'assets' . DS . "csv" . DS . $fileName;
			
			$handler = fopen($filepath,"a");
			fwrite($handler,"NUM,USER ID,NAME,USERNAME,POINTS,ALPHAUSERPOINTS ID"."\n");

			$total = count( $_row );
			$j = 0;
			for ($i=0;$i< $total;$i++) {
				if ( $_row[$i]->referreid != 'GUEST' ) {
					$j++;
					fwrite( $handler, $j . "," . $_row[$i]->iduser . "," . $_row[$i]->name . "," . $_row[$i]->username . "," . $_row[$i]->points . "," . $_row[$i]->referreid . "\n" );
				}
			}
	
			header("Expires: Mon, 26 Nov 1962 00:00:00 GMT");
			header("Last-Modified: " . gmdate('D,d M Y H:i:s') . ' GMT');
			header("Cache-Control: no-cache, must-revalidate");
			header("Pragma: no-cache");
			header("Content-Type: text/x-comma-separated-values");
			header("Content-Disposition: attachment; filename=$fileName");
			
			readfile($filepath);
			
			exit;
		} else {
			$error = JText::_('AUP_NO_DATA');
			JError::raiseWarning(0, $error );
			return $this->cpanel();
		} 
	
	}

	/**
	* Export all e-mails addresses sent in CSV
	*/
	function exportemails() {
	
		jimport( 'joomla.mail.helper' );
	
		$model        = &$this->getModel ( 'exports' );		
		$_row = $model->_export_emails ();	
		
		if ( $_row ) {
			$totalRecords = 0;
			$fileName     = "export_emails_" . uniqid(rand(), true) . ".csv";	
			$filepath     = JPATH_COMPONENT_ADMINISTRATOR . DS . 'assets' . DS . "csv" . DS . $fileName;
			
			$handler= fopen($filepath,"a");
			fwrite($handler,"EMAIL"."\n");
			
			$total = count( $_row );
			for ($i=0;$i< $total;$i++) {
				$_row[$i]->datareference = str_replace(" [at] ", "@", $_row[$i]->datareference);
				$aEmails[0] = $this->extractEmailsFromString($_row[$i]->datareference);
				$email= $aEmails[0][0];
				if ( JMailHelper::isEmailAddress($email) ) {				
					fwrite($handler,$email."\n");
				}				
			}
	
			header("Expires: Mon, 26 Nov 1962 00:00:00 GMT");
			header("Last-Modified: " . gmdate('D,d M Y H:i:s') . ' GMT');
			header("Cache-Control: no-cache, must-revalidate");
			header("Pragma: no-cache");
			header("Content-Type: text/x-comma-separated-values");
			header("Content-Disposition: attachment; filename=$fileName");
			
			readfile($filepath);
			
			exit;
		} else {
			$error = JText::_('AUP_NO_DATA');
			JError::raiseWarning(0, $error );
			return $this->cpanel();
		} 
	
	}
	
	
	/**
	* Common publish/unpublish function
	*/
	function publish() {
	
		$option		= JRequest::getVar( 'option', 'com_alphauserpoints', 'post' );
		$table  	= JRequest::getVar( 'table', '', 'post' );	
		$cid		= JRequest::getVar( 'cid', array(0), 'post', 'array' );
		$redirect	= JRequest::getVar( 'redirect', 'cpanel' );
		
		$model      = &$this->getModel ( 'helper' );
		$model->_aup_publish( $cid, 1, $option, $table, $redirect );
	
	}
	
	function unpublish() {
	
		$option		= JRequest::getVar( 'option', 'com_alphauserpoints', 'post' );
		$table  	= JRequest::getVar( 'table', '', 'post');
		$cid		= JRequest::getVar( 'cid', array(0), 'post', 'array' );
		$redirect	= JRequest::getVar( 'redirect', 'cpanel' );
		
		$model      = &$this->getModel ( 'helper' );
		$model->_aup_publish( $cid, 0, $option, $table, $redirect );	

	}
	
	
	
	function autoapprove() {
	
		$option		= JRequest::getVar( 'option', 'com_alphauserpoints', 'post' );
		$table  	= JRequest::getVar( 'table', '', 'post' );	
		$cid		= JRequest::getVar( 'cid', array(0), 'post', 'array' );
		$redirect	= JRequest::getVar( 'redirect', 'cpanel' );
		
		$model      = &$this->getModel ( 'helper' );
		$model->_aup_autoapprove( $cid, 1, $option, $table, $redirect );
	
	}
	
	function unautoapprove() {
	
		$option		= JRequest::getVar( 'option', 'com_alphauserpoints', 'post' );
		$table  	= JRequest::getVar( 'table', '', 'post');
		$cid		= JRequest::getVar( 'cid', array(0), 'post', 'array' );
		$redirect	= JRequest::getVar( 'redirect', 'cpanel' );
		
		$model      = &$this->getModel ( 'helper' );
		$model->_aup_autoapprove( $cid, 0, $option, $table, $redirect );	

	}
	
	function approve() {
	
		$option		= JRequest::getVar( 'option', 'com_alphauserpoints', 'post' );
		$table  	= JRequest::getVar( 'table', '', 'post' );	
		$cid		= JRequest::getVar( 'cid', array(0), 'post', 'array' );
		$redirect	= JRequest::getVar( 'redirect', 'cpanel' );
		
		$model      = &$this->getModel ( 'helper' );
		$model->_aup_approve( $cid, 1, $option, $table, $redirect );
	
	}
	
	function unapprove() {
	
		$option		= JRequest::getVar( 'option', 'com_alphauserpoints', 'post' );
		$table  	= JRequest::getVar( 'table', '', 'post');
		$cid		= JRequest::getVar( 'cid', array(0), 'post', 'array' );
		$redirect	= JRequest::getVar( 'redirect', 'cpanel' );
		
		$model      = &$this->getModel ( 'helper' );
		$model->_aup_approve( $cid, 0, $option, $table, $redirect );	

	}
	
	function deletependingapproval() {
	
		$cid		= JRequest::getVar( 'cid', 0, 'get', 'int' );
		$db			=& JFactory::getDBO();
		$query = "DELETE FROM #__alpha_userpoints_details"
				. "\n WHERE `id` = '" . $cid. "'"
				;
		$db->setQuery($query);
		
		if (!$db->query()) {
			JError::raiseError( 500, $db->getErrorMsg() );
		}
		
		return $this->cpanel();
	}
	
	/**
	* Common change access function
	*/	
	function accesspublic() {
		$app = JFactory::getApplication();
		
		$cid	= JRequest::getVar( 'cid', array(), 'post', 'array' );
		JArrayHelper::toInteger($cid);

		$model =& $this->getModel( 'rules' );
		$model->setAccess($cid, 0);
		$app->redirect( 'index.php?option=com_alphauserpoints&task=rules', $msg );
	}

	function accessregistered() {
		$app = JFactory::getApplication();
		
		$cid	= JRequest::getVar( 'cid', array(), 'post', 'array' );
		JArrayHelper::toInteger($cid);

		$model =& $this->getModel( 'rules' );
		$model->setAccess($cid, 1);
		$app->redirect( 'index.php?option=com_alphauserpoints&task=rules', $msg );
	}

	function accessspecial() {
		$app = JFactory::getApplication();
		
		$cid	= JRequest::getVar( 'cid', array(), 'post', 'array' );
		JArrayHelper::toInteger($cid);

		$model =& $this->getModel( 'rules' );
		$model->setAccess($cid, 2);
		$app->redirect( 'index.php?option=com_alphauserpoints&task=rules', $msg );
	}
	
	function extractEmailsFromString($sChaine) {	 
		if(false !== preg_match_all('`\w(?:[-_.]?\w)*@\w(?:[-_.]?\w)*\.(?:[a-z]{2,4})`', $sChaine, $aEmails)) {
			if(is_array($aEmails[0]) && sizeof($aEmails[0])>0) {
				return array_unique($aEmails[0]);
			}
		}		 
		return null;
	}
	
	function applybonus() {
		
		$cid		= JRequest::getVar( 'cid', array(0), 'post', 'array' );
		
		if (count($cid) < 1) {
			JViewContent::displayError( JText::_('Select an item to') . ' ' . JText::_($action) );
			return false;
		}
		
		$model      = &$this->getModel ( 'helper' );
		$model->_bonuspoints( $cid );
	
	}
	
	function rejectrequest() {
		$app = JFactory::getApplication();	
		
		$model =& $this->getModel( 'requests' );
		$model->_rejectlevel();
		
		$error = JText::_('AUP_REQUESTREJECTED');
		JError::raiseNotice(0, $error );
		$app->redirect( 'index.php?option=com_alphauserpoints&task=cpanel' );
	
	}
	
	function changeuserlevel() {
		$app = JFactory::getApplication();
		
		$model =& $this->getModel( 'requests' );
		$model->_acceptlevel();
				
		$app->redirect( 'index.php?option=com_alphauserpoints&task=cpanel' );

	}
	
	function couponcodes() {
	
		$model =& $this->getModel( 'couponcodes' );
		
		$view         = $this->getView  ( 'couponcodes','html' );

		// load coupons
		$_couponcodes = $model->_load_couponcodes ();
		
		$view->assign('couponcodes', $_couponcodes[0] );
		$view->assign('total', $_couponcodes[1] );
		$view->assign('limit', $_couponcodes[2] );
		$view->assign('limitstart', $_couponcodes[3] );
		
		// Display
		$view->_displaylist();		
	
	}	
	
	function editcoupon() {
		
		$model        = &$this->getModel ( 'couponcodes' );
		$view         = $this->getView  ( 'couponcodes','html' );
		
		$result = $model->_edit_coupon ();		
		
		$view->assign('row', $result[0] );
		$view->assign('lists', $result[1]);
		
		// Display
		$view->_edit_coupon();
	
	}
	
	function savecoupon() {
		
		$model        = &$this->getModel ( 'couponcodes' );
		// save coupon(s)
		$model->_save_coupon ();

	}	

	function deletecoupon() {
		
		$model        = &$this->getModel ( 'couponcodes' );
		// delete coupon(s)
		$model->_delete_coupon ();

	}	
	
	function cancelcoupon() {
		$app = JFactory::getApplication();
		
		$redirecturl = "index.php?option=com_alphauserpoints&task=couponcodes";		
		$app->redirect( $redirecturl );
	
	}
	
	function coupongenerator() {

		$view  = $this->getView  ( 'couponcodes','html' );
		
		$lists = array();		
		$lists['public'] 		= JHTML::_('select.booleanlist',  'public', 'class="inputbox"', 1 );
		
		$view->assign( 'lists',  $lists );		
		
		$view->_generate_coupon();	
	
	}
	
	function savecoupongenerator() {	

		$model        = &$this->getModel ( 'couponcodes' );
		// save coupon(s)
		$model->_save_coupongenerator ();

	}	
		
	
	function stats() {
	
		$date_start = JRequest::getVar( 'date_start', '', 'post', 'string' );
		$date_end = JRequest::getVar( 'date_end', '', 'post', 'string' );
		$rule = JRequest::getVar( 'rule', '', 'post', 'int' );
	
		$model        = &$this->getModel ( 'statistics' );		
		$view         = $this->getView  ( 'stats','html' );
		
		$result = $model->_pointsearned();
		$result2 = $model->_pointsspent();
		
		$average_points_earned_by_day = $model->_average_points_earned_by_day();
		$average_points_spent_by_day = $model->_average_points_spent_by_day();
		$topcountryusers = $model->_get_most_country(6);
		$numusers = $model->_get_num_users();
		$ratio_members = $model->_get_ratio_gender_members();
		$resultinactiveusers = $model->_get_inactive_members();
		$inactiveusers = $resultinactiveusers[0];
		$num_days_inactiveusers_rule = $resultinactiveusers[1];
		
		$communitypoints = $model->_totalcurrentcommunitypoints();
		
		$listRules = $model->_getListRules($rule);
		
		$_average_age = _get_average_age_community();
		
		$view->assign('result', $result );
		$view->assign('result2', $result2 );
		$view->assign( 'date_start', $date_start );
		$view->assign( 'date_end',  $date_end );
		$view->assign( 'listrules',  $listRules );
		$view->assign( 'communitypoints',  $communitypoints );
		$view->assign( 'average_age', $_average_age );		
		$view->assign( 'average_points_earned_by_day', $average_points_earned_by_day );
		$view->assign( 'average_points_spent_by_day', $average_points_spent_by_day );		
		$view->assign( 'topcountryusers', $topcountryusers );	
		$view->assign( 'numusers', $numusers );	
		$view->assign( 'ratiomembers', $ratio_members );
		$view->assign( 'inactiveusers', $inactiveusers );
		$view->assign( 'num_days_inactiveusers_rule', $num_days_inactiveusers_rule );		
				
		// Display
		$view->_display();
	}
	
	function configuration() {
	
		$model 			= &$this->getModel('configuration');
		$view  			= $this->getView ( 'configuration','html');
		
		$results = $model->getParams();

		$view->assignRef('params', $results);		
	
		$view->display();
	
	}
	
	function saveconfiguration()
	{
		// Check for request forgeries
		JRequest::checkToken() or die( 'Invalid Token' );

		$table =& JTable::getInstance('component');
		if (!$table->loadByOption( 'com_alphauserpoints' ))
		{
			JError::raiseWarning( 500, 'Not a valid component' );
			return false;
		}

		$post = JRequest::get( 'post' );
		$table->bind( $post );

		// pre-save checks
		if (!$table->check()) {
			JError::raiseWarning( 500, $table->getError() );
			return false;
		}

		// save the changes
		if (!$table->store()) {
			JError::raiseWarning( 500, $table->getError() );
			return false;
		}
	}
	
	function raffle ()
	{	
		$model        = &$this->getModel ( 'raffle' );
	
		$view         = $this->getView  ( 'raffle','html' );

		// load raffle
		$_raffle = $model->_load_raffle ();
		
		$view->assign('raffle', $_raffle[0] );
		$view->assign('total', $_raffle[1] );
		$view->assign('limit', $_raffle[2] );
		$view->assign('limitstart', $_raffle[3] );
		
		// Display
		$view->_displaylist();	
	}

	/**
	* Edit Raffle
	*/
	function editraffle() {
		
		$model        = &$this->getModel ( 'raffle' );
		$view         = $this->getView  ( 'raffle','html' );
		
		$_row = $model->_edit_raffle ();
		
		$view->assign('row', $_row );
		
		// Display
		$view->_edit_raffle();				
	}	
	
	
	/**
	* Save Raffle
	*/
	function saveraffle() {
		
		$model        = &$this->getModel ( 'raffle' );
		// save raffle
		$model->_save_raffle ();	
	}	
	
	/**
	* Delete Raffle
	*/
	function deleteraffle() {
		
		$model        = &$this->getModel ( 'raffle' );
		// delete raffle
		$model->_delete_raffle ();
	}
	
	function registration() {
	
		$option		= JRequest::getVar( 'option', 'com_alphauserpoints', 'post' );
		$table  	= JRequest::getVar( 'table', '', 'post' );	
		$cid		= JRequest::getVar( 'cid', array(0), 'post', 'array' );
		$redirect	= JRequest::getVar( 'redirect', 'cpanel' );
		
		$model      = &$this->getModel ( 'helper' );
		$model->_aup_registration_raffle( $cid, 1, $option, $table, $redirect );
	
	}
	
	function unregistration() {
	
		$option		= JRequest::getVar( 'option', 'com_alphauserpoints', 'post' );
		$table  	= JRequest::getVar( 'table', '', 'post');
		$cid		= JRequest::getVar( 'cid', array(0), 'post', 'array' );
		$redirect	= JRequest::getVar( 'redirect', 'cpanel' );
		
		$model      = &$this->getModel ( 'helper' );
		$model->_aup_registration_raffle( $cid, 0, $option, $table, $redirect );

	}	
	
	function cancelraffle() {
		$app = JFactory::getApplication();
		
		$redirecturl = "index.php?option=com_alphauserpoints&task=raffle";
		$app->redirect( $redirecturl );		
	}
	
	function makeraffle() {
		
		$model        = &$this->getModel ( 'raffle' );
		// launch raffle
		$model->_make_raffle_now ();
		
	}
	
	function exportListUsersRaffle()
	{
		$model        = &$this->getModel ( 'raffle' );
		$_row = $model->_export_users_registration ();
	
		$fileName     = "users_registration_" . uniqid(rand(), true) . ".csv";	
		$filepath     = JPATH_COMPONENT_ADMINISTRATOR . DS . 'assets' . DS . "csv" . DS . $fileName;
		
		$handler = fopen($filepath,"a");
		fwrite($handler,"#,USER ID,NAME,USERNAME\n");
		$j=0;
		$total = count( $_row );
		
		for ($i=0;$i< $total;$i++) {
			$j++;
			fwrite( $handler, $j . "," . $_row[$i]->uid . "," . $_row[$i]->name . "," . $_row[$i]->username. "\n" );
		}
		
		header("Expires: Mon, 26 Nov 1962 00:00:00 GMT");
		header("Last-Modified: " . gmdate('D,d M Y H:i:s') . ' GMT');
		header("Cache-Control: no-cache, must-revalidate");
		header("Pragma: no-cache");
		header("Content-Type: text/x-comma-separated-values");
		header("Content-Disposition: attachment; filename=$fileName");
		
		readfile($filepath);
		
		exit;

	}
	
	function levelrank() {
	
		$model =& $this->getModel( 'levelrank' );
		
		$view         = $this->getView  ( 'levelrank','html' );
		
		// load language for upload image
		JPlugin::loadLanguage( 'com_media' );

		// load levelrank
		$_levelrank = $model->_load_levelrank ();
		
		$view->assign('levelrank', $_levelrank[0] );
		$view->assign('total', $_levelrank[1] );
		$view->assign('limit', $_levelrank[2] );
		$view->assign('limitstart', $_levelrank[3] );
		$view->assign('lists', $_levelrank[4] );
		
		// Display
		$view->_displaylist();		
	
	}	
	
	function editlevelrank() {
		
		$model        = &$this->getModel ( 'levelrank' );
		$view         = $this->getView  ( 'levelrank','html' );		
		
		// load language for upload image
		JPlugin::loadLanguage( 'com_media' );
		
		$result = $model->_edit_levelrank ();		
		
		$view->assign('row', $result[0] );
		$view->assign('lists', $result[1]);
		
		// Display
		$view->_edit_levelrank();
	
	}
	
	function savelevelrank() {
		
		$model        = &$this->getModel ( 'levelrank' );
		// save levelrank(s)
		$model->_save_levelrank ();

	}	

	function deletelevelrank() {
		
		$model        = &$this->getModel ( 'levelrank' );
		// delete levelrank(s)
		$model->_delete_levelrank ();

	}	

	
	function cancellevelrank() {
		$app = JFactory::getApplication();
		
		$redirecturl = "index.php?option=com_alphauserpoints&task=levelrank";		
		$app->redirect( $redirecturl );
	
	}
	
	function detailrank() {
	
		$model =& $this->getModel( 'levelrank' );
		
		$view  = $this->getView  ( 'levelrank','html' );

		// load levelrank
		$_detailrank = $model->_load_detailrank ();
		
		$view->assign('detailrank', $_detailrank[0] );
		$view->assign('total', $_detailrank[1] );
		$view->assign('limit', $_detailrank[2] );
		$view->assign('limitstart', $_detailrank[3] );
		
		// Display
		$view->_displaydetailrank();		
	
	}
	
	function applycustom() {	// specific user
	
		$cid		= JRequest::getVar( 'cid', '', 'default', 'string' );
		$name		= JRequest::getVar( 'name', '', 'default', 'string' );
		
		if (!$cid) {
			return false;
		}
		
		$view  = $this->getView  ( 'rules','html' );
		
		$view->assign('cid', $cid );
		$view->assign('name', $name );
		
		$view->_displaycustompoints();	
	
	}
	
	function savecustompoints() {	// specific user
	
		$referrerid = JRequest::getVar( 'cid', '', 'post', 'string' );
		$name		= JRequest::getVar( 'name', '', 'post', 'string' );
		
		$points		= JRequest::getVar( 'points', 0, 'post', 'int' );
		$reason		= JRequest::getVar( 'reason', '', 'post', 'string', JREQUEST_ALLOWHTML );		
		
		if ( $referrerid ) {
			require_once ( JPATH_SITE.DS.'components'.DS.'com_alphauserpoints'.DS.'helper.php' );			
			AlphaUserPointsHelper::userpoints ( 'sysplgaup_custom', $referrerid, 0, '', $reason, $points );
		}
	
	}
	
	function applycustomrule() {	// several users
	
		$cid		= JRequest::getVar( 'cid', array(0), 'post', 'array' );
		$cid		= implode(",", $cid);
		
		if (count($cid) < 1) {
			JViewContent::displayError( JText::_('Select an item to') . ' ' . JText::_($action) );
			return false;
		}
		
		$view  = $this->getView  ( 'rules','html' );
		
		$view->assign('cid', $cid );
		
		$view->_displaycustomrulepoints();	
	
	}

	function savecustomrulepoints() {	// several users
	
		$cid		= JRequest::getVar( 'cid', '', 'post', 'string' );
		$points		= JRequest::getVar( 'points', 0, 'post', 'int' );
		$reason		= JRequest::getVar( 'reason', '', 'post', 'string', JREQUEST_ALLOWHTML );		
		
		if ( $cid ) {
			$cid = explode(",", $cid );
		}
				
		$model      = &$this->getModel ( 'helper' );		
		$model->_customrulepoints( $cid, $reason, $points );
		
	}
	
	/**
	* Save the item(s) to the menu selected
	*/
	function saveorder()
	{
		$cid	= JRequest::getVar( 'cid', array(), 'post', 'array' );
		JArrayHelper::toInteger($cid);

		$model =& $this->getModel( 'levelrank' );
		if ($model->setOrder($cid)) {
			$msg = JText::_( 'AUP_NEW_ORDERING_SAVED' );
		}
		
		$this->setRedirect( 'index.php?option=com_alphauserpoints&task=levelrank', $msg );
	}
	
	/**
	* Save rank/medal(s) order
	*/
	function orderup()
	{		
		$cid	= JRequest::getVar( 'cid', array(), 'post', 'array' );
		JArrayHelper::toInteger($cid);

		if (isset($cid[0]) && $cid[0]) {
			$id = $cid[0];
		} else {
			$this->setRedirect( 'index.php?option=com_alphauserpoints&task=levelrank', JText::_('No Items Selected') );
			return false;
		}

		$model =& $this->getModel( 'levelrank' );
		if ($model->orderItem($id, -1)) {
			$msg = JText::_( 'AUP_ITEM_MOVED_UP' );
		} else {
			$msg = $model->getError();
		}
		$this->setRedirect( 'index.php?option=com_alphauserpoints&task=levelrank', $msg );
	}

	/**
	* Save rank/medal(s) order
	*/
	function orderdown()
	{		
		$cid	= JRequest::getVar( 'cid', array(), 'post', 'array' );
		JArrayHelper::toInteger($cid);

		if (isset($cid[0]) && $cid[0]) {
			$id = $cid[0];
		} else {
			$this->setRedirect( 'index.php?option=com_alphauserpoints&task=levelrank', JText::_('No Items Selected') );
			return false;
		}

		$model =& $this->getModel( 'levelrank' );
		if ($model->orderItem($id, 1)) {
			$msg = JText::_( 'AUP_ITEM_MOVED_DOWN' );
		} else {
			$msg = $model->getError();
		}
		$this->setRedirect( 'index.php?option=com_alphauserpoints&task=levelrank', $msg );
	}
	
	
	/* *
	 * upload image
	 */
	function upload()
	{
		$app = JFactory::getApplication();

		// load language fo component media
		JPlugin::loadLanguage( 'com_media' );
		$params =& JComponentHelper::getParams('com_media');
		
		require_once( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_media'.DS.'helpers'.DS.'media.php' );		
		
		define('COM_AUP_MEDIA_BASE', JPATH_ROOT.DS.'components'.DS.'com_alphauserpoints'.DS.'assets'.DS.'images'.DS.'awards');		

		// Check for request forgeries
		JRequest::checkToken( 'request' ) or jexit( 'Invalid Token' );

		$file 		= JRequest::getVar( 'Filedata', '', 'files', 'array' );
		$folder		= JRequest::getVar( 'folder', 'icon', '', 'path' );
		$format		= JRequest::getVar( 'format', 'html', '', 'cmd');
		$return		= JRequest::getVar( 'return-url', null, 'post', 'base64' );
		$err		= null;

		// Set FTP credentials, if given
		jimport('joomla.client.helper');
		JClientHelper::setCredentialsFromRequest('ftp');

		// Make the filename safe
		jimport('joomla.filesystem.file');
		$file['name']	= JFile::makeSafe($file['name']);

		if (isset($file['name'])) {
			$filepath = JPath::clean(COM_AUP_MEDIA_BASE.DS.$folder.DS.strtolower($file['name']));

			if (!MediaHelper::canUpload( $file, $err )) {
				if ($format == 'json') {
					jimport('joomla.error.log');
					$log = &JLog::getInstance('upload.error.php');
					$log->addEntry(array('comment' => 'Invalid: '.$filepath.': '.$err));
					header('HTTP/1.0 415 Unsupported Media Type');
					jexit('Error. Unsupported Media Type!');
				} else {
					JError::raiseNotice(100, JText::_($err));
					// REDIRECT
					if ($return) {
						$app->redirect(base64_decode($return));
					}
					return;
				}
			}

			if (JFile::exists($filepath)) {
				if ($format == 'json') {
					jimport('joomla.error.log');
					$log = &JLog::getInstance('upload.error.php');
					$log->addEntry(array('comment' => 'File already exists: '.$filepath));
					header('HTTP/1.0 409 Conflict');
					jexit('Error. File already exists');
				} else {
					JError::raiseNotice(100, JText::_('Error. File already exists'));
					// REDIRECT
					if ($return) {
						$app->redirect(base64_decode($return));
					}
					return;
				}
			}

			if (!JFile::upload($file['tmp_name'], $filepath)) {
				if ($format == 'json') {
					jimport('joomla.error.log');
					$log = &JLog::getInstance('upload.error.php');
					$log->addEntry(array('comment' => 'Cannot upload: '.$filepath));
					header('HTTP/1.0 400 Bad Request');
					jexit('Error. Unable to upload file');
				} else {
					JError::raiseWarning(100, JText::_('Error. Unable to upload file'));
					// REDIRECT
					if ($return) {
						$app->redirect(base64_decode($return));
					}
					return;
				}
			} else {
				if ($format == 'json') {
					jimport('joomla.error.log');
					$log = &JLog::getInstance();
					$log->addEntry(array('comment' => $folder));
					jexit('Upload complete');
				} else {
					$app->enqueueMessage(JText::_('Upload complete'));
					// REDIRECT
					if ($return) {
						$app->redirect(base64_decode($return));
					}
					return;
				}
			}
		} else {
			$app->redirect('index.php', 'Invalid Request', 'error');
		}
	}
	
	// export activities to CSV
	function exportallactivitiesuser()
	{
		$db =& JFactory::getDBO();
		$nullDate = $db->getNullDate();
		
		$referrerid = JRequest::getVar( 'c2id', '', 'post', 'string' );
		
		$query = "SELECT a.*, r.rule_name, r.plugin_function FROM #__alpha_userpoints_details AS a, #__alpha_userpoints_rules as r "
				."\nWHERE a.referreid='$referrerid' AND a.rule=r.id"
				."\nORDER BY a.insert_date DESC";
		$db->setQuery( $query );
		$lastpoints = $db->loadObjectList();		
	
		$fileName     = $referrerid . "_activities_" . uniqid(rand(), true) . ".csv";	
		$filepath     = JPATH_SITE . DS . 'tmp' . DS . $fileName;
		
		$handler = fopen($filepath,"a");
		$header = JText::_('AUP_DATE') . ";" . JText::_('AUP_ACTIVITY') . ";" . JText::_('AUP_POINTS') . ";" . JText::_('AUP_EXPIRE') . ";" .JText::_('AUP_DETAILS') . ";" . JText::_('AUP_APPROVED');
		fwrite($handler, $header ."\n");

		$total = count( $lastpoints );
		for ($i=0;$i< $total;$i++) {
		
			$date_insert = JHTML::_('date',  $lastpoints[$i]->insert_date,  JText::_('DATE_FORMAT_LC2') );
		
			if ( $lastpoints[$i]->expire_date == $nullDate ) {
				$date_expire =  '';
			} else {
				$date_expire = JHTML::_('date',  $lastpoints[$i]->expire_date,  JText::_('DATE_FORMAT_LC') );
			}	
			
			$approved = ( $lastpoints[$i]->approved )?  JText::_('AUP_APPROVED') :  JText::_('AUP_PENDINGAPPROVAL') ;	 					 

			fwrite( $handler, $date_insert . ";" . JText::_($lastpoints[$i]->rule_name) . ";" . $lastpoints[$i]->points . ";" . $date_expire . ";" . $lastpoints[$i]->datareference . ";" . $approved . "\n" );
		}

		header("Expires: Mon, 26 Nov 1962 00:00:00 GMT");
		header("Last-Modified: " . gmdate('D,d M Y H:i:s') . ' GMT');
		header("Cache-Control: no-cache, must-revalidate");
		header("Pragma: no-cache");
		header("Content-Type: text/x-comma-separated-values");
		header("Content-Disposition: attachment; filename=$fileName");
		
		readfile($filepath);
		
		exit;
	}
	
	function exportallactivitiesallusers()
	{
		$db =& JFactory::getDBO();
		$nullDate = $db->getNullDate();
		
		$referrerid = JRequest::getVar( 'c2id', '', 'post', 'string' );
		
		$query = "SELECT a.*, r.rule_name, r.plugin_function FROM #__alpha_userpoints_details AS a, #__alpha_userpoints_rules as r "
				."\nWHERE a.rule=r.id"
				."\nORDER BY a.insert_date DESC";
		$db->setQuery( $query );
		$lastpoints = $db->loadObjectList();		
	
		$fileName     = "all_activities_" . uniqid(rand(), true) . ".csv";	
		$filepath     = JPATH_SITE . DS . 'tmp' . DS . $fileName;
		
		$handler = fopen($filepath,"a");
		$header = JText::_('AUP_DATE') . ";" . JText::_('AUP_ACTIVITY') . ";" . JText::_('AUP_POINTS') . ";" . JText::_('AUP_EXPIRE') . ";" .JText::_('AUP_DETAILS') . ";" . JText::_('AUP_APPROVED');
		fwrite($handler, $header ."\n");

		$total = count( $lastpoints );
		for ($i=0;$i< $total;$i++) {
		
			$date_insert = JHTML::_('date',  $lastpoints[$i]->insert_date,  JText::_('DATE_FORMAT_LC2') );
		
			if ( $lastpoints[$i]->expire_date == $nullDate ) {
				$date_expire =  '';
			} else {
				$date_expire = JHTML::_('date',  $lastpoints[$i]->expire_date,  JText::_('DATE_FORMAT_LC') );
			}	
			
			$approved = ( $lastpoints[$i]->approved )?  JText::_('AUP_APPROVED') :  JText::_('AUP_PENDINGAPPROVAL') ;	 					 

			fwrite( $handler, $date_insert . ";" . JText::_($lastpoints[$i]->rule_name) . ";" . $lastpoints[$i]->points . ";" . $date_expire . ";" . $lastpoints[$i]->datareference . ";" . $approved . "\n" );
		}

		header("Expires: Mon, 26 Nov 1962 00:00:00 GMT");
		header("Last-Modified: " . gmdate('D,d M Y H:i:s') . ' GMT');
		header("Cache-Control: no-cache, must-revalidate");
		header("Pragma: no-cache");
		header("Content-Type: text/x-comma-separated-values");
		header("Content-Disposition: attachment; filename=$fileName");
		
		readfile($filepath);
		
		exit;	
	}
	
	
	function archiveActivities()
	{
		// show form for archive
		$view  = $this->getView ( 'archive','html');		
		$view->show();	
	}
	
	
	function processarchive()
	{
		$app = JFactory::getApplication();		
		
		$model       = &$this->getModel ( 'archive' );
		
		// process to combine the set of all actions in one activity from a specified date
		$combined = $model->_archive ();
		
		// Display
		$app->redirect( 'index.php?option=com_alphauserpoints&task=cpanel&recalculate=start' );
	}	
	
	//----------------------- plg_editor-xtd_raffle integration start -----------------------//
		
	function editorInsertRaffle() {
		
		$db = &JFactory::getDBO();
		$document = & JFactory::getDocument();
		$lang = & JFactory::getLanguage();
		$lang->load('plg_editors-xtd_raffle', JPATH_ADMINISTRATOR);
		
		// build raffles listbox
		$raffles_list = array();
		$query = "SELECT id, description, raffledate"
		. " FROM #__alpha_userpoints_raffle"
		. " WHERE published = '1'"
		. " AND winner1=0"
		. " ORDER BY id";
		// check if not winner (current raffles)		
		
		$db->setQuery( $query );
		$raffles = $db->loadObjectList();
		foreach ($raffles as $raffle) {
			$raffles_list[] = JHTML::_('select.option', $raffle->id, $raffle->description);
		}
		$raffles_listbox =  JHTML::_('select.genericlist', $raffles_list, 'id', 'class="inputbox" size="10"', 'value', 'text', '' );
		$eName    = JRequest::getVar('e_name');

?>
<script type="text/javascript">
function insertPagebreak()
{
	var id = document.getElementById("id").value;
	var tag;
	if (id >0){
		tag = "\{AUP::RAFFLE="+id+"\}"; 
		window.parent.jInsertEditorText(tag, '<?php echo $eName; ?>');
	}    
	window.parent.document.getElementById('sbox-window').close();
	return false;
}
</script>

    <form name="insertIdRaffle" style="font-style: bold; font-family: Arial; font-size:12px; background-color: #FFFFCC">
    <table width="100%" cellpadding="2" cellspacing="2" border="0" style="padding: 10px;">
       <tr> 
         <td colspan="2">
			<img src="components/com_alphauserpoints/assets/images/aup_logo.png" width="48px" height="48px" align="bottom" border="0"/>&nbsp;&nbsp;
            <strong><?php echo JText::_('AUP_RAFFLE_PLG_TITLE').''; ?></strong><br />
            <?php echo JText::_('AUP_RAFFLE_PLG_TITLE_DESC'); ?>
         </td>
       </tr>
       <tr>
          <td class="key" align="right" width="30%" valign="top">
              <label for="id">
                  <?php echo JText::_('AUP_RAFFLE_PLG_ID_TITLE'); ?>
              </label>
          </td>
          <td width="70%" align="left">
              <?php echo $raffles_listbox; ?>
          </td>
       </tr>
       <tr><td colspan="2"><font color="#red"><?php echo JText::_('AUP_RAFFLE_PLG_NOTE'); ?></font></td></tr>
       <tr><td colspan="2"><hr /></td></tr>
			<tr>
                <td class="key" align="right"></td>
                <td>
                    <button onclick="insertPagebreak();return false;"><?php echo JText::_('AUP_BUTTON_PLG_INSERT'); ?></button>
                </td>
            </tr>
            <tr><td colspan="2"><?php echo JText::_('AUP_RAFFLE_PLG_INFO'); ?></td></tr>
        </table>
        </form>
<?php
	}
	//------------------------ plg_editor-xtd_raffle integration end ------------------------//


	
}
?>