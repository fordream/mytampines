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
 * SiteParams Table
 *
 */
class Hub2TableSiteParams extends Hub2Table {

    /**
     * ID
     * @var Int
     */
    var $id = null;

    /**
     * Name of the parameter
     * @var varchar
     */
    var $name = null;

    /**
     * description for the parameter
     * @var varchar
     */
    var $description = null;

    /**
     * @var Int
     */
    var $template_id = null;

    /**
     * @var Int
     */
    var $paramtype = null;

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
        parent::__construct( '#__hub2_siteparams', 'id', $db );
    }

}
