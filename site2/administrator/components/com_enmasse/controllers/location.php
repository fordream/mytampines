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
class EnmasseControllerLocation extends JController
{

	function display()
	{
		JRequest::setVar('view', 'location');
		JRequest::setVar('layout', 'show');
		parent::display();
	}
	function edit()
	{
		JRequest::setVar('view', 'location');
		JRequest::setVar('layout', 'edit');
		parent::display();
	}
	function control()
	{
		$this->setRedirect('index.php?option=com_enmasse');
	}
	function add()
	{
		JRequest::setVar('view', 'location');
		JRequest::setVar('layout', 'edit');
		parent::display();
	}
	function save()
	{
		$data = JRequest::get( 'post' );

		$model = JModel::getInstance('location','enmasseModel');
		$row = $model->store($data);
		
		//------------------------
		//gemerate integration class
		 $integrateFileName = EnmasseHelper::getSubscriptionClassFromSetting().'.class.php';
		 $integrationClass = EnmasseHelper::getSubscriptionClassFromSetting();
		 require_once (JPATH_SITE . DS ."components". DS ."com_enmasse". DS ."helpers". DS ."subscription". DS .$integrationClass. DS.$integrateFileName);
		 $integrationObject = new $integrationClass();
		 
		if ($row->success)
		{
			$integrationObject->integration($row,'location');
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

	function remove()
	{
		$cids = JRequest::getVar( 'cid', array(0), 'post', 'array' );

		$model = JModel::getInstance('location','enmasseModel');
		$msg = "";
		for($count=0; $count <count($cids); $count++)
		{			
			$dealList = JModel::getInstance("dealLocation","enmasseModel")->getDealByLocationId($cids[$count]);
			if(count($dealList)!=0)
			{
				$location = $model->getById($cids[$count]);
				$msg .= $location->name.' ';
				$msg .= JText::_("LOCATION_IS_BEING_ASSIGNED_TO_A_DEAL") . "<br />";
				unset($cids[$count]);
			}
		}
		
		//------------------------
		//gemerate integration class
		 $integrateFileName = EnmasseHelper::getSubscriptionClassFromSetting().'.class.php';
		 $integrationClass = EnmasseHelper::getSubscriptionClassFromSetting();
		 require_once (JPATH_SITE . DS ."components". DS ."com_enmasse". DS ."helpers". DS ."subscription". DS .$integrationClass. DS.$integrateFileName);
		 $integrationObject = new $integrationClass();

		if($model->deleteList($cids))
		{
			// to remove location from deal
			/*remove at 18/05/2011
			 * for($i=0; $i < count($cids); $i++)
			{
				$integrationObject->integration($cids[$i],'removeLocation');
				JModel::getInstance('dealLocation','enmasseModel')->removeByLocation($cids[$i]);
			}*/
			
			// Commented on July 27, 2011
			//$msg = JText::_('DELETE_SUCCESS_MSG');
			//$this->setRedirect('index.php?option=com_enmasse&controller='.JRequest::getVar('controller'), $msg );
		}
		else
		{ 
			$msg .= JText::_('DELETE_ERROR_MSG') .": " . $model->getError();
			$this->setRedirect('index.php?option=com_enmasse&controller='.JRequest::getVar('controller'), $msg, 'error');
		}
		JFactory::getApplication()->redirect('index.php?option=com_enmasse&controller=location', $msg , 'error');
	}



	function publish()
	{
		EnmasseHelper::changePublishState(1,'enmasse_location','location','location');
	}
	function unpublish()
	{
		EnmasseHelper::changePublishState(0,'enmasse_location','location','location');
	}
	function checkDuplicatedLocation()
	{
		
		$locationName = addslashes(JRequest::getVar("locationName"));
		$locationObj = JModel::getInstance('location','enmasseModel')->getLocationByName($locationName);
		if($locationObj!=null)
	    	echo 'true';
	    else
	    	echo 'false';
		exit(0);
		
	}
	
	function updateAcyList()
	{
		$locationList = JModel::getInstance('location','enmasseModel')->search();
		
		//------------------------
		//gemerate integration class
		 $integrateFileName = EnmasseHelper::getSubscriptionClassFromSetting().'.class.php';
		 $integrationClass = EnmasseHelper::getSubscriptionClassFromSetting();
		 require_once (JPATH_SITE . DS ."components". DS ."com_enmasse". DS ."helpers". DS ."subscription". DS .$integrationClass. DS.$integrateFileName);
		 $integrationObject = new $integrationClass();
		 foreach ($locationList as $item)
		 {
		 	$item->oldname = $item->name;
		 	$integrationObject->insertEnmasseLocation($item);
		 }
		 $this->setRedirect('index.php?option=com_enmasse&controller='.JRequest::getVar('controller'));
		 
	}
}
?>