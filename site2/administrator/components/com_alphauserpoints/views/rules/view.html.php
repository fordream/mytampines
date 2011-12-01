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

class alphauserpointsViewRules extends JView {

	function _displaylist($tpl = null) {
		
		$document	= & JFactory::getDocument();		
		
		$this->assignRef( 'rules', $this->rules );
	
		$pagination = new JPagination( $this->total, $this->limitstart, $this->limit );
		$this->assignRef( 'pagination', $pagination );
		$this->assignRef( 'lists', $this->lists );
		
		
		parent::display( $tpl) ;
	}
	
	function _edit_rule($tpl = null) {
		
		$document	= & JFactory::getDocument();
  		JHTML::_('behavior.mootools');
		JHTML::_('behavior.calendar');
		$document->addScriptDeclaration("window.addEvent('domready', function(){ var JTooltips = new Tips($$('.hasTip'), { maxTitleChars: 50, fixed: false}); });");
		
		$lists = array();
		// build the html radio buttons for published
		$lists['published'] = JHTML::_('select.booleanlist', 'published', '', $this->row->published);		
		$lists['autoapproved'] = JHTML::_('select.booleanlist', 'autoapproved', '', $this->row->autoapproved);
		
		$auth[] = JHTML::_('select.option', 'Registered', 'Registered');
		$auth[] = JHTML::_('select.option', 'Author', 'Author');
		$auth[] = JHTML::_('select.option', 'Editor', 'Editor');
		$auth[] = JHTML::_('select.option', 'Publisher', 'Publisher');

		$lists['percentage'] = JHTML::_('select.booleanlist', 'percentage', '', $this->row->percentage);
		
		$options[] = JHTML::_('select.option', '', JText::_( 'AUP_NONE' ) );
		$options[] = JHTML::_('select.option', 'us', JText::_( 'AUP_CAT_USER' ) );
		$options[] = JHTML::_('select.option', 'co', JText::_( 'AUP_CAT_COMMUNITY' ) );
		$options[] = JHTML::_('select.option', 'ar', JText::_( 'AUP_CAT_ARTICLE' ) );
		$options[] = JHTML::_('select.option', 'li', JText::_( 'AUP_CAT_LINK' ) );
		$options[] = JHTML::_('select.option', 'po', JText::_( 'AUP_CAT_POLL_QUIZZ' ) );		
		$options[] = JHTML::_('select.option', 're', JText::_( 'AUP_CAT_RECOMMEND_INVITE' ) );
		$options[] = JHTML::_('select.option', 'fo', JText::_( 'AUP_CAT_COMMENT_FORUM' ) );
		$options[] = JHTML::_('select.option', 'vi', JText::_( 'AUP_CAT_VIDEO' ) );		
		$options[] = JHTML::_('select.option', 'ph', JText::_( 'CAT_CAT_PHOTO' ) );
		$options[] = JHTML::_('select.option', 'mu', JText::_( 'AUP_CAT_MUSIC' ) );
		$options[] = JHTML::_('select.option', 'sh', JText::_( 'AUP_CAT_SHOPPING' ) );	
		$options[] = JHTML::_('select.option', 'pu', JText::_( 'AUP_CAT_PURCHASING' ) );		
		$options[] = JHTML::_('select.option', 'cd', JText::_( 'AUP_CAT_COUPON_CODE' ) );
		$options[] = JHTML::_('select.option', 'su', JText::_( 'AUP_CAT_SUBSCRIPTION' ) );
		$options[] = JHTML::_('select.option', 'sy', JText::_( 'AUP_CAT_SYSTEM' ) );	
		$options[] = JHTML::_('select.option', 'ot', JText::_( 'AUP_CAT_OTHER' ) );		
		$lists['category'] = JHTML::_('select.genericlist', $options, 'category', 'class="inputbox" size="1"' ,'value', 'text', $this->row->category );
		
		$options = "";
		$options[] = JHTML::_('select.option', '1', '1 ' . JText::_( 'AUP_DAY' ) );
		$options[] = JHTML::_('select.option', '2', '2 ' . JText::_( 'AUP_DAYS' ) );
		$options[] = JHTML::_('select.option', '3', '3 ' . JText::_( 'AUP_DAYS' ) );
		$options[] = JHTML::_('select.option', '4', '4 ' . JText::_( 'AUP_DAYS' ) );
		$options[] = JHTML::_('select.option', '5', '5 ' . JText::_( 'AUP_DAYS' ) );
		$options[] = JHTML::_('select.option', '6', '6 ' . JText::_( 'AUP_DAYS' ) );		
		$options[] = JHTML::_('select.option', '7', '7 ' . JText::_( 'AUP_DAYS' ) );
		$options[] = JHTML::_('select.option', '8', '8 ' . JText::_( 'AUP_DAYS' ) );
		$options[] = JHTML::_('select.option', '9', '9 ' . JText::_( 'AUP_DAYS' ) );		
		$options[] = JHTML::_('select.option', '10', '10 ' . JText::_( 'AUP_DAYS' ) );
		$options[] = JHTML::_('select.option', '11', '11 ' . JText::_( 'AUP_DAYS' ) );
		$options[] = JHTML::_('select.option', '12', '12 ' . JText::_( 'AUP_DAYS' ) );	
		$options[] = JHTML::_('select.option', '13', '13 ' . JText::_( 'AUP_DAYS' ) );		
		$options[] = JHTML::_('select.option', '14', '14 ' . JText::_( 'AUP_DAYS' ) );
		$options[] = JHTML::_('select.option', '15', '15 ' . JText::_( 'AUP_DAYS' ) );
		$options[] = JHTML::_('select.option', '20', '20 ' . JText::_( 'AUP_DAYS' ) );	
		$options[] = JHTML::_('select.option', '25', '25 ' . JText::_( 'AUP_DAYS' ) );		
		$options[] = JHTML::_('select.option', '30', '30 ' . JText::_( 'AUP_DAYS' ) );	
		$options[] = JHTML::_('select.option', '60', '2 ' . JText::_( 'AUP_MONTHS' ) );		
		$options[] = JHTML::_('select.option', '90', '3 ' . JText::_( 'AUP_MONTHS' ) );
		$options[] = JHTML::_('select.option', '180', '6 ' . JText::_( 'AUP_MONTHS' ) );
		$options[] = JHTML::_('select.option', '365', '1 ' . JText::_( 'AUP_YEAR' ) );
		$options[] = JHTML::_('select.option', '730', '2 ' . JText::_( 'AUP_YEARS' ) );
		$options[] = JHTML::_('select.option', '1825', '5 ' . JText::_( 'AUP_YEARS' ) );
		
		$lists['inactive_preset_period'] = JHTML::_('select.genericlist', $options, 'content_items', 'class="inputbox" size="1"' ,'value', 'text', $this->row->content_items );		
		
		$this->assignRef( 'row', $this->row );
		$this->assignRef( 'lists', $lists );				
	
		parent::display( "form" ) ;
	}
	
	function _displaycustompoints() {	

		$this->assignRef( 'cid', $this->cid );
		$this->assignRef( 'name', $this->name );
		
		parent::display( "custom" ) ;
	}
	
	function _displaycustomrulepoints(){
		
		$this->assignRef( 'cid', $this->cid );
		
		parent::display( "custom2" ) ;	
	}
}
?>
