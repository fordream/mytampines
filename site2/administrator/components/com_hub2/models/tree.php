<?php
/**
 * @version     $Id: $
 * @package     com_hub2
 * @copyright   (C) 2010 HyperLocalizer Pty Ltd. All rights reserved.
 * @license     HyperLocalizer proprietary.
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
require_once('model.php');
jximport( 'jxtended.database.query' );

/**
 * Tree data model
 *
 */
class Hub2ModelTree extends Hub2Model {

    function _getListQuery( $filters, $resolveFKs=false ) {
        $output = array();

        $db = &JFactory::getDBO();
        // generate the total count
        $table_name = $filters['table_name'];
        $rootnode = $filters['rootnode'];

        $qb = new JXQuery;

        $qb->select('*');

        $qb->from('#__'.$table_name);

        if ($rootnode !==0) {
            $db->setQuery('select lft,rgt from #__'.$table_name.
                ' WHERE id='.$rootnode);
            $result = $db->loadAssoc();
            $qb->where("lft BETWEEN {$result['lft']} AND {$result['rgt']}");
        }
        if (trim($filters['search']) !== '') {
            $qb->where("name LIKE '%{$filters['search']}%'");
        }
        if ($filters['level'] && (int)$filters['level'] > 0) {
            $qb->where("level <= {$filters['level']}");
        }
        if ($filters['exclude_node_and_children']) {
            $db->setQuery('select lft,rgt from #__'.$table_name.
                ' WHERE id='.$filters['exclude_node_and_children']);
            $result = $db->loadAssoc();
            $qb->where("lft < {$result['lft']} OR lft > {$result['rgt']}");
        }

        $qb->order('lft ASC');

        return $qb;
    }

    function rebuild($table_name) {
        $left = 1;
        $db = &JFactory::getDBO();
        // get all root nodes
        $db->setQuery('select id from #__'.$table_name.
                ' WHERE parent_id=0');
        $rows = $db->loadAssocList();
        foreach ($rows as $row) {
            // rebuild the tree for each root node
            $parent = $row['id'];
            $left = $this->rebuild_tree($table_name,$parent,$left,0);
        }
    }

    function rebuild_tree($table_name,$parent, $left, $level=0) {

        // the right value of this node is the left value + 1
        $db = &JFactory::getDBO();

        $right = $left+1;

        // get all children of this node
        $db->setQuery('select id from #__'.$table_name.
                ' WHERE parent_id='.$parent);
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

}
?>