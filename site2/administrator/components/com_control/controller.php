<?php
/**
 * @version		$Id: controller.php 1175 2009-06-04 00:15:16Z eddieajau $
 * @package		JXtended.Control
 * @copyright	Copyright (C) 2008 - 2009 JXtended LLC. All rights reserved.
 * @license		GNU General Public License
 */

// no direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controller');

/**
 * Component Controller
 *
 * @package		JXtended.Control
 * @subpackage	com_control
 */
class ControlController extends JController
{
	/**
	 * Constructor
	 */
	function __construct()
	{
		parent::__construct();

		$this->registerTask('save2new',	'save');
		$this->registerTask('apply',		'save');
		$this->registerTask('unpublish',	'publish');
		$this->registerTask('orderup',		'ordering');
		$this->registerTask('orderdown',	'ordering');
	}

	/**
	 * Display the view
	 */
	function display()
	{
		// Get the document object.
		$document	= &JFactory::getDocument();
		$app		= &JFactory::getApplication();

		jimport('joomla.application.component.model');
		JModel::addIncludePath(JPATH_COMPONENT.DS.'models');

		$config	= JComponentHelper::getParams('com_control');
		$extMode = $config->get('ext_mode');
		$document->addStyleSheet(JURI::base().'components/com_control/media/css/default.css');

		// Set the default view name and format from the Request
		$vName		= JRequest::getCmd('view', 'groups');
		$vFormat	= $document->getType();
		$lName		= JRequest::getCmd('layout', 'default');
		$gType		= null;

		if ($view = &$this->getView($vName, $vFormat))
		{
			switch ($vName)
			{
				case 'config':
					$model = $this->getModel('config');
					break;

				case 'sections':
					$model	= &$this->getModel('section');
					$type	= $app->getUserStateFromRequest('control.'.$vName.'.section_type',	'section_type','aco');
					$model->setState('section_type', $type);
					// Common list state
					$this->_list($vName, $model, 'a.name');
					break;

				case 'section':
					$cid	= JRequest::getVar('cid', array(0), '', 'array');
					$id		= JRequest::getVar('id', $cid[0], '', 'int');
					$type	= $app->getUserStateFromRequest('control.'.$vName.'.section_type',	'section_type','aco');
					$model	= & $this->getModel('section');
					$model->setState('id', $id);
					$model->setState('section_type', $type);
					break;

				case 'objects':
					$model	= &$this->getModel('object');
					if ($extMode == 1) {
						$type	= $app->getUserStateFromRequest('control.'.$vName.'.object_type',		'object_type',	'aco');
						$sect	= $app->getUserStateFromRequest('control.'.$vName.'.section_value',	'section_value');
					}
					else {
						$type = 'aro';
						$sect = 'users';
						$lName = 'easy';
					}
					$groupId	= $app->getUserStateFromRequest('control.'.$vName.'.group_id', 'filter_group_id');
					$model->setState('object_type', $type);
					$model->setState('section_value', $sect);
					$model->setState('group_id', $groupId);
					// Common list state
					$this->_list($vName.$type, $model, 'a.name');
					break;

				case 'object':
					$cid	= JRequest::getVar('cid', array(0), '', 'array');
					$id		= JRequest::getVar('id', $cid[0], '', 'int');
					$type	= $app->getUserStateFromRequest('control.'.$vName.'.object_type',		'object_type',	'aco');
					$sect	= $app->getUserState('control.objects.section_value');
					$model	= & $this->getModel('object');
					$model->setState('id', $id);
					$model->setState('object_type', $type);
					$model->setState('section_value', $sect);
					break;

				case 'groups':
					$model	= &$this->getModel('group');
					$gType = strtolower($app->getUserStateFromRequest('control.groups.group_type',	'group_type','aro'));
					if ($extMode == 0) {
						$lName = 'easy';
						if ($gType == 'aro') {
							$model->setState('parent_id', 28);
						}
						else if ($gType == 'axo') {
							$model->setState('parent_id', 1);
						}
					}
					$model->setState('group_type', $gType);
					$model->setState('tree', 1);
					// Common list state
					$this->_list($vName, $model, 'a.lft');
					break;

				case 'group':
					$cid	= JRequest::getVar('cid', array(0), '', 'array');
					$id		= JRequest::getVar('id', $cid[0], '', 'int');
					$type	= $app->getUserStateFromRequest('control.groups.group_type',	'group_type','aro');
					$model	= & $this->getModel('group');
					$model->setState('id', $id);
					$model->setState('group_type', $type);
					if ($extMode == 0) {
					}
					break;

				case 'acls':
					$model	= &$this->getModel('ACL');
					$sect	= $app->getUserStateFromRequest('control.'.$vName.'.section_value','section_value');
					$model->setState('section_value', $sect);
					// Common list state
					$this->_list($vName, $model, 'a.note');
					break;

				case 'acl':
					$cid	= JRequest::getVar('cid', array(0), '', 'array');
					$id		= JRequest::getVar('id', $cid[0], '', 'int');
					$model	= & $this->getModel('ACL');
					$model->setState('id', $id);
					break;

				case 'test2d':
				case 'test3d':
					$model	= &$this->getModel('test');
					$sect	= $app->getUserStateFromRequest('control.'.$vName.'.section_value','section_value');
					$sect2	= $app->getUserStateFromRequest('control.'.$vName.'.axo_section_value','axo_section_value');
					$model->setState('section_value',		$sect);
					$model->setState('axo_section_value',	$sect2);
					$this->_list($vName, $model, 'a.value');
					break;

				case 'debug':
					$model	= &$this->getModel('test');
					$model->setState('aco_section_value',	JRequest::getVar('aco_section_value'));
					$model->setState('aco_value',			JRequest::getVar('aco_value'));
					$model->setState('aro_section_value',	JRequest::getVar('aro_section_value'));
					$model->setState('aro_value',			JRequest::getVar('aro_value'));
					$model->setState('axo_section_value',	JRequest::getVar('axo_section_value'));
					$model->setState('axo_value',			JRequest::getVar('axo_value'));
					break;

				default:
					$model	= new JModel;
					break;
			}

			$model->setState('ext_mode', $extMode);

			// Push the model into the view (as default)
			$view->setModel($model, true);
			$view->setLayout($lName);

			// push document object into the view
			$view->assignRef('document', $document);

			$view->display();
		}

		if ($extMode == 1) {
			JSubMenuHelper::addEntry(JText::_('JX Link Sections'),	'index.php?option=com_control&view=sections',	$vName == 'sections');
			JSubMenuHelper::addEntry(JText::_('JX Link Objects'),	'index.php?option=com_control&view=objects',	$vName == 'objects');
			JSubMenuHelper::addEntry(JText::_('JX Link Groups'),		'index.php?option=com_control&view=groups',		$vName == 'groups');
			JSubMenuHelper::addEntry(JText::_('JX Link Rules'),		'index.php?option=com_control&view=acls',		$vName == 'acls');
			JSubMenuHelper::addEntry(JText::_('JX Link Test 2D'),	'index.php?option=com_control&view=test2d',		$vName == 'test2d');
			JSubMenuHelper::addEntry(JText::_('JX Link Test 3D'),	'index.php?option=com_control&view=test3d',		$vName == 'test3d');
			JSubMenuHelper::addEntry(JText::_('JX Link Debug'),		'index.php?option=com_control&view=debug',		$vName == 'debug');
		}
		else {
			JSubMenuHelper::addEntry(JText::_('JX Link Manage User Groups'),		'index.php?option=com_control&view=groups&group_type=aro',	($vName == 'groups' AND $gType == 'aro'));
			JSubMenuHelper::addEntry(JText::_('JX Link Assign Users to Groups'),	'index.php?option=com_control&view=objects',$vName == 'objects');
			JSubMenuHelper::addEntry(JText::_('JX Link Manage Access Levels'),	'index.php?option=com_control&view=groups&group_type=axo',	($vName == 'groups' AND $gType == 'axo'));
		}
	}

	/**
	 * Sets common list states in a model
	 * @param	string	The view name
	 * @param	iModel	The model object
	 * @param	string	The order column
	 */
	function _list($vName, &$model, $orderCol)
	{
		$app = &JFactory::getApplication();

		$limit 		= $app->getUserStateFromRequest('viewlistlimit',						'limit',		$app->getCfg('list_limit'));
		$limitstart = $app->getUserStateFromRequest('control.'.$vName.'.limitstart',	'limitstart',	0);
		$search 	= $app->getUserStateFromRequest('control.'.$vName.'.search',		'search');
		//$published 	= $app->getUserStateFromRequest('control.'.$vName.'.published', 	'published',	1);
		$orderCol	= $app->getUserStateFromRequest('control.'.$vName.'.ordercol',		'filter_order',		$orderCol);
		$orderDirn	= $app->getUserStateFromRequest('control.'.$vName.'.orderdirn',	'filter_order_Dir',	'asc');

		$model->setState('limit',		$limit);
		$model->setState('limitstart',	$limitstart);
		//$model->setState('published',	($published == '*' ? null : $published));
		$model->setState('search',		$search);
		if ($orderCol) {
			$model->setState('order by',	$orderCol.' '.($orderDirn == 'desc' ? 'desc' : 'asc'));
		}
		$model->setState('orderCol',	$orderCol);
		$model->setState('orderDirn',	$orderDirn);
	}

	/**
	 * Edit the item
	 */
	function edit()
	{
		$modelName	= JRequest::getVar('model');
		JRequest::setVar('hidemainmenu', 1);
		// The model name is the singular form, so use it for the view name
		JRequest::setVar('view', $modelName);
		JRequest::setVar('layout', 'edit');

		$this->display();
	}

	/**
	 * Checks in a record
	 */
	function cancel()
	{
		$return	= JRequest::getVar('return');
		$values	= JRequest::getVar('jxform', array(), 'post', 'array');
		$id		= @$values['id'];

		if ($modelName	= JRequest::getVar('model'))
		{
			$model	= &$this->getModel($modelName);
			//$result	= $model->checkin();
			//$table = & $model->getTable();
			//$table->checkin((int) $id);
			$err = '';
		}
		else {
			$err = 'Panic. Unknown model';
		}

		$this->setRedirect('index.php?option=com_control&view='.$return, $err);
	}

	/**
	 * Save the record
	 */
	function save()
	{
		jimport('joomla.utilities.utility');

		$token		= JUtility::getToken();
		if (JRequest::getVar($token, false, 'post') == false) {
			JError::raiseError(403, JText::_('Request Forbidden'));
		}

		$view		= JRequest::getVar('view');
		$return		= JRequest::getVar('return');
		$values		= JRequest::getVar('jxform', array(), 'post', 'array');

		if ($modelName = JRequest::getVar('model'))
		{
			$model	= &$this->getModel($modelName);
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
				$this->setRedirect('index.php?option=com_control&view='.$view.'&model='.$modelName.'&id='.$table->id.'&task=edit', JText::_($msg));
			} else {
				if ($task == 'save2new') {
					$this->setRedirect('index.php?option=com_control&view='.$view.'&model='.$modelName.'&id=0&task=edit', JText::_($msg));
				} else {
					$this->setRedirect('index.php?option=com_control&view='.$return, JText::_($msg));
				}
			}
		}
		else {
			$this->setRedirect('index.php?option=com_control&view='.$return, JText::_('Panic. No model specified'));
		}
	}

	/**
	 * Removes an  item
	 */
	function delete()
	{
		// Check for request forgeries
		JRequest::checkToken() or die('Invalid Token');

		$view		= JRequest::getWord('view');
		$cid		= JRequest::getVar('cid', null, 'post', 'array');

		if ($modelName = JRequest::getVar('model'))
		{
			$model	= & $this->getModel($modelName);
			$model->setState('request', JRequest::get('post'));
			$result	= $model->delete($cid);
			$err	= JText::sprintf('Items removed', count($cid));
		}
		else {
			$err = JText::_('Panic. No model specified');
		}

		$this->setRedirect('index.php?option=com_control&view='.$view, $err);
	}

	/**
	 * Publishes or Unpublishes one or more records
	 */
	function publish()
	{
		// Check for request forgeries
		JRequest::checkToken() or die('Invalid Token');

		$view		= JRequest::getWord('view');
		$cid		= JRequest::getVar('cid', null, 'post', 'array');
		$value		= (int) ($this->getTask() == 'publish');

		if ($modelName = JRequest::getVar('model'))
		{
			$model	= &$this->getModel($modelName);
			$result	= $model->publish($cid, $value);
			$err	= JError::isError($result) ? $result->message : '';
		}
		else {
			$err = JText::_('Panic. No model specified');
		}
		$this->setRedirect('index.php?option=com_control&view='.$view, $err);
	}

	/**
	 * Changes the order of an item
	 */
	function ordering()
	{
		// Check for request forgeries
		JRequest::checkToken() or die('Invalid Token');

		$app = &JFactory::getApplication();

		$view		= JRequest::getCmd('view');
		$cid		= JRequest::getVar('cid', null, 'post', 'array');
		$inc		= $this->getTask() == 'orderup' ? -1 : +1;
		$type		= $app->getUserStateFromRequest('control.'.$view.'.object_type',		'object_type',	'aco');

		if ($modelName = JRequest::getVar('model'))
		{
			$model = & $this->getModel($modelName);
			$model->setState('object_type', $type);
			$model->ordering($cid, $inc);
			$err = '';
		}
		else {
			$err = JText::_('Panic. No model specified');
		}
		$this->setRedirect('index.php?option=com_control&view='.$view, $err);
	}
}