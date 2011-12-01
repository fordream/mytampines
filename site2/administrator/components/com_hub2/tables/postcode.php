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
 * Tag Table
 * @author joseph
 *
 */
class Hub2TablePostcode extends Hub2Table {
    /**
     * @var Int
     */
    var $id = null;
    /**
     * @var varchar
     */
    var $postcode = null;
    /**
     * @var varchar
     */
    var $name = null;
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
    var $comments = null;
    /**
     * @var varchar
     */
    var $metakey = null;
    /**
     * @var varchar
     */
    var $metadesc = null;
    /**
     * @var varchar
     */
    var $params = null;

    /**
     * @param database A database connector object
     */
    function __construct( &$db ) {
        parent::__construct( '#__hub2_postcodes', 'id', $db );
    }
}
