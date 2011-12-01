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

defined( '_JEXEC' ) or die( 'Restricted access' );
jimport( 'joomla.application.component.view');
require_once( JPATH_ADMINISTRATOR . DS ."components". DS ."com_enmasse". DS ."toolbar.enmasse.html.php");

class EnmasseViewSetting extends JView
{
	function display($tpl = null)
	{	
		$cid = JRequest::getVar( 'cid', array(0), '', 'array' );;
		TOOLBAR_enmasse::_SMENU();
		TOOLBAR_enmasse::_SETTING();
		
		$setting 			= JModel::getInstance('setting','enmasseModel')->getSetting($cid[0]);
		$countryJOptList 	= JModel::getInstance('setting','enmasseModel')->listCountryJOpt('country', $setting->country, true);
		$taxList 			= JModel::getInstance('tax','enmasseModel')->listAllPublished();
		
		
		$this->assignRef( 'setting', $setting );
		$this->assignRef( 'countryJOptList', $countryJOptList);
		$this->assignRef( 'taxList', $taxList );
			
		parent::display($tpl);
	}

}
?>