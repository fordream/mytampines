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
 * Site Table
 *
 */
class Hub2TableSite extends Hub2Table {

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
     * QA URL of the site
     * @var varchar
     */
    var $qa_url = null;

    /**
     * QA URL of the site
     * @var varchar
     */
    var $hub2_url = null;

    /**
     * lang of site
     * @var varchar
     */
    var $lang = null;

    /**
     * ID
     * @var Int
     */
    var $globaluser = null;

    /**
     * DIR of the site
     * @var varchar
     */
    var $dirpath = null;

    /**
     * Params the site
     * @var text
     */
    var $params = null;

    /**
     * Private Key for communication
     * @var varchar
     */
    var $pkey1 = null;

    /**
     * Private Key 2 for communication
     * @var varchar
     */
    var $pkey2 = null;

    /**
     * DB host name for Master DB
     * @var varchar
     */
    var $dbhost = null;

    /**
     * DB name for Master DB
     * @var varchar
     */
    var $dbname = null;

    /**
     * DB user name for Master DB
     * @var varchar
     */
    var $dbuser = null;

    /**
     * DB Password for Master DB
     * @var varchar
     */
    var $dbpassword = null;

    /**
     * DB prefix
     * @var varchar
     */
    var $dbprefix = null;

    /**
     * DB host name for Slave DB
     * @var varchar
     */
    var $slave_dbhost = null;

    /**
     * DB name for Slave DB
     * @var varchar
     */
    var $slave_dbname = null;

    /**
     * DB user name for Slave DB
     * @var varchar
     */
    var $slave_dbuser = null;

    /**
     * DB Password for Slave DB
     * @var varchar
     */
    var $slave_dbpassword = null;

    /**
     * DB host name for shared DB
     * @var varchar
     */
    var $shared_dbhost = null;

    /**
     * DB name for shared DB
     * @var varchar
     */
    var $shared_dbname = null;

    /**
     * DB user name for shared DB
     * @var varchar
     */
    var $shared_dbuser = null;

    /**
     * DB Password for shared DB
     * @var varchar
     */
    var $shared_dbpassword = null;

    /**
     * Use webservices or local DB update
     * @var int
     */
    var $usewebservices = null;

    /**
     * @var BOOLEAN
     */
    var $is_template = null;

    /**
     * @var INT
     */
    var $template_id = null;

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
     * @var varchar
     */
    var $disabled = null;

    /**
     * @param database A database connector object
     */
    function __construct( &$db ) {
        parent::__construct( '#__hub2_sites', 'id', $db );
    }

}
