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

class Hub2DataModelCategory extends Hub2DataModelTree {

    /**
     * Constructor
     *
     */
    function __construct($config = array()) {
        $this->_table = '#__hub2_categories';
        parent::__construct($config);
    }
    /**
     * @return array of Objects (name,id)
     */
    function &getCategories() {
        $this->_db->setQuery('select title,id,level from #__hub2_categories order by lft asc');

        $options=$this->_db->loadObjectList();

        return $options;
    }

    function getSiblingWithOrderLessThan($parent_id, $order) {
        $sql = "SELECT id, ordering FROM #__hub2_categories";
        $sql .= ' WHERE ordering < '.(int)$order;
        $sql .= ' AND parent_id = '.$parent_id;
        $sql .= ' ORDER BY ordering DESC';

        $this->_db->setQuery( $sql, 0, 1 );
        $row = null;
        $row = $this->_db->loadObject();
        return $row;
    }

    function getSiblingWithOrderGreaterThan($parent_id, $order) {
        $sql = "SELECT id, ordering FROM #__hub2_categories";
        $sql .= ' WHERE ordering > '.(int)$order;
        $sql .= ' AND parent_id = '.$parent_id;
        $sql .= ' ORDER BY ordering';
        $this->_db->setQuery( $sql, 0, 1 );
        $row = null;
        $row = $this->_db->loadObject();
        return $row;
    }

    function _getListQuery($filters,$resolveFKs = false) {
        $filters['table_name'] = 'hub2_categories';
        if (!array_key_exists('rootnode',$filters)) {
            $filters['rootnode'] = 0;
        }
        if ($resolveFKs) {
            $qb = parent::_getListQuery($filters,$resolveFKs,'title');
            $qb->select("GROUP_CONCAT(s.name) as sites");
            $qb->join( 'LEFT', '#__hub2_sites_category_relations AS sr ON sr.category_id=t.id' );
            $qb->join( 'LEFT', '#__hub2_sites AS s ON sr.site_id=s.id' );
            $qb->group( 't.id' );
            return $qb;
        } else {
            return parent::_getListQuery($filters,$resolveFKs,'title');
        }
    }

    function removeCategoryFromSite($category_id,$site_id) {
        return $this->_db->Execute("DELETE FROM #__hub2_sites_category_relations
                                        WHERE category_id={$category_id} AND
                                        site_id={$site_id}");
    }

    function addCategoryToSite($category_id,$site_id) {
        return $this->_db->Execute("REPLACE INTO #__hub2_sites_category_relations
                                        (category_id,site_id)
                                        VALUES ({$category_id},{$site_id})");
    }

    /**
     * Assumes valid data to save
     * @param $values
     * @param $tableObject
     * @param $sites
     */
    function save($values, &$tableObject) {
        $result     = $tableObject->save($values);

        if ($result) {
            // rebuild the tree
            $this->rebuild("hub2_categories",true);
            $result = $tableObject->id;
        } else {
            $result = JError::raiseWarning( 500, $tableObject->getError() );
        }
        return $result;

    }

    function getPossibleParents($node) {
        $filters['table_name'] = 'hub2_categories';
        $filters['rootnode'] = 0;
        $filters['exclude_node_and_children'] = $node;
        $query = $this->_getListQuery($filters);
        $sql            = $query->toString();
        $result         = $this->_getList($sql, 0,0);
        return $result;
    }

    function getContentCount($id) {
        $db = &$this->_db;
        // check if content associated
        $db->setQuery("select count(*) from #__hub2_item_category_relations
                where category_id={$id}");
        return $db->loadResult();
    }

    function  delete($id) {
        $result = $this->_db->Execute("delete from #__hub2_categories where id={$id}");
        $this->rebuild("hub2_categories",true);
        return $result;
    }

    function getId($name) {
        $query = "SELECT id FROM #__hub2_categories WHERE title='{$name}'";
        $this->_db->setQuery($query);
        return $this->_db->loadResult();
    }

    /**
     * @return array of Site IDs
     */
    function &getSiteIDsForCategory($category_id) {
        $this->_db->setQuery('select site_id as id from #__hub2_sites_category_relations
                                where category_id='.$category_id);

        $option=$this->_db->loadResultArray();

        return $option;
    }

    /**
     * @return array of site Objects indexed by the site id
     */
    function &getSitesForCategory($category_id) {
        $this->_db->setQuery('select s.* from #__hub2_sites s
                                LEFT JOIN #__hub2_sites_category_relations sr
                                ON s.id=sr.site_id where sr.category_id='.$category_id);

        $option=$this->_db->loadObjectList('id');

        return $option;
    }

    function getNextOrderingForItemWithParent($parent_id=0) {
        $sql = "select ordering FROM #__hub2_categories";
        $sql .= ' WHERE parent_id = '.$parent_id;
        $sql .= ' ORDER BY ordering desc';
        $this->_db->setQuery( $sql, 0, 1 );
        $row = null;
        $row = $this->_db->loadObject();

        if ($row == null) {
            return 1; // default ordering is 1
        } else {
            return (int)$row->ordering+1;
        }
    }

    function getSiteIDsForChildren($parent_id) {
        $children = $this->getChildrenIDs("hub2_categories",$parent_id);
        $return = array();
        if (count($children) > 0) {
            // get the site IDs
            $sql = "select site_id as id from #__hub2_sites_category_relations where ";
            $sql .= "category_id IN (".implode(',',$children).")";
            $this->_db->setQuery($sql);
            return $this->_db->loadResultArray();
        }
        return $return;
    }

    function getChildrenCount($id) {
        $db = &$this->_db;
        // check if content associated
        $db->setQuery("select count(*) from #__hub2_categories
                where parent_id={$id}");
        return $db->loadResult();
    }

    function getChildren($id) {
        $db = &$this->_db;
        // check if content associated
        $db->setQuery("select * from #__hub2_categories
                where parent_id={$id} order by ordering");
        return $db->loadObjectList('id');
    }

    function rebuildOnExternalSave() {
        return $this->rebuild("hub2_categories",true);
    }

    function &getPath($table_name,$id) {
        $key = $table_name . '$$' . $id;
        if (array_key_exists($key, self::$pathCache)) {
            return self::$pathCache[$key];
        }
        // get all children of this node
        $db = $this->_db;
        $db->setQuery('select lft, rgt from #__'.$table_name.
                ' WHERE id='.$id);
        $row = $db->loadAssoc();
        if ($row['rgt'] && $row['lft']) { // added check to items mapped to category
            // non propagated to site cause errors with the query below
            $db->setQuery('SELECT id,title,alias FROM #__'.$table_name.
            ' WHERE lft <= '.$row['lft'].' AND rgt >= '.$row['rgt'].' ORDER BY lft ASC');
            self::$pathCache[$key] = $db->loadObjectList();
        } else {
            self::$pathCache[$key] = array();
        }
        return self::$pathCache[$key];
    }

    function getRootNodesContraintType($type_id) {
        $db = $this->_db;
        $constraints = array();
        $db->setQuery('select category_id from #__hub2_category_root_content_type_relations
        WHERE content_type_id='.$type_id);
        $rootNodes = $db->loadResultArray();
        foreach ($rootNodes as $rootnode) {
            $db->setQuery('select lft,rgt from #__hub2_categories WHERE id='.$rootnode);
            $result = $db->loadAssoc();
            $constraints[] = "(lft BETWEEN {$result['lft']} AND {$result['rgt']})";
        }
        $s = "";
        if (count($constraints) > 0) {
            $s = implode(' OR ',$constraints);
        }
        return $s;
    }

    function getParentId($catId) {
        $db = $this->_db;
        $qry = "SELECT parent_id, id FROM #__hub2_categories WHERE id = {$catId}";
        $db->setQuery( $qry );
        $rs = $db->loadAssoc();
        return $rs;
    }


}
