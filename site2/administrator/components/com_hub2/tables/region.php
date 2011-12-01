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
 * Region Table
 * @author anurag
 *
 */
class Hub2TableRegion extends Hub2Table {
    /**
     * @var Int
     */
    var $id = null;
    /**
     * @var varchar
     */
    var $name = null;
    /**
     * @var Int
     */
    var $parent_id = null;

    /**
     * @var Int
     */
    var $lft = null;

    /**
     * @var Int
     */
    var $rgt = null;

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
        parent::__construct( '#__hub2_region', 'id', $db );
    }

}
