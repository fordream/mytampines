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

class EnmasseViewUploader extends JView
{
	function display($tpl = null)
	{
		JToolBarHelper::title( JText::_( 'En Masse Uploader'),
                                           'generic.png' );
		$filePath = JRequest::getVar('folder');
        //
        //echo $filePath;
		$parentId = JRequest::getVar('parentId');
		$parent = JRequest::getVar('parent');
		$couponBg = JRequest::getVar('couponbg');
		
		$this->assignRef('couponbg', $couponBg );
		$this->assignRef('parentId', $parentId );
		$this->assignRef('parent', $parent );
		if(!empty($filePath))
		{
			$this->assignRef('imageUrl', $filePath );
		}
		
		parent::display($tpl);
	}

}
?>