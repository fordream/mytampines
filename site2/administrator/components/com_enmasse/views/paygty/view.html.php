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

class EnmasseViewPayGty extends JView
{
	function display($tpl = null)
	{

		$task = JRequest::getWord('task');
		$model = JModel::getInstance('payGty','enmasseModel');
		if($task == 'edit')
		{
			TOOLBAR_enmasse::_PAY_GTY_NEW();
			
			$cid = JRequest::getVar( 'cid', array(0), '', 'array' );
			
			$row = JModel::getInstance('payGty','enmasseModel')->getById($cid[0]);
			$this->assignRef( 'payGty', $row );
			
		}
		elseif($task == 'add')
		{
			TOOLBAR_enmasse::_PAY_GTY_NEW();
		}				
		else // Show the List
		{
			TOOLBAR_enmasse::_SMENU();
			$nNumberOfPayGtys = JModel::getInstance('payGty','enmasseModel')->countAll();
			if($nNumberOfPayGtys==0)
			{
				TOOLBAR_enmasse::_PAY_GTY_EMPTY();
			}
			else
			{
				TOOLBAR_enmasse::_PAY_GTY();
			}			

			$payGtyList = JModel::getInstance('payGty','enmasseModel')->listAll();
			$this->assignRef('payGtyList', $payGtyList );
		}
		parent::display($tpl);
	}

}
?>