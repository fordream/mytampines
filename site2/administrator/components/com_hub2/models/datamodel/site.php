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
require_once( 'category.php' );

class Hub2DataModelSite extends Hub2DataModel {

    /**
     * Constructor
     *
     */
    function __construct($config = array()) {
        $this->_table = '#__hub2_sites';
        parent::__construct($config);
    }
    /**
     * @return array of Objects (name,id)
     */
    function &getSites() {
        $this->_db->setQuery('select name,id from #__hub2_sites where disabled <> 1 order by name');

        $options=$this->_db->loadObjectList();

        return $options;
    }

    function &getAllSites() {
        $this->_db->setQuery('select * from #__hub2_sites where disabled <> 1 order by name');
        $options = $this->_db->loadObjectList();
        return $options;
    }

    function _getListQuery( $options, $resolveFKs=false) {

        $qb = new JXQuery;

        // - select -
        $qb->select( array_key_exists('select', $options) ? $options['select'] : 's.*' );

        // - from -
        $qb->from( '#__hub2_sites AS s' );

        $qb->where( 'disabled=0' ); // do not show disabled sites


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
        // filter by a particular template filter
        if (array_key_exists('template_id',$options)) {
            if (((int)$options['template_id'] > 0))  {
                $qb->where('s.template_id='.$options['template_id']);
            }
        }
        return $qb;
    }

    /**
     * @return array of Region IDs
     */
    function &getRegionIDsForSite($site_id) {
        $this->_db->setQuery(
        'select region_id as id from #__hub2_sites_region_relations where site_id='.$site_id);

        $option=$this->_db->loadResultArray();

        return $option;
    }

    /**
     * @return array of Objects (name,id)
     */
    function &getRegionsForSite($site_id) {
        $select = 'select name,id from #__hub2_region r';
        $join = ' LEFT JOIN #__hub2_sites_region_relations sr ON r.id=sr.region_id';
        $where = ' where sr.site_id='.$site_id;
        $this->_db->setQuery($select.$join.$where);

        $option=$this->_db->loadObjectList();

        return $option;
    }

    /**
     * Returns list of template sites
     * @return Object (name,id attributes)
     */
    function getTemplates() {
        $this->_db->setQuery(
            "Select name,id from #__hub2_sites where is_template=1 and disabled=0");
        return $this->_db->loadObjectList();
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

    /**
     * returns the count of sites associated with this template
     * @param $template_id number >= 0
     */
    function getSiteCountForTemplate($template_id) {
        $this->_db->setQuery(
        "select count(*) from #__hub2_sites where disabled=0 and template_id={$template_id}");
        return $this->_db->loadResult();
    }
    /**
     * disables a site
     * @param $id
     */
    function disable($id) {
        // need to ensure it is not a template site and no sites are its template
        return $this->_db->Execute("update #__hub2_sites set disabled=1 where id={$id}");
    }

    function getKeysForSite() {
        $this->_db->setQuery("select pkey1,pkey2 from #__hub2_sites LIMIT 1");
        return $this->_db->loadAssoc();
    }

    // Get local site definitions
    function getLocalSiteDetails() {
        static $details = null;
        if (!$details) {
            $this->_db->setQuery("select * from #__hub2_sites LIMIT 1");
            $details = $this->_db->loadAssoc();
        }
        return $details;
    }

    /**
     * @return array of Category IDs
     */
    function &getCategoryIDsForSite($site_id) {
        $this->_db->setQuery(
        'select category_id as id from #__hub2_sites_category_relations where site_id='.$site_id);

        $option=$this->_db->loadResultArray();

        return $option;
    }
    /**
     * @return array of Postcode IDs
     */
    function &getPostcodeIDsForSite($site_id) {
        $this->_db->setQuery(
        'select postcode_id as id from #__hub2_sites_postcode_relations where site_id='.$site_id);

        $option=$this->_db->loadResultArray();

        return $option;
    }

    function saveSiteRegions($id, $regions) {
        $result = true;
        // delete old connections if any
        if (!$this->_db->Execute("DELETE FROM #__hub2_sites_region_relations
                                    where site_id={$id}")) {
            $result = false;
        }
        foreach ($regions as $region_id) {
            if (!$this->_db->Execute("REPLACE INTO #__hub2_sites_region_relations
                                        (site_id,region_id)
                                        VALUES ({$id},{$region_id})")) {
                $result = false;
            }
        }
        return $result;
    }

    function saveSitePostcodes($id, $postcodes) {
        $result = true;
        // delete old connections if any
        if (!$this->_db->Execute("DELETE FROM #__hub2_sites_postcode_relations
                                    where site_id={$id}")) {
            $result = false;
        }
        foreach ($postcodes as $postcode_id) {
            if(!$this->_db->Execute("REPLACE INTO #__hub2_sites_postcode_relations
                                        (site_id,postcode_id)
                                        VALUES ({$id},{$postcode_id})")) {
                $result = false;
            }
        }
        return $result;
    }

    function saveSiteCategories($id, $categories) {
        $result = true;
        // delete old connections if any
        if (!$this->_db->Execute("DELETE FROM #__hub2_sites_category_relations
                                    where site_id={$id}")) {
            $result = false;
        }
        foreach ($categories as $category_id) {
            if (!$this->_db->Execute("REPLACE INTO #__hub2_sites_category_relations
                                        (site_id,category_id)
                                        VALUES ({$id},{$category_id})")) {
                $result = false;
            }
        }
        return $result;
    }

    function getDetails($ids) {
        if (count($ids) > 0) {
            $tids = implode(',',$ids);
            $sql = "SELECT * from #__hub2_sites where id IN ({$tids})";
            $this->_db->setQuery($sql);
            return $this->_db->loadObjectList('id');
        }
        return array();
    }

    function getDistinctLanguages() {
        $sql = "SELECT DISTINCT lang from #__hub2_sites";
        $this->_db->setQuery($sql);
        return $this->_db->loadResultArray();
    }

    function getParentsPath($categories=array()) {
        $cat = new Hub2DataModelCategory();
        $parents = array();
        if(! empty($categories) ) {
            foreach($categories as $k=>$v) {
                $parents[] = $cat->getParentId($v);
            }
        }
        $s = 1;
        foreach ($parents as $parent) {
            if ($parent['parent_id'] != 0) { // no need to check root nodes
                if (!in_array($parent['parent_id'],$categories)) {
                    $s = 0; // the parent node in not select
                }
            }
        }
        /*
        $s = 0;
        $parents = array_reverse ($parents);
        $m = count($parents)-1;
        foreach($parents as $k=>$v3) {
            if( $v3['parent_id'] == 0 && $m == $k) {
                $s++;
            }
        }*/
        return $s;
    }


}
