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

class Hub2DataModelPostcode extends Hub2DataModel {

    /**
     * Constructor
     *
     */
    function __construct($config = array()) {
        $this->_table = '#__hub2_postcodes';
        parent::__construct($config);
    }

    /**
     * @return array of Objects (name,id)
     */
    function &getPostcodes() {
        $this->_db->setQuery('select name,id from #__hub2_postcodes order by name');

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
        $qb->from( '#__hub2_postcodes AS s' );

        $qb->where( '1' ); // do not show disabled sites

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
     * returns the count of items associated with this postcode
     * @param $postcode_id number >= 0
     */
    function getItemCountForPostcode($postcode_id) {
        $this->_db->setQuery(
        "select count(*) from #__hub2_item_postcode_relations where postcode_id={$postcode_id}");
        return $this->_db->loadResult();
    }

    /**
     * returns the count of sites associated with this postcode
     * @param $postcode_id number >= 0
     */
    function getSiteCountForPostcode($postcode_id) {
        $this->_db->setQuery(
        "select count(*) from #__hub2_sites_postcode_relations where postcode_id={$postcode_id}");
        return $this->_db->loadResult();
    }
    /**
     * disables a site
     * @param $id
     */
    function remove($id) {
        // need to ensure postcode is not attached to any items before deletion
        return $this->_db->Execute("delete from #__hub2_postcodes where id={$id}");
    }

    /**
     * @return array of Objects (name,id)
     */
    function &getSitesForPostcode($postcode_id) {
        $select = 'select * from #__hub2_sites s LEFT JOIN #__hub2_sites_postcode_relations sr';
        $this->_db->setQuery($select.
        ' ON s.id=sr.site_id where sr.postcode_id='.$postcode_id);

        $option=$this->_db->loadObjectList();

        return $option;
    }

    /**
     * @return array of Site IDs
     */
    function &getSiteIDsForPostcode($postcode_id) {
        $this->_db->setQuery('select site_id as id from #__hub2_sites_postcode_relations
                                where postcode_id='.$postcode_id);

        $option=$this->_db->loadResultArray();

        return $option;
    }

    /**
     * deletes a site from postcode/neighbourhood
     * @param $postcode_id number >= 0
     * @param $site_id number >= 0
     */
    function removeSiteFromPostcode($site_id,$postcode_id) {
        return $this->_db->Execute("DELETE FROM #__hub2_sites_postcode_relations
                                        WHERE site_id={$site_id} AND
                                        postcode_id={$postcode_id}");
    }

    /**
     * Adds a site to a postcode/neighbourhood
     * @param $postcode_id number >= 0
     * @param $site_id number >= 0
     */
    function addSiteToPostcode($site_id,$postcode_id) {
        return $this->_db->Execute("REPLACE INTO #__hub2_sites_postcode_relations
                                        (site_id,postcode_id)
                                        VALUES ({$site_id},{$postcode_id})");
    }

    function getId($name) {
        $query = "SELECT id FROM {$this->_table} WHERE name='{$name}'";
        $this->_db->setQuery($query);
        return $this->_db->loadResult();
    }

    /**
     *
     * @param array $fields - array of field names
     * @return array Assoc array of results
     */
    function getAllFieldValues($fields=array()) {
        $fieldlist = implode(',',$fields);
        $query = "SELECT {$fieldlist} FROM {$this->_table}";
        $this->_db->setQuery($query);
        return $this->_db->loadAssocList();
    }
}
