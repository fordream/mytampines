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
class EnmasseControllerPayGty extends JController
{
	function display()
	{
		JRequest::setVar('view', 'payGty');
		JRequest::setVar('layout', 'show');
		parent::display();
	}
	function edit()
	{
		JRequest::setVar('view', 'payGty');
		JRequest::setVar('layout', 'edit');
		parent::display();
	}
	function config()
	{
		JRequest::setVar('view', 'payGty');
		JRequest::setVar('layout', 'edit_config');
		parent::display();
	}


	function add()
	{
		JRequest::setVar('view', 'payGty');
		JRequest::setVar('layout', 'add');
		parent::display();
	}

	function save()
	{
		$data = JRequest::get( 'post' );
		$model = JModel::getInstance('payGty','enmasseModel');
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
	
	function control()
	{
		$this->setRedirect('index.php?option=com_enmasse');
	}

	function publish()
	{
		EnmasseHelper::changePublishState(1,'enmasse_pay_gty','payGty','payGty');
	}
	function unpublish()
	{
		EnmasseHelper::changePublishState(0,'enmasse_pay_gty','payGty','payGty');
	}
	function remove()
	{
		$data = JRequest::get('post');
		$model = JModel::getInstance('payGty','enmasseModel');
		if($model->delete())
		{
			$msg = JText::_('DELETE_SUCCESS_MSG');
			$this->setRedirect('index.php?option=com_enmasse&controller='.JRequest::getVar('controller'), $msg);
		}
		else
		{
			$msg = JText::_('DELETE_ERROR_MSG') .": " . $model->getError();
			$this->setRedirect('index.php?option=com_enmasse&controller='.JRequest::getVar('controller'). $msg);
		}
	}
}
?>