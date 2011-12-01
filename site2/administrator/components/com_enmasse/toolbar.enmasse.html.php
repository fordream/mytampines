<?php
/*------------------------------------------------------------------------
# En Masse - Social Buying Extension 2010
# ------------------------------------------------------------------------
# By Matamko.com
# Copyright (C) 2010 Matamko.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.matamko.com
# Technical Support:  Visit our forum at www.matamko.com
-------------------------------------------------------------------------*/
$language =& JFactory::getLanguage();
$base_dir = JPATH_ADMINISTRATOR.DS.'components'.DS.'com_enmasse';
$version = new JVersion;
$joomla = $version->getShortVersion();
if(substr($joomla,0,3) == '1.6'){
    $extension = 'com_enmasse16';
}else{
    $extension = 'com_enmasse';
}
if($language->load($extension, $base_dir, $language->getTag(), true) == false)
{
	 $language->load($extension, $base_dir, 'en-GB', true);
}

class TOOLBAR_enmasse
{
	function _PAY_GTY_NEW() {
		$task 	= JRequest::getCmd( 'task');
		JToolBarHelper::title( JText::_( 'T_PAYMENT_GATEWAY_MANAGEMENT').' : ['.$task.']' ,
                                           'generic.png' );
		JToolBarHelper::save();
		JToolBarHelper::cancel();
	}
	
	function _COUPON() {
		$task 	= JRequest::getCmd( 'task');
		JToolBarHelper::title( JText::_( 'T_COUPON_MANAGEMENT' ),
                                           'generic.png' );
		//JToolBarHelper::custom( 'addElement', 'new.png', 'new.png', 'New Element', false,  false );
		//JToolBarHelper::spacer();
		//JToolBarHelper::divider();
		JToolBarHelper::spacer();
		JToolBarHelper::custom( 'save', 'save.png', 'save.png', 'T_COUPON_MANAGEMENT_SAVE', false,  false );
		JToolBarHelper::cancel();
	}
	
	function _REPORT()
	{
		JToolBarHelper::title( JText::_( 'T_REPORT' ),
                                           'article.png' );
	
	}
	function _COUPONELEMENT_NEW() {
		$task 	= JRequest::getCmd( 'task');
		JToolBarHelper::title(  JText::_( 'T_COUPON_MANAGEMENT').': ['.$task.']' ,
                                           'generic.png' );
		JToolBarHelper::custom( 'saveElement', 'save.png', 'save.png', 'T_COUPON_MANAGEMENT_SAVE_ELEMENTS', false,  false );
				JToolBarHelper::custom( 'cancelElement', 'cancel.png', 'cancel.png', 'T_COUPON_MANAGEMENT_CANCEL_ELEMENTS', false,  false );
	}
	
	function _EHELP() {
		JToolBarHelper::title( JText::_( 'T_HELP' ),
                                           'generic.png' );
        JToolBarHelper::back( 'T_MAIN', 'index.php?option=com_enmasse');
	}

	function _SMENU() {
		JSubMenuHelper::addEntry(JText::_('S_SETTING'), 'index.php?option=com_enmasse&controller=setting&cid=1');
		JSubMenuHelper::addEntry(JText::_('S_CATEGORY'), 'index.php?option=com_enmasse&controller=category');
		JSubMenuHelper::addEntry(JText::_('S_LOCATION'), 'index.php?option=com_enmasse&controller=location');
		//JSubMenuHelper::addEntry(JText::_('Tax'), 'index.php?option=com_enmasse&controller=tax');
		JSubMenuHelper::addEntry(JText::_('S_PAY_GATEWAY'), 'index.php?option=com_enmasse&controller=payGty');
		JSubMenuHelper::addEntry(JText::_('S_COUPON_EDITOR'), 'index.php?option=com_enmasse&controller=coupon');
		JSubMenuHelper::addEntry(JText::_('S_EMAIL_TEMPLATE'), 'index.php?option=com_enmasse&controller=emailTemplate');
		JSubMenuHelper::addEntry(JText::_('S_SALE_PERSON'), 'index.php?option=com_enmasse&controller=salesPerson');
		JSubMenuHelper::addEntry(JText::_('S_MERCHANT'), 'index.php?option=com_enmasse&controller=merchant');
		JSubMenuHelper::addEntry(JText::_('S_DEAL'), 'index.php?option=com_enmasse&controller=deal');
		JSubMenuHelper::addEntry(JText::_('S_ORDER'), 'index.php?option=com_enmasse&controller=order');
		JSubMenuHelper::addEntry(JText::_('S_REPORT'), 'index.php?option=com_enmasse&controller=report');
		JSubMenuHelper::addEntry(JText::_('S_HELP'), 'index.php?option=com_enmasse&controller=help');
	}


	function _PAY_GTY() {
		JToolBarHelper::title( JText::_( 'T_PAYMENT_GATEWAY_MANAGEMENT' ),
                                           'generic.png' );
		JToolBarHelper::publishList();
		JToolBarHelper::unpublishList();
		JToolBarHelper::editList();
		JToolBarHelper::deleteList(JText::_('DELETE_CONFIRM'));
		JToolBarHelper::addNew();
		JToolBarHelper::custom( 'control', 'back.png', 'back.png', 'T_MAIN', false,  false );
	}
	
	function _PAY_GTY_EMPTY() {
		JToolBarHelper::title( JText::_( 'T_PAYMENT_GATEWAY_MANAGEMENT' ),
                                           'generic.png' );
		JToolBarHelper::addNew();
		JToolBarHelper::custom( 'control', 'back.png', 'back.png', 'T_MAIN', false,  false );
	}	

	function _CATEGORY_NEW() {
		$task 	= JRequest::getCmd( 'task');
		JToolBarHelper::title( JText::_( 'T_CATEGORY_MANAGEMENT').' : ['.$task.']' ,
                                           'generic.png' );
		JToolBarHelper::save();
		JToolBarHelper::cancel();
	}

	function _CATEGORY() {
		JToolBarHelper::title( JText::_( 'T_CATEGORY_MANAGEMENT' ),
                                           'generic.png' );
		JToolBarHelper::publishList();
		JToolBarHelper::unpublishList();
		JToolBarHelper::editList();
		JToolBarHelper::deleteList(JText::_('DELETE_CONFIRM'));
		JToolBarHelper::addNew();
		JToolBarHelper::custom( 'control', 'back.png', 'back.png', 'T_MAIN', false,  false );
	}
	
	function _CATEGORY_EMPTY() {
		JToolBarHelper::title( JText::_( 'T_CATEGORY_MANAGEMENT' ),
                                           'generic.png' );
		JToolBarHelper::addNew();
		JToolBarHelper::custom( 'control', 'back.png', 'back.png', 'T_MAIN', false,  false );
	}	

	function _SALESPERSON() {
		JToolBarHelper::title( JText::_( 'T_SALES_PERSON_MANAGEMENT' ),
                                           'generic.png' );
		JToolBarHelper::publishList();
		JToolBarHelper::unpublishList();
		JToolBarHelper::editList();
		JToolBarHelper::deleteList(JText::_('DELETE_CONFIRM'));
		JToolBarHelper::addNew();
		JToolBarHelper::custom( 'control', 'back.png', 'back.png', 'T_MAIN', false,  false );
	}

	function _SALESPERSON_EMPTY() {
		JToolBarHelper::title( JText::_( 'T_SALES_PERSON_MANAGEMENT' ),
                                           'generic.png' );
		JToolBarHelper::addNew();
		JToolBarHelper::custom( 'control', 'back.png', 'back.png', 'T_MAIN', false,  false );
	}
	
	function _SALESPERSON_NEW() {
		$task 	= JRequest::getCmd( 'task');
		JToolBarHelper::title( JText::_( 'T_SALES_PERSON_MANAGEMENT'). ' : ['.$task.']' ,
                                           'generic.png' );
		JToolBarHelper::save();
		JToolBarHelper::cancel();
	}

	function _LOCATION_NEW() {
		$task   = JRequest::getCmd( 'task');
		JToolBarHelper::title( JText::_( 'T_LOCATION_MANAGEMENT').' : ['.$task.']' ,
                                           'generic.png' );
		JToolBarHelper::save();
		JToolBarHelper::cancel();
	}

	function _LOCATION() {
		JToolBarHelper::title( JText::_( 'T_LOCATION_MANAGEMENT' ),
                                           'generic.png' );
		JToolBarHelper::publishList();
		JToolBarHelper::unpublishList();
		JToolBarHelper::editList();
		JToolBarHelper::deleteList(JText::_('DELETE_CONFIRM'));
		JToolBarHelper::addNew();
		JToolBarHelper::custom( 'control', 'back.png', 'back.png', 'T_MAIN', false,  false );
	}
	
	function _LOCATION_EMPTY() {
		JToolBarHelper::title( JText::_( 'T_LOCATION_MANAGEMENT' ),
                                           'generic.png' );
		JToolBarHelper::addNew();
		JToolBarHelper::custom( 'control', 'back.png', 'back.png', 'T_MAIN', false,  false );
	}	

	function _MERCHANT() {
		JToolBarHelper::title( JText::_( 'T_MERCHANT_MANAGEMENT' ),
                                           'generic.png' );
		JToolBarHelper::publishList();
		JToolBarHelper::unpublishList();
		JToolBarHelper::editList();
		JToolBarHelper::deleteList(JText::_('DELETE_CONFIRM'));
		JToolBarHelper::addNew();
		JToolBarHelper::custom( 'control', 'back.png', 'back.png', 'T_MAIN', false,  false );
	}
	
	function _MERCHANT_EMPTY() {
		JToolBarHelper::title( JText::_( 'T_MERCHANT_MANAGEMENT' ),
                                           'generic.png' );
		JToolBarHelper::addNew();
		JToolBarHelper::custom( 'control', 'back.png', 'back.png', 'T_MAIN', false,  false );
	}

	function _MERCHANT_NEW() {
		$task 	= JRequest::getCmd( 'task');
		JToolBarHelper::title( JText::_( 'T_MERCHANT_MANAGEMENT').' : ['.$task.']' ,
                                           'generic.png' );
		JToolBarHelper::save();
		JToolBarHelper::cancel();
	}

	function _DEAL() {
		JToolBarHelper::title( JText::_( 'T_DEAL_MANAGEMENT' ),
                                           'generic.png' );
		JToolBarHelper::custom( 'approveDeal', 'apply.png', 'apply.png', 'T_DEAL_APPROVE_PENDING', false,  false );
		JToolBarHelper::custom( 'voidDeal', 'cancel.png', 'cancel.png', 'T_DEAL_VOID_DEAL', false,  false );
		JToolBarHelper::custom( 'confirmDeal', 'upload.png', 'upload.png', 'T_DEAL_CONFIRM_DEAL', false,  false );
		JToolBarHelper::spacer();
		JToolBarHelper::divider();
		JToolBarHelper::spacer();
		JToolBarHelper::publishList();
		JToolBarHelper::unpublishList();
		JToolBarHelper::editList();
		JToolBarHelper::deleteList(JText::_('DELETE_CONFIRM'));
		JToolBarHelper::addNew();
		JToolBarHelper::custom( 'copyDeal', 'copy.png', 'copy.png', 'Copy', false,  false ); //Amol
		JToolBarHelper::custom( 'control', 'back.png', 'back.png', 'T_MAIN', false,  false );
	}
	
	function _DEAL_EMPTY() {
		JToolBarHelper::title( JText::_( 'T_DEAL_MANAGEMENT' ),
                                           'generic.png' );
		JToolBarHelper::addNew();
		JToolBarHelper::custom( 'control', 'back.png', 'back.png', 'T_MAIN', false,  false );
	}	

	function _DEAL_NEW() {
		$task 	= JRequest::getCmd( 'task');
		JToolBarHelper::title( JText::_( 'T_DEAL_MANAGEMENT').' : ['.$task.']' ,
                                           'generic.png' );
		JToolBarHelper::save();
		JToolBarHelper::cancel();
	}

	function _TAXES() {
		JToolBarHelper::title( JText::_( 'TAX MANAGEMENT' ),
                                           'generic.png' );
		JToolBarHelper::publishList();
		JToolBarHelper::unpublishList();
		JToolBarHelper::editList();
		JToolBarHelper::deleteList(JText::_('DELETE_CONFIRM'));
		JToolBarHelper::addNew();
		JToolBarHelper::custom( 'control', 'back.png', 'back.png', 'T_MAIN', false,  false );
	}

	function _TAXES_NEW() {
		$task 	= JRequest::getCmd( 'task');
		JToolBarHelper::title( JText::_( 'TAX MANAGEMENT : ['.$task.']' ),
                                           'generic.png' );
		JToolBarHelper::save();
		JToolBarHelper::cancel();
	}

	function _SETTING() {
		JToolBarHelper::title( JText::_( 'T_SYSTEM_SETTING' ),
                                           'generic.png' );
		JToolBarHelper::save();
		JToolBarHelper::custom( 'control', 'back.png', 'back.png', 'T_MAIN', false,  false );
	}

	function _ORDER() {
		JToolBarHelper::title( JText::_( 'T_ORDER_MANAGEMENT' ),
                                           'generic.png' );
		
		JToolBarHelper::custom( 'control', 'back.png', 'back.png', 'T_MAIN', false,  false );
	}

	function _ORDER_NEW() {
		$task 	= JRequest::getCmd( 'task');
		JToolBarHelper::title( JText::_( 'T_ORDER_MANAGEMENT' ),
                                           'generic.png' );
		JToolBarHelper::save();		
		JToolBarHelper::custom( 'back', 'back.png', 'back.png', 'Back', false,  false );
	}

	function _EMAILTEMPLATE() {
		JToolBarHelper::title( JText::_( 'T_EMAIL_TEMPLATE_MANAGEMENT' ),
                                           'generic.png' );
		JToolBarHelper::editList();
		//JToolBarHelper::deleteList();
		//JToolBarHelper::addNew();
		JToolBarHelper::custom( 'control', 'back.png', 'back.png', 'T_MAIN', false,  false );
	}

	function _EMAILTEMPLATE_NEW() {
		$task   = JRequest::getCmd( 'task');
		JToolBarHelper::title( JText::_( 'T_EMAIL_TEMPLATE_MANAGEMENT').' : ['.$task.']' ,
                                           'generic.png' );
		JToolBarHelper::save();
		JToolBarHelper::cancel();
	}


	function _DEFAULT() {
		JToolBarHelper::title( JText::_( 'DASH_BOARD' ),
                                           'article.png' );
		JToolBarHelper::preferences('com_enmasse');
        JToolBarHelper::divider();
	}

	function _HELP() {
		JToolBarHelper::title( JText::_( 'EN MASSE HELP' ),
                                           'generic.png' );
		//	JToolBarHelper::editList();
		//    JToolBarHelper::custom( 'control', 'back.png', 'back.png', 'Main', false,  false );
	}

}
?>