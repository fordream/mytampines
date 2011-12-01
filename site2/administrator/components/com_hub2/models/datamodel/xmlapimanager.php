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

class Hub2DataModelXmlapimanager extends Hub2DataModel {
    /**
     * Constructor
     *
     */
    function __construct($config = array()) {
        $this->_table = '#__hub2_xmlapi';
        parent::__construct($config);
    }

    function _getListQuery( $options ) {

        $select = array_key_exists('select', $options) ? $options['select'] : 's.*';
        $exclude_ids    = array_key_exists('excludeids', $options) ? $options['excludeids'] : null;

        $qb = new JXQuery;

        // - select -
        $qb->select($select);

        // - from -
        $qb->from( '#__hub2_xmlapi AS s' );

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
                $qb->where( 's.metadata LIKE '.$this->_db->Quote( '%'.$search.'%' ) );
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
     * disables a site
     * @param $id
     */
    function remove($id) {
        // need to ensure tag is not attached to any items before deletion
        return $this->_db->Execute("delete from #__hub2_xmlapi where id={$id}");
    }

    function validateAPI($key=null, $date=null) {
        $query = "SELECT id FROM {$this->_table} WHERE key_desc LIKE '{$key}'
        	 AND exp_date > {$date} AND published = 1";
        $this->_db->setQuery( $query );
        return $this->_db->loadObject();
    }

}