<?php
/**
 * @version     $Id: $
 * @package     com_hub2
 * @copyright   (C) 2010 HyperLocalizer Pty Ltd. All rights reserved.
 * @license     HyperLocalizer proprietary.
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
jximport( 'jxtended.database.query' );

/**
 * Tree data model
 *
 */
class Hub2DataModelTree extends Hub2DataModel {
    static $pathCache = array();

    function _getListQuery( $filters, $resolveFKs = false, $titleFieldName = 'name' ) {
        $db = $this->_db;

        // generate the total count
        $table_name = $filters['table_name'];
        $rootnode = $filters['rootnode'];

        $qb = new JXQuery;

        $select = array_key_exists('select', $filters) ? trim($filters['select']) : '';
        if ($select !== '') {
            $qb->select($filters['select']);
        } else {
            $qb->select('t.*');
        }

        $qb->from('#__'.$table_name . ' t');

        if ($rootnode !==0) {
            $db->setQuery('select lft,rgt from #__'.$table_name.
                ' WHERE id='.$rootnode);
            $result = $db->loadAssoc();
            $qb->where("lft BETWEEN {$result['lft']} AND {$result['rgt']}");
        }

        // published filter for category
        if (array_key_exists('published',$filters)) {
            $qb->where("published={$filters['published']}");
        }

        // these are view filters
        $search = array_key_exists('search', $filters) ? trim($filters['search']) : '';
        if ($search !== '') {
            $qb->where($titleFieldName." LIKE '%{$filters['search']}%'");
        }
        $level = array_key_exists('level', $filters) ? $filters['level'] : 0;
        if ($level && (int)$filters['level'] > 0) {
            $qb->where("level <= {$filters['level']}");
        }
        $excludenode = array_key_exists('exclude_node_and_children', $filters) ?
        $filters['exclude_node_and_children'] : null;
        if ($excludenode) {
            $db->setQuery('select lft,rgt from #__'.$table_name.
                ' WHERE id='.$filters['exclude_node_and_children']);
            $result = $db->loadAssoc();
            $qb->where("lft < {$result['lft']} OR lft > {$result['rgt']}");
        }

        $qb->order('lft ASC');

        return $qb;
    }

    function rebuild($table_name,$useOrdering= false) {
        $left = 1;

        $db = $this->_db;
        $orderBy = '';
        if ($useOrdering) {
            $orderBy = ' ORDER BY ordering';
        }
        // get all root nodes
        $db->setQuery('select id from #__'.$table_name.
                ' WHERE parent_id=0'.$orderBy);
        $rows = $db->loadAssocList();
        foreach ($rows as $row) {
            // rebuild the tree for each root node
            $parent = $row['id'];
            $left = $this->rebuild_tree($table_name,$parent,$left,1,$useOrdering);
        }
    }

    function rebuild_tree($table_name,$parent, $left, $level=1,$useOrdering= false) {

        // the right value of this node is the left value + 1
        $db = $this->_db;

        $right = $left+1;

        $orderBy= '';
        if ($useOrdering) {
            $orderBy = ' order by ordering';
        }
        // get all children of this node
        $db->setQuery('select id from #__'.$table_name.
                ' WHERE parent_id='.$parent.$orderBy);
        $rows = $db->loadAssocList();
        foreach ($rows as $row) {
            // recursive execution of this function for each
            // child of this node
            // $right is the current right value, which is
            // incremented by the rebuild_tree function
            $right = $this->rebuild_tree($table_name, $row['id'], $right,$level+1);
        }

        // we've got the left value, and now that we've processed
        // the children of this node we also know the right value

        $db->Execute('UPDATE #__'.$table_name.' SET lft='.$left.
                ', rgt='.$right.', level='.$level.
                ' WHERE id='.$parent);

        // return the right value of this node + 1
        return $right+1;
    }

    function getChildrenIDs($table_name,$parent_id) {
        $db = $this->_db;

        $sql = "SELECT t.id from #__".$table_name." t";

        if (!is_array($parent_id)) {
            $parent_id = array($parent_id);
        }
        $sssql = '';
        if (count($parent_id) > 0) {
            $ssql = "(SELECT {$parent_id[0]} as id";
            $count = count($parent_id);
            for ($i=1; $i < $count; $i++) {
                $ssql .= " UNION SELECT ".$parent_id[$i];
            }
            $ssql .= ") as p";
            $ssql = ' inner join (select lft,rgt from #__'.$table_name.' r right join '.
            $ssql .' on p.id=r.id) as pn on t.lft > pn.lft and t.lft < pn.rgt ';
        }

        $sql = $sql.$ssql.' order by t.lft ASC';
        $db->setQuery($sql);
        return $db->loadResultArray();

    }

    /**
     * Compacts the ordering sequence of the selected records
     *
     */
    function reorder($tablename,$k, $where='' ) {
        /*
         $query = 'SELECT '.$k.', ordering FROM #__'. $tablename
         . ' WHERE ordering >= 0' . ( $where ? ' AND '. $where : '' )
         . ' ORDER BY ordering, modified , created' // for clashes
         ;
         echo $query;
         $this->_db->setQuery( $query );
         if (!($orders = $this->_db->loadObjectList())) {
         $this->setError($this->_db->getErrorMsg());
         return false;
         }
         // compact the ordering numbers
         for ($i=0, $n=count( $orders ); $i < $n; $i++) {
         if ($orders[$i]->ordering >= 0) {
         if ($orders[$i]->ordering != $i+1) {
         $orders[$i]->ordering = $i+1;
         $query = 'UPDATE #__'.$tablename
         . ' SET ordering = '. (int)$orders[$i]->ordering
         . ' WHERE '. $k .' = '. $this->_db->Quote($orders[$i]->$k)
         ;
         $this->_db->setQuery( $query);
         //TODO remove this comment$this->_db->query();
         echo $query;
         }
         }
         }
         */
        return true;
    }

    function isChild($table_name,$child_id,$parent_id) {
        $ids = $this->getChildrenIDs($table_name,$parent_id);
        return in_array($child_id,$ids);
    }

}
?>