<?php
/*
 * @component AlphaUserPoints
 * @copyright Copyright (C) 2008-2010 Bernard Gilly
 * @license : GNU/GPL
 * @Website : http://www.alphaplug.com
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.view');
jimport( 'joomla.html.pagination' );

class alphauserpointsViewstatistics extends JView {

	function _displaylist($tpl = null) {	
		
		$document	= & JFactory::getDocument();
		
		$this->assignRef( 'usersStats', $this->usersStats );
	
		$pagination = new JPagination( $this->total, $this->limitstart, $this->limit );		
		$this->assignRef('pagination', $pagination );
		$this->assignRef('lists', $this->lists);
		$this->assignRef('ranksexist', $this->ranksexist);
		$this->assignRef('medalsexist', $this->medalsexist);
		$this->assignRef('medalslistuser', $this->medalslistuser);
		$this->assignRef('listmedals', $this->listmedals);
		
		parent::display( $tpl) ;	
		
	}
	
	function _edit_user($tpl = null) {
		
		$document	= & JFactory::getDocument();
  		JHTML::_('behavior.mootools');
		JHTML::_('behavior.calendar');
		$document->addScriptDeclaration("window.addEvent('domready', function(){ var JTooltips = new Tips($$('.hasTip'), { maxTitleChars: 50, fixed: false}); });");

		$this->assignRef( 'row', $this->row );		
		$this->assignRef( 'listrank', $this->listrank );
		$this->assignRef('medalsexist', $this->medalsexist);
	
		parent::display( "form" ) ;
	}
	
}
?>
