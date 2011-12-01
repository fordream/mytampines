<?php
/*
 * @component AlphaUserPoints
 * @copyright Copyright (C) 2008 Bernard Gilly
 * @license : GNU/GPL
 * @Website : http://www.alphaplug.com
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.view');
jimport('joomla.filter.output');
jimport('joomla.html.pane');

class alphauserpointsViewConfiguration extends JView {

	function display($tpl = null) {	
				
		JRequest::setVar('tmpl', 'component');
	
		$document = & JFactory::getDocument();
		$document->setTitle( JText::_('AUP_CONFIGURATION') );

		$language = JFactory::getLanguage();
		$tag = $language->getTag();		
		
		$this->assignRef('tag', $tag);
		$this->assignRef('params', $this->params);
		
		JHTML::_('behavior.tooltip');
		
		parent::display( $tpl) ;		
	}
}
?>