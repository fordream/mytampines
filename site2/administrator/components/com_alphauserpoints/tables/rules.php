<?php
/*
 * @component AlphaUserPoints
 * @copyright Copyright (C) 2008 Bernard Gilly
 * @license : GNU/GPL
 * @Website : http://www.alphaplug.com
 */

// no direct access
defined('_JEXEC') or die('Restricted access');


class JTableRules extends JTable
{
	/**
	 * Primary Key
	 * @var int
	 */
	var $id = null;
	/** @var string */
	var $rule_name = '';
	/** @var string */
	var $rule_description = '';
	/** @var string */
	var $rule_plugin = '';
	/** @var string */
	var $plugin_function = '';
	/** @var int */
	var $access = '';
	/** @var string */
	var $component = '';
	/** @var string */
	var $calltask = '';
	/** @var string */
	var $taskid = '';
	/** @var int */
	var $points = '';
	var $percentage = '';
	/** @var datetime */
	var $rule_expire = '';
	/** @var string */
	var $sections = '';
	var $categories = '';
	var $content_items = '';
	var $exclude_items = '';
	/** @var int */
	var $published = '';
	var $system = '';
	var $duplicate = '';
	var $blockcopy = '';
	var $autoapproved = '';
	var $fixedpoints = '';
	/** @var string */
	var $category = '';

	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	function __construct(& $db) {
		parent::__construct('#__alpha_userpoints_rules', 'id', $db);
	}
}
?>
