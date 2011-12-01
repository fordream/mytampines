<?php
define( '_JEXEC', 1 );
define('JPATH_BASE', dirname(__FILE__).'/../../../');
define( 'DS', DIRECTORY_SEPARATOR );
//
require_once (JPATH_BASE . DS . 'includes' . DS . 'defines.php');
require_once (JPATH_BASE . DS . 'includes' . DS . 'framework.php');
$mainframe = JFactory::getApplication('site');
//
//
	if($_POST['username'] != '')
	{
		$db =& JFactory::getDBO();
		$query = "SELECT * FROM #__users WHERE username = '".$_POST['username']."'";
		$db->setQuery( $query );
		$user = $db->loadObject();
		if(!empty($user))
		 	echo 'valid';
		else
			echo 'invalid';
		  
	}
//		
	
?>