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

abstract class Hub2ModelAbstractMediaRelations extends JModel {

    protected $_table;

    public function getMedia($itemId) {
        return $this->_table->getRelations($itemId);
    }

    /**
     * @return an array of ids with indexes being the size
     */
    public function getMediaIds($itemId) {
        $mediaIds = array();
        $rows = $this->_table->getRelations($itemId);
        foreach ($rows as $row) {
            $mediaIds[$row->size_description] = $row->media_id;
        }
        return $mediaIds;
    }
    /**
     * Get all item (media) mapping for a given media
     * @return list return all rows with input media id
     */
    public function getItems($mediaId) {
        return $this->_table->getItems($mediaId);
    }

    public function updateMedia($itemId, $mediaIds = array()) {
        if (is_array($mediaIds)) {
            $result = $this->_table->deleteRelations($itemId);
            if (!$result) {
                $this->setError(JText::_('ERROR_DELETE_OLD'));
                return false;
            }
            $result = $this->_table->addRelations($itemId, $mediaIds);
            if (!$result) {
                $this->setError($this->_table->getError());
            }
            return $result;
        }
        $this->setError(JText::_('ERROR_UPDATE_RELATIONS'));
        return false;
    }

    // For testing only. Not intended to be used by runtime classes
    public function getTableMember() {
        return $this->_table;
    }
    // For testing only. Not intended to be used by runtime classes
    public function setTableMember($table) {
        $this->_table = $table;
    }
}