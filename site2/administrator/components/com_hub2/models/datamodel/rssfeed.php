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

class Hub2DataModelRssFeed extends Hub2DataModel {
    /**
     * Constructor
     *
     */
    function __construct($config = array()) {
        $this->_table = '#__hub2_rss_feeds';
        parent::__construct($config);
    }

    /**
     * filters implemented
     * folder_id
     * select
     * search_tag
     * search_filename
     * @param $filters
     * @param $resolveFKs
     */
    function _getListQuery( $filters, $resolveFKs = false ) {
        $db = $this->_db;

        $qb = new JXQuery;

        if (trim($filters['select']) !== '') {
            $qb->select($filters['select']);
        } else {
            $qb->select('t.*');
        }

        $qb->from('#__hub2_rss_feeds t');

        if (trim($filters['search_text']) !== '') {
            $qb->where("feed_id LIKE '%{$filters['search_text']}%'");
        }

        return $qb;
    }

    /**
     *
     * @param $id
     * @return array
     */
    function getRegionsForObject($id) {
        $sql = "SELECT region_ids as id from #__hub2_rss_feeds where id={$id}";
        $this->_db->setQuery($sql);
        $rs = $this->_db->loadResult();

        return explode(',',$rs);
    }

    /**
     *
     * @param $id
     * @return array
     */
    function getCategoriesForObject($id) {
        $sql = "SELECT category_ids as id from #__hub2_rss_feeds where id={$id}";
        $this->_db->setQuery($sql);
        $rs = $this->_db->loadResult();

        return explode(',',$rs);
    }
}
