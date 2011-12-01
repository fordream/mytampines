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
jimport('joomla.application.component.controller');
JTable::addIncludePath('components'.DS.'com_enmasse'.DS.'tables');
require_once( JPATH_ADMINISTRATOR . DS ."components". DS ."com_enmasse".DS."helpers". DS ."EnmasseHelper.class.php");
/// load language
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
class EnmasseControllerSalesPerson extends JController
{

	function display()
	{
		JRequest::setVar('view', 'salesperson');
		JRequest::setVar('layout', 'show');
		parent::display();
	}
	function edit()
	{
		JRequest::setVar('view', 'salesperson');
		JRequest::setVar('layout', 'edit');
		parent::display();
	}

	function add()
	{
		JRequest::setVar('view', 'salesperson');
		JRequest::setVar('layout', 'edit');
		parent::display();
	}
	function save()
	{
		$data = JRequest::get( 'post' );
		
		$model = JModel::getInstance('salesPerson','enmasseModel');
		$salesPerson = $model->checkUserNameDup($data['user_name'], $data['id']);
		if($salesPerson != null)
		{
			$msg = JText::_('DUP_SALES_PERSON_USERNAME_MSG') ."(".$salesPerson->name.")";
			$this->setRedirect('index.php?option=com_enmasse&controller='.JRequest::getVar('controller'), $msg, 'error');
		}
		else if(! EnmasseHelper::getUserByName($data['user_name']))
		{
			$msg = JText::sprintf('SALES_PERSON_INVALID_USERNAME_MSG', $data['user_name']);
			$this->setRedirect('index.php?option=com_enmasse&task=add&controller='.JRequest::getVar('controller'), $msg, 'error');
		}
		else
		{
			if ($model->store($data))
			{
				$msg = JText::_('SAVE_SUCCESS_MSG');
				$this->setRedirect('index.php?option=com_enmasse&controller='.JRequest::getVar('controller'), $msg);
			}
			else
			{
				$msg = JText::_('SAVE_ERROR_MSG') .": " . $model->getError();
				if($data['id'] == null)
					$this->setRedirect('index.php?option=com_enmasse&controller='.JRequest::getVar('controller').'&task=add', $msg, 'error');
				else
					$this->setRedirect('index.php?option=com_enmasse&controller='.JRequest::getVar('controller').'&task=edit&cid[0]='. $data['id'], $msg, 'error');
			}
		}
		
	}
	function control()
	{
		$this->setRedirect('index.php?option=com_enmasse');
	}

	function remove()
	{
		$cids = JRequest::getVar( 'cid', array(0), 'post', 'array' );

		$model = JModel::getInstance('salesPerson','enmasseModel');
		
	   for($count=0; $count <count($cids); $count++)
		{
			$dealList = JModel::getInstance("deal","enmasseModel")->getDealBySaleId($cids[$count]);
			if(count($dealList)!=0)
			{
				$salesPerson = $model->getById($cids[$count]);
				$wanring = $salesPerson->name.' ';
				$wanring.= JText::_("SALE_IS_BEING_ASSIGNED_TO_A_DEAL");
				JFactory::getApplication()->redirect('index.php?option=com_enmasse&controller=salesPerson', $wanring , 'error');
			}
			
		}
		if($model->deleteList($cids))
		{
			$msg = JText::_('DELETE_SUCCESS_MSG');
			$this->setRedirect('index.php?option=com_enmasse&controller='.JRequest::getVar('controller'), $msg );
		}
		else
		{ 
			$msg = JText::_('DELETE_ERROR_MSG') .": " . $model->getError();
			$this->setRedirect('index.php?option=com_enmasse&controller='.JRequest::getVar('controller'), $msg, 'error');
		}
	}

	function publish()
	{
		EnmasseHelper::changePublishState(1,'enmasse_sales_person','salesPerson','salesPerson');
	}
	function unpublish()
	{
		EnmasseHelper::changePublishState(0,'enmasse_sales_person','salesPerson','salesPerson');
	}
	function checkDuplicatedName()
	{
		
		$saleName = addslashes(JRequest::getVar("saleName"));
		$saleObj = JModel::getInstance('salesperson','enmasseModel')->getSaleByName($saleName);
		if($saleObj!=null)
	    	echo 'true';
	    else
	    	echo 'false';
		exit(0);
		
	}
	function checkUserName()
	{
		$userName = JRequest::getVar("userName");
		$user = EnmasseHelper::getUserByName($userName);
		$userByUserName = JModel::getInstance('salesperson','enmasseModel')->getSaleByUserName($userName);
		if(!empty($user))
		{
			if(!empty($userByUserName))
			 	echo 'duplicated';
			else
		 		echo 'valid';
		}
		else
		{
			echo 'invalid';
		}
		exit(0);
	}
}
?>