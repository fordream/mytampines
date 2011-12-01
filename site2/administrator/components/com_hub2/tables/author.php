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
class Hub2TableAuthor extends Hub2Table {

    /**
     * ID
     * @var Int
     */
    var $id = null;

    /**
     * Name of the Author
     * @var varchar
     */
    var $fullname = null;

    /**
     * Alias of the author
     * @var varchar
     */
    var $alias = null;

    /**
     * Body of the author
     * @var varchar
     */
    var $body = null;

    /**
     * Email of the author
     * @var varchar
     */
    var $email = null;

    /**
     * Position of the author
     * @var varchar
     */
    var $position = null;

    /**
     * Media of the author
     * @var varchar
     */
    var $media = null;

    /**
     * @var int
     */
    var $published = null;

    /**
    * @var INT
     */
    var $user_id = null;

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
        parent::__construct( '#__hub2_authors', 'id', $db );
    }

}
