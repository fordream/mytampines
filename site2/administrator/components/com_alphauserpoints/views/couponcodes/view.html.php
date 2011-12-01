<?php
/*
 * @component AlphaUserPoints
 * @copyright Copyright (C) 2008 Bernard Gilly
 * @license : GNU/GPL
 * @Website : http://www.alphaplug.com
 */


// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.view' );
jimport( 'joomla.html.pagination' );

class alphauserpointsViewCouponcodes extends JView {

	function _displaylist($tpl = null) {

		$document	= & JFactory::getDocument();
		
		$this->assignRef( 'couponcodes', $this->couponcodes );
	
		$pagination = new JPagination( $this->total, $this->limitstart, $this->limit );		
		$this->assignRef( 'pagination', $pagination );
		
		parent::display( $tpl) ;
	}
	
	function _edit_coupon($tpl = null) {
		
		$document	= & JFactory::getDocument();
  		JHTML::_('behavior.mootools');
		JHTML::_('behavior.calendar');
		$document->addScriptDeclaration("window.addEvent('domready', function(){ var JTooltips = new Tips($$('.hasTip'), { maxTitleChars: 50, fixed: false}); });");

		$this->assignRef( 'row', $this->row );
		$this->assignRef( 'lists', $this->lists );
		
		parent::display( "form" ) ;
	}
	
	function _generate_coupon($tpl = null) {
		
		$document	= & JFactory::getDocument();
  		JHTML::_('behavior.mootools');
		JHTML::_('behavior.calendar');
		
		$this->assignRef( 'lists', $this->lists );
		
		parent::display( "generator" ) ;
	}
	
}
?>
