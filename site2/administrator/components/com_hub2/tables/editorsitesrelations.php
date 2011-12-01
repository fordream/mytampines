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
class Hub2TableEditorSitesRelations extends Hub2Table {

    public function __construct(&$db ) {
        parent::__construct('#__hub2_editor_sites_relations' , 'id', $db);
    }

    public function getRelations($itemId) {
        $query = 'SELECT * FROM ' . $this->getTableName() .
        ' WHERE user_id=' . $itemId;
        $db = $this->getDBO();
        $db->setQuery($query);
        $result = $db->loadObjectList();
        return $result;
    }

    public function deleteRelations($itemId) {
        $query = 'DELETE FROM ' . $this->getTableName()
        . ' WHERE user_id=' . $itemId;
        $db = $this->getDBO();
        $db->setQuery($query);
        return $db->query();
    }

    public function addRelations($itemId, $siteIds) {
        if(is_array($siteIds)) {
            $result = true;
            foreach ($siteIds as $siteId) {
                $result = $this->addRelation($itemId, $siteId) && $result;
            }
            return $result;
        } else if (is_int($siteIds)) {
            return $this->addRelation($itemId, $siteIds);
        }
        return false;
    }

    public function addRelation($itemId, $siteId) {
        // cannot use save() here as it fails to do multiple rows
        $db = $this->getDBO();
        $query =
            'INSERT INTO ' . $this->getTableName()
        . ' (user_id, site_id) VALUES ('
        . $itemId . ', ' . $siteId . ')';
        $db->setQuery($query);
        return $db->query();
    }

    function _getListQuery( $options, $resolveFKs=false) {

        $qb = new JXQuery;

        // - select -
        $qb->select( 's.*' );

        // - from -
        $qb->from( '#__users s' );

        // options
        $search = $options['search'];

        if (trim($search) !== '') {
            $qb->where( 's.username LIKE \'%'.trim($search).'%\'' );
        }

        $qb->where("usertype='" . UserRole::CONTENT_EDITOR."'");

        // - ordering -
        $orderCol   = $options['orderCol'];
        $orderDirn = $options['orderDirn'];
        if ($orderCol) {
            $qb->order( $orderCol . ' ' . $orderDirn );
        }

        return $qb;
    }

    function getUser($id) {
        if (!isset($id)) {
            return null;
        }
        $query = "SELECT * from #__users where id = {$id}";
        $this->_db->setQuery($query);
        return $this->_db->loadObject();
    }

    function checkRelationExists($user_id, $site_id) {
        $query = 'select * from ' .$this->getTableName().' WHERE user_id='.$user_id.
        ' AND site_id = '. $site_id;
        $this->_db->setQuery($query);
        $result = $this->_db->loadObject();
        return !empty($result);
    }
}