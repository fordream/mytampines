<?php
/*
 * @component AlphaUserPoints
 * @copyright Copyright (C) 2008-2010 Bernard Gilly
 * @license : GNU/GPL
 * @Website : http://www.alphaplug.com
 */

// no direct access
defined('_JEXEC') or die('Restricted access');


class JTableuserspointsrequests extends JTable
{
	/**
	 * Primary Key
	 * @var int
	 */
	var $id = null;
	/** @var int */
	var $userid = '';
	/** @var string */
	var $referreid = '';
	var $name = '';
	var $username = '';
	/** @var int */
	var $levelrequest = '';
	/** @var int */
	var $checked = '';
	var $checkedadmin = '';
	var $response = '';
	/** @var datetime */
	var $requestdate = '';
	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	function __construct(& $db) {
		parent::__construct('#__alpha_userpoints_requests', 'id', $db);
	}
}
?>
