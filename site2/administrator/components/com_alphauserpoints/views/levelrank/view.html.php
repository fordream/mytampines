<?php
/*
 * @component AlphaUserPoints
 * @copyright Copyright (C) 2008-2010 Bernard Gilly
 * @license : GNU/GPL
 * @Website : http://www.alphaplug.com
 */


// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.view' );
jimport( 'joomla.html.pagination' );

class alphauserpointsViewLevelrank extends JView {

	function _displaylist($tpl = null) {
		
		$document	= & JFactory::getDocument();		
	
		$pagination = new JPagination( $this->total, $this->limitstart, $this->limit );		
		
		$this->assignRef( 'pagination', $pagination );
		$this->assignRef( 'levelrank', $this->levelrank );
		$this->assignRef( 'lists',  $this->lists );
		
		parent::display( $tpl) ;
	}
	
	function _edit_levelrank($tpl = null) {
		
		$document	= & JFactory::getDocument();
  		JHTML::_('behavior.mootools');
		$document->addScriptDeclaration("window.addEvent('domready', function(){ var JTooltips = new Tips($$('.hasTip'), { maxTitleChars: 50, fixed: false}); });");

		$this->assignRef( 'row', $this->row );
		$this->assignRef( 'lists', $this->lists );
		
		parent::display( "form" ) ;
	}
	
	
	function  _displaydetailrank($tpl = null) {

		$document	= & JFactory::getDocument();
		
		$this->assignRef( 'detailrank', $this->detailrank );
	
		$pagination = new JPagination( $this->total, $this->limitstart, $this->limit );		
		$this->assignRef( 'pagination', $pagination );
		
		parent::display( "listing" );
	}
}
?>
