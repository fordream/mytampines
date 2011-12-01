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
DS.'sitepostcoderelations.php');

class Hub2ModelSitePostcodeRelations extends JModel {

    private $_table;

    public function __construct($config=array()) {
        parent::__construct($config);
        $this->_table = new Hub2TableSitePostcodeRelations($this->_db);
    }

    public function getPostcodes($siteId) {
        return $this->_table->getRelations($siteId);
    }

    /**
     * @return an array of ids with indexes being the size
     */
    public function getPostcodeIds($itemId) {
        $catIds = null;
        $rows = $this->_table->getRelations($itemId);
        foreach ($rows as $row) {
            $catIds[] = $row->postcode_id;
        }
        return $catIds;
    }

    /**
     * Get all item (sites) mapping for a given postcode
     * @return array return all rows with given postcodeId
     */
    public function getItems($postcodeId) {
        return $this->_table->getItems($postcodeId);
    }

    public function updatePostcodes($itemId, $postcodeIds = array()) {
        if (is_array($postcodeIds) || is_int($postcodeIds)) {
            $result = $this->_table->deleteRelations($itemId);
            if (!$result) {
                $this->setError(JText::_('ERROR_DELETE_OLD'));
                return false;
            }
            if (!empty($postcodeIds)) {
                $result = $this->_table->addRelations($itemId, $postcodeIds);
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
     * Get all item (sites) mapping for a given postcode
     * @return list return all rows with given postcodeId
     */
    public function getItemsByLang($postcodeId,$lang) {
        return $this->_table->getItemsByLang($postcodeId,$lang);
    }

    /**
     * Delete site from postcodes
     * @param $siteId int ID of the site that is being deleted
     * @return boolean true on success
     */
    public function deleteSiteRelations($siteId) {
        return $this->_table->deleteRelations($siteId);
    }

    // For testing only
    public function setTable($table) {
        $this->_table = $table;
    }
}