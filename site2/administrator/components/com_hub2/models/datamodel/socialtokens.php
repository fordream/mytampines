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

class Hub2DataModelSocialTokens extends Hub2DataModel {
    /**
     * Constructor
     *
     */
    function __construct($config = array()) {
        $this->_table = '#__hub2_social_tokens';
        parent::__construct($config);
    }
    /**
     * @return array of Objects (name,id)
     */
    function &getTags() {

        $this->_db->setQuery('select id, url from #__hub2_social_tokens order by name');

        $options=$this->_db->loadObjectList();

        return $options;
    }

    function _getListQuery( $options ) {

        $select = array_key_exists('select', $options) ? $options['select'] : 's.*, sites.name';
        $exclude_ids    = array_key_exists('excludeids', $options) ? $options['excludeids'] : null;

        $qb = new JXQuery;

        // - select -
        $qb->select($select);

        // - from -
        $qb->from( '#__hub2_social_tokens AS s LEFT JOIN
         	#__hub2_sites AS sites ON s.site_id = sites.id' );

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
                $qb->where( 's.url LIKE '.$this->_db->Quote( '%'.$search.'%' ) );
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
        return $this->_db->Execute("delete from #__hub2_social_tokens where id={$id}");
    }

    function checkTheSite_id_and_key_exists($values) {
        $media_type = $values['media_type'];
        $site_id = $values['site_id'];
        $this->_db->setQuery("select media_type, site_id from
        	#__hub2_social_tokens WHERE media_type
        	LIKE ".$this->_db->Quote($media_type)."
        	AND site_id=".$site_id );
        $this->_db->query();
        return $this->_db->getNumRows();
    }

    function getSiteItemByType($type) {
        $this->_db->setQuery('select metadata from
        	#__hub2_social_tokens where media_type LIKE "'.$type.'" ');
        return $this->_db->loadObject();
    }

    function getSiteUrl($type) {
        $this->_db->setQuery('select url from
        	#__hub2_social_tokens where media_type LIKE "'.$type.'" ');
        return $this->_db->loadObject();
    }

    /**
     * Delete all social tokens for a given site
     * @param $site_id int The ID of the site whose values are to be deleted
     * @return boolean true on success
     */
    function deleteSocialTokensForSite($site_id) {
        $db = $this->getDBO();
        $result = $db->Execute("DELETE FROM #__hub2_social_tokens WHERE site_id=".$site_id);
        return ($result !== false);
    }

}