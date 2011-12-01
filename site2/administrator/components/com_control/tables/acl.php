<?php
/**
 * @version		$Id: acl.php 1163 2009-05-22 23:15:43Z eddieajau $
 * @copyright	Copyright (C) 2008 - 2009 JXtended LLC. All rights reserved.
 * @license		GNU General Public License
 */

/**
 * @package		JXtended.Control
 * @subpackage	com_control
 */
class AclTableACL extends JTable
{
/**
	 * @var int unsigned
	 */
	var $id = null;
	/**
	 * @var varchar
	 */
	var $section_value = null;
	/**
	 * @var int unsigned
	 */
	var $allow = null;
	/**
	 * @var int unsigned
	 */
	var $enabled = null;
	/**
	 * @var varchar
	 */
	var $return_value = null;
	/**
	 * @var varchar
	 */
	var $note = null;
	/**
	 * @var int unsigned
	 */
	var $updated_date = null;
	/*
	 * @var int unsigned
	 */
	var $acl_type = null;

	/*
	 * Constructor
	 * @param object Database object
	 */
	function __construct(&$db)
	{
		parent::__construct('#__core_acl_acl', 'id', $db);
	}
}