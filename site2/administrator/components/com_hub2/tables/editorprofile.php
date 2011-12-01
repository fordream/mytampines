<?php
/**
 * @version		$Id: $
 * @package		com_hub2
 * @copyright	(C) 2010 HyperLocalizer Pty Ltd. All rights reserved.
 * @license		HyperLocalizer proprietary.
 */
// No direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.database.table');


/**
 * Class to represent item media relations table
 */
class JTableEditorProfile extends JTable {

    var $user_id = null;

    var $profile = null;

    public function __construct(&$db ) {
        parent::__construct('#__hub2_editor_profile' , 'user_id', $db);
    }

    function getEditorProfile($id) {
        $sql = "SELECT * FROM ".$this->getTableName()." WHERE ";
        $sql .= " user_id = {$id}";
        $this->_db->setQuery($sql);
        return $this->_db->loadObject();
    }

    function store() {
        $sql = "REPLACE INTO ".$this->getTableName()." (user_id,profile) VALUES ";
        $sql .= " (".$this->_db->Quote($this->user_id).",".
        $this->_db->Quote($this->profile).")";
        return $this->_db->Execute($sql);
    }

    function getProfileValue($id,$key) {
        $sql = "SELECT profile FROM ".$this->getTableName()." WHERE ";
        $sql .= " user_id = {$id}";
        $this->_db->setQuery($sql);
        $profile = new JParameter($this->_db->loadResult());
        return $profile->get($key);
    }

}