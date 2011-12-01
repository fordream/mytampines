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
 * Class to represent site postcode relations table
 */
class Hub2TableSitePostcodeRelations extends Hub2Table {

    public function __construct(&$db ) {
        parent::__construct('#__hub2_sites_postcode_relations' , 'id', $db);
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

    public function addRelations($itemId, $postcodeIds) {
        if(is_array($postcodeIds)) {
            $result = true;
            foreach ($postcodeIds as $postcode) {
                $result = $this->addRelation($itemId, $postcode) && $result;
            }
            return $result;
        } else if (is_int($postcodeIds)) {
            return $this->addRelation($itemId, $postcodeIds);
        }
        return false;
    }

    public function addRelation($itemId, $postcodeId) {
        // cannot use save() here as it fails to do multiple rows
        $db = $this->getDBO();
        $query =
            'INSERT INTO ' . $this->getTableName()
        . ' (site_id, postcode_id) VALUES ('
        . $itemId . ', ' . $postcodeId . ')';
        $db->setQuery($query);
        return $db->query();
    }

    /**
     * Get all items (site) mapping for a given postcode
     * @return list return all rows with input postcode id
     */
    public function getItems($postcodeId) {
        $query = 'SELECT site_id FROM ' . $this->getTableName()
        . ' WHERE postcode_id=' . $postcodeId;
        $db = $this->getDBO();
        $db->setQuery($query);
        $result = $db->loadResultArray();
        return $result;
    }

    /**
     * Get all items (site) mapping for a given postcode
     * @return list return all rows with input postcode id
     */
    public function getItemsByLang($postcodeId,$lang) {
        $db = $this->getDBO();
        $query = 'SELECT site_id FROM ' . $this->getTableName() .
        ' LEFT JOIN #__hub2_sites ON ' . $this->getTableName() .
        '.site_id=#__hub2_sites.id WHERE #__hub2_sites.lang='.$db->Quote($lang).
        ' AND postcode_id=' . $postcodeId;
        $db->setQuery($query);
        $result = $db->loadResultArray();
        return $result;
    }

}