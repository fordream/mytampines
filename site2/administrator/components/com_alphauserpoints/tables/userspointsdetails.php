<?php
/*
 * @component AlphaUserPoints
 * @copyright Copyright (C) 2008-2010 Bernard Gilly
 * @license : GNU/GPL
 * @Website : http://www.alphaplug.com
 */

// no direct access
defined('_JEXEC') or die('Restricted access');


class JTableuserspointsdetails extends JTable
{
	/**
	 * Primary Key
	 * @var int
	 */
	var $id = null;
	/** @var string */
	var $referreid = '';
	/** @var string */
	var $points = '';
	/** @var datetime */
	var $insert_date = '';
	/** @var datetime */
	var $expire_date = '';
	/** @var int */
	var $status = '';
	/** @var int */
	var $rule = '';
	/** @var int */
	var $approved = '';
	/** @var string */
	var $keyreference = '';	
	/** @var string */
	var $datareference = '';	

	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	function __construct(& $db) {
		parent::__construct('#__alpha_userpoints_details', 'id', $db);
	}
}
?>
