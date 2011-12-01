<?php
/*
 * @component AlphaUserPoints
 * @copyright Copyright (C) 2008-2010 Bernard Gilly
 * @license : GNU/GPL
 * @Website : http://www.alphaplug.com
 */

// no direct access
defined('_JEXEC') or die('Restricted access');


class JTablemedals extends JTable
{
	/**
	 * Primary Key
	 * @var int
	 */
	var $id = null;
	/** @var int */
	var $rid = '';
	/** @var int */
	var $medal = '';
	/** @var date */
	var $medaldate = '';
	/** @var string */
	var $reason = '';

	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	function __construct(& $db) {
		parent::__construct('#__alpha_userpoints_medals', 'id', $db);
	}	

}
?>
