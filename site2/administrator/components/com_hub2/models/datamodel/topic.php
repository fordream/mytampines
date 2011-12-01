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

class Hub2DataModelTopic extends Hub2DataModelTree {

    var $_table_short = "hub2_topics";

    /**
     * Constructor
     *
     */
    function __construct($config = array()) {
        $this->_table = '#__hub2_topics';
        parent::__construct($config);
    }

    /**
     * @return array of Objects (name,id)
     */
    function &getTopics() {
        $this->_db->setQuery('select name,id,level from #__'.
        $this->_table_short.' order by lft asc');

        $options=$this->_db->loadObjectList();

        return $options;
    }



    function _getListQuery($filters,$resolveFKs = false) {
        $filters['table_name'] = $this->_table_short;
        if (!array_key_exists('rootnode',$filters)) {
            $filters['rootnode'] = 0;
        }
        return parent::_getListQuery($filters,$resolveFKs);
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
            $this->rebuild($this->_table_short);
            $result = $tableObject->id;
        } else {
            $result = JError::raiseWarning( 500, $tableObject->getError() );
        }
        return $result;

    }

    function getPossibleParents($node) {
        $filters['table_name'] = $this->_table_short;
        $filters['rootnode'] = 0;
        $filters['exclude_node_and_children'] = $node;
        $query = $this->_getListQuery($filters,$resolveFKs);
        $sql            = $query->toString();
        $result         = $this->_getList($sql, 0,0);
        return $result;
    }

    function  delete($id) {
        $db = &$this->_db;
        // check if is a parent of another topic
        $db->setQuery("select count(*) from #__{$this->_table_short} where parent_id={$id}");
        $num = $db->loadResult();
        if ($num == 0) {
            $db->Execute("delete from #__{$this->_table_short} where id={$id}");
            $this->rebuild($this->_table_short);
        } else {
            $result = JError::raiseWarning(500,
                            "Cannot delete topic with ID {$id} since it has sub-topics.");
        }
        return $result;
    }


    function getChildren($id) {
        $db = &$this->_db;
        // check if content associated
        $db->setQuery("select * from #__hub2_topics where parent_id={$id} order by lft");
        return $db->loadObjectList('id');
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
            $db->setQuery('SELECT id,name FROM #__'.$table_name.
            ' WHERE lft <= '.$row['lft'].' AND rgt >= '.$row['rgt'].' ORDER BY lft ASC');
            self::$pathCache[$key] = $db->loadObjectList();
        } else {
            self::$pathCache[$key] = array();
        }
        return self::$pathCache[$key];
    }

    /**
     * returns the count of items associated with this topic
     * @param $topic_id number >= 0
     * @return int 0 or greater
     */
    function getItemCountForTopic($topic_id) {
        $this->_db->setQuery(
        "select count(*) from #__hub2_item_topic_relations where topic_id={$topic_id}");
        return $this->_db->loadResult();
    }

    function rebuildOnExternalSave() {
        return $this->rebuild("hub2_topics",false);
    }

    function getId($name) {
        $query = "SELECT id FROM {$this->_table} WHERE name='{$name}'";
        $this->_db->setQuery($query);
        return $this->_db->loadResult();
    }

}
