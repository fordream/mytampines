<?php
/*
 * @component AlphaUserPoints
 * @copyright Copyright (C) 2008-2010 Bernard Gilly
 * @license : GNU/GPL
 * @Website : http://www.alphaplug.com
 */

// no direct access
defined('_JEXEC') or die('Restricted access');


class JTablelevelrank extends JTable
{
	/**
	 * Primary Key
	 * @var int
	 */
	var $id = null;
	/** @var string */
	var $rank = '';
	/** @var string */
	var $description = '';
	/** @var int */
	var $levelpoints = '';
	/** @var int */
	var $typerank = '';
	/** @var string */
	var $icon = '';
	/** @var string */
	var $image = '';
	/** @var int */
	var $gid = '';
	/** @var int */
	var $ruleid = '';
	/** @var int */
	var $ordering = '';	
	
	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	function __construct(& $db) {
		parent::__construct('#__alpha_userpoints_levelrank', 'id', $db);
	}	

}
?>
