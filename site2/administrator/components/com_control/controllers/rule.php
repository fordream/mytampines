<?php
/**
 * @version		$Id: rule.php 1163 2009-05-22 23:15:43Z eddieajau $
 * @copyright	Copyright (C) 2008 - 2009 JXtended LLC. All rights reserved.
 * @license		GNU General Public License
 */

jimport('joomla.application.component.controller');

/**
 * @package		JXtended.Control
 * @subpackage	com_control
 */
class ControlControllerRule extends JController
{
	/**
	 * @var	string|array
	 */
	var $_section	= null;

	/**
	 * @var	string
	 */
	var $_option	= null;

	/**
	 * Constructor
	 */
	function __construct($config = array())
	{
		$config['view_path']	= dirname(dirname(__FILE__)).DS.'views';
		parent::__construct($config);

		$this->registerTask('edit_type1',	'edit');
		$this->registerTask('edit_type2',	'edit');
		$this->registerTask('edit_type3',	'edit');
		$this->registerTask('save2copy',	'save');
		$this->registerTask('save2new',		'save');
		$this->registerTask('save2new2',	'save');
		$this->registerTask('apply',		'save');
		$this->registerTask('deny',			'allow');
		$this->registerTask('disable',		'enable');
		$this->registerTask('trash',		'publish');
		$this->registerTask('orderup',		'ordering');
		$this->registerTask('orderdown',	'ordering');

		JTable::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_control'.DS.'tables');
		JModel::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_control'.DS.'models');

		$this->_option	= JRequest::getCmd('option');
		if ($this->_section === null) {
			$this->_section	= JRequest::getVar('section', $this->_option);
		}
	}

	/**
	 * Display the view
	 */
	function display()
	{
		global $mainframe;

		$basePath	= dirname(dirname(__FILE__));
		JHTML::stylesheet('default.css', 'administrator/components/com_control/media/css/');
		JHTML::addIncludePath($basePath.DS.'helpers'.DS.'html');

		// Set the default view name from the Request
		$vName		= JRequest::getWord('view', 'rules');
		$vFormat	= JRequest::getWord('format', 'html');
		$lName		= JRequest::getWord('layout', 'default');
		$config		= array(
			'template_path'	=> $basePath.DS.'views'.DS.$vName.DS.'tmpl'
		);

		if ($view = &$this->getView($vName, $vFormat, 'ControlView', $config))
		{
			$model	= $this->getModel();
			$model->setState('section_value',	$this->_section);
			$model->setState('object_type',		'acl');
			$model->setState('option',			$this->_option);
			$this->_list($vName, $model, 'a.note');

			// Push the model into the view (as default)
			$view->setModel($model, true);
			$view->setLayout($lName);

			$view->display();
		}

		$this->setSubmenu($vName);
	}

	/**
	 * Sets common list states in a model
	 * @param	string	The view name
	 * @param	JModel	The model object
	 * @param	string	The order column
	 */
	function _list($vName, &$model, $orderCol)
	{
		global $mainframe;

		$limit 		= $mainframe->getUserStateFromRequest('global.list.limit',							'limit',		$mainframe->getCfg('list_limit'));
		$limitstart = $mainframe->getUserStateFromRequest($this->_option.'.'.$vName.'.limitstart',	'limitstart',	0);
		$search 	= $mainframe->getUserStateFromRequest($this->_option.'.'.$vName.'.search',		'search');
		$published 	= $mainframe->getUserStateFromRequest($this->_option.'.'.$vName.'.published', 	'published',	1);
		$orderCol	= $mainframe->getUserStateFromRequest($this->_option.'.'.$vName.'.ordercol',	'filter_order',		$orderCol);
		$orderDirn	= $mainframe->getUserStateFromRequest($this->_option.'.'.$vName.'.orderdirn',	'filter_order_Dir',	'asc');

		$model->setState('limit',		$limit);
		$model->setState('limitstart',	$limitstart);
		$model->setState('published',	($published == '*' ? null : $published));
		$model->setState('search',		$search);
		if ($orderCol) {
			$model->setState('order by',	$orderCol.' '.($orderDirn == 'asc' ? 'asc' : 'desc'));
		}
		$model->setState('orderCol',	$orderCol);
		$model->setState('orderDirn',	$orderDirn);
	}

	/**
	 * setSubmenu override
	 *
	 * @param	string	The name of the active view
	 */
	function setSubmenu($vName)
	{
	}

	/**
	 * Callback to see if AXO groups are supported
	 * @deprecated
	 */
	function hasAxoGroups()
	{
		return false;
	}

	/**
	 * getModel override
	 */
	function &getModel()
	{
		$result	= &parent::getModel('ACL', 'ControlModel');
		return $result;
	}

	/**
	 * User by derived class to sync AXO's
	 */
	function synchronize()
	{
	}

	/**
	 * Edit the item
	 */
	function edit()
	{
		$basePath	= dirname(dirname(__FILE__));
		JHTML::stylesheet('default.css', 'administrator/components/com_control/media/css/');
		JHTML::addIncludePath($basePath.DS.'helpers'.DS.'html');

		$model	= $this->getModel();

		$cid	= JRequest::getVar('cid', array(0), '', 'array');
		$id		= JRequest::getVar('id', $cid[0], '', 'int');
		$task	= $this->getTask();
		$model->setState('id',				$id);
		$model->setState('section_value',	$this->_section);
		$model->setState('object_type',	'acl');
		$model->setState('option',			$this->_option);
		if ($task == 'edit_type3') {
			$model->setState('acl_type',	3);
		}
		else if ($task == 'edit_type2') {
			$model->setState('acl_type',	2);
		}
		else {
			$model->setState('acl_type',	1);
		}


		// Synronise the AXO's
		$this->synchronize($model);

		JRequest::setVar('hidemainmenu', 1);
		// The model name is the singular form, so use it for the view name
		$vFormat	= JRequest::getWord('format', 'html');
		$lName		= JRequest::getWord('layout', 'edit');

		$config		= array(
			'template_path'	=> $basePath.DS.'views'.DS.'rule'.DS.'tmpl'
		);

		$view = &$this->getView('rule', $vFormat, 'ControlView', $config);
		// Push the model into the view (as default)
		$view->setModel($model, true);
		$view->setLayout($lName);
		$view->display();
	}

	/**
	 * Checks in a record
	 */
	function cancel()
	{
		// Check for request forgeries
		JRequest::checkToken() or die('Invalid Token');

		$this->setRedirect('index.php?option='.$this->_option.'&task=rule.display');
	}

	/**
	 * Save the record
	 */
	function save()
	{
		// Check for request forgeries
		JRequest::checkToken() or die('Invalid Token');

		jimport('joomla.utilities.utility');

		$model	= $this->getModel();
		$values	= JRequest::getVar('jxform', array(), 'post', 'array');

		$model->setState('request', JRequest::get('post'));
		$form	= &$model->getForm('model');
		$form->filter($values);
		$result	= $form->validate($values);

		if (JError::isError($result))
		{
			// TODO: Load the <onerror> process instruction
			JError::raiseError(500, $result->message);
		}
		$result	= $model->save($values);
		$msg	= JError::isError($result) ? $result->message : 'Saved';
		$table	= &$model->getResource();
		$task	= $this->getTask();

		if ($task == 'apply') {
			$this->setRedirect('index.php?option='.$this->_option.'&task=rule.edit&id='.$table->id, JText::_($msg));
		}
		else {
			if ($task == 'save2new') {
				$this->setRedirect('index.php?option='.$this->_option.'&task=rule.edit&id=0', JText::_($msg));
			}
			else if ($task == 'save2new2') {
				$this->setRedirect('index.php?option='.$this->_option.'&task=rule.edit2&id=0', JText::_($msg));
			}
			else {
				$this->setRedirect('index.php?option='.$this->_option.'&task=rule.display', JText::_($msg));
			}
		}
	}

	/**
	 * Removes an  item
	 */
	function delete()
	{
		// Check for request forgeries
		JRequest::checkToken() or die('Invalid Token');

		// Set the redirection
		$this->setRedirect($_SERVER['HTTP_REFERER']);

		$model	= $this->getModel();
		$cid		= JRequest::getVar('cid', null, 'post', 'array');

		$result	= $model->delete($cid);
		$err	= JText::sprintf('Items removed', count($cid));
		$this->setMessage(JError::isError($result) ? $result->getMessage() : JText::sprintf('Items removed', count($cid)));
	}

	/**
	 * Sets the allow field value on an ACL
	 */
	function allow()
	{
		// Check for request forgeries
		JRequest::checkToken() or die('Invalid Token');

		// Set the redirection
		$this->setRedirect($_SERVER['HTTP_REFERER']);

		$values		= array('allow' => 1, 'deny' => 0);
		$cid		= JRequest::getVar('cid', null, 'post', 'array');
		$task		= $this->getTask();
		$value		= JArrayHelper::getValue($values, $task, 0, 'int');

		$model	= $this->getModel();
		$result	= $model->allow($cid, $value);
		$this->setMessage(JError::isError($result) ? $result->getMessage() : '');
	}

	/**
	 * Sets the enable field value on an ACL
	 */
	function enable()
	{
		// Check for request forgeries
		JRequest::checkToken() or die('Invalid Token');

		// Set the redirection
		$this->setRedirect($_SERVER['HTTP_REFERER']);

		$values		= array('enable' => 1, 'disable' => 0);
		$cid		= JRequest::getVar('cid', null, 'post', 'array');
		$task		= $this->getTask();
		$value		= JArrayHelper::getValue($values, $task, 0, 'int');

		$model	= $this->getModel();
		$result	= $model->enable($cid, $value);
		$this->setMessage(JError::isError($result) ? $result->getMessage() : '');
	}
}