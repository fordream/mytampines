<?php

/**
 * @version		$Id: $
 * @package		com_hub2
 * @copyright	(C) 2010 HyperLocalizer Pty Ltd. All rights reserved.
 * @license		HyperLocalizer proprietary.
 */

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.application.component.model');
jimport('joomla.error.exception');
require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_hub2'.DS.'tables'.
DS.'siteregionrelations.php');

class Hub2ModelSiteRegionRelations extends JModel {

    private $_table;

    public function __construct($config=array()) {
        parent::__construct($config);
        $this->_table = new Hub2TableSiteRegionRelations($this->_db);
    }

    public function getRegions($siteId) {
        return $this->_table->getRelations($siteId);
    }

    /**
     * @return an array of ids with indexes being the size
     */
    public function getRegionIds($itemId) {
        $catIds = null;
        $rows = $this->_table->getRelations($itemId);
        foreach ($rows as $row) {
            $catIds[] = $row->region_id;
        }
        return $catIds;
    }

    /**
     * Get all item (sites) mapping for a given region
     * @return list return all rows with given regionId
     */
    public function getItems($regionId) {
        return $this->_table->getItems($regionId);
    }

    public function updateRegions($itemId, $regionIds = array()) {
        if (is_array($regionIds) || is_int($regionIds)) {
            $result = $this->_table->deleteRelations($itemId);
            if (!$result) {
                $this->setError(JText::_('ERROR_DELETE_OLD'));
                return false;
            }
            if (!empty($regionIds)) {
                $result = $this->_table->addRelations($itemId, $regionIds);
                if (!$result) {
                    $this->setError($this->_table->getError());
                }
            }
            return $result;
        }
        $this->setError(JText::_('ERROR_UPDATE_RELATIONS'));
        return false;
    }

    /**
     * Delete site from regions
     * @param $siteId int ID of the site that is being deleted
     */
    public function deleteSiteRelations($siteId) {
        return $this->_table->deleteRelations($siteId);
    }

    /**
     * Get all item (sites) mapping for a given region
     * @return list return all rows with given regionId
     */
    public function getItemsByLang($regionId,$lang) {
        return $this->_table->getItemsByLang($regionId,$lang);
    }

    // For testing only
    public function setTable($table) {
        $this->_table = $table;
    }
}