<?php
/**
 * @version		$Id: group.php 1163 2009-05-22 23:15:43Z eddieajau $
 * @copyright	Copyright (C) 2008 - 2009 JXtended LLC. All rights reserved.
 * @license		GNU General Public License
 */

/**
 * @package		JXtended.Control
 * @subpackage	com_control
 */
class AclTableGroup extends JTable
{
	/**
	 * @var decimal,
	 */
	var $id = null;
	/**
	 * @var decimal,
	 */
	var $parent_id = null;
	/**
	 * @var decimal,
	 */
	var $lft = null;
	/**
	 * @var decimal,
	 */
	var $rgt = null;
	/**
	 * @var varchar
	 */
	var $name = null;
	/**
	 * @var varchar
	 */
	var $value = null;

	/*
	 * Constructor
	 * @param object Database object
	 */
	function __construct(&$db, $type='')
	{
		parent::__construct('#__core_acl_'.$type.'_groups', 'id', $db);
	}

	/**
	 * Recursive method
	 * @param	int	parent id
	 * @param	int	Left value
	 */
	function rebuild($parent_id = 0, $left = 1)
	{
		$db = &$this->_db;

		$parent_id = (int) $parent_id;

		// get all children of this node
		$query = 'SELECT id FROM '. $this->_tbl .' WHERE parent_id='. $parent_id;

		$db->setQuery($query);
		$children = $db->loadResultArray();

		// the right value of this node is the left value + 1
		$right = $left + 1;

		$n = count($children);
		foreach ($children as $id)
		{
			// recursive execution of this function for each
			// child of this node
			// $right is the current right value, which is
			// incremented by the rebuild_tree function
			$right = $this->rebuild($id, $right);

			if ($right === FALSE) {
				return FALSE;
			}
		}

		// we've got the left value, and now that we've processed
		// the children of this node we also know the right value
		$query  = 'UPDATE '. $this->_tbl .' SET lft='. $left .', rgt='. $right .' WHERE id='. $parent_id;

		$db->setQuery($query);
		if (!$db->query()) {
			return false;
		}

		// return the right value of this node + 1
		return $right + 1;
	}
}

