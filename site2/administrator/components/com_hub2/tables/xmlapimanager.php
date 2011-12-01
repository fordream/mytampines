<?php
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
require_once('hub2.php');
/**
 * Social tokens Table
 *
 */
class Hub2TableXmlapimanager extends Hub2Table {

    /**
     * ID
     * @var Int
     */
    var $id = null;
    /**
     * Key_desc
     * @var varchar
     */
    var $key_desc = null;
    /**
     * exp_date
     * @var varchar
     */
    var $exp_date = null;
    /**
     * user_id
     * @var Int
     */
    var $user_id = null;
    /**
     * created
     * @var varchar
     */
    var $created = null;
    /**
     * created_by
     * @var varchar
     */
    var $created_by = null;
    /**
     * modified
     * @var varchar
     */
    var $modified = null;
    /**
     * modified_by
     * @var varchar
     */
    var $modified_by = null;
    /**
     * modified_by
     * @var varchar
     */
    var $published = null;
    /**
     * modified_by
     * @var varchar
     */
    var $metadata = null;

    /**
     * @param database A database connector object
     */
    function __construct( &$db ) {
        parent::__construct( '#__hub2_xmlapi', 'id', $db );
    }

}
