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

class alphauserpointsViewArchive extends JView {

	function show($tpl = null) {
		
		$document	= & JFactory::getDocument();
		
		JToolBarHelper::title(  JText::_('AUP_COMBINE_ACTIVITIES'), 'systeminfo' );
		JToolBarHelper::custom( 'cpanel', 'default.png', 'default.png', JText::_('AUP_CPANEL'), false );
		JToolBarHelper::back();
		JToolBarHelper::help( 'screen.alphauserpoints', true );
		
  		JHTML::_('behavior.mootools');		
		$document->addScriptDeclaration("window.addEvent('domready', function(){ var JTooltips = new Tips($$('.hasTip'), { maxTitleChars: 50, fixed: false}); });");
		
		parent::display( $tpl);
		
	}
}
?>
