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
class Hub2TableAuthorMediaRelations extends Hub2Table {

    public function __construct(&$db ) {
        parent::__construct('#__hub2_author_media_relations' , 'id', $db);
    }

    public function getRelations($itemId) {
        $query = 'SELECT * FROM ' . $this->getTableName() .
        ' WHERE author_id=' . $itemId;
        $db = $this->getDBO();
        $db->setQuery($query);
        $result = $db->loadObjectList();
        return $result;
    }

    public function deleteRelations($itemId) {
        $query = 'DELETE FROM ' . $this->getTableName()
        . ' WHERE author_id=' . $itemId;
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
            . ' (author_id, media_id, size_description) VALUES ('
            . $itemId . ', ' . $mediaId . ', \'' . $size . '\')';
            $db->setQuery($query);
            return $db->query();
    }

    /**
     * Get all item (author) mapping for a given media
     * @return list return all rows with input media id
     */
    public function getItems($mediaId) {
        $query = 'SELECT * FROM ' . $this->getTableName() . ' WHERE media_id=' . $mediaId;
        $db = $this->getDBO();
        $db->setQuery($query);
        $result = $db->loadObjectList();
        return $result;
    }
}