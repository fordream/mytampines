<?php
/*
 * @component AlphaUserPoints
 * @copyright Copyright (C) 2008-2010 Bernard Gilly
 * @license : GNU/GPL
 * @Website : http://www.alphaplug.com
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

Jimport( 'joomla.application.component.view');

class alphauserpointsViewPlugins extends JView {

	function show($tpl = null) {
		JToolBarHelper::title( JText::_( 'AUP_PLUGINS' ), 'plugin' );
		// TODO : Why custom not work ?
		//JToolBarHelper::custom( 'cpanel', 'default', '', JText::_( 'AUP_HOME' ), false  );
		JToolBarHelper::back();
		JToolBarHelper::help( 'screen.alphauserpoints', true );
		//JRequest::setVar( 'hidemainmenu', 1 );
		parent::display( $tpl) ;		
	}
}
?>
