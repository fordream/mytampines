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
 * Media Folder Table
 * @author anurag
 *
 */

class Hub2TableFolder extends Hub2Table {
    /**
     *
     * @var INT
     */
    var $id=null;

    /**
     *
     * @var VARCHAR
     */
    var $name=null;

    /**
     *
     * @var VARCHAR
     */
    var $path_relative=null;

    /**
     *
     * @var INT
     */
    var $size=null;

    /**
     *
     * @var INT
     */
    var $files=null;

    /**
     *
     * @var INT
     */
    var $folders=null;

    /**
     *
     * @var INT
     */
    var $parent_id = null;

    /**
     *
     * @var INT
     */
    var $hidden_folder = null;

    var $rgt = null;

    var $lft = null;

    var $checked_out = null;

    var $checked_out_time = null;

    var $level = null;

    // The constructor is called by the instantiation
    function __construct( &$database ) {
        parent::__construct( '#__hub2_media_folders', 'id', $database );
    }
}
?>