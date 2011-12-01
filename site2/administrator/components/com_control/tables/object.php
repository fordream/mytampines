<?php
/**
 * @version		$Id: object.php 1163 2009-05-22 23:15:43Z eddieajau $
 * @copyright	Copyright (C) 2008 - 2009 JXtended LLC. All rights reserved.
 * @license		GNU General Public License
 */

/**
 * @package		JXtended.Control
 * @subpackage	com_control
 */
class AclTableObject extends JTable
{
	/**
	 * @var decimal,
	 */
	var $id = null;
	/**
	 * @var varchar
	 */
	var $section_value = null;
	/**
	 * @var varchar
	 */
	var $value = null;
	/**
	 * @var decimal,
	 */
	var $order_value = null;
	/**
	 * @var varchar
	 */
	var $name = null;
	/**
	 * @var decimal,
	 */
	var $hidden = null;

	/*
	 * Constructor
	 * @param object Database object
	 */
	function __construct(&$db, $type='')
	{
		parent::__construct('#__core_acl_'.$type, 'id', $db);
	}
}

