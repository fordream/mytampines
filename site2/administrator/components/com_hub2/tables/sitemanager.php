<?php
/**
 * @version     $Id: $
 * @package     com_hub2
 * @copyright   (C) 2010 HyperLocalizer Pty Ltd. All rights reserved.
 * @license     HyperLocalizer proprietary.
 */
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
require_once('hub2.php');
/**
 * Site Manager Table
 *
 */
class Hub2TableSiteManager extends Hub2Table {

    /**
     * ID
     * @var Int
     */
    var $id = null;

    /**
     * Name of the Site
     * @var varchar
     */
    var $name = null;

    /**
     * URL of the site
     * @var varchar
     */
    var $url = null;

    /**
     * DB host name for
     * @var varchar
     */
    var $dbhost = null;

    /**
     * DB name
     * @var varchar
     */
    var $dbname = null;

    /**
     * DB user name
     * @var varchar
     */
    var $dbuser = null;

    /**
     * DB Password
     * @var varchar
     */
    var $dbpassword = null;

    /**
     * DB prefix
     * @var varchar
     */
    var $dbprefix = null;

    /**
     * @var Int
     */
    var $checked_out = null;

    /**
     * @var Datetime
     */
    var $checked_out_time = null;

    /**
     * @var Int
     */
    var $created_by = null;

    /**
     * @var Datetime
     */
    var $created = null;

    /**
     * @var Int
     */
    var $modified_by = null;

    /**
     * @var Datetime
     */
    var $modified = null;

    /**
     * @param database A database connector object
     */
    function __construct( &$db ) {
        parent::__construct( '#__hub2_manager_sites', 'id', $db );
    }

}
