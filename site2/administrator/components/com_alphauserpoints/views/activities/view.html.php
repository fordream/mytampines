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

class alphauserpointsViewActivities extends JView {

	function _displaylist($tpl = null) {
		
		$document	= & JFactory::getDocument();
		
		JToolBarHelper::title(   JText::_( 'AUP_ACTIVITY' ), 'addedit' );
		JToolBarHelper::custom( 'cpanel', 'default.png', 'default.png', JText::_('AUP_CPANEL'), false );
		JToolBarHelper::custom( 'exportallactivitiesallusers', 'export.png', 'export.png', JText::_('AUP_EXPORT_ACTIVITIES'), false );
		JToolBarHelper::help( 'screen.alphauserpoints', true );		
	
		$pagination = new JPagination( $this->total, $this->limitstart, $this->limit );
		
		$this->assignRef( 'pagination', $pagination );
		$this->assignRef( 'activities', $this->activities );
		$this->assignRef( 'lists', $this->lists );
		
		parent::display( $tpl) ;
	}	
	
}
?>
