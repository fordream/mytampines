<?php
/**
 * @version		$Id: group.php 1163 2009-05-22 23:15:43Z eddieajau $
 * @package		JXtended.Control
 * @copyright	Copyright (C) 2008 - 2009 JXtended LLC. All rights reserved.
 * @license		GNU General Public License
 */

// no direct access
defined('_JEXEC') or die;

jximport('jxtended.application.component.model');
jximport('jxtended.database.query');

/**
 * @package		JXtended.Control
 */
class ControlModelGroup extends JXModel
{
	/**
	 * Valid types
	 */
	function isValidType($type)
	{
		$types	= array('aro', 'axo');
		return in_array(strtolower($type), $types);
	}

	/**
	 * @return	JTable
	 */
	function &getResource()
	{
		static $first = true;

		$type = $this->getState('group_type');
		if (!$this->isValidType($type)) {
			return JError::raiseError(500, $type.' is not a valid group type');
		}

		if ($first)
		{
			// Bit of a shortcut to load the table class
			JTable::getInstance('Group', 'AclTable');
			$table	= new AclTableGroup($this->getDBO(), $type);
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
				'group_type' => $state->get('group_type'),
				'where' => 'a.id='.(int)$id
			);
			$temp		= $this->getList($filters, true);
			if (isset($temp[0])) {
				$instance = @$temp[0];
			}
			else {
				$temp = $this->getResource();
				$instance = JArrayHelper::toObject($temp->getPublicProperties());
			}
		}
		return $instance;
	}

	/**
	 * @param	boolean	True to resolve foreign keys
	 * @return	array	List of items
	 */
	function &getItems($resolveFKs = true)
	{
		static $instances;

		$state	= $this->getState();
		$hash	= md5(intval($resolveFKs).serialize($state->getProperties(1)));

		if (!isset($instances[$hash]))
		{
			$query				= $this->_getListQuery(JArrayHelper::fromObject($state), $resolveFKs);
			$sql				= $query->toString();
			$this->_total		= $this->_getListCount($sql);
			if ($this->_total < $state->get('limitstart')) {
				$state->set('limitstart', 0);
			}
			$result				= $this->_getList($sql, $state->get('limitstart'), $state->get('limit'));
			$instances[$hash]	= $result;
		}
		else {
			// TODO: Ideal for true caching
			$result = $instances[$hash];
		}

		return $result;
	}

	/**
	 * Gets the Form
	 */
	function &getForm($type = 'view')
	{
		jximport('jxtended.form.helper');
		JXFormHelper::addIncludePath(JPATH_COMPONENT.DS.'models');

		if ($type == 'model') {
			$result = &JXFormHelper::getModel('group');
		} else {
			$result = &JXFormHelper::getView('group');
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
		$type			= strtolower(@$filters['group_type']);
		$tree			= @$filters['tree'];
		$parentId		= @$filters['parent_id'];
		// arbitrary where
		$select			= @$filters['select'];
		$search			= @$filters['search'];
		$where			= @$filters['where'];
		$orderBy		= @$filters['order by'];

		$db		= &$this->getDBO();
		$query		= new JXQuery;
		$table	= '#__core_acl_'.$type.'_groups';

		$query->select($select !== null ? $select : 'a.*');
		$query->from($table.' AS a');

		if ($tree)
		{
			$query->select('COUNT(DISTINCT c2.id) AS level');
			$query->join('LEFT OUTER', $table.' AS c2 ON a.lft > c2.lft AND a.rgt < c2.rgt');
			$query->group('a.id');
		}

		if ($parentId > 0) {
			$query->join('LEFT', $table.' AS p ON p.id = '.(int) $parentId);
			$query->where('a.lft > p.lft AND a.rgt < p.rgt');
		}

		if ($resolveFKs)
		{
			if ($type == 'aro') {
				$query->select('COUNT(DISTINCT map.aro_id) AS object_count');
				$query->join('LEFT', '#__core_acl_groups_'.$type.'_map AS map ON map.group_id=a.id');
				$query->group('a.id');
			}
			else if ($type == 'axo') {
				$query->select('COUNT(DISTINCT map.axo_id) AS object_count');
				$query->join('LEFT', '#__core_acl_groups_'.$type.'_map AS map ON map.group_id=a.id');
				$query->group('a.id');
			}
		}

		if ($search) {
			$serach = $db->Quote('%'.$db->getEscaped($search, true).'%', false);
			$query->where('a.name LIKE '.$serach);
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

	function save($values)
	{
		$request	= $this->getState('request');
		$form		= JArrayHelper::getValue($request, 'jxform');
		$groupType	= JRequest::getCmd('group_type');

		$acl		= &JFactory::getACL();
//$acl->_debug = 1;

		$parentId	= JArrayHelper::getValue($values, 'parent_id');
		$name		= JArrayHelper::getValue($values, 'name');
		$value		= JArrayHelper::getValue($values, 'value');
		$id			= JArrayHelper::getValue($values, 'id', 0, 'int');

		if ($id) {
			// Existing
			$result = $acl->edit_group($id, $value, $name, $parentId, $groupType);
		}
		else {
			// New
			if (strtolower($groupType) == 'axo') {
				// We need to give it a numeric value
				$db = &$this->getDBO();
				$db->setQuery(
					'SELECT id FROM #__core_acl_axo_groups ORDER BY id DESC', 0, 1
				);
				$value = (int) $db->loadResult() + 1;
			}
			$result = $acl->add_group($value, $name, $parentId, $groupType);
			$id		= $result;
		}

		if (!$result) {
			//$result = JError::raiseNotice(500, 'Failed to save group');
			$result = JError::raiseWarning(500, array_pop($acl->_debugLog));
		}
		else {
			$this->setState('group_type', $groupType);
			$table		= &$this->getResource();
			$table->id	= $id;
		}
//die;
		return $result;
	}

	function delete($ids = array())
	{
		$acl		= &JFactory::getACL();
		$request	= $this->getState('request');
		$groupType	= JArrayHelper::getValue($request, 'group_type');
		if (empty($groupType)) {
			return new JException('Group type must be specified');
		}
		foreach ((array) $ids as $id)
		{
			$acl->_debug = 1;
			$result		= $acl->del_group($id, true, $groupType);
			if ($result == false) {
				JError::raiseWarning(500, array_pop($acl->_debugLog));
				break;
			}
		}
		return $result;
	}
}