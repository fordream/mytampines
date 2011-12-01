<?php
/*
 * @component AlphaUserPoints
 * @copyright Copyright (C) 2008-2010 Bernard Gilly
 * @license : GNU/GPL
 * @Website : http://www.alphaplug.com
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.model' );

class alphauserpointsModelConfiguration extends Jmodel {

	function getParams()
	{
		//$component	= JRequest::getCmd( 'component' );
		$table =& JTable::getInstance('component');
		$table->loadByOption( 'com_alphauserpoints' );

		$params = array();

		$path	= JPATH_ADMINISTRATOR.DS.'components'.DS.'com_alphauserpoints'.DS.'config_general.xml';
		$params['general'] = new JParameter( $table->params, $path );	
		
		$path	= JPATH_ADMINISTRATOR.DS.'components'.DS.'com_alphauserpoints'.DS.'integration.xml';
		$params['integration'] = new JParameter( $table->params, $path );		
		
		return $params;
	}	

}
?>