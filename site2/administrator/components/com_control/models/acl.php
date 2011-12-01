<?php
/**
 * @version		$Id: acl.php 1163 2009-05-22 23:15:43Z eddieajau $
 * @copyright	Copyright (C) 2008 - 2009 JXtended LLC. All rights reserved.
 * @license		GNU General Public License
 */

// no direct access
defined('_JEXEC') or die;

jximport('jxtended.application.component.model');
jximport('jxtended.database.query');

if (!defined('CONTROL_USERS_ARO_ID')) {
	define('CONTROL_USERS_ARO_ID', 28);
}

/**
 * @package		JXtended.Control
 * @subpackage	com_control
 */
class ControlModelACL extends JXModel
{
	/**
	 * @return	JTable
	 */
	function &getResource()
	{
		static $first = true;

		if ($first)
		{
			// Bit of a shortcut to load the table class
			$table	= JTable::getInstance('ACL', 'AclTable');
			$this->setResource($table);
			$first	= false;
		}
		$result = &parent::getResource();
		return $result;
	}

	/**
	 * @return	object
	 */
	function &getItem()
	{
		static $instance;

		if (!$instance)
		{
			$state	= &$this->getState();
			$id		= (int) $state->get('id');

			$filters = array(
				'where'			=> 'a.id='.(int) $id,
				'section_value'	=> $state->get('section_value')
			);
			$temp = $this->getList($filters, true);

			if (isset($temp[0])) {
				$instance	= $temp[0];
			}
			else {
				$instance->id = 0;
			}
			$instance->type	= 'acl';
		}
		return $instance;
	}

	/**
	 * @return	array	List of items
	 */
	function &getItems()
	{
		static $instance;

		if (!$instance)
		{
			$state			= &$this->getState();
			$filters		= JArrayHelper::fromObject($state);
			$this->_total	= $this->getListCount($filters, true);
			$instance		= $this->getList($filters, true);

			// first pass, get the id's
			$n		= count($instance);
			$aclIds	= array();
			$rlu	= array();
			for ($i = 0; $i < $n; $i++)
			{
				$aclIds[]				= $instance[$i]->id;
				$rlu[$instance[$i]->id]	= $i;
			}

			$db		= &$this->getDBO();
			$acls	= array();

			// run sql to get ACO's, ARO's and AXO's
			if (!empty($aclIds))
			{
				$ids = implode(',', $aclIds);
				foreach (array('aco', 'aro', 'axo') as $type)
				{
					$query = 'SELECT	a.acl_id,o.name,s.name AS section_name' .
							' FROM	#__core_acl_'. $type .'_map a' .
							' INNER JOIN #__core_acl_'. $type .' o ON (o.section_value=a.section_value AND o.value=a.value)' .
							' INNER JOIN #__core_acl_'. $type . '_sections s ON s.value=a.section_value' .
							' WHERE	a.acl_id IN ('. $ids . ')';
					$db->setQuery($query);
					$temp	= $db->loadObjectList();
					foreach ($temp as $item)
					{
						$i	= $rlu[$item->acl_id];
						$k	= $type.'s';

						if (!isset($instance[$i]->$k)) {
							$instance[$i]->$k = array();
						}
						$r = &$instance[$i]->$k;
						$r[$item->section_name][] = $item->name;
					}
				}

				// grab ARO and AXO groups
				foreach (array('aro', 'axo') as $type)
				{
					$query = 'SELECT a.acl_id,g.name' .
							' FROM #__core_acl_'. $type .'_groups_map a' .
							' INNER JOIN #__core_acl_'. $type .'_groups g ON g.id=a.group_id' .
							' WHERE	a.acl_id IN ('. $ids . ')';
					$db->setQuery($query);
					$temp	= $db->loadObjectList();
					foreach ($temp as $item)
					{
						$i	= $rlu[$item->acl_id];
						$k	= $type.'Groups';
						if (!isset($instance[$i]->$k)) {
							$instance[$i]->$type = array();
						}
						$r = &$instance[$i]->$k;
						$r[] = $item->name;
					}
				}
			}
		}
		return $instance;
	}

	/**
	 * Gets the Form
	 */
	function &getForm($type = 'view')
	{
		jximport('jxtended.form.helper');
		JXFormHelper::addIncludePath(dirname(__FILE__));

		if ($type == 'model') {
			$result = &JXFormHelper::getModel('acl');
		} else {
			$result = &JXFormHelper::getView('acl');
		}
		if (JError::isError($result)) {
			echo $result->message;
		}
		return $result;
	}

	/**
	 * Gets a list of categories objects
	 *
	 * Filters may be fields|published|order by|searchName|where
	 * @param array Named array of field-value filters
	 * @param boolean True if foreign keys are to be resolved
	 */
	function _getListQuery($filters, $resolveFKs=false)
	{
		$section		= @$filters['section_value'];
		// arbitrary where
		$select			= @$filters['select'];
		$search			= @$filters['search'];
		$where			= @$filters['where'];
		$orderBy		= @$filters['order by'];

		$db	= $this->getDBO();
		$query	= new JXQuery;

		$query->select($select !== null ? $select : 'a.*');
		$query->from('#__core_acl_acl AS a');

		if ($resolveFKs)
		{
			//$query->select('co.name AS editor');
			//$query->join('LEFT', '#__users AS co ON co.id=a.checked_out');
		}

		// options
		if ($section)
		{
			if (is_array($section))
			{
				foreach ($section as $k => $v) {
					$section[$k] = $db->Quote($v);
				}
				$query->where('a.section_value IN ('.implode(',', $section).')');
			}
			else {
				$query->where('a.section_value = '.$db->Quote($section));
			}
		}

		if ($search) {
			$serach = $db->Quote('%'.$db->getEscaped($search, true).'%', false);
			$query->where('a.note LIKE '.$serach);
		}

		if ($where) {
			$query->where($where);
		}

		if ($orderBy) {
			$query->order($this->_db->getEscaped($orderBy));
		}

		//echo nl2br($query->toString());
		return $query;
	}

	function getSections()
	{
		JModel::addIncludePath(JPATH_COMPONENT.DS.'models');

		$model					= JModel::getInstance('Section', 'ControlModel');
		$vars['select']			= 'a.value, a.name AS text';
		$vars['section_type']	= 'acl';
		$vars['order by']		= 'a.order_value,a.name';
		$options				= $model->getList($vars);
		return $options;
	}

	function getACOs()
	{
		JModel::addIncludePath(JPATH_COMPONENT.DS.'models');
		$model					= JModel::getInstance('object', 'ControlModel');

		$vars['section_value']	= $this->getState('section_value');
		$vars['object_type']	= 'aco';
		$vars['hidden']			= '0';
		$vars['order by']		= 'a.section_value,a.order_value,a.name';
		$result					= $model->getList($vars, true);
		return $result;
	}

	function getAROGroups()
	{
		JModel::addIncludePath(JPATH_COMPONENT.DS.'models');
		$model					= JModel::getInstance('Group', 'ControlModel');

		$vars['group_type']		= 'aro';
		$vars['tree']			= '1';
		$vars['parent_id']		= CONTROL_USERS_ARO_ID;
		$vars['order by']		= 'a.lft';
		$result					= $model->getList($vars, true);
		return $result;
	}

	function getAXOs()
	{
		JModel::addIncludePath(JPATH_COMPONENT.DS.'models');
		$model					= JModel::getInstance('object', 'ControlModel');

		$vars['section_value']	= $this->getState('section_value');
		$vars['object_type']	= 'axo';
		$vars['hidden']			= '0';
		$vars['order by']		= 'a.order_value,a.name';
		$result					= $model->getList($vars, true);
		return $result;
	}

	function getAXOGroups()
	{
		JModel::addIncludePath(JPATH_COMPONENT.DS.'models');
		$model					= JModel::getInstance('Group', 'ControlModel');

		$vars['group_type']		= 'axo';
		$vars['tree']			= '1';
		$vars['order by']		= 'a.lft';
		$result					= $model->getList($vars, true);
		return $result;
	}

	function getACL()
	{
		$acl	= &JFactory::getACL();
		$id		= (int) $this->getState('id');
		$result	= $acl->get_acl($id);
		return $result;
	}

	function save($values)
	{
		$request	= $this->getState('request');
		$form		= JArrayHelper::getValue($request, 'jxform');

		$acl		= &JFactory::getACL();

		$aco_array		= JArrayHelper::getValue($request, 'aco_array', array(), 'array');
		$aro_array		= JArrayHelper::getValue($request, 'aro_array', array(), 'array');
		$aro_group_ids	= JArrayHelper::getValue($request, 'aro_group_ids', array(), 'array');
		$axo_array		= JArrayHelper::getValue($request, 'axo_array', array(), 'array');
		$axo_group_ids	= JArrayHelper::getValue($request, 'axo_group_ids', array(), 'array');

		$allow			= JArrayHelper::getValue($values, 'allow', 1, 'int');
		$enabled		= JArrayHelper::getValue($values, 'enabled', 1, 'int');
		$return_value	= JArrayHelper::getValue($values, 'return_value');
		$note			= JArrayHelper::getValue($values, 'note');
		$section_value	= JArrayHelper::getValue($values, 'section_value');
		$acl_id			= JArrayHelper::getValue($values, 'id', 0, 'int');

		//echo "<br>allow=$allow, enabled=$enabled, rvalue=$return_value, note=$note, svalue=$section_value, acl_id=$acl_id";
		//die;

//print_r($aco_array);
//print_r($aro_group_ids);
//print_r($axo_array);

		//$acl->_debug = 1;
		$result = $acl->add_acl($aco_array, $aro_array, $aro_group_ids, $axo_array, $axo_group_ids, $allow, $enabled, $return_value, $note, $section_value, $acl_id);
		//die;

		if (!$result) {
			//$result = JError::raiseNotice(500, 'Failed to save ACL');
			$result = JError::raiseWarning(500, array_pop($acl->_debugLog));
		}
		else
		{
			$table		= &$this->getResource();
			// This is a fudge to add the acl_type field because phpgacl doesn't support it
			$table->load($result);
			$table->acl_type = (int) $values['acl_type'];
			$table->store();
		}
		return $result;
	}

	function delete($ids = array())
	{
		$acl		= &JFactory::getACL();
		foreach ((array) $ids as $id)
		{
			$result		= $acl->del_acl($id);
			$acl->_debug = 1;
			if ($result == false) {
				JError::raiseWarning(500, array_pop($acl->_debugLog));
				break;
			}
		}
		return $result;
	}

	function allow($ids = array(), $value = 1)
	{
		if (empty($ids)) {
			return JException('No items selected');
		}
		else
		{
			$acl	= &JFactory::getACL();
			$db		= $this->getDBO();
			JArrayHelper::toInteger($ids);

			$query	= 'UPDATE #__core_acl_acl' .
					' SET allow = '.(int)($value ? 1 : 0) .
					' WHERE id IN ('.implode(',', $ids).')';
			$db->setQuery($query);
			if (!$db->query()) {
				return new JExecption($db->getErrorMsg());
			}
			return true;
		}
	}

	function enable($ids = array(), $value = 1)
	{
		if (empty($ids)) {
			return JException('No items selected');
		}
		else
		{
			$acl	= &JFactory::getACL();
			$db		= $this->getDBO();
			JArrayHelper::toInteger($ids);

			$query	= 'UPDATE #__core_acl_acl' .
					' SET enabled = '.(int)($value ? 1 : 0) .
					' WHERE id IN ('.implode(',', $ids).')';
			$db->setQuery($query);
			if (!$db->query()) {
				return new JExecption($db->getErrorMsg());
			}
			return true;
		}
	}
}