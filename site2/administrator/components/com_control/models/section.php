<?php
/**
 * @version		$Id: section.php 1163 2009-05-22 23:15:43Z eddieajau $
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
class ControlModelSection extends JXModel
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

		$type = $this->getState('section_type');
		if (!$this->isValidType($type)) {
			return JError::raiseError(500, $type.' is not a valid section type');
		}

		if ($first)
		{
			// Bit of a shortcut to load the table class
			JTable::getInstance('Section', 'AclTable');
			$table	= new AclTableSection($this->getDBO(), $type);
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
				'section_type' => $state->get('section_type'),
				'where' => 'a.id='.(int)$id
			);
			$temp		= $this->getList($filters, true);
			$instance	= @$temp[0];
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

		if ($type == 'model') {
			$result = &JXFormHelper::getModel('section');
		} else {
			$result = &JXFormHelper::getView('section');
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
		$type			= @$filters['section_type'];
		// arbitrary where
		$select			= @$filters['select'];
		$search			= @$filters['search'];
		$where			= @$filters['where'];
		$orderBy		= @$filters['order by'];

		if (!$this->isValidType($type)) {
			return JError::raiseError(500, $type.' is not a valid section type');
		}

		$db	= &$this->getDBO();
		$query	= new JXQuery;

		$query->select($select !== null ? $select : 'a.*');
		$query->from('#__core_acl_'.$type.'_sections AS a');

		if ($resolveFKs)
		{
			//$query->select('co.name AS editor');
			//$query->join('LEFT', '#__users AS co ON co.id=a.checked_out');
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
		$this->setState('section_type', JArrayHelper::getValue($request, 'section_type'));
		return parent::save($values);
	}

	function delete($ids = array())
	{
		$request	= $this->getState('request');
		$type		= JArrayHelper::getValue($request, 'section_type');

		if ($type == 'aro') {
			return JError::raiseWarning(500, 'Cannot delete user (ARO) sections in Joomla!');
		}

		foreach ((array) $ids as $id)
		{
			$acl		= &JFactory::getACL();
			$acl->_debug = 1;
			$result		= $acl->del_object_section($id, $type, false);
			if ($result == false) {
				JError::raiseWarning(500, array_pop($acl->_debugLog));
				break;
			}
		}
		return $result;
	}
}