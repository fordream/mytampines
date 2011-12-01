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

class alphauserpointsViewCpanel extends JView {

	function show($tpl = null) {	
				
		JToolBarHelper::title(   'AlphaUserPoints :: ' . JText::_( 'AUP_CPANEL' ), 'cpanel' );
		
		$bar = & JToolBar::getInstance('toolbar');
		$bar->appendButton( 'Popup', 'config', 'Preferences', 'index.php?option=com_alphauserpoints&task=configuration', _ALPHAUSERPOINTS_WIDTH_POPUP_CONFIG, _ALPHAUSERPOINTS_HEIGHT_POPUP_CONFIG );
		JToolBarHelper::help( 'screen.alphauserpoints', true );
		
		require_once (JPATH_COMPONENT.DS.'assets'.DS.'includes'.DS.'functions.php');
		
		$language = JFactory::getLanguage();
		$tag = $language->getTag();
		
		jimport('joomla.html.pane');
		$pane =& JPane::getInstance('sliders');		
		
		$this->assignRef('tag', $tag);
		$this->assignRef('pane', $pane);		
		$this->assignRef('top10', $this->top10);
		$this->assignRef('needSync', $this->needSync);
		$this->assignRef('check', $this->check);
		$this->assignRef('params', $this->params);
		$this->assignRef('lastactivities', $this->lastactivities);
		$this->assignRef('synch', $this->synch);
		$this->assignRef('recalculate', $this->recalculate);
		$this->assignRef('rulechangelevelactivate', $this->rulechangelevelactivate);
		$this->assignRef('communitypoints', $this->communitypoints);		
		
		parent::display( $tpl) ;		
	}
}
?>
