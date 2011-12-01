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
DS.'sitecategoryrelations.php');

class Hub2ModelSiteCategoryRelations extends JModel {

    private $_table;

    public function __construct($config=array()) {
        parent::__construct($config);
        $this->_table = new Hub2TableSiteCategoryRelations($this->_db);
    }

    public function getCategories($itemId) {
        return $this->_table->getRelations($itemId);
    }

    /**
     * @return an array of ids with indexes being the size
     */
    public function getCategoryIds($itemId) {
        $catIds = null;
        $rows = $this->_table->getRelations($itemId);
        foreach ($rows as $row) {
            $catIds[] = $row->category_id;
        }
        return $catIds;
    }

    /**
     * Get all item (site id) mapping for a given media
     * @return list return all rows with input media id
     */
    public function getItems($categoryId) {
        return $this->_table->getItems($categoryId);
    }

    public function updateCategories($itemId, $categoryIds = array()) {
        if (is_array($categoryIds) || is_int($categoryIds)) {
            $result = $this->_table->deleteRelations($itemId);
            if (!$result) {
                $this->setError(JText::_('ERROR_DELETE_OLD'));
                return false;
            }
            if (!empty($categoryIds)) {
                $result = $this->_table->addRelations($itemId, $categoryIds);
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
     * Delete site from categories
     * @param $siteId int ID of the site that is being deleted
     * @return boolean true on success
     */
    public function deleteSiteRelations($siteId) {
        return $this->_table->deleteRelations($siteId);
    }

    /**
     * Delete a category from all sites
     * @param $catId int ID of the category that is being deleted
     * @return boolean true on success
     */
    public function deleteCategoryRelations($catId) {
        return $this->_table->deleteRelatedItem($catId);
    }

    // For testing only
    public function setTable($table) {
        $this->_table = $table;
    }
}