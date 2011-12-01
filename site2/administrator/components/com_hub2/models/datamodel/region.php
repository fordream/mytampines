<?php
/**
 * @version     $Id: $
 * @package     com_hub2
 * @copyright   (C) 2010 HyperLocalizer Pty Ltd. All rights reserved.
 * @license     HyperLocalizer proprietary.
 */
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
jximport( 'jxtended.database.query');
require_once('tree.php');

class Hub2DataModelRegion extends Hub2DataModelTree {

    /**
     * Constructor
     *
     */
    function __construct($config = array()) {
        $this->_table = '#__hub2_region';
        parent::__construct($config);
    }

    /**
     * @return array of Objects (name,id)
     */
    function &getRegions() {
        $this->_db->setQuery('select name,id,level from #__hub2_region order by lft asc');

        $options=$this->_db->loadObjectList();

        return $options;
    }

    function _getListQuery($filters,$resolveFKs = false) {
        $filters['table_name'] = 'hub2_region';
        if (!array_key_exists('rootnode',$filters)) {
            $filters['rootnode'] = 0;
        }
        if ($resolveFKs) {
            $qb = parent::_getListQuery($filters,$resolveFKs,'t.name');
            $qb->select("GROUP_CONCAT(s.name) as sites");
            $qb->join( 'LEFT', '#__hub2_sites_region_relations AS sr ON sr.region_id=t.id' );
            $qb->join( 'LEFT', '#__hub2_sites AS s ON sr.site_id=s.id' );
            $qb->group( 't.id' );
            return $qb;
        } else {
            return parent::_getListQuery($filters,$resolveFKs);
        }
    }

    /**
     * Assumes valid data to save
     * @param $values
     * @param $tableObject
     */
    function save($values, $tableObject) {
        $result     = $tableObject->save($values);

        if ($result) {
            // rebuild the tree
            $this->rebuild("hub2_region");
            $result = $tableObject->id;
        } else {
            $result = JError::raiseWarning( 500, $tableObject->getError() );
        }
        return $result;

    }

    function getPossibleParents($node) {
        $filters['table_name'] = 'hub2_region';
        $filters['rootnode'] = 0;
        $filters['exclude_node_and_children'] = $node;
        $query = $this->_getListQuery($filters);
        $sql            = $query->toString();
        $result         = $this->_getList($sql, 0,0);
        return $result;
    }

    function getChildIDs($id) {
        return $this->getChildrenIDs('hub2_region',$id);
    }

    function  delete($id) {
        $db = &$this->_db;
        // check if sites associated
        $db->setQuery("select count(*) from #__hub2_sites_region_relations where region_id={$id}");
        $num = $db->loadResult();
        if ($num == 0) {
            // check if is a parent of another region
            $db->setQuery("select count(*) from #__hub2_region where parent_id={$id}");
            $num = $db->loadResult();
            if ($num == 0) {
                $db->Execute("delete from #__hub2_region where id={$id}");
                $this->rebuild("hub2_region");
            } else {
                $result = JError::raiseWarning(500,
                            "Cannot delete region with ID {$id} since it has sub-regions.");
            }
        } else {
            // return
            $result = JError::raiseWarning(500,
                        "Cannot delete region with ID {$id} since it has associated sites.");
        }
        return $result;
    }

    function getNumberOfSites($id) {
        $db = &$this->_db;
        // check if sites associated
        $db->setQuery("select count(*) from #__hub2_sites_region_relations where region_id={$id}");
        $num = $db->loadResult();
        return $num;
    }

    function getId($region) {
        $db = &$this->_db;
        // check if sites associated
        $db->setQuery("select id from #__hub2_region where name='{$region}'");
        return $db->loadResult();
    }
}
