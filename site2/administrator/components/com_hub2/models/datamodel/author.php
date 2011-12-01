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

class Hub2DataModelAuthor extends Hub2DataModel {

    /**
     * Constructor
     */
    function __construct($config = array()) {
        $this->_table = '#__hub2_authors';
        parent::__construct($config);
    }

    function _getListQuery( $options) {

        $select= array_key_exists('select', $options) ? $options['select'] : 's.*';

        $qb = new JXQuery;

        // - select -
        $qb->select( $select );

        // - from -
        $qb->from( '#__hub2_authors AS s' );

        // options
        $search = array_key_exists('search', $options) ? $options['search'] : '';
        if (trim($search) !== '')
        {
            if (strpos( $search, 'id:' ) === 0) {
                $qb->where( 's.id = '.(int)substr( $search, 3 ) );
            } else {
                // note: need to be within parantheses else will break rest of the query
                $qb->where( '(s.fullname LIKE '.$this->_db->Quote( '%'.$search.'%' ) .
                            ' OR s.alias LIKE '.$this->_db->Quote( '%'.$search.'%' ).')');
            }
        }

        // - ordering -
        $orderCol   = array_key_exists('orderCol', $options) ? $options['orderCol'] : null;
        $orderDirn = array_key_exists('orderDirn', $options) ? $options['orderDirn'] : '';
        // orderDirn can only be asc (default) or desc
        if(strcasecmp($orderDirn, 'desc') != 0) {
            $orderDirn = '';
        }
        if ($orderCol) {
            $qb->order( $orderCol . ' ' . $orderDirn );
        }

        return $qb;
    }

    /**
     * @return array of User names and IDs
     */
    function &getUsers() {
        $this->_db->setQuery('select name,id from #__users
                                where 1');

        $option=$this->_db->loadObjectList();

        return $option;
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
     * returns the count of items associated with this author
     * @param $template_id number >= 0
     */
    function getItemCountForAuthor($id) {
        $db = &$this->_db;
        // check if content associated
        $db->setQuery("select count(*) from #__hub2_articles
                where author_id={$id}");
        return $db->loadResult();
    }

    /**
     * deletes an author
     * @param $id
     */
    function remove($id) {
        return $this->_db->Execute("delete from #__hub2_authors where id={$id}");
    }

    function getList() {
        $db = $this->getDBO();
        $query = 'SELECT * FROM #__hub2_authors';
        $db->setQuery($query);
        $result = $db->loadObjectList();
        return $result;
    }

    /**
     * Method to get generic site author to use for item submitted from site
     * via registered site user. It assumes that an author (and corr. user) is
     * set up by admin.
     * @return mixed id of author if exists. False otherwise
     */
    function getGenericSiteAuthorId() {
        $db = $this->getDBO();
        $query = 'SELECT * FROM #__hub2_authors WHERE params like ' .
        $db->Quote('%issiteuser=1%');
        $db->setQuery($query);
        $result = $db->loadObject();
        //print_r($result);
        if(!empty($result)) {
            return $result->id;
        }
        return false;
    }

    function getAuthorListById() {
        $db = $this->getDBO();
        $query = 'SELECT * FROM #__hub2_authors';
        $db->setQuery($query);
        $result = $db->loadAssocList('id');
        return $result;
    }
}
