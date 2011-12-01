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

class Hub2DataModelPoll extends Hub2DataModel {

    /**
     * Constructor
     *
     */
    function __construct($config = array()) {
        $this->_table = '#__hub2_polls';
        parent::__construct($config);
    }

    /**
     * @return array of User names and IDs
     */
    function getList() {
        $db = $this->getDBO();
        $query = 'SELECT title, head_id FROM #__hub2_polls where 1';
        $db->setQuery($query);
        $result = $db->loadObjectList();
        return $result;
    }
}
