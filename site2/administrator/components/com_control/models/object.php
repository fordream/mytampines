<?php
/**
 * @version		$Id: object.php 1163 2009-05-22 23:15:43Z eddieajau $
 * @copyright	Copyright (C) 2008 - 2009 JXtended LLC. All rights reserved.
 * @license		GNU General Public License
 */

// no direct access
defined('_JEXEC') or die;

jximport('jxtended.application.component.model');
jximport('jxtended.database.query');

/**
 * @package		JXtended.Control
 * @subpackage	com_control
 */
class ControlModelObject extends JXModel
{
	/**
	 * Valid types
	 */
	function isValidType($type)
	{
		$types	= array('acl', 'aro', 'aco', 'axo');
		return in_array($type, $types);
	}

	/**
	 * @return	JTable
	 */
	function &getResource()
	{
		static $first = true;

		$type = $this->getState('object_type');
		if (!$this->isValidType($type)) {
			return JError::raiseError(500, $type.' is not a valid object type');
		}

		if ($first)
		{
			// Bit of a shortcut to load the table class
			if ($type == 'aco') {
				JTable::getInstance('ACO', 'AclTable');
				$table	= new AclTableACO($this->getDBO(), $type);
			}
			else {
				JTable::getInstance('Object', 'AclTable');
				$table	= new AclTableObject($this->getDBO(), $type);
			}

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
				'object_type'	=> $state->get('object_type'),
				'where'			=> 'a.id='.(int)$id
			);
			$temp		= $this->getList($filters, true);
			$instance	= @$temp[0];
			$instance->type	= $this->getState('object_type');

			if ($id == 0)
			{
				// new item
				$instance->section_value	= $this->getState('section_value');
				$instance->value			= 0;
				$instance->id				= 0;
			}
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
		}
		return $instance;
	}

	/**
	 * Gets the Form
	 */
	function &getForm($type = 'view')
	{
		jximport('jxtended.form.helper');
		JXFormHelper::addIncludePath(JPATH_COMPONENT.DS.'models');
		$otype	= $this->getState('object_type');

		if ($type == 'model') {
			$result = &JXFormHelper::getModel($otype == 'aco' ? 'aco' : 'object');
		} else {
			$result = &JXFormHelper::getView($otype == 'aco' ? 'aco' : 'object');
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
		$type			= strtolower(@$filters['object_type']);
		$groupId		= @$filters['group_id'];
		// arbitrary where
		$select			= @$filters['select'];
		$search			= @$filters['search'];
		$where			= @$filters['where'];
		$orderBy		= @$filters['order by'];

		$db	= &$this->getDBO();
		$query = new JXQuery;

		$query->select($select !== null ? $select : 'a.*');
		$query->from('#__core_acl_'.$type.' AS a');

		if ($resolveFKs)
		{
			if ($type == 'aro') {
				$query->select('u.username');
				$query->join('LEFT', '#__users AS u ON u.id = a.value');
			}

			$query->select('s.name AS section_name');
			$query->join('LEFT', '#__core_acl_'.$type.'_sections AS s ON s.value = a.section_value');

			if ($type == 'aro' OR $type == 'axo') {
				$query->select('COUNT(map.group_id) AS group_count');
				$query->join('LEFT', '#__core_acl_groups_'.$type.'_map AS map ON map.'.$type.'_id=a.id');
				$query->group('a.id');

				$query->select('GROUP_CONCAT(g2.name SEPARATOR '.$db->Quote("\n").') AS group_names');
				$query->join('LEFT', '#__core_acl_'.$type.'_groups AS g2 ON g2.id = map.group_id');
			}
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
			if (strpos($search, 'id:') === 0) {
				$query->where('a.id = '.(int) substr($search, 3));
			}
			else if (strpos($search, 'value:') === 0) {
				$query->where('a.value = '.$db->Quote(substr($search, 6)));
			}
			else {
				$search = $db->Quote('%'.$db->getEscaped($search, true).'%', false);
				if ($type == 'aro') {
					$query->where('(a.name LIKE '.$search.' OR u.username LIKE '.$search.')');
				}
				else {
					$query->where('a.name LIKE '.$search);
				}
			}
		}

		if ($groupId) {
			if ($type == 'aro' OR $type == 'axo') {
				$query->join('LEFT', '#__core_acl_groups_'.$type.'_map AS map2 ON map2.'.$type.'_id=a.id');
				$query->where('map2.group_id = '.(int) $groupId);
			}
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

	/**
	 * @param array		An array of primary keys
	 * @param int		Increment, usually +1 or -1
	 * @return boolean
	 */
	function ordering($input, $inc=0)
	{
		global $mainframe;

		$database		= &$this->getDBO();
		$user			= &JFactory::getUser();
		$table			= $this->getResource();

		JArrayHelper::toInteger($input);

		if (count($input))
		{
			$cids = 'id=' . implode(' OR id=', $input);

			$query = 'UPDATE ' . $table->getTableName()
			. ' SET order_value = order_value + ' . (int) $inc
			. ' WHERE (' . $cids . ')'
			;
			$database->setQuery($query);
			if (!$database->query())
			{
				$this->setError($database->getErrorMsg());
			}
			else
			{
				return true;
			}
		}
	}



	function getSections()
	{
		JModel::addIncludePath(JPATH_COMPONENT.DS.'models');

		$model					= JModel::getInstance('Section', 'ControlModel');
		$vars['select']			= 'a.value, a.name AS text';
		$vars['section_type']	= $this->getState('object_type');
		$vars['order by']		= 'a.order_value,a.name';
		$options				= $model->getList($vars);
		return $options;
	}

	function save($values)
	{
		$request	= $this->getState('request');
		$type		= strtolower(JArrayHelper::getValue($request, 'object_type'));
		$groupIds	= JArrayHelper::getValue($request, 'group_ids',	array(), 'array');
		$this->setState('object_type', $type);
		$result	= parent::save($values);

		if (JError::isError($result)) {
			return $result;
		}

		$table	= &$this->getResource();

		// save the group assignments
		if ($type == 'aro' or $type == 'axo')
		{
			$acl		= &JFactory::getACL();
			$xGroups	= $acl->get_object_groups($table->id, $type);

			// delete from the existing groups
			foreach ($xGroups as $gid) {
				if (!$acl->del_group_object($gid, $table->section_value, $table->value, $type)) {
					return new JException(array_pop($acl->_debugLog));
				}
			}

			// put into the new groups
			foreach ($groupIds as $gid) {
				if (!$acl->add_group_object($gid, $table->section_value, $table->value, $type)) {
					return new JException(array_pop($acl->_debugLog));
				}
			}
		}

		return $result;
	}

	function delete($ids = array(), $type = null, $erase = false)
	{
		$request	= $this->getState('request');
		$type		= JArrayHelper::getValue($request, 'object_type', $type);
		$acl		= &JFactory::getACL();
		//$acl->_debug = 1;

		foreach ((array) $ids as $id)
		{
			$result		= $acl->del_object($id, $type, $erase);
			if ($result == false) {
				JError::raiseWarning(500, array_pop($acl->_debugLog));
				break;
			}
			//die;
		}
		return $result;
	}

	/**
	 * Gets the group list for the appropriate object type
	 *
	 * @return	array
	 */
	function getGroupList()
	{
		$result	= null;
		$type	= $this->getState('object_type');

		if ($type == 'aro' or $type == 'axo')
		{
			JModel::addIncludePath(JPATH_COMPONENT.DS.'models');
			$model					= JModel::getInstance('Group', 'ControlModel');

			$vars['group_type']		= $type;
			$vars['tree']			= '1';
			$vars['parent_id']		= '28';
			$vars['order by']		= 'a.lft';
			$result					= $model->getList($vars, true);
		}

		return $result;
	}

	/**
	 * Gets the groups this object is assigned to
	 */
	function getGroups()
	{
		$acl	= &JFactory::getACL();

		$id		= (int) $this->getState('id');
		$type	= $this->getState('object_type');
		$option	= 'NO_RECURSE';

		$result	= $acl->get_object_groups($id, $type, $option);

		return $result;
	}

	/**
	 * Batch update objects
	 */
	function batch($vars, $ids, $type)
	{
		// @todo put in failsafe for deleting the primary user group
		//$vars	= $this->getState('vars');
		//$ids	= $this->getState('ids');
		$db		= &$this->getDBO();
		$result	= true;

		JArrayHelper::toInteger($ids);

		if ($vars && $ids)
		{
			$groupId	= JArrayHelper::getValue($vars, 'group_id', 0, 'int');
			$groupLogic	= JArrayHelper::getValue($vars, 'group_logic');
			$type		= strtolower($type);

			if ($type != 'aro' AND $type != 'axo') {
				$result = new JException(JText::_('JX Invalid Object Type'));
				return $result;
			}

			if ($groupId == 0) {
				$result = new JException(JText::_('JX Invalid Group'));
				return $result;
			}

			$acl = &JFactory::getACL();
			$oldMode = $acl->setCheckMode(1);

			// @todo Really needs a batch API method in the ACL class (note to self for 1.6)
			switch ($groupLogic)
			{
				case 'set':
					$doDelete 		= 2;
					$doAssign 		= true;
					break;

				case 'del':
					$doDelete		= true;
					$doAssign		= false;
					break;

				case 'add':
				default:
					$doDelete		= false;
					$doAssign		= true;
					break;
			}

			if ($doDelete) {
				if ($doDelete === 2) {
					$db->setQuery(
						'DELETE FROM #__core_acl_groups_'.$type.'_map' .
						' WHERE aro_id IN ('.implode(',', $ids).')'
					);
				}
				else {
					$db->setQuery(
						'DELETE FROM #__core_acl_groups_'.$type.'_map' .
						' WHERE aro_id IN ('.implode(',', $ids).')' .
						'  AND group_id = '.$groupId
					);
				}
				if (!$db->query()) {
					$result = new JException($db->getErrorMsg());
					return $result;
				}
				echo $db->getQuery();
			}

			if ($doAssign) {
				$tuples = array();
				foreach ($ids as $id) {
					$tuples[] = '('.$id.','.$groupId.')';
				}
				$db->setQuery(
					'INSERT IGNORE INTO #__core_acl_groups_'.$type.'_map (aro_id,group_id)' .
					' VALUES '.implode(',', $tuples)
				);
				if (!$db->query()) {
					$result = new JException($db->getErrorMsg());
					return $result;
				}
				echo $db->getQuery();
			}
			$acl->setCheckMode($oldMode);
		}
		return $result;
	}

}