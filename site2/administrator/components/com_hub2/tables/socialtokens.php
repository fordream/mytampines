<?php

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
require_once('hub2.php');
/**
 * Social tokens Table
 *
 */
class Hub2TableSocialTokens extends Hub2Table {

    /**
     * ID
     * @var Int
     */
    var $id = null;

    /**
     * ID of the site
     * @var Int
     */
    var $site_id = null;

    /**
     * url of the site
     * @var Text
     */
    var $url = null;
    /**
     * url of the site
     * @var Varchar
     */
    var $media_type = null;
    /**
     * @var Datetime
     */
    var $created = null;
    /**
     * @var Int
     */
    var $created_by = null;
    /**
     * @var Datetime
     */
    var $modified = null;
    /**
     * @var Int
     */
    var $modified_by = null;
    /**
     * @var Int
     */
    var $checked_out = null;

    /**
     * @var Datetime
     */
    var $checked_out_time = null;
    /**
     * @var Text
     */
    var $metadata = null;

    /**
     * @param database A database connector object
     */
    function __construct( &$db ) {
        parent::__construct( '#__hub2_social_tokens', 'id', $db );
    }

}
