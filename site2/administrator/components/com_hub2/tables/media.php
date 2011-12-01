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
 * Media Table
 * @author anurag
 *
 */

class Hub2TableMedia extends Hub2Table {
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
     * @var VARCHAR
     */
    var $title=null;

    /**
     *
     * @var VARCHAR
     */
    var $description=null;

    /**
     *
     * @var INT
     */
    var $size=null;

    /**
     *
     * @var INT
     */
    var $width=null;

    /**
     *
     * @var INT
     */
    var $height=null;

    /**
     *
     * @var INT
     */
    var $image_type=null;

    /**
     *
     * @var INT
     */
    var $type=null;

    /**
     *
     * @var VARCHAR
     */
    var $mime=null;

    /**
     *
     * @var INT
     */
    var $folder_id = null;

    /**
     *
     * @var VARCHAR
     */
    var $thumbnail_relative=null;

    /**
     *
     * @var VARCHAR
     */
    var $publish_url = null;

    /**
     * one of thumb, small, medium, large, full
     * @var unknown_type
     */
    var $image_size_desc = null;

    /**
     * @var unknown_type
     */
    var $parent_media_id = null;

    /**
     *
     * @var INT
     */
    var $resized_image = null;

    var $checked_out = null;

    var $checked_out_time = null;

    var $created_by = null;

    var $created = null;

    var $user_uploaded = null;

    var $view_publish_url=null;

    var $view_thumbnail_url=null;

    // The constructor is called by the instantiation
    function __construct( &$database ) {
        parent::__construct( '#__hub2_media', 'id', $database );
    }

    function updateCreatedBy($item_id,$user_id) {
        $db = $this->getDBO();
        $query = 'UPDATE #__hub2_media SET created_by = '.$user_id.' WHERE id = '.$item_id;
        $db->setQuery($query);
        $result = $db->query();
    }

    function updateModifiedBy($item_id,$user_id) {
        $now = new JDate();
        $db = $this->getDBO();
        $query = 'UPDATE #__hub2_media SET modified=\''.$now->toMySQL().'\', modified_by = '.
        $user_id.' WHERE id = '.$item_id;
        $db->setQuery($query);
        $result = $db->query();
    }
}
?>