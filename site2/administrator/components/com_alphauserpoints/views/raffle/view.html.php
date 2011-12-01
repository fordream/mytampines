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

class alphauserpointsViewRaffle extends JView {

	function _displaylist($tpl = null) {

		$document	= & JFactory::getDocument();
		
		$this->assignRef( 'raffle', $this->raffle );
	
		$pagination = new JPagination( $this->total, $this->limitstart, $this->limit );		
		$this->assignRef( 'pagination', $pagination );
		
		parent::display( $tpl) ;
	}
	
	function _edit_raffle($tpl = null) {
		
		$document	= & JFactory::getDocument();
  		JHTML::_('behavior.mootools');
		JHTML::_('behavior.calendar');
		$document->addScriptDeclaration("window.addEvent('domready', function(){ var JTooltips = new Tips($$('.hasTip'), { maxTitleChars: 50, fixed: false}); });");
		
		$lists = array();
		// build the html
		$lists['published'] = JHTML::_('select.booleanlist', 'published', '', $this->row->published);		
		$lists['inscription'] = JHTML::_('select.booleanlist', 'inscription', '', $this->row->inscription);
		$lists['multipleentries'] = JHTML::_('select.booleanlist', 'multipleentries', '', $this->row->multipleentries);		
		
		$lists['removepointstoparticipate'] = JHTML::_('select.booleanlist', 'removepointstoparticipate', '', $this->row->removepointstoparticipate);
		
		$javascript = 'onchange="return mxctoggleSystemRaffle(\'rafflesystemexpand\'+this.value);"';
		$rafflesys[] = JHTML::_('select.option', '0', JText::_( 'AUP_POINTS' ));
		$rafflesys[] = JHTML::_('select.option', '1', JText::_( 'AUP_COUPON_CODES' ));
		$rafflesys[] = JHTML::_('select.option', '2', JText::_( 'AUP_EMAIL_WITH_A_LINK_TO_DOWNLOAD' ));
		$lists['rafflesystem'] = JHTML::_( 'select.genericlist', $rafflesys, 'rafflesystem', 'class="inputbox" size="1" ' . $javascript, 'value', 'text', $this->row->rafflesystem );
		//$lists['rafflesystem'] = JHTML::_( 'select.genericlist', $rafflesys, 'rafflesystem' );
		
		$numwin[] = JHTML::_('select.option', '1', '1');
		$numwin[] = JHTML::_('select.option', '2', '2');
		$numwin[] = JHTML::_('select.option', '3', '3');
		$lists['numwinner'] = JHTML::_( 'select.genericlist', $numwin, 'numwinner', 'class="inputbox" size="1"', 'value', 'text', $this->row->numwinner );
		
		$db			    =& JFactory::getDBO();
		$query = "SELECT * FROM #__alpha_userpoints_coupons";
		$db->setQuery($query);
		$couponcodes = $db->loadObjectlist();
		
		$selCouponCode[] = JHTML::_('select.option', '0', JText::_( 'AUP_SELECT_A_COUPON_CODE_FOR_THIS_RANK' ));
		if ( $couponcodes ) 
		{
			foreach ($couponcodes as $couponcode) 
			{
				$selCouponCode[] = JHTML::_('select.option', $couponcode->id, $couponcode->couponcode . ' [ '.$couponcode->points . ' ' . JText::_( 'AUP_POINTS' )  . ' ]');
			}
		}
		
		$lists['couponcodeid1'] = JHTML::_( 'select.genericlist', $selCouponCode, 'couponcodeid1', 'class="inputbox" size="1" ', 'value', 'text', $this->row->couponcodeid1 );
		$lists['couponcodeid2'] = JHTML::_( 'select.genericlist', $selCouponCode, 'couponcodeid2', 'class="inputbox" size="1" ', 'value', 'text', $this->row->couponcodeid2 );
		$lists['couponcodeid3'] = JHTML::_( 'select.genericlist', $selCouponCode, 'couponcodeid3', 'class="inputbox" size="1" ', 'value', 'text', $this->row->couponcodeid3 );
		
		/*
		$selEmailFileattached[] = JHTML::_('select.option', '0', JText::_( 'AUP_SELECT_A_FILE_TO_ATTACH' ));
		//switch ( specificdowloadsoftware) {
			//default:
				$table = 'p2dxt_files';
				//break;
		//}
			$query = "SELECT id, filename FROM #__$table";
			$db->setQuery($query);
			$filesToAttach = $db->loadObjectlist();		
			if ( $filesToAttach) {
				foreach ($filesToAttach as $fileToAttach) 
				{
					$selEmailFileattached[] = JHTML::_('select.option', $fileToAttach->id, $fileToAttach->filename);
				}
			}
		
		$lists['emailFileAttached1'] = JHTML::_( 'select.genericlist', $selEmailFileattached, 'emailFileAttached1', 'class="inputbox" size="1" ', 'value', 'text', $this->row->couponcodeid1 );
		$lists['emailFileAttached2'] = JHTML::_( 'select.genericlist', $selEmailFileattached, 'emailFileAttached2', 'class="inputbox" size="1" ', 'value', 'text', $this->row->couponcodeid2 );
		$lists['emailFileAttached3'] = JHTML::_( 'select.genericlist', $selEmailFileattached, 'emailFileAttached3', 'class="inputbox" size="1" ', 'value', 'text', $this->row->couponcodeid3 );
		*/
		
		$lists['sendcouponbyemail'] = JHTML::_('select.booleanlist', 'sendcouponbyemail', '', $this->row->sendcouponbyemail);
		
		$this->assignRef( 'row', $this->row );
		$this->assignRef( 'lists', $lists );				
	
		parent::display( "form" ) ;
	}
}
?>
