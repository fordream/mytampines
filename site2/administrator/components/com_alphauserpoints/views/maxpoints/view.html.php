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

class alphauserpointsViewMaxpoints extends JView {

	function showform($tpl = null) {
		
		$document	= & JFactory::getDocument();
		
  		JHTML::_('behavior.mootools');		
		$document->addScriptDeclaration("window.addEvent('domready', function(){ var JTooltips = new Tips($$('.hasTip'), { maxTitleChars: 50, fixed: false}); });");
		
		$this->assignRef('setpoints', $this->setpoints );		
		
		parent::display( $tpl);
		
	}
}
?>
