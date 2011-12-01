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
class Hub2TableSiteCategoryRelations extends Hub2Table {

    public function __construct(&$db ) {
        parent::__construct('#__hub2_sites_category_relations' , 'id', $db);
    }

    public function getRelations($itemId) {
        $query = 'SELECT * FROM ' . $this->getTableName() .
        ' WHERE site_id=' . $itemId;
        $db = $this->getDBO();
        $db->setQuery($query);
        $result = $db->loadObjectList();
        return $result;
    }

    public function deleteRelations($itemId) {
        $query = 'DELETE FROM ' . $this->getTableName()
        . ' WHERE site_id=' . $itemId;
        $db = $this->getDBO();
        $db->setQuery($query);
        return $db->query();
    }

    public function deleteRelatedItem($catId) {
        $query = 'DELETE FROM ' . $this->getTableName()
        . ' WHERE category_id=' . $catId;
        $db = $this->getDBO();
        $db->setQuery($query);
        return $db->query();
    }

    public function addRelations($itemId, $categoryIds) {
        if(is_array($categoryIds)) {
            $result = true;
            foreach ($categoryIds as $category) {
                $result = $this->addRelation($itemId, $category) && $result;
            }
            return $result;
        } else if (is_int($categoryIds)) {
            return $this->addRelation($type, $itemId, $categoryIds);
        }
        return false;
    }

    public function addRelation($itemId, $categoryId) {
        // cannot use save() here as it fails to do multiple rows
        $db = $this->getDBO();
        $query =
            'INSERT INTO ' . $this->getTableName()
        . ' (site_id, category_id) VALUES ('
        . $itemId . ', ' . $categoryId . ')';
        $db->setQuery($query);
        return $db->query();
    }

    /**
     * Get all items (site) mapping for a given category
     * @return list return all rows with input category id
     */
    public function getItems($categoryId) {
        $query = 'SELECT site_id FROM ' . $this->getTableName() .
             ' WHERE category_id=' . $categoryId;
        $db = $this->getDBO();
        $db->setQuery($query);
        $result = $db->loadResultArray();
        return $result;
    }
}