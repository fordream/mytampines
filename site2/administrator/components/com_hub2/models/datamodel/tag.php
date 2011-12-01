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

class Hub2DataModelTag extends Hub2DataModel {

    /**
     * Constructor
     *
     */
    function __construct($config = array()) {
        $this->_table = '#__hub2_tags';
        parent::__construct($config);
    }

    /**
     * @return array of Objects (name,id)
     */
    function &getTags() {
        $this->_db->setQuery('select name,id from #__hub2_tags order by name');

        $options=$this->_db->loadObjectList();

        return $options;
    }

    function _getListQuery( $options ) {

        $select         = array_key_exists('select', $options) ? $options['select'] : 's.*';
        $exclude_ids    = array_key_exists('excludeids', $options) ? $options['excludeids'] : null;

        $qb = new JXQuery;

        // - select -
        $qb->select($select);

        // - from -
        $qb->from( '#__hub2_tags AS s' );

        if ($exclude_ids) {
            $qb->where( "id NOT IN (".implode(',',$exclude_ids).")");
        }

        // options
        $search = array_key_exists('search', $options) ? $options['search'] : '';
        if (trim($search) !== '')
        {
            if (strpos( $search, 'id:' ) === 0) {
                $qb->where( 's.id = '.(int)substr( $search, 3 ) );
            } else {
                // note: need to be within parantheses else will break rest of the query
                $qb->where( 's.name LIKE '.$this->_db->Quote( '%'.$search.'%' ) );
            }
        }

        // - ordering -
        $orderCol   = array_key_exists('orderCol', $options) ? $options['orderCol'] : null;
        $orderDirn =  array_key_exists('orderDirn', $options) ? $options['orderDirn'] : '';
        if(strcasecmp($orderDirn, 'desc') != 0) {
            $orderDirn = '';
        }
        if ($orderCol) {
            $qb->order( $orderCol . ' ' . $orderDirn );
        }

        return $qb;
    }

    /**
     * Assumes valid data to save
     * @param $values
     * @param $tableObject
     */
    function save($values, $tableObject) {

        $result     = $tableObject->save($values);

        if ($result) {
            $result = $tableObject->id;
        } else {
            $result = JError::raiseWarning( 500, $tableObject->getError() );
        }
        return $result;
    }

    /**
     * returns the count of items associated with this tag
     * @param $tag_id number >= 0
     */
    function getItemCountForTag($tag_id) {
        $this->_db->setQuery(
        "select count(*) from #__hub2_item_tag_relations where tag_id={$tag_id}");
        return $this->_db->loadResult();
    }
    /**
     * disables a site
     * @param $id
     */
    function remove($id) {
        // need to ensure tag is not attached to any items before deletion
        return $this->_db->Execute("delete from #__hub2_tags where id={$id}");
    }

    function getId($name) {
        $query = "SELECT id FROM {$this->_table} WHERE name='{$name}'";
        $this->_db->setQuery($query);
        return $this->_db->loadResult();
    }
}
