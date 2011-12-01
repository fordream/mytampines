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
 * Class to represent site region relations table
 */
class Hub2TableSiteRegionRelations extends Hub2Table {

    public function __construct(&$db ) {
        parent::__construct('#__hub2_sites_region_relations' , 'id', $db);
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

    public function addRelations($itemId, $regionIds) {
        if(is_array($regionIds)) {
            $result = true;
            foreach ($regionIds as $region) {
                $result = $this->addRelation($itemId, $region) && $result;
            }
            return $result;
        } else if (is_int($regionIds)) {
            return $this->addRelation($itemId, $regionIds);
        }
        return false;
    }

    public function addRelation($itemId, $categoryId) {
        // cannot use save() here as it fails to do multiple rows
        $db = $this->getDBO();
        $query =
            'INSERT INTO ' . $this->getTableName()
        . ' (site_id, region_id) VALUES ('
        . $itemId . ', ' . $categoryId . ')';
        $db->setQuery($query);
        return $db->query();
    }

    /**
     * Get all items (site) mapping for a given category
     * @return list return all rows with input category id
     */
    public function getItems($regionId) {
        $query = 'SELECT site_id FROM ' . $this->getTableName() . ' WHERE region_id=' . $regionId;
        $db = $this->getDBO();
        $db->setQuery($query);
        $result = $db->loadResultArray();
        return $result;
    }

    /**
     * Get all items (site) mapping for a given category
     * @return list return all rows with input category id
     */
    public function getItemsByLang($regionId,$lang) {
        $db = $this->getDBO();
        $query = 'SELECT site_id FROM ' . $this->getTableName() .
        ' LEFT JOIN #__hub2_sites ON ' . $this->getTableName() .
        '.site_id=#__hub2_sites.id WHERE #__hub2_sites.lang='.$db->Quote($lang).
        ' AND region_id=' . $regionId;
        $db->setQuery($query);
        $result = $db->loadResultArray();
        return $result;
    }

}