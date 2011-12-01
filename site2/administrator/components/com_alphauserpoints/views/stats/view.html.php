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

class alphauserpointsViewStats extends JView {

	function _display($tpl = null) {
	
		JToolBarHelper::title( JText::_( 'AUP_STATISTICS' ), 'searchtext' );
		JToolBarHelper::custom( 'cpanel', 'default', '', JText::_('AUP_CPANEL'), false  );
		JToolBarHelper::back();
		JToolBarHelper::help( 'screen.alphauserpoints', true );
		
		$this->assignRef( 'result', $this->result );
		$this->assignRef( 'result2', $this->result2 );
		$this->assignRef( 'date_start', $this->date_start );
		$this->assignRef( 'date_end', $this->date_end );
		$this->assignRef( 'listrules', $this->listrules );
		$this->assignRef( 'communitypoints', $this->communitypoints );
		$this->assignRef( 'average_age', $this->average_age );
		$this->assignRef( 'average_points_earned_by_day', $this->average_points_earned_by_day );
		$this->assignRef( 'average_points_spent_by_day', $this->average_points_spent_by_day );
		$this->assignRef( 'topcountryusers', $this->topcountryusers );
		$this->assignRef( 'numusers', $this->numusers );
		$this->assignRef( 'ratiomembers', $this->ratiomembers );
		$this->assignRef( 'inactiveusers', $this->inactiveusers );
		$this->assignRef( 'num_days_inactiveusers_rule', $this->num_days_inactiveusers_rule );
		
		parent::display( $tpl);
		
	}
}
?>
