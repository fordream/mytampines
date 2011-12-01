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
class Hub2TableCategoryRootContentTypeRelations extends Hub2Table {

    public function __construct(&$db ) {
        parent::__construct('#__hub2_category_root_content_type_relations' , 'category_id', $db);
    }

    public function getRelations($categoryId) {
        $query = 'SELECT content_type_id FROM ' . $this->getTableName() .
        ' WHERE category_id=' . $categoryId;
        $this->_db->setQuery($query);
        $result = $this->_db->loadResultArray();
        return $result;
    }

    public function deleteRelations($categoryId) {
        $query = 'DELETE FROM ' . $this->getTableName()
        . ' WHERE category_id=' . $categoryId;
        $this->_db->setQuery($query);
        return $this->_db->query();
    }

    public function addRelations($categoryId, $contentTypeIds) {
        $success = true;
        foreach ($contentTypeIds as $id) {
            $success = $this->addRelation($categoryId,$id) and $success;
        }
        return $success;
    }

    public function addRelation($categoryId, $contentTypeId) {
        $query =
            'REPLACE INTO ' . $this->getTableName()
        . ' (category_id, content_type_id) VALUES ('
        . $categoryId . ', ' . $contentTypeId . ' )';
        $this->_db->setQuery($query);
        return $this->_db->query();
    }
}