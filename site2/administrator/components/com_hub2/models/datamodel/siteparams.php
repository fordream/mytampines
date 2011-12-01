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

class Hub2DataModelSiteParams extends Hub2DataModel {

    /**
     * Constructor
     *
     */
    function __construct($config = array()) {
        $this->_table = '#__hub2_siteparams';
        parent::__construct($config);
    }

    function _getListQuery( $options) {

        $select= array_key_exists('select', $options) ? $options['select'] : 's.*';

        $qb = new JXQuery;

        // - select -
        $qb->select( $select );

        // - from -
        $qb->from( '#__hub2_siteparams AS s' );

        // options
        $search = array_key_exists('search', $options) ? $options['search'] : '';
        if (trim($search) !== '')
        {
            if (strpos( $search, 'id:' ) === 0) {
                $qb->where( 's.id = '.(int)substr( $search, 3 ) );
            } else {
                // note: need to be within parantheses else will break rest of the query
                $qb->where( '(s.name LIKE '.$this->_db->Quote( '%'.$search.'%' ) .')');
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
     * Assumes valid data to save
     * @param siteparam_id id of the site parameter
     * @param $values an array with index as site id, each item has an index 'value'
     * with the actual value
     */
    function saveSiteParamValues($siteparam_id, $values) {
        $db = $this->getDBO();
        foreach ($values as $siteId=>$val) {
            $result = $db->Execute("Replace into #__hub2_siteparams_values
            (site_id, siteparam_id, value)
            VALUES ('{$siteId}','{$siteparam_id}',".$db->Quote($val['value']).")");
            if (!$result) {
                return JError::raiseWarning(0,$db->getErrorMsg());
            }
        }
        return true;
    }

    /**
     * Delete all site parameter values for a given site
     * @param $site_id int The ID of the site whose values are to be deleted
     * @return boolean true on success
     */
    function deleteSiteParamValuesForSite($site_id) {
        $db = $this->getDBO();
        $result = $db->Execute("DELETE FROM #__hub2_siteparams_values WHERE site_id=".$site_id);
        return ($result !== false);
    }

    /**
     * deletes a site parameter
     * @param $id - the id of the parameter to delete
     */
    function remove($id,&$tableObject) {

        $result = $tableObject->delete($id);
        if (!$result) {
            $this->setError($tableObject->getError());
        }
        return $result;
    }

    /**
     * Returns a list of the site parameters
     *
     */
    function getList() {
        $db = $this->getDBO();
        $query = 'SELECT * FROM #__hub2_siteparams';
        $db->setQuery($query);
        $result = $db->loadObjectList();
        return $result;
    }

    /**
     * Returns the number of values for a given parameter
     * @param $id the parameter id
     */
    function getValueCountForParameter($id) {
        $this->_db->setQuery("select count(*) from #__hub2_siteparams_values where
        siteparam_id={$id}");
        $count = $this->_db->loadResult();
        return $count;
    }

    /**
     * Assumes valid data to save
     * @param int site_id id of the site
     * @param array values an array with index as param id with the actual value
     * @return true or an JError object
     */
    function saveSiteParamValuesForASite($site_id, $values) {
        $db = $this->getDBO();
        foreach ($values as $siteparam_id=>$val) {
            $result = $db->Execute("Replace into #__hub2_siteparams_values
            (site_id, siteparam_id, value)
            VALUES ('{$site_id}','{$siteparam_id}',".$db->Quote($val).")");
            if (!$result) {
                return JError::raiseWarning(0,$db->getErrorMsg());
            }
        }
        return true;
    }
}
