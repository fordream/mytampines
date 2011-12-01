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

// No direct access 
defined( '_JEXEC' ) or die( 'Restricted access' ); 
jimport('joomla.application.component.controller');
// load language
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
class EnmasseController extends JController
{
    
    /*function display()
    {
    	JRequest::setVar('view', 'dashboard');        
    	JRequest::setVar('layout', 'default');
        parent::display($cachable);
        parent::display();
    }*/
    
    function display($cachable = false) 
	{
		// set default view if not set
		JRequest::setVar('view', JRequest::getCmd('view', 'dashboard'));

		// call parent behavior
		parent::display($cachable);
	}
}
?>