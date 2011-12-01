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
require_once(dirname(__FILE__). DS.'hub2.php');


/**
 * Class to represent item media relations table
 */
class Hub2TableCategoryMediaRelations extends Hub2Table {

    public $_relation_table;
    public $_relation_tablekey;
    public $_mapping_key;

    public function __construct(&$db ) {
        parent::__construct('#__hub2_category_media_relations' , 'id', $db);
        $this->setRelationTableMapping('#__hub2_site_media','id','media_id');
    }

    public function getRelations($itemId) {
        $query = 'SELECT * FROM ' . $this->getTableName() .
        ' WHERE category_id=' . $itemId;
        $db = $this->getDBO();
        $db->setQuery($query);
        $result = $db->loadObjectList();
        return $result;
    }

    public function deleteRelations($itemId) {
        $query = 'DELETE FROM ' . $this->getTableName()
        . ' WHERE category_id=' . $itemId;
        $db = $this->getDBO();
        $db->setQuery($query);
        return $db->query();
    }

    public function addRelations($itemId, $mediaIds) {
        if(is_array($mediaIds)) {
            foreach ($mediaIds as $size=>$media) {
                if (!empty($media)) {
                    $this->addRelation($itemId, $size, $media);
                }
            }
            return true;
        }
        return false;
    }

    public function addRelation($itemId, $size, $mediaId) {
        // cannot use save() here as it fails to do multiple rows
        $db = $this->getDBO();
        $query =
            'INSERT INTO ' . $this->getTableName()
        . ' (category_id, media_id, size_description) VALUES ('
        . $itemId . ', ' . $mediaId . ', \'' . $size . '\')';
        $db->setQuery($query);
        return $db->query();
    }

    /**
     * Get all item (media) mapping for a given media
     * @return list return all rows with input media id
     */
    public function getItems($mediaId) {
        $query = 'SELECT * FROM ' . $this->getTableName() . ' WHERE media_id=' . $mediaId;
        $db = $this->getDBO();
        $db->setQuery($query);
        $result = $db->loadObjectList();
        return $result;
    }

    public function setRelationTableMapping($table,$tableKey,$foreignKey) {
        $this->_relation_table = $table;
        $this->_relation_tablekey = $tableKey;
        $this->_mapping_key = $foreignKey;
    }

    public function getRelationDetails($itemId,$size) {
        if (empty($this->_relation_table)) {
            return null;
        }
        $db = $this->getDBO();
        $query = 'SELECT t.*, rt.* FROM ' . $this->_relation_table
        .' rt LEFT JOIN ' . $this->_tbl
        . ' t ON t.'.$this->_mapping_key.'=rt.'.$this->_relation_tablekey
        .' WHERE t.category_id=' . $itemId . ' AND t.size_description='.$db->Quote($size);
        // echo $query;
        $db->setQuery($query);
        $result = $db->loadObject();
        return $result;
    }

}