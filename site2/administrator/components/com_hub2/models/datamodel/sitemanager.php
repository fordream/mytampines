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

class Hub2DataModelSiteManager extends Hub2DataModel {

    /**
     * Constructor
     *
     */
    function __construct($config = array()) {
        $this->_table = '#__hub2_manager_sites';
        parent::__construct($config);
    }
    /**
     * @return array of Objects (name,id)
     */
    function &getSites() {
        $this->_db->setQuery('select name,id from #__hub2_manager_sites');

        $options=$this->_db->loadObjectList();

        return $options;
    }

    function _getListQuery( $options, $resolveFKs=false) {

        $qb = new JXQuery;

        // - select -
        $qb->select( array_key_exists('select', $options) ? $options['select'] : 's.*' );

        // - from -
        $qb->from( '#__hub2_manager_sites AS s' );

        if (array_key_exists('exclude_ids', $options) && $options['exclude_ids']) {
            $exclude_ids = $options['exclude_ids'];
            $qb->where( "s.id NOT IN (".implode(',',$exclude_ids).")");
        }

        // options
        $search = array_key_exists('search', $options) ? $options['search'] : '';
        if (trim($search) !== '')
        {
            if (strpos( $search, 'id:' ) === 0) {
                $qb->where( 's.id = '.(int)substr( $search, 3 ) );
            } else {
                // note: need to be within parantheses else will break rest of the query
                $qb->where( '(s.name LIKE '.$this->_db->Quote( '%'.$search.'%' ) .
                            ' OR s.url LIKE '.$this->_db->Quote( '%'.$search.'%' ).')');
            }
        }

        // - ordering -
        $orderCol   = array_key_exists('orderCol', $options) ? $options['orderCol'] : '';
        $orderDirn = array_key_exists('orderDirn', $options) ? $options['orderDirn'] : '';
        if (strcasecmp($orderDirn, 'desc') != 0) {
            $orderDirn = '';
        }
        if ($orderCol) {
            $qb->order( $orderCol . ' ' . $orderDirn );
        }

        // is template filter
        if (array_key_exists('is_template',$options)) {
            if (!((int)$options['is_template'] == -1))  {
                $qb->where('s.is_template='.$options['is_template']);
            }
        }
        return $qb;
    }

    /**
     * Assumes valid data to save
     * @param $values
     * @param $tableObject
     */
    function save($values, &$tableObject) {

        $result     = $tableObject->save($values);

        if ($result) {
            $result = $tableObject->id;
        } else {
            $result = JError::raiseWarning( 500, $tableObject->getError() );
        }
        return $result;
    }

    function getDetails($ids) {
        if (count($ids) > 0) {
            $tids = implode(',',$ids);
            $sql = "SELECT * from #__hub2_manager_sites where id IN ({$tids})";
            $this->_db->setQuery($sql);
            return $this->_db->loadObjectList('id');
        }
        return array();
    }

}
